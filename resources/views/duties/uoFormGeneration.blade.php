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
                <div>UO Form Generation</div>
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

            {{-- Step 4 --}}
            <div class="setup-content" id="step-4">
                <div class="form-group card">
                    <div class="small card-body">
                        <h5 class="card-title">UO Form Generation</h5>
                        <div class="col-sm-12 mb-3">
                            {{ route('duties.uo.formgeneration.document', $proforma->proforma_id) }}
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
        const proformaId = "{{ $proforma->proforma_id }}";
        const rejectUrl =
            "{{ route('tasks.performa.reject', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const revertUrl =
            "{{ route('tasks.performa.revert', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const forwardUrl = "{{ route('duties.uo.formfillup.forward', $proforma->proforma_id) }}";
        const verifyUrl = "{{ route('duties.verify.form.verify', $proforma->proforma_id) }}";
        const confirmRedirectUrl = "{{ route('duties.verify.form.index', $tasks['current']['tasks_id']) }}";
        const proformaDashboardUrl = "{{ route('tasks.performa.all') }}";


        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                $(document).ready(function() {
                    showStep(4);
                });
            });
        });
    </script>
    <script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush
