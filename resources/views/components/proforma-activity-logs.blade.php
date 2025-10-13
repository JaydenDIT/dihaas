<div class="dashboard">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Proforma Status</h5>

            <div class="activity">
                @foreach ($logs as $log)
                    <div class="activity-item d-flex">
                        <div class="activite-label">{{ date('d M, Y', strtotime($log->created_at)) }}</div>
                        <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                        <div class="activity-content">
                            <div class="fw-bold">{{ $log->tasks_name ?? 'Unknown Action' }} ({{ $log->action_name }})
                            </div>
                            <div class="small">{{ $log->action_remark ?? '' }}</div>
                            <p class="small text-muted">
                                By {{ $log->actionBy->fullname ?? 'System' }} <span
                                    class="fw-bold">({{ $log->actionBy->role->role_name }})</span>
                            </p>
                        </div>
                    </div><!-- End activity item-->
                @endforeach
                @forelse ($remainingTasks as $task)
                    <div class="activity-item d-flex">
                        <div class="activite-label">Pending .......</div>
                        <i class='bi bi-circle-fill activity-badge text-secondary align-self-start'></i>
                        <div class="activity-content">
                            <div class="fw-bold">{{ $task->tasks_name ?? 'Unknown Action' }}
                            </div>
                            <div class="small">{{ $task->tasks_description ?? '' }}</div>
                            <p class="small text-muted">
                                To be done by
                                {{ $task->roles()->pluck('role_name')->implode(', ') }}
                            </p>
                        </div>
                    </div><!-- End activity item-->
                @endforeach
            </div>

        </div>
    </div><!-- End Recent Activity -->
</div>
