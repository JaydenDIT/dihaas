<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->

@extends('layouts.app')

@section('content')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/select.css') }}" rel="stylesheet">
<?php $selected = session()->get('deptId') ?>

<div class="container">

    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form method="POST" action="{{ route('submit-form') }}">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <h3>{{ __('Select Department') }}</h3>
                            </div>
                            <!-- Department -->
                            <div class="row mb-3">
                                <label for="Departments"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="dept_id"
                                        name="dept_id">
                                        <option value="" selected>All Department</option>
                                        @foreach($deptListArray as $option)
                                        <option value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}"
                                            required {{($selected == $option['dept_id'])?'selected':''}}>
                                            {{$option['dept_name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-9">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Submit') }}
                                    </button>

                                    <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif -->
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <!-- saved succes message -->
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <!-- saved erroe message -->
                    @if(session()->has('error_message'))
                    <div class="alert alert-danger">
                        {{ session()->get('error_message') }}
                    </div>
                    @endif
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- {{ __('You are logged in!') }} -->

                    <!-- CMIS Started employee list table -->
                    <!-- <div id="started_emp_table" style="display:show"><b>Started Employee List :</b><br> -->
                    <!-- Add the button outside the form -->


                    <div class="row">

                        <div class="col-7">
                            <b class="color">List of Approved Applicants for giving Appointment</b>
                        </div>
                        <div class="col-5">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                                action="{{ url('ddo-assist/selectDeptByDPApproveSearch') }}"
                                enctype="multipart/form-data" class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-10 marginright_textalign">
                                        <input type="text" class="form-control margin" placeholder="Search by EIN NO."
                                            name="searchItem"></input>
                                    </div>
                                    <div class="col-2 margin_text">
                                        <button class="btn btn-outline-secondary" type="submit"
                                            id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <hr>
                    <div class="row">

                        <div class="col-sm-12 d-flex justify-content-end">
                            <button id="forwardBtn" class="btn btn-success" >Fill UO Form</button>
                        </div>
                    </div>
                    <hr>

                    <!-- Hidden form to submit selected EINs -->
                    <form id="selectedEinForm" method="post" action="{{ route('check_fill_uo') }}">
                        @csrf
                        <input type="hidden" name="selectedEinIds" id="selectedEinIds" value="">
                    </form>
                    <p>
                    <form name="frmGenerateUO" method="post" action="{{ route('viewApproveGroup') }}" novalidate
                        target="_blank">
                        <!-- <div style="text-align: left">
                        <a href="{{ route('viewOrderGroup') }}" id="btnGroupPdf1" class="badge btn btn-success  text-wrap" style="width:13rem; height:2.5rem ;"  aria-disabled="true" disabled>View Order of Selected Applicants</a>
                        </div> -->
                        <div class="row">
                            <div class="col-sm-2 textleft">
                                @if($empList->isEmpty())

                                <button type="button" class="btn btn-success"
                                    disabled>Print</button>
                                @else
                                <button id="printButtonCard1" type="button" class="btn btn-success"
                                    >Print</button>
                                @endif

                            </div>
                            <div class="col-sm-4">


                            </div>

                            <div class="col-sm-6 textright">
                                <!-- <a href="fill" id="btnGroupFillUO" class=" btn btn-success width_height3 " > <h6><small>Fill UO for the Selected Applicants</small></h6></a> -->


                                <!-- <button id="btnGroupFillUO" class="badge btn btn-success  text-wrap width_height3"
                                    role="button" href="hg" aria-disabled="true" disabled> sida metpada View approve group Fill UO for the Selected
                                    Applicants</button> -->
                                <!-- <button id="btnGroupPdf" class="btn btn-success width_height3 " disabled>
                                    <h6><small>Generate UO for the Selected Applicants</small></h6>
                                </button> -->


                                <button id="btnGroupPdf" class="btn btn-success " role="button" aria-disabled="true"
                                    disabled>Generate UO for Multiple Applicants
                                </button>
                            </div>


                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-striped" id="table">

                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Select</th>
                                        <th scope="col">Sl.No.</th>
                                        <th scope="col">EIN</th>
                                        <th scope="col">Deceased Name</th>
                                        <th scope="col">Department Name</th>
                                        <th>Applicant Name</td>
                                        <th scope="col">Mobile No.</th>
                                        <th scope="col">eFile AD No.</th>
                                        <th>eFile DP No.</td>
                                        <th scope="col">status</th>
                                        <th scope="col" colspan="4" colspan="4" class="textcenter">Action</th>
                                    </tr>
                                </thead>
                                <tbody>



                                    @if($empList->isEmpty())
                                    <tr>
                                        <td colspan="11" class="textcenter">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else



                                    @foreach($empList as $key => $data)



                                    <tr>


                                        @if($data->status == 'Appointed' || $data->formSubStat == "order")
                                        <td>
                                            <input type="checkbox" name="selectedGrades[]"
                                                id="{{$data->efile_dp.'|'.$data->dept_id.'|'.$data->ein}}" value="{{ $data->ein }}"
                                                onchange="onChangeCheckBox(this);onChangeCheckBox1(this)">
                                        </td>
                                        @endif





                                        @if($data->formSubStat == "approved" )
                                        <td>
                                            <input type="checkbox" id="{{$data->efile_dp.'|'.$data->dept_id.'|'.$data->ein}}"
                                                class="ein-checkbox" name="selected_ein[]"
                                                value="{{$data->ein}}" onchange="onChangeCheckBoxForward(this)">
                                        </td>
                                        @endif
                                        <td>{{ $empList->firstItem() + $key }}</td>

                                        <td>{{$data->ein}}</td>
                                        <td>{{$data->deceased_emp_name}}</td>
                                        <td>{{$data->dept_name}}</td>

                                        <td>{{$data->applicant_name}}</td>

                                        <td>{{$data->applicant_mobile}}</td>
                                        <td>{{$data->efile_ad}}</td>

                                        <td>{{$data->efile_dp}}</td>

                                        <td>{{$data->status}}</td>


                                        @if($data->formSubStat == "submitted")
                                        <td class="textright">                                   
                                          
                                           

                                        </td>
                                        @endif
                               
                                      
                                        @if($data->formSubStat == "approved")
                                        <td class="textright">
                                            <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}"
                                                class="btn btn-success btn-sm" role="button"
                                                aria-disabled="true">View</a>
                                           
                                        </td>
                                       
                                        @endif
                                        @if($data->formSubStat == "appointed")

                                        <td>

                                            <a href="{{ route('fill_uo_update', Crypt::encryptString($data->ein))}}"
                                                id="update_uo" class="badge btn btn-success text-wrap width_height"
                                                role="button" aria-disabled="true" target="_blank">Update</a>

                                        </td>
                                        <td>
                                            <a href="{{ route('generate-pdf', Crypt::encryptString($data->ein))}}"
                                                id="generate_uo" class="badge btn btn-success text-wrap width_height"
                                                role="button" aria-disabled="true" target="_blank">Generate Single
                                                UO</a>
                                        </td>

                                        @endif

                                        @if($data->formSubStat == "order")

                                        <td class="textright">
                                            <a href="{{ route('viewOrder', Crypt::encryptString($data->ein)) }}"
                                                class="btn btn-success" role="button"
                                                aria-disabled="true" target="_blank">View Order</a>
                                            <!-- capturing the ein at click instant -->

                                        </td>

                                        @endif


                                    </tr>
                                    <!-- MODAL FADE CODE BELOW FOR REVERT -->
                                    <div class="modal fade" id="uoModal" tabindex="-1" aria-labelledby="uoModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form name="FillUOForm"
                                                action="{{ route('uo_submit', Crypt::encryptString($data->ein)) }}"
                                                method="Post">
                                                @csrf
                                                <!-- @method('GET') -->


                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="uoModalTitle">UO Form</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label for="name"><b>Fill the UO Form </b></label>

                                                        <input type="text" class="form-control" id="ein" name="ein"
                                                            value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                        <div class="mb-3">
                                                            <label for="applicant_name" class="form-label">Applicant
                                                                Name</label>

                                                            <input class="form-control" id="applicant_name"
                                                                name="applicant_name" value="{{$data->applicant_name}}"
                                                                required>

                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="dp_file_number" class="form-label">DP File
                                                                No.</label>
                                                            <input type="text" class="form-control" id="dp_file_number"
                                                                name="dp_file_number" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="ad_file_number" class="form-label">AD File
                                                                No.</label>
                                                            <input type="text" class="form-control" id="ad_file_number"
                                                                name="ad_file_number" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="DP_signing_authority" class="form-label">Post
                                                                Given</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" id="post"
                                                                name="post">
                                                                <option selected>Select</option>
                                                                @foreach($desigListArray as $option)
                                                                <option value="{{ $option['desig_name'] }}" required>
                                                                    {{$option['desig_name']}}
                                                                </option>
                                                                @endforeach


                                                            </select><br>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="DP_signing_authority" class="form-label">DP
                                                                Signing Authority</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" id="sign1"
                                                                name="sign1">
                                                                <option selected>Select</option>
                                                                @foreach($Sign1 as $option)
                                                                <option value="{{ $option['authority_name'] }}"
                                                                    required> {{$option['authority_name']}}</option>
                                                                @endforeach


                                                            </select><br>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="Dept_signing_authority"
                                                                class="form-label">Department Signing Authority</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" id="sign2"
                                                                name="sign2">
                                                                <option selected>Select</option>
                                                                @foreach($Sign2 as $option)
                                                                <option value="{{ $option['name'] }}" required>
                                                                    {{$option['name']}}
                                                                </option>
                                                                @endforeach


                                                            </select><br>
                                                        </div>


                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button id="BtnSvData" type="submit"
                                                            class="btn btn-success">Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>




                                    <!-- MODAL FADE CODE BELOW FOR FORWARD -->

                                    <div class="modal fade" id="remarkForwardModal" tabindex="-1"
                                        aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form name="forwardForm"
                                                action="{{ route('viewFormWord', Crypt::encryptString($data->ein)) }}"
                                                method="Post">
                                                @csrf
                                                <!-- @method('GET') -->


                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button id="BtnSvData" type="submit"
                                                            class="btn btn-success">Open</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach


                                    @endif

                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive display" id="card1-table">
                            <table class="table table-bordered table-condensed table-striped">

                                <thead class="thead-dark">
                                    <tr>

                                        <th scope="col">Sl.No.</th>
                                        <th scope="col">EIN</th>
                                        <th scope="col">Deceased Name</th>
                                        <th scope="col">Department Name</th>                                      
                                        <th>Applicant Name</td>
                                        <th scope="col">Mobile No.</th>
                                        <th>eFile AD No.</td>
                                        <th scope="col">eFile DP No.</th>
                                        <th scope="col">status</th>

                                    </tr>
                                </thead>
                                <tbody>







                                    @foreach($empListprint as $data)




                                    <tr>
                                        @if($data->status == 'Appointed')
                                        <td>
                                            <input type="checkbox" name="selectedGrades[]"
                                                id="{{$data->efile_dp.'|'.$data->dept_id.'|'.$data->ein}}" value="{{ $data->ein }}"
                                                onchange="onChangeCheckBox(this);onChangeCheckBox1(this)">
                                        </td>
                                        @endif

                                        @if($data->status == 'Approved')
                                        <td>

                                        </td>
                                        @endif
                                        <th scope="row">{{$loop->index + 1}}</th>
                                        <td>{{$data->ein}}</td>
                                        <td>{{$data->deceased_emp_name}}</td>
                                        <td>{{$data->dept_name}}</td>                                     
                                        <td>{{$data->applicant_name}}</td>
                                        <td>{{$data->applicant_mobile}}</td>
                                        <td>{{$data->efile_ad}}</td>
                                        <td>{{$data->efile_dp}}</td>
                                        <td>{{$data->status}} </td>
                                       </tr>




                                    <!-- MODAL FADE CODE BELOW FOR FORWARD -->


                                    @endforeach




                                </tbody>
                            </table>
                        </div>
                    </form>
                    {{-- @if($empList != null)
                    <div class="row">
                        {!! $empList->links() !!}
                    </div>
                    @endif --}}
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script nonce="{{ csp_nonce() }}">

