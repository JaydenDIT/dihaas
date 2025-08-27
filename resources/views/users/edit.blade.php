@extends('layouts.app')

@section('content')

    <?php $selected = session()->get('deptId'); ?>

    <div class="">
        <div class="row justify-content-center">
            <div class="col-md-12">

                @if ($errors)
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @endif
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('Edit Official User Form') }}</h5>
                        <form id="edit_user_form" method="POST" action="{{ route('user.updateOfficialUser') }}">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->user_id }}" />
                            <div class="row mb-3">
                                <label for="fullname" class="col-md-4 col-form-label ">{{ __('Name') }}</label>

                                <div class="col-md-8">
                                    <input id="fullname" type="text"
                                        class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                                        value="{{ old('fullname', $user->fullname) }}" required autocomplete="name"
                                        autofocus>
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
                                        value="{{ old('email', $user->email) }}" required autocomplete="email">

                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="mobile" class="col-md-4 col-form-label ">{{ __('Mobile') }}</label>

                                <div class="col-md-8">
                                    <input id="mobile" type="mobile" value="{{ old('mobile', $user->mobile) }}"
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
                                        name="role_id" required>
                                        <option selected>Select</option>
                                        @foreach ($roles as $option)
                                            @php
                                                $selected = $option['role_id'] == $user->role_id ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $option['role_id'] }}" {{ $selected }}>
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
                                            @php
                                                $selected = $adm_dept_cd == $option['adm_dept_cd'] ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $option['adm_dept_cd'] }}" {{ $selected }}>
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
                                        @foreach ($departments as $dept)
                                            @php
                                                $selected =
                                                    $dept['field_dept_cd'] == $user->field_dept_cd ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $dept['field_dept_cd'] }}" {{ $selected }}>
                                                {{ $dept['field_dept_desc'] }}
                                            </option>
                                        @endforeach
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
                                        @foreach ($posts as $post)
                                            @php
                                                $selected = $post['dsg_srno'] == $user->dsg_serial_no ? 'selected' : '';
                                            @endphp
                                            <option value="{{ $post['dsg_srno'] }}" {{ $selected }}>
                                                {{ $post['dsg_desc'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" id="updateOfficialUser" class="btn btn-primary">
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
                            `<option value="${post.dsg_srno}">${post.dsg_desc}</option>`;
                    });
                    $("#post_id").html(options);
                }
            });

        });
    </script>
@endpush
