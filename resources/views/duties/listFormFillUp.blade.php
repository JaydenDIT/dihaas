@extends('layouts.app')

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function() {
                applicationTable();
            }, 300);
        });

        function applicationTable(application_status = "{{ $view ?? 'pending' }}") {
            $(".statusBtn").removeClass('btn-success').addClass('btn-primary');
            // $(this).addClass('btn-success');
            $(".statusBtn[data-application_status='" + application_status + "']").addClass('btn-success');
            let user_id = document.querySelector("#user_id").value;
            let columns = [
                "DT_RowIndex|nonorderable|nonsearchable",
                "overall_seniority_idx",
                "dept_seniority_idx",
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
                url: "{{ route('duties.form.ajaxlist', $task->tasks_id) }}",
                message: "No Performa Found",
                columns: columns,
                param: {
                    application_status: application_status,
                    create_by: user_id,
                },
                action: true,
            });
        }

        $(document).on('click', '.statusBtn', function(e) {
            e.preventDefault();
            let application_status = $(this).data('application_status');
            applicationTable(application_status); // Reload with selected status
        });
    </script>
@endpush

@section('content')
    <div class="container-fluid pt-3">
        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}">
        <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->
        <div class="my-4">
            <button class="btn btn-sm btn-success statusBtn" data-application_status="pending" type="button">Draft</button>
            |
            <button class="btn btn-sm btn-primary statusBtn" data-application_status="forwarded" type="button">In
                Progress</button> |
            <button class="btn btn-sm btn-primary statusBtn" data-application_status="completed"
                type="button">Completed</button> |
            <button class="btn btn-sm btn-primary statusBtn" data-application_status="rejected"
                type="button">Rejected</button>

        </div>

        @include('duties._report-table')
    </div>
@endsection
