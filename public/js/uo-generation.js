async function loadPdfAsBase64(proformaDocUrl) {
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