document.addEventListener("DOMContentLoaded", function() {
    var printButton = document.getElementById("printButtonCard1");

    printButton.addEventListener("click", function() {
        printListCard1();
    });
});


function printListCard1() {
    var cardContent = document.getElementById('card1-table').innerHTML;
    var borderedContent = '<div class="center-table" >' + cardContent + '</div>' +
        '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding:2px;}}' +
        '.center-table {display: flex; justify-content: center; align-items: center;}</style>';
    printContent(borderedContent);
}


// Shared function to print content
function printContent(content) {
    var printWindow = window.open('', '_blank');


    <?php

        use App\Models\DepartmentModel;
        use App\Models\PortalModel;
        use App\Models\User;
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Auth;

        $getPortalName = PortalModel::where('id', 1)->first();
        //Portal name short form    
        $getProjectShortForm = $getPortalName->short_form_name;
        //Application long name
        $getSoftwareName = $getPortalName->software_name;
        $getDeptName = $getPortalName->department_name;
        $getGovtName = $getPortalName->govt_name;
        $getDeveloper = $getPortalName->developed_by;
        $getCopyright = $getPortalName->copyright;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $Department = DepartmentModel::get()->where('dept_id', $getUser->dept_id)->first();
        $getDeptName = $Department->dept_name;
        $getDate = Carbon::now()->format('Y-m-d');

        ?>

    printWindow.document.write("<html><head><title>{{$getSoftwareName}}</title></head><body>");
    printWindow.document.write("<div style='display: flex; align-items: center;'>");
    printWindow.document.write(
        "<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");
    printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
    // printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
    printWindow.document.write("<p>{{$getProjectShortForm}}</p>");
    printWindow.document.write("<p>Vacancy List</p>");
    printWindow.document.write("</div></div>");
    printWindow.document.write("<p><br></br></p>");
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>





<script nonce="{{ csp_nonce() }}">

function setFillData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure that the Applicant File is OK for UO Generation?')) {
        return;
    }
    $("#uoModal").modal('show');
    let form = document.forms['FillUOForm'];
    // form.appl_number.value = temp_array['appl_number'];
    form.ein.value = temp_array['ein'];
    console.log(temp_array['ein'])

}


