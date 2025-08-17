//check when enter ein for first time
$(document).ready(function() {
  $('#ein').on('change', function() {
      var einValue = $(this).val();
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      if(einValue !== ''){
        $.ajax({
            url: '/ddo-assist/checkein', // Update this route to match your backend endpoint
            method: 'POST',
            data: {
                ein: einValue,
                _token: csrfToken
            }, // Include CSRF token in the data
            dataType: 'json', // Specify that you expect a JSON response
            success: function(response) {
                if (response.exists) {
                    $('#ein-validation-message').html('<div class="alert alert-danger"><b>This EIN is already entered in the DIHAS database</b></div>');                   
                } else {
                  var ein = document.getElementById('ein').value;
                  document.descriptiveRoleForm.action = "enterProformaDetails";
                  document.descriptiveRoleForm.submit();
                    //$('#ein-validation-message').text('');
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
      }
  });


  $('#applicant_email_id').on('change', function() {
      var applicant_email_id_Value = $(this).val();
      var csrfToken = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
          url: '/ddo-assist/checkemail', // Updated route with initial forward slash
          method: 'POST',
          data: { applicant_email_id: applicant_email_id_Value, _token: csrfToken }, // Include CSRF token in the data
          dataType: 'json', // Specify that you expect a JSON response
          success: function(response) {
              if (response.exists) {
                  $('#applicant_email_id-validation-message').text('Email exists in the database.');
              } else {
                  $('#applicant_email_id-validation-message').text('');
              }
          },
          error: function(error) {
              console.error('Error:', error);
          }
      });
  });


//While saving data
  $('#desc_role_next_btn').on('click', function (event) {
      event.preventDefault();

      var einValue = $('#ein').val();
      var csrfToken = $('meta[name="csrf-token"]').attr('content');
      // Perform AJAX request to check if EIN exists
      $.ajax({
          type: "POST",
          url: 'checkein', // Update this route to match your backend endpoint
          data: {
              ein: einValue,
              _token: csrfToken // Include CSRF token in the data
          },
          success: function (response) {
              if (response.exists) {
                  // EIN already exists, show an error message
                  $('#ein').next(".required-field-indicator").remove();
                  $('#ein').after("<span class='required-field-indicator'>*EIN already exists</span>");
                  alert("EIN already exists");
              } else {
                  // EIN does not exist, proceed with form submission
                  validateForm();
              }
          },
         
      });    
  });



  function validateForm() {
      // Code for validating other form fields (similar to your existing code)
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
          
        //  "#second_post_id",
        //  "#second_grade_id",
        //  "#dept_id_option",
        //  "#third_post_id",
        //  "#third_grade_id"
      ];





      var isValid = true;

      // Check if any required field is blank
      requiredFields.forEach(function(field) {
          var value = $(field).val();
          if (!value && !$(field).is(":checkbox")) {
              isValid = false;
              $(field).next(".required-field-indicator").remove();
              $(field).after(
                  "<span class='required-field-indicator'>*This field is required</span>");
          } else if ($(field).is(":checkbox") && !$(field).is(":checked")) {
              isValid = false;
              $(field).parent().find(".required-field-indicator").remove();
              $(field).parent().prepend(
              "<span class='required-field-indicator'>*Required</span>");
          } else {
              $(field).next(".required-field-indicator").remove();
          }
      });

      // Display an alert if any required field is blank
      if (!isValid) {
          alert("Please fill up all required fields.");
          return;
      }






      // If all validations pass, display the confirmation modal
      if (isValid) {
          $('#confirmationModal').modal('show');
      }


  }

  // Event listener to remove error message when typing in required input fields
  $(document).on('input', '#expire_on_duty, #deceased_doe, #deceased_causeofdeath, #applicant_name, #appl_date, #applicant_dob, #relationship, #applicant_mobile, #applicant_edu_id, #applicant_email_id, #emp_addr_lcality, #emp_addr_district, #emp_addr_subdiv, #emp_state, #emp_pincode, #emp_addr_lcality_ret, #emp_addr_district_ret, #emp_addr_subdiv_ret, #emp_state_ret, #emp_pincode_ret, #applicant_desig_id, #applicant_grade, #second_post_id, #second_grade_id, #third_post_id, #third_grade_id, #dept_id_option, #other_qualification', function (){
      $(this).next(".required-field-indicator").remove();
  });

  // Handle OK button click event in the modal
  $('#confirmSubmit').on('click', function() {
      // Perform form submission using AJAX
      var formData = $("#descriptiveRoleForm").serialize();
      console.log(formData);
      $.ajax({
          type: "POST",
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
          url: "/ddo-assist/save-proforma-details", // Update the route to match your Laravel route
          data: formData,
          success: function(response) {
              
              alert(response.message);
              window.location.href = '/ddo-assist/enterProformaDetails';
              // You can handle the response here if needed
          },
      });

      // Close the modal without fade animation
      $('#confirmationModal').modal('hide');

      // Reset the form and hide the modal
      $('#descriptiveRoleForm')[0].reset();
  });

  // Handle Cancel button click event in the modal
  $('#confirmCancel').on('click', function() {
      // Close the modal without fade animation
      $('#confirmationModal').modal('hide');
  });
});


$("#resetBtn").click(function (e) {
    e.preventDefault();

    $("#ein").val("");
    $("#deceased_emp_name").val("");
    $("#dept_name").val("");
    $("#desig_name").val("");
    $("#grade_name").val("");
    $("#deceased_doa").val("");
    $("#deceased_dob").val("");
    $("#ministry").val("");

    // Reset form fields to blank
    $("input[name='expire_on_duty']").prop("checked", false); // Uncheck radio buttons for expire_on_duty
    $("#deceased_doe").val("");
    $("#deceased_causeofdeath").val("");
    $("#applicant_name").val("");
    $("#appl_date").val("");
    $("#applicant_dob").val("");
    $("#relationship").val("Select"); // Reset select to default value
    $("#applicant_mobile").val("");
    $("#applicant_edu_id").val("Select");
    $("input[name='physically_handicapped']").prop("checked", false); // Uncheck all radio buttons for caste
    $("#applicant_email_id").val("");
    $("#caste_id").val("Select");

    $("#sex").val("Select");
    $("#emp_addr_lcality").val("");
    $("#emp_addr_district").val("");
    $("#emp_addr_subdiv").val("");
    $("#emp_state").val("");
    $("#emp_pincode").val("");
    $("#emp_addr_lcality_ret").val("");
    $("#emp_addr_district_ret").val("");
    $("#emp_addr_subdiv_ret").val("");
    $("#emp_state_ret").val("");
    $("#emp_pincode_ret").val("");
    $("#applicant_desig_id").val("Select");
    $("#applicant_grade").val("Select");

    $("#second_post_id").val("Select");
    $("#second_grade_id").val("Select");
    $("#dept_id_option").val("Select");
    $("#third_post_id").val("Select");
    $("#third_grade_id").val("Select");

    // Reset other form fields as necessary

    // Make an AJAX request to delete the record from the database

    //console.log(ein);
    $.ajax({
        url: "/reset", // replace ein with the actual value
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            // alert(response.message);
            window.location.href = "/ddo-assist/enterProformaDetails";
        },
        error: function (xhr) {
            alert("Error occurred: " + JSON.stringify(xhr.statusText));
        },
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