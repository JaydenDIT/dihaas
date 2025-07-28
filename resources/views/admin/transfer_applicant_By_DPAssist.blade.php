@extends('layouts.app')

@section('content')
{{-- <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet"> --}}

<link href="{{ asset('assets/css/select.css') }}" rel="stylesheet">

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script> --}}


<?php $selected = session()->get('deptId') ?>

<div class="container">
    <br>

    <div class="row justify-content-center">
        <div class="col-md-12">
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


             <!-- <div class="row">
                <div class="col-7"> -
                 
                </div>

                <div class="col-5">
                    <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                        action="{{ route('selectDeptByDPAssistSearch') }}" enctype="multipart/form-data"
                        class="was-validated">
                        <div class="row textright">
                            @csrf

                            <div class="col-10 marginright_textalign">
                                <input type="text" class="form-control marginright" placeholder="Search by EIN NO."
                                    name="searchItem">
                            </div>
                            <div class="col-2 margin_text">
                                <button class="btn btn-outline-secondary" type="submit"
                                    id="button-addon2">Search</button>
                            </div>
                        </div>
                    </form>
                </div>


            </div> -->

            <br>
            <div class="row">

                <div class="col-sm-12">

                    <table class="table table-bordered table-condensed table-striped" id="table">
                        <thead>
                            <tr>


                                <th scope="col">Sl.No.</th>
                                <th scope="col">EIN</th>
                                <th scope="col">Deceased Name</th>
                                <th scope="col">DOE</th>
                                <th scope="col">AD File No.</th>
                                <th scope="col">File put up By AD</th>
                                <th scope="col">Applicant Name</td>

                                <th scope="col">Remark</th>
                                <th scope="col">Description</th>

                                <th scope="col">Department</th>
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
                            <td>{{ $empList->firstItem() + $key }}</td>
                            <td>{{$data->ein}}</td>
                            <td>{{$data->deceased_emp_name}}</td>
                            <td>{{$data->deceased_doe ? \Carbon\Carbon::parse($data->deceased_doe)->format('d/m/Y') : 'NA'}}
                            </td>
                            <td>
                                {{$data->efile_ad}}
                            </td>
                            <td>
                                <a href="{{ route('viewFileForwardByADNodal', ['filename' => $data->ad_file_link]) }}"
                                    target="_blank">{{ (strlen($data->ad_file_link)>10)? substr($data->ad_file_link, 0, 10)."...": $data->ad_file_link}}</a>
                            </td>

                            <td>{{$data->applicant_name}}</td>
                            <!-- <td>{{$data->applicant_dob ? \Carbon\Carbon::parse($data->applicant_dob)->format('d/m/Y') : 'NA'}}</td>                                    -->

                            <td>{{$data->remark}}</td>
                            <td>{{$data->remark_details}}</td>
                            <td>{{$data->dept_name}}</td>


                            @if($data->formSubStat == "signed")
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


                            <!-- MODAL FADE CODE BELOW FOR FORWARD -->

                            <div class="modal fade" id="remarkForwardModal" tabindex="-1"
                                aria-labelledby="remarkForwardModalLabel" aria-hidden="true">
                               
                                <div class="modal-dialog" role="document">
                                    <form name="forwardForm"
                                        action="{{ route('transferFromDPAssistToAnyDepartment', Crypt::encryptString($data->ein)) }}"
                                        method="Post">
                                        @csrf
                                        <!-- @method('GET') -->


                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="remarkForwardModalTitle">Transfer of Applicant</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for="ein"><b>Name:</b></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <h6>{{$data->deceased_emp_name}}</h6>

                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for="ein"><b>EIN:</b></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <h6>{{$data->ein}}</h6>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-5">
                                                        <label for="ein"><b>Parent Department:</b></label>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <h6>{{$data->dept_name}}</h6>

                                                    </div>
                                                </div>

                                                <!-- department -->
                                                <label for="Departments"><b>Department</b></label>

                                                <select class="form-select" aria-label="Default select example"
                                                    id="dept_id" name="dept_id">
                                                    <option value="" selected>All Department</option>
                                                    @foreach($departments as $option)
                                                    <option data-ministry="{{ $option->ministry_id }}"
                                                        value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}"
                                                        required {{($selected == $option['dept_id'])?'selected':''}}>
                                                        {{$option['dept_name']}}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <br>
                                                <!-- post -->

                                                <label for="post"><b>Post</b></label>

                                                <select class="form-select  @error('post') is-invalid @enderror "
                                                    aria-label="Default select example" id="post" name="post">
                                                    <option value="" selected disabled>Select Designation
                                                    </option>

                                                    <option
                                                        value="{{ $option['dsg_serial_no'] == null ? null : $option['dsg_serial_no'] }}"
                                                        required
                                                        {{($selected == $option['dsg_serial_no'])?'selected':''}}>
                                                        {{$option['dsg_desc']}}
                                                    </option>
                                                </select>

                                                <br>
                                                <!-- remark -->

                                                <label for="remark"><b>Remark: </b></label>

                                                <input type="hidden" class="form-control" id="ein" name="ein"
                                                    value="{{ $data['ein'] == null ? null : $data['ein'] }}">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="remark" name="remark">
                                                    <option selected>Select</option>
                                                    @foreach($RemarksApprove as $option)
                                                    <option value="{{ $option['probable_remarks'] }}" required>
                                                        {{$option['probable_remarks']}}</option>
                                                    @endforeach


                                                </select>
                                                <br>

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


            </div>

        </div>

    </div>

</div>




<script>
$('#dept_id').change(function() {

    //make blank      

    $("#post").empty();
    $('#post').append(new Option('Select Post', ''));

    $('#post option[value=""]').attr('disabled', true);




    var id = $(this).find('option:selected').val();
    var data_dept_id = {
        'dept_id': $(this).find('option:selected').val(),
    };
    // console.log(data_dept_id);
    $.get('{{ route("retrieve_dept_register_user") }}', data_dept_id, function(id, textStatus, xhr) {

        //now load the result data in id post i.e. for designation  

        $.each(id, function(index, element) {

            $('#post').append(new Option(element.dsg_desc, element
                .dsg_serial_no)); //only value and text
            //Below is for adding extra attribute
            // $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr('data-grade', element.group_code).appendTo('#post');

        });

    });


})
</script>

<!-- From this  is for Forward Previous -->
<script>
function setForwardData(temp_array) {
    // console.log(temp_array['ein']);
    if (!confirm('Are You Sure to transfer to HOD Assistant ?')) {
        return;
    }
    $("#remarkForwardModal").modal('show');
    let form = document.forms['forwardForm'];
    // form.appl_number.value = temp_array['appl_number'];
    form.ein.value = temp_array['ein'];
    console.log(temp_array['ein'])

}
</script>
<!-- Upto this  is for Forward Previous -->



{{-- <script>
    $('document').ready(()=>{
        $('#dept_id').select2();
    })
</script> --}}
@endsection

