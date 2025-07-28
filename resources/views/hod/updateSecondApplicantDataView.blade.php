<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">

   









    <title>DIHAS</title>

    <style nonce="{{ csp_nonce() }}">
        input[type="radio"].readonly {
        pointer-events: none;
    }

    label.readonly {
        pointer-events: none;
    }
        .rq {
            color: #FF0000;
            font-size: 10pt;
        }

        .error {
            color: #FF0000;
            font-size: 14pt;
            font-style:oblique;
        }

        div.c {
            text-transform: capitalize;
        }

        .flex {
            display: flex;
            margin-top: 30px;

        }

        .flex2 {
            display: flex;
            margin-top: 10px;

        }

        .flex3 {
            display: flex;
            margin-top: 20px
        }

        .label {

            margin-right: 30px;
        }

        .label1 {

            margin-right: 30px;
            margin-left: 30px;
        }

        .label2 {

            margin-right: 45px;

        }

        .label3 {

            margin-right: 250px;

        }

        .label4 {

            margin-right: 232px;

        }

        input[type=text]:focus:not([readonly]) {
            border: 1px solid #609;
            box-shadow: 0 1px 0 0 #609;

        }

        .button1 {
            border-radius: 4px;
            background-color: #f4511e;
            border: none;
            color: #FFFFFF;
            text-align: center;
            font-size: 28px;
            padding: 5px;
            width: 500px;
            transition: all 0.5s;
            cursor: pointer;
            margin: 5px;
        }

        .button1 span {
            cursor: pointer;
            display: inline-block;
            position: relative;
            transition: 0.5s;
        }

        .button1 span:after {
            content: '\00bb';
            position: absolute;
            opacity: 0;
            top: 0;
            right: -20px;
            transition: 0.5s;
        }

        .button1:hover span {
            padding-right: 25px;
        }

        .button1:hover span:after {
            opacity: 1;
            right: 0;
        }

        a:hover {

            color: white;
        }

        .flex1 {
            display: flex;
        }

        .a {
            width: 200px;
        }
    </style>
</head>

