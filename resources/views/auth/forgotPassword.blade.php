@extends('layouts.app')

@section('content')

<div class="container my-5 mb-5 text-center align-middle">


        <div class="card col-md-6 bg-light my-3 py-2 mx-auto shadow">
            <div class="card-header text-center">
                <h4><b>{{ __('Reset Password') }}</b></h4>
            </div>
            <div class="card-body">
                <form class="row g-3 needs-validation" action="{{route('password.reset')}}" method="POST" >
                @csrf
                    <div class="vstack gap-2 mt-5">
                        <div class="input-group has-validatation">
                            <span class="input-group-text" id="Email-Field"><i class="fa-solid fa-at" style="color: #000046;"></i></span>
                            <input type="email" name="email" id="user_email" class="col-md-8 form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                            placeholder="{{ __('Email') }}" aria-label="Email" aria-describedby="Email-Field" autocomplete="off" required autofocus>

                            <div class="invalid-feedback">
                                @if ($errors->has('email'))
                                    {{ $errors->first('email') }}
                                @else
                                    Please enter your email.
                                @endif
                            </div>
                        </div>
                        <button type="submit" style="background-color: 000046;" class="btn btn-info col-md-4 mt-4 mx-auto" onClick="$('#loading-div').show();" >Reset Password</button>
                      </div>
                </form>
            </div>
        </div>
</div>

<script>
    $(function () {
       $('.footer-sm-sec').addClass('fixed-bottom');
    });
</script>

@endsection
