<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')
<link href="{{ asset('assets/DataTableCompact/datatablecompact.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/viewFileStatus.css') }}" rel="stylesheet">
<div class="container mt-3">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row  container mt-3">
                        <div class="col-6">
                            <h3>{{ __('Applications Status') }}</h3>
                        </div>
                        <div class="col-6">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ route('viewFileStatusSearch') }}" enctype="multipart/form-data" class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-10 margin_textalign">
                                        <input type="text" class="form-control marginright" placeholder="Search by EIN NO." name="searchItem">
                                    </div>
                                    <div class="col-2 margin_textalign2">
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
                   
                   
                    @if(empty($empListArray))

                    <button class="btn btn-success" disabled>Download Pdf</button>
                    <button id="myButton" name="myButton" type="button" class="btn btn-success" disabled>Print</button>
                @else
                <a href="{{ route('viewFileStatus.downloadPDFStatus') }}" target=”_blank”>

                    <button id="myButton2" class="btn btn-success">Download Pdf</button>
                </a>
                <button id="myButton" name="myButton" type="button" class="btn btn-success">Print</button>
                 @endif

                   

                    <p>
                    <div class="table-responsive">
                        <table class="table table-bordered shadow table-sm display data-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Seniority List Order</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">DOE</th>
                                    <th>Submitted Date</td>
                                    <th>Applicant Name</td>
                                    <th>Sent By</td>
                                    <th>Currently With</td>
                                    <th>Remarks</td>
                                    <th style="color:red;">status</td>
                                    <th scope="col" colspan="4" class="textcenter">Action</th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="10" class="textcenter">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>
                                @else

                                @foreach($empList as $key => $data)

                                <tr>
                                    <td>{{ $empList->firstItem() + $key }}</td>
                                    <td>{{$data->ein}}</td>
                                    <td>{{$data->deceased_emp_name}}</td>
                                    <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}
                                    </td>

                                    <td>{{$data->appl_date ? \Carbon\Carbon::parse($data->appl_date)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    <td>{{$data->applicant_name}}</td>
                                    <td>{{$data->sent_by}}</td>
                                    <td>{{$data->received_by}}</td>
                                    <td>{{$data->remark}}</td>

                                    <td style="color:red;">{{$data->status}}</td>

                                    @if($data->formSubStat == "submitted")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>


                                    </td>
                                    @endif



                                    {{-- @if($data->formSubStat == "started")
                                    <!-- <td class="textright">
                                    <a href="{{ route('Proforma_ApplicantDetails', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius"  role="button" aria-disabled="true">Update</a>

                                   


                                </td> --> --}}
                                    @endif
                                    @if($data->formSubStat == "verifieddp")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                    </td>
                                    @endif

                                     @if($data->formSubStat == "verifieddept")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                   </td>
                                    @endif

                                    @if($data->formSubStat == "forapproval")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->


                                    </td>
                                    @endif
                                    @if($data->formSubStat == "approved")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
                                    </td>
                                    @endif
                                    @if($data->formSubStat == "appointed")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
\

                                    </td>
                                    @endif

                                    @if($data->formSubStat == "order")
                                    <td class="textright">
                                        <a href="{{ route('generate-pdf', Crypt::encryptString($data->ein)) }}" target=”_blank” class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View Order</a>
                                        <!-- capturing the ein at click instant -->

                                    </td>

                                    @endif


                                </tr>
                                <!-- MODAL FADE CODE BELOW FOR REVERT -->

                                <!-- <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form name="revertForm" action="{{ route('revertPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" method="Post">
                                        @csrf
                                   


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="name"><b>Select a reason: </b></label>
                                             <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                    <option selected>Select</option>
                                                    @foreach($Remarks as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                    @endforeach


                                                </select><br>

                                                <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                                <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="{{ $data['remark_details'] == null ? null : $data['remark_details'] }}">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> -->
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
<!-- <script type="text/javascript" src="{{ asset('assets/DataTableCompact/datatablecompact.js') }}"></script> -->

<script nonce="{{ csp_nonce() }}">



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

    document.addEventListener("DOMContentLoaded", function() {
        var content = document.querySelectorAll("tbody tr");
        var printButton = document.getElementById("myButton");
        var printButton2 = document.getElementById("myButton2");
        var empListArrayEmpty = "{{ empty($empListArray) }}";

        if (content.length === 0 || empListArrayEmpty) {
            // No records or empty empListArray, disable the Print button
            printButton.disabled = true;
            printButton2.disabled = true;
        } else {
            // Records present, enable the Print button
            printButton.disabled = false;
            printButton2.disabled = false;
        }
    });

   
    document.addEventListener("DOMContentLoaded", function() {
$("#myButton").on('click', function() {
            printList();
        });
    }); 
  


    function printList() {

        var applicantNames = [];
        var applicantRows = document.querySelectorAll("tbody tr");
        applicantRows.forEach(function(row) {
            var data2 = row.querySelector("td:nth-child(2)").innerText;
            var data3 = row.querySelector("td:nth-child(3)").innerText;
            var data4 = row.querySelector("td:nth-child(4)").innerText;
            var data5 = row.querySelector("td:nth-child(5)").innerText;
            var data6 = row.querySelector("td:nth-child(6)").innerText;
            var data7 = row.querySelector("td:nth-child(7)").innerText;
            var data8 = row.querySelector("td:nth-child(8)").innerText;
            var data9 = row.querySelector("td:nth-child(9)").innerText;
            var data10 = row.querySelector("td:nth-child(10)").innerText;
            // var data11 = row.querySelector("td:nth-child(11)").innerText;

            var applicant = {
                data2: data2,
                data3: data3,
                data4: data4,
                data5: data5,
                data6: data6,
                data7: data7,
                data8: data8,
                data9: data9,
                data10: data10,
                //  data11: data12,

            };
            applicantNames.push(applicant);
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
        // printWindow.document.write(       
          
        //       "<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");
        printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
        printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
        printWindow.document.write("<p>In-Order of Seniority</p>");

        printWindow.document.write("</div></div>");
        printWindow.document.write(
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}</style>'
        );
        printWindow.document.write("<table>");
        printWindow.document.write(
            "<thead><tr><th>Seniority List Order</th><th>EIN</th><th>Deceased Name</th><th>DOE</th><th>Submitted Date</th><th>Application Name</th><th>Sent By</th><th>Currently With</th><th>Remarks</th><th>status</th></tr></thead>"
        );
        printWindow.document.write("<tbody>");
        applicantNames.forEach(function(applicant, index) {
            printWindow.document.write("<tr>");
            printWindow.document.write("<td>" + (index + 1) + "</td>");
            printWindow.document.write("<td>" + applicant.data2 + "</td>");
            printWindow.document.write("<td>" + applicant.data3 + "</td>");
            printWindow.document.write("<td>" + applicant.data4 + "</td>");
            printWindow.document.write("<td>" + applicant.data5 + "</td>");
            printWindow.document.write("<td>" + applicant.data6 + "</td>");
            printWindow.document.write("<td>" + applicant.data7 + "</td>");
            printWindow.document.write("<td>" + applicant.data8 + "</td>");
            printWindow.document.write("<td>" + applicant.data9 + "</td>");
            printWindow.document.write("<td>" + applicant.data10 + "</td>");
            // printWindow.document.write("<td>" + applicant.data11 + "</td>");
            //  printWindow.document.write("<td>" + applicant.data12 + "</td>");


            printWindow.document.write("</tr>");
        });
        printWindow.document.write("</tbody></table>");
        printWindow.document.write("</body></html>");
        printWindow.document.close();
        printWindow.print();

    }
</script>