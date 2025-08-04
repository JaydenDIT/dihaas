@extends('layouts.app_process')

@push('js')
<script type="text/javascript">
    $(function() {
        reportTable();
    });

    function reportTable() {
        let column = [
            "DT_RowIndex|nonorderable|nonsearchable",
            'process_name',
            'process_description',
            'process_criteria',
            'total_tasks'
        ];
        loadAjaxTable({
            'id': "#report-table",
            'url': "{{ route('admin.process.ajaxlist') }}",
            'message': "No Data Found",
            'columns': column,
            'action': true
        });
    }


    $(document.body).on("click", ".edit-button", async function(e) {
        try {
            removeError();
            e.preventDefault();
            let obj = $(this).data("row");
            let edit_url = decodeURI(obj).edit_url;
            console.log(edit_url);
            await showConfirmation({
                title: `Redirecting`,
                text: `Redirecting to edit page..`,
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
            console.log(delete_url);
            await showConfirmation({
                title: `Delete`,
                text: `Are you sure you want to delete this notification?`,
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
            success_message("Successfully deleted");
            reportTable();
        } catch (error) {
            console.error(error);
        }
    });
</script>
@endpush

@section('content')

<!-- page-content" -->

<div class="container-fluid pt-3 px-5">
    <h3><b>Process List</b></h3>
    <div class="text-end mb-3">
        <a href="{{ route('admin.process.create') }}" class="btn btn-sm btn-success">
            <i class="fa fa-plus-circle"></i> Create New Process
        </a>
    </div>

    <table class="report-table" id="report-table">
        <thead>
            <th>Sl. No.</th>
            <th>Process Name</th>
            <th>Description</th>
            <th>Criteria</th>
            <th>Number of Tasks</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>


@endsection