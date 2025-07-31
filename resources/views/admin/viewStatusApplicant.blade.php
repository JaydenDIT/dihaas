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
                            <h3>{{ __('Application Status') }}</h3>
                        </div>
                        <div class="col-6">
                          
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
                                <th scope="col">Seniority List Order</th>
                                <th scope="col">EIN</th>
                                <th scope="col">Deceased Name</th>
                                <th scope="col">DOE</th>
                               
                                <th>Submitted Date</td>
                                <th>Applicant Name</td>
                                <th>Sent By</td>
                                <th>Currently With</td>
                                <th>Remarks</td>                              
                               
                                <th>status</td>
                                <th scope="col" colspan="4" class="textcenter">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($empListArray))
                            <tr>
                                <td colspan="8" class="textcenter">
                                    <b>No Data Found!</b>
                                </td>
                            </tr>
                            @else

                            @foreach($empList as $data)
           
                            <tr>
                                <th scope="row">{{$data->slNo}}</th>
                                <td>{{$data->ein}}</td>
                                <td>{{$data->deceased_emp_name}}</td>
                                <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}</td>
                               
                                <td>{{$data->appl_date ? \Carbon\Carbon::parse($data->appl_date)->format('d/m/Y') : 'NA'}}</td>
                                <td>{{$data->applicant_name}}</td>
                                <td>{{$data->sent_by}}</td>
                                <td>{{$data->received_by}}</td>
                                <td>{{$data->remark}}</td>
                               
                                <td>{{$data->status}}</td>

                                @if($data->formSubStat == "submitted")
                                <td class="rightstyle">
                                    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>

                                  
                                </td>
                                @endif

                                @if($data->formSubStat == "started")
                                <td class="rightstyle">
                                    <a href="{{ route('Proforma_ApplicantDetails', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">Update</a>
                                </td>
                                <td class="rightstyle">
                                    <a href="{{ route('discard-applicant', Crypt::encryptString($data->ein)) }}" class="btn btn-danger btn-sm borderradius" role="button" aria-disabled="true" onclick="return confirm('Are You Sure to Discard this Applicant?')">Delete</a>
                                </td>
                                @endif
                                @if($data->formSubStat == "verified")
                                <td class="rightstyle">
                                    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true">View</a>
                                    <!-- capturing the ein at click instant -->


                                </td>
                                @endif
                                @if($data->formSubStat == "forapproval")
                                <td class="rightstyle">
                                    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius"  role="button" aria-disabled="true">View</a>
                                    <!-- capturing the ein at click instant -->


                                </td>
                                @endif
                                @if($data->formSubStat == "approved")
                                <td class="rightstyle">
                                    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius"  role="button" aria-disabled="true">View</a>
                                    <!-- capturing the ein at click instant -->


                                </td>
                                @endif
                                @if($data->formSubStat == "appointed")
                                <td class="rightstyle">
                                    <a href="{{ route('viewPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius"  role="button" aria-disabled="true">View</a>
                                    <!-- capturing the ein at click instant -->


                                </td>
                                @endif
                                @if($data->formSubStat == "order")
                                <td class="rightstyle">
                                    <a href="{{ route('generate-pdf', Crypt::encryptString($data->ein)) }}" class="btn btn-success btn-sm borderradius" role="button" aria-disabled="true" target="_blank">View Order</a>
                                    <!-- capturing the ein at click instant -->


                                </td>


                                @endif

                            </tr>
                            <!-- MODAL FADE CODE BELOW FOR REVERT -->

                            <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form name="revertForm" action="{{ route('revertPersonalDetailsFrom', Crypt::encryptString($data->ein)) }}" method="Post">
                                        @csrf
                                        <!-- @method('GET') -->


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="name"><b>Select a reason: </b></label>
                                                <!-- <input type="hidden" class="form-control" id="control_name" name="control_name" value="remarks"> -->
                                                <!-- <input type="hidden" class="form-control" id="appl_number" name="appl_number" value="{{ $data['appl_number'] == null ? null : $data['appl_number'] }}"> -->
                                                <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                                                    <option selected>Select</option>
                                                    @foreach($Remarks as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                                                    @endforeach


                                                </select><br>

                                                <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                                                <input type="text" placeholder="Remark" class="form-control" id="remark_details" name="remark_details" value="{{ $data['remark_details'] == null ? null : $data['remark_details'] }}">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
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
</script>