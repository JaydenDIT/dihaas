<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->
@extends('layouts.app')

@section('content')




<div class="container">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ __('Submitted Applications') }}</h3>
                        </div>
                        <div class="col-6">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                                action="{{ url('ddo-assist/viewTransferListByHodAssistantSearch') }}"
                                enctype="multipart/form-data" class="was-validated">
                                <div class="row rightstyle">
                                    @csrf

                                    <div class="col-10 marginright">
                                        <input type="text" class="form-control marginright2"
                                            placeholder="Search by EIN NO." name="searchItem">
                                    </div>
                                    <div class="col-2 marginright1">
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

                                    <th scope="col">status</th>
                                    <th scope="col">Transferred From</th>
                                    <th scope="col">Proposed Post</th>
                                    <th scope="col">Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($empListArray))
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>
                                @else

                                @foreach($empList as $key => $data)

                                <?php
                                $uploader = $data->uploader_role_id;
                                if ($uploader == 77) {
                                    $mode = "Online";
                                } else {
                                    $mode = "Offline";
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

                                    <td>{{$data->transfer_status}}</td>
                                    <td>{{$data->dept_name}}</td>
                                    <td>
                                        @foreach($data->matching_postnames as $matching_postname)
                                        {{ $matching_postname }}
                                        @endforeach
                                    </td>


                                    @if($data->formSubStat == "transfer")

                                    <td class="rightstyle">
                                        <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}"
                                            class="btn btn-success btn-sm blockstyle" role="button"
                                            aria-disabled="true">View</a>
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
                                        <button class="btn btn-success btn-sm" role="button" aria-disabled="true"
                                            id="edit_emp_name_btn" type="button"
                                            onclick='setForwardData(<?= json_encode($temp_array) ?>)'>Forward</button>

                                    </td>
                                    @endif

                                    <div class="modal fade" id="remarkForwardModal" tabindex="-1"
                                        aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <form name="forwardForm"
                                                action="{{ route('forwardDetailsFrom', Crypt::encryptString($data->ein)) }}"
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
                                                        <label for="remark"><b>Select a Reason: </b></label>
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
                                                            class="btn btn-success">Forward</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>




                                </tr>

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

<script>
function setForwardData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure to transfer to HOD ?')) {
        return;
    }
    $("#remarkForwardModal").modal('show');
    let form = document.forms['forwardForm'];
    // form.appl_number.value = temp_array['appl_number'];
    form.ein.value = temp_array['ein'];
    console.log(temp_array['ein'])

}
</script>




@endsection