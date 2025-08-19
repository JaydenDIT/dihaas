@extends('layouts.app')

@push('css')
<style>
    .form-check-input {
        width: 1.3em;
        height: 1.3em;
        border-radius: 0.25em;
        border: 2px solid #0d6efd;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .form-check-label {
        margin-left: 0.5rem;
        cursor: pointer;
    }
</style>
@endpush

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
            <div>Documents Verification</div>
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
                <div class="bg-committee p-0 pb-5 rounded">
                    <h5 class="py-1 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">Verification Document Form </h5>

                    <div class="col-sm-12 mb-3 px-4">
                        @foreach($proforma->uploadedDocuments as $doc)
                        <div class="row align-items-center mb-2">
                            <div class="col-sm-8">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input me-2  mb-1 doc-checkbox"
                                        type="checkbox"
                                        name="uploaded_documents[]"
                                        id="doc_{{ $doc->uploaded_document_id }}"
                                        value="{{ $doc->uploaded_document_id }}"
                                        {{ $doc->verified ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold mb-0" for="doc_{{ $doc->uploaded_document_id }}">
                                        {{ $doc->document->document_name }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4 d-flex justify-content-end">
                                <a href="{{ route('duties.upload.document.load', $doc->uploaded_document_id) }}"
                                    target="_blank"
                                    class="btn btn-link">
                                    View
                                </a>
                            </div>
                        </div>
                        <hr class="hr-brown m-0">
                        @endforeach



                    </div>

                    <div class="col-sm-12 mb-3 px-4">


                        <div class="d-flex justify-content-center gap-2 mt-5">
                            <button class="btn btn-md btn-success prevBtn" type="button" data-step="3">Prev</button>
                            @include('duties.tasks._revert')
                            @include('duties.tasks._reject')

                            <!-- when checkbox is uncheck this should shows up -->
                            <button class="btn btn-md btn-primary verify-btn" type="button">Verify</button>

                            @if ($proforma->mini_sequence == "verified")
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
        if ($(".forward-btn").length > 0) {
            $(".verify-btn").hide();
        }
    });
    const proformaId = "{{ $proforma->proforma_id }}";
    const rejectUrl = "{{ route('tasks.performa.reject', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
    //need own revert need extra tasks
    const revertUrl = "{{ route('duties.verify.document.revert', $proforma->proforma_id) }}";
    const forwardUrl = "{{ route('duties.verify.document.forward', $proforma->proforma_id) }}";
    const verifyUrl = "{{ route('duties.verify.document.verify', $proforma->proforma_id) }}";
    const confirmRedirectUrl = "{{ route('duties.verify.document.index', $tasks['current']['tasks_id']) }}?view=verified";


    $(document).on("click", ".doc-checkbox", function(e) {
        let allchecked = $("input[name='uploaded_documents[]']:not(:checked)");
        //once uncheck need to verify again
        if (allchecked.length > 0) {
            $(".forward-btn").hide();
            $(".verify-btn").show();
        }
    });

    $(document).on("click", ".verify-btn", async function(e) {
        e.preventDefault();
        confirm_message = {
            title: "Verify!",
            text: "Are you sure to Verify?",
            type: "info",
        };
        let allchecked = $("input[name='uploaded_documents[]']:checked");
        if (allchecked.length === 0) {
            error_message("No documents selected for verification.");
            return;
        }
        // Clear old hidden inputs (avoid duplicates if button clicked multiple times)
        $("#confirmation_form input[name='uploaded_documents[]']").remove();
        // Append all checked values as hidden inputs
        allchecked.each(function() {
            let hidden = $("<input>")
                .attr("type", "hidden")
                .attr("name", "uploaded_documents[]")
                .val($(this).val());
            $("#confirmation_form").append(hidden);
        });
        $("#confirmation_form").attr("action", verifyUrl);
        $("#confirm_remarks").val("Documents Verification Started");
        $("#confirm_proforma_id").val(proformaId);
        $("#confirmation_form").submit();
    });
</script>
<script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush