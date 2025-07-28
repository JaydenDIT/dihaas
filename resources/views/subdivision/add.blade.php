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
            <div class="col-12  pt-5">
                <h1 class="display-one mt-5 text-center">New Sub Division Form</h1>
                <div class="text-left"><a href="/subdivisions" class="btn btn-outline-primary">Sub Division List</a>
                </div>

                <form id="add-frm" method="POST" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="district_cd_cmis">District Code Census</label> </div>
                            <div class="div2"> <input type="text" id="district_cd_cmis" class="form-control mb-4 "
                                    name="district_cd_cmis" placeholder="" required
                                    onblur="DistrictCdCmisValidation()">
                                <div id="districtCdCmisError" class="text-danger"></div>
                            </div>
                        </div>

                       
                        <div class="flex1">
                            <div class="div1">
                                <label for="sub_district_cd_lgd">Sub District Cd Lgd</label>
                            </div>

                            <div class="div2">
                                <input type="text" id="sub_district_cd_lgd" class="form-control mb-4"
                                    name="sub_district_cd_lgd" placeholder="Enter Sub District Cd Lgd" required
                                    onblur="SubDistrictCdLgdValidation()">
                                <div id="subDistrictCdLgdError" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="flex1">
                            <div class="div1">
                                <label for="sub_division_name">Sub Division Name</label>
                            </div>
                            <div class="div2">
                                <input type="text" id="sub_division_name" class="form-control mb-4"
                                    name="sub_division_name" placeholder="Enter Sub Division Name" required>
                            </div>
                        </div>

                   
                    </div>

                    @csrf

                    <div class="control-group col-6 text-center mt-2"><button class="btn btn-primary">Add New Sub
                            Division</button></div>
                </form>
            </div>
        </div>
    </div>
    <script>
    function DistrictCdCmisValidation() {
        // Get the input element by its ID
        var inputElement = document.getElementById("district_code_census");

        // Get the value entered by the user
        var inputValue = inputElement.value.trim();

        // Regular expression pattern to match 1 or 2 digits
        var pattern = /^[0-9]{1,2}$/;

        // Check if the input matches the pattern
        if (pattern.test(inputValue)) {
            // Clear any previous error message
            document.getElementById("districtCdCmisError").textContent = "";
        } else {
            // Display an error message
            document.getElementById("districtCdCmisError").textContent = "Please enter a valid 1 or 2 digit number.";
            // Clear the input value
            inputElement.value = "";
        }
    }
    </script>


    
    <script>
    function SubDistrictCdLgdValidation() {
        // Get the input element by its ID
        var inputElement = document.getElementById("sub_district_cd_lgd");

        // Get the value entered by the user
        var inputValue = inputElement.value.trim();

        // Regular expression pattern to match 4 digits
        var pattern = /^[0-9]{4}$/;

        // Check if the input matches the pattern
        if (pattern.test(inputValue)) {
            // Clear any previous error message
            document.getElementById("subDistrictCdLgdError").textContent = "";
        } else {
            // Display an error message
            document.getElementById("subDistrictCdLgdError").textContent = "Enter only 4 digit number.";
            // Clear the input value
            inputElement.value = "";
        }
    }
    </script>
    <script  nonce="{{ csp_nonce() }}">
	
        $("#district_cd_cmis").keydown(function(event) {
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
      
      
      
      
            
        $("#sub_district_cd_lgd").keydown(function(event) {
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