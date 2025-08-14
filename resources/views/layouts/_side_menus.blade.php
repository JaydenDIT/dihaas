<div class="sidebar">
    <h4 class="px-3">{{ env('APP_NAME') }}</h4>
    <hr class="bg-light">
    <ul class="nav flex-column">
        <li class="nav-item @if (request()->routeIs('home')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item @if (request()->routeIs('admin.process.index')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('admin.process.index') }}">Process</a>
        </li>
        <li class="nav-item @if (request()->routeIs('admin.process.create')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('admin.process.create') }}">Create Process</a>
        </li>
        <li class="nav-item @if (request()->routeIs('admin.role.index')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('admin.role.index') }}">Roles</a>
        </li>
        <li class="nav-item @if (request()->routeIs('admin.task.index')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('admin.task.index') }}">Tasks</a>
        </li>
        <li class="nav-item @if (request()->routeIs('admin.task.create')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('admin.task.create') }}">Create Tasks</a>
        </li>
        <li class="nav-item @if (request()->routeIs('admin.processtaskmapping.index')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('admin.processtaskmapping.index') }}">Process Tasks Mapping</a>
        </li>
        <li class="nav-item @if (request()->routeIs('tasks.performa.all')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('tasks.performa.all') }}">All My Process</a>
        </li>
        <li class="nav-item @if (request()->routeIs('duties.proforma.create')) {{ 'active' }} @endif">
            <a class="nav-link" href="{{ route('duties.proforma.create') }}">Create Performa</a>
        </li>
    </ul>
    <hr class="bg-light">
    <div class="dropdown px-3">
        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
            {{ Auth::user()->fullname }}
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
