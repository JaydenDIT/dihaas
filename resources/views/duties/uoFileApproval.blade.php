@extends('layouts.app')



@section('content')
    <div class="container-fluid">

        @include('duties.tasks._proforma_task_heading')

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
                <div>UO File Approval</div>
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
                        <h5 class="py-1 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">UO File Approval
                        </h5>
                        <div class="col-sm-12 mb-3 px-4">
                            <iframe style="width:100%;height:100vh;"
                                src="{{ route('uo-file.get', $proforma->proforma_id) }}" frameborder="0"></iframe>

                        </div>
                        <!-- Bank Details -->
                        <div class="col-sm-12 mb-3 px-4">
                            <div class="d-flex justify-content-center gap-2 mt-5">
                                @include('duties.tasks._revert')
                                @include('duties.tasks._reject')
                                @include('duties.tasks._forward')
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('duties.tasks.modals._confirmation_of_uo_file_and_forward_modal')
@endsection



@push('js')
    <script src="{{ asset('js/step-wizard.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                showStep(1);
            });
        });
        const proformaId = "{{ $proforma->proforma_id }}";
        const rejectUrl =
            "{{ route('tasks.performa.reject', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const revertUrl =
            "{{ route('tasks.performa.revert', ['proforma_id' => $proforma->proforma_id, 'tasks_id' => $tasks['current']['tasks_id']]) }}";
        const forwardUrl = "{{ route('duties.uo.file-approval.forward', $proforma->proforma_id) }}";
        const confirmRedirectUrl = "{{ route('duties.uo.file-approval.index', $tasks['current']['tasks_id']) }}";

        //optional
        //const formSubmitUrl = "{{ route('duties.uo.file-approval.submit', $proforma->proforma_id) }}";
    </script>
    <script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush
