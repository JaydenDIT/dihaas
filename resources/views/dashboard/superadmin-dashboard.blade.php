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

                @include('dashboard._proforma-counts')
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Notifications -->
                <div class="card" style="min-height: 380px; max-height:390; overflow-y:auto;">
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

                        </div>

                    </div>
                </div><!-- End Notifications -->


            </div><!-- End Right side columns -->

        </div>
    </section>
    @include('dashboard._proforma-list-section')
@endsection
