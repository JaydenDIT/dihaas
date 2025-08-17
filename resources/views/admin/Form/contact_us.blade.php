@extends('layouts.guest')

@section('content')
    <div class="container my-5">

        <div class="text-center mb-4">
            <h2 class="fw-bold text-success border-bottom pb-2 d-inline-block">Contact Us</h2>
            <p class="text-muted mt-2">
                In case of any problem you may contact between <strong>9:00 AM to 5:00 PM</strong> on working days only.
            </p>
        </div>

        <div class="row g-4">
            <!-- Email Section -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center position-relative">
                        <div class="mb-3">
                            <i class="fas fa-envelope fa-3x text-primary bg-light rounded-circle p-3 shadow-sm"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">For any queries, email us:</h5>
                        <p class="mb-1"><strong>Email 1:</strong> ddo.gad.man@gmail.com</p>
                        <p class="mb-0"><strong>Email 2:</strong> example2@example.com</p>
                    </div>
                </div>
            </div>

            <!-- Phone Section -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body text-center position-relative">
                        <div class="mb-3">
                            <i class="fas fa-phone fa-3x text-success bg-light rounded-circle p-3 shadow-sm"></i>
                        </div>
                        <h5 class="card-title fw-bold mb-3">Or call us at:</h5>
                        <p class="mb-1"><strong>Phone 1:</strong> +123456789</p>
                        <p class="mb-0"><strong>Phone 2:</strong> +987654321</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
