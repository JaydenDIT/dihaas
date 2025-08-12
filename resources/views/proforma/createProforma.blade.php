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
                <a href="#" class="btn btn-circle btn-step active" data-step="1">1</a>
                <p><small>Details</small></p>
            </div>
            <div class="stepwizard-step">
                <a href="#" class="btn btn-circle btn-step" disabled="disabled" data-step="2">2</a>
                <p><small>Documents Upload</small></p>
            </div>
            <div class="stepwizard-step">
                <a href="#" class="btn btn-circle" disabled="disabled" data-step="3">3</a>
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
    </div>

    @if($action == 'edit')
    <!-- STEP 2 -->
    <div class="setup-content" id="step-2">
        @include('proforma.step2._collection')
    </div>

    <!-- STEP 3 -->
    <div class="setup-content" id="step-3">
        <div class="form-group">
            Testing Schedule Step
        </div>
    </div>
    @endif

</div>
@endsection

@push('js')
<script>
    const action = "{{ $action }}";
    const proforma_id = "{{ $proforma->proforma_id ?? '' }}";
    //misc
    const getDistrictByStateId = "{{ route('misc.option.district', ['id'=>'__ID__'])}}";
    const getSubDivisionByDistrictId = "{{ route('misc.option.subdivision', ['id'=>'__ID__'])}}";
    //cmis api
    const getDepartmentByAdmCd = "{{ route('cmis.api.department.adm_cd', ['id'=>'__ID__'])}}";
    const searchEmpByEIN = "{{ route('cmis.api.employee.detail.ein', ['id'=>'__ID__'])}}";
    const getPostByDeptCode = "{{ route('cmis.api.post.dept_code', ['id'=>'__ID__'])}}";
    //proforma
    const saveProforma = "{{ route('duties.proforma.store') }}";
    const editProforma = "{{ route('duties.proforma.edit', ['id'=>'__ID__']) }}";
    const updateProforma = "{{ route('duties.proforma.update', ['id'=>'__ID__']) }}";
    //documents
    const uploadDocumentSave = "{{ route('duties.upload.document.store') }}";
</script>
<script src="{{ asset('js/proforma-create.js') }}"></script>

@endpush