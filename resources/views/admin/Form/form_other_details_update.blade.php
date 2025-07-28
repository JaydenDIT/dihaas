<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')
@php $form_no = 4; @endphp
<link href="{{ asset('assets/css/others_details.css') }}" rel="stylesheet">
<div class="container">
<br>
    <!-- @include('admin.form_menu_buttons') -->
    @include('admin.progress.form_progress_update')

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
                                $deceasedDate = new DateTime($empDetails['deceased_doe']);

                                // Add 6 months to the date
                                $sixMonthsLater = $deceasedDate->modify('+6 months');

                                // Display the result in red
                                echo "<p class='colorred'>Last date of submission: " . $sixMonthsLater->format('Y-m-d') . "</p>";
                            ?>
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
                        @if(session()->has('errormessage'))
                            <div class="alert alert-danger">
                                {{ session()->get('errormessage') }}
                            </div>
                            @endif
                            <br>
                            <div id="form_header" class="textalign_fontsize"><b> Complete Form Submission! </b></div>
                        <hr>
                        <!--  -->
                        <br>
                        <!-- url('save-other-details') -->
                       
                        @if($status == 1)
                        <div class="row">
                        <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                    <h5><span class="color">( Forms Already Submitted ! )</span></h5>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 textcenter">
                       
                       <a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                   Download Form As Pdf
                               </a>
                       </div>
                        @else
                        <div class="row">
                            <div class="col-sm-2"></div> 
                            <div class="col-sm-10">
                                <p>
                                    <b>
                                        <h5><span class="colorred">Notes :</span></h5>
                                        <ul class="color">
                                            <li>You can submit only after saving all forms (1-3) !</li>
                                            <li>Please do check your saved information before submission !</li>
                                            <li>You can check and update saved information by selecting above form (1-3) or simply by clicking back button before submission !</li>
                                            <li>Once submitted HOD Assist can't edit submitted information !</li>

                                        </ul>
                                    </b>
                                </p>
                            </div>
                        </div>
                       
                        <hr>
                        <div class="row">
                            <div class="col-sm-12 textcenter">
                            
                            <a href="{{ route('create-applicant-files-dihas') }}" class="btn btn-success btn-sm">Back</a>&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('submit-forms-update') }}" id="submit_form_update" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                            Final Submit
                                        </a>
                            </div>
                        </div>
                        @endif
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var discardLink = document.getElementById("submit_form_update");

        discardLink.addEventListener("click", function(event) {
            var confirmed = confirm("Are You Sure?");
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
    </script>
    @endsection