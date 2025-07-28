@extends('.layouts.app') @section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-3">
			<h1 class="display-one m-5">Application Number</h1>
			<div class="text-left"><a href="applicationnumber/create" class="btn btn-outline-primary">Add One Format for Application Number</a></div>

			<table class="table mt-3 text-left">
				<thead>
					<tr>
						<th scope="col" >Prefix (Application Number Format before any number)</th>
						<th scope="col" >Suffix (Number of digit after prefix format)</th>
										
						
					
						<th >Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse($applicationnumbers as $applicationnumber)
					<tr>
						
						<td>{!! $applicationnumber->prefix !!}</td>
						<td>{!! $applicationnumber->suffix !!}</td>
											
						
						<td><a href="applicationnumber/{!! $applicationnumber->id !!}/edit"
							class="btn btn-outline-primary">Edit</a>
							<button type="button" class="btn btn-outline-danger ml-1"
								onClick='showModel({!! $applicationnumber->id !!})'>Delete</button></td>
					</tr>
					@empty
					<tr>
						<td colspan="3">No applicationnumber found</td>
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

<script>
function showModel(id) {
	var frmDelete = document.getElementById("delete-frm");
	frmDelete.action = 'applicationnumber/delete/'+id;
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



