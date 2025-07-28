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
            <div class="col-10  pt-5">
                <h1 class="display-one mt-5 text-center">Files To Upload</h1>
                <div class="text-left"><a href="/files_name" class="btn btn-outline-primary">Files To Upload List</a>
                </div>
              
                <form id="edit-frm" method="POST" action="" class="border p-3 mt-2">
                    <div style="display:flex;">


                        <div class="control-group col-10 text-left">
                            <div class="flex1">
                                <div class="div1"> <label for="doc_name">Doc Name</label> </div>
                                <div class="div2"> <input type="text" id="doc_name" class="form-control mb-4 "
                                        name="doc_name" placeholder="Enter Document Name" class="input1"
                                        value=" {!! $file->doc_name !!}" required> </div>
                            </div>
                           
                            <!-- Is Mandatory -->
                            <div class="flex1">
                                <div class="div1">
                                    <label for="is_mandatory">Is Mandatory</label>
                                </div>
                                <div class="div2">
                                    <select id="is_mandatory" class="form-control mb-4" name="is_mandatory" required>
                                   

                                        @foreach($mandatoryfile as $option)
                                        <option value="{{ $option->is_mandatory }}"
                                            {{ old('is_mandatory', $file->is_mandatory) == $option->is_mandatory ? 'selected' : '' }}>
                                            {{ $option->is_mandatory }}
                                        </option>
                                            
                                         
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <!-- status -->
                            <div class="flex1">
                                <div class="div1">
                                    <label for="status">status</label>
                                </div>
                                <div class="div2">
                                    <select id="status" class="form-control mb-4" name="status" required>


                                        @foreach($filestatus as $option)
                                        <option value="{{ $option->status }}"
                                            {{ old('status', $file->status) == $option->status ? 'selected' : '' }}>
                                            {{ $option->status }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                         




                        </div>

                        <div class="col-2 ">
                            <h5><b>Index for Description</b></h5>


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
                    @method('PUT')
                    <div class="control-group col-6 text-center mt-2">
                    <a href="{{ url('/files_name') }}" class="btn btn-primary">Back</a>
                    <button class="btn btn-primary">Save Update</button></div>
                </form>
            </div>
        </div>
    </div>
    @endsection
</body>