@push('css')
<style>
    /* Step Progress Tracker (New Button Style) */
    .step-tracker {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-bottom: 30px;
        position: relative;
    }

    .step-tracker::before {
        content: "";
        position: absolute;
        top: 20px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: #e0e0e0;
        z-index: 0;
    }

    .btn-go-through {
        z-index: 1;
        text-align: center;
    }

    .btn-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #ccc;
        background-color: #fff;
        color: #666;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .btn-circle.completed {
        border-color: #28a745;
        background-color: #28a745;
        color: white;
    }

    /* active should be bellow completed */
    .btn-circle.active {
        border-color: #007bff;
        background-color: #007bff;
        color: white;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
    }

    .btn-circle:hover {
        background-color: #f0f0f0;
        cursor: pointer;
    }
</style>
@endpush


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