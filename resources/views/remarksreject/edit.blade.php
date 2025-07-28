<head>
	<style>
		 .flex1 {
      display: flex;
    }

    .div1 {
      width: 250px;
    }

    .div2 {
      width: 500px;
    }
	</style>
</head>



<body>
	
@extends('layouts.app') @section('content')
<div class="container">
	<div class="row">
		<div class="col-12  pt-5">
			<h1 class="display-one mt-5 text-center">Reject Remarks</h1>
			<div class="text-left"><a href="/remarksrejects" class="btn btn-outline-primary">Remarks List</a></div>

			<form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-8 text-left">
				
             

            <div class="flex1">
              <div class="div1"> <label for="probable_remarks">Remarks</label></div>

              <div class="div2"><input type="text" id="probable_remarks" class="form-control mb-4" value="{!! $remarksreject->probable_remarks !!}" name="probable_remarks" placeholder="Enter Remarks" required></div>

            </div>
		</div>


				@csrf
				@method('PUT')
				<div class="control-group col-6 text-center mt-2"><button class="btn btn-primary">Save Update</button></div>
			</form>
		</div>
	</div>
</div>
@endsection
</body>
