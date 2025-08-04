<nav class="navbar navbar-expand-lg " id="myHeader">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('home')}}">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('admin.process.index')}}">Process</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('admin.process.create')}}">Create Process</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('admin.role.index')}}">Roles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('admin.task.index')}}">Tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('admin.task.create')}}">Create Tasks</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('admin.processtaskmapping.index')}}">Process Tasks Mapping</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('tasks.performa.all')}}">All My Process</a>
            </li>

        </ul>
        <div class="d-flex">
            <ul class="navbar-nav d-flex me-auto mb-2 mb-lg-0">
                <li class="nav-item" id="loginli">
                    <a class="nav-link " href="#" role="button"
                        aria-expanded="false">
                        {{ Auth::user()->name??'' }}
                    </a>

                </li>


                <li class="nav-item"><a class="nav-link no-pointer" href="#">|</a></li>

                <li class="nav-item dropdown " id="loginli">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </a>
                    <ul class="dropdown-menu   dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('setting.profile') }}">
                                Profile Setting
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>