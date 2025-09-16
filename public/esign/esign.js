const pageNum = document.getElementById("page-num");//For displaying
const pageCount = document.getElementById("page-count");//For displaying
const pageNoInput = document.getElementById("pageNo");
const pdfCanvas = document.getElementById('pdf-canvas');
const overlayCanvas = document.getElementById('overlay-canvas');
const container = document.getElementById('pdf-container');
const pdfCtx = pdfCanvas.getContext('2d');
const overlayCtx = overlayCanvas.getContext('2d');
const prevPageBtn = document.getElementById("prev-page-btn");
const nextPageBtn = document.getElementById("next-page-btn");
const pdfControls = document.getElementById("pdf-controls");
const thumbnailContainer = document.getElementById("thumbnail-container");
const drawSignatureBtn = document.getElementById("draw-signature-btn");
const responseDiv = document.getElementById("serverResponse");
const spinner = document.getElementById("spinner");
// const eSignResult = document.getElementById("eSignResult");
const docPreviewContainer = document.querySelector("div.document-preview");

const esignPdfForm = document.getElementById("esignPdfForm");
const pdf_base64 = document.getElementById('pdf_base64');
const esigned_pdf_base64 = document.getElementById('esigned_pdf_base64');
const fileInput = document.getElementById('file-input');


var pdfDoc = null;
var currentPage = 1;
var startX, startY, isDrawing = false, currentX, currentY;
var originalPdfWidth, originalPdfHeight;

if(fileInput){
    fileInput.addEventListener('change', () => {
		file = fileInput.files[0];
		
		if(!file){
			return;
		}
		
		if (file?.type === 'application/pdf') {
            const fileURL = URL.createObjectURL(file);
            currentPage = 1;
            pageNoInput.value = 1;
            renderPDF(fileURL, parseInt(currentPage) || 1);
            pdfControls.style.display = "block";
            //container.style.display = "block";
            pdfCanvas.classList.remove("d-none");
            disableDownloadSignedDocBtn();				

            reader.readAsDataURL(file); // Triggers base64 conversion
        }
	});
}

prevPageBtn.addEventListener('click', () => {
    renderPreviousPage();
});

nextPageBtn.addEventListener('click', () => {
    renderNextPage();
});

function renderPreviousPage() {
    if (currentPage == 1) {
        return;
    }
    //const file = fileInput.files[0];
    if (pdfDoc) {
        --currentPage;
        if (currentPage > 0 && currentPage <= pdfDoc.numPages) {
            pageNoInput.value = currentPage;
            activateThumbNail(currentPage);
            renderPage(currentPage);
        }
    }
}

function renderNextPage() {
    if (currentPage >= pdfDoc.numPages) {
        return;
    }
    if (pdfDoc) {
        ++currentPage;
        if (currentPage > 0 && currentPage <= pdfDoc.numPages) {
            pageNoInput.value = currentPage;
            activateThumbNail(currentPage);
            renderPage(currentPage);
        }
    }
}

function renderPDF(source, pageNumber) {
    pdfjsLib.GlobalWorkerOptions.workerSrc = document.getElementById("pdf_worker_js").value;
    /*
    pdfjsLib.getDocument(url).promise.then(pdf => {
        
    });*/
    let loadingTask;

    if (source.startsWith('data:') || /^[A-Za-z0-9+/=]+$/.test(source)) {
        // Base64
        const byteCharacters = atob(source);
        const byteNumbers = new Uint8Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
            byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        loadingTask = pdfjsLib.getDocument({ data: byteNumbers });
    } else {
        // Assume URL
        loadingTask = pdfjsLib.getDocument(source);
    }

    loadingTask.promise.then(pdf => {
        pdfDoc = pdf;
        renderThumbnails(pdfDoc);
        pageCount.innerHTML = pdfDoc.numPages;
        pageNum.innerHTML = pageNumber;
        renderPage(pageNumber);
    });
}


