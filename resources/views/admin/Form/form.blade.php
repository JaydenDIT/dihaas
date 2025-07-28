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
  
   
    <title>DIHAS</title>


</head>

<body>



    @extends('layouts.app')

    @section('content')
    @php $form_no = 1; @endphp
    <?php $selected = session()->get('deptId') ?>
    <div class="container">
    <br>
        <!-- @include('admin.form_menu_buttons') -->
        @include('admin.progress.form_progress')

        <div class="row justify-content-center">
            <!-- @include('admin.form_menu_buttons') -->

            <br>
            <div class="col-md-12 margintop">
                <!-- Descriptive Role & Personel Details-->
                <div class="card displayshow" id="desc_role_section">
                    <div class="card-header">
                        <div class="row">
                        <div class="col-12 center">
                            <?php
                            // Convert the date string to a DateTime object
                            $deceasedDate = new DateTime($data['deceased_doe']);

                            // Add 6 months to the date
                            $sixMonthsLater = $deceasedDate->modify('+6 months');

                            // Display the result in red
                            echo "<p class='colourred'>Last date of submission: " . $sixMonthsLater->format('d-m-Y') . "</p>";
                            ?>
                            </div>
                            
                        <div class="col-12 textleft" >
                                <b>EIN: {{ $data['ein'] }} &nbsp; Deceased Name : {{ $data['deceased_emp_name'] }} &nbsp; D.O.E: {{date('d-m-Y', strtotime( $data['deceased_doe'])) }} &nbsp;&nbsp;&nbsp; Applicant Name : {{ $data['applicant_name'] }}</b>
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
                                {{ session()->get('message') }}
                            </div>
                            @endif
                            @if(session()->has('errormessage'))
                            <div class="alert alert-danger">
                                {{ session()->get('errormessage') }}
                            </div>
                            @endif
                            <!-- <hr> -->
                            <!--  -->
                            <br>
                            <div id="form_body" class="displayshow">
                                <form id="descriptiveRoleForm" method="POST" action="" enctype="multipart/form-data" class="">
                                    @csrf
                                    @method('PUT')

                                    <div id="form_header" class="textcenter_fontsize"><b>Submitted Proforma </b></div>
                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="ein"><b>EIN Number of Deceased Government Servant </b></label>
                                            <label class="rq displaynone" id="einerror"><b>* Please enter EIN to proceed further</b></label>
                                        </div>
                                        <div class=" col-sm-3">
                                            <input readonly type="text" class="form-control" name="ein" id="ein" value="{{ $data == null ? '' : $data['ein'] }}">
                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2">
                                            <label for="name"><b>Name of the Deceased Government Servant</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="deceased_emp_name" id="deceased_emp_name" value="{{ $data == null ? '' : $data['deceased_emp_name'] }}" readonly>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div><br>


                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="dept_name"><b>Department</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="dept_name" id="dept_name" value="{{ $data == null ? '' : $data['dept_name'] }}" readonly>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2">
                                            <label for="ministry"><b>Administrative Department</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="ministry" id="ministry" value="{{ $data == null ? '' : $data['ministry'] }}" readonly>
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
                                            <input type="date" class="form-control" placeholder="D.O.A." name="deceased_doa" id="deceased_doa" value="{{ $data['deceased_doa'] == null ? null : $data['deceased_doa'] }}" readonly>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2">
                                            <label for="desig_name"><b>Post Held</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="desig_name" id="desig_name" value="{{ $data == null ? '' : $data['desig_name'] }}" readonly>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                    </div></br>
                                    <div class="row">

                                        <div class="col-sm-2">
                                            <label for="deceased_dob"><b>Deceased DOB</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control" placeholder="" id="deceased_dob" name="deceased_dob" value="{{ $data['deceased_dob'] == null ? null : $data['deceased_dob'] }}" readonly required>


                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2">
                                            <label for="grade_name"><b>Grade/Group</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" name="grade_name" id="grade_name" value="{{ $data == null ? '' : $data['grade_name'] }}" readonly>
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
                                    <div class="invalid-feedback">Submitted Data for Applicant</div>
                                    <!-- Deceased Details -->
                                    <div class="row fontweight_color" id="dividerDiv">
                                        Information of the Deceased Government Servant:
                                    </div>
                                    <br>

                                    <br>
                                     <div class="row">
                                        <div class="col-sm-2 textleft">

                                            <label for="expire"><b>Expired on duty</b></label>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="displayflex">
                                                <div class="marginright">
                                                    <label class="radio-inline" for="yes"><b>
                                                            <input disabled type="radio" id="Yes" name="expire_on_duty" value="yes" {{$data['expire_on_duty'] == "yes" ? "checked" : ''}}>Yes</b></label><br>
                                                </div>
                                                <div>
                                                    <label class="radio-inline marginL" for="no"><b>
                                                            <input disabled type="radio" id="No" name="expire_on_duty" value="no" {{$data['expire_on_duty'] == "no" ? "checked" : ''}}>No</b></label><br>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="deceased_doe"><b>Date of Expiry</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input readonly type="date" class="form-control" id="deceased_doe" name="deceased_doe" placeholder="DOE" value="{{ $data['deceased_doe'] == null ? null : $data['deceased_doe'] }}">

                                        </div>

                                    </div>

                                    <br>
                                    <div class="row">
                                    <div class="col-sm-2 textleft">

                                            <label for="deceased_causeofdeath"><b>Cause of Death</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input readonly type="text" class="form-control texttransform" placeholder="" id="deceased_causeofdeath" name="deceased_causeofdeath" value="{{ $data == null ? '' : $data['deceased_causeofdeath'] }} ">
                                        </div>


                                    </div>
                                    <br>


                                    <br>


                                    <br>

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
                                            <input readonly type="text" class="form-control texttransform" placeholder="" id="applicant_name" name="applicant_name" value="{{ $data == null ? null : $data['applicant_name'] }}">
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        @if($getUploader->role_id == 77)
                                        <div class="col-sm-2 textleft">

                                            <label for="date"><b>Date of Application Submitted</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input hidden type="date" class="form-control" value="{{ $data['appl_date'] == null ? null : $data['appl_date'] }}" id="appl_date" name="appl_date">
                                        </div>

                                        @else
                                        <div class="col-sm-2 textleft">

                                            <label for="date"><b>Date of Application Submitted</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input readonly type="date" class="form-control" value="{{ $data['appl_date'] == null ? null : $data['appl_date'] }}" id="appl_date" name="appl_date">
                                        </div>

                                        @endif

                                    </div>
                                    <br>
                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="applicant_dob"><b>Date of birth</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input readonly type="date" class="form-control" placeholder="DOB" id="applicant_dob" name="applicant_dob" value="{{ $data['applicant_dob'] == null ? '' : $data['applicant_dob'] }}" required>
                                        </div>

                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2 textleft">

                                            <label for="relationship"><b>Relationship with Deceased/Retired</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled class="form-select" aria-label="Default select example" id="relationship" name="relationship">
                                                <option selected>Select</option>
                                                @foreach($Relationship as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $data['relationship'] ? 'selected' : ''}} required> {{$option['relationship']}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" placeholder="Relationship" class="form-control" id="relationship" name="relationship" value="{{ $data['relationship'] == null ? null : $data['relationship'] }}">
                                        </div>


                                    </div>

                                    <br>
                                    <div class="row">
                                    <div class="col-sm-2 textleft">

                                            <label for="mobile_no"><b>Mobile No.</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input readonly type="number" class="form-control" id="applicant_mobile" name="applicant_mobile" value="{{ $data == null ? '' : $data['applicant_mobile'] }}" required>
                                        </div>

                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-2 textleft">

                                            <label for="applicant_edu_id"><b>Educational Qualification</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled class="form-select" aria-label="Default select example" id="applicant_edu_id" name="applicant_edu_id">
                                                <option selected>Select</option>
                                                @foreach($educations as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $data['applicant_edu_id'] ? 'selected' : ''}} required> {{$option['edu_name']}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" placeholder="State" class="form-control" id="applicant_edu_id" name="applicant_edu_id" value="{{ $data['applicant_edu_id'] == null ? null : $data['applicant_edu_id'] }}">
                                        <br>
                                            <div id="otherEducation" style="display: none;">
                                                <input type="text" class="form-control" id="other_qualification"
                                                    name="other_qualification">
                                            </div>
                                        
                                        </div>

                                     
                                    </div>

                                    <br>


                                    <!-- Physically Handicapped -->
                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="physically_handicapped"><b>Physically Handicapped</b></label>
                                        </div>
                                        <div class="col-sm-3 ">
                                        <div class="displayflex">
                                                <div class="marginright">
                                                    <label class="radio-inline" for="yes"><b>
                                                            <input disabled type="radio" id="Yes" name="physically_handicapped" value="yes" {{$data['physically_handicapped'] == "yes" ? "checked" : ''}}>Yes</b></label><br>

                                                </div>
                                                <div>
                                                    <label class="radio-inline" for="no"><b>
                                                            <input disabled type="radio" id="No" name="physically_handicapped" value="no" {{$data['physically_handicapped'] == "no" ? "checked" : ''}}>No</b></label><br>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2 textleft">

                                            <label for="applicant_email_id"><b>Email Id</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input readonly type="email" class="form-control" id="applicant_email_id" name="applicant_email_id" value="{{ $data == null ? '' : $data['applicant_email_id'] }}" required>
                                        </div>



                                    </div>
                                    <br>
                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="caste_id"><b>Caste</b></label>
                                         
                                        </div>
                                        <div class="col-sm-3 ">

                                            <select disabled class="form-select  @error('caste_id') is-invalid @enderror" aria-label="Default select example" id="caste_id" name="caste_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($Caste as $option)
                                                <option value="{{$option['caste_id']}}" {{$option['caste_id'] == $data['caste_id'] ? 'selected' : ''}} required> {{$option['caste_name']}}</option>
                                                @endforeach
                                            </select>


                                            @error('caste_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2 textleft">

                                            <label for="gender"><b>Sex</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select" aria-label="Default select example" id="sex" name="sex" disabled>
                                                <option selected>Select</option>
                                                @foreach($Gender as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $data['sex'] ? 'selected' : ''}} required> {{$option['sex']}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" readonly placeholder="Sex" class="form-control" id="sex" name="sex" value="{{ $data['sex'] == null ? null : $data['sex'] }}">
                                            <p id="sexError" class="red"></p>
                                        </div>




                                    </div>
                                    <br>
                                    <!-- Identification Mark -->

                                    <br>

                                    <div class="row">
                                    <div class="col-sm-2 textalign_fontsize_fontweight_color"><u>
                                                <font class="fontweight_color">Present Address</font>
                                            </u></div>
                                            <div class="col-sm-3 textleft">
                                            <hr>
                                        </div>

                                        <div class="col-sm-4 textalign_fontsize_fontweight_color">
                                            <input disabled class="form-check-input" type="checkbox" id="presentAddressSamecheckbox" name="presentAddressSamecheckbox" value="presentAddressSamecheckbox">
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
                                    <br>

                                    <!-- Present Address -->
                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="emp_addr_lcality"><b>Locality </b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <textarea readonly class="form-control" id="emp_addr_lcality" rows="2" placeholder="Present Address" name="emp_addr_lcality">{{ $data == null ? '' : $data['emp_addr_lcality'] }}</textarea>
                                            <p id="emp_addr_lcalityError" class="red"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">
                                            <label for="emp_addr_lcality_ret"><b>Locality</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <textarea readonly class="form-control" id="emp_addr_lcality_ret" rows="2" placeholder="Present Address" name="emp_addr_lcality_ret">{{ $data == null ? '' : $data['emp_addr_lcality_ret'] }}</textarea>
                                        </div>
                                    </div>

                                    <br>

                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="emp_state"><b>State</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled name="emp_state" id="emp_state" onChange="funSelectChange('emp_addr_district',this);" class="form-select @error('emp_state') is-invalid @enderror" readonly required>

                                                <option value="" disabled>Choose State....</option>
                                                @foreach($stateDetails as $row)
                                                <option value="{{ $row->id }}" @if($proformas->emp_state == $row->id)
                                                    selected @endif >{{ $row->name }}</option>
                                                @endforeach
                                            </select>

                                            <p id="emp_stateError" class="red"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">
                                            <label for="emp_state"><b>State</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled name="emp_state_ret" id="emp_state_ret" onChange="funSelectChange('emp_addr_district_ret',this);" class="form-select @error('emp_state_ret') is-invalid @enderror" readonly required>

                                                <option value="" disabled>Choose State....</option>
                                                @foreach($stateDetails as $row)
                                                <option value="{{ $row->id }}" @if($proformas->emp_state_ret == $row->id)
                                                    selected @endif >{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="emp_state_retError" class="red"></p>
                                        </div>

                                    </div>
                                    <br>
                                    <div class="row">
                                    <div class="col-sm-2 textleft">

                                            <label for="emp_addr_district"><b>District</b></label>

                                        </div>

                                        <div class="col-sm-3">
                                            <select disabled name="emp_addr_district" id="emp_addr_district" onChange="funSelectChange('emp_addr_subdiv',this);" class="form-select @error('emp_addr_district') is-invalid @enderror" readonly required>
                                                <option value="" disabled>Choose District....</option>
                                                @foreach($cur_districts as $row)
                                                <option value="{{ $row->id }}" @if($proformas->emp_addr_district == $row->id) selected
                                                    @endif >{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="emp_addr_districtError" class="red"></p>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2 textleft">

                                            <label for="emp_addr_district_ret"><b>District</b></label>

                                        </div>

                                        <div class="col-sm-3">
                                            <select disabled name="emp_addr_district_ret" id="emp_addr_district_ret" onChange="funSelectChange('emp_addr_subdiv_ret',this);" class="form-select @error('emp_addr_district_ret') is-invalid @enderror" readonly required>
                                                <option value="" disabled>Choose District....</option>
                                                @foreach($per_districts as $row)
                                                <option value="{{ $row->id }}" @if($proformas->emp_addr_district_ret == $row->id) selected
                                                    @endif >{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="emp_addr_district_retError" class="red"></p>
                                        </div>

                                    </div>
                                    <br>
                                    <!-- sub division -->
                                    <div class="row">
                                    <div class="col-sm-2 textleft">
                                            <label for="emp_addr_subdiv"><b>Sub Division</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled name="emp_addr_subdiv" id="emp_addr_subdiv" class="form-select @error('emp_addr_subdiv') is-invalid @enderror" readonly required>
                                                <option value="" disabled selected>Choose Sub Division....</option>
                                                @foreach($cur_subdivision as $row)
                                                <option value="{{ $row->id }}" @if($proformas->emp_addr_subdiv == $row->id)
                                                    selected
                                                    @endif >{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="emp_addr_subdivError" class="red"></p>

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">
                                            <label for="emp_addr_subdiv"><b>Sub Division</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled name="emp_addr_subdiv_ret" id="emp_addr_subdiv_ret" class="form-select @error('emp_addr_subdiv_ret') is-invalid @enderror" readonly required>
                                                <option value="" disabled selected>Choose Sub Division....</option>
                                                @foreach($per_subdivision as $row)
                                                <option value="{{ $row->id }}" @if($proformas->emp_addr_subdiv_ret == $row->id)
                                                    selected
                                                    @endif >{{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            <p id="emp_addr_subdiv_retError" class="red"></p>
                                        </div>
                                    </div>
                                    <br>
                                    <!-- State -->

                                    <div class="row">
                                        <!-- Pincode -->
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_pincode"><b>Pin Code</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" placeholder="Pincode." class="form-control" id="emp_pincode" name="emp_pincode" value="{{ $data['emp_pincode'] == null ? null : $data['emp_pincode'] }}" readonly>
                                        </div>


                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-2 textleft">

                                            <label for="emp_pincode_ret"><b>Pin Code</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" placeholder="Pincode." class="form-control" id="emp_pincode_ret" name="emp_pincode_ret" value="{{ $data['emp_pincode_ret'] == null ? null : $data['emp_pincode_ret'] }}" readonly>
                                        </div>
                                    </div>

                                    <br>


                                    <!-- Post Proposed details -->
                                                                       <br>
                                    <!-- Applied Post and Grade  -->
                                   
                                    <!-- Applied Post and Grade  -->
                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-12 fontweight_color">Post Proposed for Appointment in parent
                                            Department</div>
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
                                            <select disabled
                                                class="form-select  @error('applicant_desig_id') is-invalid @enderror "
                                                aria-label="Default select example" id="applicant_desig_id"
                                                name="applicant_desig_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($post as $option)
                                                <option value="{{$option['dsg_serial_no']}}"
                                                    {{$option['dsg_serial_no'] == $data['applicant_desig_id'] ? 'selected' : ''}}
                                                    required> {{$option['dsg_desc']}}</option>
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

                                            <input class="form-control" type="text"
                                                value="{{ $data['applicant_grade'] == null ? null : $data['applicant_grade'] }}"
                                                id="applicant_grade" name="applicant_grade" readonly>
                                            <div id="applicant_gradeError" class="text-danger"></div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    @if( $data['second_post_id'] !=0 || $data['dept_id_option'] != 0 || $data['third_post_id'] !=0)
                                    <div class="row">

                                        <div class="col-sm-2 textleft">

                                            <label for="post_applied"><b>Second Preference</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select disabled class="form-select  @error('second_post_id') is-invalid @enderror "
                                                aria-label="Default select example" id="second_post_id"
                                                name="second_post_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($post as $option)
                                                <option value="{{$option['dsg_serial_no']}}"
                                                    {{$option['dsg_serial_no'] == $data['second_post_id'] ? 'selected' : ''}}
                                                    required> {{$option['dsg_desc']}}</option>
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

                                            <input class="form-control" type="text"
                                                value="{{ $data['second_grade_id'] == null ? null : $data['second_grade_id'] }}"
                                                id="second_grade_id" name="second_grade_id" readonly>
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
                                            <select disabled class="form-select" aria-label="Default select example"
                                                id="dept_id_option" name="dept_id_option">
                                                <option value="" selected>All Department</option>
                                                @foreach($deptListArray as $option)

                                                <option value="{{$option['dept_id']}}"
                                                    {{$option['dept_id'] == $data['dept_id_option'] ? 'selected' : ''}}
                                                    required> {{$option['dept_name']}}</option>
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
                                            <!--   // below hidden is for third designation which is already in database -->
                                            <input type="hidden" value="{{$data['third_post_id']}}"
                                                id="hiddenThirdPostId">
                                            <select disabled class="form-select  @error('third_post_id') is-invalid @enderror "
                                                aria-label="Default select example" id="third_post_id"
                                                name="third_post_id">
                                                <option value="" selected disabled>Select</option>

                                                <option value="{{$option['dsg_serial_no']}}"
                                                    {{$option['dsg_serial_no'] == $data['third_post_id'] ? 'selected' : ''}}
                                                    required> {{$option['dsg_desc']}}</option>

                                            </select>
                                            <div id="third_post_idError" class="text-danger"></div>

                                        </div>

                                        <div class="col-sm-1">

                                        </div>

                                        <div class="col-sm-2 textleft">
                                            <label for="grade"><b>Grade</b></label>
                                        </div>
                                        <div class="col-sm-3">

                                            <input readonly class="form-control" type="text"
                                                value="{{ $data['applicant_grade'] == null ? null : $data['third_grade_id'] }}"
                                                id="third_grade_id" name="third_grade_id" readonly>
                                            <div id="third_grade_idError" class="text-danger"></div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>


                                    <hr>

                                    @endif
                                    <div class="row textright">
                                        <div class="col-sm-12">
                                            @csrf
                                            @method('POST')
                                            <a href="{{ route('view-family-details') }}" class=" btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true"><span class="style200">Next</span></a>



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


 
    <script nonce="{{ csp_nonce() }}">
  
        window.addEventListener('DOMContentLoaded', function() {
            var initialOption = document.getElementById('applicant_edu_id');
            var otherEducationDiv = document.getElementById('otherEducation');
            var otherQualificationInput = document.getElementById('other_qualification');
    
            if (initialOption.options[initialOption.selectedIndex].text === 'Others') {
                otherEducationDiv.style.display = 'block';
                // Populate the input field with the old value if other_qualification is  available 
                otherQualificationInput.value = "{{$data['other_qualification'] =! null ? $data['other_qualification'] : ''}}";
            }
        });
    
    </script>

    <script nonce="{{ csp_nonce() }}">
        //     $("#datepicker").datepicker({
        //         autoclose: true,
        //         format: "dd-mm-yyyy",
        //         todayHighlight: true,

        //     }).datepicker('#deceased_doa', new Date());
        // });

        var _token = "{{ csrf_token() }}";

        $(document).ready(function() {
            $("#presentAddressSamecheckbox").change(function() {
                var checkStat = $('#presentAddressSamecheckbox').is(':checked');
                // get address before retirement
                var localAddress = $('#emp_present_address_model_field').val();
                var district = $('#emp_addr_district_model_field :selected').val();
                var suvDivision = $('#emp_addr_subdiv_model_field :selected').val();
                // var assemConst = $('#emp_addr_assem_cons_model_field :selected').val();
                var state = $('#emp_state_model_field :selected').val();
                var pin = $('#emp_pincode_model_field').val();

                if ($('#presentAddressSamecheckbox').is(':checked')) {
                    // set address after retirement same as address before retirement
                    $('#emp_present_address_model_field_retr').val(localAddress);
                    $('#emp_addr_district_retr').val(district);
                    $('#emp_addr_subdiv_retr').val(suvDivision);
                    //$('#emp_addr_assem_cons_retr').val(assemConst);
                    $('#emp_state_ret_model_field').val(state);
                    $('#emp_pincode_ret_model_field').val(pin);
                } else {
                    // set address after retirement same as address before retirement
                    $('#emp_present_address_model_field_retr').val("");
                    $('#emp_addr_district_retr').val("");
                    $('#emp_addr_subdiv_retr').val("");
                    $('#emp_addr_assem_cons_retr').val("");
                    $('#emp_state_ret_model_field').val("");
                    $('#emp_pincode_ret_model_field').val("");
                }

            });
        });


        $(document).ready(function() {
        $("#third_post_id").empty();
        $('#third_post_id').append(new Option('Select Post', ''));
        $('#third_post_id option[value=""]').attr('disabled', true);

        var id = $('#dept_id_option').find('option:selected').val();
        var data_dept_id = {
            'dept_id': $('#dept_id_option').find('option:selected').val(),
        };

        $.get('{{ route("retrieve_dept") }}', data_dept_id, function(id, textStatus, xhr) {

            //now load the result data in id third_post_id i.e. for designation  
            console.log(data_dept_id.dept_id);
            console.log(JSON.stringify(id));

            $.each(id, function(index, element) {

                //$('#third_post_id').append(new Option(element.dsg_desc, element.dsg_serial_no)); //only value and text
                //Below is for adding extra attribute
                $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr(
                    'data-grade', element.group_code).appendTo('#third_post_id');

            });
            // below 2 lines is for third designation 
            var hiddenThirdPostId = $('#hiddenThirdPostId').val();
            $('#third_post_id option[value="' + hiddenThirdPostId + '"]').attr('selected', true)

        });

    });

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
        // console.log(third_post_id)   ;
        // alert($('#third_post_id option[value="'+this.value+'"]').data('grade'));
        $('#third_grade_id').val($('#third_post_id option[value="' + this.value + '"]').data('grade'));

    })


    $('#dept_id_option').change(function() {

        //make blank      

        $("#third_post_id").empty();
        $('#third_post_id').append(new Option('Select Post', ''));
        $('#third_post_id option[value=""]').attr('disabled', true);

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
                $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr(
                    'data-grade', element.group_code).appendTo('#third_post_id');

            });

        });


    })


   

    </script>






    @endsection