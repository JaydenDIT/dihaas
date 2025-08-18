@extends('layouts.guest')

@section('content')
    <div class="container my-5">

        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark border-bottom pb-2 d-inline-block">
                <i class="fas fa-headset text-primary me-2"></i> Contact Us
            </h2>
            <p class="text-muted mt-3">
                In case of any problem you may contact between <strong>9:00 AM</strong> to <strong>5:00 PM</strong> in
                Working Days Only to <br>
                Concerned Officials.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Email Card -->
            <div class="col-md-5">
                <div class="card shadow-lg border-0 h-100 rounded-3">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="fas fa-envelope fa-3x text-white bg-primary rounded-circle p-3 shadow"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Email Us</h5>
                        <p class="text-muted">For general queries, drop us a mail:</p>
                        <p class="mb-1">
                            <i class="fas fa-paper-plane text-primary me-2"></i>
                            <a href="mailto:ddo.gad.man@gmail.com" class="text-decoration-none">
                                ddo.gad.man@gmail.com
                            </a>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-paper-plane text-primary me-2"></i>
                            <a href="mailto:example2@example.com" class="text-decoration-none">
                                example2@example.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Phone Card -->
            <div class="col-md-5">
                <div class="card shadow-lg border-0 h-100 rounded-3">
                    <div class="card-body text-center p-4">
                        <div class="icon mb-3">
                            <i class="fas fa-phone-alt fa-3x text-white bg-success rounded-circle p-3 shadow"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Call Us</h5>
                        <p class="text-muted">Our support team is available at:</p>
                        <p class="mb-1">
                            <i class="fas fa-phone text-success me-2"></i>
                            <a href="tel:+123456789" class="text-decoration-none">+123456789</a>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-phone text-success me-2"></i>
                            <a href="tel:+987654321" class="text-decoration-none">+987654321</a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
