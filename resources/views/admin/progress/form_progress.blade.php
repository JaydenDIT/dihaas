
@php
$id = session('ein');
@endphp

<?php
global $formStatArray;
global $formStatus;

?>



@if(count((array)$formStatArray) == 3)
<div class="row justify-content-center">
    <!-- ...................1..................... -->
    @if($form_no == 1)
    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($id)) }}" class="btn btn-primary width_height_border_padding" data-toggle="tooltip" data-placement="top" title="Deceased & Applicants Details" role="button" id="progressbtn1">1</a>
    <div class="col-sm-2 background_blue"></div>
    <br>
    @else
    @if($formStatArray[0]['form-1'] == 1)
    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($id)) }}" class="btn btn-success width_height_border_padding2" data-toggle="tooltip" data-placement="top" title="Deceased & Applicants Details" role="button"  id="progressbtn1">1</a>
    <div class="col-sm-2 background_green" ></div>
    @else
    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($id)) }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Deceased & Applicants Details" role="button"  id="progressbtn1">1</a>
    <div class="col-sm-2 background_blue" ></div>
    @endif
    @endif
    <!-- ...................2............... -->
    @if($form_no == 2)
    <a href="{{ route('view-family-details') }}" class="btn btn-primary width_color" data-toggle="tooltip" data-placement="top" title="Family Details" role="button"  id="progressbtn2">2</a>
    <div class="col-sm-2 background_blue"></div>
    @else
    @if($formStatArray[1]['form-2'] == 1)
    <a href="{{ route('view-family-details') }}" class="btn btn-success width_height_border_padding2" data-toggle="tooltip" data-placement="top" title="Family Details" role="button"  id="progressbtn2">2</a>
    <div class="col-sm-2 background_green" ></div>
    @else
    <a href="{{ route('view-family-details') }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Family Details" role="button"  id="progressbtn2">2</a>
    <div class="col-sm-2 background_blue"></div>
    @endif
    @endif
    <!-- ....................3.................. -->
    @if($form_no == 3)
    <a href="{{ route('uploaded-applicant-files') }}" class="btn btn-primary width_color" data-toggle="tooltip" data-placement="top" title="Uplaod Files" role="button"  id="progressbtn3">3</a>
    <div class="col-sm-2 background_blue"></div>
    @else
    @if($formStatArray[2]['form-3'] == 1)
    <a href="{{ route('uploaded-applicant-files') }}" class="btn btn-success width_height_border_padding2" data-toggle="tooltip" data-placement="top" title="Uplaod Files" role="button"  id="progressbtn3">3</a>
    <div class="col-sm-2 background_green" ></div>
    @else
    <a href="{{ route('uploaded-applicant-files') }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Uplaod Files" role="button" id="progressbtn3">3</a>
    <div class="col-sm-2 background_blue"></div>
    @endif
    @endif
  
    @if($form_no == 4)
    <a href="{{ route('other_form_details') }}" class="btn btn-primary width_color" data-toggle="tooltip" data-placement="top" title="Others" role="button" id="progressbtn9">4</a>
    @else
    @if($formStatus == 1)
    <a href="{{ route('other_form_details') }}" class="btn btn-success width_height_border_padding2" data-toggle="tooltip" data-placement="top" title="Others" role="button" id="progressbtn9">4</a>
    @else
    <a href="{{ route('other_form_details') }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Others" role="button"  id="progressbtn9">4</a>
    @endif
    @endif
</div>
@else
<div class="row justify-content-center">
    <!-- ...................1..................... -->
    @if($form_no == 1)
    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($id)) }}" class="btn btn-primary width_height_border_padding" data-toggle="tooltip" data-placement="top" title="Deceased & Applicants Details" role="button"  id="progressbtn1">1</a>
    <div class="col-sm-2 background_blue" ></div>
    <br>
    @else
    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($id)) }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Deceased & Applicants Details" role="button"  id="progressbtn1">1</a>
    <div class="col-sm-2 background_blue" ></div>
    @endif
    <!-- ...................2............... -->
    @if($form_no == 2)
    <a href="{{ route('view-family-details') }}" class="btn btn-primary width_color" data-toggle="tooltip" data-placement="top" title="Family Details" role="button" id="progressbtn2">2</a>
    <div class="col-sm-2 background_blue" ></div>
    @else
    <a href="{{ route('view-family-details') }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Family Details" role="button"  id="progressbtn2">2</a>
    <div class="col-sm-2 background_blue" ></div>
    @endif
    <!-- ....................3.................. -->
    @if($form_no == 3)
    <a href="{{ route('uploaded-applicant-files') }}" class="btn btn-primary width_color" data-toggle="tooltip" data-placement="top" title="Upload Files" role="button"  id="progressbtn3">3</a>
    <div class="col-sm-2 background_blue"></div>
    @else
    <a href="{{ route('uploaded-applicant-files') }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Upload Files" role="button"  id="progressbtn3">3</a>
    <div class="col-sm-2 background_blue"></div>
    @endif
   
    @if($form_no == 4)
    <a href="{{ route('other_form_details') }}" class="btn btn-primary width_color" data-toggle="tooltip" data-placement="top" title="Others" role="button"  id="progressbtn9">4</a>
    @else
    @if($formStatus == 1)
    <a href="{{ route('other_form_details') }}" class="btn btn-success width_height_border_padding2" data-toggle="tooltip" data-placement="top" title="Others" role="button"  id="progressbtn9">4</a>
    @else
    <a href="{{ route('other_form_details') }}" class="btn btn-primary width_color2" data-toggle="tooltip" data-placement="top" title="Others" role="button" id="progressbtn9">4</a>
    @endif
    @endif
</div>
@endif