// //for submit all files
// $("#submit_id").click(function() {
//     onSubmitHandler();
// });

// function onSubmitHandler() {

//     document.uploadform.action = "upload-applicant-files-submit";
//     document.uploadform.submit();
// }

// //for upload single file
// $(document.body).on('click', '#upload_id', function() {
//     onUpload($(this).attr("data-detail_id"));
// });


// function onUpload() {
//     document.uploadform.action = "upload-applicant-files-update";
//     document.uploadform.submit();
// }


// //for remove upload

// $(document.body).on('click', '#remove_id', function() {
//     onDelete($(this).attr("data-detail_id"));
// });

// function onDelete(id) {
//     document.uploadform.deleteId.value = id;
//     document.uploadform.action = "upload-applicant-files-delete";
//     document.uploadform.submit();
// }

//Above is also working for not ajax but on id basis

//'upload-button'
$(document).on('click', '.upload-button', function() {
    
    var form = $(this).closest('form.upload-form');

   
    var formData = new FormData(form[0]);
    
  
    // Find the parent div of the button
    var buttonParentDiv = $(this).closest('div');

    // Create a loading message
    var loadingMessage = $('<div class="loading-message">Please wait...</div>');
    buttonParentDiv.append(loadingMessage);

    // Disable the button during the AJAX request
    $(this).prop('disabled', true);
    $.ajax({
        url: '/ddo-assist/upload-applicant-files-update',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
           
            if (response.status === 'success') {

              // Remove the loading message and enable the button on success
            loadingMessage.remove();
            $('.upload-button').prop('disabled', false);
          
              
                $.ajax({
                    url: '/ddo-assist/create-applicant-files-dihas',
                    type: 'GET',
                    success: function(newContent) {
                       
                        $('body').html(newContent);
                    },
                    error: function(error) {
                        alert('Error fetching new content: ' + error.responseText);
                      
            
                    }
                });

                
            } else {
                   ////
          
                alert('Error uploading file: ' + response.message);
               
            }
        },
        error: function(error) {
            // Remove the loading message and enable the button on error
            loadingMessage.remove();
            $('.upload-button').prop('disabled', false);
        }
    });
});



//  'remove-button'
$(document).on('click', '.remove-button', function() {
    var docId = $(this).attr('data_details_id');

     // Find the parent div of the button
     var buttonParentDiv = $(this).closest('div');

     // Create a loading message
     var loadingMessage = $('<div class="loading-message">Please wait...</div>');
     buttonParentDiv.append(loadingMessage);
 
     // Disable the button during the AJAX request
     $(this).prop('disabled', true);

    $.ajax({
        url: '/ddo-assist/upload-applicant-files-delete',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { docId: docId },
        success: function(response) {
            if (response.status === 'success') {
               
                $.ajax({
                    url: '/ddo-assist/create-applicant-files-dihas',
                    type: 'GET',
                    success: function(newContent) {

                                // Remove the loading message and enable the button on success
                        loadingMessage.remove();
                        $('.upload-button').prop('disabled', false);  
                      
                        $('body').html(newContent);
                       
                    },
                    error: function(error) {
                        alert('Error fetching new content: ' + error.responseText);
                    }
                });

                $('#document_' + docId).remove();
            } else {
                alert('Error removing file: ' + response.message);
                
            }
        },
        error: function(error) {
            alert('Error removing file: ' + error.responseText);
            // Remove the loading message and enable the button on success
            loadingMessage.remove();
            $('.upload-button').prop('disabled', false);
        }
    });
});



// submit
$(document).ready(function() {
    
    $('#submitButton').off('click').on('click', function() {
        onSubmitHandler();
    });
});

onSubmitHandler = () => {
    var form = $('form.upload-form');

    var formData = new FormData(form[0]);

    $.ajax({
        url: '/ddo-assist/upload-applicant-files-submit',
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response && response.status === 'success') {
                alert('Files submitted successfully');
            } else {
                alert('Error submitting files: ' + (response ? response.message : 'Unknown error'));
            }
        },
        error: function(error) {   
            alert('Error submitting files: ' + error.responseText);
        }
    });
}


    document.getElementById('close').addEventListener('click', function (event) {
        // Display the confirmation dialog
        var confirmed = confirm('Have you finished?');

        // If not confirmed, prevent the default behavior (following the link)
        if (!confirmed) {
            event.preventDefault();
        }
    });