//function to render thumbnails
async function renderThumbnails(pdfDoc) {
    thumbnailContainer.innerHTML = "";

    for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
        const page = await pdfDoc.getPage(pageNum);

        const thumbDiv = document.createElement("div");
        thumbDiv.className = "thumbnail";
        thumbDiv.setAttribute("data-page-no", pageNum);
        if (pageNum === currentPage) thumbDiv.classList.add("active");

        const scale = 0.25;
        const viewport = page.getViewport({ scale });

        const canvas = document.createElement("canvas");
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const context = canvas.getContext("2d");
        await page.render({ canvasContext: context, viewport }).promise;


        thumbDiv.appendChild(canvas);

        thumbDiv.addEventListener("click", () => {
            document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
            thumbDiv.classList.add("active");
            currentPage = pageNum;
            pageNoInput.value = pageNum;
            renderPage(pageNum);
        });

        let thumbnailHolder = document.createElement("div");
        thumbnailHolder.classList.add("thumbnail-holder");
        let pageSpan = document.createElement("span");
        pageSpan.classList.add("page-span");
        pageSpan.innerHTML = pageNum;

        thumbnailHolder.appendChild(pageSpan);
        thumbnailHolder.appendChild(thumbDiv);

        thumbnailContainer.appendChild(thumbnailHolder);
    }
}

//function to activate a particular thumbnail
function activateThumbNail(pageNum) {
    document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
    let thumbDiv = document.querySelector(`.thumbnail[data-page-no="${pageNum}"]`);
    thumbDiv.classList.add("active");
}

function renderPage(pageNumber) {
    pageNum.innerHTML = pageNumber;
    pdfDoc.getPage(pageNumber).then(page => {
        const containerWidth = document.querySelector(".document-preview").clientWidth;

        // Get original dimensions at scale 1.0
        const unscaledViewport = page.getViewport({ scale: 1.0 });
        originalPdfWidth = unscaledViewport.width;
        originalPdfHeight = unscaledViewport.height;

        // Calculate scale to fit width
        const scale = (containerWidth / originalPdfWidth) - 0.12;
        const viewport = page.getViewport({ scale });

        // Resize canvas
        pdfCanvas.width = overlayCanvas.width = viewport.width;
        pdfCanvas.height = overlayCanvas.height = viewport.height;

        overlayCanvas.style.left = pdfCanvas.offsetLeft + "px";
        overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);

        page.render({ canvasContext: pdfCtx, viewport });
    });
}

drawSignatureBtn.addEventListener("click", (e) => {
    let drawingMode = drawSignatureBtn.getAttribute("data-drawing-mode") === "true";
    toggleDrawSignatureBtn(!drawingMode);
    if (!drawingMode) {
        setDrawingMode();
    } else {
        removeDrawingMode();
    }
});

//function to switch to "Draw Signature Area" or switch to "Cancel Drawing" according to the drawingMode passed
function toggleDrawSignatureBtn(drawingMode) {
    drawSignatureBtn.setAttribute("data-drawing-mode", drawingMode);
    drawSignatureBtn.classList.toggle("btn-outline-primary", !drawingMode);
    drawSignatureBtn.classList.toggle("btn-danger", drawingMode);
    drawSignatureBtn.innerHTML = drawingMode
        ? '<i class="bi bi-x-circle"></i> Cancel Drawing'
        : '<i class="bi bi-pencil-square"></i> Draw on PDF';
}


// Declare handler functions in a broader scope
function handleMouseDown(e) {
    const rect = overlayCanvas.getBoundingClientRect();
    startX = e.clientX - rect.left;
    startY = e.clientY - rect.top;
    isDrawing = true;
}

function handleMouseMove(e) {
    if (!isDrawing) return;
    const rect = overlayCanvas.getBoundingClientRect();
    currentX = e.clientX - rect.left;
    currentY = e.clientY - rect.top;
    const width = currentX - startX;
    const height = currentY - startY;
    overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
    overlayCtx.strokeStyle = 'blue';
    overlayCtx.lineWidth = 1;
    overlayCtx.strokeRect(startX, startY, width, height);
}

function handleMouseUp() {
    isDrawing = false;
    const width = Math.abs(currentX - startX);
    const height = Math.abs(currentY - startY);
    const left = Math.min(startX, currentX);
    const top = Math.min(startY, currentY);

    const canvasWidth = overlayCanvas.width;
    const canvasHeight = overlayCanvas.height;

    const scaleX = originalPdfWidth / canvasWidth;
    const scaleY = originalPdfHeight / canvasHeight;

    const xInPoints = left * scaleX;
    const yInPoints = (canvasHeight - top - height) * scaleY;
    const widthInPoints = width * scaleX;
    const heightInPoints = height * scaleY;

    esignPdfForm.x.value = Math.round(xInPoints);
    //esignPdfForm.y.value = yInPoints > 10?Math.round(yInPoints-10):Math.round(yInPoints);
    esignPdfForm.y.value = Math.round(yInPoints);
    esignPdfForm.rectangle_width.value = Math.round(widthInPoints);
    esignPdfForm.rectangle_height.value = Math.round(heightInPoints);
}

