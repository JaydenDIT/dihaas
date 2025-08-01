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
                    <form method="POST" action="{{ route('submitForm') }}">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <h3>{{ __('Select Department') }}</h3>
                            </div>
                            <!-- Department -->
                            <div class="row mb-3">
                                <label for="Departments" class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="dept_id" name="dept_id">
                                        <option value="" selected>All Department</option>
                                        @foreach($deptListArray as $option)
                                        <option value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}" required {{($selected == $option['dept_id'])?'selected':''}}>
                                            {{$option['dept_name']}}
                                        </option> @endforeach
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

                    <!-- {{ __('You are logged in!') }} -->

                    <!-- CMIS Started employee list table -->
                    <!-- <div id="started_emp_table" style="display:show"><b>Started Employee List :</b><br> -->
                    <div class="row">
                        <div class="col-7">
                            <b class="color">List of forwarded Applicants for DIH Scheme</b>
                        </div>

                        <div class="col-5">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ route('selectDeptByDPAssistSearch') }}" enctype="multipart/form-data" class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-10 marginright_textalign">
                                        <input type="text" class="form-control marginright" placeholder="Search by EIN NO." name="searchItem">
                                    </div>
                                    <div class="col-2 margin_text">
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <hr>

                    <div class="row">

                        <div class="col-sm-6">
                            @if(empty($empListArray))
                            <button type="button" class="btn btn-success"  disabled>Print </button>
              
                            @else
                           
                            <button id="printButtonCard1" type="button" class="btn btn-success" >Print</button>

                            <a href="{{ route('selectDeptByDPAssist.downloadPDFStatusByDP') }}" class="btn btn-success" target=”_blank”>Download As Pdf </a>
                            @endif
                        </div>


                        <div class="col-sm-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary open-modal" id="forwardBtn">
                                Forward
                            </button>

                        </div>
                    </div>
                    <div class="table-responsive">

                        <table class="table table-bordered table-condensed table-striped" id="table">

                            <thead>
                                <tr>                                   
                                    <th scope="col">Select</th>
                                    <th scope="col">Sl.No.</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">DOE</th>
                                    {{-- <th>eFile No. AD</th> --}}
                                    <th>File put up By Department</th>
                                    <th>Applicant Name</td>

                                    <th scope="col" style="color:green;">Remark</th>
                                    <th scope="col" style="color:red;">Status</th>
                                    <th scope="col">Department</th>

                                    <th scope="col" colspan="4" class="textcenter">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="12" class="textcenter">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>
                                @else

                                @foreach($empList as $key => $data)

                                <?php
                                $uploader = $data->uploader_role_id;
                                if ($uploader == 77) {
                                    $mode = "Online";
                                } else {
                                    $mode = "Offline";
                                }

                                ?>

                                <tr>

                                    <td>
                                        <input type="checkbox" id="{{$data->dept_id.'|'.$data->efile_ad.'|'.$data->ein}}" class="ein-checkbox" name="selected_ein[]" value="{{$data->ein}}" onchange="onChangeCheckBoxForward(this)">
                                    </td>

                                    </td>
                                    <!-- <th scope="row">{{$loop->index + 1}}</th> -->

                                    <!-- <td>{{$data->sl_no}}</td> -->
                                    <td>{{ $empList->firstItem() + $key }}</td>
                                    <td>{{$data->ein}}</td>
                                    <td>{{$data->deceased_emp_name}}</td>
                                    <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    {{-- <td>
                                    {{$data->efile_ad}}
                                </td> --}}
                                <td>
                                    @if(!empty($data->pdf_file))
                                    <a href="{{ route('viewFileForwardByHODAssistant', ['filename' => $data->pdf_file]) }}"
                                        target="_blank">
                                        {{ (strlen($data->pdf_file) > 10) ? substr($data->pdf_file, 0, 10)."..." : $data->pdf_file }}
                                    </a>
                                    @else
                                  
                                    File link is missing
                                    @endif
                                </td>

                                    <td>{{$data->applicant_name}}</td>
                                    <!-- <td>{{$data->applicant_dob ? \Carbon\Carbon::parse($data->applicant_dob)->format('d/m/Y') : 'NA'}}</td>                                    -->

                                    <td style="color:green;">{{$data->remark}}</td>
                                    <td style="color:red;">{{$data->status}}</td>
                                    <td>{{$data->dept_name}}</td>

                                    @if($data->formSubStat == "submitted")
                                    <td class="textright">
                                                                          
                                    </td>
                                    @endif

                                    @if($data->formSubStat == "started")
                                  
                                    @endif
                                    @if($data->formSubStat == "verifieddp")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
                                    </td>                                  

                                    <td>
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-danger btn-sm" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>
                                        <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->
                                    </td>                               


                                    @endif

                                     @if($data->formSubStat == "verifieddept")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
                                    </td>                                  

                                    <td>
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-danger btn-sm" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>
                                        <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->
                                    </td>                               
                                    <td class="rightstyle">
                                        <!-- forward put -->
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-success btn-sm blockstyle" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward to DP Nodal</button>
                                    </td>

                                    @endif

                                </tr>
                                <!-- MODAL FADE CODE BELOW FOR REVERT -->

                                <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form name="revertForm" action="{{ route('revertDetailsFromDPAssist', Crypt::encryptString($data->ein)) }}" method="Post">
                                            @csrf
                                            <!-- @method('GET') -->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="name"><b>Give a remark </b></label>
                                                    <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                    <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                    <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                    <select class="form-select" aria-label="Default select example" id="emp_addr_district_ret" name="emp_addr_district_ret">
                                                        <option selected>Select</option>
                                                        @foreach($Remarks as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required>
                                                            {{$option['probable_remarks']}}
                                                        </option>
                                                        @endforeach
                                                    </select><br>

                                                    <label for="remark"><b>Remark (Less than 250 words)</b></label>
                                                    <input type="text" placeholder="Remark" class="form-control" id="remark_data" name="remark" value="" required>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- MODAL FADE CODE BELOW FOR FORWARD -->

                                <div class="modal fade" id="remarkForwardModal" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form name="forwardForm" action="{{ route('forwardDetailsFromDPAssistToDPNodal', Crypt::encryptString($data->ein)) }}" method="Post">
                                            @csrf
                                            <!-- @method('GET') -->

                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remarkForwardModalTitle">Remark </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="efileDP"><b>eFile number</b></label>
                                                    <input type="text" placeholder="efileDP" class="form-control" id="efile_dp" name="efile_dp" value="" required>
                                                    <br>
                                                    <label for="dp_efile_link"><b>Browse eFile:</b></label>

                                                    <input type="file" class="form-control" id="dp_efile_link" name="dp_efile_link" accept="application/pdf" value="" required>
                                                    <br>

                                                    <label for="remark"><b>Select a reason: </b></label>
                                                    <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                    <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                    <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                    <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                        <option selected>Select</option>
                                                        @foreach($RemarksApprove as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required>
                                                            {{$option['probable_remarks']}}
                                                        </option>
                                                        @endforeach
                                                    </select><br>

                                                    <label for="remark_details"><b>Any Description (Less than 250
                                                            words)</b></label>
                                                    <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- MODAL FADE CODE BELOW FOR FORWARD CHECKED-->

                                <div class="modal fade" id="remarkForwardModal_Checked" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form action="{{ route('forwardSelectedEINsDPAssistToDPNodal') }}" method="post" enctype="multipart/form-data">
                                            @csrf
                                            <!-- @method('GET') -->

                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    {{-- <label for="modalSelectedEIN"><b>Selected EIN:</b></label> --}}
                                                    <input type="hidden" class="form-control" id="modalSelectedEIN" name="modalSelectedEIN" value="" readonly>
                                                    <label for="efileDP"><b>eFile number</b></label>
                                                    <input type="text" placeholder="efileDP" class="form-control" id="efile_dp" name="efile_dp" value="" required>
                                                    <br>
                                                    <label for="dp_efile_link"><b>Browse eFile:</b></label>

                                                    <input type="file" class="form-control" id="dp_efile_link" name="dp_efile_link" accept="application/pdf" value="" required>
                                                    <br>

                                                    <label for="remark"><b>Select a reason: </b></label>
                                                    <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                    <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                    <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                    <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                        <option selected>Select</option>
                                                        @foreach($RemarksApprove as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required>
                                                            {{$option['probable_remarks']}}
                                                        </option>
                                                        @endforeach

                                                    </select><br>

                                                    <label for="remark_details"><b>Any Description (Less than 250
                                                            words)</b></label>
                                                    <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- MODAL FADE CODE BELOW FOR FORWARD CHECKED-->
                                @endforeach

                                @endif

                            </tbody>
                        </table>
                    </div>

                    <!-- ///////The below table is for print -->
                    <div class="table-responsive display" id="card1-table">
                        <table class="table table-bordered table-condensed table-striped">

                            <thead class="thead-dark">
                                <tr>

                                    <th scope="col">Sl.No.</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">Department Name</th>
                                    <th scope="col">DOE</th>
                                    <th>Application No.</td>
                                    <th>Applicant Name</td>
                                    <th scope="col">Mobile No.</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach($empListprint as $data)
                                <tr>
                                    @if($data->status == 'Appointed')
                                    <td>
                                        <input type="checkbox" name="selectedGrades[]" id="{{$data->efile_dp.'|'.$data->dept_id.'|'.$data->ein}}" value="{{ $data->ein }}" onchange="onChangeCheckBox(this);onChangeCheckBox1(this)">
                                    </td>
                                    @endif

                                    @if($data->status == 'Approved')
                                    <td>

                                    </td>
                                    @endif
                                    <td scope="row">{{$loop->index + 1}}</td>
                                    <td>{{$data->ein}}</td>
                                    <td>{{$data->deceased_emp_name}}</td>
                                    <td>{{$data->dept_name}}</td>
                                    <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    <td>{{$data->appl_number}}</td>

                                    <td>{{$data->applicant_name}}</td>

                                    <td>{{$data->applicant_mobile}}</td>

                                    @if($data->status == '5')
                                    <td>
                                        Appointted
                                    </td>
                                    @endif

                                    @if($data->status == '4')
                                    <td>
                                        Approved
                                    </td>
                                    @endif

                                    @if($data->formSubStat == "submitted")
                                    <td class="textright">
                                     

                                        <!-- capturing the ein at click instant -->
                                     

                                    </td>
                                    @endif

                                    @if($data->formSubStat == "started")
                                   
                                    @endif
                                    @if($data->formSubStat == "approved")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
                                    </td>

                                    <td>
                                        <a href="{{ route('fill_uo', Crypt::encryptString($data->ein)) }}" id="fill_uo" class="btn btn-primary width_height2">Fill UO</a>

                                    </td>
                                   
                                  
                                    @endif
                                    @if($data->formSubStat == "appointed")
                                    <td>
                                        <a href="{{ route('generate-pdf', Crypt::encryptString($data->ein))}}" id="generate_uo" class="badge btn btn-success text-wrap width_height" role="button" aria-disabled="true" target="_blank">Generate Single UO</a>
                                    </td>

                                    @endif

                                    @if($data->formSubStat == "order")
                                    <td class="textright">
                                        <a href="{{ route('viewOrder', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm width_height" role="button" aria-disabled="true">View Order</a>
                                        <!-- capturing the ein at click instant -->

                                    </td>

                                    @endif


                                </tr>




                                <!-- MODAL FADE CODE BELOW FOR FORWARD -->


                                @endforeach




                            </tbody>
                        </table>
                    </div>
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
        // printWindow.document.write(
        //     "<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");


        printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
        // printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
        printWindow.document.write("<p>{{$getProjectShortForm}}</p>");
        printWindow.document.write("<p> List</p>");
        printWindow.document.write("</div></div>");
        printWindow.document.write("<p><br></br></p>");
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }








    function setRevertData(temp_array) {
        // console.log(temp_array['ein']);
        if (!confirm('Are You Sure that the Applicant File is NOT OK?')) {
            return;
        }
        $("#remarkModal").modal('show');
        let form = document.forms['revertForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])

    }

    function setForwardData(temp_array) {
        // console.log(temp_array['ein']);
        if (!confirm('Are You Sure that the Applicant File is OK ?')) {
            return;
        }
        $("#remarkForwardModal").modal('show');
        let form = document.forms['forwardForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])

    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {


        document.getElementById('removeBtn').addEventListener('click', function() {
            // Clear file input
            document.getElementById('dp_efile_link').value = '';
        });
    });
</script>

<!-- Add these lines to include jQuery and Bootstrap -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>






<script>
    $(document).ready(function() {
        // Use event delegation for dynamically generated content
        $(document).on('click', '.open-modal', function() {
            var selectedEINs = $('input[name="selected_ein[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedEINs.length > 0) {
                $('#modalSelectedEIN').val(selectedEINs.join(
                    ', ')); // Display all selected EINs separated by commas
                $('#remarkForwardModal_Checked').modal('show');
            } else {
                alert('Please select an EIN before opening the modal.');
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        // Use event delegation for dynamically generated content
        $(document).on('click', '.openModalSingle', function() {
            var selectedEINs = $('input[name="selected_ein[]"]:checked').map(function() {
                return this.value;
            }).get();

            if (selectedEINs.length > 0) {
                $('#modalSelectedEIN').val(selectedEINs.join(
                    ', ')); // Display all selected EINs separated by commas
                $('#remarkForwardModal').modal('show');
            } else {
                alert('Please select an EIN before opening the modal.');
            }
        });
    });

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
<script>
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
            alert("Please check whether eFile from DP and Department name are same...");
        }
    }
</script>


<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>