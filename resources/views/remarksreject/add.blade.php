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

    .flex {
      display: flex;
      flex-direction: row;
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
  @extends('layouts.app')
  @section('content')


  @if (session('status'))
  <div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert"></button>
    {{ session('status') }}
  </div>
  @elseif(session('failed'))
  <div class="alert alert-danger" role="alert">
    <button type="button" class="close" data-dismiss="alert"></button>
    {{ session('failed') }}
  </div>
  @endif
  <div class="container">
    <div class="row">
      <div class="col-12  pt-5">
        <h1 class="display-one mt-5 text-center">New Reject Remarks Form</h1>
        <div class="text-left"><a href="/remarksrejects" class="btn btn-outline-primary">Remarks List</a></div>

        <form id="add-frm" method="POST" action="" class="border p-3 mt-2">
          <div class="control-group col-8 text-left">
           

            <div class="flex1">
              <div class="div1"> <label for="probable_remarks">Remarks</label></div>

              <div class="div2"><input type="text" id="probable_remarks" class="form-control mb-4" name="probable_remarks" placeholder="Enter Remarks" required></div>

            </div>


            

          

    

          </div>

          @csrf

          <div class="control-group col-6 text-center mt-2"><button class="btn btn-primary">Add New Remarks</button></div>
        </form>
      </div>
    </div>
  </div>

  @endsection
</body>

</html>