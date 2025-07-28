$(document).ready(function () {
    // $('#otpModal').modal('show');
     $("#new_user_form").trigger("reset");
     $("#otp-form").trigger("reset");
    // show_loader();
    $('#loginli').addClass('active');
     //reloadCaptcha();
 });

// function idproofClick(that) {
//     $("#idproof_number").prop("disabled", false);
//     if (
//         $(that).val() == 1 ||
//         $(that).find("option:selected").text() == "Aadhaar card"
//     ) {
//         $("#idproof_number").prop("disabled", true);
//     }
// }
function resendOTP(input) {
    var formData = new FormData();
    formData.append("_token", _token);
    switch (input) {
        case "mobile":
            ajax_send_multipart(resendSMSOTP, formData, resendOTPAjaxCall);
            break;
        case "email":
            ajax_send_multipart(resendEmailOTP, formData, resendOTPAjaxCall);
            break;
    }

    function resendOTPAjaxCall(resp) {
        alert("OTP resend successfully");
    }
}

$(".funSelectChange").on('change', function() {
 
    var param = new FormData();
    param.append('_token', _token);
    param.append('id', $(this).find(":selected").val());

    let html = "";
    let error_msg = "";
    let url = "#";
    let select_id = $(this).attr("data-change");
    switch(select_id){
        // case "current_state_id":
        // case "permanent_state_id":
        //     html =
        //         "<option value='' disabled selected>Choose State....</option>";
        //     error_msg = "No State under the selected Country.";
        //     url = stateoption_url;
        //     break;
        case "current_district_id":
        case "permanent_district_id":
            html =
                "<option value='' disabled selected>Choose District....</option>";
            error_msg = "No District under the selected State.";
            url = districtoption_url;
            break;
           }
   
    function funSelectChangeAjaxCall(resp){
        //console.log(resp);
        if(resp.status == 0){
            alert(resp.msg);
        }
        
        Object.keys(resp.data).forEach(function(key){
            var row = resp.data[key];
            html += "<option value='" + row.id + "' >" + row.name + "</option>";
        });
        if(resp.data.length == 0){
            alert(error_msg);
        }       
        $("#"+select_id).html(html);
    } 
    ajax_send_multipart(url, param, funSelectChangeAjaxCall);
    
});
    

//for register
$("#checkPermanent").click(function () {
    $("#permanent_address1").val("");
    $("#permanent_address2").val("");
    $("#permanent_address3").val("");
    $("#permanent_state_id").val("");
    $("#permanent_pin").val("");
    // $("#permanent_state_id").html(
    //     "<option value='' diabled selected>" + "Choose State..." + "</option>"
    // );
    $("#permanent_district_id").html(
        "<option value='' diabled selected>" +
            "Choose District..." +
            "</option>"
    );
    if ($(this)[0].checked == 1) {
        $("#permanent_address1").val($("#current_address1").val());
        $("#permanent_address2").val($("#current_address2").val());
        $("#permanent_address3").val($("#current_address3").val());
       // $("#permanent_country_id").val($("#current_country_id").val());
        $("#permanent_pin").val($("#current_pin").val());

        $("#permanent_state_id").html($("#current_state_id").html());
        $("#permanent_state_id").val($("#current_state_id").val());

        $("#permanent_district_id").html($("#current_district_id").html());
        $("#permanent_district_id").val($("#current_district_id").val());
    }
});

//for proforma
// function checkBoxForm(that) {
//     $("#emp_addr_lcality_ret").val("");
//     $("#emp_pincode_ret").val("");
//     $("#emp_state_ret").html(
//         "<option value='' diabled selected>" + "Choose State..." + "</option>"
//     );
//     $("#emp_addr_district_ret").html(
//         "<option value='' diabled selected>" +
//             "Choose District..." +
//             "</option>"
//     );
//     $("#emp_addr_subdiv_ret").html(
//         "<option value='' diabled selected>" +
//             "Choose Sub Division..." +
//             "</option>"
//     );
//     if ($(that)[0].checked == 1) {
//         $("#emp_addr_lcality_ret").val($("#emp_addr_lcality").val());
//         $("#emp_pincode_ret").val($("#emp_pincode").val());