<body onload="firstfocus();">

    <!-- @include('sweetalert::alert') -->
    <!-- above code is for showing notification -->



    @extends('layouts.app')

    @section('content')
    @php $form_no = 1; @endphp

    <div class="container">
        <!-- Progress -->
        <!-- @include('admin.form_menu_buttons') -->
        @include('admin.progress.form_progress_2nd')

        <div class="row justify-content-center">
            <!-- @include('admin.form_menu_buttons') -->

            <br>
            <div class="col-md-12" style="margin-top: 10px;">
                <!-- Descriptive Role & Personel Details-->
                <div class="card" id="desc_role_section" style="display:show">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12" style="text-align: left;">


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
                            <div id="form_body" style="display:show">
                                <form name="descriptiveRoleForm" id="descriptiveRoleForm" method="POST" enctype="multipart/form-data" onSubmit="return formValidation();">
                                    <!-- onSubmit="return formValidation();">class="was-validated"> -->
                                    @csrf
                                    @method('PUT')

                                    <div id="form_header" style="text-align: center;font-size:22px">
                                        <b>Proforma Data Entry </b>
                                    </div>
                                    <div class="error">{{($notfound != null)?$notfound:''}}</div>
                                    <div class="flex1">

                                        <div>

                                            <div class="flex">
                                                <div class="label">
                                                    <label for="ein"><b>EIN Number of Deceased Government Servant </b></label>
                                                    <label class="rq" id="einerror" style="display:none"><b>* Please enter EIN to proceed further</b></label>
                                                </div>
                                                <div>
                                                    <input type="text" class="form-control @error('ein') is-invalid @enderror" onblur="fieldValidate('ein');getEmployee()" style="width:300px" name="ein" id="ein" value="{{ (count($data)>0)?$data['emp_state_ein']:'' }}" placeholder="" readonly>
                                                    @error('ein')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="flex2">
                                                <div class="label2">
                                                    <label for="name"><b>Name of the Deceased Government Servant</b></label>
                                                </div>
                                                <div> <input type="text" style="width:300px" class=" form-control @error('deceased_emp_name') is-invalid @enderror" onblur="fieldValidate('deceased_emp_name')" name="deceased_emp_name" id="deceased_emp_name" value="{{ (count($data)>0)?$data['emp_title'].' '.$data['emp_lname']:'' }}" readonly>
                                                    @error('d_emp_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="flex3">
                                                <div class="label3"><label for="dept_name"><b>Department</b></label></div>
                                                <div>
                                                    <input type="text" class="form-control @error('dept_name') is-invalid @enderror" style="width:300px" name="dept_name" id="dept_name" value="{{ (count($data)>0)?$data['field_dept_desc']:'' }}" placeholder="" readonly>

                                                    @error('dept_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                           
                                            <div class="flex3">
                                                <div class="label4"><label for="deceased_dob"><b>Deceased DOB</b></label></div>
                                                <div>
                                                    <input type="text" class="form-control  @error('deceased_dob') is-invalid @enderror" style="width:300px" placeholder="" id="deceased_dob" name="deceased_dob" value="{{ (count($data)>0)?$data['emp_birth_dt']:'' }}" readonly>

                                                    @error('deceased_dob')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>

                                            </div>

                                        </div>
                                        <div style="margin-left:80px;">

                                            <div class="flex">
                                                <div class="a">
                                                    <label for="d_doa"><b>Date of Appoinment</b></label>
                                                </div>
                                                <div>
                                                    <input class="form-control  @error('deceased_doa') is-invalid @enderror" style="width:300px" name="deceased_doa" id="deceased_doa" value="{{ (count($data)>0)?$data['emp_entry_dt']:'' }}" placeholder="" readonly>
                                                    @error('d_doa')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror

                                                </div>
                                            </div>

                                            <div class="flex2">
                                                <div class="a"><label for="desig_name"><b>Post Held</b></label></div>
                                                <div><input type="text" style="width:300px" class="form-control  @error('desig_name') is-invalid @enderror" name="desig_name" id="desig_name" value="{{ (count($data)>0)?$data['emp_desig']:'' }}" placeholder="" readonly>

                                                    @error('desig_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="flex2">
                                                <div class="a"><label for="ministry"><b>Administrative Department</b></label></div>
                                                <div><input type="text" class="form-control @error('ministry') is-invalid @enderror" style="width:300px" name="ministry" id="ministry" value="{{ (count($data)>0)?$data['adm_dept_desc']:'' }}" placeholder="" readonly>

                                                    @error('ministry')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>


                                            <div class="flex2">
                                                <div class="a"><label for="grade_name"><b>Grade/Group</b></label></div>
                                                <div><input type="text" class="form-control  @error('grade_name') is-invalid @enderror" style="width:300px" name="grade_name" id="grade_name" value="{{ (count($data)>0)?$data['emp_group']:'' }}" placeholder="" readonly>
                                                    @error('grade_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <!-- <hr> -->
                                    <!--  -->
                                    <br>
                                    @csrf
                                    <!-- validation -->
                                    <div class="valid-feedback">Valid.</div>
                                    <div class="invalid-feedback">Please fill out this field.</div>
                                    <!-- Deceased Details -->
                                    <div class="row" id="dividerDiv">
                                        Information of the Deceased Government Servant:
                                    </div>
                                    <br>

                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2 " style="text-align: left;">
                                            <label for="expire"><b>Expire on duty</b></label>
                                            <label class="rq" id="expire_on_dutyerror" style="display:none"><b>* Please Choose Yes or No</b></label>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div style="display:flex; ">
                                                <div style="margin-right:50px;">
                                                    <label class="radio-inline" for="yes"><b>
                                                            <input class="readonly" type="radio" class="  @error('expire_on_duty') is-invalid @enderror" id="Yes" name="expire_on_duty" value="yes" {{$getDeceased->expire_on_duty == "yes" ? "checked" : ''}}>Yes</b></label><br>
                                                </div>
                                                <div>
                                                    <label class="radio-inline" for="no"><b>
                                                            <input class="readonly" type="radio" class=" @error('expire_on_duty') is-invalid @enderror" id="No" name="expire_on_duty" value="no" {{$getDeceased->expire_on_duty == "no" ? "checked" : ''}} >No</b></label><br>
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
                                        <div class="col-sm-3" style="text-align: left;">
                                            <label for="deceased_doe"><b>Date of Expiry</b></label>
                                            <label class="rq" id="deceased_doeerror" style="display:none"><b>* Please select date of expiry</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control  @error('deceased_doe') is-invalid @enderror" onblur="fieldValidate('deceased_doe')" placeholder="" id="deceased_doe" name="deceased_doe" value="{{ $getDeceased->deceased_doe }}" readonly>
                                            @error('deceased_doe')
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
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="deceased_causeofdeath"><b>Cause of Death</b></label>
                                            <label class="rq" id="deceased_causeofdeatherror" style="display:none"><b>* Please mention cause of death</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control  @error('deceased_causeofdeath') is-invalid @enderror" onblur="fieldValidate('deceased_causeofdeath')" placeholder="" id="deceased_causeofdeath" name="deceased_causeofdeath" value="{{ $getDeceased->deceased_causeofdeath }}" style="text-transform:uppercase" readonly>
                                            @error('deceased_causeofdeath')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                      

                                    </div>
                                    <br>


                                    <br>


                                    <br>

                                    <!-- Applicant Details -->
                                    <div class="row" id="dividerDiv">
                                        Applicant/Claimaint Details :
                                    </div>
                                    <br>
                                    <br>


                                    <div class="row">
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="name"><b>Applicant Name</b></label>
                                            <label class="rq" id="applicant_nameerror" style="display:none"><b>* Please Enter Applicant Name</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control  @error('applicant_name') is-invalid @enderror" onblur="fieldValidate('applicant_name')" placeholder="" id="applicant_name" name="applicant_name" value="{{ $get2nd_appl->second_appl_name }}" style="text-transform:uppercase" required>
                                            <!-- <input type="text" class="form-control  @error('applicant_name') is-invalid @enderror" onblur="fieldValidate('applicant_name')" placeholder="" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" style="text-transform:uppercase" required> -->
                                            @error('applicant_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-1">
                                        </div>

                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="date"><b>Date of Application Submitted</b></label>
                                            <label class="rq" id="appl_dateerror" style="display:none"><b>* Please select Date of Submission</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control  @error('appl_date') is-invalid @enderror" onblur="fieldValidate('appl_date')" placeholder="" id="appl_date" name="appl_date" value="{{ old('appl_date') }}" required>
                                            @error('appl_date')
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
                                        <div class="col-sm-2" style="text-align: left;">
                                            <label for="applicant_dob"><b>Date of birth</b></label>
                                            <label class="rq" id="applicant_doberror" style="display:none"><b>* Please select DOB of Applicant</b></label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="date" class="form-control  @error('applicant_dob') is-invalid @enderror" onblur="fieldValidate('applicant_dob')" placeholder="" id="applicant_dob" name="applicant_dob" value="{{ old('applicant_dob') }}" required>
                                            @error('applicant_dob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="relationship"><b>Relationship with Deceased/Retired</b></label>
                                            <label class="rq" id="relationshiperror" style="display:none"><b>* Please fill the relationship</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control  @error('relationship') is-invalid @enderror" onblur="fieldValidate('relationship')" placeholder="" id="relationship" name="relationship" value="{{ old('relationship') }}" required>
                                            @error('relationship')
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
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="applicant_mobile"><b>Mobile No.</b></label>
                                            <label class="rq" id="applicant_mobileerror" style="display:none"><b>* Please Enter Mobile Number</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="tel" class="form-control  @error('applicant_mobile') is-invalid @enderror" onblur="fieldValidate('applicant_mobile')" placeholder="" id="applicant_mobile" name="applicant_mobile" maxlength="10" pattern="[1-9]{1}[0-9]{9}" value="{{ old('applicant_mobile') }}" required>

                                            @error('applicant_mobile')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>


                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="applicant_edu_id"><b>Educational Qualification</b></label>
                                            <label class="rq" id="applicant_edu_iderror" style="display:none"><b>* Please select Educational Qualification</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select" @error('applicant_edu_id') is-invalid @enderror" onblur="fieldValidate('applicant_edu_id')" aria-label="Default select example" id="applicant_edu_id" name="applicant_edu_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($educations as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == old($option['edu_name']) ? 'selected' : ''}} required> {{$option['edu_name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('applicant_edu_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-1">

                                        </div>
                                    </div>

                                    <br>


                                    <!-- Mobile & Email -->
                                    <div class="row">
                                        <div class="col-sm-2 " style="text-align: left;">
                                            <label for="physically_handicapped"><b>Physically Handicapped</b></label>
                                            <label class="rq" id="physically_handicappederror" style="display:none"><b>* Please choose yes or no</b></label>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div style="display:flex; ">
                                                <div style="margin-right:50px;">
                                                    <label class="radio-inline" for="yes"><b>
                                                            <input type="radio" class="  @error('physically_handicapped') is-invalid @enderror" id="Yes" name="physically_handicapped" value="yes" {{ old('Yes') == 'yes' ? 'checked' : '' }}>Yes</b></label><br>
                                                </div>
                                                <div>
                                                    <label class="radio-inline" for="no"><b>
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


                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="applicant_email_id"><b>Email Id</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <input type="email" class="@error('applicant_email_id') is-invalid @enderror form-control" placeholder="" id="applicant_email_id" name="applicant_email_id" value="{{ old('applicant_email_id') }}">
                                            @error('applicant_email_id')
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
                                        <div class="col-sm-2 " style="text-align: left;">
                                            <label for="caste"><b>Caste</b></label>
                                            <label class="rq" id="caste" style="display:none"><b>* Please choose your caste</b></label>
                                        </div>
                                        <div class="col-sm-10 ">
                                            <div style="display:flex;">

                                                <label class="radio-inline" for="General"><b>
                                                        <input type="radio" class="  @error('caste') is-invalid @enderror" id="General" name="caste" value="General" {{ old('General') == 'General' ? 'checked' : '' }} checked>General &nbsp;&nbsp;&nbsp;</b></label><br>

                                                <br>
                                                <div>
                                                    <label class="radio-inline" for="OBC"><b>
                                                            <input type="radio" class=" @error('caste') is-invalid @enderror" id="OBC" name="caste" value="OBC" {{ old('OBC') == 'OBC' ? 'checked' : '' }}>OBC&nbsp;&nbsp;&nbsp;</b></label><br>
                                                </div>
                                                <br>
                                                <div>
                                                    <label class="radio-inline" for="ST"><b>
                                                            <input type="radio" class=" @error('caste') is-invalid @enderror" id="ST" name="caste" value="ST" {{ old('ST') == 'ST' ? 'checked' : '' }}>ST&nbsp;&nbsp;&nbsp;</b></label><br>
                                                </div>
                                                <br>
                                                <div>
                                                    <label class="radio-inline" for="SC"><b>
                                                            <input type="radio" class=" @error('caste') is-invalid @enderror" id="SC" name="caste" value="SC" {{ old('SC') == 'SC' ? 'checked' : '' }}>SC&nbsp;&nbsp;&nbsp;</b></label><br>
                                                </div>
                                                <br>
                                                <div>
                                                    <label class="radio-inline" for="OBC Meitei"><b>
                                                            <input type="radio" class=" @error('caste') is-invalid @enderror" id="OBC Meitei" name="caste" value="OBC Meitei" {{ old('OBC Meitei') == 'OBC Meitei' ? 'checked' : '' }}>OBC Meitei&nbsp;&nbsp;&nbsp;</b></label><br>
                                                </div>
                                                <br>
                                                <div>
                                                    <label class="radio-inline" for="OBC Pangal"><b>
                                                            <input type="radio" class=" @error('caste') is-invalid @enderror" id="OBC Pangal" name="caste" value="OBC Pangal" {{ old('OBC Pangal') == 'OBC Pangal' ? 'checked' : '' }}>OBC Pangal&nbsp;&nbsp;&nbsp;</b></label><br>
                                                </div>


                                            </div>
                                            @error('caste')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    <!-- Identification Mark -->

                                    <br>

                                    <!-- Address Details -->
                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-2">Address Details :</div>
                                        <div class="col-sm-10">

                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align:left;font-size:13px;font-weight:bold;color:#6c757d"><u>Present Address</u></div>
                                        <div class="col-sm-10" style="text-align: left;">
                                            <hr>
                                        </div>
                                    </div>
                                    <!-- Present Address -->
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="emp_addr_lcality"><b>Locality</b></label>
                                            <label class="rq" id="emp_addr_lcalityerror" style="display:none"><b>* Please Enter Address</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <textarea class="form-control  @error('emp_addr_lcality') is-invalid @enderror" onblur="fieldValidate('emp_addr_lcality')" id="emp_addr_lcality" rows="2" placeholder="" name="emp_addr_lcality" value="{{ old('emp_addr_lcality') }}" style="text-transform:uppercase"></textarea>
                                            @error('emp_addr_lcality')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-3" style="text-align:left;">

                                            <label for="district"><b>District</b></label>
                                            <label class="rq" id="emp_addr_districterror" style="display:none"><b>* Please Select District Name</b></label>

                                        </div>
                                        <!-- district -->
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('emp_addr_district') is-invalid @enderror" onblur="fieldValidate('emp_addr_district')" aria-label="Default select example" id="emp_addr_district" name="emp_addr_district">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($District as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['district_name_english'] ? 'selected' : ''}} required> {{$option['district_name_english']}}</option>
                                                @endforeach
                                            </select>
                                            @error('emp_addr_district')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                            <!--   <input placeholder="District" class="form-control" id="emp_addr_district" name="emp_addr_district" value=""> -->
                                        </div>


                                        <div class="col-sm-1">

                                        </div>

                                    </div>
                                    <br>
                                    <!-- sub division -->
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="emp_addr_subdiv"><b>Sub Division</b></label>

                                        </div>

                                        <div class="col-sm-3">
                                            <select class="form-select  @error('emp_addr_subdiv') is-invalid @enderror" aria-label="Default select example" id="emp_addr_subdiv" name="emp_addr_subdiv">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($subDiv as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['sub_division_name'] ? 'selected' : ''}} required> {{$option['sub_division_name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('emp_addr_subdiv')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror
                                            <!--    <input placeholder="Sub Division" class="form-control" id="emp_addr_subdiv" name="emp_addr_subdiv" value="">-->
                                        </div>


                                        <div class="col-sm-1">

                                        </div>
                                        <!-- Assemply Constituency -->

                                    </div>
                                    <br>
                                    <!-- State -->
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="emp_state"><b>State</b></label>
                                            <label class="rq" id="emp_stateerror" style="display:none"><b>* Please Select State Name</b></label>


                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('emp_state') is-invalid @enderror" onblur="fieldValidate('emp_state')" aria-label="Default select example" id="emp_state" name="emp_state">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($stateDetails as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['state_name'] ? 'selected' : ''}} required> {{$option['state_name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('emp_state')
                                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                            @enderror

                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <!-- Pincode -->
                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="emp_pincode"><b>Pin Code</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                        <input type="text" placeholder="Pincode." class="form-control @error('emp_pincode') is-invalid @enderror" id="emp_pincode" name="emp_pincode" value="{{ old('emp_pincode') }}" maxlength="6">
                                            <div id="error-message" class="text-danger"></div>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <div class="row">
                                        <!-- Checkbox is needed here to make present address same as Permanant Address -->

                                        <div class="col-sm-2" style="text-align: ileft;font-size:13px;font-weight:bold;color:#6c757d">
                                            <input type="checkbox" id="presentAddressSamecheckbox" name="presentAddressSamecheckbox" value="presentAddressSamecheckbox"> &nbsp;&nbsp; Same as above address.
                                            &nbsp;&nbsp;&nbsp; <u>Permanent Address</u>
                                        </div>
                                        <div class="col-sm-10" style="text-align: left;">
                                            <hr>
                                        </div>
                                    </div>







                                    <!-- Permanant Address -->
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align:left;">

                                            <label for="emp_addr_lcality_ret"><b>Locality</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <textarea class="form-control  @error('emp_addr_lcality_ret') is-invalid @enderror" id="emp_addr_lcality_ret" rows="2" placeholder="Present Address" name="emp_addr_lcality_ret" value="{{ old('emp_addr_lcality_ret') }}" style="text-transform:uppercase"></textarea>
                                            @error('emp_addr_lcality_ret')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="emp_addr_district_ret"><b>District</b></label>

                                        </div>
                                        <!-- district -->
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('emp_addr_district_ret') is-invalid @enderror" aria-label="Default select example" id="emp_addr_district_ret" name="emp_addr_district_ret">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($District as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['district_name_english'] ? 'selected' : ''}}> {{$option['district_name_english']}}</option>
                                                @endforeach


                                            </select>
                                            @error('emp_addr_district_ret')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <!--    <input  placeholder="District" class="form-control" id="emp_addr_district_ret" name="emp_addr_district_ret" value="">-->
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    <!-- sub division -->
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="emp_addr_subdiv_ret"><b>Sub Division</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select @error('emp_addr_subdiv_ret') is-invalid @enderror" aria-label="Default select example" id="emp_addr_subdiv_ret" name="emp_addr_subdiv_ret">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($subDiv as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['sub_division_name'] ? 'selected' : ''}}> {{$option['sub_division_name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('emp_addr_subdiv_ret')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <!--  <input  placeholder="Sub Division" class="form-control" id="emp_addr_subdiv_ret" name="emp_addr_subdiv_ret" value=""> -->
                                        </div>
                                        <div class="col-sm-1">

                                        </div>

                                    </div>
                                    <br>

                                    <!-- State -->
                                    <div class="row">
                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="emp_state_ret"><b>State</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select @error('emp_state_ret') is-invalid @enderror" aria-label="Default select example" id="emp_state_ret" name="emp_state_ret">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($stateDetails as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['state_name'] ? 'selected' : ''}}> {{$option['state_name']}}</option>
                                                @endforeach
                                            </select>
                                            @error('emp_state_ret')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <!--   <input placeholder="State" class="form-control" id="emp_state_ret" name="emp_state_ret" value="">-->
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <!-- Pincode -->
                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="emp_pincode_ret"><b>Pin Code</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                        <input type="text" placeholder="Pincode." class="form-control @error('emp_pincode_ret') is-invalid @enderror" id="emp_pincode_ret" name="emp_pincode_ret" value="{{ old('emp_pincode_ret') }}" maxlength="6">
                                            <div id="error-message2" class="text-danger"></div>
                                        </div>
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>

                                    <!-- Post Proposed details -->
                                    <div class="row" id="dividerDiv">
                                        <div class="col-sm-2">Post Proposed for Appoinment</div>
                                        <div class="col-sm-10">

                                        </div>
                                    </div>
                                    <br>

                                    <br>
                                    <!-- Applied Post and Grade  -->
                                    <div class="row">

                                        <div class="col-sm-2" style="text-align: left;">

                                            <label for="post_applied"><b>Post Applied</b></label>
                                            <label class="rq" id="applicant_desig_iderror" style="display:none"><b>* Please select a post</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select  @error('applicant_desig_id') is-invalid @enderror " onblur="fieldValidate('applicant_desig_id')" aria-label="Default select example" id="applicant_desig_id" name="applicant_desig_id">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($post as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['desig_name'] ? 'selected' : ''}} required> {{$option['desig_name']}}</option>

                                                @endforeach
                                            </select>
                                            @error('applicant_desig_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-1">

                                        </div>
                                        <div class="col-sm-3" style="text-align: left;">

                                            <label for="grade"><b>Grade</b></label>
                                            <label class="rq" id="applicant_gradeerror" style="display:none"><b>* Please select a Grade</b></label>

                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-select @error('applicant_grade') is-invalid @enderror" onblur="fieldValidate('applicant_grade')" aria-label="Default select example" id="applicant_grade" name="applicant_grade">
                                                <option value="" selected disabled>Select</option>
                                                @foreach($grades as $option)
                                                <option value="{{$option['id']}}" {{$option['id'] == $option['grade_name'] ? 'selected' : ''}} required> {{$option['grade_name']}}</option>

                                                @endforeach
                                            </select>
                                            @error('applicant_grade')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror

                                        </div>

                                        <div class="col-sm-1">

                                        </div>
                                    </div>
                                    <br>
                                    <!-- <div>

                                        <p style="font-weight: bold;">I hereby declare that I can be transfer to any department.</p>
                                        <label class="rq" id="accept_transfererror" style="display:none"><b>* Please select Yes or No</b></label>
                                        <div style="display:flex; ">
                                            <div style="margin-right:50px;">
                                                <label class="radio-inline" for="yes"><b>
                                                        <input type="radio" class=" @error('accept_transfer') is-invalid @enderror" id="Yes" name="accept_transfer" value="yes" {{ old('Yes') == 'yes' ? 'checked' : '' }} checked>Yes</b></label><br>
                                            </div>
                                            <div>
                                                <label class="radio-inline" for="no"><b>
                                                        <input type="radio" class=" @error('accept_transfer') is-invalid @enderror" id="No" name="accept_transfer" value="no" {{ old('No') == 'no' ? 'checked' : '' }}>No</b></label><br>
                                            </div>

                                        </div>
                                        @error('accept_transfer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> -->
                                    <br>



                                    <hr>
                                    <div class="row" style="text-align: right;">
                                        <div class="col-sm-12">


                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-success btn-sm" id="desc_role_next_btn">Save</button>

                                            <!-- <a style="margin-bottom: 3px;" onclick="return confirm('Are You Sure?')" href="{{ route('submit-forms') }}" id="submit_form1" class="btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true">
                                                Submit
                                            </a> -->

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

    <!--  Mobile no. update model -->

    <!--  email id. update model -->

    <!--  address locality update model -->

    <!--  address after retirement update model -->

    <!--  sub division update model -->


    <!--  District update model -->


    <!--  Assembly constituency update model -->


    <!-- after retirement -->
    <!--  address locality After retirement update model -->

    <!--  sub division After retirement update model -->

    <!--  District After Retirement update model -->

    <!--  Assembly constituency After Retirement update model -->

    <!--  Bank Name update model -->


    <!--  Bank Branch update model -->


    <!--  Pay Scale update model -->

    <!--  Name update model -->

    <!--  Name update model -->

    <!--  emp expire dt update model -->

    <!--  emp entry dt update model -->

    <!--  emp designation dt update model -->

    <!--  emp designation dt update model -->

    <!--  emp height update model -->

    <!--  emp height update model -->

    <!--  emp bank name update model -->

    <!--  emp da rate update model -->

    <!--  emp pay commision details update model -->

    <!--  emp commutation rate update model -->


    <!--  emp identification mark update model -->

    <!--  emp is gazetted update model -->


    <!--  emp edit Address details -->

    <!-- district -->

    <!-- assem-const -->


    <!-- sub-dividion -->


    <!--  emp Bank details -->



    <!--  emp Pay details -->

    <!--  other details -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>

    {{-- toastr js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script> -->




    <script nonce="{{ csp_nonce() }}">
        function getEmployee() {
            var ein = document.getElementById('ein').value;
            document.descriptiveRoleForm.action = "update_second_applicant_data";
            
            document.descriptiveRoleForm.submit();

        }
   
     

        $(document).ready(function() {
            $("#presentAddressSamecheckbox").on('change', function() {
                var checkStat = $('#presentAddressSamecheckbox').is(':checked');
                console.log(checkStat)

                if (checkStat == true) {
                    // get address before retirement
                    var localAddress = $('#emp_addr_lcality').val();
                    var district = $('#emp_addr_district :selected').val();
                    var suvDivision = $('#emp_addr_subdiv :selected').val();
                    // var assemConst = $('#emp_addr_assem_cons_model_field :selected').val();
                    var state = $('#emp_state :selected').val();
                    var pin = $('#emp_pincode').val();
                    // set address after retirement same as address before retirement
                    $('#emp_addr_lcality_ret').val(localAddress);
                    $('#emp_addr_district_ret').val(district);
                    $('#emp_addr_subdiv_ret').val(suvDivision);
                    //$('#emp_addr_assem_cons_retr').val(assemConst);
                    $('#emp_state_ret').val(state);
                    $('#emp_pincode_ret').val(pin);
                } else {
                    // set address after retirement same as address before retirement
                    $('#emp_addr_lcality_ret').val("");
                    $('#emp_addr_district_ret').val("");
                    $('#emp_addr_subdiv_ret').val("");
                    //$('#emp_addr_assem_cons_retr').val("");
                    $('#emp_state_ret').val("");
                    $('#emp_pincode_ret').val("");
                }

            });
        });



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


        // $("#applicant_mobile").keydown(function(event) {
        //     k = event.which;
        //     if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8) {
        //         if ($(this).val().length == 10) {
        //             if (k == 8) {
        //                 return true;
        //             } else {
        //                 event.preventDefault();
        //                 return false;

        //             }
        //         }
        //     } else {
        //         event.preventDefault();
        //         return false;
        //     }

        // });

        // $("#emp_pincode").keydown(function(event) {
        //   k = event.which;
        //   if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8) {
        //     if ($(this).val().length == 6) {
        //       if (k == 8) {
        //         return true;
        //       } else {
        //         event.preventDefault();
        //         return false;

        //       }
        //     }
        //   } else {
        //     event.preventDefault();
        //     return false;
        //   }

        // });

        // $("#emp_pincode_ret").keydown(function(event) {
        //   k = event.which;
        //   if ((k >= 48 && k <= 57) || (k >= 96 && k <= 105) || k == 8) {
        //     if ($(this).val().length == 6) {
        //       if (k == 8) {
        //         return true;
        //       } else {
        //         event.preventDefault();
        //         return false;

        //       }
        //     }
        //   } else {
        //     event.preventDefault();
        //     return false;
        //   }

        // });



        // After form loads focus will go to User id field.
        function firstfocus() {
            if (document.descriptiveRoleForm.ein.value == '')
                document.descriptiveRoleForm.ein.focus();
            return true;
        }



        //formValidation code are written below  

        function formValidation() {
            event.preventDefault(); //prevent from submitting is error found

            //event prevent form submission is remove, allowing to save
            if (education_validation() == false)
                return;
            if (name_validation() == false)
                return;
            if (relation_validation() == false)
                return;
            if (mobile_validation() == false)
                return;
            if (causeofdeath_validation() == false)
                return;
            if (district_validation() == false)
                return;
            if (state_validation() == false)
                return;
            if (desig_validation() == false)
                return;
            if (grade_validation() == false)
                return;
            if (dob_validation() == false)
                return;
            document.descriptiveRoleForm.action = "save-proforma-details2ndAppl";
            document.descriptiveRoleForm.submit();
        }

        //This function helps to show required field on runtime
        function fieldValidate(field) {
            if (document.getElementById('' + field).value == "") {
                document.getElementById(field + 'error').style.display = "inline";
            }
        }

        ////////////////////////FUNCTIONS BELOW///////////////////////////////////////////

        //Applicant Name Validation
        function name_validation() {
            if (document.descriptiveRoleForm.applicant_name.value == "") {
                alert("Applicant Name should not be empty");
                document.descriptiveRoleForm.applicant_name.focus();
                return false;
            }
            // document.descriptiveRoleForm.relationship.focus();
            // return true;
        }

        //relationship validation
        function relation_validation() {
            if (document.descriptiveRoleForm.relationship.value == "") {
                alert("Relationship should not be empty.....");
                document.descriptiveRoleForm.relationship.focus();
                return false;
            }
            // document.descriptiveRoleForm.applicant_dob.focus();
            // return true;
        }

        //Select option of educational qualification
        function education_validation() {
            if (document.descriptiveRoleForm.applicant_edu_id.value == "") {
                alert("Please Select Applicant Educational Qualification");
                document.descriptiveRoleForm.applicant_edu_id.focus();
                return false;
            }
            // document.descriptiveRoleForm.applicant_mobile.focus();
            // return true;
        }

        function dob_validation() {
            if (document.descriptiveRoleForm.applicant_dob.value == "") {
                alert("Applicant DOB should not be empty");
                document.descriptiveRoleForm.applicant_dob.focus();
                return false;
            }
            // document.descriptiveRoleForm.applicant_edu_id.focus();
            // return true;
        }
        //Mobile
        function mobile_validation() {
            if (document.descriptiveRoleForm.applicant_mobile.value == "") {
                alert("Please Enter Applicant Mobile No.");
                document.descriptiveRoleForm.applicant_mobile.focus();
                return false;
            }
            // document.descriptiveRoleForm.applicant_email_id.focus();
            // return true;
        }

        function causeofdeath_validation() {
            if (document.descriptiveRoleForm.deceased_causeofdeath.value == "") {
                alert("Please Enter Cause of Death");
                document.descriptiveRoleForm.deceased_causeofdeath.focus();
                return false;
            }
            // document.descriptiveRoleForm.deceased_dob.focus();
            // return true;
        }

        function district_validation() {
            if (document.descriptiveRoleForm.emp_addr_district.value == "") {
                alert("Please Select District");
                document.descriptiveRoleForm.emp_addr_district.focus();
                return false;
            }
            // document.descriptiveRoleForm.emp_addr_subdiv.focus();
            // return true;
        }

        function state_validation() {
            if (document.descriptiveRoleForm.emp_state.value == "") {
                alert("Please Select State");
                document.descriptiveRoleForm.emp_state.focus();
                return false;
            }
            // document.descriptiveRoleForm.emp_pincode.focus();
            // return true;
        }

        function desig_validation() {
            if (document.descriptiveRoleForm.applicant_desig_id.value == "") {
                alert("Please Select a Post");
                document.descriptiveRoleForm.applicant_desig_id.focus();
                return false;
            }
            // document.descriptiveRoleForm.applicant_grade.focus();
            // return true;
        }

        function grade_validation() {
            if (document.descriptiveRoleForm.applicant_grade.value == "") {
                alert("Please Select Grade/Group");
                document.descriptiveRoleForm.applicant_grade.focus();
                return false;
            }

        }
    </script>

    <!-- @if(Session::has('errorAlert'))
 <script type="text/javascript">
    swal({
        title:'Oops!',
        text:"{{Session::get('errorAlert')}}",
        type:'error',
        timer:5000
    }).then((value) => {
      
    }).catch(swal.noop);
</script>
@endif -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
    </script> -->

    @endsection