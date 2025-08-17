   //otp box next movement
const inputBoxes = document.querySelectorAll('.input-box');
const combinedInput = document.getElementById('combined-input');

inputBoxes.forEach((box, index) => {
    box.addEventListener('input', () => {
        const value = box.value;

        if (value.length === 1) {
            if (index < inputBoxes.length - 1) {
                inputBoxes[index + 1].focus();
            }
        }

        const inputValues = Array.from(inputBoxes).map(box => box.value).join('');
        combinedInput.value = inputValues;
    });

    box.addEventListener('keydown', (event) => {
        if (event.key === 'Backspace' && !box.value) {

            const prevBox = inputBoxes[index - 1];
            if (prevBox) {
                prevBox.focus();
            }
        }
    });
});
   
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

function startCountdownTimer1() {  
   // Pass the session value to JavaScript
   // var otpmoibleNumber = userMobile.slice(-4); // Get the last 4 digits of the mobile number
   var countdownMessage = document.getElementById('countdownMessage');
   var secondsLeft = 10; // 2 minutes in seconds

   function updateCountdownMessage() {
       var minutes = Math.floor(secondsLeft / 60);
       var seconds = secondsLeft % 60;

       countdownMessage.textContent = `Your OTP will expire in ${minutes} minutes and ${seconds} seconds`;
       // otpsendMessage.textContent = `OTP is sent to your mobile number ending with ** ${otpmoibleNumber}`;
       document.getElementById('getOTPBtn').disabled = true;
           document.getElementById('resendBtn').disabled = true;
           document.getElementById('resendBtn').style.color = "red";

       if (secondsLeft === 0) {
           clearInterval(timerInterval);
           countdownMessage.textContent = 'OTP has expired. Please request a new OTP.';
           document.getElementById('getOTPBtn').disabled = true;
           document.getElementById('resendBtn').disabled = false;
           document.getElementById('resendBtn').style.color = "blue";
       }

       secondsLeft--;
   }

   updateCountdownMessage();
   var timerInterval = setInterval(updateCountdownMessage, 1000);
}



//this is use by citizen, official and superadmin too for login hashing of hash is done here

$("#getOTPBtn").click(function(){
    var form = document.forms['login-form'];

    $(".is-invalid").removeClass("is-invalid");

    $("#email").siblings(".invalid-feedback").html("Email Cannot be empty");
    //$("#username").siblings(".invalid-feedback").html("Username Cannot be empty");
    $("#password").siblings(".invalid-feedback").html("Password Cannot be empty");
    
    var checkForm = form.checkValidity();
    form.classList.add('was-validated');
    if( ! checkForm ){
       return;
    }
var pwd = $('#password').val();
       var hashedPwd = forge_sha256(pwd);
       $('#password').val(hashedPwd); 

    var formData = new FormData( document.forms['login-form'] );
    ajax_send_multipart(login_otp, formData, getOtpClickAjaxCall);  
});

function getOtpClickAjaxCall(resp){    
   console.log(resp);

    switch( resp.status ){
        case -1 :           
             alert(resp.msg.message);
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
            $('#password').val('');
            $('#password').focus(); 
            $(".login-div").show(); 
            $(".login-div2").show();
          //  reloadCaptcha();
           // alert(resp.msg);
          
        break;
        case 1 :
           
            document.getElementById('otpsendMessage').textContent = resp.msg;
            startCountdownTimer1();
            document.getElementById('otp-container').style.display = 'inline';
            document.getElementById('loginBtn').disabled = false;
            $(".login-div2").show();
            $(".login-div").show();
            $(".input-box").val("");
            $("#Input1").focus();            
        break;
    }
        
       
}


$("#resendBtn").click(function(){
    var formData = new FormData();
    formData.append('_token', _token);
    formData.append('email', $("#email").val() );
    ajax_send_multipart(login_otp_resend, formData, resendOTPAjaxCall);

    function resendOTPAjaxCall(resp){
        alert("OTP resend successfully");
        startCountdownTimer1();
    }
});


$("#loginBtn").click(function(){
    let otp = '';
    for (let i = 1; i <= 6; i++) {
        el = document.getElementById('Input' + i);
        if (el.value == '') {
            alert('Please enter correct OTP!');
            return false;
        }
        otp += el.value
    }
    $("#login_otp").val(otp);
    //$("#login-form").submit();
    var formData = new FormData( document.forms['login-form'] );
    ajax_send_multipart(login, formData, loginBtnClickAjaxCall);
});

function loginBtnClickAjaxCall(resp){
    switch( resp.status ){
        case 0 :
            alert(resp.msg);
            $(".input-box").val("");
            $("#Input1").focus();
        break;
        case 1 :
            $("#loading-div").show();
            location.href = welcome;
        break;
    }
  }

  