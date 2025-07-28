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

    .flex1 {
        display: flex;
    }

    .div1 {
        width: 250px;
    }

    .div2 {
        width: 500px;
    }




    /* .registration_form input[type="text"], input[type="email"], input[type="password"]:focus:not([readonly]) {
      border: 1px solid #609;
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
            <div class="col-12 text-center pt-5">
                <h1 class="display-one mt-5">New UO Format Entry Form</h1>
                <div class="text-left"><a href="/uonomenclatures" class="btn btn-outline-primary">UO Format
                        Available</a></div>

                <form id="add-frm" method="POST" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="probable_remarks">UO Full Format</label></div>

                            <div class="div2">
                                <input type="text" id="uo_format" class="form-control mb-4" name="uo_format"
                                    placeholder="Enter UO Full Format" 
                                    required>
                                </input>
                            </div>

                        </div>


                        <div class="flex1">
                            <div class="div1"> <label for="probable_remarks">UO File Number</label></div>

                            <div class="div2">
                                <input type="text" id="uo_file_no" class="form-control mb-4" name="uo_file_no"
                                    placeholder="Enter UO File Number" 
                                    required>
                                </input>
                            </div>

                        </div>

                        <div class="flex1">
                            <div class="div1"> <label for="probable_remarks">Financial Year</label></div>

                            <div class="div2">
                                <input type="text" id="year" class="form-control mb-4" name="year"
                                    placeholder="Enter Financial Year"  maxlength='7' required>
                                </input>
                            </div>

                        </div>


                        <div class="flex1">
                            <div class="div1"> <label for="probable_remarks">Suffix</label></div>

                            <div class="div2">
                                <input type="text" id="suffix" class="form-control mb-4" name="suffix"
                                    placeholder="Enter Suffix"  required>
                                </input>
                            </div>

                        </div>




                    </div>


                    @csrf

                    <div class="control-group col-6 text-left mt-2"><button class="btn btn-primary">Add Only One
                            Format</button></div>
                </form>
            </div>
        </div>
    </div>

    @endsection
</body>

</html>