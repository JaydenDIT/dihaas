<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->

@extends('layouts.app')

@section('content')
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/select.css') }}" rel="stylesheet">

<?php $selected = session()->get('deptId') ?>
<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form method="POST" action="{{ route('selectDeptByDPNodal') }}">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <h3>{{ __('Select Department') }}</h3>
                            </div>
                            <!-- Department -->
                            <div class="row mb-3">
                                <label for="Departments"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="dept_id"
                                        name="dept_id">
                                        <option value="" selected>All Department</option>
                                        @foreach($deptListArray as $option)
                                        <option value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}"
                                            required {{($selected == $option['dept_id'])?'selected':''}}>
                                            {{$option['dept_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-9">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Submit') }}
                                    </button>

                                    <!-- @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif -->
                                </div>
                            </div>

                        </div>
                    </form>
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
                    <b class="color">List of forwarded Applicants for DIH Scheme</b>
                    <hr>
                    <p>

                    <div class="textright">

                        @if(empty($empListArray))

                        <button type="button" class="btn btn-success" disabled>Print
                        </button>
                        <a href="{{ route('submit_form.downloadPDFNODAL') }}" target=”_blank”><button
                                class="btn btn-success" disabled>Download Pdf</button></a>
                        @else
                        <button id="printButtonCard1" type="button" class="btn btn-success">Print </button>
                        <a href="{{ route('submit_form.downloadPDFNODAL') }}" class="btn btn-success"
                            target=”_blank”>Download Pdf</a>
                        @endif

                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed table-striped" id="table">

                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Sl.No.</th>
                                    <th scope="col">EIN</th>
                                    <th scope="col">Deceased Name</th>
                                    <th scope="col">DOE</th>
                                    <th scope="col">Recruitment Rules</th>
                                    <th>Applicant Name</td>
                                    <th>File put up By AD</th>
                                    <th>File put up By DP</th>
                                    <th scope="col">Remark</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">status</th>
                                    <th scope="col" colspan="4" class="textcenter">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="11" class="textcenter">
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
                                    <td>
                                        @if(!empty($data->pdf_file))
                                            <a href="{{ route('viewFileForwardByHODAssistant', ['filename' => $data->pdf_file]) }}"
                                                   target="_blank">
                                                       {{ (strlen($data->pdf_file) > 10) ? substr($data->pdf_file, 0, 10)."..." : $data->pdf_file }}
                                            </a>
                                       @else
                                                                     
                                       File link is missing
                                       @endif
                                </td>
                                    <td>{{$data->applicant_name}}</td>
                                    <td>
                                        @if(!empty($data->ad_file_link))
                                        <a href="{{ route('viewFileForwardByADNodal', ['filename' => $data->ad_file_link]) }}"
                                            target="_blank">
                                            {{ (strlen($data->ad_file_link) > 10) ? substr($data->ad_file_link, 0, 10)."..." : $data->ad_file_link }}
                                        </a>
                                        @else
                                      
                                        File link is missing
                                        @endif
                                    </td>

                                    <td>
                                        @if(!empty($data->dp_file_link))
                                        <a href="{{ route('viewFileForwardByADNodal', ['filename' => $data->dp_file_link]) }}"
                                            target="_blank">
                                            {{ (strlen($data->dp_file_link) > 10) ? substr($data->dp_file_link, 0, 10)."..." : $data->dp_file_link }}
                                        </a>
                                        @else
                                      
                                        File link is missing
                                        @endif
                                    </td>

                                    <td>{{$data->remark}}</td>
                                    <td>{{$data->remark_details}}</td>
                                    <td>{{$data->status}}</td>


                                    <!-- <td><button class="btn btn-primary approve_for_transfer " data-toggle="modal" data-target="#confirmationModal">Approve for transfer</button></td> -->

                                    @if($data->formSubStat == "submitted")
                                    <td class="textright">
                                     

                                    </td>
                                    @endif

                                    @if($data->formSubStat == "started")
                                  
                                    @endif
                                    @if($data->formSubStat == "forapproval")
                                    <td class="textright">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}"
                                            class="btn btn-success btn-sm" role="button" aria-disabled="true">View</a>
                                        <!-- capturing the ein at click instant -->
                                    </td>
                                  

                                    <td>
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp

                                        @php
                                        $loopIndex = $loop->index + 1;
                                        @endphp

                                        @if($loopIndex !== 1)
                                        <button class="btn btn-danger btn-sm width approve_for_uo"
                                            style="font-size: 13px;" role="button" aria-disabled="true"
                                            id="edit_emp_name_btn" type="button" disabled
                                            onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Approve for
                                            UO</button>


                                        @else
                                        <button class="btn btn-danger btn-sm width approve_for_uo"
                                            style="font-size: 13px;" role="button" id="edit_emp_name_btn" type="button"
                                            onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Approve for
                                            UO</button>

                                        @endif
                                   
                                    </td>

                                    @if($data->transfer_status == null || $data->transfer_status == 0)
                                    <td>
                                        {{-- <button class="btn btn-primary approve_for_transfer" data-id="{{$data->id}}"
                                        data-toggle="modal" data-target="#confirmationModal">Approve for
                                        transfer</button> --}}

                                        <!-- ///////////////////// -->
                                    
                                        <button class="btn btn-danger btn-sm" role="button" aria-disabled="true"
                                        id="edit_emp_name_btn" type="button"
                                        onclick='setTransfertData(<?= json_encode($temp_array) ?>)'>Approve for
                                        transfer</button>
                                    </td>
                                    @endif
                                    <td>
                                        @php
                                        $temp_array = [];
                                        $ein = $data->ein;
                                        $appl_no = $data->appl_number;
                                        $temp_array['ein'] = $ein;
                                        $temp_array['appl_no'] = $appl_no;
                                        @endphp
                                        <button class="btn btn-danger btn-sm" role="button" aria-disabled="true"
                                            id="edit_emp_name_btn" type="button"
                                            onclick='setRevertData(<?= json_encode($temp_array) ?>)'>Revert</button>

                                    </td>


                                    @endif

                                </tr>
                                <!-- MODAL FADE CODE BELOW FOR REVERT -->

                                <div class="modal fade" id="remarkModal" tabindex="-1"
                                    aria-labelledby="remarkModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form name="revertForm"
                                            action="{{ route('revertDetailsFromDPNodal', Crypt::encryptString($data->ein)) }}"
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
                                                    <label for="name"><b>Give a remark </b></label>
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

                                                    <label for="remark"><b>Remark (Less than 250 words)</b></label>
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
                                            action="{{ route('approvedListFromDP', Crypt::encryptString($data->ein)) }}"
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
                                                        @foreach($RemarksApprove as $option)
                                                        <option value="{{ $option['probable_remarks'] }}" required>
                                                            {{$option['probable_remarks']}}</option>
                                                        @endforeach


                                                    </select><br>

                                                    <label for="remark_details"><b>Remark (Less than 250
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



                                <!-- Modal fade for Approve for trasfer  -->
                                <div class="modal fade" id="remarkTransferModal" tabindex="-1"
                                aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form name="transferForm"
                                        action="{{ route('transferFromDPNodal', Crypt::encryptString($data->ein)) }}"
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
                                                <label for="transfer_remark"><b>Select a reason: </b></label>
                                                <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                <input type="hidden" class="form-control" id="ein" name="ein"
                                                    value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="transfer_remark" name="transfer_remark">
                                                    <option selected>Select</option>
                                                    @foreach($RemarksApprove as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required>
                                                        {{$option['probable_remarks']}}</option>
                                                    @endforeach


                                                </select><br>

                                                <label for="transfer_remark_details"><b>Remark (Less than 250
                                                        words)</b></label>
                                                <input type="text" placeholder="Remark" class="form-control"
                                                    id="transfer_remark_details" name="transfer_remark_details" value="">

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






<!-- Update your button to trigger the modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>


@endsection


<!-- Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
// Approve for trasfer to update the transfer_status to 1 in the Proforma Table 
$(document).ready(function() {
    var dataId;

    $('.approve_for_transfer').click(function() {
        // Store the data-id for later use
        dataId = $(this).data('id');
    });

    // Handle modal confirmation
    $('#confirmApproval').click(function() {
        // Send AJAX request to update transfer_status
        $.ajax({
            url: '/ddo-assist/updateTransferStatus',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                dataId: dataId
            },
            success: function(response) {
                // Handle success response, if needed
                console.log('Transfer status updated successfully');

                // Close the modal after successful update
                $('#confirmationModal').modal('hide');

                window.location.href = "/ddo-assist/selectDeptByDPNodal";
            },
            error: function(error) {
                // Handle error response, if needed
                console.error('Error updating transfer status:', error);
            }
        });
    });
});

function setTransfertData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure to transfer?')) {
        return;
    }
    $("#remarkTransferModal").modal('show');
    let form = document.forms['transferForm'];
    // form.appl_number.value = temp_array['appl_number'];
    form.ein.value = temp_array['ein'];
    console.log(temp_array['ein'])

}


