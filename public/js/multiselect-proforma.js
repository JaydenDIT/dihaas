//Actions to be triggered after loading data table
function afterDataTableLoad(application_status, overall_seniority_indexes) {
    // set action events for proforma checkbox
    setEventsForProformaCheckBox(application_status);

    //enable the first proforma checkbox with the first sniority index
    setTimeout(() => {
        enableFirstProformaCheckbox(application_status, overall_seniority_indexes);
    }, 500);
}

// function to enable the first proforma checkbox with the smallest overall seniority index
function enableFirstProformaCheckbox(application_status, overall_seniority_indexes) {
    console.log("Enabling first checkbox if applicable...");

    if (application_status == "un-verified" || application_status == "verified") {

        let firstCheckbox = document.querySelector('.proforma-checkbox');
        if (firstCheckbox) {
            let firstIndex = (firstCheckbox.getAttribute('data-overall_seniority_idx') + "").trim();
            let smallestIndex = getSmallestSeniorityIndex(overall_seniority_indexes);

            console.log("firstIndex: " + firstIndex + ", smallestIndex: " + smallestIndex);
            if (firstIndex == smallestIndex) {
                //Remove the disabled attribute from the first checkbox
                firstCheckbox.disabled = false;
                //firstCheckbox.checked = true;
                // Manually trigger change event
                // let event = new Event('change');
                // firstCheckbox.dispatchEvent(event);
                console.log("First checkbox enabled.");
            } else {
                console.log(
                    "First checkbox index does not match smallest index. Something went wrong.");
            }
        }
    }
}

//function to set onchange event for the proforma checkbox
function setEventsForProformaCheckBox(application_status) {
    console.log("Setting events for proforma checkboxes...");
    // On change effect of the proforma checkbox            
    document.querySelectorAll('.proforma-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', function() {

            //enable next checkbox or disable successive checkbox based on the checked one
            enableOrDisableNextCheckbox(this);

            let verifyButton = document.getElementById('verify-button');
            let forwardButton = document.getElementById('forward-button');
            let revertButton = document.getElementById('revert-button');

            let anyChecked = document.querySelectorAll('.proforma-checkbox:checked').length > 0;
            if (application_status == 'un-verified') {
                if(verifyButton){
                    verifyButton.disabled = !anyChecked;
                }
                
                if(forwardButton){
                    forwardButton.disabled = anyChecked;
                }
                                
            } else if (application_status == 'verified') {
                if(verifyButton){
                    verifyButton.disabled = anyChecked;
                }                
                if(forwardButton){
                    forwardButton.disabled = !anyChecked;
                }
            }
            if(revertButton){
                revertButton.disabled = !anyChecked;
            }
        });
    });
}

//function to enable or disable successive checkbox based on the checked one
function enableOrDisableNextCheckbox(currentCheckbox) {
    let checkboxes = document.querySelectorAll('.proforma-checkbox');
    let currentIndex = Array.from(checkboxes).indexOf(currentCheckbox);
    if (currentCheckbox.checked) {
        // Enable the next checkbox
        if (checkboxes[currentIndex + 1]) {
            checkboxes[currentIndex + 1].disabled = false;
        }
    } else {
        // Disable all successive checkboxes and uncheck them
        for (let i = currentIndex + 1; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
            checkboxes[i].disabled = true;
        }
    }
}

//function to get all the seniority index from the proforma checkboxes
function getOverallSeniorityIndexes() {
    let seniorityIndexes = [];
    $('.proforma-checkbox').each(function() {
        seniorityIndexes.push($(this).data('overall_seniority_idx'));
    });
    // Sort the indexes in ascending order
    seniorityIndexes.sort((a, b) => a - b);
    return seniorityIndexes;
}

//function to get the smallest seniority index
function getSmallestSeniorityIndex(overall_seniority_indexes) {    
    return overall_seniority_indexes.length ? overall_seniority_indexes[0] : null;
}