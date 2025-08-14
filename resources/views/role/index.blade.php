@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <!-- Role Form -->

    <form id="roleForm" class="needs-validation">
        @csrf

        <div class="row">
            <input type="hidden" name="role_id" id="role_id">
            <div class="row col-sm-5 mb-2">
                <label class="col-form-label required_label"><b>Role Name : </b></label>
                <div>
                    <input type="text" name="role_name" id="role_name" placeholder="Role Name" class="form-control is_name"
                        maxlength="75" required>
                </div>
            </div>
            <div class="row col-sm-5 mb-2">
                <label class="col-form-label required_label"><b>Role Group : </b></label>
                <div>
                    <select name="role_group" id="role_group" class="form-select" required>
                        <option value="">Select Role Group</option>
                        <option value="superadmin">Superadmin</option>
                        <option value="single_department">Single department</option>
                        <option value="all_department">All department</option>
                        <option value="citizen">Citizen</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-2">
                <label class="col-form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-success" id="roleSubmitBtn">Add New Role</button>
                </div>
            </div>
    </form>



    <!-- Role Table -->
    <div class="row mt-4">
        <table class="table table-bordered w-100" id="roleTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Assigned Duties</th>
                    <th>Role Group</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        setTimeout(function() {
            roleTable();
        }, 300);


        function roleTable() {
            loadAjaxTable({
                id: '#roleTable',
                url: "{{ route('admin.role.ajaxlist') }}",
                columns: [
                    "DT_RowIndex|nonorderable|nonsearchable",
                    "role_name",
                    "duties",
                    "role_group",
                    "action|nonorderable|nonsearchable"
                ]
            });
        }

        $('#roleForm').on('submit', async function(e) {
            e.preventDefault();
            try {
                await validateForm(this);
                const data = getFormDataAsJson(this);
                const id = data.role_id;
                const url = id ?
                    "{{ route('admin.role.update', ':id') }}".replace(':id', id) :
                    "{{ route('admin.role.store') }}";

                const method = id ? 'PUT' : 'POST';

                await ajax_send_multipart({
                    url,
                    method,
                    param: data,
                    json: true
                });

                success_message(id ? "Role updated" : "Role created");
                roleTable();
                resetForm(this);
                $('#roleSubmitBtn').text('Add New Role');
            } catch (err) {
                console.error("Role form error:", err);
            }
        });

        $(document).on('click', '.edit-btn', function(e) {
            e.preventDefault();
            const data = decodeURI($(this).data('row')).edit_data;
            // console.log(data);

            $('#role_id').val(data.role_id);
            $('#role_name').val(data.role_name);
            $('#role_group').val(data.role_group);
            $('#roleSubmitBtn').text('Update Role');
            $('#role_name').focus();

        });



        $(document).on('click', '.delete-btn', async function() {
            const url = decodeURI($(this).data('row')).delete_url;
            try {
                await showConfirmation({
                    title: "Delete",
                    text: "Are you sure?",
                    type: "warning"
                });
                await ajax_send_multipart({
                    url,
                    method: 'DELETE',
                    param: {
                        _token
                    },
                    json: true
                });
                success_message("Role deleted");
                roleTable()
            } catch (err) {
                console.error("Delete error:", err);
            }
        });
    });
</script>
@endpush