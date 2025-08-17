<nav class="navbar navbar-expand-lg navbar-light bg-gradiant shadow-sm fixed-top">
    <div class="container">

        {{-- Mobile Menu Toggle --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Links --}}
        <div class="collapse navbar-collapse justify-content-end" id="navbarMenu">
            <ul class="navbar-nav align-items-center">

                <li class="nav-item">
                    <a href="{{ route('welcome') }}" class="nav-link">
                        <span>Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ url('/dihas_overview') }}" class="nav-link">
                        <span>Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/sitemap') }}" class="nav-link">
                        <span>Sitemap</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('/contact_us') }}" class="nav-link">
                        <span>Contact Us </span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="verdana_txtnone nav-link dropdown-toggle" href="#" id="navbarDropdown"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color:black;">
                        Manual
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ asset('assets/files/citizen.pdf') }}" target="_blank">Citizen User</a>
                        </li>
                        <li>
                            <a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ asset('assets/files/department.pdf') }}" target="_blank">Department User</a>
                        </li>
                        <li>
                            <a class="verdana_txtnone dropdown-item yellow-bg"
                                href="{{ asset('assets/files/superadmin.pdf') }}" target="_blank">Superadmin User</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
