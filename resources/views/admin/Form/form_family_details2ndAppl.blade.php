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
        @include('admin.progress.form_progress_2nd')
        <br>
        <div class="col-md-12 margintop">
            <!-- Family Detail Form -->
            <div class="card displayshow" id="family_details_section">
                <div class="card-header">
                <div class="col-12 center">
<span class="color">Last date of submission.....</span>
<br>
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
                        <b>EIN: {{ $empDetails['ein'] }} &nbsp; Deceased Name : {{ $empDetails['deceased_emp_name'] }} &nbsp; D.O.E: {{date('Y-m-d', strtotime( $empDetails['deceased_doe'])) }} &nbsp;&nbsp;&nbsp; Applicant Name : {{ $empDetails['applicant_name'] }}</b>
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
                            <form method="POST" action="{{ url('ddo-assist/save-family-details2ndAppl') }}" class="">
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
                                    <!-- <b>Add Family members:</b> -->
                                    <hr>
                                </div>
                                <!-- ein field -->
                                <input type="hidden" name="ein" class="form-control" value="{{$ein}}" />

                                <table class="table" id="multiForm">
                                    @empty($emp_family_details)
                                    <tr>
                                        <th>Name</th>
                                        <th>D.O.B</th>
                                        <th>Gender</th>
                                        <th>Relation</th>
                                        <!-- <th>Remark</th>
                                        <th>Action</th> -->
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="text" name="new_data[0][name]" class="form-control" value="" />
                                        </td>
                                        <td>
                                            <input type="date" name="new_data[0][dob]" class="form-control" value="" />

                                        </td>
                                        <td>
                                            <!-- <input type="text" name="gender[0][gender]" class="form-control" /> -->
                                            <select class="form-select" aria-label="Default select example" name="new_data[0][gender]">
                                                <option Value="" selected>Select </option>
                                                <option value="M">Male</option>
                                                <option value="F">Female</option>
                                                <!-- <option value="T">Transgender</option> -->
                                            </select>
                                        </td>
                                        <td>
                                        <select class="form-select" aria-label="Default select example" name="new_data[0][gender]">
                                                <option Value="" selected>Select </option>
                                                <option value="1">Wife</option>
                                                <option value="2">Husband</option>
                                                <option value="3">Son</option>
                                                <option value="4">Daughter</option>
                                            </select>
                                        </td>
                                        <!-- <td>
                                            <input type="text" name="new_data[0][remarks]" class="form-control" value="" id="new_data[0][remark]" />
                                        </td> -->
                                        <td>
                                            @if($status != 1)
                                            <input type="button" name="add" value="Add" id="addRemoveIp" class="btn btn-outline-success">

                                            <!-- Add New Code is in app.blade.php search for id= addRemoveIp -->
                                            @endif
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="5"></td>
                                        <td>
                                            @if($status != 1)
                                            <input type="button" name="add" value="Add" id="addRemoveIp" class="btn btn-outline-success">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>D.O.B</th>
                                        <th>Gender</th>
                                        <th>Relation</th>
                                        <!-- <th>Remark</th>
                                        <th>Action</th> -->
                                    </tr>
                                    @foreach($emp_family_details as $data)
                                    <!-- id field of already exist data -->
                                    <input type="hidden" name="old_data[{{$loop->index}}][id]" class="form-control" value="{{$data->id}}" />
                                    <tr>
                                        <td>

                                            @if($data->name == null)
                                            <input type="text" name="old_data[{{$loop->index}}][name]" class="form-control bordercolor" value="{{$data->name}}" />
                                            @else
                                            <input type="text" name="old_data[{{$loop->index}}][name]" class="form-control" value="{{$data->name}}" />
                                            @endif

                                        </td>
                                        <td>
                                            @if($data->dob == null)
                                            <input type="date" name="old_data[{{$loop->index}}][dob]" class="form-control bordercolor" value="{{$data->dob}}" />
                                            @else
                                            <input type="date" name="old_data[{{$loop->index}}][dob]" class="form-control" value="{{$data->dob}}" />
                                            @endif
                                        </td>
                                        <td>
                                            <!-- <input type="text" name="gender[0][gender]" class="form-control" /> -->
                                            @if($data->gender == null || $data->gender == '')
                                            <select class="form-select bordercolor" aria-label="Default select example" name="old_data[{{$loop->index}}][gender]">
                                                <option Value="" {{$data->gender =='' ? 'selected' : '' }}>Select </option>
                                                <option value="M" {{$data->gender =='M' ? 'selected' : '' }}>Male</option>
                                                <option value="F" {{$data->gender =='F' ? 'selected' : '' }}>Female</option>
                                                <!-- <option value="T" {{$data->gender =='T' ? 'selected' : '' }}>Transgender</option> -->
                                            </select>
                                            @else
                                            <select class="form-select" aria-label="Default select example" name="old_data[{{$loop->index}}][gender]">
                                                <option Value="" {{$data->gender =='' ? 'selected' : '' }}>Select </option>
                                                <option value="M" {{$data->gender =='M' ? 'selected' : '' }}>Male</option>
                                                <option value="F" {{$data->gender =='F' ? 'selected' : '' }}>Female</option>
                                                <!-- <option value="T" {{$data->gender =='T' ? 'selected' : '' }}>Transgender</option> -->
                                            </select>
                                            @endif

                                        </td>
                                        <td>
                                        @if($data->relation == null || $data->relation == '')
                                            
                                            <select class="form-select bordercolor" aria-label="Default select example" name="old_data[{{$loop->index}}][relation]">
                                                <option Value="" {{$data->relation =='' ? 'selected' : '' }}>Select </option>
                                                <option value="1" {{$data->relation =='1' ? 'selected' : '' }}>Wife</option>
                                                <option value="2" {{$data->relation =='2' ? 'selected' : '' }}>Husband</option>
                                                <option value="3" {{$data->relation =='3' ? 'selected' : '' }}>Son</option>
                                                <option value="4" {{$data->relation =='4' ? 'selected' : '' }}>Daughter</option>
                                                <!-- <option value="T" {{$data->gender =='T' ? 'selected' : '' }}>Transgender</option> -->
                                            </select>
                                            @else
                                            <!-- <input type="text" name="old_data[{{$loop->index}}][relation]" class="form-control" value="{{$data->relation}}" /> -->
                                            <select class="form-select" aria-label="Default select example" name="old_data[{{$loop->index}}][relation]">
                                            <option Value="" {{$data->relation =='' ? 'selected' : '' }}>Select </option>
                                                <option value="1" {{$data->relation =='1' ? 'selected' : '' }}>Wife</option>
                                                <option value="2" {{$data->relation =='2' ? 'selected' : '' }}>Husband</option>
                                                <option value="3" {{$data->relation =='3' ? 'selected' : '' }}>Son</option>
                                                <option value="4" {{$data->relation =='4' ? 'selected' : '' }}>Daughter</option>
                                                <!-- <option value="T" {{$data->gender =='T' ? 'selected' : '' }}>Transgender</option> -->
                                            </select>
                                            @endif

                                        </td>
                                        <!-- <td>
                                            @if($data->remarks == null || $data->remarks == '')
                                            <input style="border-color: red;" type="text" name="old_data[{{$loop->index}}][remarks]" class="form-control" value="{{$data->remarks}}" />
                                            @else
                                            <input type="text" name="old_data[{{$loop->index}}][remarks]" class="form-control" value="{{$data->remarks}}" />
                                            @endif
                                        </td> -->
                                        <td>
                                            @if($status != 1)
                                            <a class="btn" href="{{ route('delete-family-member2ndAppl', Crypt::encryptString($data->id))}}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill color" viewBox="0 0 16 16">
                                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                                </svg>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endempty
                                </table>

                                <div class="col-sm-12 textright">
                                <div class="col-sm-12">
                                <a href="{{ route(URL::previous()) }}" class="btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true">Back</a>
                                    
                                      <button type="submit" class="btn btn-success btn-sm" id="save_family_btn" >Save As Draft</button>
                                      &nbsp;&nbsp;&nbsp;<a href="{{ route('create-applicant-files2ndAppl') }}" id="close" class="btn btn-success btn-sm marginbottom" tabindex="-1" role="button" aria-disabled="true" >
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

<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        var discardLink = document.getElementById("save_family_btn");

        discardLink.addEventListener("click", function(event) {
            var confirmed = confirm("Are You Sure?");
            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
    </script>
    @endsection