function handleMouseLeave() {
    isDrawing = false;
}

function setDrawingMode() {
    overlayCanvas.style.cursor = "crosshair";
    overlayCanvas.addEventListener('mousedown', handleMouseDown);
    overlayCanvas.addEventListener('mousemove', handleMouseMove);
    overlayCanvas.addEventListener('mouseup', handleMouseUp);
    overlayCanvas.addEventListener('mouseleave', handleMouseLeave);
}


function removeDrawingMode() {
    overlayCanvas.style.cursor = "default";
    overlayCanvas.removeEventListener('mousedown', handleMouseDown);
    overlayCanvas.removeEventListener('mousemove', handleMouseMove);
    overlayCanvas.removeEventListener('mouseup', handleMouseUp);
    overlayCanvas.removeEventListener('mouseleave', handleMouseLeave);

    isDrawing = false;
    startX = 0;
    startY = 0;
    currentX = 0;
    currentY = 0;
    overlayCtx.clearRect(0, 0, overlayCanvas.width, overlayCanvas.height);
    esignPdfForm.x.value = 0;
    esignPdfForm.y.value = 0;
    esignPdfForm.rectangle_width.value = "";
    esignPdfForm.rectangle_height.value = "";
}

//Defining on submit event for esignPdfForm
esignPdfForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    const formData = new FormData(esignPdfForm);

    responseDiv.innerHTML = "Uploading and signing...";
    spinner.classList.remove("d-none");
    esigned_pdf_base64.value = "";
    // eSignResult.classList.add("d-none");
    
    try {
        const response = await fetch(esignPdfForm.action, {
            method: "POST",
            body: formData,
            headers: { Accept: "application/json" }
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || "Server error");
        }
        
        let responseData = await response.json();
        
        responseDiv.innerHTML = `<div class="alert alert-success alert-dismissible fade show">
                                ${responseData.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;
        
        const base64PDF = responseData.signedPDFBase64;
        esigned_pdf_base64.value = responseData.signedPDFBase64;
        
        // Convert base64 string to a Blob
        const blob = convertToBlob(base64PDF);
        // Create object URL and open in new tab
        const blobUrl = URL.createObjectURL(blob);
        //Rendering the signed pdf in the canvas
        renderPDF(blobUrl, currentPage);
        pdfCanvas.classList.remove("d-none");
        toggleDrawSignatureBtn(false);
        removeDrawingMode();
        enableDownloadSignedDocBtn(blobUrl);
        spinner.classList.add("d-none");
        //eSignResult.classList.remove("d-none");
        esignPdfForm.classList.add("d-none");
        document.forms['submit_esigned_doc_form'].classList.remove("d-none");
    }
    catch (err) {
        if(err.message == "Failed to fetch"){
            responseDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show">
            Unable to reach digital signing server, make sure that the 'Digital Signing App' is running and up. 
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                    
            </div>`;
        }
        else{
            responseDiv.innerHTML = `<div class="alert alert-danger alert-dismissible fade show">
            ${err.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                    
            </div>`;
        }
        
        spinner.classList.add("d-none");
    }

});

//function to convert base64 encoded string to blob
function convertToBlob(base64String){
    // Convert base64 string to a Blob
    const byteCharacters = atob(base64String);
    const byteNumbers = Array.from(byteCharacters, char => char.charCodeAt(0));
    const byteArray = new Uint8Array(byteNumbers);
    return new Blob([byteArray], { type: 'application/pdf' });
}

// This function will enable users to download signed doc for personal use
function enableDownloadSignedDocBtn(url) {
    const downloadSignedDocBtn = document.getElementById("downloadSignedDocBtn");
    downloadSignedDocBtn.style.display = "block";
    downloadSignedDocBtn.setAttribute("href", url);
    downloadSignedDocBtn.download = "signed-document.pdf";
}

//function to disable the download button for esigned doc
function disableDownloadSignedDocBtn() {
    const downloadSignedDocBtn = document.getElementById("downloadSignedDocBtn");
    downloadSignedDocBtn.style.display = "none";
    downloadSignedDocBtn.setAttribute("href", "#");
}

function toggleCanvasMessage(show) {
    const msg = document.getElementById("canvas-overlay-message");
    msg.style.display = show ? "block" : "none";
}

window.addEventListener('resize', () => {
    if (pdfDoc && currentPage) {
        renderPage(currentPage);
    }
});
