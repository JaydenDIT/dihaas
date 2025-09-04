@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h4>Super Admin Dashboard</h4>
    </div>
    <section class="section dashboard">

        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">
                    <!-- Official Users Card -->
                    <div class="col-xxl-6 col-md-12">
                        <div class="card info-card revenue-card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                    <li><a class="dropdown-item" href="{{ route('user.getOfficialUsers') }}">View Official
                                            Users</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Official Users</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $officialCount }}</h6>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div><!-- End Revenue Card -->

                    <!-- Citizen Users Card -->
                    <div class="col-xxl-6 col-md-12">

                        <div class="card info-card customers-card">

                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                        class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                    <li><a class="dropdown-item" href="{{ route('user.getCitizenUsers') }}">View Citizens
                                            Users</a></li>
                                </ul>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Citizens Users</h5>

                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $citizenCount }}</h6>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div><!-- End Citizen Users Card -->
                </div>

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
                                        <span class="text-muted small pt-2 ps-1">Total applications submitted so far.</span>

                                    </div>
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
                                        <i class="bi bi-card-list"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $pendingAppCount }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Applications under process</span>

                                    </div>
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
                                        <i class="bi bi-card-list"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $completedAppCount }}</h6>
                                        <span class="text-muted small pt-2 ps-1">Appointment given</span>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>



                    <!-- End Proforma Counts -->



                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <!-- Notifications -->
                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Notifications <span>| Today</span></h5>

                        <div class="activity">
                            @if (count($notifications) == 0)
                                <div class="text-muted">No notifications</div>
                            @else
                                @foreach ($notifications as $notification)
                                    <div class="activity-item d-flex">
                                        <div class="activite-label">
                                            {{ date('d M, Y', strtotime($notification->created_at)) }}</div>
                                        <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                        <div class="activity-content">
                                            {{ $notification->document_caption }}<a
                                                href="{{ $notification->document_link }}" target="_blank"
                                                class="fw-bold text-dark">Click to download</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- <div class="activity-item d-flex">
                                <div class="activite-label">32 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a>
                                    beatae
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">56 min</div>
                                <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                                <div class="activity-content">
                                    Voluptatem blanditiis blanditiis eveniet
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 hrs</div>
                                <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                                <div class="activity-content">
                                    Voluptates corrupti molestias voluptatem
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">1 day</div>
                                <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                <div class="activity-content">
                                    Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati
                                        voluptatem</a> tempore
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 days</div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <div class="activity-content">
                                    Est sit eum reiciendis exercitationem
                                </div>
                            </div><!-- End activity item-->

                            <div class="activity-item d-flex">
                                <div class="activite-label">4 weeks</div>
                                <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
                                <div class="activity-content">
                                    Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
                                </div>
                            </div><!-- End activity item--> --}}

                        </div>

                    </div>
                </div><!-- End Notifications -->


            </div><!-- End Right side columns -->

        </div>
    </section>
@endsection
