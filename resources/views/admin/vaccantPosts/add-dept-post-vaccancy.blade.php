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
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#vacancyModal"><i
                                        class="fa fa-circle-plus"></i> Add
                                    Vaccancy</button>
                            </th>
                        </tr>
                        <tr>
                            <th>Die-in-Harness</th>
                            <th>Direct Recruitment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vaccancies as $key => $vaccancy)
                            <tr>
                                <td>{{ $key + 1 }}.</td>
                                <td>{{ date('d M, Y', strtotime($vaccancy->created_at)) }}</td>
                                <td>{{ $vaccancy->dsg_name }}</td>
                                <td class="text-center">{{ $vaccancy->no_of_posts_dia }}</td>
                                <td class="text-center">{{ $vaccancy->no_of_posts_dr }}</td>
                                <td>
                                    <div class="d-flex text-end gap-2">
                                        <div class=""><a href="#" class="btn btn-sm btn-outline-primary"><i
                                                    class="fa fa-edit"></i></a></div>
                                        <div class=""><a href="#" class="btn btn-sm btn-outline-danger"><i
                                                    class="bi bi-trash"></i></a></div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Post vaccancy data entry and edit -->
    <div class="modal fade" id="vacancyModal" tabindex="-1" aria-labelledby="vacancyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form name="post-vaccancy-form" action="{{ route('admin.postvaccancies.store') }}" method="POST">
                @csrf
                {{-- Hidden inputs:
                    for 'adm_dept_cd', 'adm_dept_name', 'field_dept_cd', and 'field_dept_name'
                --}}
                <input type="hidden" name="adm_dept_cd" value="{{ $department['adm_dept']['adm_dept_cd'] }}" />
                <input type="hidden" name="adm_dept_name" value="{{ $department['adm_dept']['adm_dept_desc'] }}" />
                <input type="hidden" name="field_dept_cd" value="{{ $department['field_dept_cd'] }}" />
                <input type="hidden" name="field_dept_name" value="{{ $department['field_dept_desc'] }}" />

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
                                    <option value="{{ $post['dsg_srno'] }}~{{ $post['dsg_desc'] }}">
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
    <script src="{{ asset('js/post-vaccancy/post-vaccancy.js') }}"></script>
    <script>
        const calculate_vaccancy_url = "{{ route('admin.postvaccancies.calculateVaccancy', '_total_') }}";
        // Wrap it with debounce
        const debouncedCalculation = debounceCall(getCalculatedValue, 1000);
        // Attach it to keyup
        document.getElementById("total_vaccant_post").addEventListener("keyup", debouncedCalculation);

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
                    //success_message(resp.message);
                    Swal.fire({
                        icon: "success",
                        title: "Vaccancy Added",
                        text: resp.message,
                    }).then(() => {
                        console.log("Form submitted successfully:", resp);

                        //Refreshng data
                        //getDepartmentVaccancies();

                        // Optional: reset form + close offcanvas
                        form.reset();
                        $('#vacancyModal').modal('hide');
                        window.location.reload();
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

        }
    </script>
@endpush
