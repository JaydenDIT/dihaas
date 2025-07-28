@extends('layouts.app')

@section('content')
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
                        {{ __('Official Edit Form') }}</div>

                    <div class="card-body">

                        <form method="POST" id="officialUpdateForm" action=" {{ route('save.updateViewUser') }} ">
                            @csrf
                            <input type="hidden" class="form-control" id="id" name="id"
                                value="{{ $data['id'] == null ? null : $data['id'] }}">

                            <!-- Roles Dropdown -->
                           

                            <!-- Post section for role_id == 9 -->



                         


                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $data['name'] == null ? null : $data['name'] }}" required>


                                    {{-- @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif --}}
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="email" name="email"
                                        value="{{ $data['email'] == null ? null : $data['email'] }}" required>

                                  
                                    @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mobile"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Mobile') }}</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="mobile" name="mobile" maxlength="10"
                                        value="{{ $data['mobile'] == null ? null : $data['mobile'] }}" required>
                                    {{-- @if ($errors->has('mobile'))
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    @endif --}}
                                    @error('mobile')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="roles"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Status') }}</label>
                                <div class="col-md-6">


                                    <select class="form-select" aria-label="Default select example" id="active_status"
                                        name="active_status">
                                        <option value="true"
                                            {{ old('active_status', $data['active_status']) == true ? 'selected' : '' }}>
                                            Active</option>
                                        <option value="false"
                                            {{ old('active_status',  $data['active_status']) == false ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                            </div>



                            <!-- Roles -->
                            <div class="row mb-3">
                                <label for="roles"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Roles') }}</label>
                                <div class="col-md-6">


                                    <select class="form-select" aria-label="Default select example" id="role_id"
                                        name="role_id">
                                        <option value="" selected disabled>Select</option>
                                        @foreach($rolesArray as $option)
                                        <option value="{{ $option['role_id'] }}"
                                            {{$option['role_id'] == $data['role_id'] ? 'selected' : ''}}>
                                            {{$option['role_name']}}
                                        </option>
                                        @endforeach
                                    </select>
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
                                        <option value="" selected disabled>Select</option>
                                        @foreach($departmentArray as $option)
                                        <option value="{{$option['dept_id']}}"
                                            {{$option['dept_id'] == $data['dept_id'] ? 'selected' : ''}}
                                            data-ministry="{{ $option->ministry_id }}" required>
                                            {{$option['dept_name']}}</option>

                                        @endforeach
                                    </select>



                                </div>
                            </div>
                            <!-- Ministry -->
                            <div class="row mb-3">
                                <label for="Ministrys"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Administrative Department') }}</label>

                                <div class="col-md-6">
                                    <select disabled class="form-select" aria-label="Default select example"
                                        id="ministry_id" name="ministry_id">
                                        <option value="" selected disabled>Select</option>
                                        @foreach($ministryArray as $option)
                                        <option value="{{$option['ministry_id']}}"
                                            {{$option['ministry_id'] == $data['ministry_id'] ? 'selected' : ''}}>
                                            {{$option['ministry']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>


                    </div>
<!-- For role_id== 9 -->

                    <div id="postSection9" style="display:none;">
                                <div class="row mb-3">

                                    <label for="department_signing_authority"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Post') }}</label>

                                    <div class="col-md-6">
                                        <select class="form-select" aria-label="Default select example"
                                            id="department_signing_authority" name="department_signing_authority">
                                            <option value="">Select Post </option>
                                            @foreach($departmentSigningAuthority as $option)
                                            <option value="{{ $option['id'] }}"
                                                {{ old('department_signing_authority', $data['post_id']) == $option['id'] ? 'selected' : '' }}
                                                required>
                                                {{ $option['name'] }}
                                            </option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>

                            </div>
<!-- for role_id=1,2,3,4,5,6,8 -->
                            <!-- Post section for roles other than 9 -->
                            <div id="postSectionNot9" style="display:none;">
                                <div class="row mb-3">

                                    <label for="post"
                                        class="col-md-4 col-form-label text-md-end">{{ __('Post') }}</label>

                                    <div class="col-md-6">
                                    <input type="hidden" value="{{$data['post_id']}}" id="hiddenThirdPostId">
                                        <select class="form-select  @error('post') is-invalid @enderror "
                                            aria-label="Default select example" id="post" name="post">
                                            <option value="" selected disabled>Select</option>

                                            <option value="{{$option['dsg_serial_no']}}"
                                                {{$option['dsg_serial_no'] == $data['post_id'] ? 'selected' : ''}}
                                                required>
                                                {{$option['dsg_desc']}}</option>



                                        </select>
                                    </div>
                                </div>


                            </div>
                    <!-- Post  role_id == 9 -->



                    <!-- @if($data['role_id'] != 9) -->


                    <!-- Post role_id == 1,2,3,4,5,6,7,8 -->

                   


                    <!-- @endif -->




                    <div class="row mb-3">
                        <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

                            @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                            <!-- @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror -->
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="password_confirmation"
                            class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password_confirmation" type="password" class="form-control"
                                name="password_confirmation" required autocomplete="new-password">
                            @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>




                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" id="updateBtn" class="btn btn-primary">
                                {{ __('Update') }}
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
// var _token = "{{ csrf_token() }}";

//var view_url = "{{ route('register_user_edit') }}";

$(document).ready(function() {
    $('#dept_id').change(function() {
        var selectedDepartment = $(this).children("option:selected").data('ministry');
        $('#ministry_id').val(selectedDepartment);
    });
});
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



<script nonce="{{ csp_nonce() }}">
$(document).ready(function() {
    $("#post").empty();
    $('#post').append(new Option('Select Post', ''));
    $('#post option[value=""]').attr('disabled', true);

    var id = $('#dept_id').find('option:selected').val();
    var data_dept_id = {
        'dept_id': $('#dept_id').find('option:selected').val(),
    };

    $.get('{{ route("retrieve_dept_register_user") }}', data_dept_id, function(id, textStatus, xhr) {

        //now load the result data in id post i.e. for designation  
        console.log(data_dept_id.dept_id);
        console.log(JSON.stringify(id));

        $.each(id, function(index, element) {

            $('#post').append(new Option(element.dsg_desc, element
                .dsg_serial_no)); //only value and text
            //Below is for adding extra attribute
            // $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr(
            // 'data-grade', element.group_code).appendTo('#post');

        });
        // below 2 lines is for third designation 
        var hiddenThirdPostId = $('#hiddenThirdPostId').val();
        $('#post option[value="' + hiddenThirdPostId + '"]').attr('selected', true)

    });

});



$('#post').change(function() {
    var id = $(this).find('option:selected').val();
    var item = $(this).find(item => item.dsg_serial_no === id);
    // console.log(post)   ;
    // alert($('#post option[value="'+this.value+'"]').data('grade'));


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
            // $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr(
            // 'data-grade', element.group_code).appendTo('#post');

        });

    });


})
</script>




