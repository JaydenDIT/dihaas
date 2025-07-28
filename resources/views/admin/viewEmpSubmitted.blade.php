<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')
<link href="{{ asset('assets/css/viewFileStatus.css') }}" rel="stylesheet" >
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ __('Submitted Applicants') }}</h3>
                        </div>
                        <div class="col-6">
                        <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ url('ddo-assist/viewEmpSubmittedSearch') }}" enctype="multipart/form-data" class="was-validated">
                                <div class="row textright" >
                                    @csrf 
                                    <div class="col-10 margin_textalign" >
                                        <input type="text" class="form-control marginright" placeholder="Search by EIN NO." name="searchItem" >
                                    </div>
                                    <div class="col-2 margin_textalign2" >
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
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
                    <b class="color">List of applicants whose details are Completed</b>
                    <hr>
                    <button id="myButton" type="button" class="btn btn-success" onclick="printList()">Print</button>
                    <p>
                    <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Sl.No.</th>
                                <th scope="col">EIN</th>
                                <th scope="col">Deceased Name</th>
                                <th scope="col">DOE</th>
                                <th>Application No.</td>
                                <th>Submitted Date</td>
                                <th>Applicant Name</td>
                                <th class="col-md-1">DOB</td>
                                <th scope="col">Mobile No.</th>
                                <th scope="col">status</th>
                                <th scope="col" colspan="4" class="textcenter">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($empListArray))
                            <tr>
                                <td colspan="11" class="textcenter">
                                    <b>No Data Found!</b>
                                </td>
                            </tr>
                            @else

                            @foreach($empList as $key => $data)

                            <tr>
                            <td>{{ $empList->firstItem() + $key }}</td>
                                <td>{{$data->ein}}</td>
                                <td>{{$data->deceased_emp_name}}</td>
                                <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}</td>
                                <td>{{$data->appl_number}}</td>
                                <td>{{$data->appl_date ? \Carbon\Carbon::parse($data->appl_date)->format('d/m/Y') : 'NA'}}</td>
                                <td>{{$data->applicant_name}}</td>
                                <td>{{$data->applicant_dob ? \Carbon\Carbon::parse($data->applicant_dob)->format('d/m/Y') : 'NA'}}</td>
                                <td>{{$data->applicant_mobile}}</td>
                                <!-- <td>Verified</td> -->
                                <td>{{$data->status}}</td>


                                @if($data->formSubStat == "verified")
                                <td classs="textright">
                                    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                    @php
                                    $temp_array = [];
                                    $ein = $data->ein;
                                    $appl_no = $data->appl_number;
                                    $temp_array['ein'] = $ein;
                                    $temp_array['appl_no'] = $appl_no;
                                    @endphp
                                    <button class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward to HOD</button>
                                    <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->

                                    <!-- capturing the ein at click instant -->
                                    @php
                                    $temp_array = [];
                                    $ein = $data->ein;
                                    $appl_no = $data->appl_number;
                                    $temp_array['ein'] = $ein;
                                    $temp_array['appl_no'] = $appl_no;
                                    @endphp
                                    <button class="btn btn-danger btn-sm borderradius" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>
                                    <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->


                                    <a href="{{ route('discard-started-employee', Crypt::encryptString($data->ein)) }}" class="btn btn-danger btn-sm borderradius" role="button" aria-disabled="true" onclick="return confirm('Are You Sure to Discard this Applicant?')">Discard</a>

                                </td>
                                @endif

                                @if($data->formSubStat == "started")

                                @endif

                            </tr>
                            <div class="modal fade" id="forwardModal" tabindex="-1" aria-labelledby="forwardModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form name="forwardForm" action="{{ route('forwardDetailsFrom', Crypt::encryptString($data->ein)) }}" method="Post">
                                        @csrf
                                        <!-- @method('GET') -->


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="forwardModalTitle">Remark</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="remark"><b>Select a reason:  </b></label>
                                                <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                <!-- <input type="text" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                    <option selected>Select</option>
                                                    @foreach($Remarks as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                    @endforeach


                                                </select><br>

                                                <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
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
                            <!-- MODAL FADE CODE BELOW FOR REVERT -->

                            <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form name="revertForm" action="{{ route('revertDetailsFrom', Crypt::encryptString($data->ein)) }}" method="Post">
                                        @csrf
                                        <!-- @method('GET') -->


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="remark"><b>Select a Reason: </b></label>
                                                <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                <!-- <input type="text" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                    <option selected>Select</option>
                                                    @foreach($Remarks as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                    @endforeach


                                                </select><br>

                                                <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
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

                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    </div>
                    @if($empList != null)
                    <div class="row">
                        {!! $empList->links() !!}
                    </div>
                    @endif
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
</div>




@endsection

<script type="text/javascript">
    function setRevertData(temp_array) {
        //console.log(temp_array['ein']);
        // console.log(temp_array['appl_number']);
        if (!confirm('Are You Sure that the Applicant File is NOT OK?')) {
            return;
        }
        $("#remarkModal").modal('show');
        let form = document.forms['revertForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])
        // console.log(temp_array['appl_number'])

    }
    //for forwarding
    function setForwardData(temp_array) {
        //console.log(temp_array['ein']);
        // console.log(temp_array['appl_number']);
        if (!confirm('You are going to forward the applicant to Nodal Officer (Dept)!!!!!')) {
            return;
        }
        $("#forwardModal").modal('show');
        let form = document.forms['forwardForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])
        // console.log(temp_array['appl_number'])

    }

//Code for printList()
document.addEventListener("DOMContentLoaded", function() {
        var content = document.querySelectorAll("tbody tr");
        var printButton = document.getElementById("myButton");
        var empListArrayEmpty = "{{ empty($empListArray) }}";

        if (content.length === 0 || empListArrayEmpty) {
            // No records or empty empListArray, disable the Print button
            printButton.disabled = true;
        } else {
            // Records present, enable the Print button
            printButton.disabled = false;
        }
    });

    function printList() {
        var submittedApplicantNames = [];
        var submittedApplicantRows = document.querySelectorAll("tbody tr");
        submittedApplicantRows.forEach(function(row) {
            var data2 = row.querySelector("td:nth-child(2)").innerText;
            var data3 = row.querySelector("td:nth-child(3)").innerText;
            var data4 = row.querySelector("td:nth-child(4)").innerText;
            var data5 = row.querySelector("td:nth-child(5)").innerText;
            var data6 = row.querySelector("td:nth-child(6)").innerText;
            var data7 = row.querySelector("td:nth-child(7)").innerText;
            var data8 = row.querySelector("td:nth-child(8)").innerText;
            var data9 = row.querySelector("td:nth-child(9)").innerText;
            var data10 = row.querySelector("td:nth-child(10)").innerText;
            var submittedapplicant = {
                data2: data2,
                data3: data3,
                data4: data4,
                data5: data5,
                data6: data6,
                data7: data7,
                data8: data8,
                data9: data9,
                data10: data10,
            };
            submittedApplicantNames.push(submittedapplicant);
        });

        var printWindow = window.open("", "_blank");
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

        printWindow.document.write("<html><head><title>{{$getProjectShortForm}}</title></head><body>");
        printWindow.document.write("<div style='display: flex; align-items: center;'>");
        printWindow.document.write("<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");
        printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
        printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
        printWindow.document.write("<p>List of applicants whose details are Completed</p>");
        printWindow.document.write("</div></div>");
        printWindow.document.write('<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}</style>');
        printWindow.document.write("<table>");
        printWindow.document.write("<thead><tr><th>Serial</th><th>EIN</th><th>Deceased Name</th><th>DOE</th><th>Application No.</th><th>Submitted Date</th><th>Application Name</th><th>DOB</th><th>Mobile No.</th><th>status</th></tr></thead>");
        printWindow.document.write("<tbody>");
        submittedApplicantNames.forEach(function(submittedapplicant, index) {
            printWindow.document.write("<tr>");
            printWindow.document.write("<td>" + (index + 1) + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data2 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data3 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data4 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data5 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data6 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data7 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data8 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data9 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data10 + "</td>");

            printWindow.document.write("</tr>");
        });
        printWindow.document.write("</tbody></table>");
        printWindow.document.write("</body></html>");
        printWindow.document.close();
        printWindow.print();
    }

</script>