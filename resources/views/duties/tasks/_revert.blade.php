    @if( $tasks['current']['allow_drop'] && $tasks['previous'])

    <button class="btn btn-success modal-revert" data-toggle="modal" data-target="#revertModalForm">Revert Back to {{$tasks['previous']['tasks_name']}}</button>



    @endif