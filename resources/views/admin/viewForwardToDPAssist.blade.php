<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')

<link href="{{ asset('assets/css/viewstartemp.css') }}" rel="stylesheet">
<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ __('Submitted Applicantions') }}</h3>
                        </div>
                        <div class="col-6">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                                action="{{ url('ddo-assist/viewForwardToDPAssistSearch') }}"
                                enctype="multipart/form-data" class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-10 marginright_textright">
                                        <input type="text" class="form-control marginright2"
                                            placeholder="Search by EIN NO." name="searchItem">
                                    </div>
                                    <div class="col-2 margin_textalign">
                                        <button class="btn btn-outline-secondary" type="submit"
                                            id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <!-- saved succes message -->
                    @if(session()->has('message'))
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                    @endif
                    <!-- saved erroe message -->
                    @if(session()->has('error_message'))
                    <div class="alert alert-danger">
                        {{ session()->get('error_message') }}
                    </div>
                    @endif

                    <!-- {{ __('You are logged in!') }} -->

                    <!-- CMIS Started employee list table -->
                    <!-- <div id="started_emp_table" style="display:show"><b>Started Employee List :</b><br> -->
                    <b class="color">List of applicants whose details are Completed and Incomplete:</b>
                    <hr>
                    <p>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Sl.No.</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">DOE</th>
                                    <th>Submitted Date</td>
                                    <th>Applicant Name</td>
                                    <th>DOB</td>
                                    <th scope="col">status</th>
                                    <th scope="col">Mode</th>
                                    <th scope="col" colspan="4" class="textcenter">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="10" class="textcenter">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>
                                @else

                                @foreach($empList as $key => $data)

                                <?php
                                $uploader=$data->uploader_role_id;
                                if($uploader==77){
                                    $mode="Online";
                                }else{
                                    $mode="Offline";
                                }

                                ?>

                                <tr>
                                <td>{{ $empList->firstItem() + $key }}</td>
                                    <td>{{$data->ein}}</td>
                                    <td>{{$data->deceased_emp_name}}</td>
                                    <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    <td>{{$data->appl_date ? \Carbon\Carbon::parse($data->appl_date)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    <td>{{$data->applicant_name}}</td>
                                    <td>{{$data->applicant_dob ? \Carbon\Carbon::parse($data->applicant_dob)->format('d/m/Y') : 'NA'}}
                                    </td>
                                    <td>{{$data->status}}</td>
                                    <td>{{$mode}}</td>



                                    @if($data->formSubStat == "submitted")
                                    <td class="textright">
                                        <!-- <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true">View</a>
                                    <a href="{{ route('verifyPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true" onclick="return confirm('Are You Sure that the Applicant File is OK?')">Verify</a> -->


                                        <!-- capturing the ein at click instant -->
                                        <!-- @php
                                    $temp_array = [];
                                    $ein = $data->ein;
                                    $appl_no = $data->appl_number;
                                    $temp_array['ein'] = $ein;
                                    $temp_array['appl_no'] = $appl_no;
                                    @endphp -->
                                        <!-- <button class="btn btn-danger btn-sm" role="button" aria-disabled="true" style="border-radius:50%" id="edit_emp_name_btn" type="button" onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button> -->
                                        <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->

                                        <!-- <a href="{{ route('discard-started-employee', Crypt::encryptString($data->ein)) }}" class="btn btn-danger btn-sm" tabindex="-1" role="button" aria-disabled="true" onclick="return confirm('Are You Sure to Discard this Applicant?')">Discard</a> -->


                                    </td>
                                    @endif

                                    @if($data->formSubStat == "started")
                                    <!-- <td style="text-align:right">
                                    <a href="{{ route('Proforma_ApplicantDetails', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm" style="border-radius:50%" role="button" aria-disabled="true">Continue Entry</a>

                                    <a href="{{ route('discard-started-employee', Crypt::encryptString($data->ein)) }}" class="btn btn-danger btn-sm" style="border-radius:50%" role="button" aria-disabled="true" onclick="return confirm('Are You Sure to Discard this Applicant?')">Discard</a>


                                </td> -->
                                    @endif
                                    @if($data->formSubStat == "verified")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}"
                                            class="btn btn-success btn-sm borderradius" role="button"
                                            aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-danger btn-sm borderradius" role="button"
                                            aria-disabled="true" id="edit_emp_name_btn" type="button"
                                            onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>
                                        <!-- data-bs-toggle="modal" data-bs-target="#remarkModal"-->
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp

                                        <button class="btn btn-danger btn-sm borderradius" role="button"
                                            aria-disabled="true" id="edit_emp_name_btn" type="button"
                                            onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward to DP
                                            Assistant</button>




                                        <a href="{{ route('discard-started-employee', Crypt::encryptString($data->ein)) }}"
                                            class="btn btn-danger btn-sm borderradius" role="button"
                                            aria-disabled="true"
                                            onclick="return confirm('Are You Sure to Discard this Applicant?')">Reject</a>


                                    </td>


                                    @endif

                                </tr>
                                <!-- MODAL FADE CODE BELOW FOR REVERT -->

                                <div class="modal fade" id="remarkModal" tabindex="-1"
                                    aria-labelledby="remarkModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form name="revertForm"
                                            action="{{ route('revertDetailsFromADNodal', Crypt::encryptString($data->ein)) }}"
                                            method="Post">
                                            @csrf
                                            <!-- @method('GET') -->


                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="name"><b>Select a reason: </b></label>
                                                    <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                    <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                    <input type="hidden" class="form-control" id="ein" name="ein"
                                                        value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                    <select class="form-select" aria-label="Default select example"
                                                        id="emp_addr_district_ret" name="emp_addr_district_ret">
                                                        <option selected>Select</option>
                                                        @foreach($Remarks as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required>
                                                            {{$option['probable_remarks']}}</option>
                                                        @endforeach


                                                    </select><br>

                                                    <label for="remark"><b>Any Description (Less than 250
                                                            words)</b></label>
                                                    <input type="text" placeholder="Remark" class="form-control"
                                                        id="remark_data" name="remark" value="" required>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button id="BtnSvData" type="submit"
                                                        class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- MODAL FADE CODE BELOW FOR FORWARD -->

                                <div class="modal fade" id="remarkForwardModal" tabindex="-1"
                                    aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form name="forwardForm"
                                            action="{{ route('forwardDetailsFromDPAssistToDPNodal', Crypt::encryptString($data->ein)) }}"
                                            method="Post">
                                            @csrf
                                            <!-- @method('GET') -->


                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="remarkForwardModalTitle">Remark</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                  
                                                  



                                                    <label for="remark"><b>Select a reason: </b></label>
                                                    <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                    <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                    <input type="hidden" class="form-control" id="ein" name="ein"
                                                        value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                    <select class="form-select" aria-label="Default select example"
                                                        id="remark" name="remark">
                                                        <option selected>Select</option>
                                                        @foreach($Remarks as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required>
                                                            {{$option['probable_remarks']}}</option>
                                                        @endforeach


                                                    </select><br>

                                                    <label for="remark_details"><b>Any Description (Less than 250
                                                            words)</b></label>
                                                    <input type="text" placeholder="Remark" class="form-control"
                                                        id="remark_details" name="remark_details" value="">

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button id="BtnSvData" type="submit"
                                                        class="btn btn-success">Save</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @endforeach

                                @endif

                            </tbody>
                        </table>
                    </div>
                    @if($empList != null)
                    <div class="row">
                        {!! $empList->links() !!}
                    </div>
                    @endif
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
</div>





@endsection

<script type="text/javascript">
function setRevertData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure that the Applicant File is NOT OK?')) {
        return;
    }
    $("#remarkModal").modal('show');
    let form = document.forms['revertForm'];
    // form.appl_number.value = temp_array['appl_number'];
    form.ein.value = temp_array['ein'];
    console.log(temp_array['ein'])

}

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
</script>