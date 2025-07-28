

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <title>DIHAS</title>
 
  <style nonce="{{ csp_nonce() }}"> 

    .button {
      text-align: center;
    }

.flex{
    display: flex;
    flex-direction:row;
}






    /* .registration_form input[type="text"], input[type="email"], input[type="password"]:focus:not([readonly]) {
      border: 1px sol
      
      
      
      #609;
      box-shadow: 0 1px 0 0 #609;
    } */
    input[type=text]:focus:not([]) {
      border: 1px solid #609;
      box-shadow: 0 1px 0 0 #609;
    }

    
    .form-control {

      border: 2px solid #609;
      border-radius: 10px;

    }

  
  </style>



</head>

<body>
@extends('layouts.app')
@section('content')


@if (session('status'))
<div class="alert alert-success" role="alert">
	<button type="button" class="close" data-dismiss="alert">×</button>
	{{ session('status') }}
</div>
@elseif(session('failed'))
<div class="alert alert-danger" role="alert">
	<button type="button" class="close" data-dismiss="alert">×</button>
	{{ session('failed') }}
</div>
@endif
<div class="container">
	<div class="row">
		<div class="col-12 text-center pt-5">
			<h1 class="display-one mt-5">New Education Form</h1>
			<div class="text-left"><a href="/educations" class="btn btn-outline-primary">Education List</a></div>

			<form id="add-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-6 text-left">
					<label for="edu_name">Education Name</label>
					<div>
						<input type="text" id="edu_name" class="form-control mb-4" name="edu_name"
							placeholder="Enter Education" required>
					</div>
				</div>
				
				@csrf

				<div class="control-group col-6 text-left mt-2"><button class="btn btn-primary">Add New Education</button></div>
			</form>
		</div>
	</div>
</div>
   
  @endsection
</body>
</html>