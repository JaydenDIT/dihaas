@if( $tasks['next'] )
<button class="btn btn-success modal-forward" data-toggle="modal" data-target="#forwardModalForm">Verify and Forward to {{$tasks['next']['tasks_name']}} </button>
@else
<button class="btn btn-success">Verify </button>
@endif






<!-- Modal -->
<div class="modal fade" id="forwardModalForm" tabindex="-1" role="dialog" aria-labelledby="forwardModalFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>