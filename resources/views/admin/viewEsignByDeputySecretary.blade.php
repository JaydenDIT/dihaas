<!-- Notes : 
        jquery code to show and hide active,removed and all employee list write inside app.js file
-->

@extends('layouts.app')

@section('content')
<link href="{{ asset('assets/css/select.css') }}" rel="stylesheet">
<?php $selected = session()->get('deptId') ?>

<div class="container">

    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form method="POST" action="{{ route('viewEsignByDPDeptSelect') }}">
                        @csrf

                        <div class="row">
                            <div class="col-6">
                                <h3>{{ __('Select Department') }}</h3>
                            </div>
                            <!-- Department -->
                            <div class="row mb-3">
                                <label for="Departments" class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="dept_id" name="dept_id">
                                        <option value="" selected>All Department</option>
                                        @foreach($deptListArray as $option)
                                        <option value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}" required {{($selected == $option['dept_id'])?'selected':''}}>
                                            {{$option['dept_name']}}
                                        </option>
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
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- {{ __('You are logged in!') }} -->

                    <!-- CMIS Started employee list table -->
                    <!-- <div id="started_emp_table" style="display:show"><b>Started Employee List :</b><br> -->
                    <!-- Add the button outside the form -->


                    <div class="row">

                        <div class="col-7">
                            <b class="color">List of Approved Applicants for giving Appointment</b>
                        </div>
                        <div class="col-5">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST" action="{{ url('viewEsignByDPSearch') }}" enctype="multipart/form-data" class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-10 marginright_textalign">
                                        <input type="text" class="form-control margin" placeholder="Search by EIN NO." name="searchItem"></input>
                                    </div>
                                    <div class="col-2 margin_text">
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <hr>



                    <!-- Hidden form to submit selected EINs -->
                    <form id="selectedEinForm" method="post" action="{{ route('check_fill_uo') }}">
                        @csrf
                        <input type="hidden" name="selectedEinIds" id="selectedEinIds" value="">
                    </form>
                    <p>
                    <form name="frmGenerateUO" method="post" action="{{ route('viewApproveGroup') }}" novalidate target="_blank">
                        <!-- <div style="text-align: left">
                        <a href="{{ route('viewOrderGroup') }}" id="btnGroupPdf1" class="badge btn btn-success  text-wrap" style="width:13rem; height:2.5rem ;"  aria-disabled="true" disabled>View Order of Selected Applicants</a>
                        </div> -->


                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed table-striped" id="table">

                                <thead class="thead-dark">
                                    <tr>

                                        <th scope="col">Sl.No.</th>
                                        <th scope="col">EIN</th>
                                        <th scope="col">Deceased Name</th>
                                        <th scope="col">Department Name</th>
                                        <th>Applicant Name</td>
                                        <th scope="col">Mobile No.</th>
                                        <th scope="col">eFile AD No.</th>
                                        <th>eFile DP No.</td>
                                        <th scope="col">status</th>
                                        <th scope="col" colspan="4" colspan="4" class="textcenter">Action</th>
                                    </tr>
                                </thead>
                                <tbody>



                                    @if($empList->isEmpty())
                                    <tr>
                                        <td colspan="11" class="textcenter">
                                            <b>No Data Found!</b>
                                        </td>
                                    </tr>
                                    @else



                                    @foreach($empList as $key => $data)



                                    <tr>


                                        <td>{{ $empList->firstItem() + $key }}</td>

                                        <td>{{$data->ein}}</td>
                                        <td>{{$data->deceased_emp_name}}</td>
                                        <td>{{$data->dept_name}}</td>

                                        <td>{{$data->applicant_name}}</td>

                                        <td>{{$data->applicant_mobile}}</td>
                                        <td>{{$data->efile_ad}}</td>

                                        <td>{{$data->efile_dp}}</td>

                                        <td>{{$data->status}}</td>

                                        @if($data->formSubStat == "order")

                                        <td class="textright">
                                            <a href="{{ route('viewOrder', Crypt::encryptString($data->ein)) }}" class="btn btn-success font_size btnwidth" role="button" aria-disabled="true" target="_blank">e-Sign</a>
                                            <!-- capturing the ein at click instant -->

                                        </td>

                                        @endif


                                    </tr>

                                    @endforeach


                                    @endif

                                </tbody>
                            </table>
                        </div>


                    </form>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



@endsection