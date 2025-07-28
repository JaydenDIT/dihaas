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
                <h1 class="display-one mt-5 text-center">New District Form</h1>
                <div class="text-left"><a href="/districts" class="btn btn-outline-primary">District List</a></div>

                <form id="add-frm" method="POST" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="district_code_census">District Code Census</label> </div>
                            <div class="div2"> <input type="text" id="district_code_census" class="form-control mb-4 "
                                    name="district_code_census" id="district_code_census" placeholder="Enter District Code Census" class="input1"
                                    required onblur="districtCodeCensusValidation()">
                                <div id="errormessage" class="text-danger"></div> 
                                   
                                  </div>
                        </div>

                        <div class="flex1">
                            <div class="div1"> <label for="district_name_english">District Name</label></div>

                            <div class="div2"><input type="text" id="district_name_english" class="form-control mb-4"
                                    name="district_name_english" placeholder="Enter District Name" required></div>

                        </div>


                        <div class="flex1">
                            <div class="div1">
                                <label for="state_code_census">State Code Census</label>
                            </div>

                            <div class="div2">
                                <input type="text" id="state_code_census" class="form-control mb-4"
                                    name="state_code_census" placeholder="Enter state code census" required
                                    onblur="stateCodeCensusValidation()">
                                <div id="error-message" class="text-danger"></div> 
                               
                            </div>
                        </div>






                    </div>

                    @csrf

                    <div class="control-group col-6 text-center mt-2"><button class="btn btn-primary">Add New
                            District</button></div>
                </form>
            </div>
        </div>
    </div>

    <script nonce="{{ csp_nonce() }}">
    function districtCodeCensusValidation() {
        // Get the input element by its ID
        var inputElement = document.getElementById("district_code_census");

        // Get the value entered by the user
        var inputValue = inputElement.value.trim();

        // Regular expression pattern to match 1 or 2 digits
        var pattern = /^[0-9]{1,3}$/;

        // Check if the input matches the pattern
        if (pattern.test(inputValue)) {
            // Clear any previous error message
            document.getElementById("errormessage").textContent = "";
        } else {
            // Display an error message
            document.getElementById("errormessage").textContent = "Please enter a valid 1 or 2 digit number.";
            // Clear the input value
            inputElement.value = "";
        }
    }
    </script>


    <script>
    function stateCodeCensusValidation() {
        // Get the input element by its ID
        var inputElement = document.getElementById("state_code_census");

        // Get the value entered by the user
        var inputValue = inputElement.value.trim();

        // Regular expression pattern to match 1 or 2 digits
        var pattern = /^[0-9]{1,2}$/;

        // Check if the input matches the pattern
        if (pattern.test(inputValue)) {
            // Clear any previous error message
            document.getElementById("error-message").textContent = "";
        } else {
            // Display an error message
            document.getElementById("error-message").textContent = "Please enter a valid 1 or 2 digit number.";
            // Clear the input value
            inputElement.value = "";
        }
    }
    </script>

<script nonce="{{ csp_nonce() }}">
	
    $("#district_code_census").keydown(function(event) {
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
  
  
  
  
        
    $("#state_code_census").keydown(function(event) {
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

</html>