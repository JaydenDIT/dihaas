@extends('layouts.app')



@section('content')
<div class="container-fluid">



    <div class="row">
        <div class="col-sm-4">
            <h3 class="row py-1 mb-3 text-center fw-bold">
                Proforma View
            </h3>
        </div>
    </div>

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
                    <h5 class="py-1 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">UO File Submission Form </h5>
                    <div class="col-sm-12 mb-3 px-4">

                        <form id="uo_file_submit_form" name="uo_file_submit_form">

                            <div class="form-group">
                                <label class="form-label" id="fileLabel">Choose File</label>
                                <input type="file" class="form-control" name="document_file" id="upload_document_file" required>
                            </div>

                            <button class="btn btn-md btn-success file-submit-btn" type="button">Upload File </button>

                        </form>
                    </div>




                    <!-- Bank Details -->
                    <div class="col-sm-12 mb-3 px-4">
                        <div class="d-flex justify-content-center gap-2 mt-5">
                            @include('duties.tasks._revert')
                            @include('duties.tasks._reject')

                            @if($proforma->mini_sequence === "file_uploaded" )
                            @include('duties.tasks._forward')
                            @endif

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
        $(document).ready(function() {
            showStep(1);
        });
    });
    const proformaId = "{{ $proforma->proforma_id }}";
    const rejectUrl = "{{ route('tasks.performa.reject', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
    const revertUrl = "{{ route('tasks.performa.revert', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
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
            await showConfirmation({
                title: "Successfully Save",
                text: "You can now Forward",
                type: "success",
                showCancelButton: false,
            });
            window.location.reload();


        } catch (error) {
            console.log(error);
        }


    });
</script>
<script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush