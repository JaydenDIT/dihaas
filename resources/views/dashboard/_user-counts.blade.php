<div class="row">
    <!-- Official Users Card -->
    <div class="col-sm-6">
        <div class="card info-card revenue-card">

            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
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
    <div class="col-sm-6">

        <div class="card info-card customers-card">

            <div class="filter">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
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
