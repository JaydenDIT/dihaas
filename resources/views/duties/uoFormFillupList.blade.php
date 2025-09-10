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
                "overall_seniority_idx|nonorderable|nonsearchable",
                "dept_seniority_idx|nonorderable|nonsearchable",
                "deceased_ein",
                "deceased_emp_name",
                "deceased_doe",
                "deceased_field_dept_desc",
                "applicant_name",
                "applicant_dob",
                "proforma_submission_date",
                "proforma_status",
                "remarks"
            ];
            loadAjaxTable({
                id: "#application-table",
                url: "{{ route('duties.uo.formfillup.ajaxlist', $task->tasks_id) }}",
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
    <div class="pt-3">
        <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->
        <div class="table-container p-2">
            <div class="my-4">

                <button class="btn btn-sm btn-success statusBtn" data-application_status="pending"
                    type="button">Pending</button>
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
    </div>
@endsection
