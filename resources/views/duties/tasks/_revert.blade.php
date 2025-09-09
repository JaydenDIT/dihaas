@if ($tasks['can_drop'] && $tasks['previous'])
    <button type="button" class="btn btn-md btn-warning btn-sm revert-btn" data-bs-toggle="tooltip"
        data-bs-placement="left" data-bs-html="true"
        title="Revert back to previous step:
        <u>'{{ $tasks['previous']['tasks_name'] }}'</u>. Click to revert.">
        Revert
    </button>
@endif
