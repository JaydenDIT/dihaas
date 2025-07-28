@extends('layouts.app') @section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-5">
			<h1 class="display-one mt-5">Grade</h1>
			<div class="text-left"><a href="/grades" class="btn btn-outline-primary">Grade List</a></div>

			<form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-6 text-left">
					<label for="title">Grade</label>
					<div>
						<input type="text" id="grade_name" class="form-control mb-4" name="grade_name"
							placeholder="Enter grade name" value="{!! $grade->grade_name !!}"
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