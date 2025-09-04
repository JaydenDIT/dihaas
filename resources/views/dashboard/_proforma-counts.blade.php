<div class="row">
    <!-- Proforma Counts -->
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card secondary-card">

            <div class="card-body">
                <h5 class="card-title">Total Applications</h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-card-list"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $totalAppCount }}</h6>
                        <span class="text-muted small pt-2 ps-1">Total applications submitted.</span>

                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-sm statusBtn" data-application_status="all">View</button>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card primary-card">

            <div class="card-body">
                <h5 class="card-title">Pending Applications</h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $pendingAppCount }}</h6>
                        <span class="text-muted small pt-2 ps-1">Applications under process</span>

                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-sm statusBtn" data-application_status="pending">View</button>
                </div>
            </div>

        </div>
    </div>
    <div class="col-xxl-4 col-md-6">
        <div class="card info-card success-card">

            <div class="card-body">
                <h5 class="card-title">Completed</h5>

                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <div class="ps-3">
                        <h6>{{ $completedAppCount }}</h6>
                        <span class="text-muted small pt-2 ps-1">Appointment given</span>

                    </div>
                </div>
                <div class="text-center mt-3">
                    <button class="btn btn-sm statusBtn" data-application_status="completed">View</button>
                </div>
            </div>

        </div>
    </div>
    <!-- End Proforma Counts -->
</div>
