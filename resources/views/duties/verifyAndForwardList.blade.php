@extends('layouts.app')
@push('css')
    <style>
        .proforma-checkbox {
            cursor: pointer;
        }

        button:disabled {
            cursor: not-allowed !important;
        }
    </style>
@endpush

@push('js')
    <script src="{{ asset('js/multiselect-proforma.js') }}"></script>
    <script type="text/javascript">
        var overall_seniority_indexes = [];
        const bulkVerifyUrl = "{{ route('duties.verify.form.bulkVerify') }}";
        const bulkForwardUrl = "{{ route('duties.verify.form.bulkForward') }}";
        const select_proforma_form = document.forms['select_proforma_form'];

        $(document).ready(function() {
            setTimeout(function() {
                applicationTable('un-verified');
            }, 300);
        });

        //function to manipulate the verify button and forward button based on the application status
        function manipulateActionButtons(application_status) {
            if (application_status == 'un-verified') {
                $('#verify-button').show();
                $('#forward-button').hide();
            } else if (application_status == 'verified') {
                $('#verify-button').hide();
                $('#forward-button').show();
            } else {
                $('#verify-button').hide();
                $('#forward-button').hide();
            }
        }

        function applicationTable(application_status = "{{ $view ?? 'un-verified' }}") {
            $(".statusBtn").removeClass('btn-success').addClass('btn-primary');
            $(this).addClass('btn-success');
            $(".statusBtn[data-application_status='" + application_status + "']").addClass('btn-success');

            manipulateActionButtons(application_status);

            let columns = [{
                    data: 'DT_RowIndex',
                    render: (data, type, row) => {
                        if (application_status == "un-verified" || application_status == "verified") {
                            return `<input type="checkbox" 
                            data-overall_seniority_idx="${row.overall_seniority_idx}" class="select-single form-check-input proforma-checkbox" name="selected_proforma[]" 
                            value="${row.proforma_id}" disabled/>`;
                        }
                        return row.DT_RowIndex;
                    },
                    orderable: false,
                    searchable: false
                },
                "overall_seniority_idx|nonorderable|nonsearchable",
                "dept_seniority_idx|nonorderable|nonsearchable",
                "deceased_ein|nonorderable",
                "deceased_emp_name|nonorderable",
                "deceased_doe|nonorderable",
                "deceased_field_dept_desc|nonorderable",
                "applicant_name|nonorderable",
                "applicant_dob|nonorderable",
                "proforma_submission_date|nonorderable",
                "proforma_status|nonorderable",
                "remarks|nonorderable"
            ];
            var dataTable = loadAjaxTable({
                id: "#application-table",
                url: "{{ route('duties.verify.form.ajaxlist', $task->tasks_id) }}",
                message: "No Performa Found",
                columns: columns,
                order: [1, 'asc'],
                param: {
                    application_status: application_status
                },
                action: true,
                searching: !(application_status == "un-verified" || application_status == "verified"),
            }, () => {
                // This function 'getOverallSeniorityIndexes' is defined in multiselect-proforma.js
                overall_seniority_indexes = getOverallSeniorityIndexes();
            });

            dataTable.on('draw.dt', () => {
                // This function 'afterDataTableLoad' is defined in multiselect-proforma.js
                afterDataTableLoad(application_status, overall_seniority_indexes);
            });
        }

        $(document).on('click', '.statusBtn', function(e) {
            e.preventDefault();
            let application_status = $(this).data('application_status');
            applicationTable(application_status); // Reload with selected status
        });

        //Onclick event for bulk verify button
        $('#verify-button').on('click', function(e) {
            e.preventDefault();
            bulkAction(bulkVerifyUrl, 'verify');
        });

        //Onclick event for bulk forward button
        $('#forward-button').on('click', function(e) {
            e.preventDefault();
            bulkAction(bulkForwardUrl, 'forward');
        });

        //Defining bulk action function
        function bulkAction(url, action) {
            let selectedProformas = [];
            document.querySelectorAll('.proforma-checkbox:checked').forEach((checkbox) => {
                selectedProformas.push(checkbox.value);
            });

            if (selectedProformas.length === 0) {
                alert('Please select at least one proforma to ' + action + '.');
                return;
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You are about to " + action + " these selected proformas!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, " + action + " it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create FormData from your form
                    let formData = new FormData(select_proforma_form);
                    formData.append('remarks', 'Bulk ' + action + ' by {{ Auth::user()->fullname }}');

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Network response was not ok");
                            }
                            return response.json(); // if backend returns JSON
                        })
                        .then(data => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Selected proforma(s) have been successfully ' +
                                    (action === 'verify' ? 'verified' : 'forwarded') + '.'
                            });

                            // Disable buttons
                            document.getElementById('verify-button').disabled = true;
                            document.getElementById('forward-button').disabled = true;

                            // Reload the table with the appropriate status
                            let status = (action === 'verify') ? 'un-verified' : 'verified';
                            applicationTable(status);
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while trying to ' + action +
                                    ' the selected proforma(s). Please try again.'
                            });
                            console.error("Error:", error);
                        });

                }
            });
        }
    </script>
@endpush

@section('content')
    <div class="pt-3">
        <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->
        <div class="table-container p-2">
            <div class="d-flex justify-content-between">
                <div class="my-2">
                    <button class="btn btn-sm btn-success statusBtn" data-application_status="un-verified"
                        type="button">Un-Verified</button> |
                    <button class="btn btn-sm btn-primary statusBtn" data-application_status="verified"
                        type="button">Verified</button> |
                    <button class="btn btn-sm btn-primary statusBtn" data-application_status="forwarded" type="button">In
                        Progress</button> |
                    <button class="btn btn-sm btn-primary statusBtn" data-application_status="completed"
                        type="button">Completed</button> |
                    <button class="btn btn-sm btn-primary statusBtn" data-application_status="rejected"
                        type="button">Rejected</button>
                </div>
                <div class="my-2">
                    <button type="button" class="btn btn-success btn-sm" id="verify-button" style="display:none;" disabled>
                        Verify Selected Proforma <i class="bi bi-check2-all"></i>
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" id="forward-button" style="display:none;"
                        disabled>
                        Forward Selected Proforma <i class="bi bi-forward"></i>
                    </button>
                </div>
            </div>
            <form action="#" name="select_proforma_form">
                @csrf
                @include('duties._report-table')
            </form>
        </div>
    </div>
@endsection
