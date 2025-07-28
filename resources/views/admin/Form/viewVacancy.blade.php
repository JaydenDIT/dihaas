
@extends('layouts.app')

@section('content')
<div class="container">
<br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3>Vacancy Records</h3>
                
                </div>

             


                <div class="card-body">


                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                    @endif

                    <b style="color:blue">List of vacancies</b>

                    <hr>
                     <button id="myButton" type="button" class="btn btn-success">Print</button>
                     &nbsp;&nbsp;&nbsp;&nbsp; 
                     <a href="{{ route('vacancy_records') }}" class="btn btn-success">Back</a>
                    <table class="table text-center">
                        <thead>
                            <tr>
                                <th>Vacancy Year</th>
                                <th>Post Name</th>
                                <th>Sanctioned Post</th>
                                <th>Vacancy for Direct Recruitment</th>
                                <th>Employees under DIH</th>
                                <th>Post Vacant under DIH</th>
                                
                        </thead>
                        <tbody>
                            @foreach($vacancies as $vacancy)
                            <tr>
                                <td>{{ $vacancy->vacancy_year }}</td>
                                <td>{{ $vacancy->post_name }}</td>
                                <td>{{ $vacancy->sanctioned_post }}</td>
                                <td>{{ $vacancy->vacancy_direct }}</td>
                                <td>{{ $vacancy->employees_under_dih }}</td>
                                <td>{{ $vacancy->post_vacant_under_dih }}</td>
                               
                                 
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div>



                </div>

            </div>

        </div>

    </div>
    @endsection

    <script nonce="{{ csp_nonce() }}" type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var applicantRows = document.querySelectorAll("tbody tr");
        var printButton = document.getElementById("myButton");

        if (applicantRows.length === 0) {
            // No records, disable the Print button
            printButton.disabled = true;
        } else {
            // Records present, enable the Print button
            printButton.disabled = false;
        }
    });

    
    document.addEventListener("DOMContentLoaded", function() {
        var printButton = document.getElementById("myButton");
        
        printButton.addEventListener("click", function() {
            printList();
        });
    });

    function printList() {
        var submittedApplicantNames = [];
        var submittedApplicantRows = document.querySelectorAll("tbody tr");
        submittedApplicantRows.forEach(function(row) {
            var data1 = row.querySelector("td:nth-child(1)").innerText;
            var data2 = row.querySelector("td:nth-child(2)").innerText;
            var data3 = row.querySelector("td:nth-child(3)").innerText;
            var data4 = row.querySelector("td:nth-child(4)").innerText;
            var data5 = row.querySelector("td:nth-child(5)").innerText;
            var data6 = row.querySelector("td:nth-child(6)").innerText;

            var submittedapplicant = {
                data1: data1,
                data2: data2,
                data3: data3,
                data4: data4,
                data5: data5,
                data6: data6,

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

        printWindow.document.write("<html><head><title>{{$getSoftwareName}}</title></head><body>");
        printWindow.document.write("<div style='display: flex; align-items: center;'>");
        // printWindow.document.write(
        //     "<img src='https://cmdahaisi.mn.gov.in/images/kanglasha.png' alt='Image' width='80' height='100'>");
        printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
        printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
        printWindow.document.write("<p>List of vacancies</p>");
        printWindow.document.write("</div></div>");
        printWindow.document.write(
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding: 5px;}}</style>'
        );
        printWindow.document.write("<table>");
        printWindow.document.write(
            "<thead><tr><th>Vacancy Year</th><th>Post Name</th><th>Sanctioned Post</th><th>Vacancy for Direct Recruitment</th><th>Employees under DIH</th><th>Post Vacant Under DIH</th></tr></thead>"
        );
        printWindow.document.write("<tbody>");
        submittedApplicantNames.forEach(function(submittedapplicant) {
            printWindow.document.write("<tr>");
            // printWindow.document.write("<td>" + (index + 1) + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data1 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data2 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data3 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data4 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data5 + "</td>");
            printWindow.document.write("<td>" + submittedapplicant.data6 + "</td>");


            printWindow.document.write("</tr>");
        });
        printWindow.document.write("</tbody></table>");
        printWindow.document.write("</body></html>");
        printWindow.document.close();
        printWindow.print();
    }
    </script>