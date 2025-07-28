@extends('.layouts.app') @section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-3">
			<h1 class="display-one m-5">Reject Remarks</h1>
			<div class="text-left"><a href="remarksreject/create" class="btn btn-outline-primary">Add new
				remarks</a></div>

			<table class="table mt-3 text-left">
				<thead>
					<tr>
					
						<th scope="col">Remarks </th>
						
					
						<th >Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse($remarksrejects as $remarksreject)
					<tr>
						
				
						<td>{!! $remarksreject->probable_remarks !!}</td>
					
						
						<td><a href="remarksreject/{!! $remarksreject->id !!}/edit"
							class="btn btn-outline-primary">Edit</a>
							<button type="button" class="btn btn-outline-danger ml-1"
								onClick='showModel({!! $remarksreject->id !!})'>Delete</button></td>
					</tr>
					@empty
					<tr>
						<td colspan="3">No remarks found</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="modal fade" id="deleteConfirmationModel" tabindex="-1" remarksreject="dialog"
	aria-labelledby="myModalLabel">
	<div class="modal-dialog" remarksreject="document">
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
	frmDelete.action = 'remarksreject/delete/'+id;
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



