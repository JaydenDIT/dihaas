@extends('layouts.app')

@section('content')

    <?php $selected = session()->get('deptId'); ?>

    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">
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
                @if ($errors)
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Official Users\' Register Form') }}</h5>
                        <form id="officialSaveForm" method="POST" action=" {{ route('user.saveOfficialUser') }} ">
                            @csrf

                            <div class="row mb-3">
                                <label for="fullname" class="col-md-4 col-form-label ">{{ __('Name') }}</label>

                                <div class="col-md-8">
                                    <input id="fullname" type="text"
                                        class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                                        value="{{ old('fullname') }}" required autocomplete="name" autofocus>
                                    @if ($errors->has('fullname'))
                                        <span class="text-danger">{{ $errors->first('fullname') }}</span>
                                    @endif
                                </div>
                            </div>


                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label ">{{ __('Email Address') }}</label>

                                <div class="col-md-8">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mobile" class="col-md-4 col-form-label ">{{ __('Mobile') }}</label>

                                <div class="col-md-8">
                                    <input id="mobile" type="mobile" value="{{ old('mobile') }}"
                                        class="form-control @error('mobile') is-invalid @enderror" name="mobile"
                                        maxlength="10" required autocomplete="mobile">
                                    @if ($errors->has('mobile'))
                                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="roles" class="col-md-4 col-form-label ">{{ __('Roles') }}</label>
                                <div class="col-md-8">
                                    <select class="form-select" aria-label="Default select example" id="role_id"
                                        name="role_id">
                                        <option selected>Select</option>
                                        @foreach ($roles as $option)
                                            <option value="{{ $option['role_id'] }}" required>
                                                {{ $option['role_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ministry (Administrative Department) -->
                            <div class="row mb-3">
                                <label for="ministry_id"
                                    class="col-md-4 col-form-label ">{{ __('Administrative Department') }}</label>

                                <div class="col-md-8">
                                    <select class="form-select" aria-label="Default select example" id="ministry_id"
                                        name="ministry_id">
                                        <option selected disabled>Select</option>
                                        @foreach ($ministry as $option)
                                            <option value="{{ $option['adm_dept_cd'] }}">
                                                {{ $option['adm_dept_desc'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ministry_id')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Department -->
                            <!-- Getting departments under the selected administrative department -->
                            <div class="row mb-3">
                                <label for="dept_id" class="col-md-4 col-form-label ">{{ __('Department') }}</label>
                                <div class="col-md-8">
                                    <select name="field_dept_cd" id="dept_id" class="form-select">
                                        <option value="">Select Department</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Post -->
                            <!-- Getting posts under the selected department -->
                            <div class="row mb-3">
                                <label for="post_id" class="col-md-4 col-form-label ">{{ __('Post') }}</label>
                                <div class="col-md-8">
                                    <select name="dsg_serial_no" id="post_id" class="form-select">
                                        <option value="">Select Post</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label ">{{ __('Password') }}</label>

                                <div class="col-md-8">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        autocomplete="new-password" required>

                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password_confirmation"
                                    class="col-md-4 col-form-label ">{{ __('Confirm Password') }}</label>

                                <div class="col-md-8">
                                    <input id="password_confirmation" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
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
@endsection
@push('js')
    <script nonce="{{ csp_nonce() }}">
        $(document).ready(function() {
            $('#ministry_id').change(function() {
                var dept_url = "{{ route('cmis.api.department.adm_cd', '__adm_cd__') }}";
                dept_url = dept_url.replace("__adm_cd__", $(this).val());

                $.ajax({
                    url: dept_url,
                    success: (resp) => {
                        let departments = resp.field_dept;
                        console.log(departments);

                        var options = '<option value="">Select Department</option>';
                        departments.forEach(dept => {
                            options +=
                                `<option value="${dept.field_dept_cd}">${dept.field_dept_desc}</option>`;
                        });
                        $("#dept_id").html(options);
                    }
                });

            });
        });

        $('#dept_id').change(function() {
            //url for getting all post under a department id
            var post_url = "{{ route('cmis.api.post.dept_code', '__dept_cd__') }}";
            post_url = post_url.replace("__dept_cd__", $(this).val());

            $.ajax({
                url: post_url,
                success: (resp) => {
                    let posts = resp;
                    console.log(posts);

                    var options = '<option value="">Select Post</option>';
                    posts.forEach(post => {
                        options +=
                            `<option value="${post.dsg_serial_no}">${post.dsg_desc}</option>`;
                    });
                    $("#post_id").html(options);
                }
            });

        });
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
@endpush
