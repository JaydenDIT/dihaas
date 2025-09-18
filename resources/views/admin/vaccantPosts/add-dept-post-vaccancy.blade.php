@extends('layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">

            <h5 class="card-title">Post vaccancy for the department <strong>{{ $department['field_dept_desc'] }}</strong>
            </h5>
            <p class="text-muted small">
                The post vaccancy distribution is based on the criteria
                that <strong>{{ $vaccancyPercentage->dia_percentage }}%</strong> of the total posts is
                reserved for the <strong>'Die-in-Harness'</strong> and the remaining
                will counted for <strong>'Direct Recruitment'</strong> posts as per the latest effective
                date <strong>{{ date('d M, Y', strtotime($vaccancyPercentage->effective_date)) }}</strong>.
            </p>
            <div class="small">
                <table class="table table-bordered" id="dept-post-vaccancy-table">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Date of Entry</th>
                            <th rowspan="2">Post</th>
                            <th colspan="2" class="text-center">Available Posts</th>
                            <th rowspan="2" class="text-end">
                                <button class="btn btn-primary" onclick="showAddVancyModal();" data-bs-toggle="modal"
                                    data-bs-target="#vacancyModal"><i class="fa fa-circle-plus"></i> Add
                                    Vaccancy</button>
                            </th>
                        </tr>
                        <tr>
                            <th>Die-in-Harness</th>
                            <th>Direct Recruitment</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Post vaccancy data entry and edit -->
    <div class="modal fade" id="vacancyModal" tabindex="-1" aria-labelledby="vacancyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form name="post-vaccancy-form" id="post-vaccancy-form" action="#" method="POST">
                @csrf
                {{-- Hidden inputs:
                    for 'adm_dept_cd', 'adm_dept_name', 'field_dept_cd', and 'field_dept_name'
                --}}
                <input type="hidden" name="adm_dept_cd" value="{{ $department['adm_dept']['adm_dept_cd'] }}" />
                <input type="hidden" name="adm_dept_name" value="{{ $department['adm_dept']['adm_dept_desc'] }}" />
                <input type="hidden" name="field_dept_cd" value="{{ $department['field_dept_cd'] }}" />
                <input type="hidden" name="field_dept_name" value="{{ $department['field_dept_desc'] }}" />

                <!-- Action type -->
                <input type="hidden" name="action_type" value="create" />
                {{-- Vaccancy id --}}
                <input type="hidden" name="vaccancy_id" value="" />

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vacancyModalLabel">Add Vacancy</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- form fields -->
                        <div class="mb-3">
                            <label for="designation" class="form-label">Post / Designation</label>
                            <select name="dsg_srno" id="designation" class="form-select">
                                <option value="">Select Post</option>
                                @foreach ($posts as $post)
                                    @php
                                        $desigValue = trim($post['dsg_srno'] . '~' . $post['dsg_desc']);
                                    @endphp
                                    <option value="{{ $desigValue }}">
                                        {{ $post['dsg_desc'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="total_vaccant_post">Enter Total No. of Post:</label>
                            <input type="text" class="form-control" name="total_vaccant_post" id="total_vaccant_post" />
                        </div>
                        <div class="mb-3">
                            <label for="dia" class="form-label">Die-in-Harness Posts:</label>
                            <input type="number" name="no_of_posts_dia" id="dia" class="form-control" readonly>
                            <div class="small text-muted"><strong>{{ $vaccancyPercentage->dia_percentage }}%</strong> of
                                the total posts</div>
                        </div>
                        <div class="mb-3">
                            <label for="dr" class="form-label">Direct Recruitment Posts</label>
                            <input type="number" name="no_of_posts_dr" id="dr" class="form-control" readonly>
                            <div class="small text-muted">
                                <strong>{{ 100 - $vaccancyPercentage->dia_percentage }}%</strong> of
                                the total posts
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('js/date-function.js') }}"></script>
    <script src="{{ asset('js/post-vaccancy/post-vaccancy.js') }}"></script>
    <script>
        const dept_vaccancy_url = "{{ route('admin.postvaccancies.getPostVaccancyEntries', '__dept_code__') }}";
        const calculate_vaccancy_url = "{{ route('admin.postvaccancies.calculateVaccancy', '_total_') }}";
        const create_post_vaccancy_url = "{{ route('admin.postvaccancies.store') }}";
        const update_post_vaccancy_url = "{{ route('admin.postvaccancies.update') }}";
        const delete_post_vaccancy_url = "{{ route('admin.postvaccancies.delete', '__id__') }}";

        const field_dept_cd = "{{ $department['field_dept_cd'] }}";
        // Wrap it with debounce
        const debouncedCalculation = debounceCall(getCalculatedValue, 1000);

        var post_vaccancy_form = document.forms['post-vaccancy-form'];

        // Attach it to keyup
        document.getElementById("total_vaccant_post").addEventListener("keyup", debouncedCalculation);

        //Get departmental vaccancy entries
        getDepartmentVaccancies(field_dept_cd);

        //On submit of the post-vaccancy-form form
        $('form[name="post-vaccancy-form"]').on('submit', function(event) {
            event.preventDefault();

            let form = this; // raw DOM form element
            let formData = new FormData(form);

            let action_url = form.action_type.value == "update" ? update_post_vaccancy_url :
                create_post_vaccancy_url;

            $.ajax({
                url: action_url,
                type: $(form).attr('method'),
                data: formData,
                processData: false, // required for FormData
                contentType: false, // required for FormData
                success: function(resp) {
                    //success_message(resp.message);
                    Swal.fire({
                        icon: "success",
                        title: `Vaccancy ${form.action_type.value}d`,
                        text: resp.message,
                    }).then(() => {
                        console.log("Form submitted successfully:", resp);
                        //Refreshng data
                        getDepartmentVaccancies(form.field_dept_cd.value);
                        // Optional: reset form + close offcanvas
                        form.reset();
                        $('#vacancyModal').modal('hide');
                        // window.location.reload();
                    })

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

        // Function to get the departmental post vaccancies
        function getDepartmentVaccancies(fieldDeptCode) {
            let url = dept_vaccancy_url.replace('__dept_code__', fieldDeptCode);

            fetch(url).then(async (response) => {
                let result = await response.json();
                if (!response.ok) {
                    // Server returned an error with a message
                    const errorMsg = result?.message || `HTTP error ${response.status}`;
                    throw new Error(errorMsg);
                }
                return result;
            }).then((data) => {
                //Now displaying the data
                let vaccancies = data.vaccancies;
                displayVaccancies(document.getElementById("dept-post-vaccancy-table"), vaccancies);
            }).catch(error => {
                const errorMessage = error.message || "An unknown error has occured";
                //alert("Something went wrong while calculating vacancy: " + errorMessage);
                Swal.fire({
                    icon: "error",
                    title: "Oops! Something went wrong while calculating vacancy",
                    text: errorMessage
                });
            })
        }

        //Function to display vaccancies
        function displayVaccancies(table, vaccancy_data = []) {
            let tbody = table.querySelector("tbody");
            if (vaccancy_data.length == 0) {
                tbody.innerHTML = `
                <tr>
                    <td class="text-center" colspan="6"> No record found!</td>
                </tr>
                `;
                return;
            }

            let layout = "";
            vaccancy_data.forEach((row, i) => {
                layout += `
                    <tr>
                        <td>${i+1}.</td>
                        <td>${getDate(row.created_at)}</td>
                        <td>${row.dsg_name}</td>
                        <td class="text-center">${row.no_of_posts_dia}</td>
                        <td class="text-center">${row.no_of_posts_dr}</td>
                        <td>
                            <div class="d-flex text-end gap-2">
                                <div class="col-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-vaccancy-btn" 
                                    data-vaccancy='${JSON.stringify(row)}'>
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </div>
                                <div class="col-2"><button type="button" class="btn btn-sm btn-outline-danger delete-vaccancy-btn" 
                                    data-vaccancy_id="${row.vaccancy_id}"><i
                                            class="bi bi-trash"></i></button></div>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = layout;
            tbody.querySelectorAll("button.edit-vaccancy-btn").forEach((btnElement) => {
                btnElement.addEventListener('click', (event) => {
                    let vaccancyData = JSON.parse(btnElement.getAttribute("data-vaccancy"));
                    showEditVaccancyModal(vaccancyData);
                });
            });
            tbody.querySelectorAll("button.delete-vaccancy-btn").forEach((btnElement) => {
                btnElement.addEventListener('click', (event) => {
                    let vaccancyId = btnElement.getAttribute("data-vaccancy_id");
                    deletePostVaccancy(vaccancyId);
                });
            });

        }

        //function to show modal for add post vaccancy
        function showAddVancyModal() {
            $("#vacancyModalLabel").text("Add Vaccancy");
            $("#vacancyModal button[type='submit']").text("Add");
            post_vaccancy_form.reset();
            post_vaccancy_form.action_type.value = "create";
        }

        //function to show modal for editing post vaccancy
        function showEditVaccancyModal(vaccancyData) {

            $("#vacancyModal").modal('show');
            $("#vacancyModalLabel").text("Update Vaccancy");
            $("#vacancyModal button[type='submit']").text("Update");

            if (vaccancyData !== null) {
                post_vaccancy_form.action_type.value = "update";
                post_vaccancy_form.vaccancy_id.value = vaccancyData.vaccancy_id;
                post_vaccancy_form.dsg_srno.value = vaccancyData.dsg_srno.trim() + "~" + vaccancyData.dsg_name.trim();
                post_vaccancy_form.total_vaccant_post.value = vaccancyData.total_vaccant_post;
                post_vaccancy_form.no_of_posts_dia.value = vaccancyData.no_of_posts_dia;
                post_vaccancy_form.no_of_posts_dr.value = vaccancyData.no_of_posts_dr;
            } else {
                alert("Vaccancy Data is null");
            }
        }

        //function to delete a vaccancy entry
        function deletePostVaccancy(vaccancy_id) {
            let url = delete_post_vaccancy_url.replace('__id__', vaccancy_id);


            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {

                    fetch(url, {
                        method: "DELETE"
                    }).then(async (response) => {
                        let result = await response.json();
                        if (!response.ok) {
                            // Server returned an error with a message
                            const errorMsg = result?.message || `HTTP error ${response.status}`;
                            throw new Error(errorMsg);
                        }
                        return result;
                    }).then(data => {
                        Swal.fire({
                            title: "Deleted!",
                            text: data.message,
                            icon: "success"
                        });
                    }).catch(error => {
                        const errorMessage = error.message || "An unknown error has occured";
                        //alert("Something went wrong while calculating vacancy: " + errorMessage);
                        Swal.fire({
                            icon: "error",
                            title: "Oops! Something went wrong while deleting vacancy",
                            text: errorMessage
                        });
                    });

                }
            });

        }
    </script>
@endpush
