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
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="{{route('duties.proforma.create')}}">Create Performa</a>
            </li>

        </ul>

    </div>
</nav>