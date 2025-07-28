<!DOCTYPE html>
<html>

<head>
<link href="css/pdf.css" rel="stylesheet">
   

    <title></title>
    <style nonce="{{ csp_nonce() }}">
    @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                font-size: 20px !important;
                background-color: #423737;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 
                font-size: 20px !important;
                background-color: #423737;
                color: white;
                text-align: center;
                line-height: 35px;
            }
table {
    border-left: 0.01em solid black;
    border-right: 0;
    border-top: 0.01em solid black;
    border-bottom: 0;
    border-collapse: collapse;
}
table td,
table th {
    border-left: 0;
    border-right: 0.01em solid black;
    border-top: 0;
    border-bottom: 0.01em solid black;
}
    </style>
</head>

<body>
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
    $getDate = Carbon::now()->format('d-m-Y');

    ?>
    <header>
        {{$getProjectShortForm}}
    </header>
    <footer>
        Copyright © <?php echo date("Y");?> - DP & AR, Government of Manipur
    </footer>
    <!-- <a class="navbar-brand" href="{{ url('/') }}">
 <img src="https://cmdahaisi.mn.gov.in/images/kanglasha.png" width="60" height="60"></a> -->
 <div class="table-responsive">
    {{-- <div style="text-align: center;font-size:30px">{{$getProjectShortForm}}</div> --}}

    <div style="text-align: center;font-size:30px">Department: {{$getDeptName}}</div>

    <div style="text-align: center;font-size:25px">List of Reverted Applicants for DIH Scheme</div>
    <div style="text-align: center;font-size:22px">In-Order of Seniority</div>
    <div style="text-align: right;font-size:15px">Dated: {{ $getDate}}</div>
    <br>
    <table class="table table-bordered table-condensed table-striped" id="table">

        <thead class="thead-dark">
            <tr>Department:-
                <th scope="col">Sl.No.</th>
                <th scope="col">EIN</th>
                <th scope="col">Deceased Name</th>
                <!-- <th scope="col">Department Name</th> -->
                <th>Application No.</td>
                <th>Applicant Name</td>               
                <th scope="col">Mobile No.</th>
                <th>Remarks</td>
                <th scope="col">Description</th>
                <th>status</td>
                <!-- <th scope="col">status</th> -->

            </tr>
        </thead>
        <tbody>


            @if(empty($empListArray))
            <tr>
                <td colspan="8" style="text-align: center;">
                    <b>No Data Found!</b>
                </td>
            </tr>
            @else



            @foreach($empList as $data)

            <tr>
                <th scope="row">{{$loop->index + 1}}</th>
                <td>{{$data->ein}}</td>
                <td>{{$data->deceased_emp_name}}</td>
                <!-- <td>{{$data->dept_name}}</td> -->
                <td>{{$data->appl_number}}</td>
                <td>{{$data->applicant_name}}</td>
                <td>{{$data->applicant_mobile}}</td>
                <td>{{$data->remark}}</td>
                <td>{{$data->remark_details}}</td>
                <td>{{$data->status}}</td>
                <!-- <td>{{$data->status}}</td> -->
            </tr>
            @endforeach


            @endif

        </tbody>
    </table>
 </div>
  
  

</body>

</html>