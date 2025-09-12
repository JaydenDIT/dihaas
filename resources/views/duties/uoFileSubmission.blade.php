@extends('layouts.app')



@section('content')
    <div class="container-fluid">
        @include('duties.tasks._proforma_task_heading')
        <!-- NEW STEP TRACKER -->
        <div class="step-tracker">
            <div class="btn-go-through btn-step" data-step="1">
                <button class="btn btn-sm btn-circle statusBtn   completed" type="button">1</button>
                <div>Details</div>
            </div>
            <div class="btn-go-through btn-step" data-step="2">
                <button class="btn btn-sm btn-circle statusBtn completed" type="button">2</button>
                <div>Family Members</div>
            </div>
            <div class="btn-go-through btn-step" data-step="3">
                <button class="btn btn-sm btn-circle statusBtn completed" type="button">3</button>
                <div>Documents Upload</div>
            </div>
            <div class="btn-go-through btn-step" data-step="4">
                <button class="btn btn-sm btn-circle statusBtn completed" type="button">4</button>
                <div>UO File Submission</div>
            </div>
        </div>


        <div class="row">
            <!-- STEP 1 -->
            <div class="setup-content" id="step-1">
                @include('proforma.viewParts.view1._collection')
            </div>

            <!-- STEP 2 -->
            <div class="setup-content" id="step-2">
                @include('proforma.viewParts.view2._familyDetail')
            </div>

            <!-- STEP 3 -->
            <div class="setup-content" id="step-3">
                <div class="form-group">
                    @include('proforma.viewParts.view3._uploadDocument')
                </div>
            </div>
            <div class="setup-content" id="step-4">
                <div class="form-group">
                    <div class="bg-committee p-0 pb-5 rounded">
                        <h5 class="py-2 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">Process for
                            approval of appointment</h5>
                        <div class="col-sm-12 mb-3 px-4">
                            <form id="uo_file_submit_form" name="uo_file_submit_form">
                                <div class="form-group">
                                    <label for="select_remarks">Put remarks</label>
                                    <div class="my-2">
                                        <select id="select_remarks" class="form-select" required>
                                            <option value="">Select one from below</option>
                                            @foreach ($remarks as $remark)
                                                <option value="{{ $remark->remark }}">{{ $remark->remark }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <textarea class="form-control d-none" name="remarks" placeholder="If others, please specify your remark here."
                                        id="remarks" rows="3"></textarea>
                                </div>
                                <div class="form-group mt-4">
                                    <label class="form-label" id="fileLabel">Choose File (Office Order / Minutes of
                                        meeting)</label>

                                    <input type="file" class="form-control" name="document_file"
                                        id="upload_document_file" required />

                                    @if ($doc_submitted)
                                        <div class="mt-3">
                                            <a href="{{ route('uo-file.get', $proforma->proforma_id) }}"
                                                class="btn btn-sm btn-info" target="_blank"><i class="fa fa-download"></i>
                                                Download previously uploaded
                                                file</a>
                                        </div>
                                    @endif

                                </div>
                            </form>
                        </div>

                        <!-- Bank Details -->
                        <div class="col-sm-12 mb-3 px-4">
                            <div class="d-flex justify-content-center gap-2 mt-5">
                                @include('duties.tasks._revert')
                                @include('duties.tasks._reject')
                                {{-- 
                                @if ($proforma->mini_sequence === 'file_uploaded')
                                    @include('duties.tasks._forward')
                                @endif --}}
                                <div class="col-sm-4">
                                    <button type="button" class="btn btn-md btn-success btn-sm file-submit-btn"
                                        data-bs-html="true" data-bs-toggle="tooltip" data-bs-placement="right"
                                        title="Forward to next step: <u>{{ $tasks['next']['tasks_name'] }}</u>. Click to forward.">
                                        Submit & Forward
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>


        </div>
    </div>

    @include('duties.tasks.modals._confirmation_modal')
@endsection



@push('js')
    <script src="{{ asset('js/step-wizard.js') }}"></script>
    <script>
        $(document).ready(function() {
            showStep(1);
            $("#select_remarks").on("change", function() {
                if ($(this).val() === "Others") {
                    $("#remarks").removeClass("d-none");
                    $("#remarks").val('');
                } else {
                    $("#remarks").addClass("d-none");
                    $("#remarks").val($(this).val());
                }
            });
        });
        const proformaId = "{{ $proforma->proforma_id }}";
        const rejectUrl =
            "{{ route('tasks.performa.reject', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const revertUrl =
            "{{ route('tasks.performa.revert', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const forwardUrl = "{{ route('duties.uo.filesubmission.forward', $proforma->proforma_id) }}";
        const confirmRedirectUrl = "{{ route('duties.uo.filesubmission.index', $tasks['current']['tasks_id']) }}";

        //optional
        const formSubmitUrl = "{{ route('duties.uo.filesubmission.submit', $proforma->proforma_id) }}";


        $(document).on("click", ".file-submit-btn", async function(e) {
            e.preventDefault();
            try {
                const form = document.getElementById("uo_file_submit_form");
                const param = new FormData(form);
                await validateForm(form);
                let res = await ajax_send_multipart({
                    url: formSubmitUrl,
                    param: param,
                });

                console.log(res);

                await showConfirmation({
                    title: "Successfully Save",
                    text: res.message,
                    type: "success",
                    showCancelButton: false,
                });
                //window.location.reload();
                //window.location.href = confirmRedirectUrl;
                window.location.assign(confirmRedirectUrl)

            } catch (error) {
                console.log(error);
            }


        });
    </script>
    <script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush
