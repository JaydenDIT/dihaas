<form action="#" class="text-center w-100">
    <h1>Verify application</h1>

    @if( $tasks['current']['allow_drop'] && $tasks['previous'])
    <button class="btn btn-success">Revert Back to {{$tasks['previous']['tasks_name']}}</button>
    @endif


    <button class="btn btn-success">Verify</button>

    @if( $tasks['next'] )
    <button class="btn btn-success">Forward to {{$tasks['next']['tasks_name']}} </button>
    @else
    <button class="btn btn-success">Submit </button>
    @endif

    @if( $tasks['current']['allow_reject'] )
    <button class="btn btn-danger">Reject</button>
    @endif
</form>