//         $("#emp_state_ret").html($("#emp_state").html());
//         $("#emp_state_ret").val($("#emp_state").val());

//         $("#emp_addr_district_ret").html($("#emp_addr_district").html());
//         $("#emp_addr_district_ret").val($("#emp_addr_district").val());
//         $("#emp_addr_subdiv_ret").html($("#emp_addr_subdiv").html());
//         $("#emp_addr_subdiv_ret").val($("#emp_addr_subdiv").val());
//     }
// }


// this is for citizen
$("#registerSave").click(function () {
    $(".custom_error").hide();
    $(".is-invalid").removeClass("is-invalid");
    var form = document.forms["new_user_form"];
    var checkForm = form.checkValidity();
    form.classList.add("was-validated");
    if (!checkForm) {
        return;
    }
    if($('#password').val()=='' || $('#password').val()==null)
    alert("All fields are required to filled");
    else
    var pwd = $('#password').val();
    //console.log(pwd);

    var hashedPwd = forge_sha256(pwd);
    $('#password').val(hashedPwd);


    var cpwd = $('#password_confirmation').val();
    var hashedCPwd = forge_sha256(cpwd);
    $('#password_confirmation').val(hashedCPwd);
    //select option of ministry disable in blade so need to make enable so that data can be posted in database
    

     var formData = new FormData(form);
     console.log(formData);
    ajax_send_multipart(register_url, formData, registerSaveAjaxCall);
    endif
});

function registerSaveAjaxCall(resp) {
    switch (resp.status) {
        case -1:
            if (resp.msg.errors != undefined) {
                Object.keys(resp.msg.errors).forEach(function (key) {
                    key = key.replace(".", "_");
                    $("#" + key).addClass("is-invalid");
                    $("#" + key)
                        .siblings(".invalid-feedback")
                        .html(resp.msg.errors[key]);
                        $("#" + key).focus();
                });
            }
            $('#password').val('');  
            $('#password_confirmation').val('');
           // reloadCaptcha(); use when captcha use
            alert(resp.msg);
            break;
            case 0:
                if (resp.errors != undefined) {                
                    $("#custom_error").html("<li>"+ resp.errors +"</li>");
                    $(".custom_error").show();
                    $('html, body').animate({
                        scrollTop: $("#custom_error").offset().top - 150
                    }, 2000);
                }
                $('#password').val('');  
                $('#password_confirmation').val('');  
               // reloadCaptcha(); use when captcha use
                alert(resp.msg);
                break;
            case 1:
                $("#mobileOtpLabel").html(resp.mobileotpmsg);
               // $("#emailOtpLabel").html(resp.emailotpmsg);
                $('#otpModal').modal('show');
            break;
    }
}

$("#otp_submit").click(function () {
    $("#otp_error_msg").html("");
    $("#otp_error_msg").hide();
    var form = document.forms["otp-form"];
    var checkForm = form.checkValidity();
    form.classList.add("was-validated");
    if (!checkForm) {
        return;
    }
    var formData = new FormData(form);
    ajax_send_multipart(otpsubmit_url, formData, otp_submitAjaxCall);
});

function otp_submitAjaxCall(resp) {
    switch (resp.status) {
        case 0:
            if (resp.mobileOtpError != undefined) {
               
                $("#otp_error_msg").append(resp.mobileOtpError);
                $("#otp_error_msg").show();
            }
            // if (resp.emailOtpError != undefined){
            //     $("#otp_error_msg").append(resp.emailOtpError);
            //     $("#otp_error_msg").show();
            // }
            alert(resp.msg);
            break;
        case 1:
            alert(resp.msg);
            $("#new_user_form").trigger("reset");
            $("#otp-form").trigger("reset");
            location.href = login_url;
            break;
    }
}