function setForwardData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure you want to generate the UO ?')) {
        return;
    }
    $("#remarkForwardModal").modal('show');
    let form = document.forms['forwardForm'];
    // form.appl_number.value = temp_array['appl_number'];
    form.ein.value = temp_array['ein'];
    console.log(temp_array['ein'])

}



function onChangeCheckBox(current) {
    var checkboxes = document.getElementsByName('selectedGrades[]');

    var count = 0;
    var multidept = false;
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        if (checkboxes[i].checked) {
            count++;
            if (checkboxes[i].id != current.id && checkboxes[i].id.split("|")[0] != current.id.split("|")[0]) {
                multidept = true;
                current.checked = false;
            }
        }
    }
    if (count > 1) {
        // document.getElementById('btnGroupUO').disabled = false;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants
        document.getElementById('btnGroupPdf').disabled = false;

    } else {
        // document.getElementById('btnGroupUO').disabled = true;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants
        document.getElementById('btnGroupPdf').disabled = true;

    }
    if (multidept == true) {
        alert("Please check whether eFile from DP and Department name are same...");
    }
}

function onChangeCheckBox1(current) {
    var checkboxes = document.getElementsByName('selectedGrades[]');

    var count = 0;
    var multidept = false;
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        if (checkboxes[i].checked) {
            count++;
            if (checkboxes[i].id != current.id && checkboxes[i].id.split("|")[0] != current.id.split("|")[0]) {
                multidept = true;
                current.checked = false;
            }
        }
    }
    if (count > 1) {
        // document.getElementById('btnGroupUO').disabled = false;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants

        document.getElementById('btnGroupPdf1').disabled = false;
    } else {
        // document.getElementById('btnGroupUO').disabled = true;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants

        document.getElementById('btnGroupPdf1').disabled = true;
    }
    if (multidept == true) {
        alert("Please check whether eFile from DP and Department name are same...");
    }
}
</script>

