$(document).ready(function () {
    // $("#idproof_id").trigger("click");
});


$(".funSelectChange").on('change', function() {

   var param = new FormData();
   param.append('_token', _token);
   param.append('id', $(this).find(":selected").val());

   let html = "";
   let error_msg = "";
   let url = "#";
   let select_id = $(this).attr("data-change");
   switch(select_id){
    //    case "current_state_id" : case "permanent_state_id" : 
    //        html = "<option value='' disabled selected>Choose State....</option>";
    //        error_msg = "No State under the selected Country.";
    //        url = stateoption_url;
    //    break;
       case "current_district_id" : case "permanent_district_id" : 
           html = "<option value='' disabled selected>Choose District....</option>";
           error_msg = "No District under the selected State.";
           url = districtoption_url;
       break;
   }
   
   //nested function
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


$("#checkPermanent").click(function(){
   $("#permanent_address1").val( ""  );
       $("#permanent_address2").val( ""   );
       $("#permanent_address3").val( ""   );
       $("#permanent_state_id").val( ""   );
       $("#permanent_pin").val( ""  );          
    //    $("#permanent_state_id").html( 
    //        "<option value='' diabled selected>"
    //        + "Choose State..."
    //        + "</option>"
    //      );
       $("#permanent_district_id").html( 
           "<option value='' diabled selected>"
           + "Choose District..."
           + "</option>"
         );
   if( $(this)[0].checked == 1){
       $("#permanent_address1").val( $("#current_address1").val()  );
       $("#permanent_address2").val( $("#current_address2").val()  );
       $("#permanent_address3").val( $("#current_address3").val()  );
    //    $("#permanent_country_id").val( $("#current_country_id").val()  );
       $("#permanent_pin").val( $("#current_pin").val()  );
       
       $("#permanent_state_id").html( $("#current_state_id").html() ); 
       $("#permanent_state_id").val( $("#current_state_id").val() ); 

       $("#permanent_district_id").html( $("#current_district_id").html() ); 
       $("#permanent_district_id").val( $("#current_district_id").val() ); 
   }
}); 

//update profile
//    $("#saveMain_id").click(function(){
//        $(".custom_error").hide();
//        $(".is-invalid").removeClass("is-invalid");
//        var form = document.forms['main-form'];
//        var checkForm = form.checkValidity();
//        form.classList.add('was-validated');
//        if (!checkForm) {
//            return;
//        }
   
//        var formData = new FormData(form);
//        var url = saveMain;
      
//        ajax_send_multipart(url, formData, saveProfileAjaxCall);
//    });

   //update address
//    $("#address-form_id").click(function(){
//    $(".custom_error").hide();
//    $(".is-invalid").removeClass("is-invalid");
//    var form = document.forms['address-form'];
//    var checkForm = form.checkValidity();
//    form.classList.add('was-validated');
//    if (!checkForm) {
//        return;
//    }

//    var formData = new FormData(form);
//    var url = saveAddress;
//    ajax_send_multipart(url, formData, saveProfileAjaxCall);
// });

//update password
$("#password-form_id").click(function(){
   $(".custom_error").hide();
   $(".is-invalid").removeClass("is-invalid");
   var form = document.forms['password-form'];
   var checkForm = form.checkValidity();
   form.classList.add('was-validated');
   if (!checkForm) {
       return;
   }

   var oldpwd = $('#old_password').val();
   console.log(oldpwd);
   var hashedOldPwd = forge_sha256(oldpwd);
   $('#old_password').val(hashedOldPwd);

   var pwd = $('#password').val();
   var hashedPwd = forge_sha256(pwd);
   $('#password').val(hashedPwd);

   var cpwd = $('#password_confirmation').val();
   var hashedCPwd = forge_sha256(cpwd);
   $('#password_confirmation').val(hashedCPwd);

    var formData = new FormData(form);
    console.log(formData);
   var url = savePassword;
   ajax_send_multipart(url, formData, saveProfileAjaxCall);

});


   function saveProfileAjaxCall(resp){
       switch (resp.status) {
           case -1:
               if (resp.msg.errors != undefined) {
                   Object.keys(resp.msg.errors).forEach(function (key) {
                       key = key.replace(".", "_");
                       $("#" + key).addClass("is-invalid");
                       $("#" + key).siblings(".invalid-feedback").html(resp.msg.errors[key]);
                       $("#" + key).focus();
                   });
               }
             
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
               alert(resp.msg);
               
           break;
           case 1:
               alert(resp.msg);
               //resetForm( document.forms['password-form'] );
               location.href = "/logout";
           break;
       }

   }




// function idproofClick(that){
//    $("#idproof_number").prop('disabled', false);
//    if( $(that).val() == 1 || $(that).find('option:selected').text() == "Aadhaar card" ){
//        $("#idproof_number").prop('disabled', true );
//    }
// }

$("#otp_submit").click(function(){
   $("#otp_error_msg").html("");
   $("#otp_error_msg").hide();
   var form = document.forms['otp-form'];
   var checkForm = form.checkValidity();
   form.classList.add('was-validated');
   if (!checkForm) {
       return;
   }
   var formData = new FormData(form);
   var resp = ajax_send_multipart(otpsubmit_url, formData);
   switch (resp.status) {
       case 0:
           if (resp.mobileOtpError != undefined) {
               $("#otp_error_msg").append(resp.mobileOtpError);
               $("#otp_error_msg").show();
           }
           if (resp.emailOtpError != undefined) {
               $("#otp_error_msg").append(resp.emailOtpError);
               $("#otp_error_msg").show();
           }
           alert(resp.msg);
           break;
       case 1:
           alert(resp.msg);
           location.href = login_url;
           break;
   }
});



