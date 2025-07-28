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
            <div class="col-10  pt-5">
                <h1 class="display-one mt-5 text-center">New Files To Upload Form</h1>
                <div class="text-left"><a href="/files_name" class="btn btn-outline-primary">Files To Upload List</a>
                </div>

                <form id="add-frm" method="POST" action="" class="border p-3 mt-2">
                    <div style="display:flex;">


                        <div class="control-group col-10 text-left">
                            <div class="flex1">
                                <div class="div1"> <label for="doc_name">Document Name</label> </div>
                                <div class="div2"> <input type="text" id="doc_name" class="form-control mb-4 " name="doc_name" placeholder="Enter Document Name" class="input1" required>
                                </div>
                            </div>

                            <div class="flex1">
                                <div class="div1">
                                    <label for="status">Is Mandatory</label>
                                </div>

                                <div class="div2">
                                    <select id="is_mandatory" class="form-control mb-4" name="is_mandatory" required>
                                        <option value="">Select</option>
                                        @foreach($mandatoryfile as $option)
                                        <option value="{{ $option->is_mandatory }}">{{ $option->is_mandatory }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex1">
                                <div class="div1">
                                    <label for="status">status</label>
                                </div>

                                <div class="div2">
                                    <select id="status" class="form-control mb-4" name="status" required>
                                        <option value="">Select</option>
                                        @foreach($filestatus as $option)
                                        <option value="{{ $option->status }}">{{ $option->status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                        </div>


                        <div class="col-2 ">
                            <h5><b>Index for Mandatory</b></h5>


                            <ol>
                                <li>Yes</li>
                                <li>No</li>

                            </ol>
                            <p>
                            <h5><b>Index for status</b></h5>


                            <ol>
                                <li>Normal Death</li>                                
                                <li>Citizen required Documents</li>
                                <li>Expired on duty</li>
                                <li>Physically Handicapped</li>
                                <li>Documents required in all the 4 conditions</li>
                            </ol>
                        </div>

                    </div>


                    @csrf
                    
                    <div class="control-group col-6 text-center mt-2">
                    <a href="{{ url('/files_name') }}" class="btn btn-primary">Back</a>    
                    <button class="btn btn-primary">Add New File</button>
                </div>
                </form>
            </div>

        </div>
    </div>

    @endsection
</body>

</html>