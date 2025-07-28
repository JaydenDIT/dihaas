@extends('layouts.app') @section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-5">
			<h1 class="display-one mt-5">Designation</h1>
			<div class="text-left"><a href="/designations" class="btn btn-outline-primary">Designation List</a></div>

			<form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-6 text-left">
					<label for="title">Designation</label>
					<div>
						<input type="text" id="desig_name" class="form-control mb-4" name="desig_name"
							placeholder="Enter designation name" value="{!! $desig->desig_name !!}"
							required>
					</div>
				</div>
				

				@csrf 
				@method('PUT')
				<div class="control-group col-6 text-left mt-2"><button class="btn btn-primary">Save Update</button></div>
		</form>
		</div>
	</div>
</div>
@endsection