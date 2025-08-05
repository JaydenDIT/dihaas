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
                            $deceasedDate = new DateTime($empDetails['deceased_doe']);

                            // Add 6 months to the date
                            $sixMonthsLater = $deceasedDate->modify('+6 months');

                            // Display the result in red
                            echo "<p class='colorred'>Last date of submission: " . $sixMonthsLater->format('Y-m-d') . "</p>";
                            ?>
                        </div>
                        <hr>
                        <div class="col-12 textleft">
                            <b>EIN: {{ $empDetails['ein'] }} &nbsp; Deceased Name : {{ $empDetails['deceased_emp_name'] }} &nbsp; D.O.E: {{date('Y-m-d', strtotime( $empDetails['deceased_doe'])) }} &nbsp;&nbsp;&nbsp; Applicant Name : {{ $empDetails['applicant_name'] }}</b>
                        </div>
                        <div class="col-6 textright">
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

                        <div id="form_header" class="textalign_fontsize"><b> Complete Form Submission! </b></div>
                        <hr>
                        <!--  -->
                        <br>
                        <!-- url('save-other-details') -->
                        @if($status == 7 && $empDetails['uploader_role_id']==77)
                        <div class="row">
                            <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                        <h5><span class="color">( Forms Already Submitted ! )</span></h5>
                                    </b>
                                </p>
                            </div>
                        </div>
                        @elseif($status == 1)
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

                            @php
                            $temp_array = [];
                            $ein = $empDetails['ein'];
                            $appl_no = $empDetails['appl_number'];
                            $temp_array['ein'] = $ein;
                            $temp_array['appl_no'] = $appl_no;
                            @endphp
                            <button class="btn btn-success btn-sm stylewidth120" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward</button>


                            &nbsp; &nbsp;&nbsp;<a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                Download Form As Pdf
                            </a>
                        </div>



                        <div class="modal fade" id="remarkForwardModal" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form name="forwardForm" action="{{ route('forwardDetailsFrom', Crypt::encryptString($empDetails['ein'])) }}" method="Post">
                                    @csrf
                                    <!-- @method('GET') -->


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="remark"><b>Select a Reason: </b></label>
                                            <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $empDetails['ein'] == null ? null : $empDetails['ein'] }}">
                                            <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                <option selected>Select</option>
                                                @foreach($RemarksApprove as $option)
                                                <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                @endforeach


                                            </select><br>

                                            <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                            <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @elseif ($status == 0)
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
                                <a href="{{ URL::previous() }}" class="btn btn-success btn-sm">Back</a>&nbsp;&nbsp;&nbsp;
                                <a href="{{ route('submit-forms') }}" id="submit_form9" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                    Submit
                                </a>
                            </div>


                        </div>
                        @elseif ($status == 2)

                        @if ($getUser->name != $empDetails['received_by'])
                        <div class="row">
                            <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                        <h5><span class="color">( Forms Already Forwarded ! )</span></h5>
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
                        <div class="col-sm-12 textcenter">

                            @php
                            $temp_array = [];
                            $ein = $empDetails['ein'];
                            $appl_no = $empDetails['appl_number'];
                            $temp_array['ein'] = $ein;
                            $temp_array['appl_no'] = $appl_no;
                            @endphp
                            <button class="btn btn-success btn-sm stylewidth120" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward</button>


                            &nbsp; &nbsp;&nbsp;<a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                Download Form As Pdf
                            </a>
                        </div>
                        <div class="modal fade" id="remarkForwardModal" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form name="forwardForm" action="{{ route('forwardDetailsFromHOD', Crypt::encryptString($empDetails['ein'])) }}" method="Post">
                                    @csrf
                                    <!-- @method('GET') -->


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="remark"><b>Select a Reason: </b></label>
                                            <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $empDetails['ein'] == null ? null : $empDetails['ein'] }}">
                                            <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                <option selected>Select</option>
                                                @foreach($RemarksApprove as $option)
                                                <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                @endforeach


                                            </select><br>

                                            <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                            <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @endif

                        @elseif ($status == 3)
                        @if ($getUser->name != $empDetails['received_by'])
                        <div class="row">

                            <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                        <h5><span class="color">( Forms Already Forwarded ! )</span></h5>
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
                        <div class="col-sm-12 textcenter">

                            @php
                            $temp_array = [];
                            $ein = $empDetails['ein'];
                            $appl_no = $empDetails['appl_number'];
                            $temp_array['ein'] = $ein;
                            $temp_array['appl_no'] = $appl_no;
                            @endphp
                            <button class="btn btn-success btn-sm stylewidth120" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward</button>


                            &nbsp; &nbsp;&nbsp;<a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                Download Form As Pdf
                            </a>
                        </div>
                        <div class="modal fade" id="remarkForwardModal" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form name="forwardForm" action="{{ route('forwardDetailsFromADToADNodal', Crypt::encryptString($ein)) }}" method="Post">
                                    @csrf
                                    <!-- @method('GET') -->


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="efileAD"><b>eFile number</b></label>
                                            <input type="text" placeholder="efileAD" class="form-control" id="efile_ad" name="efile_ad" value="" required>
                                            <br>
                                            <label for="ad_efile_link"><b>Browse eFile:</b></label>

                                            <input type="file" class="form-control" id="ad_efile_link" name="ad_efile_link" accept="application/pdf" value="" required>
                                            <br>

                                            <label for="remark"><b>Select a reason: </b></label>
                                            <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $ein == null ? null : $ein }}">
                                            <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                <option selected>Select</option>
                                                @foreach($RemarksApprove as $option)
                                                <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                @endforeach


                                            </select><br>

                                            <label for="remark_details"><b>Any Description (Less than 250 words)</b></label>
                                            <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @endif

                        @elseif ($status == 4)
                        @if ($getUser->name != $empDetails['received_by'])
                        <div class="row">
                            <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                        <h5><span class="color">( Forms Already Forwarded ! )</span></h5>
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
                        <div class="col-sm-12 textcenter">

                            @php
                            $temp_array = [];
                            $ein = $empDetails['ein'];
                            $appl_no = $empDetails['appl_number'];
                            $temp_array['ein'] = $ein;
                            $temp_array['appl_no'] = $appl_no;
                            @endphp
                            <button class="btn btn-success btn-sm stylewidth120" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward</button>


                            &nbsp; &nbsp;&nbsp;<a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                Download Form As Pdf
                            </a>
                        </div>
                        <div class="modal fade" id="remarkForwardModal" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form name="forwardForm" action="{{ route('forwardDetailsFromADNodalToDPAssist', Crypt::encryptString($empDetails['ein'])) }}" method="Post">
                                    @csrf
                                    <!-- @method('GET') -->


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="remark"><b>Select a Reason: </b></label>
                                            <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $empDetails['ein'] == null ? null : $empDetails['ein'] }}">
                                            <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                <option selected>Select</option>
                                                @foreach($RemarksApprove as $option)
                                                <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                @endforeach


                                            </select><br>

                                            <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                            <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @endif
                        @elseif ($status == 5)
                        @if ($getUser->name != $empDetails['received_by'])
                        <div class="row">
                            <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                        <h5><span class="color">( Forms Already Forwarded ! )</span></h5>
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
                        <div class="col-sm-12 textcenter">

                            @php
                            $temp_array = [];
                            $ein = $empDetails['ein'];
                            $appl_no = $empDetails['appl_number'];
                            $temp_array['ein'] = $ein;
                            $temp_array['appl_no'] = $appl_no;
                            @endphp
                            <button class="btn btn-success btn-sm stylewidth120" role="button" aria-disabled="true" id="edit_emp_name_btn" type="button" onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward</button>


                            &nbsp; &nbsp;&nbsp;<a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                Download Form As Pdf
                            </a>
                        </div>
                        <div class="modal fade" id="remarkForwardModal" tabindex="-1" aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <form name="forwardForm" action="{{ route('forwardDetailsFromDPAssistToDPNodal', Crypt::encryptString($empDetails['ein'])) }}" method="Post">
                                    @csrf
                                    <!-- @method('GET') -->


                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label for="remark"><b>Select a Reason: </b></label>
                                            <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $empDetails['ein'] == null ? null : $empDetails['ein'] }}">
                                            <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                <option selected>Select</option>
                                                @foreach($RemarksApprove as $option)
                                                <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                @endforeach


                                            </select><br>

                                            <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                            <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="">

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @endif
                        @elseif ($status == 6)
                        @if ($getUser->name != $empDetails['received_by'])
                        <div class="row">
                            <div class="col-sm-12 color_textalign">
                                <p>
                                    <b>
                                        <h5><span class="color">( Forms Already Forwarded ! )</span></h5>
                                    </b>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 textcenter">

                            <a href="{{ route('downloadDetails') }}" target="_blank" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true">
                                Download Form As Pdf
                            </a>
                        </div>
                        @endif


                        @endif
                        <br>

                        @include('duties.taskLoader')

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script nonce="{{ csp_nonce() }}">
    function setForwardData(temp_array) {
        // console.log(temp_array['ein']);
        if (!confirm('Are You Sure that the Applicant File is OK ?')) {
            return;
        }
        $("#remarkForwardModal").modal('show');
        let form = document.forms['forwardForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])

    }














    document.addEventListener("DOMContentLoaded", function() {
        var discardLink = document.getElementById("submit_form9");

        if (discardLink) {
            discardLink.addEventListener("click", function(event) {
                var confirmed = confirm("Are You Sure?");
                if (!confirmed) {
                    event.preventDefault();
                }
            });
        }
    });
</script>






@endsection