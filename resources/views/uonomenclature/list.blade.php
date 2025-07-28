@extends('.layouts.app') @section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-5">
			<h1 class="display-one m-5">UO Format</h1>
			<div class="text-left"><a href="uonomenclature/create" class="btn btn-outline-primary">Add a UO Format</a></div>

			<table class="table mt-3  text-left">
				<thead>
					<tr>
						<th scope="col">UO Prefix</th>
						<th scope="col">File Number</th>
						<th scope="col">Year</th>
						<th scope="col">Suffix</th>
						<th scope="col">Action</th>
					</tr>
				</thead>
				<tbody>
				@forelse($uonomenclatures as $uonomenclature)
					<tr>
						
						<td>{!! $uonomenclature->uo_format !!}</td>
						<td>{!! $uonomenclature->uo_file_no !!}</td>
						<td>{!! $uonomenclature->year !!}</td>
						<td>{!! $uonomenclature->suffix !!}</td>
						<td>
							
						<a href="uonomenclature/{!! $uonomenclature->id !!}/edit"
							class="btn btn-outline-primary">Edit</a>
							 <button type="button" class="btn btn-outline-danger ml-1"
								onClick='showModel({!! $uonomenclature->id !!})'>Delete</button></td> 
					</tr>
					@empty
					<tr>
						<td colspan="3">No UO Format found</td>
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
	frmDelete.action = 'uonomenclature/delete/'+id;
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



