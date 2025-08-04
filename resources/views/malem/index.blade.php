@extends('layouts.app_process')

@push('js')
<script type="text/javascript">
    $(function() {
        applicationTable();
    });

    function applicationTable() {
        let columns = [
            "DT_RowIndex|nonorderable|nonsearchable",
            "deceased_emp_name",
            "process_id",
            "process_sequence",
            "task_sequence",
            "status"
        ];
        loadAjaxTable({
            id: "#application-table",
            url: "{{ route('tasks.performa.ajaxlist', $task->tasks_id) }}",
            message: "No Performa Found",
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