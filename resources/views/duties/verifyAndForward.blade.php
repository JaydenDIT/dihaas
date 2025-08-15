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
            <div>Verification</div>
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
                    <h5 class="py-1 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">Verification Form </h5>
                    <!-- Bank Details -->
                    <div class="col-sm-12 mb-3 px-4">
                        <form>
                            <div class="mb-3">
                                <label for="verification_remark" class="form-label">Verification Remark</label>
                                <textarea class="form-control" id="verification_remark" rows="3"></textarea>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-md btn-danger prevBtn" type="button" data-step="3">Revert</button>
                                <button class="btn btn-md btn-primary nextBtn" type="button" data-step="4">Verify</button>
                                <button class="btn btn-md btn-success nextBtn" type="button" data-step="4">Verify And Forward</button>
                            </div>
                        </form>


                    </div>
                </div>

            </div>
        </div>


    </div>
</div>
@endsection



@push('js')
<script src="{{ asset('js/step-wizard.js') }}"></script>
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            showStep(1);
        });
    });
</script>
@endpush