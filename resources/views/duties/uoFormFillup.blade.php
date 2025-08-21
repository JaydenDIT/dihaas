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
                <div>UO Form Fill Up</div>
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
                        <h5 class="py-1 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">UO Form Fill Up
                        </h5>
                        <div class="col-sm-12 mb-3 px-4">


                            <div class="row">
                                <div class="col-sm-3 " style="text-align: left;">
                                    <strong>Applicant Name:</strong>
                                </div>
                                <div class="col-sm-9 ">
                                    <h5>{{ $proforma->applicant_name }}</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3 " style="text-align: left;">
                                    <strong>EIN of the Deceased:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <h5>{{ $proforma->deceased_ein }}</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 " style="text-align: left;">
                                    <strong>Deceased Name:</strong>
                                </div>
                                <div class="col-sm-9 ">
                                    <h5>{{ $proforma->deceased_emp_name }}</h5>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3 " style="text-align: left;">
                                    <strong>DP File No.</strong>
                                </div>
                                <div class="col-sm-9 ">
                                    <h5>{{ $proforma->uoFileSubmission->uo_file_submission_id }}</h5>
                                </div>
                            </div>

                            <form id="uo_form_fillup" name="uo_form_fillup">

                                <div class="row">
                                    <div class=" col-sm-4 form-check">
                                        <input class="form-check-input" type="radio" name="post_option"
                                            id="exampleRadios1" value="option1" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            <b> Employee Preferred Post</b>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="mb-3">
                                            @php
                                                $preferredPosts = [
                                                    [
                                                        'request_dsg_srno' => $proforma->request_dsg_srno_1,
                                                        'request_dsg_desc' => $proforma->request_dsg_desc_1,
                                                        'request_group_code' => $proforma->request_group_code_1,
                                                    ],
                                                    [
                                                        'request_dsg_srno' => $proforma->request_dsg_srno_2,
                                                        'request_dsg_desc' => $proforma->request_dsg_desc_2,
                                                        'request_group_code' => $proforma->request_group_code_2,
                                                    ],
                                                ];
                                            @endphp
                                            <select name="preferred_post" id="preferred_post"
                                                class="form-select applicant-prefered">
                                                <option value="">Select Post</option>
                                                @foreach ($preferredPosts as $post)
                                                    <option value="{{ $post['request_dsg_srno'] }}"
                                                        data-dept="{{ $proforma->deceased_field_dept_desc }}"
                                                        data-group_code="{{ $post['request_group_code'] }}">
                                                        {{ $post['request_dsg_desc'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" name="prefered_group_code"
                                                class="form-control applicant-prefered" id="group_code" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <input type="text" name="prefered_department"
                                                class="form-control applicant-prefered" id="department" readonly>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class=" col-sm-4 form-check">
                                        <input class="form-check-input" type="radio" name="post_option"
                                            id="exampleRadios2" value="option2">
                                        <label class="form-check-label" for="exampleRadios2">
                                            <b> Alloted Post when Employee preferred post is not vacant</b>
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="mb-3">
                                            <label for="post_id" class="form-label">Department </label>
                                            <select class="form-select" aria-label="Default select example"
                                                id="dept_id_option" name="dept_id_option" disabled>
                                                <option value="" selected>Select Department</option>
                                                @foreach ($deptListArray as $option)
                                                    <option
                                                        value="{{ $option['field_dept_cd'] == null ? null : $option['field_dept_desc'] }}"
                                                        required data-deptid="{{ $option['field_dept_cd'] }}">
                                                        {{ $option['field_dept_desc'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    {{-- json_encode($proforma) --}}
                                </div>
                            </form>
                        </div>





                        <div class="col-sm-12 mb-3 px-4">
                            <div class="d-flex justify-content-center gap-2 mt-5">
                                @include('duties.tasks._revert')
                                @include('duties.tasks._reject')


                                @if ($tasks['can_forward'])
                                    @if ($tasks['next'])
                                        <button class="btn btn-md btn-success forward-btn" type="button">Save and Forward
                                            to {{ $tasks['next']['tasks_name'] }} </button>
                                    @else
                                        <button class="btn btn-md btn-success forward-btn" type="button">Submit </button>
                                    @endif
                                @endif

                            </div>
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
        document.addEventListener("DOMContentLoaded", function() {
            const preferredPostSelect = document.getElementById("preferred_post");
            const group_code = document.getElementById("group_code");

            preferredPostSelect.addEventListener("change", function() {

                // get selected option
                const selectedOption = preferredPostSelect.options[preferredPostSelect.selectedIndex];

                //Filling up group code
                group_code.value = selectedOption.getAttribute("data-group_code");
                department.value = selectedOption.getAttribute("data-dept");
            });

            document.querySelectorAll(`input[type='radio'][name='post_option']`).forEach(radiioBtn => {
                radiioBtn.addEventListener('click', (e) => {
                    switch (radiioBtn.value.trim()) {
                        case "option1":
                            document.querySelectorAll(`.applicant-prefered`).forEach(element => {
                                element.disabled = false;
                            });
                            document.getElementById("dept_id_option").value = "";
                            document.getElementById("dept_id_option").disabled = true;
                            break;
                        case "option2":
                            document.querySelectorAll(`.applicant-prefered`).forEach(element => {
                                element.value = "";
                                element.disabled = true;
                            });
                            document.getElementById("dept_id_option").disabled = false;
                            break;
                    }
                });
            });
        });



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
        const forwardUrl = "{{ route('duties.verify.form.forward', $proforma->proforma_id) }}";
        const verifyUrl = "{{ route('duties.verify.form.verify', $proforma->proforma_id) }}";
        const confirmRedirectUrl = "{{ route('duties.verify.form.index', $tasks['current']['tasks_id']) }}";


        $(document).on("click", ".verify-btn", async function(e) {
            e.preventDefault();
            confirm_message = {
                title: "Verify!",
                text: "Are you sure to Verify?",
                type: "info",
            };
            $("#confirmation_form").attr("action", verifyUrl);
            $("#confirm_remarks").prop("required", false);
            $("#confirm_title").html("Write a Remarks(Optional)");
            $("#confirm_proforma_id").val(proformaId);
            $("#confirmationModal").modal("show");
        });
    </script>
    <script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush
