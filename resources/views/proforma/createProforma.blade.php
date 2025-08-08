@extends('layouts.app')



@section('content')



<div class="container mt-3 mb-4">

    <form action="#" name="upload_form" id="upload_form">
        @csrf
        @if($action == 'create')
        <input type="hidden" name="action" id="action" value="create">
        @else
        <input type="hidden" name="action" id="action" value="update">
        <input type="hidden" name="proforma_id" id="proforma_id" value="{{ $proforma?->proforma_id }}">
        @endif
        <!-- victims details -->
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




        <div class="row bg-pink">


            @include('proforma._1deceased_cmis_details');
            @include('proforma._2deceased_entry_details');
            @include('proforma._3applicant_details');
            @include('proforma._4applicant_address_current');
            @include('proforma._5applicant_address_permanent');
            @include('proforma._6prefer_post_parent_department');
            @include('proforma._7prefer_post_other_department');



            <!-- I agree -->
            <div class="row mb-4 ps-5 mt-5">
                <div class="form-check  ps-5">
                    <input class="form-check-input" type="checkbox" name="flexCheckDefault" id="flexCheckDefault"
                        required>
                    <label class="form-check-label" for="flexCheckDefault">
                        I agree all the above information are correct. I also undertake that the remaining mandatory
                        document will be uploaded.
                    </label>
                </div>

            </div>

            <div class="hstack gap-3 my-3 p-3">


                <div class="ms-auto">
                    <button class="btn btn-md btn-success submitBtn" type="button" data-status="20">SUBMIT</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('js')
<script>
    const empDetailUrl = "{{ route('cmis.api.employee.detail.ein', ['id'=>'__ID__'])}}";
    const loadpost = "{{ route('cmis.api.post.dept_code', ['id'=>'__ID__'])}}";
    const loadDistrict = "{{ route('misc.option.district', ['id'=>'__ID__'])}}";
    const loadSubDivision = "{{ route('misc.option.subdivision', ['id'=>'__ID__'])}}";
</script>
<script src="{{ asset('js/proforma-create.js') }}"></script>

@endpush