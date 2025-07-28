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
			<h1 class="display-one mt-5 text-center">Application Number</h1>
			<div class="text-left"><a href="/applicationnumbers" class="btn btn-outline-primary">Application Format Available</a></div>

			<form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
				<div class="control-group col-8 text-left">
				<div class="flex1">
              <div class="div1"> <label for="prefix">Prefix (Application Number Format before any number)</label> </div>
              <div class="div2"> <input type="text" id="prefix" class="form-control mb-4 " name="prefix" placeholder="Enter Prefix" class="input1" value="{!! $applicationnumber->prefix !!}"  required autocomplete="off"> </div>
            </div>
			<div class="flex1">
			<div class="div1"> <label for="suffix">Suffix (Number of digit after prefix format)</label> </div>
              <div class="div2"> <input type="text" id="suffix" class="form-control mb-4 " name="suffix" placeholder="Enter suffix" class="input1" value="{!! $applicationnumber->suffix !!}"  required autocomplete="off"> </div>
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
	
	$("#suffix").keydown(function(event) {
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
