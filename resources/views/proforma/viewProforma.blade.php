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
                <div>Documents Uploaded</div>
            </div>
            @if ($proforma->proforma_status == 'completed')
                <div class="btn-go-through btn-step" data-step="4">
                    <button class="btn btn-sm btn-circle statusBtn completed" type="button">4</button>
                    <div>Signed UO Document</div>
                </div>
            @endif
        </div>


        <div class="row">

            {{-- <div class="col-md-5"> --}}
            {{--  @include --}}{{-- ('proforma.viewParts._status') --}}
            {{--  </div> --}}

            <div class="col-md-12 small">
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
                        @php
                            $total_step = $proforma->proforma_status == 'completed' ? 4 : 3;
                        @endphp
                        @include('proforma.viewParts.view3._uploadDocument', [
                            'total_step' => $total_step,
                        ])
                    </div>
                </div>

                @if ($proforma->proforma_status == 'completed')
                    <!-- STEP 4 in case if the process is completed -->
                    {{-- Loading signed document from the uo_gnerations table --}}
                    <div class="setup-content" id="step-4">
                        <div class="form-group">
                            <iframe src="{{ route('duties.uo.formgeneration.document', $proforma->proforma_id) }}"
                                style="position:relative;height:700px;width: 100%;" frameborder="0"></iframe>
                        </div>
                        <div class="hstack gap-3 my-3 p-3">
                            <div class="ms-auto">
                                <button class="btn btn-md btn-success prevBtn" type="button" data-step="3">Prev</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection



@push('js')
    <script src="{{ asset('js/step-wizard.js') }}"></script>
    <script>
        const proforma_status = "{{ $proforma->proforma_status }}";
        $(document).ready(function() {
            $(document).ready(function() {
                if (proforma_status.trim() == "completed") {
                    showStep(4);
                } else {
                    showStep(1);
                }
            });
        });
    </script>
@endpush
