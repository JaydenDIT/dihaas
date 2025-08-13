@extends('layouts.app')

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

@section('content')
<div class="container">

    <!-- NEW STEP TRACKER -->
    <div class="step-tracker">
        <div class="btn-go-through btn-step" data-step="1">
            <button class="btn btn-sm btn-circle statusBtn   {{ $current_step > 0 ? 'completed' : '' }}" type="button">1</button>
            <div>Details</div>
        </div>
        <div class="btn-go-through {{$current_step >= 1 ? 'btn-step' : ''}}" data-step="2">
            <button class="btn btn-sm btn-circle statusBtn {{ $current_step > 1 ? 'completed' : '' }}" type="button">2</button>
            <div>Family Members</div>
        </div>
        <div class="btn-go-through {{$current_step >= 2 ? 'btn-step' : ''}}" data-step="3">
            <button class="btn btn-sm btn-circle statusBtn {{ $current_step > 2 ? 'completed' : '' }}" type="button">3</button>
            <div>Documents Upload</div>
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
        @include('proforma.step2._familyDetail')
    </div>

    <!-- STEP 3 -->
    <div class="setup-content" id="step-3">
        <div class="form-group">
            @include('proforma.step3._uploadDocument')
        </div>
    </div>
    @endif

</div>
@endsection


<?php

if ($action == 'create') {
    $hiddenClass = [
        "under_expire_on_duty_flag",
        "under_applicant_qualification_id",
    ];
} else {

    if ($proforma->expire_on_duty == 0) {
        $hiddenClass[] = "under_expire_on_duty_flag";
    }


    $othersId = null;
    foreach ($qualifications as $q) {
        if (isset($q['qualification_name']) && strtolower($q['qualification_name']) === 'others') {
            $othersId = $q['qualification_id'];
            break;
        }
    }

    if ($othersId !== null && $proforma->applicant_qualification_id != $othersId) {
        $hiddenClass[] = "under_applicant_qualification_id";
    }
}

?>



@push('js')
<script>
    const action = "{{ $action }}";
    const proforma_id = "{{ $proforma->proforma_id ?? '' }}";
    const current_step = "{{ $current_step ?? '1' }}";
    //checking data or form fill to hide
    const hiddenClass = <?= json_encode($hiddenClass ?? []) ?>;




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
    let requiredDocumentsLeft = <?= json_encode($requiredDocumentsLeft ?? []) ?>;
    //family
    const familyDetailAdd = "{{ route('duties.family.store', ['id'=>'__ID__']) }}";
    const familyDetailUpdate = "{{ route('duties.family.update', ['id'=>'__ID__']) }}";
    const familyDetailDelete = "{{ route('duties.family.destroy', ['id'=>'__ID__']) }}";
    //form submit
    const completeUploadDocument = "{{ route('duties.form.completeUploadDocument', ['id'=>'__ID__']) }}";
    const completeFamilyDetail = "{{ route('duties.form.completeFamilyDetail', ['id'=>'__ID__']) }}";
    const formSubmitUrl = "{{ route('duties.form.final.submit', ['id'=>'__ID__']) }}";
</script>
<script src="{{ asset('js/proforma-create.js') }}"></script>

@endpush