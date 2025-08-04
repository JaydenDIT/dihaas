@extends('layouts.app')

@push('js')
<script type="text/javascript">
    $(function() {
        taskTable();
    });

    function taskTable() {
        let columns = [
            "DT_RowIndex|nonorderable|nonsearchable",
            "tasks_name",
            "tasks_description",
            "tasks_duty_label",
            "roles"
        ];
        loadAjaxTable({
            id: "#duty-table",
            url: "{{ route('admin.task.ajaxlist') }}",
            message: "No Duties Found",
            columns: columns,
            action: true
        });
    }

    $(document.body).on("click", ".edit-button", async function(e) {
        try {
            removeError();
            e.preventDefault();
            let obj = $(this).data("row");
            let edit_url = decodeURI(obj).edit_url;
            await showConfirmation({
                title: `Redirecting`,
                text: `Redirecting to edit page...`,
                type: "info",
                showCancelButton: false,
                confirmButtonText: "Go"
            });
            window.location.href = edit_url;
        } catch (error) {
            console.error(error);
        }
    });

    $(document.body).on("click", ".delete-button", async function(e) {
        try {
            removeError();
            e.preventDefault();
            let obj = $(this).data("row");
            let delete_url = decodeURI(obj).delete_url;
            await showConfirmation({
                title: `Delete`,
                text: `Are you sure you want to delete this duty?`,
                type: "question",
            });
            await ajax_send_multipart({
                url: delete_url,
                method: "DELETE",
                param: {
                    _token: _token,
                },
                json: true,
            });
            success_message("Duty deleted successfully.");
            taskTable();
        } catch (error) {
            console.error(error);
        }
    });
</script>
@endpush

@section('content')
<div class="container-fluid pt-3 px-5">
    <h3><b>Duty List</b></h3>
    <div class="text-end mb-3">
        <a href="{{ route('admin.task.create') }}" class="btn btn-sm btn-success">
            <i class="fa fa-plus-circle"></i> Create New Duty
        </a>
    </div>

    <table class="report-table" id="duty-table">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Duty Name</th>
                <th>Description</th>
                <th>Activity</th>
                <th>Assigned Roles</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
@endsection