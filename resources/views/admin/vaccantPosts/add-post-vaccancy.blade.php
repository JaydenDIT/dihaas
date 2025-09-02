@extends('layouts.app')
@push('css')
    <style>
        label {
            font-weight: 600;
            color: #333131;
            margin-bottom: 8px;
        }

        input[readonly] {
            cursor: not-allowed;
        }
    </style>
@endpush
@section('content')
    <div class="col-md-12">
        <div class="card">

            <div class="card-body small">
                <div class="card-title d-flex justify-content-between">
                    <h5>List of vaccant posts department wise:</h5>
                    <button class="btn btn-success btn-sm" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fa fa-circle-plus"></i> Add
                        Vaccant Post</button>
                </div>
                <table id="vaccancy_table" class="table table-stripped table-bordered">
                    <thead class="bg-gray">
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Administrative Department</th>
                            <th rowspan="2">Field Department</th>
                            <th rowspan="2">Post</th>
                            <th colspan="2" class="text-center">Available Posts</th>
                        </tr>
                        <tr>
                            <th>Die-in-Harness</th>
                            <th>Direct Recruitment</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title text-primary" id="offcanvasRightLabel">Add Vaccant Post:</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <form action="{{ route('admin.postvaccancies.store') }}" method="POST" name="post-vaccancy-form">
            @csrf
            <div class="offcanvas-body small">
                <div class="mb-3">
                    <label for="admin_dept">Select Admin Department:</label>
                    <select name="adm_dept_cd" class="form-select" id="admin_dept">
                        <option value="">--Select--</option>
                        @foreach ($adminDepts as $adminDept)
                            <option value="{{ $adminDept['adm_dept_cd'] }}~{{ $adminDept['adm_dept_desc'] }}">
                                {{ $adminDept['adm_dept_desc'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="field_dept">Select Field Department:</label>
                    <select name="field_dept_cd" class="form-select" id="field_dept">
                        <option value="">--Select--</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="post_id">Select Post:</label>
                    <select name="dsg_srno" class="form-select" id="post_id">
                        <option value="">--Select--</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="total_vaccant_post">Enter Total No. of Post:</label>
                    <input type="text" class="form-control" name="total_vaccant_post" id="total_vaccant_post" />
                </div>

                <div class="mb-3 d-flex justify-content-between">
                    <div class="col-6" style="padding-right:5px;">
                        <label for="dia">Die-in-Harness</label>
                        <input name="no_of_posts_dia" type="text" class="form-control" id="dia" readonly>
                    </div>
                    <div class="col-6" style="padding-left:5px;">
                        <label for="dr">Direct Recruitment</label>
                        <input name="no_of_posts_dr" type="text" class="form-control" id="dr" readonly>
                    </div>
                </div>
                <div class="mb-3 text-end">
                    <button type="button" class="btn btn-secondary" id="post-vaccancy-form-reset-btn">Reset</button>
                    <button class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        const admin_dept = document.querySelector("#admin_dept");
        const field_dept = document.querySelector("#field_dept");
        const post_id = document.querySelector("#post_id");
        const get_dept_url = "{{ route('cmis.api.department.adm_cd', '__ID__') }}";
        const get_post_url = "{{ route('cmis.api.post.dept_code', '__ID__') }}";
        const get_vaccancy_url = "{{ route('admin.postvaccancies.getVaccancyData') }}";

        $(function() {

            getAllVaccancies();

            $('select').select2({
                dropdownParent: $('#offcanvasRight')
            });

            //When Admin Department is changed
            $('#admin_dept').on('change', function() {
                var admin_dept = $(this).val();
                if (admin_dept.trim() == "") {
                    return;
                }
                var admin_dept_cd = admin_dept.split("~")[0];
                $.ajax({
                    url: get_dept_url.replace("__ID__", admin_dept_cd),
                    success: function(response) {
                        const departments = response.field_dept;
                        var layout = `<option value="">--- Select ---</option>`;
                        departments.forEach(element => {
                            layout +=
                                `<option value="${element.field_dept_cd}~${element.field_dept_desc}">${element.field_dept_desc}</option>`;
                        });
                        $("#field_dept").html(layout);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            //When Field Department is changed
            $("#field_dept").on('change', function() {

                var field_dept = $(this).val();
                if (field_dept.trim() == "") {
                    return;
                }
                var field_dept_cd = field_dept.split("~")[0];
                $.ajax({
                    url: get_post_url.replace("__ID__", field_dept_cd),
                    success: function(posts) {
                        var layout = `<option value="">--- Select ---</option>`;

                        posts.forEach(element => {
                            layout +=
                                `<option value="${element.dsg_srno}~${element.dsg_desc}">${element.dsg_desc}</option>`;
                        });
                        $("#post_id").html(layout);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            const calculate_vaccancy_url = "{{ route('admin.postvaccancies.calculateVaccancy', '_total_') }}";
            //getting calculated vaccancy of the total
            function getCalculatedValue(event) {
                const totalPost = event.target.value; // get typed value
                if (!totalPost) return;

                let url = calculate_vaccancy_url.replace("_total_", totalPost);
                fetch(url)
                    .then(async response => {
                        const data = await response.json();

                        if (!response.ok) {
                            // Server returned an error with a message
                            const errorMsg = data?.message || `HTTP error ${response.status}`;
                            throw new Error(errorMsg);
                        }

                        console.log("Calculated values:", data);
                        $("#dia").val(data.dia);
                        $("#dr").val(data.dr);
                    })
                    .catch(error => {
                        const errorMessage = error.message || "An unknown error has occured";
                        //alert("Something went wrong while calculating vacancy: " + errorMessage);
                        Swal.fire({
                            icon: "error",
                            title: "Oops! Something went wrong while calculating vacancy",
                            text: errorMessage
                        });
                        console.error(errorMessage);
                    });
            }

            // Wrap it with debounce
            const debouncedCalculation = debounceCall(getCalculatedValue, 1000);
            // Attach it to keyup
            document.getElementById("total_vaccant_post").addEventListener("keyup", debouncedCalculation);


            //Form reset action
            $("#post-vaccancy-form-reset-btn").on('click', function() {
                $('form[name="post-vaccancy-form"]')[0].reset();
                $('select').val('').trigger('change'); //Resetting select2
            });

            //On submit of the post-vaccancy-form form
            //On submit of the post-vaccancy-form form
            $('form[name="post-vaccancy-form"]').on('submit', function(event) {
                event.preventDefault();

                let form = this; // raw DOM form element
                let formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    type: $(form).attr('method'),
                    data: formData,
                    processData: false, // required for FormData
                    contentType: false, // required for FormData
                    success: function(resp) {
                        success_message(resp.message);
                        console.log("Form submitted successfully:", resp);

                        //Refreshng data
                        getAllVaccancies();

                        // Optional: reset form + close offcanvas
                        form.reset();
                        $('select').val('').trigger('change');
                        $('#offcanvasRight').offcanvas('hide');
                        //window.location.reload();
                    },
                    error: function(xhr) {
                        console.error("Error submitting form:", xhr.responseText);
                        try {
                            const obj = JSON.parse(xhr.responseText);
                            error_message(obj.message);
                        } catch (error) {
                            console.log(error)
                        }

                    }
                });
            });

            //function to get vaccancy data
            function getAllVaccancies() {
                let vaccancyTable = document.querySelector("#vaccancy_table");
                vaccancyTable.querySelector("tbody").innerHTML =
                    `<tr><td colspan="6" class="text-center text-muted">Refreshing data ...</td></tr>`;
                fetch(get_vaccancy_url)
                    .then(response => response.json())
                    .then((data) => {
                        const vaccancies = data.vaccancies;
                        if (vaccancies) {

                            layout = "";
                            vaccancies.forEach((vaccancy, i) => {
                                layout += `<tr>
                                        <td>${i+1}.</td>
                                        <td>${vaccancy.adm_dept_name}</td>
                                        <td>${vaccancy.field_dept_name}</td>
                                        <td>${vaccancy.dsg_name}</td>
                                        <td>${vaccancy.dia_vacc_posts}</td>
                                        <td>${vaccancy.dr_vacc_posts}</td>
                                    </tr>`;
                            });
                            if (vaccancies.length == 0) {
                                layout =
                                    `<tr><td colspan="6" class="text-center text-muted">No vaccancies available</td></tr>`;
                            }
                            vaccancyTable.querySelector("tbody").innerHTML = layout;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

        });
    </script>
@endpush
