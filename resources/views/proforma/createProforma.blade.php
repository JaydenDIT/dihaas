@extends('layouts.app')

@push('styles')
<style nonce="{{ csp_nonce() }}">
    .bg-pink {
        background-color: #f2a29070;
    }
</style>
@endpush

@section('content')



<div class="container mt-3 mb-4">

    <form action="#" name="upload_form" id="upload_form">
        @csrf
        @if($action == 'create')
        <input type="hidden" name="action" id="action" value="create">
        @else
        <input type="hidden" name="action" id="action" value="update">
        <input type="hidden" name="application_id" id="application_id" value="{{ $application_data?->application_id }}">
        @endif
        <!-- victims details -->
        <div class="row">
            <div class="col-sm-4">
                <h3 class="row py-1 mb-3 text-center fw-bold">
                    @if(in_array(Auth::user()->role->role_type, ['state_user', 'district_user']))
                    SUO Moto
                    @endif
                    Application Form
                </h3>
            </div>
            <div class="col-sm-8 small">
                <i class="required_label"></i> : Mandatory Field. <br>
                <i class="required_labelplus"></i> : Mandatory Files but can be uploaded later if document not available
                at the moment.
            </div>

        </div>


        <div class="row bg-pink">


            @include('application.formparts._1victimdetail')
            @include('application.formparts._2victimdecease')
            @include('application.formparts._3victimaddress')
            @include('application.formparts._4incidentaddress')
            @include('application.formparts._5categoryloss')
            @include('application.formparts._6firandmedical')
            @include('application.formparts._7courttrial')
            @include('application.formparts._8othercompensation')
            @include('application.formparts._9undertaking')
            @include('application.formparts._10otherdetail')
            @include('application.formparts._11bankdetail')


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
                <div class="ps-5">
                    <ol>
                        <li class="li-death_certificate_doc under_victim_decease_flag">Victim death certificate</li>
                        <li class="li-victim_address_doc">Victim Address Proof</li>
                        <li class="li-fir_lodge_doc">FIR Copy</li>
                        <li class="li-medical_exam_doc under_medical_exam_flag">Medical Report</li>
                        <li class="li-court_compensation_doc under_court_compensation_flag">Order of Court Trial</li>
                        <li class="li-agency_compensation_doc under_agency_compensation_flag">Upload Details of Any other Compensation</li>
                        <li class="li-bank_doc">Bank Passbook/Cancel Cheque</li>
                    </ol>
                </div>
            </div>

            <div class="hstack gap-3 my-3 p-3">
                @if(in_array(Auth::user()->role->role_type, ['client_user']))
                <div>
                    <button class="btn btn-md btn-info submitBtn" type="button" data-status="10">SAVE AS
                        DRAFT</button>
                </div>
                @endif

                <div class="ms-auto">
                    <button class="btn btn-md btn-success submitBtn" type="button" data-status="20">SUBMIT</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection