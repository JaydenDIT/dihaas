@extends('layouts.app')
@section('content')

<div class="container my-3 mb-5">

   
    
    <div class="card bg-light my-3 py-2">
        <div class="card-header">
            <i class="fa-solid fa-user-plus fa-beat-fade colour1"></i>&ensp;<b>Change Password</b>
        </div>
        <div class="card-body">
            <form method="POST" name="password-form" id="password-form" class="row g-3 needs-validation" novalidate
                enctype="multipart/form-data">
                @csrf
                <!-- Password -->
                <div class="col-md-6">
                    <label for="old_password" class="form-label"><b>Old Password</b></label>
                    <input type="password" name="old_password" id="old_password" placeholder="{{ __('Old Password') }}"
                        class="form-control @error('old_password') is-invalid @enderror" required>
                    <div class="invalid-feedback" role="alert">
                        Please enter your old password.
                    </div>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-6">
                    <label for="password" class="form-label"><b>Password</b></label>
                    <input type="password" name="password" id="password" placeholder="{{ __('Password') }}"
                        class="form-control @error('password') is-invalid @enderror" required>
                    <div class="invalid-feedback" role="alert">
                        Please enter your password.
                    </div>
                </div>
                <div class="col-md-6"></div>
                <!-- Conf-Password -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label"><b>Confirm Password</b></label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="{{ __('Confirm Password') }}"
                        class="form-control @error('password_confirmation') is-invalid @enderror" required>
                    <div class="invalid-feedback" role="alert">
                        Please enter your confirmation password.
                    </div>
                </div>
                <div class="col-12">
                    <button id="password-form_id" type="button" class="btn btn-primary"
                        >Submit</button>
                </div>

            </form>
        </div>
    </div>

</div>




<script nonce="{{ csp_nonce() }}">
var _token = "{{ csrf_token() }}";

var savePassword = "{{ route('setting.saveProfilePassword') }}";
</script>
<script type="text/javascript" src="{{ asset('assets/js/auth/forge-sha256.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/auth/editProfile.js') }}"></script>

@endsection