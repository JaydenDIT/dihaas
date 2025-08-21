@extends('layouts.app')

@push('js')
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            applicationTable();
        }, 300); // short delay ensures cookies/session are ready
        reinitSelect2();
    });

    function applicationTable(application_status = 'pending') {
        let columns = [
            "DT_RowIndex|nonorderable|nonsearchable",
            "deceased_ein",
            "deceased_emp_name",
            "deceased_doe",
            "created_at",
            "applicant_name",
            "applicant_dob",
            "proforma_status",
            "deceased_field_dept_desc",
            "remarks"
        ];
        loadAjaxTable({
            id: "#application-table",
            url: "{{ route('duties.uo.file-approval.ajaxlist', $task->tasks_id) }}",
            message: "No Performa Found",
            columns: columns,
            param: {
                application_status: application_status
            },
            action: true,
        });
    }

    $(document).on('click', '.statusBtn', function(e) {
        e.preventDefault();
        let application_status = $(this).data('application_status');
        applicationTable(application_status); // Reload with selected status
        $(".statusBtn").removeClass('btn-success').addClass('btn-primary');
        $(this).addClass('btn-success');
    });
</script>
@endpush

@section('content')
<div class="container-fluid pt-3 px-5">
    <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->
    <div class="my-4">

        <button class="btn btn-sm btn-success statusBtn" data-application_status="pending" type="button">Pending</button> |
        <button class="btn btn-sm btn-primary statusBtn" data-application_status="forwarded" type="button">In Progress</button> |
        <button class="btn btn-sm btn-primary statusBtn" data-application_status="completed" type="button">Completed</button> |
        <button class="btn btn-sm btn-primary statusBtn" data-application_status="rejected" type="button">Rejected</button>

    </div>

    <table class="report-table table" id="application-table">
        <thead>
            <tr>
                <th>Seniority<br />List<br />Order</th>
                <th>EIN</th>
                <th>Deceased Name</th>
                <th>DOE</th>
                <th>Submitted Date</th>
                <th>Applicant Name</th>
                <th>Applicant DOB</th>
                <th>Status</th>
                <th>Department</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@endsection