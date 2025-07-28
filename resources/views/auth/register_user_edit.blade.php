@extends('layouts.app')

@section('content')

<!-- Same with views/admin/Form/vacancy/index.blade.php -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <h3>Users List</h3>
                        </div>
                        <div class="col-6">
                            <form class="form-inline" id="ddoAststartEmpSearchBox" method="POST"
                                action="{{ url('ddo-assist/vacancySearch') }}" enctype="multipart/form-data"
                                class="was-validated">
                                <div class="row textright">
                                    @csrf

                                    <div class="col-10 marginright_textalign">
                                        <input type="text" class="form-control" placeholder="Search by Designation"
                                            name="searchItem" style="margin-right:0px;">
                                    </div>
                                    <div class="col-2 margin_textalign">
                                        <button class="btn btn-outline-secondary" type="submit"
                                            id="button-addon2">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- Add input field for search query

                 
                     <div class="col-3"> -->

                        <!-- Add input field for search query -->
                        <!-- <input type="text" id="searchInput" class="form-control" placeholder="Search..."> -->
                        <!-- </div> -->

                    </div>
                </div>

                <div class="card-body">






                    <div class="table-responsive">

                        <table class="table">
                            <thead>


                                <tr class="text-center">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email Address</th>
                                    <th>Mobile</th>
                                    <th>Department</th>
                                    <th>Administrative Department</th>
                                    <th>Post</th>
                                    <th>Roles</th>
                                    <th>Status</th> 
                                   <!--  <th>Confirm Password</th>  -->
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(empty($users))
                                <tr>
                                    <td colspan="10" class="textcenter">
                                        <b>No Data Found!</b>
                                    </td>
                                </tr>
                                @else

                                @foreach($empList as $data)


                                @if($data->active_status == 1)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td>{{ $data->name }}</td>

                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->mobile }}</td>


                                   
                                    <td>{{ $data->dept_name }}</td>


                                    <td>{{$data->ministry}} </td>



                                    @if( $data->role_id == 1 || $data->role_id == 2 || $data->role_id == 3 ||
                                    $data->role_id == 4 || $data->role_id == 5 || $data->role_id == 6 || $data->role_id == 8)

                                    <td>


                                        @foreach($data->matching_postnames as $matching_postname)
                                        {{ $matching_postname }}
                                        @endforeach
                                    </td>

                                    @else
                                    <td>
                                        @php
                                        $Id = $data->post_id;
                                        $name = optional(\App\Models\Sign2Model::find($Id));
                                        $post = $name->name;
                                        @endphp
                                        {{ $post }}
                                    </td>

                                    @endif




                                    <td>{{$data->role_name}}</td>
                                    <td>
                                        Active
                                    </td>

                                    <td>
                                        <!-- <button class="btn btn-primary w-100" id="EditUserID" type="button">Update</button> -->
                                        <a href="{{ route('official-updateViewUser',Crypt::encryptString($data->id)) }}"
                                            class="btn btn-success btn-sm" role="button" aria-disabled="true">Update</a>
                                    </td>
                                    <td>
                                        <!-- <button class="btn btn-primary w-100" id="EditUserID" type="button">Update</button> -->
                                        <a href="{{ route('official-delete',Crypt::encryptString($data->id)) }}"
                                            class="btn btn-danger btn-sm" role="button" aria-disabled="true">Delete</a>
                                    </td>

                                </tr>

                                @else
                                <tr class="inactive_color">
                                    <td>{{$data->id}}</td>
                                    <td>{{ $data->name }}</td>

                                    <td>{{ $data->email }}</td>
                                    <td>{{ $data->mobile }}</td>


                                    
                                    <td>{{ $data->dept_name }}</td>


                                    <td>{{$data->ministry}} </td>



                                    @if( $data->role_id == 1 || $data->role_id == 2 || $data->role_id == 3 ||
                                    $data->role_id == 4 || $data->role_id == 5 || $data->role_id == 6 || $data->role_id == 8)

                                    <td>


                                        @foreach($data->matching_postnames as $matching_postname)
                                        {{ $matching_postname }}
                                        @endforeach
                                    </td>

                                    @else
                                    <td>
                                        @php
                                        $Id = $data->post_id;
                                        $name = optional(\App\Models\Sign2Model::find($Id));
                                        $post = $name->name;
                                        @endphp
                                        {{ $post }}
                                    </td>

                                    @endif




                                    <td>{{$data->role_name}}</td>
                                    <td>
                                    <p class="active_status_font"><span class="badge bg-warning p-2">Inactive</span></p>
                                    </td>

                                    <td>
                                        <!-- <button class="btn btn-primary w-100" id="EditUserID" type="button">Update</button> -->
                                        <a href="{{ route('official-updateViewUser',Crypt::encryptString($data->id)) }}"
                                            class="btn btn-success btn-sm" role="button" aria-disabled="true">Update</a>
                                    </td>
                                    <td>
                                        <!-- <button class="btn btn-primary w-100" id="EditUserID" type="button">Update</button> -->
                                        <a href="{{ route('official-delete',Crypt::encryptString($data->id)) }}"
                                            class="btn btn-danger btn-sm" role="button" aria-disabled="true">Delete</a>
                                    </td>

                                </tr>

                                @endif

                                


                                <input type="hidden" id="id" name="id"
                                    value="{{ $data->id == null ? null : $data->id }}">
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
                    <!-- ------------------The below table is for Printing Purpose ------------------------------------->




                    <div class="row">

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript" src="{{ asset('assets/js/auth/forge-sha256.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/auth/register.js') }}"></script>

<script nonce="{{ csp_nonce() }}">
var _token = "{{ csrf_token() }}";



$(document).ready(function() {
    $('#dept_id').change(function() {
        var selectedDepartment = $(this).children("option:selected").data('ministry');
        $('#ministry_id').val(selectedDepartment);
    });
});
</script>

<script>

</script>




@endsection