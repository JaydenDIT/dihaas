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
			<h1 class="display-one mt-5 text-center">Role</h1>
			<div class="text-left"><a href="/roles" class="btn btn-outline-primary">Role List</a></div>

			<form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-8 text-left">
				<div class="flex1">
              <div class="div1"> <label for="role_id">Role Id</label> </div>
              <div class="div2"> 
				<input type="text" id="role_id" class="form-control mb-4 " name="role_id" placeholder="Enter Role Code" class="input1"  value="{!! $role->role_id !!}" required> </div>
            </div>

            <div class="flex1">
              <div class="div1"> <label for="role_name">Role Name</label></div>

              <div class="div2"><input type="text" id="role_name" class="form-control mb-4" name="role_name" placeholder="Enter Role Name"  value="{!! $role->role_name !!}" required></div>

            </div>


           
       

           

      
				</div>


				@csrf
				@method('PUT')
				<div class="control-group col-6 text-center mt-2"><button class="btn btn-primary">Save Update</button></div>
			</form>
		</div>
	</div>
</div>

<script  nonce="{{ csp_nonce() }}">
	
    $("#role_id").keydown(function(event) {
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
</body>
