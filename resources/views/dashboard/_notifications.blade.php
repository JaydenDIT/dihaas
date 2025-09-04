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
                            {{ $notification->document_caption }}<a href="{{ $notification->document_link }}"
                                target="_blank" class="fw-bold text-dark">Click to download</a>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

    </div>
</div>
