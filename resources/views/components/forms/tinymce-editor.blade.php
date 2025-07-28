<style>
    div.justified {
        display: flex;
        justify-content: center;
    }
</style>
<?php

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
 <form method="post">
 <!-- <div class="justified">
 <textarea id="myTextarea" rows="40" cols="170" ALIGN=RIGHT>{{$getSoftwareName}}</textarea>

 </div> -->

 </form>