function setRevertData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure that the Applicant File is not OK?')) {
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
<script nonce="{{ csp_nonce() }}">
    
    document.addEventListener("DOMContentLoaded", function() {
        var printButton = document.getElementById("printButtonCard1");
        
        printButton.addEventListener("click", function() {
            printListCard1();
        });
    });
    //The below code is for print
    // The print function for Card 1
    function printListCard1() {
        var cardContent = document.getElementById('card1-table').innerHTML;
        var borderedContent = '<div class="center-table" >' + cardContent + '</div>' +
            '<style>@media print {table {border-collapse: collapse;} td, th {border: 1px solid black; padding:2px;}}' +
            '.center-table {display: flex; justify-content: center; align-items: center;}</style>';
        printContent(borderedContent);
    }
    
    function printContent(content) {
        var printWindow = window.open('', '_blank');
    
    
        <?php
    
            use App\Models\DepartmentModel;
            use App\Models\PortalModel;
            use App\Models\User;
            use Carbon\Carbon;
            use Illuminate\Support\Facades\Auth;
    
            $getPortalName = PortalModel::where('id', 1)->first();
            //Portal name short form    
            $getProjectShortForm = $getPortalName->short_form_name;
            //Application long name
            $getSoftwareName = $getPortalName->software_name;
            $getDeptName = $getPortalName->department_name;
            $getGovtName = $getPortalName->govt_name;
            $getDeveloper = $getPortalName->developed_by;
            $getCopyright = $getPortalName->copyright;
    
            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $Department = DepartmentModel::get()->where('dept_id', $getUser->dept_id)->first();
            $getDeptName = $Department->dept_name;
            $getDate = Carbon::now()->format('d-m-Y');
    
            ?>
    
        printWindow.document.write("<html><head><title>{{$getSoftwareName}}</title></head><body>");
        printWindow.document.write("<div style='display: flex; align-items: center;'>");
        printWindow.document.write(
            "<img src='{{asset('assets/images/kanglasha.png')}}' alt='Image ' width='80' height='100'>");
    
    
        printWindow.document.write("<div style='text-align: center; flex-grow: 1;'>");
        // printWindow.document.write("<p>For Department: {{$getDeptName}}</p>");
        printWindow.document.write("<p>{{$getProjectShortForm}}</p>");
        printWindow.document.write("<p> List</p>");
        printWindow.document.write("</div></div>");
        printWindow.document.write("<p><br></br></p>");
        printWindow.document.write(content);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
    </script>
    <script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script>