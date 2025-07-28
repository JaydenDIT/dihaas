@extends('layouts.app')

@section('content')

<?php $selected = session()->get('deptId') ?>
<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                @if($errors)
                @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
                @endif
                <div class="card">
                    <div class="card-header" align="center"
                        style="font-family: Elephant;font-size: 20px;font-weight:bold;line-height:66px;color: rgb(51, 51, 233);">
                        {{ __('Official Register Form') }}</div>

                    <div class="card-body">

                        <form id="officialSaveForm" method="POST" action=" {{ route('official-post-register') }} ">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                     {{-- @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror --}}
                                    @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                   {{-- @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror  --}}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mobile"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Mobile') }}</label>

                                <div class="col-md-6">
                                    <input id="mobile" type="mobile"
                                        class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                        maxlength="10" required autocomplete="mobile">
                                    @if ($errors->has('mobile'))
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    @endif
                                   {{-- @error('mobile')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror  --}}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="roles" class="col-md-4 col-form-label text-md-end">{{ __('Roles') }}</label>
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="role_id"
                                        name="role_id">
                                        <option selected>Select</option>
                                        @foreach($roles as $option)
                                        <option value="{{$option['role_id']}}" required>{{$option['role_name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>



                            <!-- Department -->
                            <!-- Department -->
                            <div class="row mb-3">
                                <label for="Departments"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Department') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="dept_id"
                                        name="dept_id">
                                        <option value="" selected>All Department</option>
                                        @foreach($departments as $option)
                                        <option data-ministry="{{ $option->ministry_id }}"
                                            value="{{ $option['dept_id'] == null ? null : $option['dept_id'] }}"
                                            required {{($selected == $option['dept_id'])?'selected':''}}>
                                            {{$option['dept_name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('dept_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                </div>
                            </div>
                            <!-- Ministry -->
                            <div class="row mb-3">
                                <label for="Ministrys"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Administrative Department') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" id="ministry_id"
                                        name="ministry_id" disabled>
                                        <option selected disabled>Select</option>
                                        @foreach($ministry as $option)
                                        <option value="{{$option['ministry_id']}}">{{$option['ministry']}}</option>


                                        @endforeach
                                    </select>
                                    @error('ministry_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>
                        <!-- Post  role_id == 9 -->
                            <div id="departmentSection" style="display: none;">
                                <div class="row mb-3">
                                    <label for="department_signing_authority"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Post') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" aria-label="Default select example"
                                            id="department_signing_authority" name="department_signing_authority">
                                            <option value="" selected> Signing Authority of Department</option>
                                            @foreach($departmentSigningAuthority as $option)
                                            <option value="{{ $option['id'] }}" required>
                                                {{$option['name']}}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('department_signing_authority')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- Post  role_id == 1,2,3,4,5,6,7,8 -->
                            <div id="postList" >
                                <!-- Post-->
                                <div class="row mb-3">
                                    <label for="post" class="col-md-4 col-form-label text-md-end">Post</label>

                                    <div class="col-md-6">




                                        <select class="form-select  @error('post') is-invalid @enderror "
                                            aria-label="Default select example" id="post" name="post">
                                            <option value="" selected disabled>Select Designation</option>

                                            <option
                                                value="{{ $option['dsg_serial_no'] == null ? null : $option['dsg_serial_no'] }}"
                                                required {{($selected == $option['dsg_serial_no'])?'selected':''}}>
                                                {{$option['dsg_desc']}}
                                            </option>




                                        </select>

                                        @error('post')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror






                                    </div>

                                </div>
                            </div>



                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required>

                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                    {{-- @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror  --}}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password_confirmation"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required>
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>




                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" id="registerSaveOfficial" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script nonce="{{ csp_nonce() }}">
var _token = "{{ csrf_token() }}";


$(document).ready(function() {
    $('#dept_id').change(function() {
        var selectedDepartment = $(this).children("option:selected").data('ministry');
        $('#ministry_id').val(selectedDepartment);
    });
});

// $('#registerSaveOfficial').click(function() {


// });
</script>

<!-- <script>
    $(document).ready(function() {
        // Get the select elements
        const departmentSelect = $('#dept_name_select');
        const ministrySelect = $('#ministry_name_select');

        // Add event listener to departmentSelect
        departmentSelect.on('change', function() {
            // Get the selected dept_name
            const selectedDepartment = $(this).val();

            // Find the corresponding ministry_name option
            const ministrynameOption = ministrySelect.find(option[value="${selectedDepartment}"]);

            // Set the selected attribute of the ministrynameOption to true
            ministrynameOption.prop('selected', true);
        });


    });
</script>  -->


<script type="text/javascript" src="{{ asset('assets/js/auth/forge-sha256.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/auth/register.js') }}"></script>

<script>
$('#post').change(function() {
    var id = $(this).find('option:selected').val();
    var item = $(this).find(item => item.dsg_serial_no === id);
    //alert($('#post option[value="'+this.value+'"]').data('grade'));


})


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


<script>
    // To display the post according to roles 
document.addEventListener('DOMContentLoaded', function() {
    var roleSelect = document.getElementById('role_id');
    var departmentSection = document.getElementById('departmentSection');

    var postList = document.getElementById('postList');

    roleSelect.addEventListener('change', function() {
        var selectedValue = this.value;

        if (selectedValue == 9) {
            departmentSection.style.display = 'block';
            postList.style.display = 'none';
        } else {
            departmentSection.style.display = 'none';
            postList.style.display = 'block';
        }
    });
});
</script>

@endsection