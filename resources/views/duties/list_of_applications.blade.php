@extends('layouts.app')

@push('js')
<script type="text/javascript">
    $(function() {
        applicationTable();
        reinitSelect2();
    });

    function applicationTable(dept_id = '') {
        let columns = [
            "DT_RowIndex|nonorderable|nonsearchable",
            "ein",
            "deceased_emp_name",
            "deceased_doe",
            "appl_date",
            "applicant_name",
            "applicant_dob",
            "tasks_status",
            "dept_name"
        ];
        loadAjaxTable({
            id: "#application-table",
            url: "{{ route('tasks.performa.ajaxlist', $task->tasks_id) }}",
            message: "No Performa Found",
            columns: columns,
            action: true,
            param: {
                dept_id: dept_id
            }
        });
    }

    $(document).on('change', '#dept_id', function(e) {
        let dept_id = $(this).val();
        $('#application-table').DataTable().destroy(); // Destroy previous instance
        applicationTable(dept_id); // Reload with selected dept
    });
</script>
@endpush

@section('content')
<div class="container-fluid pt-3 px-5">
    <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->


    <div class="row">
        <div class="col-6">
            <h3>Select Department</h3>
        </div>
        <!-- Department -->
        <div class="row mb-3">
            <label for="Departments"
                class="col-md-4 col-form-label text-md-end">Department</label>

            <div class="col-md-6">
                <select class="form-select" aria-label="Default select example" id="dept_id"
                    name="dept_id">
                    <option value="" selected>All Department</option>
                    @foreach($departments as $dept)
                    <option value="{{ $dept->dept_id }}">{{ $dept->dept_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


    </div>

    <table class="report-table" id="application-table">
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