function reloadCaptcha() {
    var formData = new FormData();
    formData.append('_token', _token);
    ajax_send_multipart(captchaUrl, formData, reloadCaptchaAjaxCall);

    function reloadCaptchaAjaxCall(resp){
        $(".captcha span").html(resp.captcha);
    }

}   

$("#reloadCaptcha").click(function(){
    reloadCaptcha();
}); 

$("#mobile").keydown(function (event) {
    k = event.which;
    if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 9) {
        if ($(this).val().length == 10) {
            if (k == 8) {
                return true;
            } else {
                event.preventDefault();
                return false;
            }
        }
    } else {
        event.preventDefault();
        return false;
    }
});


// below is for update register user



// $(document.body).on('click', '#updateVacancyForm', function(event) {
//     event.preventDefault();

//     var form = $(this);
//     var id = $('#id').val()

//     // Serialize form data for debugging
//     var formData = form.serialize();
//     console.log('Form Data:', formData); // Log form data to the console

//     $.ajax({
//         url: form.attr('action'),
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             // Add other headers if needed
//         },
//         data: formData,
//         success: function(response) {
//             if (response.success) {
//                 // Update UI or show success message
//                 $('#updateModal').modal('hide');

//                 // Get the updated data from the response
//                 var updatedData = response.data;

//                 $('#name' + id).html($('#name').val());
//                 $('#email' + id).html($('#email').val());
//                 $('#mobile' + id).html($('#mobile').val());
//                 $('#dept_name' + id).html($('#dept_name').val());
//                 $('#ministry' + id).html($('#ministry').val());
//                 $('#role_id' + id).html($('#role_id').val());
                
//                 //console.log(dr);
//                 //console.log(dr);
//                 // Select the corresponding table row and update the specific <td> elements with the updated data
//                 /*var tableRow = form.closest('tr');
//                 tableRow.find('td:nth-child(6)').text(updatedData.name);
//                 tableRow.find('td:nth-child(7)').text(updatedData.email);
//                 tableRow.find('td:nth-child(8)').text(updatedData.mobile);*/

//                 // Perform any additional actions after successful update
//             } else {
//                 $('#updateModal').modal('hide');
//                 // Handle error response, show error message, etc.
//                 // console.error(response.message);
//             }
//         },

//         error: function(xhr, status, error) {
//             // Handle AJAX error, show error message, etc.
//             // console.error(error);
//             // 
//         }

//     });
// });

$("#updateBtn").click(function () {

    // if($('#password').val()=='' || $('#password').val()==null)
    if(($('#password').val()=='' || $('#password').val()==null) && ($('#active_status').val()=='true') )
alert("All fields are required to filled");
else
    var pwd = $('#password').val();
    var hashedPwd = forge_sha256(pwd);
    $('#password').val(hashedPwd);


    var cpwd = $('#password_confirmation').val();
    var hashedCPwd = forge_sha256(cpwd);
    $('#password_confirmation').val(hashedCPwd);
//select option of ministry disable in blade so need to make enable so that data can be posted in database
    document.getElementById('ministry_id').disabled=false;
    //console.log($('#officialRegisterForm'));return;

    $('#officialUpdateForm').submit();
    endif

});

$("#registerSaveOfficial").click(function () {

    
if($('#password').val()=='' || $('#password').val()==null)
alert("All fields are required to filled");
else
var pwd = $('#password').val();
   var hashedPwd = forge_sha256(pwd);
    $('#password').val(hashedPwd);   


    var cpwd = $('#password_confirmation').val();
    var hashedCPwd = forge_sha256(cpwd);
    $('#password_confirmation').val(hashedCPwd);
//select option of ministry disable in blade so need to make enable so that data can be posted in database
    document.getElementById('ministry_id').disabled=false;
    //console.log($('#officialRegisterForm'));return;

    $('#officialSaveForm').submit();
endif

});

