@extends('layouts.app')

@section('content')


<div class="container my-3 mb-5 ">
   
    <br>
   <br> <br>
   <br>
<p></p>
<br>
<h4 class="text-center my-5 text-danger">Information: Install Sandes App from Google Playstore to receive OTP and Message from the Portal</h4>
  
    <h2 class="text-center my-5">REGISTER</h2>

    <div class="card bg-light my-3 py-2">
        <div class="card-header" style="color: blue;text-align: center;">
        <i class="fa-solid fa-user-plus fa-beat-fade"></i>&ensp;<b>New User
                Registration Form</b>
        </div>

        <div class="card-body">
            <form method="POST" name="new_user_form" id="new_user_form" class="row g-3 needs-validation" novalidate
                enctype="multipart/form-data">
                @csrf
                <!--full name-->
                <div class="col-md-6">
                    <label for="name" class="form-label"><b>Name</b></label>
                    <input type="text" id="name" name="name" placeholder="{{ __('Name') }}"
                        class="form-control" required autofocus />

                    <div class="invalid-feedback" role="alert">
                        Please enter name.
                    </div>
                </div>

                <!--Mobile Number -->
                <div class="col-md-6">
                    <label for="mobile" class="form-label"><b>Mobile</b></label>
                    <input type="text" name="mobile" id="mobile" placeholder="{{ __('Mobile') }}" maxlength="10" pattern="[0-9]{10}"
                        class="form-control" required />
                    <div class="invalid-feedback" role="alert">
                        Please enter Mobile Number.
                    </div>
                </div>

                <!--Email-->
                <div class="col-md-6">
                    <label for="email" class="form-label"><b>Email</b></label>
                    <input type="email" id="email" name="email" placeholder="{{ __('Email') }}"
                        class="form-control" required>
                    <div class="invalid-feedback" role="alert">
                        Please enter Email.
                    </div>
                </div>

                <!--gender-->
                <div class="col-md-6">
                    <label for="email" class="form-label"><b>Gender:</b></label>
                    <div class="hstack gap-3">
                        <div class="form-check">
                            <input type="radio" name="gender" value="male"
                                class="form-check-input" required>
                            <label class="form-check-label" for="gender">
                                Male
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="gender" value="female"
                                class="form-check-input" required>
                            <label class="form-check-label" for="gender">
                                Female
                            </label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="gender" value="transgender"
                                class="form-check-input" required>
                            <label class="form-check-label" for="gender">
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="invalid-feedback" role="alert">
                        Please Select gender.
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="relative_name" class="form-label register-rsp"><b>Father's/Mother's/Spouse's Name</b></label>
                    <input type="text" name="relative_name" placeholder="{{ __('Name of Father/Mother/Spouse') }}"
                        class="form-control" required>
                    <div class="invalid-feedback" role="alert">
                        Please Select One.
                    </div>
                </div>
                
                <div class="col-md-6">
                    <label for="relationship_id" class="form-label register-rsp"><b>Relationship with the Applicant</b></label>
                    <select name="relationship_id" id="relationship_id" aria-label="Select Relation with applicant"
                        class="form-select" required>
                        <option value="" disabled selected>Choose....</option>
                        @foreach($relationships as $row)
                        <option value="{{ $row->id }}">{{ $row->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" role="alert">
                        Please Select One.
                    </div>
                </div>

                <!--ID PROOF-->
                            
               
                <!--Current Address-->
                <div class="col-md-6">
                    <div class="hstack">
                        <div><b>Current Address</b> &ensp;</div>
                    </div>
                    <div class="vstack gap-2 card bg-light border-dark my-3 py-2">
                        <div class="container">
                            <div>
                                <!--current_address1-->
                                <input type="text" name="current_address1" id="current_address1"
                                    class="form-control mb-2" required>
                                <!--current_address2-->
                                <input type="text" name="current_address2" id="current_address2"
                                    class="form-control mb-2">
                                <!--current_address3-->
                                <input type="text" name="current_address3" id="current_address3"
                                    class="form-control mb-2">
                                <div class="invalid-feedback" role="alert">
                                    Please enter Address.
                                </div>
                            </div>


                            <!--pincode-->
                            <div>
                                <label for="current_pin" class="form-label"><b>Pincode</b></label>
                                <input type="text" name="current_pin" id="current_pin"
                                    class="form-control mb-2" min-length="6"
                                    max-length="6" required>
                                <div class="invalid-feedback" role="alert">
                                    Please enter Pin Code.
                                </div>
                            </div>

                            {{-- <div>
                            <label for="current_country_id" class="form-label"><b>Country</b></label>
                                <select name="current_country_id" id="current_country_id" data-change="current_state_id"
                                    
                                    class="form-select funSelectChange" required>

                                    <option value="" disabled selected>Choose Country....</option>
                                    @foreach($countries as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" role="alert">
                                    Please Select Country.
                                </div>
                            </div> --}}

                            <!--state-->
                            <div>
                                <label for="current_state_id" class="form-label"><b>State</b></label>
                                <select name="current_state_id" id="current_state_id" data-change="current_district_id"                                  
                                    class="form-select funSelectChange" required>
                                    <option value="" disabled selected>Choose State....</option>
                                    @foreach($states as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" role="alert">
                                    Please Select State.
                                </div>
                            </div>

                            <!--district-->
                            <div>
                                <label for="current_district_id" class="form-label"><b>District</b></label>
                                <select name="current_district_id" id="current_district_id"
                                    class="form-select" required>
                                    <option value="" disabled selected>Choose District....</option>
                                </select>
                                <div class="invalid-feedback" role="alert">
                                    Please Select District.
                                </div>
                            </div>
                            
                           
                        </div>
                    </div>
                </div>
                <!-- End of Current Address -->

                <!--permanant address-->
                <div class="col-md-6">
                <div class="hstack">
                        <div><b>Permanent Address</b> &ensp;</div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkPermanent">
                            <label class="form-check-label">
                                Same as Current Address
                            </label>
                        </div>
                    </div>
                    <div class="vstack gap-2 card bg-light border-dark my-3 py-2">
                        <div class="container">
                            <div>
                                <!--permanent_address1-->
                                <input type="text" name="permanent_address1" id="permanent_address1"
                                    class="form-control mb-2"
                                    required>
                                <!--permanent_address2-->
                                <input type="text" name="permanent_address2" id="permanent_address2"
                                    class="form-control mb-2">
                                <!--permanent_address3-->
                                <input type="text" name="permanent_address3" id="permanent_address3"
                                    class="form-control mb-2">
                                <div class="invalid-feedback" role="alert">
                                    Please enter Address.
                                </div>
                            </div>


                            <!--pincode-->
                            <div>
                                <label for="permanent_pin" class="form-label"><b>Pincode</b></label>
                                <input type="text" name="permanent_pin" id="permanent_pin"
                                    class="form-control mb-2"
                                    min-length="6" max-length="6" required>
                                <div class="invalid-feedback" role="alert">
                                    Please enter Pin Code.
                                </div>
                            </div>
                            {{-- <div>
                            <label for="permanent_country_id" class="form-label"><b>Country</b></label>
                                <select name="permanent_country_id" id="permanent_country_id" data-change="permanent_state_id"                                    
                                    class="form-select funSelectChange" required>
                                    <option value="" disabled selected>Choose Country....</option>
                                    @foreach($countries as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" role="alert">
                                    Please Select Country.
                                </div>
                            </div> --}}

                            <!--state-->
                            <div>
                                <label for="permanent_state_id" class="form-label"><b>State</b></label>
                                <select name="permanent_state_id" id="permanent_state_id" data-change="permanent_district_id"                                   
                                    class="form-select funSelectChange" required>
                                    <option value="" disabled selected>Choose State....</option>
                                    @foreach($states as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" role="alert">
                                    Please Select State.
                                </div>
                            </div>

                            
                            <!--district-->
                            <div>
                                <label for="permanent_district_id" class="form-label"><b>District</b></label>
                                <select name="permanent_district_id" id="permanent_district_id"
                                    class="form-select" required>
                                    <option value="" disabled selected>Choose District....</option>
                                </select>
                                <div class="invalid-feedback" role="alert">
                                    Please Select District.
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <!-- End of Permanent Address -->

                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label"><b>Password</b></label>
                    <input type="password" name="password" id="password" placeholder="{{ __('Password') }}"
                        class="form-control" required>
                    <div class="invalid-feedback" role="alert">
                        Please enter your password.
                    </div>
                </div>

                <!-- Conf-Password -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label"><b>Confirm Password</b></label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="{{ __('Confirm Password') }}"
                        class="form-control" required>
                    <div class="invalid-feedback" role="alert">
                        Please enter your confirmation password.
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input @error('iagree') is-invalid @enderror" type="checkbox"
                            name="iagree" id="iagree" required>
                        <label class="form-check-label" for="flexCheckDefault">
                            I agree all the above information are correct.
                        </label>
                        <div class="invalid-feedback" role="alert">
                            Please confirm.
                        </div>
                    </div>
                </div>

                <div class="col-12">
                <button type="button" class="btn btn-primary" id="registerSave" >{{ __('Register') }}</button>
                </div>
            </form>
        </div>
        <!--End of card body-->

    </div>
</div>



<!-- Modal to edit court name -->
<div class="modal fade" id="otpModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <!-- class="needs-validation" -->
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="needs-validation" name="otp-form" id="otp-form" method="POST">
                <div class="modal-body">
                    @csrf

                    <div class="row">
                        <div class="alert alert-danger" style="display: none;" id="otp_error_msg">
                        </div>
                        <div class="col-md-12 text-center fw-bold" style="color: GREEN;"><i id="mobileOtpLabel"></i>
                            <a href="javascript: resendOTP('mobile');">( Resend OTP )</a>
                        </div>
                        <div class="col-md text-end fw-bold">Enter OTP :</div>
                        <div class="col-md">
                            <input type="number" maxlength="10" class="" name="mobile_otp" class="" id="mobile_otp">
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-12 text-center fw-bold" style="color: GREEN;"><i id="emailOtpLabel"></i>
                            <a href="javascript: resendOTP('email');">( Resend OTP )</a>
                        </div>
                        <div class="col-md text-end fw-bold">Enter OTP :</div>
                        <div class="col-md">
                            <input type="number" maxlength="10" class="" name="email_otp" id="email_otp">
                        </div>
                    </div> -->
                    <div class="row">
                        <i class="text-center text-danger">Note: The OTP may be delayed due to network issues.
                            Please wait for 2-6 minutes and try requesting for another OTP by clicking the "Resend OTP"
                            button.</i>
                    </div>

                    <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="otp_submit">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script nonce="{{ csp_nonce() }}">
var _token = "{{ csrf_token() }}";


var districtoption_url = "{{ route('district.getOption') }}";

var register_url = "{{ route('citizen.preRegistration') }}";
var otpsubmit_url = "{{ route('citizen.saveRegistration') }}";

var login_url = "{{ route('welcome') }}";

var resendSMSOTP = "{{ route('citizen.smsRegistrationOTP') }}";
var resendEmailOTP = "{{ route('citizen.emailRegistrationOTP') }}";
</script>

<script type="text/javascript" src="{{ asset('assets/js/auth/forge-sha256.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/auth/register.js') }}"></script>
@endsection