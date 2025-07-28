@extends('layouts.app')
@section('content')
<!-- page-content" -->
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-5">
            <div class="card table-card shadow-lg">
                <div class="card-header">
                    <h5>Official Users</h5>
                    <div class="input-group">
                        <input type="text" id="search_user" class="form-control search-menu"
                            placeholder="Search a user...">
                        <span class="input-group-text">
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </span>
                    </div>
                </div>
                <div class="card-block">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-inline-block align-middle">
                                            <img src="{{ asset('assets/img/user.jpg') }}" alt="user image" style="width: 50px;"
                                                class="img-responsive rounded-img img-radius img-40 align-top m-r-15">
                                            <div class="d-inline-block">
                                                <h6>{{ $user->name }}</h6>
                                                <p class="text-muted m-b-0">{{ $user->role_name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <span class="f-w-700"><a
                                                href="javascript:setProfileData({{json_encode($user)}});">See Profile<i
                                                    class="fas fa-level-up-alt text-c-red m-l-10"></i></a></span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card shadow-lg">
                <div class="card-body pt-3 mutation-box ">
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <img src="{{ asset('assets/img/user.jpg') }}" alt="user image" style="width: 150px;"
                                class="img-responsive rounded-img img-size-100 img-radius align-top m-r-15">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-4">Name:</label>
                        <div class="col-sm-8"><span id="user_fullname"></span></div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-4">User Role:</label>
                        <div class="col-sm-8"><span id="user_role_name"></span></div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-4">Phone No.:</label>
                        <div class="col-sm-8"><span id="user_mobile"></span></div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-4">Email:</label>
                        <div class="col-sm-8"><span id="user_email"></span></div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-4">status:</label>
                        <div class="col-sm-8"><span id="user_active_status"></span></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script nonce="{{ csp_nonce() }}">
function setProfileData(user) {
    Object.keys(user).forEach(function(key) {
        var value = user[key];
        //console.log(key + ':' + value);
        let element = document.getElementById("user_" + key);
        if (element !== null) {
            element.innerHTML = value;
        }
    });
}
</script>
@endsection