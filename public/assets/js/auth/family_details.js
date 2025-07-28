$('#save_family_btn').click(function() {
    // Display a confirmation message
    var isConfirmed = confirm('Are You Sure?');

    // After Confirm call the ajax
    var formData = $('#form_family_details').serialize();
    // console.log('Serialized Form Data:', formData);

  
    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },        
        url: '/ddo-assist/save-family-details-update',
        data: formData,
        success: function(response) {       
           
            
            alert(response.message)

        },
        error: function(error) {
            
            console.log('Error:', error);
        }
    });
});

$('#save_family_btn_fresh').click(function() {
    // Display a confirmation message
    var isConfirmed = confirm('Are You Sure?');

    // After Confirm call the ajax
    var formData = $('#form_family_details').serialize();
    // console.log('Serialized Form Data:', formData);

  
    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },        
        url: '/ddo-assist/save-family-details',
        data: formData,
        success: function(response) {       
           
            
            alert(response.message)

        },
        error: function(error) {
            
            console.log('Error:', error);
        }
    });
});

$('#save_family_btn_backlog').click(function() {
    // Display a confirmation message
    var isConfirmed = confirm('Are You Sure?');

    // After Confirm call the ajax
    var formData = $('#form_family_details').serialize();
    // console.log('Serialized Form Data:', formData);

  
    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },        
        url: '/ddo-assist/save-family-details-backlog',
        data: formData,
        success: function(response) {       
           
            
            alert(response.message)

        },
        error: function(error) {
            
            console.log('Error:', error);
        }
    });
});


document.getElementById('close').addEventListener('click', function (event) {
    // Display the confirmation dialog
    var confirmed = confirm('Have you finished?');

    // If not confirmed, prevent the default behavior (following the link)
    if (!confirmed) {
        event.preventDefault();
    }
});

var i = 0;
$("#addRemoveIp").click(function() {
    ++i;
    $("#multiForm").append(
        '<tr>' +
        '<td>' +
        '<input type="text" name="new_data[' + i + '][name]" class="form-control" value="" required/>' +
        '</td>' +
        '<td>' +
        '<input type="date" name="new_data[' + i + '][dob]" class="form-control" value="" required/>' +
        '</td>' +
        '<td>' +
        '<select class="form-select" aria-label="Default select example" name="new_data[' + i +
        '][gender]" required>' +
        '<option value=""selected>Select </option>' +
        '<option value="M">Male</option>' +
        '<option value="F">Female</option>' +
        // '<option value="T">Transgender</option>' +
        '</select>' +
        '</td>' +
        '<td>' +
        // '<input type="text" name="new_data[' + i + '][relation]" class="form-control" value="" required/>' +
        '<select class="form-select" aria-label="Default select example" name="new_data[' + i +
        '][relation]" required>' +
        '<option value=""selected>Select </option>' +
        '<option value="1">Wife</option>' +
        '<option value="2">Husband</option>' +
        '<option value="3">Son</option>' +
        '<option value="4">Daughter</option>' +

        '</select>' +
        '</td>' +

        '<td>' +
        '<button type="button" class="remove-item btn btn-danger btn-sm">' +
        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">' +
        '<path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />' +
        '</svg></button>' +
        '</td>' +
        '</tr>'
    );
});
$(document).on('click', '.remove-item', function() {
    $(this).parents('tr').remove();
});





