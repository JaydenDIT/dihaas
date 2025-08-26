@extends('layouts.app')



@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-4">
                <h3 class="row py-1 mb-3 text-center fw-bold">
                    Proforma View
                </h3>
            </div>
        </div>

        <!-- NEW STEP TRACKER -->
        <div class="step-tracker">
            <div class="btn-go-through btn-step" data-step="1">
                <button class="btn btn-sm btn-circle statusBtn   completed" type="button">1</button>
                <div>Details</div>
            </div>
            <div class="btn-go-through btn-step" data-step="2">
                <button class="btn btn-sm btn-circle statusBtn completed" type="button">2</button>
                <div>Family Members</div>
            </div>
            <div class="btn-go-through btn-step" data-step="3">
                <button class="btn btn-sm btn-circle statusBtn completed" type="button">3</button>
                <div>Documents Upload</div>
            </div>
            <div class="btn-go-through btn-step" data-step="4">
                <button class="btn btn-sm btn-circle statusBtn completed" type="button">4</button>
                <div>UO Form Generation</div>
            </div>
        </div>


        <div class="row">
            <!-- STEP 1 -->
            <div class="setup-content" id="step-1">
                @include('proforma.viewParts.view1._collection')
            </div>

            <!-- STEP 2 -->
            <div class="setup-content" id="step-2">
                @include('proforma.viewParts.view2._familyDetail')
            </div>

            <!-- STEP 3 -->
            <div class="setup-content" id="step-3">
                <div class="form-group">
                    @include('proforma.viewParts.view3._uploadDocument')
                </div>
            </div>

            {{-- Step 4 --}}
            <div class="setup-content" id="step-4">
                <div class="form-group card">
                    <div class="small card-body">
                        <h5 class="card-title">UO Form Generation</h5>
                        <div class="row">
                            <div class="col-sm-9 mb-3">
                                <iframe src="" id="proforma_document" style="width:100%;height:780px;"
                                    frameborder="0"></iframe>
                            </div>
                            <div class="col-sm-3">
                                <form id="esignPdfForm" name="esignPdfForm"
                                    action="http://localhost:8095/api/esignBase64PDF" method="POST"
                                    enctype="multipart/form-data">

                                    <div class="mb-1 d-none">
                                        <label>Base64 encoded String:</label>
                                        <textarea name="pdf_base64" id="pdf_base64" class="form-control" cols="40" rows="10" rows="2"></textarea>
                                    </div>
                                    <div class="form-group mb-3 d-none">
                                        <label>Set coordinates for signature stamp:</label>
                                        <div class="d-flex gap-3">
                                            <div>
                                                <label for="x">X:</label>
                                                <input type="number" id="x" name="x" value="420"
                                                    class="form-control">
                                            </div>
                                            <div>
                                                <label for="y">Y:</label>
                                                <input type="number" id="y" name="y" value="384"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-3 d-none">
                                        <label for="#" class="text-sm">The height and width of the rectangular
                                            signature
                                            area:</label>
                                        <div class="col">
                                            <input type="number" id="rectangle_height" name="rectangle_height"
                                                placeholder="Height" value="60" class="form-control form-control-sm"
                                                readonly="">
                                        </div>
                                        <div class="col">
                                            <input type="number" id="rectangle_width" name="rectangle_width"
                                                placeholder="Width" value="170" class="form-control form-control-sm"
                                                readonly="">
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 d-none">
                                        <label for="file">Page number where the signature will appear:</label>
                                        <input type="number" id="pageNo" name="pageNo" value="1"
                                            class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="location">Reason:</label>
                                        <input type="text" id="reason" name="reason" class="form-control">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="location">Location:</label>
                                        <input type="text" id="location" name="location" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">E-Sign & Upload</button>
                                </form>
                                <div class="col-md-7" id="serverResponse">

                                </div>
                                <div class="d-none">
                                    <textarea name="esigned_pdf_base64" id="esigned_pdf_base64" class="form-control" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('duties.tasks.modals._confirmation_modal')
