//getting calculated vaccancy of the total
function getCalculatedValue(event) {
    const totalPost = event.target.value; // get typed value
    if (!totalPost) return;

    // calculate_vaccancy_url is a global variable
    let url = calculate_vaccancy_url.replace("_total_", totalPost);
    fetch(url)
        .then(async response => {
            const data = await response.json();

            if (!response.ok) {
                // Server returned an error with a message
                const errorMsg = data?.message || `HTTP error ${response.status}`;
                throw new Error(errorMsg);
            }

            console.log("Calculated values:", data);
            $("#dia").val(data.dia);
            $("#dr").val(data.dr);
        })
        .catch(error => {
            const errorMessage = error.message || "An unknown error has occured";
            //alert("Something went wrong while calculating vacancy: " + errorMessage);
            Swal.fire({
                icon: "error",
                title: "Oops! Something went wrong while calculating vacancy",
                text: errorMessage
            });
            console.error(errorMessage);
        });
}