<script nonce="{{ csp_nonce() }}">
function onChangeCheckboxFillUO(current) {
    var checkboxes = document.getElementsByName('selectedFillUO[]');

    var count = 0;
    var multidept = false;
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        if (checkboxes[i].checked) {
            count++;
            if (checkboxes[i].id != current.id && checkboxes[i].id.split("|")[0] != current.id.split("|")[0]) {
                multidept = true;
                current.checked = false;
            }
        }
    }
    if (count > 1) {
        // document.getElementById('btnGroupUO').disabled = false;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants
        document.getElementById('btnGroupFillUO').disabled = false;

    } else {
        // document.getElementById('btnGroupUO').disabled = true;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants
        document.getElementById('btnGroupFillUO').disabled = true;

    }
    if (multidept == true) {
        alert("Please check whether eFile from DP and Department name are same...");
    }
}
</script>


<script nonce="{{ csp_nonce() }}">
// from this is to check the checkboxes
// $(document).ready(function() {
    document.addEventListener('DOMContentLoaded', function() {
    // Add a change event listener to the checkboxes
    $('input[name="selected_ein[]"]').on('change', function() {
        // Update the button's disabled attribute based on the number of checked checkboxes
        var selectedEINs = $('input[name="selected_ein[]"]:checked');
        $('#selectedEinButton').prop('disabled', selectedEINs.length === 0);
    });


document.getElementById('forwardBtn').addEventListener('click', function() {
        var selectedEINs = $('input[name="selected_ein[]"]:checked');

    // Check if no checkboxes are checked
    if (selectedEINs.length === 0) {
        alert('Please select at least one EIN');
        return;
    }

    // If at least one checkbox is checked, proceed with your logic
    $('#selectedEinIds').val(selectedEINs.map(function() {
        return $(this).val();
    }).get());
    document.getElementById('selectedEinForm').submit();
    });
}); 
</script>




<script nonce="{{ csp_nonce() }}">

// $('.select_check').change(function() {
    function onChangeCheckBoxForward(current) {
    var checkboxes = document.getElementsByName('selected_ein[]');

    var count = 0;
    var multidept = false;
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        if (checkboxes[i].checked) {
            count++;
            if (checkboxes[i].id != current.id && checkboxes[i].id.split("|")[0] != current.id.split("|")[0]) {
                multidept = true;
                current.checked = false;
            }
        }
    }
    if (count = 1) {
        // document.getElementById('btnGroupUO').disabled = false;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants
        document.getElementById('forwardBtn').disabled = false;

    } else {
        // document.getElementById('btnGroupUO').disabled = true;//We can remove this if we don't want to use the button Generate UO for the Selected Applicants
        document.getElementById('forwardBtn').disabled = true;

    }
    if (multidept == true) {
        alert("Different eFile No.");
    }
}

</script>

<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>