@extends('layouts.app_process')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Role Form -->
        <div class="col-12">
            <form id="roleForm" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="role_id" id="role_id">

                <div class="row align-items-center mb-3">
                    <label class="col-sm-2 text-end">Role Name : </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="role_name" name="role_name" required>
                        <div class="invalid-feedback">Role name is required.</div>
                    </div>
                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-success" id="roleSubmitBtn">Add New Role</button>
                    </div>
                </div>
            </form>
        </div>


        <!-- Role Table -->
        <div class="col-12">
            <table class="table table-bordered w-100" id="roleTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Assigned Duties</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    let roleTable;

    $(function() {
        roleTable = loadAjaxTable({
            id: '#roleTable',
            url: "{{ route('admin.role.ajaxlist') }}",
            columns: [
                "DT_RowIndex|nonorderable|nonsearchable",
                "role_name",
                "duties",
                "action|nonorderable|nonsearchable"
            ]
        });

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
                roleTable.ajax.reload(null, false);
                resetForm(this);
                $('#roleSubmitBtn').text('Add New Role');
            } catch (err) {
                console.error("Role form error:", err);
            }
        });

        $(document).on('click', '.edit-btn', function() {
            const data = decodeURI($(this).data('row')).edit_data;
            console.log(data);
            setMatchValue(data);
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
                roleTable.ajax.reload(null, false);
            } catch (err) {
                console.error("Delete error:", err);
            }
        });
    });
</script>
@endpush