@endsection

@push('js')
    <script src="{{ asset('js/step-wizard.js') }}"></script>
    <script>
        const proformaId = "{{ $proforma->proforma_id }}";
        const rejectUrl =
            "{{ route('tasks.performa.reject', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const revertUrl =
            "{{ route('tasks.performa.revert', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const forwardUrl = "{{ route('duties.uo.formfillup.forward', $proforma->proforma_id) }}";
        const verifyUrl = "{{ route('duties.verify.form.verify', $proforma->proforma_id) }}";
        const confirmRedirectUrl = "{{ route('duties.verify.form.index', $tasks['current']['tasks_id']) }}";
        const proformaDashboardUrl = "{{ route('tasks.performa.all') }}";
        const proformaDocUrl = "{{ route('duties.uo.formgeneration.document', $proforma->proforma_id) }}";


        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                $(document).ready(function() {
                    showStep(4);
                });
                /*
                fetch(proformaDocUrl).then(res => res.json()).then(data => {
                    let base64Pdf = data.base64;
                    document.querySelector("#proforma_document").src =
                        "data:application/pdf;base64," + base64Pdf;
                });
                */
                loadPdfAsBase64().then(base64Pdf => {
                    //console.log(base64Pdf);
                    // Set iframe source
                    document.querySelector("#proforma_document").src =
                        "data:application/pdf;base64," +
                        base64Pdf;
                    document.forms['esignPdfForm'].pdf_base64.value = base64Pdf;
                });
            });
        });

        async function loadPdfAsBase64() {
            const response = await fetch(proformaDocUrl);
            const blob = await response.blob();

            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => {
                    resolve(reader.result.split(',')[1]); // remove the "data:application/pdf;base64,"
                };
                reader.readAsDataURL(blob);
            });
        }

        document.addEventListener("DOMContentLoaded", () => {
            const esignPdfForm = document.forms['esignPdfForm'];
            //document.getElementById("esignPdfForm");
            const esigned_pdf_base64 = document.getElementById('esigned_pdf_base64');

            esignPdfForm.onsubmit = async function(event) {
                event.preventDefault();

                const formData = new FormData(esignPdfForm);
                const serverResponseDiv = document.getElementById("serverResponse");

                try {
                    serverResponseDiv.innerHTML = "Wait please ...";
                    const response = await fetch(esignPdfForm.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const responseData = await response.json();
                        throw new Error(responseData.message || "An error occurred");
                    }

                    // Lazy load the response
                    let responseData = await response.json();
                    alert(responseData.message);
                    esigned_pdf_base64.value = responseData.signedPDFBase64;

                    document.querySelector("#proforma_document").src =
                        "data:application/pdf;base64," +
                        responseData.signedPDFBase64;
                    // Convert base64 string to a Blob
                    const blob = convertToBlob(responseData.signedPDFBase64);
                    /* Create object URL and open in new tab using window.open(pdfUrl) 
                    			or just create a download link as shown below.*/
                    const pdfUrl = URL.createObjectURL(blob);
                    serverResponseDiv.innerHTML = `<a href="${pdfUrl}" 
				download="signed_doc.pdf">Download Signed PDF</a>`;
                } catch (error) {
                    console.error("Error in client-side request:", error);
                    serverResponseDiv.innerHTML = error.message;
                    alert(error.message);
                }
            };

            //function to convert base64 encoded string to blob
            function convertToBlob(base64String) {
                // Convert base64 string to a Blob
                const byteCharacters = atob(base64String);
                const byteNumbers = Array.from(byteCharacters, char => char.charCodeAt(0));
                const byteArray = new Uint8Array(byteNumbers);
                return new Blob([byteArray], {
                    type: 'application/pdf'
                });
            }
        });
    </script>

    <script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush
