{{-- OTP Verification Page --}}
@extends('layouts.guest')

@section('content')
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="card shadow-lg p-4 w-100" style="max-width: 400px;">
            <div class="text-center mb-3">
                <h4 class="fw-bold">OTP Verification</h4>
            </div>

            {{-- Flash messages (error/success) --}}
            @if (session('error'))
                <div class="alert alert-danger small">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success small">{{ session('success') }}</div>
            @endif

            {{-- OTP Form --}}
            <form action="{{ route('otp.verify') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="user_id" value="{{ session('2fa_user_id') }}" />

                <div class="mb-3">
                    <label for="otp" class="form-label fw-semibold">Enter OTP</label>
                    <input type="text" name="otp" id="otp" class="form-control text-center"
                        placeholder="000000" maxlength="6" inputmode="numeric" required>
                    @error('otp')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <button class="btn btn-primary w-100" type="submit">Verify OTP</button>
            </form>

            {{-- Resend OTP --}}
            <div class="text-center mt-3">
                <form action="{{ route('otp.resend') }}" method="POST" id="resendOtpForm">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ session('2fa_user_id') }}" />
                    <button type="submit" class="btn btn-outline-secondary w-100" id="resendOtpBtn" disabled>
                        Resend OTP <span id="countdown">(30s)</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Countdown Script --}}
    <script>
        let countdown = 30; // seconds
        const countdownEl = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendOtpBtn');

        const timer = setInterval(() => {
            countdown--;
            countdownEl.textContent = `(${countdown}s)`;
            if (countdown <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                countdownEl.textContent = "";
            }
        }, 1000);
    </script>
@endsection
