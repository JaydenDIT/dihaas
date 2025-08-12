<!DOCTYPE html>
<html>

<head>
<!-- <link href="css/pdf.css" rel="stylesheet"> -->

    <title></title>
   

<!-- <link href="{{ asset('assets/css/pdftable.css') }}" rel="stylesheet" > -->

<style>
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
table,
th,
td {
    border: 1px solid black;
    border-collapse: collapse;
}

.centertable{

   width:700px;
    
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
    {{-- <div  style="text-align: center;font-size:30px">{{$getProjectShortForm}}</div> --}}

    <div style="text-align: center;font-size:30px">Department: {{$getDeptName}}</div>

    <div style="text-align: center;font-size:25px">Inter-Seniority List</div>

    <div style="text-align: right;font-size:15px">Dated: {{ $getDate}}</div>
    <br>

    <table class="table table-bordered table-condensed table-striped centertable" id="table">

        <thead class="thead-dark">
            <tr>
                <th  scope="col">Seniority Serial</th>
                <th  scope="col">EIN</th>
                <th scope="col">Deceased Name</th>
                <th>DOE</th>
                <th>Expired on Duty / Reason</th>   
                <th>Department</th>     
                <th>Applicant Name</th>            
              
            </tr>
        </thead>
        <tbody>


            @if(empty($empListArray))
            <tr>
                <td colspan="6" style="text-align: center;">
                    <b>No Data Found!</b>
                </td>
            </tr>
            @else



            @foreach($empList as $key => $data)


            <tr>
           <th scope="row">{{$data->slNo}}</th>
                <td >{{$data->ein}}</td>
                <td >{{$data->deceased_emp_name}}</td>
                <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}
                </td>
                <td>{{$data->expire_on_duty}} / {{$data->deceased_causeofdeath}}</td>
                <td>{{$data->dept_name}}</td>
                <td >{{$data->applicant_name}}</td>   
                
            </tr>
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
  


</body>

</html>