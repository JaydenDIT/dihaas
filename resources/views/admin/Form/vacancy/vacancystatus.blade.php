

    @extends('layouts.app')

    @section('content')
    <style nonce="{{ csp_nonce() }}">
table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 8px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {background-color: coral;}
</style>

    <?php

        $getYear = date('Y');

        ?>

    <link href="{{ asset('assets/css/vacancystatus.css') }}" rel="stylesheet">  
   
    <div class="container">
    <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <h3>{{ __('Vacancy status') }}</h3>
                            </div>
                            <div class="col-6">
                                <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ url('ddo-assist/vacancystatusSearch') }}" enctype="multipart/form-data" class="was-validated">
                                    <div class="row textright">
                                        @csrf

                                        <div class="col-10 marginright_textalign">
                                            <input type="text" class="form-control marginright" placeholder="Search by Designation" name="searchItem">
                                        </div>
                                        <div class="col-2 margin_textalign">
                                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Add input field for search query

                     
                         <div class="col-3"> -->

                            <!-- Add input field for search query -->
                            <!-- <input type="text" id="searchInput" class="form-control" placeholder="Search..."> -->
                            <!-- </div> -->

                        </div>
                    </div>

                    <div class="card-body">

                        <b sclass="color">CMIS Vacancy as on 31st March {{$getYear}}</b>
                        <hr>
                       

                        @if(empty($vacancyList))
                                
                                <button type="button" class="btn btn-success" disabled>Print</button>
                                @else
                                <button id="printButtonCard1" type="button" class="btn btn-success " >Print</button>
                                @endif
                      
                        <br>
                        <br>

                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr class="text-center font_background_color">
                                        <th colspan="4"><b>Vacancy As Per CMIS</b></th>
                                        <th colspan="3"><b>Vacancy As Per Department</b></th>
                                    </tr>
                                   
                                    <tr>

                                        <th style="background-color: #f0d597;">Designation</th>
                                        <th style="background-color: #f0d597;">Post Count</th>
                                        <th style="background-color: #f0d597;">Employee Count</th>
                                        <th style="background-color: #f0d597;">Vacancy</th>
                                         <th style="background-color: #04AA6D;">Current Employee Under DIH</th>
                                        <th style="background-color: #04AA6D;">Total Post Vacant</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for DIH</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for Direct Recruitment</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(empty($vacancyList))
                                    <tr>
                                        <td colspan="8" class="textcenter"">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach($vacancyData as $data)
                                    <tr>

                                        <td>{{ $data->designation }}</td>
                                        <td>{{ $data->post_count }}</td>
                                        <td>{{ $data->emp_cnt }}</td>
                                        <td>{{ $data->vacancy }}</td>
                                          <td style="background-color: #04AA6D;" id="current_employee_under_dih_{{ $data->id }}">{{ $data->current_employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="total_post_vacant_dept_{{ $data->id }}">{{ $data->total_post_vacant_dept }}</td>
                                        <td style="background-color: #04AA6D;" id="employee_under_dih_{{ $data->id }}">{{ $data->employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="post_under_dih_{{ $data->id }}">{{ $data->post_under_direct }}</td>
                                       
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>
<!-- ------------------The below table is for Printing Purpose ------------------------------------->

                        <div class="table-responsive displaynone" id="card1-table" >

                            <table class="table">
                                <thead>
                                    <tr class="text-center">
                                        <th colspan="4"><b>Vacancy As Per CMIS</b></th>
                                        <th colspan="3"><b>Vacancy As Per Department</b></th>
                                    </tr>
                                  
                                    <tr>

                                        <th>Designation</th>
                                        <th>Post Count</th>
                                        <th>Employee Count</th>
                                        <th>Vacancy</th>
                                         <th style="background-color: #04AA6D;">Employee Under DIH</th>
                                        <th style="background-color: #04AA6D;">Total Post Vacant</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for DIH</th>
                                        <th style="background-color: #04AA6D;">Post Vacant for Direct Recruitment</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(empty($vacancyList))
                                    <tr>
                                        <td colspan="8" class="textcenter">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else
                                    @foreach($vacancyDataPrint as $data)
                                    <tr>

                                        <td>{{ $data->designation }}</td>
                                        <td>{{ $data->post_count }}</td>
                                        <td>{{ $data->emp_cnt }}</td>
                                        <td>{{ $data->vacancy }}</td>
                                         <td style="background-color: #04AA6D;" id="current_employee_under_dih_{{ $data->id }}">{{ $data->current_employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="total_post_vacant_dept_{{ $data->id }}">{{ $data->total_post_vacant_dept }}</td>
                                        <td style="background-color: #04AA6D;" id="employee_under_dih_{{ $data->id }}">{{ $data->employee_under_dih }}</td>
                                        <td style="background-color: #04AA6D;" id="post_under_dih_{{ $data->id }}">{{ $data->post_under_direct }}</td>
                                       
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>

                        </div>


                        <!-- Pagination Links -->
                        @if($vacancyData != null)
                        <div class="row">
                            {!! $vacancyData->links() !!}
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script nonce="{{ csp_nonce() }}">
        //The below code is for print
        // The print function for Card 1
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
        // printWindow.document.write(
        //     "<img src='https://cmdahaisi.mn.gov.in/images/kanglasha.png' alt='Image' width='80' height='100'>");
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

@endsection
