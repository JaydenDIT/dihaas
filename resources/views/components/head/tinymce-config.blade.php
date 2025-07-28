<!DOCTYPE html>
<html>

<head>
  <style>
    div.justified {
      display: flex;
      justify-content: center;
    }

    #myDIV {

      text-align: right;
    }
  </style>


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
  $getDate = Carbon::now()->format('Y-m-d');

  $ein = session()->get('ein');
  $selectedGrades = session()->get('selectedGrades');
  //FOR SINGLE APPLICANT THIS IS OK

  $deptListArray = DepartmentModel::orderBy('dept_name')->get()->unique('dept_name');
  $Remarks = RemarksModel::get()->toArray();
  $empListArray = ProformaModel::get()->where('status', 3)->where('ein', $ein)->toArray();
  $empList = ProformaModel::where('ein', $ein)->where('status', 3)->paginate(10);
  $empDept = ProformaModel::get()->where('status', 3)->where('ein', $ein)->first();
  $designame = DesignationModel::where('id', $empDept->applicant_desig_id)->first();

  $uoGeneration =  UOGenerationModel::get()->where('ein', $ein)->first();

  ?>
  <script type="text/javascript" src='https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js' referrerpolicy="origin">
  </script>
  <script type="text/javascript">
    tinymce.init({
      selector: '#myTextarea',
      width: 1000,
      height: 600,
      plugins: [
        'save',
        'advlist autolink link image lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
        'table emoticons template paste help'

      ],
      toolbar: 'save' +
        'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
        'forecolor backcolor emoticons | help',

      menu: {
        //favs: {title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons'}
      },
      menubar: 'file edit view insert format tools table help',
      content_css: 'css/content.css'

    });
  </script>
</head>

<body>
  <div class="justified">
    <textarea id="myTextarea">
  <p>
  <center> {{$getSoftwareName}} </center>
  <center> {{$getDeptName}} </center>
  <center>{{$getGovtName}}</center>
  </p>
<p>
  </p>
  <p>
  @foreach($empList as $data)
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DP concurs to the Proposal of {{$data->dept_name}} Department for appointment of 
    {{$data->applicant_name}} s/o or d/o (L) {{$data->deceased_emp_name}}, ex-{{$data->desig_name}}
    to the post of {{$designame->desig_name}} under the die-in-harness scheme as he/she has fulfilled all 
    the eligibility criteria for appointment under the Scheme.
    Accordingly, the following U.O. number is allotted:-
<p>
{{$uoGeneration->uo_number}}
Dated: {{ $getDate}}
  </p>
    @endforeach
  </p>
 
                            
 
  <p>
<br><br><br><br>
<div id="myDIV">
Deputy Secretary (DP)
  </br> </br> </br> </br>
</p>

</div>

                       

                                      
                       
</p>


  </textarea>

  </div>
</body>

</html>
<p>
<div class="justified">
<a href="{{ route('selectDeptByDPApprove') }}" class="btn btn-success btn-sm" role="button" aria-disabled="true">Close</a>
</div>
</p>