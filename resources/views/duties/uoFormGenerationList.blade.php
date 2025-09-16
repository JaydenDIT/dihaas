@extends('layouts.app')

@section('content')
    <div class="pt-3">
        <h3><b>Applications for Task: {{ $task->tasks_name }}</b></h3> <!-- Add this -->
        <div class="d-flex justify-content-between">
            <div class="my-2">
                <button class="btn btn-sm btn-success statusBtn" data-application_status="pending"
                    type="button">Pendings</button>
                |
                <button class="btn btn-sm btn-primary statusBtn" data-application_status="forwarded" type="button">In
                    Progress</button> |
                <button class="btn btn-sm btn-primary statusBtn" data-application_status="completed"
                    type="button">Completed</button> |
                <button class="btn btn-sm btn-primary statusBtn" data-application_status="rejected"
                    type="button">Rejected</button>
            </div>

            <div class="my-2 d-flex gap-2">
                <button type="button" class="btn btn-secondary btn-sm" id="revert-button" style="display:none;" disabled>
                    <i class="bi bi-arrow-left"></i> Revert Selected Proforma
                </button>

                <button type="button" class="btn btn-warning btn-sm" id="forward-button" style="display:none;" disabled>
                    Esign & Forward Selected Proforma <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="#" name="select_proforma_form">
                    @csrf
                    @include('duties._report-table')
                </form>
            </div>
        </div>
    </div>
@endsection
@push('modals')
    @include('duties.tasks.modals._esign_remarks_modal', ['remarks' => $remarks])
    @include('duties.tasks.modals._remarks_modal')
@endpush
@push('js')
    <script src="{{ asset('js/multiselect-proforma.js') }}"></script>
    <script src="{{ asset('js/uo-generation.js') }}"></script>
    <script type="text/javascript">
        var overall_seniority_indexes = [];
        const bulkRevertUrl = "{{ route('tasks.performa.bulkRevert', $task->tasks_id) }}";
        const bulkForwardUrl = "{{ route('duties.uo.formgeneration.bulk-esign-and-submit') }}";
        const ajaxDataListUrl = "{{ route('duties.uo.formgeneration.ajaxlist', $task->tasks_id) }}";
        const proformaDocCollectionUrl = "{{ route('duties.uo.formgeneration.document-collection') }}";

        const select_proforma_form = document.forms['select_proforma_form'];
        const forwardButton = document.getElementById('forward-button');
        const revertButton = document.getElementById('revert-button');

        /**
         * External Functions Used:
         * -----------------------------------
         * getOverallSeniorityIndexes(),
         * manipulateActionButtons(), 
         * -- are defined in public/js/mutiselect-proforma.js
         * 
         * getProformaColumnsForDataTable() -- is defined in _report-table.blade.php
         * 
         */

        $(document).ready(function() {
            setTimeout(function() {
                applicationTable();
            }, 300); // short delay ensures cookies/session are ready
            //reinitSelect2();
        });

        function applicationTable(application_status = 'pending') {
            let columns = getProformaColumnsForDataTable({
                allow_multi_select: (application_status == 'pending')
            });

            var dataTable = loadAjaxTable({
                id: "#application-table",
                url: ajaxDataListUrl,
                message: "No Performa Found",
                columns: columns,
                param: {
                    application_status: application_status
                },
                action: true,
            }, () => {
                // This function 'getOverallSeniorityIndexes' is defined in multiselect-proforma.js
                overall_seniority_indexes = getOverallSeniorityIndexes();
                manipulateActionButtons(application_status);
            });
            dataTable.on('draw.dt', () => {
                // This function 'afterDataTableLoad' is defined in multiselect-proforma.js
                afterDataTableLoad(application_status, overall_seniority_indexes);

                //disabling all the action buttons after data table loads
                revertButton.disabled = true;
                forwardButton.disabled = true;
            });
        }

        $(document).on('click', '.statusBtn', function(e) {
            e.preventDefault();
            let application_status = $(this).data('application_status');
            applicationTable(application_status); // Reload with selected status
            $(".statusBtn").removeClass('btn-success').addClass('btn-primary');
            $(this).addClass('btn-success');
        });

        //Onclick event for bulk revert button
        $('#revert-button').on('click', function(e) {
            e.preventDefault();
            $("#remarkModal").modal('show');
            $("#remarkModal .modal-title").text("Give remarks for reverting the selected proformas");
            $("#remarkModal button[type='submit']").html(`<i class="bi bi-arrow-left-circle "></i> Confirm Revert`);
            $("#remarkModal button[type='submit']").attr("class", "btn btn-warning");
            $("#action_type").val("revert");
        });

        //Onclick event for bulk forward button
        $('#forward-button').on('click', function(e) {
            e.preventDefault();

            let selectedProformas = getSelectedProformas();
            if (selectedProformas.length == 0) {
                alert("No proforma is selected.");
                return;
            }
            loadProformaPDF(selectedProformas);

            $("#esignModal").modal('show');
            $("#action_type").val("forward");
        });

        function getSelectedProformas() {
            let selected = [];
            document.querySelectorAll('.proforma-checkbox:checked').forEach((checkbox) => {
                selected.push(checkbox.value);
            });
            return selected;
        }


        function loadProformaPDF(selectedProformas) {
            let query = selectedProformas.map(id => 'selected_proforma[]=' + id).join('&');
            let url = proformaDocCollectionUrl + '?' + query;

            fetch(url)
                .then(res => res.blob())
                .then(blob => {
                    if (blob.type !== "application/pdf") {
                        alert("Invalid PDF response from server");
                        return;
                    }

                    const fileURL = URL.createObjectURL(blob);
                    currentPage = 1;
                    pageNoInput.value = 1;

                    // Use blob URL just like input[type=file]
                    renderPDF(fileURL, currentPage);

                    pdfControls.style.display = "block";
                    pdfCanvas.classList.remove("d-none");
                    disableDownloadSignedDocBtn();

                    // Base64 conversion (for your hidden input)
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        document.getElementById('pdf_base64').value = reader.result.split(',')[1];
                    };
                    reader.readAsDataURL(blob);
                })
                .catch(err => {
                    console.error("Error loading PDF:", err);
                    alert("Failed to load PDF");
                });
        }

        //Handling Esign Submit
        handleEsignSubmission((formData) => {
            let selectedProformas = getSelectedProformas();
            selectedProformas.forEach(id => {
                formData.append('selected_proforma[]', id);
            });

            fetch(bulkForwardUrl, {
                method: "POST",
                body: formData,
                headers: {
                    accept: 'application/json'
                }
            }).then(async response => {
                let jsonResult = await response.json();
                if (!response.ok) {
                    throw new Error(jsonResult.message || `${response.status}: An error has occured`);
                }
                return jsonResult;
            }).then(data => {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: data.message
                }).then(() => {
                    $("#esignModal").modal('hide');
                    applicationTable("pending");
                });

            }).catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error
                });
                console.error("Error:", error);
            });

        });

        //Handling remark submission
        handleRemarkSubmission(() => {
            const remark_form = document.forms['remark_form'];
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
                                text: data.message
                            }).then(() => {
                                $("#remarkModal").modal('hide');
                                // Disable buttons
                                document.getElementById('verify-button').disabled = true;
                                document.getElementById('forward-button').disabled = true;

                                // Reload the table with the appropriate status                                
                                applicationTable("pending");
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

        // this function 'handleRemarkSubmission' is defined in duties.tasks.modals._remarks_modal.blade.php
        handleRemarkSubmission(() => {
            let action_type = document.getElementById('action_type').value;
            bulkAction(bulkRevertUrl, action_type);
        });
    </script>
@endpush
