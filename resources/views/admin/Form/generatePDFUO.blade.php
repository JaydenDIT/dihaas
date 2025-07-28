<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

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
    <?php

    use App\Models\PortalModel;
    use App\Models\UoNomenclatureModel;
    use App\Models\UOGenerationModel;
    use Carbon\Carbon;
//  save
//  ,uo_number,generted_by,generated_on

    $getPortalName = PortalModel::where('id', 1)->first();
    $getSoftwareName = $getPortalName->software_name;
    $getProjectShortForm = $getPortalName->short_form_name;
    $getDeptName = $getPortalName->department_name;
    $getGovtName = $getPortalName->govt_name;

    $getUOFormat = UoNomenclatureModel::get()->first();
    $getDate = Carbon::now()->format('d-m-Y');

    ?>

<header>
    {{$getProjectShortForm}}
</header>
<footer>
    Copyright © <?php echo date("Y");?> - DP & AR, Government of Manipur
</footer>

    <div class="container">
        <div>
            <center>
                <h3>{{$getSoftwareName}}</h3>
                <h3>{{$getDeptName}}</h3>
                <h3>{{$getGovtName}}</h3>

            </center>
        </div>
        <div>
          <ol type="i">
          <li>AD's File No.: {{$adfilenumber}}</li> 
           <li>DP's File No.: {{$dpfilenumber}}</li>
</ol>

        </div>
        <div>


            <p>
                DP concurs to the Proposal of {{$data->dept_name}} Department for appointment of
                {{$data->applicant_name}} {{$relationship}} of (L) {{$data->deceased_emp_name}}, ex-{{$data->desig_name}}
                to the post of {{$post}} in the Department of {{$department}} as Group {{$grade}} under the 
                die-in-harness scheme as he/she has fulfilled all the eligibility criteria for appointment 
                under the Scheme.
                Accordingly, the following U.O. number is allotted:-
            </p>

            <p>
                {{$getUOFormat->uo_format}} {{$getUOFormat->uo_file_no}}/{{$getUOFormat->year}}/{{$getUOFormat->suffix}}
                Dated: {{ $getDate}}
            </p>
        </div>
<br> <p>
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