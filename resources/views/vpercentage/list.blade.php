@extends('.layouts.app') 
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-5">
			<h1 class="display-one m-5">Vacancy Percentage At Present</h1>
			
			@if ($vpercent->count() == 0)
			<div class="text-left"><a href="vpercentage/create" class="btn btn-outline-primary">Add new
				Vacancy Percentage</a></div>
				@endif
			<table class="table mt-3  text-left">
				<thead>
					<tr>
						<th scope="col">Vacancy Percentage</th>
						<th scope="col">Date</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse($vpercent as $vpercentage)
					<tr>
						
						<td>{!! $vpercentage->vpercentage !!}</td>
						<td>{!! $vpercentage->year !!}</td>
						<td><a href="vpercentage/{!! $vpercentage->id !!}/edit"
							class="btn btn-outline-primary">Edit</a>
							<button type="button" class="btn btn-outline-danger ml-1"
								onClick='showModel({!! $vpercentage->id !!})'>Delete</button></td>
					</tr>
					@empty
					<tr>
						<td colspan="3">No percentage found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="modal fade" id="deleteConfirmationModel" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body">Are you sure to delete this record?</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" onClick="dismissModel()">Cancel</button>
				<form id="delete-frm" class="" action="" method="POST">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger">Delete</button>
                </form>
			</div>
		</div>
	</div>
</div>

<script nonce="{{ csp_nonce() }}">
function showModel(id) {
	var frmDelete = document.getElementById("delete-frm");
	frmDelete.action = 'vpercentage/delete/'+id;
	var confirmationModal = document.getElementById("deleteConfirmationModel");
	confirmationModal.style.display = 'block';
	confirmationModal.classList.remove('fade');
	confirmationModal.classList.add('show');
}

function dismissModel() {
	var confirmationModal = document.getElementById("deleteConfirmationModel");
	confirmationModal.style.display = 'none';
	confirmationModal.classList.remove('show');
	confirmationModal.classList.add('fade');
}
</script>
@endsection



