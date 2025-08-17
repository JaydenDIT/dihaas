//below is for dept

 //otp box next movement
 const inputBoxesDept = document.querySelectorAll('.input-box');
 const combinedInputDept = document.getElementById('combined-input-Dept');
 
 inputBoxesDept.forEach((box, index) => {
     box.addEventListener('input', () => {
         const value = box.value;
 
         if (value.length === 1) {
             if (index < inputBoxesDept.length - 1) {
              inputBoxesDept[index + 1].focus();
             }
         }
 
         const inputValues = Array.from(inputBoxesDept).map(box => box.value).join('');
         combinedInputDept.value = inputValues;
     });
 
     box.addEventListener('keydown', (event) => {
         if (event.key === 'Backspace' && !box.value) {
 
             const prevBox = inputBoxesDept[index - 1];
             if (prevBox) {
                 prevBox.focus();
             }
         }
     });
 });



$("#loginBtnDept").click(function(){
    let otp = '';
    for (let i = 1; i <= 6; i++) {
        el = document.getElementById('InputDept' + i);
        if (el.value == '') {
            alert('Please enter correct OTP!');
            return false;
        }
        otp += el.value
    }
    $("#login_otp_Dept").val(otp);
    //$("#login-form-dept").submit();
    var formData = new FormData( document.forms['login-form-dept'] );
    ajax_send_multipart(login_Dept, formData, loginBtnDeptClickAjaxCall);
});

function loginBtnDeptClickAjaxCall(resp){
    switch( resp.status ){
        case 0 :
            alert(resp.msg);
            $(".input-box-Dept").val("");
            $("#InputDept1").focus();
        break;
        case 1 :
            $("#loading-div").show();
            location.href = welcome;
        break;
    }
  }

  $("#resendBtnDept").click(function(){
    var formData = new FormData();
    formData.append('_token', _token);
    formData.append('email', $("#emailDept").val() );
    ajax_send_multipart(login_otp_resend_Dept, formData, resendBtnDeptAjaxCall);

    function resendBtnDeptAjaxCall(resp){
        alert("OTP resend successfully");
        startCountdownTimer();
    }
});

$("#getOTPBtnDept").click(function(){
    var form = document.forms['login-form-dept'];

    $(".is-invalid").removeClass("is-invalid");

    $("#emailDept").siblings(".invalid-feedback").html("Email Cannot be empty");
    //$("#username").siblings(".invalid-feedback").html("Username Cannot be empty");
    $("#passwordDept").siblings(".invalid-feedback").html("Password Cannot be empty");
    
    var checkForm = form.checkValidity();
    form.classList.add('was-validated');
    if( ! checkForm ){
       return;
    }
var pwd = $('#passwordDept').val();
       var hashedPwd = forge_sha256(pwd);
       $('#passwordDept').val(hashedPwd); 

    var formData = new FormData( document.forms['login-form-dept'] );
    ajax_send_multipart(login_otp_Dept, formData, getOTPBtnDeptClickAjaxCall);  
});

function getOTPBtnDeptClickAjaxCall(resp){    
   // console.log(resp);

    switch( resp.status ){
        case -1 :           
             alert(resp.msg);
        break;
        case 0 :
            if(resp.errors != undefined ){
                Object.keys(resp.errors).forEach(function(key){
                    key = key.replace(".", "_");
                    $("#"+key).addClass("is-invalid");
                    $("#"+key).siblings(".invalid-feedback").html(resp.errors[key]);
                    $("#" + key).focus();
                });
            }           
            $('#passwordDept').val('');
            $('#passwordDept').focus();
            // $(".login-div").show();  
            $(".login-div3").show();
           // reloadCaptcha();
           // alert(resp.msg);
          
        break;
        case 1 :
           
            document.getElementById('otpsendMessageDept').textContent = resp.msg;
            startCountdownTimer();
            document.getElementById('otp-containerDept').style.display = 'inline';
            document.getElementById('loginBtnDept').disabled = false;
            // $(".login-div").show();
            $(".login-div3").show();
            $(".input-box-Dept").val("");
            $("#InputDept1").focus();            
        break;
    }
        
       
}

function startCountdownTimer() {  
    // Pass the session value to JavaScript
    // var otpmoibleNumber = userMobile.slice(-4); // Get the last 4 digits of the mobile number
    var countdownMessage = document.getElementById('countdownMessageDept');
    var secondsLeft = 10; // 2 minutes in seconds
 
    function updateCountdownMessage() {
        var minutes = Math.floor(secondsLeft / 60);
        var seconds = secondsLeft % 60;
 
        countdownMessage.textContent = `Your OTP will expire in ${minutes} minutes and ${seconds} seconds`;
        // otpsendMessage.textContent = `OTP is sent to your mobile number ending with ** ${otpmoibleNumber}`;
        document.getElementById('getOTPBtnDept').disabled = true;
            document.getElementById('resendBtnDept').disabled = true;
            document.getElementById('resendBtnDept').style.color = "red";
 
        if (secondsLeft === 0) {
            clearInterval(timerInterval);
            countdownMessage.textContent = 'OTP has expired. Please request a new OTP.';
            document.getElementById('getOTPBtnDept').disabled = true;
            document.getElementById('resendBtnDept').disabled = false;
            document.getElementById('resendBtnDept').style.color = "blue";
        }
 
        secondsLeft--;
    }
 
    updateCountdownMessage();
    var timerInterval = setInterval(updateCountdownMessage, 1000);
 }