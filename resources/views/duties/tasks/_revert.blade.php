@if ($tasks['can_drop'] && $tasks['previous'])
    <button class="btn btn-md btn-warning btn-sm revert-btn" type="button">Revert back to
        {{ $tasks['previous']['tasks_name'] }}</button>
@endif
