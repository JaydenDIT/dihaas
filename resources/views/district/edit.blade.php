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
                <h1 class="display-one mt-5 text-center">District</h1>
                <div class="text-left"><a href="/districts" class="btn btn-outline-primary">District List</a></div>

                <form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="district_code_census">District Code Census</label> </div>
                            <div class="div2"> <input type="text" id="district_code_census"
                                    value="{!! $district->district_code_census !!}" class="form-control mb-4 "
                                    name="district_code_census" placeholder="Enter District Code Census" class="input1"
                                    required onblur="districtCodeCensusValidation()">
                                <div id="errormessage" class="text-danger"></div> 
                                    
                                  </div>
                          
                        </div>

                        <div class="flex1">
                            <div class="div1"> <label for="district_name_english">District Name</label></div>

                            <div class="div2"><input type="text" id="district_name_english"
                                    value="{!! $district->district_name_english !!}" class="form-control mb-4"
                                    name="district_name_english" placeholder="Enter District Name" required></div>
                        </div>

                        <div class="flex1">
                            <div class="div1">
                                <label for="state_code_census">State Code Census</label>
                            </div>

                            <div class="div2">
                                <input type="text" id="state_code_census" class="form-control mb-4"
                                    value="{!! $district->state_code_census !!}" name="state_code_census"
                                    placeholder="Enter State Code Census" required  onblur="stateCodeCensusValidation()">
                                <div id="error-message" class="text-danger"></div> 
                               
                                
                            </div>
                        </div>






                    </div>


                    @csrf
                    @method('PUT')
                    <div class="control-group col-6 text-center mt-2"><button class="btn btn-primary">Save
                            Update</button></div>
                </form>
            </div>
        </div>
    </div>

    
    <script>
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