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
    </div>


    <div class="row">

        <div class="col-md-5">
            @include('proforma.viewParts._status')
        </div>

        <div class="col-md-7">
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