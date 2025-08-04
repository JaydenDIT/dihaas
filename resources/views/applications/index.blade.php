@extends('layouts.app')

@push('js')
<script type="text/javascript">
    $(function() {
        applicationTable();
    });

    function applicationTable() {
        let columns = [
            "DT_RowIndex|nonorderable|nonsearchable",
            "application_id",
            "process_id",
            "current_sequence",
            "task_sequence",
            "status"
        ];
        loadAjaxTable({
            id: "#application-table",
            url: "{{ route('tasks.applications.ajaxlist', $task->tasks_id) }}",
            message: "No Applications Found",
            columns: columns,
            action: false
        });
    }
</script>
@endpush

@section('content')
<div class="container-fluid pt-3 px-5">
    <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->

    <table class="report-table" id="application-table">
        <thead>
            <tr>
                <th>Sl. No.</th>
                <th>Application ID</th>
                <th>Process ID</th>
                <th>Current Sequence</th>
                <th>Task Sequence</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@endsection