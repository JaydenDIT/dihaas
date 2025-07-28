

$(document).ready(function () {    
   
    $(document).on('input', '#expire_on_duty, #deceased_doe, #deceased_causeofdeath, #applicant_name, #appl_date, #applicant_dob, #relationship, #applicant_mobile, #applicant_edu_id, #applicant_email_id, #emp_addr_lcality, #emp_addr_district, #emp_addr_subdiv, #emp_state, #emp_pincode, #emp_addr_lcality_ret, #emp_addr_district_ret, #emp_addr_subdiv_ret, #emp_state_ret, #emp_pincode_ret, #applicant_desig_id, #applicant_grade, #second_post_id, #second_grade_id, #third_post_id, #third_grade_id, #dept_id_option, #other_qualification', function () {
        $(this).next(".required-field-indicator").remove();
    });

    $("#desc_role_next_btn").click(function (e) {
        e.preventDefault();

        var requiredFields = [
            "input[name='expire_on_duty']:checked",
            "#deceased_doe",
            "#deceased_causeofdeath",
            "#applicant_name",
            "#appl_date",
            "#applicant_dob",
            "#relationship",
            "#applicant_mobile",
            "#applicant_edu_id",
            "input[name='physically_handicapped']:checked",
            "#applicant_email_id",
            "#caste_id",
            "#sex",
            "#emp_addr_lcality",
            "#emp_addr_district",
            "#emp_addr_subdiv",
            "#emp_state",
            "#emp_pincode",
            "#emp_addr_lcality_ret",
            "#emp_addr_district_ret",
            "#emp_addr_subdiv_ret",
            "#emp_state_ret",
            "#emp_pincode_ret",
            "#applicant_desig_id",
            "#applicant_grade",

            "#second_post_id",
            "#second_grade_id",
            "#dept_id_option",
            "#third_post_id",
            "#third_grade_id"
        ];

        var isValid = true;

        // Check if any required field is blank
        requiredFields.forEach(function (field) {
            var value;

            // Handle radio buttons and checkboxes
            if ($(field).is(":radio")) {
                value = $("input[name='" + $(field).attr("name") + "']:checked").val();
            } else if ($(field).is(":checkbox")) {
                value = $(field).is(":checked");
            } else {
                value = $(field).val();
            }

            if (!value) {
                isValid = false;

                // Add asterisk below the empty required field
                $(field).next(".required-field-indicator").remove();
                $(field).after("<span class='required-field-indicator'>*This field is required</span>");
            } else {
                // Remove asterisk if the field is not empty
                $(field).next(".required-field-indicator").remove();
            }
        });

        // Display an alert if any required field is blank
        if (!isValid) {
            alert("Please fill up all required fields.");
            return;
        }

        // Your AJAX request logic goes here
        var formData = $("#descriptiveRoleForm").serialize();

        // Show confirmation modal
        $("#confirmationModal").modal("show");

        // Handle OK button click event in the modal
        $("#confirmSubmit").one("click", function () {    url: '/ddo-assist/update_proforma_self', // Update the route to match your Laravel route
        $.ajax({
          url: '/ddo-assist/update_proforma_self', // Update the route to match your Laravel route
          type: "POST",
          data: formData,
          success: function(response) {
            // Handle success response here, if needed
             
               // Handle success response here, if needed
               alert(response.message);
    
    
          },
                error: function (error) {
                    // Handle error here, if needed
                    alert('Error occurred: ' + JSON.stringify(error.statusText));
                }
            });

            // Close the modal without fade animation
            $("#confirmationModal").modal("hide");
        });

        // Handle Cancel button click event in the modal
        $("#confirmCancel").one("click", function () {
            // Close the modal without fade animation
            $("#confirmationModal").modal("hide");
        });
    });


    $("#cancelBtn").click(function(e) {
    e.preventDefault();

    // Reset form fields to blank
    $("input[name='expire_on_duty']").prop("checked", false); // Uncheck radio buttons for expire_on_duty
    $("#deceased_doe").val('');
    $("#deceased_causeofdeath").val('');
    $("#applicant_name").val('');
    $("#appl_date").val('');
    $("#applicant_dob").val('');
    $("#relationship").val('Select'); // Reset select to default value
    $("#applicant_mobile").val('');
    $("#applicant_edu_id").val('Select');
    $("input[name='physically_handicapped']").prop("checked", false); // Uncheck all radio buttons for caste
    $("#applicant_email_id").val('');
    $("#caste_id").val('Select');
 
    $("#sex").val('Select');
    $("#emp_addr_lcality").val('');
    $("#emp_addr_district").val('');
    $("#emp_addr_subdiv").val('Select');
    $("#emp_state").val('Select');
    $("#emp_pincode").val('');
    $("#emp_addr_lcality_ret").val('');
    $("#emp_addr_district_ret").val('');
    $("#emp_addr_subdiv_ret").val('Select');
    $("#emp_state_ret").val('Select');
    $("#emp_pincode_ret").val('');
    $("#applicant_desig_id").val('Select');
    $("#applicant_grade").val('Select');

    $("#second_post_id").val("Select");
    $("#second_grade_id").val("Select");
    $("#dept_id_option").val("Select");
    $("#third_post_id").val("Select");
    $("#third_grade_id").val("Select");

    // Reset other form fields as necessary

    // Make an AJAX request to delete the record from the database
    var ein = $("#ein").val(); // Assuming you have an input field with id "ein" containing the EIN value
    //console.log(ein);
    $.ajax({
      url: '/deleteself/' + ein, // replace ein with the actual value
      type: 'DELETE',
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {

        alert(response.message);
          // Handle success response here
      },
      error: function(xhr) {
        alert('Error occurred: ' + JSON.stringify(xhr.statusText));
      }
  });
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