<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->

@extends('layouts.app')

@section('content')

<link href="{{ asset('assets/css/screening.css') }}" rel="stylesheet">

<?php $selected = session()->get('deptId') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                   
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
                    <b class="colourblue">Seniority List</b>
                    <hr>
                    <p>

                    <div class="col-6">
                        <!-- the below route to new one and controller idea copy from viewempstartsearch function -->
                        <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ url('ddo-assist/viewSeniorityStatusSearch') }}" enctype="multipart/form-data" class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-4 marginright_text_right" >
                                        <input type="text" class="form-control margin_right" placeholder="Search by EIN NO." name="searchItem" >
                                    </div>
                                    <div class="col-2 margin_textalign" >
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                                <p>
                    <div class="textright">
                        
                        @if(empty($empListArray))

                        <button class="btn btn-success" disabled>Download Pdf</button>
                    @else
                    <a href="{{ route('viewSeniorityStatus.downloadseniorityPDF') }}" class="btn btn-success" target=”_blank”>Download As Pdf</a>
                    @endif
                        <!-- below code function is already written in control but need to check whether the select option is blank or "Select" then download pdf route is below -->
                        <!-- already put in web.php also -->
                     

                        <!-- &nbsp; &nbsp; &nbsp; &nbsp; <button type="button" class="btn btn-success" onclick=" printEmployeeList()">Print </button> -->
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed table-striped" id="table">

                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Sl.No.</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th>DOE</th>
                                    <th>Expired on Duty / Reason</th>
                                      <th>Applicant Name</th>
                                      
                                      <th>Applicant DOB</th>  
                                      <th>Submitted Date</th>  
                                                                       

                                </tr>
                            </thead>
                            <tbody>


                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="7" class="textcenter">
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
                                    <td>{{$data->expire_on_duty}} / {{$data->deceased_causeofdeath}}</td>
                                    <td>{{$data->applicant_name}}</td>
                                    <td>{{$data->applicant_dob ? \Carbon\Carbon::parse($data->applicant_dob)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    
                                    <td>{{$data->appl_date ? \Carbon\Carbon::parse($data->appl_date)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    
                                   
                                </tr>
                                
                              
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


<!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"> -->
<!-- <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script> -->


@endsection



<script type="text/javascript">
    function printEmployeeList() {



        var employeeList = [];
        var employeeRows = document.querySelectorAll("tbody tr");
        employeeRows.forEach(function(row) {
            var ein = row.querySelector("td:nth-child(2)").innerText;
            var deceasedName = row.querySelector("td:nth-child(3)").innerText;
            var dept_name = row.querySelector("td:nth-child(4)").innerText;
            var doe = row.querySelector("td:nth-child(5)").innerText;

            var applicationName = row.querySelector("td:nth-child(6)").innerText;
            var sentby = row.querySelector("td:nth-child(7)").innerText;
            var currently = row.querySelector("td:nth-child(8)").innerText;
            var remark = row.querySelector("td:nth-child(9)").innerText;
            var description = row.querySelector("td:nth-child(10)").innerText;

            var employee = {
                ein: ein,
                deceasedName: deceasedName,
                dept_name: dept_name,
                doe: doe,
                applicationName: applicationName,
                sentby: sentby,
                currently: currently,
                remark: remark,
                description: description


            };
            employeeList.push(employee);
        });

        var printWindow = window.open("", "_blank");
        <?php

        use App\Models\DepartmentModel;
        use App\Models\PortalModel;

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

        <?php

        use App\Models\User;
        use Carbon\Carbon;
        use Illuminate\Support\Facades\Auth;

        $user_id = Auth::user()->id;
        $getUser = User::get()->where('id', $user_id)->first();
        $Department = DepartmentModel::get()->where('dept_id', $getUser->dept_id)->first();
        $getDeptName = $Department->dept_name;
        $getDate = Carbon::now()->format('Y-m-d');

        ?>


        // <div style="text-align: center;font-size:30px">Department: {{$getDeptName}}</div>

        // <div style="text-align: center;font-size:25px">List of Applicants for DIH Scheme</div>
        // <div style="text-align: center;font-size:22px">In-Order of Seniority</div>
        // <div style="text-align: right;font-size:15px">Dated: {{ $getDate}}</div>

        printWindow.document.write("<html><head><title>{{$getProjectShortForm}}</title></head><body>");
        printWindow.document.write("<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");
        printWindow.document.write('<h2 title = "DIHAS"><center>Department of Personnel </center></h2>');
        printWindow.document.write("<p><center>For Department: {{$getDeptName}} </center></p>");
        printWindow.document.write("<p><center>List of Applicants for DIH Scheme</center></p>");
        printWindow.document.write("<p><center>In-Order of Seniority</center></p>");

        printWindow.document.write('<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}</style>');
        printWindow.document.write("<table>");

        printWindow.document.write("<thead><tr><th>Serial</th><th>EIN</th><th>Deceased Name</th><th>Department Name</th><th>DOE</th><th>Application Name</th><th>Sent By</th><th>Currently With</th><th>Remark</th><th>Description</th></tr></thead>");
        printWindow.document.write("<tbody>");
        employeeList.forEach(function(employee, index) {
            printWindow.document.write("<tr>");
            printWindow.document.write("<td>" + (index + 1) + "</td>");
            printWindow.document.write("<td>" + employee.ein + "</td>");
            printWindow.document.write("<td>" + employee.deceasedName + "</td>");
            printWindow.document.write("<td>" + employee.dept_name + "</td>");
            printWindow.document.write("<td>" + employee.doe + "</td>");
            printWindow.document.write("<td>" + employee.applicationName + "</td>");
            printWindow.document.write("<td>" + employee.sentby + "</td>");
            printWindow.document.write("<td>" + employee.currently + "</td>");
            printWindow.document.write("<td>" + employee.remark + "</td>");
            printWindow.document.write("<td>" + employee.description + "</td>");

            printWindow.document.write("</tr>");
        });
        printWindow.document.write("</tbody></img>");
        printWindow.document.write("</body></html>");
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