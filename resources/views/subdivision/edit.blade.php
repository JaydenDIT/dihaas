<head>
<style nonce="{{ csp_nonce() }}">
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
                <h1 class="display-one mt-5 text-center">Sub Division</h1>
                <div class="text-left"><a href="/subdivisions" class="btn btn-outline-primary">Sub Division List</a>
                </div>

                <form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="district_code_census">District Code Census</label> </div>
                            <div class="div2"> <input type="text" id="district_code_census"
                          
                                    value=  "{{ $subdivision == null ? '' : $subdivision->district_code_census }}" class="form-control mb-4 "
                                    name="district_code_census" placeholder="Enter District Code Census" class="input1" required
                                    onblur="DistrictCdCmisValidation()">
                                <div id="district_code_censusError" class="text-danger"></div>
                            </div>
                        </div>

                       
                        <div class="flex1">
                            <div class="div1">
                                <label for="sub_district_cd_lgd">Sub District Cd Lgd</label>
                            </div>

                            <div class="div2">
                                <input type="text" id="sub_district_cd_lgd"
                                    value="{!! $subdivision->sub_district_cd_lgd !!}" class="form-control mb-4"
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
                                <input type="text" id="sub_division_name"
                                    value="{!! $subdivision->sub_division_name !!}" class="form-control mb-4"
                                    name="sub_division_name" placeholder="Enter Sub Division Name" required>
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


    <script nonce="{{ csp_nonce() }}">
    function DistrictCdCmisValidation() {
        // Get the input element by its ID
        var inputElement = document.getElementById("district_code_census");

        // Get the value entered by the user
        var inputValue = inputElement.value.trim();

        // Regular expression pattern to match 1 or 2 digits
        var pattern = /^[0-9]{1,3}$/;

        // Check if the input matches the pattern
        if (pattern.test(inputValue)) {
            // Clear any previous error message
            document.getElementById("district_code_censusError").textContent = "";
        } else {
            // Display an error message
            document.getElementById("district_code_censusError").textContent = "Please enter a valid 1 or 3 digit number.";
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