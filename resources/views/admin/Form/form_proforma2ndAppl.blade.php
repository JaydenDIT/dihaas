<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="{{ asset('assets/css/proforma.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/form_proforma.css') }}" rel="stylesheet">
    
    <!-- above link is for notification -->
    
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DIHAS</title>


</head>

<body onload="firstfocus();">

    <!-- @include('sweetalert::alert') -->
    <!-- above code is for showing notification -->



    @extends('layouts.app')

    @section('content')
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
    @php $form_no = 1; @endphp
    <?php $selected = session()->get('deptId') ?>
    <div class="container">
    <br>
        <!-- @include('admin.form_menu_buttons') -->
        @include('admin.progress.form_progress_2nd')

        <div class="row justify-content-center">
            <!-- @include('admin.form_menu_buttons') -->

            <br>
            <div class="col-md-12 margintop">
                <!-- Descriptive Role & Personel Details-->
                <div class="card displayshow" id="desc_role_section">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 center">
                          

                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="card-body">

                            @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                            @endif
                            @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                            @endif
                            @if(session()->has('duplicate'))
                            <div class="alert alert-danger">
                                {{ session('duplicate') }}
                            </div>
                            @endif
                            @if(session()->has('eligible'))
                            <div class="alert alert-danger">
                                {{ session('eligible') }}
                            </div>
                            @endif
                            @if(session()->has('errormessage'))
                            <div class="alert alert-danger">
                                {{ session('errormessage') }}
                            </div>
                            @endif
                            @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <!-- $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        }); -->


                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <br>
                            <div id="form_body" class="displayshow">
                                <form name="descriptiveRoleForm" id="descriptiveRoleForm" method="POST" enctype="multipart/form-data">
                                    <!-- onSubmit="return formValidation();">class="was-validated"> -->
                                    @csrf
                                    @method('PUT')

                                    <div id="form_header" class="textcenter_fontsize">
                                        <b>Proforma Data Entry </b>
                                    </div>

                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="ein"><b>EIN Number of Deceased Government Servant </b></label>
                                            <label class="rq displaynone" id="einerror"><b>* Please enter EIN to proceed further</b></label>
                                        </div>
                                        <div class=" col-sm-3">
                                            <input type="text" class="form-control @error('ein') is-invalid @enderror" onblur="fieldValidate('ein');" name="ein" id="ein" value="{{ $get2nd_appl->ein }}" placeholder="" readonly>
                                            @error('einError')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2">
                                            <label for="name"><b>Name of the Deceased Government Servant</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" onblur="fieldValidate('deceased_emp_name')" name="deceased_emp_name" id="deceased_emp_name" value="{{ $get2nd_appl->deceased_emp_name }}" readonly>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div><br>


                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="dept_name"><b>Department</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control @error('dept_name') is-invalid @enderror" name="dept_name" id="dept_name" value="{{ $get2nd_appl->dept_name }}" placeholder="" readonly>

                                            @error('dept_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2">
                                            <label for="ministry"><b>Administrative Department</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control @error('ministry') is-invalid @enderror" name="ministry" id="ministry" value="{{ $get2nd_appl->ministry }}" placeholder="" readonly>

                                            @error('ministry')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-1">

                                        </div>

                                    </div>

                                    </br>
                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="d_doa"><b>Date of Appoinment</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control  @error('deceased_doa') is-invalid @enderror" name="deceased_doa" id="deceased_doa" value="{{ $get2nd_appl->deceased_doa }}" placeholder="" readonly>
                                            @error('d_doa')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2">
                                            <label for="desig_name"><b>Post Held</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control  @error('desig_name') is-invalid @enderror" name="desig_name" id="desig_name" value="{{ $get2nd_appl->desig_name }}" placeholder="" readonly>

                                            @error('desig_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                    </div></br>
                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="deceased_dob"><b>Deceased DOB</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control  @error('deceased_dob') is-invalid @enderror" placeholder="" id="deceased_dob" name="deceased_dob" value="{{ $get2nd_appl->deceased_dob }}" readonly>

                                            @error('deceased_dob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2">
                                            <label for="grade_name"><b>Grade/Group</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control  @error('grade_name') is-invalid @enderror" name="grade_name" id="grade_name" value="{{ $get2nd_appl->grade_name }}" placeholder="" readonly>
                                            @error('grade_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                    </div>


                                    <!-- <hr> -->
                                    <!--  -->
                                    <br>
                                    @csrf
                                    <!-- validation -->
                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                    <hr class="new">
                                    <!-- Deceased Details -->
                                    <div class="row fontweight_color" id="dividerDiv">
                                        Information of the Deceased Government Servant:
                                    </div>
                                    <br>

                                    <div class="row">
                                        <div class="col-sm-10">
                                            <span class="font_color">Expired on Duty: Government servants who died while
                                                performing
                                                official
                                                duties viz. election/census /survey /research / official tour/ field
                                                inspection etc. or
                                                who died in insurgency related violence while performing official
                                                duties.</span>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 textleft">
                                            <label for="expire"><b>Expired on duty</b></label>

                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="displayflex">
                                                <div class="marginright">
                                                    <label class="radio-inline" for="yes"><b>
                                                            <input class="readonly" type="radio" class="  @error('expire_on_duty') is-invalid @enderror" id="Yes" name="expire_on_duty" value="yes" {{$get2nd_appl->expire_on_duty == "yes" ? "checked" : ''}}>Yes</b></label><br>
                                                </div>
                                                <div>
                                                    <label class="radio-inline marginL" for="no"><b>
                                                            <input class="readonly" type="radio" class=" @error('expire_on_duty') is-invalid @enderror" id="No" name="expire_on_duty" value="no" {{$get2nd_appl->expire_on_duty == "no" ? "checked" : ''}}>No</b></label><br>
                                                </div>

                                            </div>
                                            @error('expire_on_duty')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 textleft">
                                            <label for="deceased_doe"><b>Date of Expiry</b></label>

                                        </div>
                                        <div class="col-sm-3">

                                            <input type="date" class="form-control  @error('deceased_doe') 
                                            is-invalid @enderror" pattern="\d{4}-\d{2}-\d{2}" placeholder="" id="deceased_doe" name="deceased_doe" value="{{ $get2nd_appl->deceased_doe }}" readonly>

                                            <p class="colourred" id="deceased_doeError"></p>


                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 textleft">

                                            <label for="deceased_causeofdeath"><b>Cause of Death</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control texttransform @error('deceased_causeofdeath') is-invalid @enderror" placeholder="" id="deceased_causeofdeath" name="deceased_causeofdeath" value="{{ $get2nd_appl->deceased_causeofdeath }}" readonly>
                                            <p class="colourred" id="deceased_causeofdeathError"></p>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                    </div>
                                    <hr class="new">

                                    <!-- Applicant Details -->
                                    <div class="row fontweight_color" id="dividerDiv">
                                        Applicant/Claimaint Details :
                                    </div>
                                    <br>
                                    <br>


                                    <div class="row">
                                        <div class="col-sm-2 textleft">

                                            <label for="name"><b>Applicant Name</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control texttransform" placeholder="" id="applicant_name" name="applicant_name" value="{{ $get2nd_appl->second_appl_name }}" readonly>
                                            <p class="colourred" id="applicant_nameError"></p>


                                        </div>

                                        <div class="col-sm-1">
                                        </div>

                                        @if($getUser->role_id == 77)
                                        <div class="col-sm-2 textleft">

                                            <label for="date"><b>Date of Application Submitted</b></label>

                                        </div>
                                        <div class="col-sm-3">

                                            <input type="text" class="form-control" id="appl_date" name="appl_date" value="{{ $get2nd_appl->appl_date }}" readonly>

                                            <p id="appl_dateError" class="colourred"></p>
                                            <p id="error_message" class="colourred"></p>

                                        </div>

                                        @else
                                        <div class="col-sm-2 textleft">

                                            <label for="date"><b>Date of Application Submitted</b></label>



                                        </div>
                                        <div class="col-sm-3">
                                            <!-- <div class="input-group date" id="datepicker"> -->
                                            <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" placeholder="" id="appl_date" name="appl_date" value="{{ $get2nd_appl->appl_date }}" readonly>

                                            <p class="colourred" id="appl_dateError"></p>
                                            <p class="colourred" id="error_message"></p>
                                            <!-- </div> -->

                                        </div>

                                        @endif
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 textleft">
                                            <label for="applicant_dob"><b>Date of birth</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <!-- <div class="input-group date" id="datepicker"> -->
                                            <input type="date" class="form-control" pattern="\d{4}-\d{2}-\d{2}" placeholder="" id="applicant_dob" name="applicant_dob" value="{{ old('applicant_dob') }}" required>


                                            <p id="dob-error" class="colourred"></p>
                                            <p id="applicant_dobError" class="colourred"></p>
                                            <!-- </div> -->
                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2 textleft">

                                            <label for="relationship"><b>Relationship with Deceased/Retired</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select" aria-label="Default select example" id="relationship" name="relationship">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Relationship as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['relationship'] ? 'selected' : ''}} required> {{$option['relationship']}}</option>
                                                @endforeach
                                            </select>
                                            <p id="relationshipError" class="colourred"></p>
                                        </div>


                                    </div>

                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 textleft">

                                            <label for="applicant_mobile"><b>Mobile No.</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input autocomplete="off" type="text" class="form-control" placeholder="" id="applicant_mobile" name="applicant_mobile" maxlength="10" pattern="[1-9]{1}[0-9]{9}" value="{{ old('applicant_mobile') }}">
                                            <p id="applicant_mobileError" class="colourred"></p>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2 textleft">

                                            <label for="applicant_edu_id"><b>Educational Qualification</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select" @error('applicant_edu_id') is-invalid @enderror" aria-label="Default select example" id="applicant_edu_id" name="applicant_edu_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($educations as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == old($option['edu_name']) ? 'selected' : ''}} required> {{$option['edu_name']}}</option>
                                                @endforeach
                                            </select>
                                            <p id="applicant_edu_idError" class="colourred"></p>


                                            
                                            <div id="otherEducation" style="display: none;">

                                                <input type="text" class="form-control" id="other_qualification"
                                                    name="other_qualification">
                                            </div>

                                        </div>

                                    </div>

                                    <br>


                                    <!-- Mobile & Email -->
                                    <div class="row">
                                        <div class="col-sm-2 textleft">
                                            <label for="physically_handicapped"><b>Physically Handicapped</b></label>

                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="displayflex">
                                                <div class="marginright">
                                                    <label class="radio-inline" for="yes"><b>
                                                            <input type="radio" class="  @error('physically_handicapped') is-invalid @enderror" id="Yes" name="physically_handicapped" value="yes" {{ old('Yes') == 'yes' ? 'checked' : '' }}>Yes</b></label><br>
                                                </div>
                                                <div>
                                                    <label class="radio-inline marginL" for="no"><b>
                                                            <input type="radio" class=" @error('physically_handicapped') is-invalid @enderror" id="No" name="physically_handicapped" value="no" {{ old('No') == 'no' ? 'checked' : '' }} checked>No</b></label><br>
                                                </div>

                                            </div>
                                            @error('physically_handicapped')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2 textleft">

                                            <label for="applicant_email_id"><b>Email Id</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <input type="email" class="@error('applicant_email_id') is-invalid @enderror form-control" placeholder="" id="applicant_email_id" name="applicant_email_id" value="{{ old('applicant_email_id') }}" required>
                                            <p id="applicant_email_id-validation-message" class="colourred"></p>

                                            <p id="applicant_email_idError" class="colourred"></p>

                                        </div>


                                    </div>
                                    <br>
                                    <div class="row">
                                        @if($getUser->role_id == 77)
                                        <div class="col-sm-2 textleft">
                                            <label for="caste_id"><b>Caste</b></label>

                                        </div>
                                        <div class="col-sm-3 ">

                                            <select class="form-select  @error('caste_id') is-invalid @enderror" aria-label="Default select example" id="caste_id" name="caste_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Caste as $option)
                                                <option value="{{$option['caste_id']}}" {{$option['caste_id'] == $option['caste_name'] ? 'selected' : ''}} required> {{$option['caste_name']}}</option>
                                                @endforeach
                                            </select>


                                            <p id="caste_idError" class="colourred"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        @else
                                        <div class="col-sm-2 textleft">
                                            <label for="caste_id"><b>Caste</b></label>

                                        </div>
                                        <div class="col-sm-3 ">

                                            <select class="form-select  @error('caste_id') is-invalid @enderror" aria-label="Default select example" id="caste_id" name="caste_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Caste as $option)
                                                <option value="{{$option['caste_id']}}" {{$option['caste_id'] == $option['caste_name'] ? 'selected' : ''}} required> {{$option['caste_name']}}</option>
                                                @endforeach
                                            </select>


                                            <p id="caste_idError" class="colourred"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        @endif
                                        <div class="col-sm-2 textleft">

                                            <label for="sex"><b>Sex</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('sex') is-invalid @enderror" aria-label="Default select example" id="sex" name="sex">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Gender as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['sex'] ? 'selected' : ''}} required> {{$option['sex']}}</option>
                                                @endforeach
                                            </select>
                                            <p id="sexError" class="colourred"></p>
                                        </div>


                                    </div>
                                    <hr class="new">

                                    <!-- Address Details -->
                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-6 fontweight_color_textalign">Address Details :</div>


                                        <!-- Checkbox is needed here to make present address same as Permanant Address -->


                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 textalign_fontsize_fontweight_color"><u>
                                                <font class="fontweight_color">Present Address</font>
                                            </u></div>
                                        <div class="col-sm-3 textleft">
                                            <hr>
                                        </div>

                                        <div class="col-sm-4 textalign_fontsize_fontweight_color">
                                            <input class="form-check-input" type="checkbox" id="presentAddressSamecheckbox" name="presentAddressSamecheckbox" value="presentAddressSamecheckbox">
                                            <label class="form-check-label fontweight_color">
                                                &nbsp;&nbsp;Same as Present address
                                            </label>

                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>
                                                <font class="fontweight_color">Permanent Address</font>
                                            </u>
                                        </div>
                                        <div class="col-sm-2 textleft">
                                            <hr>
                                        </div>
                                    </div>
                                    <!-- Present Address -->
                                    <div class="row">
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_lcality"><b>Locality</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <textarea class="form-control texttransform @error('emp_addr_lcality') is-invalid @enderror" id="emp_addr_lcality" rows="2" placeholder="" name="emp_addr_lcality" value="{{ old('emp_addr_lcality') }}"></textarea>
                                            <p id="emp_addr_lcalityError" class="colourred"></p>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <!-- Permanant Address -->

                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_lcality_ret"><b>Locality</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <textarea class="form-control texttransform @error('emp_addr_lcality_ret') is-invalid @enderror" id="emp_addr_lcality_ret" rows="2" placeholder="Present Address" name="emp_addr_lcality_ret" value="{{ old('emp_addr_lcality_ret') }}"></textarea>
                                            <p id="emp_addr_lcality_retError" class="colourred"></p>
                                        </div>

                                    </div>

                                    <br>
                                    <div class="row">

                                        <div class="col-sm-2 textleft">

                                            <label for="emp_state"><b>State</b></label>


                                        </div>
                                        <!-- district -->
                                        <div class="col-sm-3">
                                            <select class="form-select funSelectChange" aria-label="Default select example" id="emp_state" name="emp_state" data-change="emp_addr_district">
                                                <option value="" disabled selected>Choose State....</option>
                                                @foreach($stateDetails as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>


                                            <p id="emp_stateError" class="colourred"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2 textleft">

                                            <label for="emp_state_ret"><b>State</b></label>


                                        </div>
                                        <!-- district -->
                                        <div class="col-sm-3">
                                            <select class="form-select funSelectChange" aria-label="Default select example" id="emp_state_ret" name="emp_state_ret" data-change="emp_addr_district_ret">
                                                <option value="" disabled selected>Choose State....</option>
                                                @foreach($stateDetails as $row)
                                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" role="alert">
                                                Please Select State.
                                            </div>


                                            <p id="emp_state_retError" class="colourred"></p>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">


                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_district"><b>District</b></label>


                                        </div>
                                        <!-- district -->
                                        <div class="col-sm-3">
                                            <select class="form-select funSelectChange" aria-label="Default select example" id="emp_addr_district" name="emp_addr_district" data-change="emp_addr_subdiv">
                                                <option value="" disabled selected>Choose District....</option>
                                            </select>
                                            <div class="invalid-feedback" role="alert">
                                                Please Select District.
                                            </div>


                                            <p id="emp_addr_districtError" class="colourred"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_district_ret"><b>District</b></label>

                                        </div>
                                        <!-- district -->
                                        <div class="col-sm-3">
                                            <select class="form-select funSelectChange" aria-label="Default select example" id="emp_addr_district_ret" name="emp_addr_district_ret" data-change="emp_addr_subdiv_ret">
                                                <option value="" disabled selected>Choose District....</option>
                                            </select>
                                            <div class="invalid-feedback" role="alert">
                                                Please Select District.
                                            </div>


                                            <p id="emp_addr_district_retError" class="colourred"></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_subdiv"><b>Sub Division</b></label>

                                        </div>


                                        <div class="col-sm-3">
                                            <select class="form-select" aria-label="Default select example" id="emp_addr_subdiv" name="emp_addr_subdiv">
                                                <option value="" disabled selected>Choose Sub-Division....</option>
                                            </select>
                                            <div class="invalid-feedback" role="alert">
                                                Please Select Sub-Division.
                                            </div>


                                            <p id="emp_addr_subdivError" class="colourred"></p>
                                            <!--    <input placeholder="Sub Division" class="form-control" id="emp_addr_subdiv" name="emp_addr_subdiv" value="">-->
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_subdiv_ret"><b>Sub Division</b></label>

                                        </div>


                                        <div class="col-sm-3">
                                            <select class="form-select" aria-label="Default select example" id="emp_addr_subdiv_ret" name="emp_addr_subdiv_ret">
                                                <option value="" disabled selected>Choose Sub-Division....</option>
                                            </select>
                                            <div class="invalid-feedback" role="alert">
                                                Please Select Sub-Division.
                                            </div>


                                            <p id="emp_addr_subdiv_retError" class="colourred"></p>
                                            <!--    <input placeholder="Sub Division" class="form-control" id="emp_addr_subdiv" name="emp_addr_subdiv" value="">-->
                                        </div>
                                    </div>

                                    <br>



                                    <div class="row">
                                        <!-- Pincode -->
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_pincode"><b>Pin Code</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" placeholder="Pincode." class="form-control @error('emp_pincode') is-invalid @enderror" id="emp_pincode" name="emp_pincode" value="{{ old('emp_pincode') }}" maxlength="6">
                                            <p id="emp_pincodeError" class="colourred"></p>
                                            <p id="error-message" class="colourred"></p>
                                        </div>

                                        <div class="col-sm-1">

                                        </div>
                                        <!-- Pincode -->
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_pincode_ret"><b>Pin Code</b></label>

                                        </div>

                                        <div class="col-sm-3">
                                            <input type="text" placeholder="Pincode." class="form-control @error('emp_pincode_ret') is-invalid @enderror" id="emp_pincode_ret" name="emp_pincode_ret" value="{{ old('emp_pincode_ret') }}" maxlength="6">
                                            <p id="emp_pincode_retError" class="colourred"></p>
                                            <p id="error-message2" class="colourred"></p>
                                        </div>
                                    </div>


                                    <hr class="new">

                                    <!-- Post Proposed details -->
                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-12 fontweight_color">Post Proposed for Appoinment</div>
                                        <div class="col-sm-10">

                                        </div>
                                    </div>
                                    <br>

                                    <br>
                                    <!-- Applied Post and Grade  -->
                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-12 fontweight_color">Post Proposed for Appoinment in parent
                                            department</div>
                                        <div class="col-sm-10">

                                        </div>
                                    </div>
                                    <br>
                                    <!-- Applied Post and Grade  -->
                                    <div class="row">

                                        <div class="col-sm-2 textleft">

                                            <label for="post_applied"><b>First Preference</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select
                                                class="form-select  @error('applicant_desig_id') is-invalid @enderror "
                                                aria-label="Default select example" id="applicant_desig_id"
                                                name="applicant_desig_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($post as $option)
                                                <option value="{{$option['dsg_serial_no']}}" required>
                                                    {{$option['dsg_desc']}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div id="applicant_desig_idError" class="text-danger"></div>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">
                                            <label for="grade"><b>Grade</b></label>
                                        </div>
                                        <div class="col-sm-3">

                                            <input class="form-control" type="text" value="" id="applicant_grade"
                                                name="applicant_grade" readonly>
                                            <div id="applicant_gradeError" class="text-danger"></div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>

                                 
                                    <div class="row">

                                        <div class="col-sm-2 textleft">

                                            <label for="post_applied"><b>Second Preference</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('second_post_id') is-invalid @enderror "
                                                aria-label="Default select example" id="second_post_id"
                                                name="second_post_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($post as $option)
                                                <option value="{{$option['dsg_serial_no']}}" required>
                                                    {{$option['dsg_desc']}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div id="second_post_idError" class="text-danger"></div>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">
                                            <label for="grade"><b>Grade</b></label>
                                        </div>
                                        <div class="col-sm-3">

                                            <input class="form-control" type="text" value="" id="second_grade_id"
                                                name="second_grade_id" readonly>
                                            <div id="second_grade_idError" class="text-danger"></div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    <hr>

                                    <hr>

                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-12 fontweight_color">Post Proposed for Appoinment in Other
                                            Department</div>
                                        <div class="col-sm-10">

                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">

                                        <div class="col-sm-2 textleft">

                                            <label for="department"><b>Select Department</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select" aria-label="Default select example"
                                                id="dept_id_option" name="dept_id_option" >
                                                <option value="" selected>All Department</option>
                                                @foreach($deptListArray as $option)
                                                <option
                                                    value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}"
                                                    required {{($selected == $option['dept_id'])?'selected':''}}>
                                                    {{$option['dept_name']}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <div id="dept_id_optionError" class="text-danger"></div>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    <hr>

                                   
                                    <!-- Applied Post and Grade  -->
                                    <div class="row">

                                        <div class="col-sm-2 textleft">

                                            <label for="post_applied"><b>Prefer Post</b></label>


                                        </div>
                                     
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('third_post_id') is-invalid @enderror "
                                                aria-label="Default select example" id="third_post_id"
                                                name="third_post_id">
                                                <option value=""  selected disabled>Select Designation</option>
                                              
                                                <option
                                                    value="{{ $option['dsg_serial_no'] == null ? null : $option['dsg_serial_no'] }}"
                                                    required {{($selected == $option['dsg_serial_no'])?'selected':''}}>
                                                    {{$option['dsg_desc']}}
                                                    </option>
                                           

                                            </select>
                                            <div id="third_post_idError" class="text-danger"></div>

                                        </div>
                                      
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2 textleft">
                                            <label for="grade"><b>Grade</b></label>
                                        </div>
                                        <div class="col-sm-3">

                                            <input class="form-control" type="text" value="" id="third_grade_id"
                                                name="third_grade_id" readonly>
                                            <div id="third_grade_idError" class="text-danger"></div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>

                                

                                    <hr class="new">
                                    <div class="row textright">
                                        <div class="col-sm-12">


                                            @csrf
                                            @method('POST')
                                            <button id="cancelBtn" type="button" class="btn btn-danger  btn-sm">Discard</button>
                                            <button type="submit" class="btn btn-success btn-sm" id="desc_role_next_btn">Save As Draft</button>
                                            &nbsp;<a href="{{ route('enter-family-details2ndAppl') }}" id="close" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                                Next
                                            </a>


                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- row justify-content-center -->

    </div> <!--Container -->
    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Submission</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" id="confirmCancel" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> -->
                </div>
                <div class="modal-body">
                    Are you sure you want to save this as draft?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="confirmCancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{ asset('assets/js/select2.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
    <script>
        $('document').ready(()=>{
            $('#applicant_edu_id').select2();
        })
        
        
        $('document').ready(()=>{
            $('#applicant_desig_id').select2();
        })
    
        $('document').ready(()=>{
            $('#second_post_id').select2();
        })
    
        $('document').ready(()=>{
            $('#dept_id_option').select2();
        })
    
        $('document').ready(()=>{
            $('#third_post_id').select2();
        })
    
    </script>

    <script nonce="{{ csp_nonce() }}">
         $('#applicant_desig_id').change(function() {
            var id = $(this).find('option:selected').val();
            var array = <?php echo json_encode($post); ?>;
            var item = array.find(item => item.dsg_serial_no === id);
            $('#applicant_grade').val(item.group_code);

        })

        $('#second_post_id').change(function() {
        var id = $(this).find('option:selected').val();
        var array = <?php echo json_encode($post); ?>;
        var item = array.find(item => item.dsg_serial_no === id);
        $('#second_grade_id').val(item.group_code);

    })


    $('#third_post_id').change(function() {
        var id = $(this).find('option:selected').val();
       var item = $(this).find(item => item.dsg_serial_no === id);     
       //alert($('#third_post_id option[value="'+this.value+'"]').data('grade'));
       $('#third_grade_id').val($('#third_post_id option[value="'+this.value+'"]').data('grade'));

    })


    $('#dept_id_option').change(function() {
        
        //make blank      

        $("#third_post_id").empty();
        $('#third_post_id').append(new Option('Select Post', ''));
        $('#third_post_id option[value=""]').attr('disabled',true);

        $('#third_grade_id').val('');


        var id = $(this).find('option:selected').val();
        var data_dept_id = {
            'dept_id': $(this).find('option:selected').val(),
        };
        // console.log(data_dept_id);
        $.get('{{ route("retrieve_dept") }}', data_dept_id, function(id, textStatus, xhr) {
         
            //now load the result data in id third_post_id i.e. for designation  
          
            $.each(id, function(index, element) {
           
                //$('#third_post_id').append(new Option(element.dsg_desc, element.dsg_serial_no)); //only value and text
                //Below is for adding extra attribute
                $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr('data-grade', element.group_code).appendTo('#third_post_id');

            });

        });
      

    })




        var _token = "{{ csrf_token() }}";


        var formdistrictoption1_url = "{{ route('district1.getOption') }}";
        var formsubdivisionoption1_url = "{{ route('SubDivision1.getOption') }}";

        // Retrieving ein record from CMIS
        // function getEmployee() {
        //     var ein = document.getElementById('ein').value;
        //     document.descriptiveRoleForm.action = "enterProformaDetails";
        //     document.descriptiveRoleForm.submit();
        // }

        // Pincode
        document.getElementById("emp_pincode").addEventListener("input", function() {
            var input = this.value;
            var errorMessage = document.getElementById("error-message");
            var isValid = /^\d+$/.test(input) && parseInt(input) >= 795001 && parseInt(input) <= 795159;

            if (!isValid) {
                errorMessage.innerHTML = "Please enter a number between 795001 and 795159.";
            } else {
                errorMessage.innerHTML = "";
            }
        });
        document.getElementById("emp_pincode_ret").addEventListener("input", function() {
            var input = this.value;
            var errorMessage = document.getElementById("error-message2");
            var isValid = /^\d+$/.test(input) && parseInt(input) >= 795001 && parseInt(input) <= 795159;

            if (!isValid) {
                errorMessage.innerHTML = "Please enter a number between 795001 and 795159.";
            } else {
                errorMessage.innerHTML = "";
            }
        });


        $("#applicant_mobile").keydown(function(event) {
            k = event.which;
            if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 9) {
                if ($(this).val().length == 10) {
                    if (k == 8 || k == 9) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;

                    }
                }
            } else {
                event.preventDefault();
                return false;
            }

        });

        $("#emp_pincode").keydown(function(event) {
            k = event.which;
            if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 9) {
                if ($(this).val().length == 6) {
                    if (k == 8 || k == 9) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;

                    }
                }
            } else {
                event.preventDefault();
                return false;
            }

        });

        $("#emp_pincode_ret").keydown(function(event) {
            k = event.which;
            if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8 || k == 9) {
                if ($(this).val().length == 6) {
                    if (k == 8 || k == 9) {
                        return true;
                    } else {
                        event.preventDefault();
                        return false;

                    }
                }
            } else {
                event.preventDefault();
                return false;
            }

        });



        // After form loads focus will go to User id field.
        function firstfocus() {
            if (document.descriptiveRoleForm.ein.value == '')
                document.descriptiveRoleForm.ein.focus();
            return true;
        }


        //This function helps to show required field on runtime implies to all field now not in used

        function fieldValidate(field) {

            if (document.getElementById('' + field).value == "") {
                document.getElementById("desc_role_next_btn").disabled = true;
                document.getElementById(field + 'error').style.display = "inline";
                //  document.getElementById(field).focus();

            } else
                document.getElementById("desc_role_next_btn").disabled = false;

        }


        ////////////////////////FUNCTIONS BELOW///////////////////////////////////////////


        function validateDateWithinSixMonths(applDate, deceasedDoe, errorMessageElement) {
            const sixMonthsInMillis = 6 * 30 * 24 * 60 * 60 * 1000;

            const currentDate = new Date();
            const applDateObj = new Date(applDate);
            const deceasedDoeObj = new Date(deceasedDoe);

            const sixMonthsFromExpiry = new Date(deceasedDoeObj.getTime() + sixMonthsInMillis);

            if (applDateObj >= deceasedDoeObj && applDateObj <= sixMonthsFromExpiry) {
                // Valid date
                errorMessageElement.textContent = "";
                console.log("Date is within 6 months from expiry.");
                document.getElementById("desc_role_next_btn").disabled = false;
            } else {
                // Invalid date
                errorMessageElement.textContent = "Date must be within 6 months from expiry.";
                console.log("Date is not within 6 months from expiry.");
                //document.descriptiveRoleForm.appl_date.focus();
                document.getElementById("desc_role_next_btn").disabled = true;
            }
        }

        function validateAge() {
            const dobInputElement = document.getElementById('applicant_dob');
            const dobErrorElement = document.getElementById('dob-error');

            const inputValue = dobInputElement.value;

            if (inputValue === '') {
                dobErrorElement.textContent = 'Please fill up this field.';
                dobInputElement.classList.add('is-invalid');
                //document.descriptiveRoleForm.applicant_dob.focus();
                document.getElementById("desc_role_next_btn").disabled = true;
                return; // Exit the function if input is empty
            }

            const dateInputElement = new Date(inputValue);
            const currentDate = new Date();

            const ageDiffMilliseconds = currentDate - dateInputElement;
            const ageDiffYears = ageDiffMilliseconds / (1000 * 60 * 60 * 24 * 365);
            // 1000 * 60 * 60 * 24 * 365==miliseconds in one year

            if (ageDiffYears < 15) {
                dobErrorElement.textContent = 'Applicant must be at least 15 years old.';
                dobInputElement.classList.add('is-invalid');
                //document.descriptiveRoleForm.applicant_dob.focus();
                document.getElementById("desc_role_next_btn").disabled = true;
            } else if (ageDiffYears > 60) {
                dobErrorElement.textContent = 'Applicant cannot be older than 60 years.';
                dobInputElement.classList.add('is-invalid');
                //document.descriptiveRoleForm.applicant_dob.focus();
                document.getElementById("desc_role_next_btn").disabled = true;
            } else {
                dobErrorElement.textContent = '';
                dobInputElement.classList.remove('is-invalid');
                //document.descriptiveRoleForm.applicant_dob.focus();
                // document.getElementById("desc_role_next_btn").disabled = true;
            }
        }




        //prevent submit if not validate
        document.addEventListener('DOMContentLoaded', function() {
            // const einInput = document.getElementById('ein');

            const deceased_doeInput = document.getElementById('deceased_doe');
            const deceased_causeofdeathInput = document.getElementById('deceased_causeofdeath');
            const applicant_nameInput = document.getElementById('applicant_name');
            const appl_dateInput = document.getElementById('appl_date');
            const applicant_dobInput = document.getElementById('applicant_dob');
            const caste_idInput = document.getElementById('caste_id');
            const relationshipInput = document.getElementById('relationship');
            const applicant_mobileInput = document.getElementById('applicant_mobile');
            const applicant_edu_idInput = document.getElementById('applicant_edu_id');
            const applicant_email_idInput = document.getElementById('applicant_email_id');
            const sexInput = document.getElementById('sex');
            const emp_addr_lcalityInput = document.getElementById('emp_addr_lcality');
            const emp_addr_districtInput = document.getElementById('emp_addr_district');
            const emp_addr_subdivInput = document.getElementById('emp_addr_subdiv');
            const emp_stateInput = document.getElementById('emp_state');
            const emp_pincodeInput = document.getElementById('emp_pincode');
            const emp_addr_lcality_retInput = document.getElementById('emp_addr_lcality_ret');
            const emp_addr_district_retInput = document.getElementById('emp_addr_district_ret');
            const emp_addr_subdiv_retInput = document.getElementById('emp_addr_subdiv_ret');
            const emp_state_retInput = document.getElementById('emp_state_ret');
            const emp_pincode_retInput = document.getElementById('emp_pincode_ret');
            const applicant_desig_idInput = document.getElementById('applicant_desig_id');
            const applicant_gradeInput = document.getElementById('applicant_grade');
            // Add more field inputs here

            // const einError = document.getElementById('einError');

            const deceased_doeError = document.getElementById('deceased_doeError');
            const deceased_causeofdeathError = document.getElementById('deceased_causeofdeathError');
            const applicant_nameError = document.getElementById('applicant_nameError');
            const appl_dateError = document.getElementById('appl_dateError');
            const applicant_dobError = document.getElementById('applicant_dobError');
            const relationshipError = document.getElementById('relationshipError');
            const caste_idError = document.getElementById('caste_idError');
            const applicant_mobileError = document.getElementById('applicant_mobileError');
            const applicant_edu_idError = document.getElementById('applicant_edu_idError');
            const applicant_email_idError = document.getElementById('applicant_email_idError');
            const sexError = document.getElementById('sexError');
            const emp_addr_lcalityError = document.getElementById('emp_addr_lcalityError');
            const emp_addr_districtError = document.getElementById('emp_addr_districtError');
            const emp_addr_subdivError = document.getElementById('emp_addr_subdivError');
            const emp_stateError = document.getElementById('emp_stateError');
            const emp_pincodeError = document.getElementById('emp_pincodeError');
            const emp_addr_lcality_retError = document.getElementById('emp_addr_lcality_retError');
            const emp_addr_district_retError = document.getElementById('emp_addr_district_retError');
            const emp_addr_subdiv_retError = document.getElementById('emp_addr_subdiv_retError');
            const emp_state_retError = document.getElementById('emp_state_retError');
            const emp_pincode_retError = document.getElementById('emp_pincode_retError');
            const applicant_desig_idError = document.getElementById('applicant_desig_idError');
            const applicant_gradeError = document.getElementById('applicant_gradeError');
            // Add more error elements here


            // einInput.addEventListener('onload', function() {

            //     if (einInput.value.trim() === '') {
            //         einError.textContent = 'Please fill up this field';
            //        document.descriptiveRoleForm.ein.focus();
            //         document.getElementById("desc_role_next_btn").disabled = true;
            //     } else {
            //         document.descriptiveRoleForm.deceased_doe.focus();
            //         document.getElementById("desc_role_next_btn").disabled = false;
            //         einError.textContent = '';
            //     }
            // });



            deceased_doeInput.addEventListener('blur', function() {

                var doaInput = document.getElementById('deceased_doa');
                var doeInput = document.getElementById('deceased_doe');

                var doa = new Date(doaInput.value);
                var doe = new Date(doeInput.value);
                console.log(doe, doa, "Anand")


                var fiveYearsLater = new Date(doa);
                console.log(fiveYearsLater, "Anand")
                var res = fiveYearsLater.setFullYear(fiveYearsLater.getFullYear() + 5);
                console.log(res, "Anand")
                var test = doe > fiveYearsLater;
                console.log(test, "Anand")

                if (test == false) {
                    deceased_doeError.textContent = 'Date of expiry must be at least 5 years from the date of appointment.';

                    doeInput.value = '';
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else if (deceased_doeInput.value.trim() == '') {
                    deceased_doeError.textContent = 'Please select Expired Date and cannot be less than 5 years from Date of Appointment';
                    //document.descriptiveRoleForm.deceased_doe.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    deceased_doeError.textContent = '';
                }
                if (test == true) {
                    deceased_doeError.textContent = '';
                    document.getElementById("desc_role_next_btn").disabled = false;

                }
            });


            deceased_causeofdeathInput.addEventListener('blur', function() {
                if (deceased_causeofdeathInput.value.trim() === '') {
                    deceased_causeofdeathError.textContent = 'Please fill up this field';
                    // document.descriptiveRoleForm.deceased_causeofdeath.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    deceased_causeofdeathError.textContent = '';
                }
            });

            applicant_nameInput.addEventListener('blur', function() {
                if (applicant_nameInput.value.trim() === '') {
                    applicant_nameError.textContent = 'Please fill the Applicant Name';
                    // document.descriptiveRoleForm.applicant_name.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    applicant_nameError.textContent = '';
                }
            });

            caste_idInput.addEventListener('blur', function() {
                if (caste_idInput.value.trim() === '') {
                    caste_idError.textContent = 'Please Select Caste';
                    //  document.descriptiveRoleForm.caste_id.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    caste_idError.textContent = '';
                }
            });

            appl_dateInput.addEventListener('blur', function() {
                if (appl_dateInput.value.trim() === '') {
                    appl_dateError.textContent = 'Please Select Application Sumission Date';
                    //  document.descriptiveRoleForm.appl_date.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    const applDateInput = document.getElementById("appl_date");
                    const deceasedDoeInput = document.getElementById("deceased_doe"); // Assuming you have an element with this ID
                    const errorMessage = document.getElementById("error_message");
                    validateDateWithinSixMonths(applDateInput.value, deceasedDoeInput.value, errorMessage);
                    // document.getElementById("desc_role_next_btn").disabled = false;
                    // appl_dateError.textContent = '';
                }
            });

            applicant_dobInput.addEventListener('blur', function() {
                if (applicant_dobInput.value.trim() === '') {
                    applicant_dobError.textContent = 'Please select Applicant DOB';
                    //document.descriptiveRoleForm.applicant_dob.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    validateAge();
                    // document.getElementById("desc_role_next_btn").disabled = false;
                    // applicant_dobError.textContent = '';
                }
            });



            relationshipInput.addEventListener('blur', function() {
                if (relationshipInput.value.trim() === '') {
                    relationshipError.textContent = 'Please select relation with the deceased';
                    //document.descriptiveRoleForm.relationship.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    relationshipError.textContent = '';
                }
            });

            applicant_mobileInput.addEventListener('blur', function() {
                if (applicant_mobileInput.value.trim() === '') {
                    applicant_mobileError.textContent = 'Please enter your mobile number';

                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    applicant_mobileError.textContent = '';



                }
            });

            applicant_edu_idInput.addEventListener('blur', function() {
                if (applicant_edu_idInput.value.trim() === '') {
                    applicant_edu_idError.textContent = 'Please select your education qualification';
                    //document.descriptiveRoleForm.applicant_edu_id.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    applicant_edu_idError.textContent = '';
                }
            });

            applicant_email_idInput.addEventListener('blur', function() {
                if (applicant_email_idInput.value.trim() === '') {
                    applicant_email_idError.textContent = 'Please put your email id';
                    //document.descriptiveRoleForm.applicant_email_id.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    applicant_email_idError.textContent = '';
                }
            });


            sexInput.addEventListener('blur', function() {
                if (sexInput.value.trim() === '') {
                    sexError.textContent = 'Please select ddsex';
                    // document.descriptiveRoleForm.sex.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    sexError.textContent = '';
                }
            });

            emp_addr_lcalityInput.addEventListener('blur', function() {
                if (emp_addr_lcalityInput.value.trim() === '') {
                    emp_addr_lcalityError.textContent = 'Please fill your Address';
                    // document.descriptiveRoleForm.emp_addr_lcality.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_addr_lcalityError.textContent = '';
                }
            });

            emp_addr_districtInput.addEventListener('blur', function() {
                if (emp_addr_districtInput.value.trim() === '') {
                    emp_addr_districtError.textContent = 'Please select district';
                    // document.descriptiveRoleForm.emp_addr_district.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_addr_districtError.textContent = '';
                }
            });

            emp_addr_subdivInput.addEventListener('blur', function() {
                if (emp_addr_subdivInput.value.trim() === '') {
                    emp_addr_subdivError.textContent = 'Please select sub-division';
                    //document.descriptiveRoleForm.emp_addr_subdiv.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_addr_subdivError.textContent = '';
                }
            });

            emp_stateInput.addEventListener('blur', function() {
                if (emp_stateInput.value.trim() === '') {
                    emp_stateError.textContent = 'Please select state';
                    // document.descriptiveRoleForm.emp_state.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_stateError.textContent = '';
                }
            });

            emp_pincodeInput.addEventListener('blur', function() {
                if (emp_pincodeInput.value.trim() === '') {
                    emp_pincodeError.textContent = 'Please fill up the pincode';
                    //document.descriptiveRoleForm.emp_pincode.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_pincodeError.textContent = '';
                }
            });
            //////////////////////////////////////

            emp_addr_lcality_retInput.addEventListener('blur', function() {
                if (emp_addr_lcality_retInput.value.trim() === '') {
                    emp_addr_lcality_retError.textContent = 'Please fill your Address';
                    //document.descriptiveRoleForm.emp_addr_lcality_ret.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_addr_lcality_retError.textContent = '';
                }
            });

            emp_addr_district_retInput.addEventListener('blur', function() {
                if (emp_addr_district_retInput.value.trim() === '') {
                    emp_addr_district_retError.textContent = 'Please select district';
                    // document.descriptiveRoleForm.emp_addr_district_ret.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_addr_district_retError.textContent = '';
                    // document.descriptiveRoleForm.emp_addr_subdiv_ret.focus();
                }
            });

            emp_addr_subdiv_retInput.addEventListener('blur', function() {
                if (emp_addr_subdiv_retInput.value.trim() === '') {
                    emp_addr_subdiv_retError.textContent = 'Please select sub-division';
                    // document.descriptiveRoleForm.emp_addr_subdiv_ret.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_addr_subdiv_retError.textContent = '';
                }
            });

            emp_state_retInput.addEventListener('blur', function() {
                if (emp_state_retInput.value.trim() === '') {
                    emp_state_retError.textContent = 'Please select state';
                    //document.descriptiveRoleForm.emp_state_ret.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_state_retError.textContent = '';
                }
            });

            emp_pincode_retInput.addEventListener('blur', function() {
                if (emp_pincode_retInput.value.trim() === '') {
                    emp_pincode_retError.textContent = 'Please fill up the pincode';
                    //document.descriptiveRoleForm.emp_pincode_ret.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    emp_pincode_retError.textContent = '';
                }
            });

            // 

            applicant_desig_idInput.addEventListener('blur', function() {
                if (applicant_desig_idInput.value.trim() === '') {
                    applicant_desig_idError.textContent = 'Please select post to apply';
                    //document.descriptiveRoleForm.applicant_desig_id.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    applicant_desig_idError.textContent = '';
                }
            });

            applicant_gradeInput.addEventListener('blur', function() {
                if (applicant_gradeInput.value.trim() === '') {
                    applicant_gradeError.textContent = 'Please select grade/group of the applied post';
                    // document.descriptiveRoleForm.applicant_grade.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                } else {
                    document.getElementById("desc_role_next_btn").disabled = false;
                    applicant_gradeError.textContent = '';
                }
            });

            // Add more event listeners for other fields

            document.getElementById('descriptiveRoleForm').addEventListener('submit', function(event) {
                let isValid = true;

                if (deceased_doeInput.value.trim() === '') {
                    deceased_doeError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (deceased_causeofdeathInput.value.trim() === '') {
                    deceased_causeofdeathError.textContent = 'Please fill up this field';
                    // document.descriptiveRoleForm.deceased_causeofdeath.focus();
                    document.getElementById("desc_role_next_btn").disabled = true;
                    isValid = false;
                }
                if (applicant_nameInput.value.trim() === '') {
                    applicant_nameError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (appl_dateInput.value.trim() === '') {
                    appl_dateError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (applicant_dobInput.value.trim() === '') {
                    applicant_dobError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (relationshipInput.value.trim() === '') {
                    relationshipError.textContent = 'Please fill up this field';
                    isValid = false;
                }
                if (applicant_mobileInput.value.trim() === '') {
                    applicant_mobileError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (applicant_edu_idInput.value.trim() === '') {
                    applicant_edu_idError.textContent = 'Please fill up this field';
                    isValid = false;
                }



                if (applicant_email_idInput.value.trim() === '') {
                    applicant_email_idError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (sexInput.value.trim() === '') {
                    sexError.textContent = 'Please fill up this field';
                    isValid = false;
                }
                if (emp_addr_lcalityInput.value.trim() === '') {
                    emp_addr_lcalityError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (emp_addr_districtInput.value.trim() === '') {
                    emp_addr_districtError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (emp_stateInput.value.trim() === '') {
                    emp_stateError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (emp_pincodeInput.value.trim() === '') {
                    emp_pincodeError.textContent = 'Please fill up this field';
                    isValid = false;
                }
                if (applicant_desig_idInput.value.trim() === '') {
                    applicant_desig_idError.textContent = 'Please fill up this field';
                    isValid = false;
                }

                if (applicant_gradeInput.value.trim() === '') {
                    applicant_gradeError.textContent = 'Please fill up this field';
                    isValid = false;
                }


                // Add more validation checks for other fields

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deceasedDoeInput = document.getElementById('deceased_doe');
            var applDateInput = document.getElementById('appl_date');
            var errorElement = document.getElementById('deceased_doeError');

            deceasedDoeInput.addEventListener('blur', function() {
                var deceasedDoe = new Date(deceasedDoeInput.value);
                var sixMonthsBeforeApplDate = new Date(applDateInput.value);
                sixMonthsBeforeApplDate.setMonth(sixMonthsBeforeApplDate.getMonth() - 6);

                if (deceasedDoe < sixMonthsBeforeApplDate) {
                    errorElement.textContent = 'Date must be within six months of the application submission date.';
                } else {
                    errorElement.textContent = '';
                }
            });
        });
    </script>



 <!-- from this  to display the textbox when we select Others from the Educational Qualification -->
 <script>
    document.getElementById('applicant_edu_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex].text;
        var otherEducationDiv = document.getElementById('otherEducation');

        if (selectedOption === 'Others') {
            otherEducationDiv.style.display = 'block';
        } else {
            otherEducationDiv.style.display = 'none';
        }
    });
    </script>


    <!-- upto  this  to display the textbox when we select Others from the Educational Qualification -->

    <script type="text/javascript" src="{{ asset('assets/js/auth/date.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/auth/proformaselect.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/auth/proforma2ndApplsave.js') }}"></script>



    @endsection
    <script>
    $('document').ready(()=>{
        $('#applicant_edu_id').select2();
    })
    
    
    $('document').ready(()=>{
        $('#applicant_desig_id').select2();
    })

    $('document').ready(()=>{
        $('#second_post_id').select2();
    })

    $('document').ready(()=>{
        $('#dept_id_option').select2();
    })

    $('document').ready(()=>{
        $('#third_post_id').select2();
    })

</script>