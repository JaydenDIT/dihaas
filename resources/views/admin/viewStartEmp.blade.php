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
                    <form method="POST" action="{{ route('submitApplicant') }}">
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
                    
                        @if(empty($empListArray))

                        <button class="btn btn-success" disabled>Download Pdf</button>
                    @else
                    <a href="{{ route('viewStartEmp.downloadPDFApplView') }}" target="_blank" class="textdeconone">
                        <button id="myButton" class="btn btn-success">Download Pdf</button>
                    </a>
                     @endif
                   

                   <div class="row">

                        <div class="col-7">
                            <b class="color">List of Applicants for DIH</b>
                        </div>
                        <div class="col-5">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                                action="{{ url('ddo-assist/viewStartEmpSearch') }}"
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



                    <p>

                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                     <th scope="col">Select</th>
                                    <th scope="col">Seniority List Order</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">DOE</th>
                                    <th>Submitted Date</td>
                                    <th>Applicant Name</td>
                                    <th>DOB</td>
                                    <th scope="col" style="color:red;">status</th>
                                    <th scope="col">Mode</th>
                                    <th scope="col">Department</th>
                                    <th scope="col" colspan="4" class="text-center" >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="10" class="textcenter">
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
                                @if($data->formSubStat == "submitted" || $data->formSubStat == "transfer" || $data->formSubStat == "order" || $data->formSubStat == "appointed" || $data->formSubStat == "approved" || $data->formSubStat == "forapproval" || $data->formSubStat == "verified")
                                        <td>
                                            <input type="checkbox" id="{{$data->efile_dp.'|'.$data->dept_id.'|'.$data->ein}}"
                                                class="ein-checkbox" name="selected_ein[]"
                                                value="{{$data->ein}}" onchange="onChangeCheckBoxForward(this)">
                                        </td>
                                        @endif
                                {{-- <td>{{ $empList->firstItem() + $key }}</td> --}}
                                 <th scope="row">{{$data->slNo}}</th>
                                    <td>{{$data->ein}}</td>
                                    <td>{{$data->deceased_emp_name}}</td>
                                    <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}</td>
                                    <td>{{$data->appl_date ? \Carbon\Carbon::parse($data->appl_date)->format('d/m/Y') : 'NA'}}</td>
                                    <td>{{$data->applicant_name}}</td>
                                    <td>{{$data->applicant_dob ? \Carbon\Carbon::parse($data->applicant_dob)->format('d/m/Y') : 'NA'}}</td>
                                    <td style="color:red;">{{$data->status}}</td>
                                    <td>{{$mode}}</td>
                                     <td>{{$data->dept_name}}</td>


                                    @if($data->formSubStat == "submitted")
                                    <td class="rightstyle">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm blockstyle" role="button" aria-disabled="true">View</a>
                                    </td>
                                    <td>
                                        <a href="{{ route('verifyPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm blockstyle" role="button" aria-disabled="true" onclick="return confirm('Are You Sure that the Applicant File is OK?')">Verify</a>
                                    </td>
                                    <td>

                                        <!-- capturing the ein at click instant -->
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-danger btn-sm blockstyle" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>
                                        <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->
                                    </td>
                                    {{-- <td>
                                        <a href="{{ route('discard-started-employee', Crypt::encryptString($data->ein)) }}" class="btn btn-danger btn-sm blockstyle" role="button" aria-disabled="true" onclick="return confirm('Are You Sure to Discard this Applicant?')">Delete</a>


                                    </td> --}}
                                    @endif

                                    @if($data->formSubStat == "started")
                                    {{-- <td class="rightstyle">
                                        <a href="{{ route('Proforma_ApplicantDetails', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm blockstyle" role="button" aria-disabled="true">Update</a>
                                    </td> --}}
                                    {{-- <td>
                                        <a href="{{ route('discard-started-employee', Crypt::encryptString($data->ein)) }}" class="btn btn-danger btn-sm blockstyle" role="button" aria-disabled="true" onclick="return confirm('Are You Sure to Discard this Applicant?')">Delete</a>


                                    </td> --}}
                                    @endif
                                    @if($data->formSubStat == "verified")
                                    <td class="rightstyle">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm blockstyle" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
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
                                        <button class="btn btn-success btn-sm blockstyle" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward to Department</button>
                                    </td>
                                    <td>
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-danger btn-sm blockstyle" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>
                                        <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->
                                    </td>

                                    @endif
                                @if($data->formSubStat == "transfer")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>


                                    </td>
                                    @endif
                                </tr>
                                <!-- MODAL FADE CODE BELOW FOR REVERT -->

                                <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form name="revertForm" action="{{ route('revertPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" method="Post">
                                            @csrf
                                            <!-- @method('GET') -->


                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="name"><b>Select a reason: </b></label>
                                                    <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                    <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                    <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">

                                                    <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                        <option selected>Select</option>
                                                        @foreach($Remarks as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                        @endforeach


                                                    </select><br>

                                                    <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                                    <input type="text" placeholder="Description" class="form-control" id="remark_details" name="remark_details" value="{{ $data['remark_details'] == null ? null : $data['remark_details'] }}">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal fade" id="remarkForwardModal" tabindex="-1"
                                aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form name="forwardForm"
                                        action="{{ route('forwardDetailsFromDP', Crypt::encryptString($data->ein)) }}"
                                        method="Post" enctype="multipart/form-data">
                                        @csrf
                                        <!-- @method('GET') -->

                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="remark"><b>Select a Reason: </b></label>
                                                <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                <input type="hidden" class="form-control" id="ein" name="ein"
                                                    value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="remark" name="remark">
                                                    <option selected>Select</option>
                                                    @foreach($RemarksApprove as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required>
                                                        {{$option['probable_remarks']}}</option>
                                                    @endforeach

                                                </select><br>

                                                <label for="remark_details"><b>Remark (Less than 250
                                                        words)</b></label>
                                                <input type="text" placeholder="Remark" class="form-control"
                                                    id="remark_details" name="remark_details" value="">
                                                <br>

                                                <label for="pdf_file"><b>Upload PDF:</b></label>
                                                <input type="file" class="form-control" id="pdf_file"
                                                    name="pdf_file">

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
                            @endforeach


                                @endif

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





@endsection

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var content = document.querySelectorAll("tbody tr");
        var printButton = document.getElementById("myButton");
        var printButton2 = document.getElementById("Button");
        var empListArrayEmpty = "{{ empty($empListArray) }}";;

        if (content.length === 0 || empListArrayEmpty) {
            // No records or empty empListArray, disable the Print button
            // printButton.disabled = true;
            // printButton2.disabled = true;
        } else {
            // Records present, enable the Print button
            // printButton.disabled = false;
            // printButton2.disabled = true;
        }
    });

    // function printList() {
    //     var submittedApplicantNames = [];
    //     var submittedApplicantRows = document.querySelectorAll("tbody tr");
    //     submittedApplicantRows.forEach(function(row) {
    //         var data2 = row.querySelector("td:nth-child(2)").innerText;
    //         var data3 = row.querySelector("td:nth-child(3)").innerText;
    //         var data4 = row.querySelector("td:nth-child(4)").innerText;
    //         var data5 = row.querySelector("td:nth-child(6)").innerText;
    //         var data6 = row.querySelector("td:nth-child(7)").innerText;
    //         var data7 = row.querySelector("td:nth-child(5)").innerText;
    //         var data8 = row.querySelector("td:nth-child(8)").innerText;
    //         var data9 = row.querySelector("td:nth-child(9)").innerText;


    //         var submittedapplicant = {
    //             data2: data2,
    //             data3: data3,
    //             data4: data4,
    //             data5: data5,
    //             data6: data6,
    //             data7: data7,
    //             data8: data8,


    //         };
    //         submittedApplicantNames.push(submittedapplicant);
    //     });

    //     var printWindow = window.open("", "_blank");
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

    ?>

    //     $user_id = Auth::user()->id;
    //     $getUser = User::get()->where('id', $user_id)->first();
    //     $Department = DepartmentModel::get()->where('dept_id', $getUser->dept_id)->first();
    //     $getDeptName = $Department->dept_name;
    //     $getDate = Carbon::now()->format('Y-m-d');

    //    

    //     printWindow.document.write("<html><head><title>{{$getProjectShortForm}}</title></head><body>");
    //     printWindow.document.write("<div style='display: flex; align-items: center;'>");
    //     printWindow.document.write("<img src='https://cmdahaisi.mn.gov.in/images/kanglasha.png' alt='Image' width='80' height='100'>");
    //     printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
    //     printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
    //     printWindow.document.write("<p>List of applicants whose details are Completed and Incomplete</p>");
    //     printWindow.document.write("</div></div>");
    //     printWindow.document.write('<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}</style>');
    //     printWindow.document.write("<table>");
    //     printWindow.document.write("<thead><tr><th>Serial</th><th>EIN</th><th>Deceased Name</th><th>DOE</th><th>Application Name</th><th>DOB</th><th>Date of Submission</th><th>status</th></tr></thead>");
    //     printWindow.document.write("<tbody>");
    //     submittedApplicantNames.forEach(function(submittedapplicant, index) {
    //         printWindow.document.write("<tr>");
    //         printWindow.document.write("<td>" + (index + 1) + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data2 + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data3 + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data4 + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data5 + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data6 + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data7 + "</td>");
    //         printWindow.document.write("<td>" + submittedapplicant.data8 + "</td>");



    //         printWindow.document.write("</tr>");
    //     });
    //     printWindow.document.write("</tbody></table>");
    //     printWindow.document.write("</body></html>");
    //     printWindow.document.close();
    //     printWindow.print();
    // }

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