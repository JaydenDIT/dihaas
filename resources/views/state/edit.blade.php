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
                <h1 class="display-one mt-5 text-center">State</h1>
                <div class="text-left"><a href="/states" class="btn btn-outline-primary">State List</a></div>

                <form id="edit-frm" method="POST" action="" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="state_name">State Name</label> </div>
                            <div class="div2"> <input type="text" id="state_name" class="form-control mb-4 "
                                    name="state_name" placeholder="Enter State Name" class="input1"
                                    value="{!! $state->state_name !!}" required> </div>
                        </div>

                        <div class="flex1">
                            <div class="div1"> <label for="state_desc">State DESC</label></div>

                            <div class="div2"><input type="text" id="state_desc" class="form-control mb-4"
                                    name="state_desc" placeholder="Enter state DESC" value="{!! $state->state_desc !!}"
                                    required>
                            </div>

                        </div>


                        <div class="flex1">
                            <div class="div1">
                                <label for="state_code_census">State Code Census</label>
                            </div>

                            <div class="div2">
                                <input type="text" id="state_code_census" id="state_code_census"
                                    class="form-control mb-4" name="state_code_census"
                                    placeholder="Enter state code census" value="{!! $state->state_code_census !!}"
                                    required  onblur="stateCodeCensusValidation()">
                                <div id="error-message" class="text-danger"></div>
                            </div>
                        </div>






                    </div>


                    @csrf
                    @method('PUT')
                    <div class="control-group col-6 text-center mt-2"><button type="submit" class="btn btn-primary">Save
                            Update</button></div>
                </form>
            </div>
        </div>
    </div>



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

    @endsection
</body>