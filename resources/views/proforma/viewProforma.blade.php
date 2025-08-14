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
<script>
    $(document).ready(function() {
        showStep(1);

        // Click to navigate to step
        $(".btn-step").click(function() {
            let step = $(this).data("step");
            // Allow clicking only if step is unlocked
            if (!$(this).hasClass("disabled")) {
                showStep(step);
            }
        });

        $(".nextBtn").click(function() {
            let step = $(this).data("step");
            showStep(step + 1);
        });

        $(".prevBtn").click(function() {
            let step = $(this).data("step");
            showStep(step - 1);
        });
    });

    function showStep(step) {
        step = step < 1 ? 1 : step > 3 ? 3 : step;
        $(".setup-content").hide();
        $("#step-" + step).show();

        $(".btn-circle").removeClass("active");
        $(".btn-circle")
            .eq(step - 1)
            .addClass("active");
    }
</script>
@endpush