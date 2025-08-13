@extends('layouts.app')

@push('js')
<script type="text/javascript">
    $(document).ready(function() {
        setTimeout(function() {
            applicationTable();
        }, 300); // short delay ensures cookies/session are ready
        reinitSelect2();
    });

    function applicationTable() {
        let columns = [
            "DT_RowIndex|nonorderable|nonsearchable",
            "deceased_ein",
            "deceased_emp_name",
            "deceased_doe",
            "created_at",
            "applicant_name",
            "applicant_dob",
            "proforma_status",
            "deceased_field_dept_desc"
        ];
        loadAjaxTable({
            id: "#application-table",
            url: "{{ route('duties.form.ajaxlist', $task->tasks_id) }}",
            message: "No Performa Found",
            columns: columns,
            action: true,
        });
    }
</script>
@endpush

@section('content')
<div class="container-fluid pt-3 px-5">
    <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->



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
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@endsection