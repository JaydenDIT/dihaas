@if ($tasks['can_forward'])
    @if ($tasks['next'])
        <button type="button" class="btn btn-md btn-success btn-sm forward-btn" data-bs-html="true"
            data-bs-toggle="tooltip" data-bs-placement="right"
            title="Forward to next step: <u>{{ $tasks['next']['tasks_name'] }}</u>. Click to forward.">
            Forward
        </button>
    @else
        <button type="button" class="btn btn-md btn-success btn-sm forward-btn" data-bs-html="true"
            data-bs-toggle="tooltip" data-bs-placement="bottom"
            title="You are at the final step, clicking this will complete the process.">
            Submit
        </button>
    @endif
@endif
