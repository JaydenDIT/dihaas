<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')
<link href="{{ asset('assets/css/form_family_details.css') }}" rel="stylesheet">
@php $form_no = 2; @endphp
<div class="container">
<br>
    <div class="row justify-content-center">
        <!-- @include('admin.form_menu_buttons') -->
        <!-- progress -->
        @include('admin.progress.form_progress_entry')
        <br>
        <div class="col-md-12 margintop">
            <!-- Family Detail Form -->
            <div class="card displayshow" id="family_details_section">
                <div class="card-header">
                <div class="col-12 center">
                    <?php
                                // Convert the date string to a DateTime object
                                $deceasedDate = new DateTime($empDetails['deceased_doe']);

                                // Add 6 months to the date
                                $sixMonthsLater = $deceasedDate->modify('+6 months');

                                // Display the result in red
                                echo "<p class='color'>Last date of submission: " . $sixMonthsLater->format('Y-m-d') . "</p>";
                            ?>
                             </div>
                    </div>
                    <hr>
                    <div class="row">
                    <div class="col-12 center">
<span class="color">All Family members should be enter......</span>
<br>
                    </div>
                    <hr>
                    <div class="col-12 center">
<span class="color">All Family members should be enter......</span>
<br>
                    </div>
                    <hr>
                    <div class="col-12 textleft">
                            <b>EIN: {{$empDetails->ein }} &nbsp; Deceased Name : {{ $empDetails->deceased_emp_name }} &nbsp; D.O.E: {{date('Y-m-d', strtotime($empDetails->deceased_doe))}} &nbsp;&nbsp;&nbsp; Applicant Name : {{ $empDetails->applicant_name }}</b>

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
                        <div id="form_header" class="textcenter_fontsize"><b>Other Family Details</b></div>
                        <hr>
                        <!--  -->
                        <div id="form_body" class="displayshow">
                            <!-- ROW-1 -->
                            <form id="form_family_details" method="" action="" class="">
                                @csrf
                                <div class="valid-feedback">Valid.</div>
                                <div class="invalid-feedback">Please fill out this field.</div>
                                @if ($errors->any())
                                <div class="alert alert-warning">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if (Session::has('success'))
                                <div class="alert alert-info">
                                    <p>{{ Session::get('success') }}</p>
                                </div>
                                @endif
                                <div>
                                    <b>Add Family members:</b>
                                    <hr>
                                </div>
                                <!-- ein field -->
                                <input type="hidden" name="ein" class="form-control" />

                                <table class="table" id="multiForm">

                                    <tr>
                                        <th>Name</th>
                                        <th>D.O.B</th>
                                        <th>Gender</th>
                                        <th>Relation</th>

                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="applicant_name" class="form-control" value="{{ $empDetails->applicant_name }}" />
                                            <br>
                                            <input type="text" name="new_data[0][name]" class="form-control" value="" />
                                        </td>
                                        <td>
                                            <input type="text" name="dob" class="form-control" value="{{ $empDetails->applicant_dob }}" />
                                            <br>
                                            <input type="date" name="new_data[0][dob]" class="form-control" value="" />

                                        </td>
                                        <td>
                                            <?php

                                            use App\Models\ProformaModel;
                                            use App\Models\GenderModel;
                                            use App\Models\RelationshipModel;

                                            $ein =  session()->get('ein');
                                            $empDetails = ProformaModel::get()->where("ein", $ein)->first();
                                            $Gender = GenderModel::get()->where("id", $empDetails->sex)->first();
                                            $Relationship = RelationshipModel::get()->where("id", $empDetails->relationship)->first();

                                            ?>
                                            <input type="text" name="sex" class="form-control" value="{{ $Gender->sex }}" />
                                            <br>
                                            <!-- <input type="text" name="gender[0][gender]" class="form-control" /> -->
                                            <select class="form-select" aria-label="Default select example" name="new_data[0][gender]">
                                                <option Value="" selected>Select </option>
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                               
                                            </select>
                                        </td>
                                        <td>

                                            <input type="text" name="relationship" class="form-control" value="{{ $Relationship->relationship }}" />
                                            <br>
                                            <!-- <input type="text" name="new_data[0][relation]" class="form-control" value="" /> -->
                                            <select class="form-select" aria-label="Default select example" name="new_data[0][relation]">
                                                <option Value="" selected>Select </option>
                                                <option value="1">Wife</option>
                                                <option value="2">Husband</option>
                                                <option value="3">Son</option>
                                                <option value="4">Daughter</option>
                                            </select>
                                        </td>

                                        <td>

                                            <input type="button" name="add" value="Add" id="addRemoveIp" class="btn btn-outline-success">

                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="5"></td>
                                        <td>



                                        </td>
                                    </tr>
                                    <tr>

                                    </tr>


                                </table>

                                <div class="col-sm-12 textright">
                                    <div class="col-sm-12">

                                    <a href="{{ route('enterProformaDetails') }}" class="btn btn-success btn-sm">Back</a>&nbsp;&nbsp;&nbsp;


                                        <button type="submit" class="btn btn-success btn-sm" id="save_family_btn_fresh">Save As Draft</button>
                                        &nbsp;&nbsp;&nbsp;<a href="{{ route('create-applicant-files') }}" id="close" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true" >
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
    </div>
</div>

   
    @endsection