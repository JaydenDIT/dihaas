@extends('layouts.app')

@section('content')
    <div class="pt-3">
        <h3>Applications for Task: <b>{{ $task->tasks_name }}</b></h3>
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
                <div class="my-2 d-flex gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" id="revert-button" style="display:none;"
                        disabled>
                        <i class="bi bi-arrow-left"></i> Revert Selected Proforma
                    </button>
                    <button type="button" class="btn btn-success btn-sm" id="verify-button" style="display:none;" disabled>
                        Verify Selected Proforma <i class="bi bi-check2-all"></i>
                    </button>
                    <button type="button" class="btn btn-warning btn-sm" id="forward-button" style="display:none;"
                        disabled>
                        Forward Selected Proforma <i class="bi bi-arrow-right"></i>
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

@push('modals')
    @include('duties.tasks.modals._remarks_modal', ['remarks' => $remarks])
@endpush

@push('js')
    <script src="{{ asset('js/multiselect-proforma.js') }}"></script>
    <script type="text/javascript">
        var overall_seniority_indexes = [];
        const bulkRevertUrl = "{{ route('tasks.performa.bulkRevert', $task->tasks_id) }}";
        const bulkVerifyUrl = "{{ route('duties.verify.form.bulkVerify') }}";
        const bulkForwardUrl = "{{ route('duties.verify.form.bulkForward') }}";
        const ajaxDataListUrl = "{{ route('duties.verify.form.ajaxlist', $task->tasks_id) }}";

        const select_proforma_form = document.forms['select_proforma_form'];
        const verifyButton = document.getElementById('verify-button');
        const forwardButton = document.getElementById('forward-button');
        const revertButton = document.getElementById('revert-button');

        $(document).ready(function() {
            setTimeout(function() {
                applicationTable('un-verified');
            }, 300);
        });

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
                //"deceased_ein|nonorderable",
                {
                    data: "deceased_emp_name",
                    render: (data, type, row) => {
                        return `<div>${data}</div><div cass="text-muted"><strong>(${row.deceased_ein})</strong></div>`;
                    },
                    orderable: false
                },
                "deceased_doe|nonorderable",
                "deceased_field_dept_desc|nonorderable",
                "applicant_name|nonorderable",
                "applicant_dob|nonorderable",
                //"proforma_submission_date",
                {
                    data: "remarks",
                    render: (data, type, row) => {
                        if (data == null) {
                            return 'N/A';
                        }
                        return `
                        <div>${data.by}</div>
                        <div class="text-muted">${data.date}</div>
                        `;
                    },
                    orderable: false
                },
                "proforma_status|nonorderable",
                {
                    data: "remarks",
                    render: (data, type, row) => {
                        if (data == null) {
                            return 'N/A';
                        }
                        return `<div>${data.remark}</div>`;
                    },
                    orderable: false
                },
                {
                    data: "action",
                    render: (data, type, row) => {
                        /* if (application_status == "un-verified" || application_status == "verified") {
                            return '';
                        } */
                        return data;
                    }
                }
            ];

            var dataTable = loadAjaxTable({
                id: "#application-table",
                url: ajaxDataListUrl,
                message: "No Performa Found",
                columns: columns,
                order: [1, 'asc'],
                param: {
                    application_status: application_status
                },
                searching: !(application_status == "un-verified" || application_status == "verified"),
            }, () => {
                // This function 'getOverallSeniorityIndexes' is defined in multiselect-proforma.js
                overall_seniority_indexes = getOverallSeniorityIndexes();
            });

            dataTable.on('draw.dt', () => {
                // This function 'afterDataTableLoad' is defined in multiselect-proforma.js
                afterDataTableLoad(application_status, overall_seniority_indexes);

                //disabling all the action buttons after data table loads
                revertButton.disabled = true;
                verifyButton.disabled = true;
                forwardButton.disabled = true;
            });
        }

        $(document).on('click', '.statusBtn', function(e) {
            e.preventDefault();
            let application_status = $(this).data('application_status');
            applicationTable(application_status); // Reload with selected status
        });

        //Onclick event for bulk revert button
        $('#revert-button').on('click', function(e) {
            e.preventDefault();
            $("#remarkModal").modal('show');
            $("#remarkModal .modal-title").text("Give remarks for reverting the selected proformas");
            $("#remarkModal button[type='submit']").text("Confirm Revert");
            $("#action_type").val("revert");
            //bulkAction(bulkVerifyUrl, 'revert');
        });

        //Onclick event for bulk verify button
        $('#verify-button').on('click', function(e) {
            e.preventDefault();
            $("#remarkModal").modal('show');
            $("#remarkModal .modal-title").text("Give remarks for verification of the selected proformas");
            //bulkAction(bulkVerifyUrl, 'verify');
            $("#remarkModal button[type='submit']").text("Confirm Verify");
            $("#action_type").val("verify");
        });

        //Onclick event for bulk forward button
        $('#forward-button').on('click', function(e) {
            e.preventDefault();
            //bulkAction(bulkForwardUrl, 'forward');
            $("#remarkModal").modal('show');
            $("#remarkModal .modal-title").text("Give remarks for forwarding the selected proformas");
            $("#remarkModal button[type='submit']").text("Confirm Forward");
            $("#action_type").val("forward");
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
                    formData.append('remarks', document.getElementById('remarks').value);

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'accept': 'application/json',
                            },
                            body: formData
                        })
                        .then(async response => {
                            const data = await response.json(); // parse once

                            if (!response.ok) {
                                // throw server error message if available
                                const errorMsg = data.message || 'An error occurred while trying to ' +
                                    action +
                                    ' the selected proforma(s). Please try again.';
                                throw new Error(errorMsg);
                            }

                            return data; // if successful, return parsed JSON
                        })
                        .then(data => {

                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Selected proforma(s) have been successfully ' +
                                    (action === 'verify' ? 'verified' : 'forwarded') + '.'
                            }).then(() => {
                                $("#remarkModal").modal('hide');
                                // Disable buttons
                                document.getElementById('verify-button').disabled = true;
                                document.getElementById('forward-button').disabled = true;

                                // Reload the table with the appropriate status
                                let status = (action === 'verify') ? 'un-verified' : 'verified';
                                applicationTable(status);
                            });

                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error
                            });
                            console.error("Error:", error);
                        });

                }
            });
        }

        handleRemarkSubmission(() => {
            let action_type = document.getElementById('action_type').value;
            let url = '';
            if (action_type === 'revert') {
                url = bulkRevertUrl;
            } else if (action_type === 'verify') {
                url = bulkVerifyUrl;
            } else if (action_type === 'forward') {
                url = bulkForwardUrl;
            } else {
                alert('Invalid action type.');
                return;
            }
            bulkAction(url, action_type);
        });
    </script>
@endpush
