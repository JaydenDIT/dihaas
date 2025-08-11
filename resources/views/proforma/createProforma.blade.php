@extends('layouts.app')

@push('css')
<style>
    /* Step Wizard Container */
    .stepwizard {
        display: table;
        width: 100%;
        position: relative;
        margin-bottom: 30px;
    }

    .stepwizard-row {
        display: table-row;
        position: relative;
    }

    .stepwizard-row:before {
        top: 15px;
        bottom: 0;
        position: absolute;
        content: "";
        width: 100%;
        height: 3px;
        background-color: #e0e0e0;
        z-index: 0;
    }

    .stepwizard-step {
        display: table-cell;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    /* Step Circles */
    .btn-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        text-align: center;
        padding: 8px 0;
        font-size: 16px;
        border: 2px solid #ccc;
        background-color: #fff;
        color: #666;
        transition: all 0.3s ease;
    }

    .btn-circle.active {
        border-color: #007bff;
        background-color: #007bff;
        color: white;
        font-weight: bold;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.4);
    }

    .btn-circle.completed {
        border-color: #28a745;
        background-color: #28a745;
        color: white;
    }

    .btn-circle:hover {
        background-color: #f0f0f0;
        cursor: pointer;
    }

    /* Step Content */
    .setup-content {
        display: none;
    }

    /* Next Button */
    .nextBtn {
        background: linear-gradient(45deg, #007bff, #0056b3);
        border: none;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 30px;
        transition: background 0.3s ease;
    }

    .nextBtn:hover {
        background: linear-gradient(45deg, #0056b3, #003d80);
    }
</style>
@endpush

@section('content')
<div class="container">

    <!-- STEP WIZARD -->
    <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <a href="#step-1" class="btn btn-circle btn-step active" data-step="1">1</a>
                <p><small>Details</small></p>
            </div>
            <div class="stepwizard-step">
                <a href="#step-2" class="btn btn-circle btn-step" disabled="disabled" data-step="2">2</a>
                <p><small>Documents Upload</small></p>
            </div>
            <div class="stepwizard-step">
                <a href="#step-3" class="btn btn-circle" disabled="disabled" data-step="3">3</a>
                <p><small>Schedule</small></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <h3 class="row py-1 mb-3 text-center fw-bold">
                Proforma Form
            </h3>
        </div>
        <div class="col-sm-8 small">
            <i class="required_label"></i> : Mandatory Field. <br>
            <i class="required_labelplus"></i> : Mandatory Files but can be uploaded later if document not available
            at the moment.
        </div>
    </div>



    <!-- STEP 1 -->
    <div class="setup-content" id="step-1">
        @include('proforma.step1._collection')
        <button class="btn nextBtn float-end" type="button" data-step="1">Next</button>
    </div>

    <!-- STEP 2 -->
    <div class="setup-content" id="step-2">
        @include('proforma.step2._collection')
        <button class="btn nextBtn float-end" type="button" data-step="2">Next</button>
    </div>

    <!-- STEP 3 -->
    <div class="setup-content" id="step-3">
        <div class="form-group">
            Testing Schedule Step
        </div>
        <button class="btn btn-success float-end" type="button" id="finishBtn">Finish</button>
    </div>

</div>
@endsection

@push('js')
<script>
    const empDetailUrl = "{{ route('cmis.api.employee.detail.ein', ['id'=>'__ID__'])}}";
    const loadpost = "{{ route('cmis.api.post.dept_code', ['id'=>'__ID__'])}}";
    const loadDistrict = "{{ route('misc.option.district', ['id'=>'__ID__'])}}";
    const loadSubDivision = "{{ route('misc.option.subdivision', ['id'=>'__ID__'])}}";
    const loadDepartment = "{{ route('cmis.api.department.adm_cd', ['id'=>'__ID__'])}}";

    $(document).ready(function() {
        showStep(1);

        // Handle Next Button Click
        $('.btn-step').click(function() {
            let step = $(this).data('step');
            showStep(step);
        });
        $('.nextBtn').click(function() {
            let step = $(this).data('step');
            showStep(step + 1);
        });
        $('.prevBtn').click(function() {
            let step = $(this).data('step');
            showStep(step - 1);
        });

        function showStep(step) {
            let currentPanel = $('#step-' + step);
            $('.setup-content').hide();
            currentPanel.show();
            $('.stepwizard-step a').eq(step - 1).addClass('active').removeAttr('disabled');
        }

        // Handle Finish Button
        $('#finishBtn').click(function() {
            alert('Form completed!');
            // Optional: send final form data via AJAX here
        });

    });
</script>
<script src="{{ asset('js/proforma-create.js') }}"></script>

@endpush