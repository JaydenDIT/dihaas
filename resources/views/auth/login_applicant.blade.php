    @extends('layouts.app')

    @section('content')


    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7">
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if($errors)
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
                @endif
                <div class="card">
                    <h5 class="text-center my-3 " style="color:crimson">Information: Install Sandes App from Google Playstore to receive OTP and Message from the Portal</h5>
                    <div class="card-header" align="center" style="font-family: Elephant;font-size: 20px;font-weight:bold;line-height:66px;color: mediumblue">
                        {{ __('Citizen  Login') }}
                    </div>
                    <!-- <span align="center" style="font-family: Elephant;font-size: 15px;font-weight:bold;line-height:66px;color: rgb(245, 49, 55);"> No need to select Department Name for DP Users</span> -->

                    <div class="card-body">

                        <form method="POST" action="#" name="login-form" id="login-form">
                            @csrf

                           <input type="hidden" id="login_otp" name="login_otp">

                            <div class="mb-3 row">
                                <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email
                                    Address</label>
                                <div class="col-md-6">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    <div class="invalid-feedback" role="alert">
                                    </div>
                                </div>
                            </div>
                            <p>
                                <!-- Department -->
                                <!-- Department -->



                            <div class="mb-3 row">
                                <label for="password" class="col-md-4 col-form-label text-md-end text-start">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                    <div class="invalid-feedback" role="alert">
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-5 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        </form>

                        <div class="row mb-1">
                            <div class="col-md-5 offset-4">
                                <button type="button" class="btn btn-primary" id="getOTPBtn">Get
                                    OTP</button>
                            </div>
                        </div>

                        <div class="row mb-1 login-div" style="display: none;">
                            <div class="col-md-6 offset-4" style="display: flex; align-items: center;">
                                <p style=" margin: 0;">Didn't receive an OTP?</p>
                                <button class="link" type="button" id="resendBtn" style="border-radius: 50px; color:blue; margin: 0 0 0 10px;  background-color: white; border-color: rgba(0, 0, 255, 0);" disabled>
                                    Resend OTP
                                </button>
                            </div>


                            <div class="row mb-1 text-center">

                                <div id="countdownMessage" style="color: blue;"></div>
                                <div id="otpsendMessage" style="color: blue;"></div>

                            </div>



                            <div class="row-mb-1 text-center">
                                <div class="text-center" id="otp-container" style="display:none">
                                    <input class="input-box" id="Input1" type="text" maxlength="1">
                                    <input class="input-box" id="Input2" type="text" maxlength="1">
                                    <input class="input-box" id="Input3" type="text" maxlength="1">
                                    <input class="input-box" id="Input4" type="text" maxlength="1">
                                    <input class="input-box" id="Input5" type="text" maxlength="1">
                                    <input class="input-box" id="Input6" type="text" maxlength="1">
                                    <input type="hidden" id="combined-input" name="otp">
                                </div>
                            </div>

                            <p>
                            <!-- <div>
                        <p>
                        <div class="col-sm-12 center">
                           
                            <a style="text-align:center" href="{{route('citizen.register')}}" class="link">Don't have an account?Sign Up</a> or
                          
                            <a style="text-align:center" href="{{route('password.forgot')}}" class="link">Forgot Password?</a>
                        </div>
                    </div> -->

                            <div class="row mb-3 justify-content-center">
                                <button type="button" class="btn btn-primary" disabled id="loginBtn" style="width:80px;">
                                    Login
                                </button>


                            </div>
                        </div>

                        <div style="text-align:center">
                            <p>
                            <div col-sm-12>
                              
                                <a href="{{route('citizen.register')}}" class="link">Don't have an account?Sign Up</a> or


                                <a href="{{route('password.forgot')}}" class="link">Forgot Password?</a>

                            </div>
                        </div>



                        <!-- <div>Registration closes in <span id="time">05:00</span> minutes!</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--//////////////////// From this to move the CURSOR  to the next   /////////////////////////////////////////-->
    <!-- If a character has been entered (when the value.length is equal to 1), it will move the focus to the next input box  -->
   
    <script nonce="{{ csp_nonce() }}">
        var _token = "{{ csrf_token() }}";
        var login_otp = "{{ route('smsLoginCitizenOTP') }}";
        var login_otp_resend = "{{ route('smsLoginCitizenOTPResend') }}";
      //  var captchaUrl = "{{ route('reloadCaptcha') }}";
    //var authenticate_url = "{{ route('authenticate') }}";
    var login = "{{ route('authenticate.citizen') }}";
    var welcome = "{{route('welcome')}}";
    </script>
 <script type="text/javascript" src="{{ asset('assets/js/auth/forge-sha256.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/auth/login.js') }}"></script>


    @endsection
