@if( $tasks['can_forward'] )

@if( $tasks['next'] )

{{--  --}}
<button class="btn btn-md btn-success forward-btn" type="button">Forward to {{$tasks['next']['tasks_name']}} </button>
@else
<button class="btn btn-md btn-success forward-btn" type="button">Submit </button>
@endif


@endif