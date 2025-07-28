<?php

use App\Models\DepartmentModel;
use App\Models\DesignationModel;
use App\Models\PortalModel;
use App\Models\ProformaModel;
use App\Models\RemarksModel;
use App\Models\UOGenerationModel;
use App\Models\UoNomenclatureModel;
use Carbon\Carbon;



$getPortalName = PortalModel::where('id', 1)->first();
//Portal name short form    
$getProjectShortForm = $getPortalName->short_form_name;
//Application long name
$getSoftwareName = $getPortalName->software_name;
$getDeptName = $getPortalName->department_name;
$getGovtName = $getPortalName->govt_name;
$getDeveloper = $getPortalName->developed_by;
$getCopyright = $getPortalName->copyright;

$getUOFormat = UoNomenclatureModel::get()->first();
$getDate = Carbon::now()->format('d-m-Y');

$ein = session()->get('ein');

$selectedGrades = session()->get('selectedGrades');
$countTotal = count($selectedGrades);

//FOR SINGLE APPLICANT THIS IS OK

$deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
$Remarks = RemarksModel::get()->toArray();

$empDept = ProformaModel::get()->where('status', 6)->where('ein', $ein)->first();


?>
<!DOCTYPE html>
<html>


<head>
<link href="css/app.css" rel="stylesheet">
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
    
 
        </style>
</head>

<body>
    <header>
        {{$getProjectShortForm}}
    </header>
    <footer>
        Copyright © <?php echo date("Y");?> - DP & AR, Government of Manipur
    </footer>

  
    <div class="justified">

        <p>
            <center> {{$getSoftwareName}} </center>
            <center> {{$getDeptName}} </center>
            <center>{{$getGovtName}}</center>
        </p>
        <p>
        </p>
        <div>
          <ol type="i">
          <li>AD's File No.: {{$adfilenumber}}</li> 
           <li>DP's File No.: {{$dpfilenumber}}</li>
</ol>

        </div>
        <p>

        DP concurs to the proposal of {{$data->dept_name}} Department for appointment of {{$countTotal}} applicants under die-in-harness scheme
    as they fulfilled all the eligibility criteria for appoinment under the 
    Scheme.
    <p>Details of the {{$countTotal}} applicants and their U.O number are shown as below:-  </p>


        <table class="table" id="table">
            <tr>
                <th>Sl.No</th>
                <th>Name of Applicant</th>
                <th>Department</th>
                <th>Post Given</th>
                <th>Group</th>
                <th>U.O Number & Date</th>
            </tr>

            Dated: {{ $getDate}}

            @foreach($selectedGrades as $grade)
            <?php
            $selectedGrades = session()->get('selectedGrades');
            // $empListArray = ProformaModel::get()->where('status', 3)->where('ein', $grade)->toArray();
            $empList = ProformaModel::where('ein', $grade)->where('status', 6)->paginate(10);

            $empDept = ProformaModel::get()->where('status', 6)->where('ein', $grade)->first();

            // foreach ($empList as $data) {
            $designame = DesignationModel::where('id', $empDept->applicant_desig_id)->first();



            $uoGeneration =  UOGenerationModel::get()->where('ein', $grade)->first();


           // $signingAuthority = UOGenerationModel::get()->where ('ein',$grade)->first();

            ?>

            <tr>
                <td>{{$loop->index + 1}}</td>
                @foreach($empList as $data)

                <td> {{$data->applicant_name}}</td>
                <td>{{$uoGeneration->department}}</td>
                <td>{{$uoGeneration->post}}</td>
                <td>{{$uoGeneration->grade}}</td>
                <td>{{$uoGeneration->uo_number}} {{ date('Y-m-d', strtotime($uoGeneration->generated_on)) }}
            </tr>
            @endforeach

            @endforeach

        </table>

        <p>
            <br><br><br><br>
        <div style="text-align: right">
        {{$dpsign1}}


            </br> </br> </br> </br>
            </p>

        </div>
        <div style="text-align: left">
        {{$adsign2}}


            </br> </br> </br> </br>
            </p>

        </div>



        </p>




    </div>
</body>

</html>