<script>
// Show the appropriate section based on the selected role
$('#role_id').on('change', function() {

    var role_id = $(this).val();
    // alert (role_id);
    if (role_id == 9) {


        // alert("Hi")
        $('#department_signing_authority').show();
        $('#post').hide();
        // document.getElementById("department_signing_authority").style.visibility = "visible";
        // document.getElementById("post").style.visibility = "hidden";
    }
    if (role_id != 9) {
        // alert("Hi")
        $('#department_signing_authority').hide();
        $('#post').show();
        // document.getElementById("department_signing_authority").style.visibility = "hidden";
        // document.getElementById("post").style.visibility = "visible";

    }
});

// Call the change event on page load to initialize the section visibility
//  $('#role_id').change();
</script>
<script>

    // The below code is for change the post ehrn we select the roles

document.getElementById('role_id').addEventListener('change', function() {

    var selectedRole = this.value;

    document.getElementById('postSection9').style.display = 'none';
    document.getElementById('postSectionNot9').style.display = 'none';

    if (selectedRole == 9) {
        document.getElementById('postSection9').style.display = 'block';
    } else {
        // Show postSectionNot9
        document.getElementById('postSectionNot9').style.display = 'block';

      
        var postDropdown = document.getElementById('post');
        postDropdown.selectedIndex = 0;
    }
});

window.addEventListener('load', function() {

    var initialSelectedRole = document.getElementById('role_id').value;

    if (initialSelectedRole == 9) {
        document.getElementById('postSection9').style.display = 'block';
    } else {
        // Show postSectionNot9
        document.getElementById('postSectionNot9').style.display = 'block';

       
        // selectedIndex = 0; is a JavaScript statement that sets the selected index of a dropdown (<select>) element to 0.
         
        // In a dropdown, the selected index refers to the position of the selected option within the list of options.
        var postDropdown = document.getElementById('post');
        postDropdown.selectedIndex = 0;
    }
});
</script>

<script nonce="{{ csp_nonce() }}">
    document.addEventListener("DOMContentLoaded", function() {
        
        var activeStatus = document.getElementById("active_status");
        var postSelect = document.getElementById("post");
        var passwordSelect = document.getElementById("password");
        var password_confirmationSelect = document.getElementById("password_confirmation");

        // Function to enable/disable the post select
        function togglePostSelect() {
            if (activeStatus.value === "false") {
                postSelect.disabled = true;
                passwordSelect.disabled = true;
                passwordSelect.disabled = true;
                password_confirmationSelect.disabled = true;


            } else {
                postSelect.disabled = false;
                passwordSelect.disabled = false;
                password_confirmationSelect.disabled = false;
            }
        }

      
        togglePostSelect();

       
        activeStatus.addEventListener("change", function() {
            togglePostSelect();
        });
    });
</script>

@endsection