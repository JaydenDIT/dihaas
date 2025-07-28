@extends('layouts.app') 
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-5">
			<h1 class="display-one mt-5">Eligible Age</h1>
			<div class="text-left"><a href="/eligibilities" class="btn btn-outline-primary">Eligible Age At Present</a></div>

			<form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-6 text-left">
					<label for="title">Eligible Age </label>
					<div>
						<input type="text" id="eligible_age" class="form-control mb-4" name="eligible_age"
							placeholder="Enter eligibility age" value="{!! $eligible->eligible_age !!}"
							required autocomplete="off">
					</div>
				</div>
				
			

				@csrf 
				@method('PUT')
				<div class="control-group col-6 text-left mt-2"><button class="btn btn-primary">Save Update</button></div>
		</form>
		</div>
	</div>
</div>
<script  nonce="{{ csp_nonce() }}">
	
	$("#eligible_age").keydown(function(event) {
		k = event.which;
		if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 9) {
			if ($(this).val().length == 10) {
				if (k == 8 || k == 9) {
					return true;
				} else {
					event.preventDefault();
					return false;

				}
			}
		} else {
			event.preventDefault();
			return false;
		}

	});
</script>
@endsection