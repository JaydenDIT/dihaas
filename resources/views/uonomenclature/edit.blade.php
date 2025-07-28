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
            <div class="col-12 text-center pt-5">
                <h1 class="display-one mt-5">UO Nomenclature</h1>
                <div class="text-left"><a href="/uonomenclatures" class="btn btn-outline-primary">UO Format
                        Available</a></div>

                <form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
                    <div class="control-group col-8 text-left">
                        <div class="flex1">
                            <div class="div1"> <label for="probable_remarks">UO Full Format</label></div>

                            <div class="div2">
                                <input type="text" id="uo_format" class="form-control mb-4" name="uo_format"
                                    placeholder="Enter UO Full Format" value="{!! $uonomenclature->uo_format !!}"
                                    required>
                                </input>
                            </div>

                        </div>


						<div class="flex1">
                            <div class="div1"> <label for="probable_remarks">UO File Number</label></div>

                            <div class="div2">
                                <input type="text" id="uo_file_no" class="form-control mb-4" name="uo_file_no"
                                    placeholder="Enter UO File Number" value="{!! $uonomenclature->uo_file_no !!}"
                                    required>
                                </input>
                            </div>

                        </div>

						<div class="flex1">
                            <div class="div1"> <label for="probable_remarks">Financial Year</label></div>

                            <div class="div2">
                                <input type="text" id="year" class="form-control mb-4" name="year"
                                    placeholder="Enter Financial Year" value="{!! $uonomenclature->year !!}"
                                    required maxlength='7'>
                                </input>
                            </div>

                        </div>


						<div class="flex1">
                            <div class="div1"> <label for="probable_remarks">Suffix</label></div>

                            <div class="div2">
                                <input type="text" id="suffix" class="form-control mb-4" name="suffix"
                                    placeholder="Enter Suffix" value="{!! $uonomenclature->suffix !!}"
                                    required>
                                </input>
                            </div>

                        </div>



                    
                    </div>


                    @csrf
                    @method('PUT')
                    <div class="control-group col-6 text-left mt-2"><button class="btn btn-primary">Save Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</body>