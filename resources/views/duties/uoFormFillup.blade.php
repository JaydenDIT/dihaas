@extends('layouts.app')



@section('content')
    <div class="container-fluid">
        @include('duties.tasks._proforma_task_heading')
        <div class="row">
            <div class="col-sm-8">
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
                        <div>UO File (Approved)</div>
                    </div>
                    <div class="btn-go-through btn-step" data-step="5">
                        <button class="btn btn-sm btn-circle statusBtn completed" type="button">4</button>
                        <div>UO Form Fill Up</div>
                    </div>
                </div>
                <div class="">
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

                    {{-- STEP 4 --}}
                    <div class="setup-content" id="step-4">
                        <div class="form-group">
                            <div class="bg-committee p-0 pb-5 rounded">
                                <h5 class="py-1 ps-3 mt-0 bg-success bg-gradient text-white fw-bold rounded-top">
                                    Approved UO File
                                </h5>
                                <div class="col-sm-12 mb-3 px-4">
                                    <iframe style="width:100%;height:100vh;"
                                        src="{{ route('uo-file.get', $proforma->proforma_id) }}" frameborder="0"></iframe>

                                </div>
                            </div>
                            <div class="hstack gap-3 my-3 p-3">
                                <div class="ms-auto">
                                    <button class="btn btn-md btn-success prevBtn" type="button"
                                        data-step="4">Prev</button>
                                    @if (isset($total_step) && $total_step > 4)
                                        <button class="btn btn-md btn-success nextBtn" type="button"
                                            data-step="4">Next</button>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Step 5 --}}
                    <div class="setup-content" id="step-5">
                        <div class="form-group card">
                            <div class="small card-body">
                                <h5 class="card-title">UO Form Fill Up</h5>
                                <div class="col-sm-12 mb-3">
                                    <div class="row mb-2">
                                        <div class="col-sm-4" style="text-align: left;">
                                            <strong>Applicant Name:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <span>{{ $proforma->applicant_name }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-sm-4 " style="text-align: left;">
                                            <strong>EIN of the Deceased:</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <span>{{ $proforma->deceased_ein }}</span>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-4 " style="text-align: left;">
                                            <strong>Deceased Name:</strong>
                                        </div>
                                        <div class="col-sm-8 ">
                                            <span>{{ $proforma->deceased_emp_name }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-4" style="text-align: left;">
                                            <strong>DP File No.</strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <span>{{ $proforma->uoFileSubmission->uo_file_submission_id }}</span>
                                        </div>
                                    </div>

                                    <form id="uo_form_fillup" name="uo_form_fillup" method="POST"
                                        action="{{ route('duties.uo.formfillup.forward', $proforma->proforma_id) }}">
                                        @csrf
                                        <input type="hidden" name="proforma_id" value="{{ $proforma->proforma_id }}">

                                        {{-- When applicant's choice is available --}}
                                        <div class="row">
                                            <div class=" col-sm-4 form-check">
                                                <input class="form-check-input" type="radio" name="post_option"
                                                    id="exampleRadios1" value="applicant-prefered" checked>
                                                <label class="form-check-label cursor-pointer" for="exampleRadios1">
                                                    <b> Employee Preferred Post</b>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                @php
                                                    $preferredPosts = [
                                                        /** The first two must belong to the department the deceased one once belonged **/
                                                        [
                                                            'request_dsg_srno' => $proforma->request_dsg_srno_1,
                                                            'request_dsg_desc' => $proforma->request_dsg_desc_1,
                                                            'request_group_code' => $proforma->request_group_code_1,
                                                            'request_field_dept_cd' =>
                                                                $proforma->deceased_field_dept_cd, // Same as deceased one
                                                            'request_field_dept_desc' =>
                                                                $proforma->deceased_field_dept_desc, // Same as deceased one
                                                            'request_adm_dept_cd' => $proforma->deceased_adm_dept_cd, // Same as deceased one
                                                            'request_adm_dept_desc' =>
                                                                $proforma->deceased_adm_dept_desc, // Same as deceased one
                                                        ],
                                                        [
                                                            'request_dsg_srno' => $proforma->request_dsg_srno_2,
                                                            'request_dsg_desc' => $proforma->request_dsg_desc_2,
                                                            'request_group_code' => $proforma->request_group_code_2,
                                                            'request_field_dept_cd' =>
                                                                $proforma->deceased_field_dept_cd, // Same as deceased one
                                                            'request_field_dept_desc' =>
                                                                $proforma->deceased_field_dept_desc, // Same as deceased one
                                                            'request_adm_dept_cd' => $proforma->deceased_adm_dept_cd, // Same as deceased one
                                                            'request_adm_dept_desc' =>
                                                                $proforma->deceased_adm_dept_desc, // Same as deceased one
                                                        ],

                                                        /** The applicant has the right to prefer post from other departments also **/
                                                        [
                                                            'request_dsg_srno' => $proforma->request_dsg_srno_3,
                                                            'request_dsg_desc' => $proforma->request_dsg_desc_3,
                                                            'request_group_code' => $proforma->request_group_code_3,
                                                            'request_field_dept_cd' =>
                                                                $proforma->request_field_dept_cd_3,
                                                            'request_field_dept_desc' =>
                                                                $proforma->request_field_dept_desc_3,
                                                            'request_adm_dept_cd' => $proforma->request_adm_dept_cd_3,
                                                            'request_adm_dept_desc' =>
                                                                $proforma->request_adm_dept_desc_3,
                                                        ],
                                                    ];
                                                @endphp
                                                <div class="mb-3">
                                                    <div class="row mb-2">
                                                        <div class="col-6"><label for="applicant_prefered_post_id">Prefered
                                                                Post</label></div>
                                                        <div class="col-6">No. of vaccant posts: <span
                                                                id="applicant_preferred_vacc_posts" class="fw-bold">0</span>
                                                        </div>
                                                    </div>

                                                    <select name="applicant_prefered_post_id" data-prefered="applicant"
                                                        id="applicant_prefered_post_id"
                                                        class="form-select applicant-prefered post-select" required>
                                                        <option value="">Select Post</option>
                                                        @foreach ($preferredPosts as $post)
                                                            <option value="{{ $post['request_dsg_srno'] }}"
                                                                data-dept="{{ $post['request_field_dept_desc'] }}"
                                                                data-dept_cd="{{ $post['request_field_dept_cd'] }}"
                                                                data-adm_dept_cd="{{ $post['request_adm_dept_cd'] }}"
                                                                data-adm_dept_desc="{{ $post['request_adm_dept_desc'] }}"
                                                                data-group_code="{{ $post['request_group_code'] }}">
                                                                {{ $post['request_dsg_desc'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="applicant_prefered_post_desc"
                                                        class="applicant-prefered" id="applicant_prefered_post_desc"
                                                        value="">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="applicant_prefered_group_code">Prefered Group Code:</label>
                                                    <input type="text" name="applicant_prefered_group_code"
                                                        class="form-control applicant-prefered"
                                                        id="applicant_prefered_group_code" readonly required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="department">Prefered Department</label>
                                                    <input type="text" name="applicant_prefered_dept_desc"
                                                        class="form-control applicant-prefered"
                                                        id="applicant_prefered_dept_desc" readonly required>
                                                    <input type="hidden" name="applicant_prefered_dept_cd"
                                                        class="applicant-prefered" id="applicant_prefered_dept_cd"
                                                        readonly required>

                                                    <input type="hidden" name="applicant_prefered_adm_dept_cd"
                                                        value="" class="applicant-prefered"
                                                        id="applicant_prefered_adm_dept_cd">
                                                    <input type="hidden" name="applicant_prefered_adm_dept_desc"
                                                        value="" class="applicant-prefered"
                                                        id="applicant_prefered_adm_dept_desc">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- When applicant's choice is not available, department will allot the available vaccant post --}}
                                        <div class="row mb-3">
                                            <div class=" col-sm-4 form-check">
                                                <input class="form-check-input" type="radio" name="post_option"
                                                    id="exampleRadios2" value="department-prefered">
                                                <label class="form-check-label cursor-pointer" for="exampleRadios2">
                                                    <b> Allot Post when Employee preferred post is not vacant</b>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="mb-3">


                                                    <div class="row mb-2">
                                                        <div class="col-6"><label for="department_prefered_dept_cd"
                                                                class="form-label">Department:
                                                            </label></div>
                                                        <div class="col-6">No. of vaccant posts: <span
                                                                id="department_preferred_vacc_posts"
                                                                class="fw-bold">0</span>
                                                        </div>
                                                    </div>

                                                    <select class="form-select dept-prefered"
                                                        aria-label="Default select example"
                                                        id="department_prefered_dept_cd"
                                                        name="department_prefered_dept_cd" disabled>
                                                        <option value="" selected>Select Department</option>
                                                        @foreach ($departments as $dept)
                                                            <option value="{{ $dept['field_dept_cd'] }}"
                                                                data-adm_dept_cd="{{ $dept['adm_dept_cd'] }}"
                                                                data-adm_dept_desc="{{ $adminDepts[$dept['adm_dept_cd']] }}">
                                                                {{ $dept['field_dept_desc'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" id="department_prefered_adm_dept_cd"
                                                        class="dept-prefered" name="department_prefered_adm_dept_cd"
                                                        value="">
                                                    <input type="hidden" id="department_prefered_adm_dept_desc"
                                                        class="dept-prefered" name="department_prefered_adm_dept_desc"
                                                        value="">

                                                    <input type="hidden" id="department_prefered_dept_desc"
                                                        class="dept-prefered" name="department_prefered_dept_desc"
                                                        value="">
                                                </div>

                                                <!-- Get availabe posts in the selected departments -->
                                                <div class="mb-3">
                                                    <label for="dept_prefered_post_id" class="form-label">Posts: </label>
                                                    <select class="form-select dept-prefered post-select"
                                                        data-prefered="department" aria-label="Default select example"
                                                        id="dept_prefered_post_id" name="department_prefered_post_id"
                                                        disabled>
                                                        <option value="" selected>Select Post</option>
                                                    </select>
                                                    <input type="hidden" name="department_prefered_post_desc"
                                                        class="dept-prefered" id="department_prefered_post_desc"
                                                        value="">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="department_prefered_group_code" class="form-label ">Group
                                                        Code: </label>
                                                    <input type="text" id="department_prefered_group_code"
                                                        name="department_prefered_group_code" value=""
                                                        class="form-control dept-prefered" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-sm-4 " style="text-align: left;">
                                                <strong>Signing Authority:</strong>
                                            </div>
                                            <div class="col-sm-8 ">
                                                <select name="signing_authority" id="" class="form-select"
                                                    required>
                                                    <option value="">Select signing authority</option>
                                                    @foreach ($dpNodals as $nodal)
                                                        <option value="{{ $nodal->user_id }}">{{ $nodal->fullname }}
                                                            ({{ $nodal->role->role_name }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-2">
                                            <div class="col-sm-4 " style="text-align: left;">
                                                <strong>Do you want to give remarks? (Optional)</strong>
                                            </div>
                                            <div class="col-sm-8 ">
                                                <textarea name="remarks" id="remarks" class="form-control" rows="6"></textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center gap-2 mt-5">
                                            <button class="btn btn-md btn-secondary prevBtn" type="button"
                                                data-step="5">Prev</button>
                                            @include('duties.tasks._revert')
                                            @include('duties.tasks._reject')
                                            {{-- @include('duties.tasks._forward') --}}
                                            @if ($tasks['can_forward'])
                                                @if ($tasks['next'])
                                                    <button type="submit" class="btn btn-md btn-success btn-sm"
                                                        data-bs-html="true" data-bs-toggle="tooltip"
                                                        data-bs-placement="right"
                                                        title="Forward to next step: <u>{{ $tasks['next']['tasks_name'] }}</u>. Click to forward.">
                                                        Forward
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-md btn-success btn-sm"
                                                        data-bs-html="true" data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom"
                                                        title="You are at the final step, clicking this will complete the process.">
                                                        Submit
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <x-proforma-activity-logs :proforma-id="$proforma->proforma_id" />
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
        const verifyUrl = "{{ route('duties.uo.formfillup.verify', $proforma->proforma_id) }}";
        const confirmRedirectUrl = "{{ route('duties.verify.form.index', $tasks['current']['tasks_id']) }}";
        const proformaDashboardUrl = "{{ route('tasks.performa.all') }}";
        var vaccantCountUrl =
            "{{ route('admin.postvaccancies.get-vaccant-count', ['fieldDeptCd' => '__field_dept_cd__', 'dsgSrno' => '__dsg_srno__']) }}";


        document.addEventListener("DOMContentLoaded", function() {
            $(document).ready(function() {
                $(document).ready(function() {
                    @if ($errors->any())
                        showStep(4);
                    @else
                        showStep(1);
                    @endif
                });
            });

            document.querySelectorAll(`input[type='radio'][name='post_option']`).forEach(radiioBtn => {
                radiioBtn.addEventListener('click', (e) => {
                    switch (radiioBtn.value.trim()) {
                        case "applicant-prefered":
                            document.querySelectorAll(`.applicant-prefered`).forEach(element => {
                                element.disabled = false;
                                element.setAttribute("required", true);
                            });

                            document.querySelectorAll(`.dept-prefered`).forEach(element => {
                                element.value = "";
                                element.disabled = true;
                                element.removeAttribute("required");
                            });
                            break;
                        case "department-prefered":
                            document.querySelectorAll(`.applicant-prefered`).forEach(element => {
                                element.value = "";
                                element.disabled = true;
                                element.removeAttribute("required");
                            });
                            document.querySelectorAll(`.dept-prefered`).forEach(element => {
                                element.disabled = false;
                                element.setAttribute("required", true);
                            });
                            break;
                    }
                });
            });
        });

        $(document).ready(function() {
            $("#department_prefered_dept_cd").on('change', function() {
                var selectedValue = $(this).val();
                if (selectedValue.trim() == "") {
                    return;
                }

                const selectedOption = $(this).find('option:selected');
                const adm_dept_cd = selectedOption.data('adm_dept_cd');
                const adm_dept_desc = selectedOption.data('adm_dept_desc');

                $("#department_prefered_adm_dept_cd").val(adm_dept_cd);
                $("#department_prefered_adm_dept_desc").val(adm_dept_desc);
                $("#department_prefered_dept_desc").val(selectedOption.text().trim());

                //Route url for getting available post in a department
                let url = "{{ route('cmis.api.post.dept_code', '__id__') }}";
                url = url.replace("__id__", selectedValue);

                $.ajax({
                    url: url,
                    success: function(posts) {
                        //console.log(posts);
                        let layout = `<option value=''>Select Post</option>`;
                        posts.forEach((post) => {
                            if (post.group_cd == "C" || post.group_cd == "D") {
                                layout +=
                                    `<option value="${post.dsg_srno}" data-group_code="${post.group_cd}">${post.dsg_desc}</option>`;
                            } //dsg_serial_no
                        });

                        $("#dept_prefered_post_id").html(layout);
                    },
                    error: function(xhr, status, error) {
                        $("#dept_prefered_post_id").html(
                            `<option value=''>Select Post</option>`);
                        let msg = "An error occurred. Please try again.";
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                msg = response.message.trim() == "No Data Found" ?
                                    "No post available in the selected department." : response
                                    .message;
                            }
                        } catch (e) {
                            console.error("Failed to parse error response:", e);
                        }
                        //alert(msg);
                        Swal.fire({
                            title: "Something wrong!",
                            text: msg,
                            icon: "warning"
                        });
                    }
                });
            });

            $("#dept_prefered_post_id").on("change", function() {
                //var selectedValue = $(this).val();
                const selectedOption = $(this).find('option:selected');
                const groupCode = selectedOption.data('group_code');
                $("#department_prefered_group_code").val(groupCode);
                $("#department_prefered_post_desc").val(selectedOption.text().trim());

            });

            document.forms['uo_form_fillup'].onsubmit = function(event) {
                event.preventDefault();

                //validation of vaccant posts

                postSelection = document.querySelector(`input[type='radio'][name='post_option']:checked`);
                if (!postSelection || postSelection == null) {
                    alert(
                        "Please select whether you are opting 'Employee Preferred Post' or 'Allot Post when Employee preferred post is not vacant'. "
                    );
                    return false;
                }

                if (postSelection.value == "applicant-prefered") {
                    let vaccPostCount = document.querySelector(`#applicant_preferred_vacc_posts`).innerText;
                    if (vaccPostCount == "0") {
                        alert("There is no vaccant post for the applicant preferred post in the department");
                        return;
                    }
                } else if (postSelection.value == "department-prefered") {
                    let vaccPostCount = document.querySelector(`#department_preferred_vacc_posts`).innerText;
                    if (vaccPostCount == "0") {
                        alert("There is no vaccant post for the selected department");
                        return;
                    }
                }

                Swal.fire({
                    title: "Are you sure?",
                    text: "You are saving and forwarding this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, save & forward it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        //document.forms['uo_form_fillup'].submit();
                        var formData = new FormData(document.forms['uo_form_fillup']);
                        console.log('Forwarding to url:', document.forms['uo_form_fillup'].action);
                        //return;
                        $.ajax({
                            url: document.forms['uo_form_fillup'].action,
                            data: formData,
                            async: false,
                            type: document.forms['uo_form_fillup'].method,
                            //contentType: "application/json",
                            Accept: "application/json",
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                console.log(response);
                                Swal.fire({
                                    title: "Done!",
                                    text: response.message,
                                    icon: "success"
                                }).then(() => {
                                    window.location.href = proformaDashboardUrl;
                                });
                            },
                            error: function(xhr, status, error) {
                                let msg = "An error occurred. Please try again.";
                                try {
                                    let response = JSON.parse(xhr.responseText);
                                    if (response.message) {
                                        msg = response.message;
                                    }
                                } catch (e) {
                                    console.error("Failed to parse error response:", e);
                                }
                                //alert(msg);
                                Swal.fire({
                                    title: "Something wrong!",
                                    text: msg,
                                    icon: "warning"
                                });
                            }
                        });
                    }
                });
            }
        });

        // Function to get the vaccant counts of a particular designation/post in a department
        async function getVaccantCount(fieldDeptCd, dsgSrno) {

            var url = vaccantCountUrl.replace('__field_dept_cd__', fieldDeptCd);
            url = url.replace('__dsg_srno__', dsgSrno);

            var response = await fetch(url);
            var jsonData = await response.json();
            console.log(jsonData);
            return (jsonData.count);
        }

        //applicant_prefered_dept_cd
        //department_prefered_dept_cd

        var postSelects = document.querySelectorAll("select.post-select");
        postSelects.forEach((selectElement) => {

            selectElement.addEventListener('change', async (event) => {
                let dsgSrno = selectElement.value.trim();
                if (dsgSrno == "") {
                    return;
                }
                let choice = selectElement.getAttribute("data-prefered");
                let fieldDeptElement = document.querySelector(`#${choice}_prefered_dept_cd`);
                if (choice == "applicant") {
                    var uo_form_fillup = document.forms['uo_form_fillup'];

                    const preferredPostSelect = document.getElementById(choice + "_prefered_post_id");

                    //preferredPostSelect.addEventListener("change", function() {
                    // get selected option
                    const selectedOption = preferredPostSelect.options[preferredPostSelect
                        .selectedIndex];
                    //Filling up group code
                    uo_form_fillup.applicant_prefered_group_code.value = selectedOption.getAttribute(
                        "data-group_code");
                    uo_form_fillup.applicant_prefered_dept_desc.value = selectedOption.getAttribute(
                        "data-dept");
                    uo_form_fillup.applicant_prefered_dept_cd.value = selectedOption.getAttribute(
                        "data-dept_cd");
                    uo_form_fillup.applicant_prefered_adm_dept_cd.value = selectedOption.getAttribute(
                        "data-adm_dept_cd");
                    uo_form_fillup.applicant_prefered_adm_dept_desc.value = selectedOption.getAttribute(
                        "data-adm_dept_desc");
                    uo_form_fillup.applicant_prefered_adm_dept_desc.value = selectedOption.getAttribute(
                        "data-adm_dept_desc");
                    uo_form_fillup.applicant_prefered_post_desc.value = selectedOption.text || "";

                    //});
                }

                if (fieldDeptElement && fieldDeptElement.value != "") {
                    let count = await getVaccantCount(fieldDeptElement.value, dsgSrno);
                    console.log("vaccant count: " + count);
                    //applicant_preferred_vacc_posts
                    let spanElement = document.querySelector(`#${choice}_preferred_vacc_posts`);
                    if (spanElement) {
                        spanElement.innerText = count;
                    }
                }
            });

        });
    </script>
    <script src="{{ asset('js/duties-btn.js') }}"></script>
@endpush
