@extends('layouts.app')

@section('content')
    <?php $selected = session()->get('deptId'); ?>
    <?php $selected = session()->get('desigId'); ?>
    <div class="container mb-6">
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            @foreach ($records as $data)
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-12" style="text-align: left;">

                                            <b>Applicant Name : {{ $data->applicant_name }} &nbsp; EIN of the Deceased:
                                                {{ $data->ein }} &nbsp; Deceased Name :
                                                {{ $data->deceased_emp_name }}</b>


                                        </div>

                                        <div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div><br>
                    <p>
                    <div class="col-12" style="text-align: center;">
                        <h2 style=" font-family:Verdana;">Fill UO Form<h2>
                    </div>

                    <form id="fill_uo">
                        @csrf


                        <div class="card-body">


                            <div class=" row">
                                <div class="col-sm-2">

                                </div>

                                <div class="col-sm-3 " style="text-align: left;">

                                    <label for="dp_file_no" class="form-label">DP File No.</label>
                                </div>
                                <div class="col-sm-3 ">

                                    <!-- <input type="text" name="efile_dp" value="{{ $data->efile_dp }}" readonly> -->

                                    <h5>{{ $data->efile_dp }}</h5>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3 " style="text-align: left;">

                                    <label for="ad_file_number" class="form-label">AD File No.</label>
                                </div>
                                <div class="col-sm-3 ">

                                    <h5>{{ $data->efile_ad }}</h5>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>





                            <br>
                            <div class="row">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3" style="text-align: left;">
                                    <b>
                                        <label for="applicantSelect">Applicant Name:</label>




                                    </b>
                                </div>

                                <div class="col-sm-3">

                                    <select class="form-select" id="applicantSelect" name="applicantSelect">
                                        <option>Select Applicant</option>
                                        @foreach ($records as $data)
                                            <option value="{{ $data->ein }}">{{ $data->applicant_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <br>


                            <div class="row">

                                <div class="col-sm-2">

                                </div>
                                <div class=" col-sm-8 form-check">
                                    <input class="form-check-input" type="radio" name="post_option" id="exampleRadios1"
                                        value="option1">
                                    <label class="form-check-label" for="exampleRadios1">
                                        <b> Employee Preferred Post</b>

                                    </label>
                                </div>

                                <div class="col-sm-2">

                                </div>


                            </div>
                            <br>

                            <div class="row">

                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3" style="text-align: left;">


                                    <b>
                                        <label for="postSelect">Post:</label>

                                    </b>
                                </div>

                                <div class="col-sm-3">
                                    <select class="form-select" name="post" id="postSelect" onchange="updatePostInfo()"
                                        disabled>
                                        <option>Select Post</option> <!--  default option -->
                                        <!-- Options will be dynamically added here based on the selected applicant -->
                                    </select>
                                </div>
                            </div>
                            <br>

                            <div class="row">


                                <div class="col-sm-5">

                                </div>


                                <div class="col-sm-3" style="text-align: left;">
                                    <input class="form-control" type="text" value="" id="gradeDisplay"
                                        name="gradeDisplay" readonly>
                                    <br>

                                    <input class="form-control" type="text" value="" id="departmentDisplay"
                                        name="departmentDisplay" readonly>



                                    </b>
                                </div>
                            </div>









                            <br>
                            <div class="row">

                                <div class="col-sm-2">

                                </div>
                                <div class=" col-sm-3 form-check">
                                    <input class="form-check-input" type="radio" name="post_option" id="exampleRadios2"
                                        value="option2">
                                    <label class="form-check-label" for="exampleRadios2">
                                        <b> Alloted Post when Employee preferred post is not vacant</b>

                                    </label>
                                </div>

                                <div class="col-sm-2">

                                </div>


                            </div>

                            <br>


                            <div class=" row" id="option2Div">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3 " style="text-align: left;">

                                    <label for="post_id" class="form-label">Department </label>
                                </div>
                                <div class="col-sm-3 ">
                                    <!-- UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU -->
                                    <select class="form-select" aria-label="Default select example" id="dept_id_option"
                                        name="dept_id_option" disabled>
                                        <option value="" selected>Select Department</option>
                                        @foreach ($deptListArray as $option)
                                            <option value="{{ $option['dept_id'] == null ? null : $option['dept_name'] }}"
                                                required {{ $selected == $option['dept_name'] ? 'selected' : '' }}
                                                data-deptid="{{ $option['dept_id'] }}"> {{ $option['dept_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- UUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUUU -->
                                <div class="col-sm-2">

                                </div>
                            </div> <br>

                            <div class=" row" id="option2Div">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3 " style="text-align: left;">

                                    <label for="post_id" class="form-label"> Post </label>
                                </div>
                                <div class="col-sm-3 ">

                                    <select class="form-select  @error('third_post_id') is-invalid @enderror "
                                        aria-label="Default select example" id="third_post_id" name="third_post_id"
                                        disabled>
                                        <option value="" selected disabled>Select Designation</option>

                                        <option value="{{ $option['dsg_desc'] == null ? null : $option['dsg_desc'] }}"
                                            required {{ $selected == $option['dsg_desc'] ? 'selected' : '' }}>
                                            {{ $option['dsg_desc'] }}
                                        </option>


                                    </select>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div> <br>



                            <div class=" row" id="option2Div">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3 " style="text-align: left;">

                                    <label for="post_id" class="form-label">Grade </label>
                                </div>
                                <div class="col-sm-3 ">

                                    <input class="form-control" type="text" value="" id="third_grade_id"
                                        name="third_grade_id" readonly>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div> <br>





                            <div class="row">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3" style="text-align: left;">

                                    <label for="signing_authority_1" class="form-label">Signing Authority 1</label>
                                </div>
                                <div class="col-sm-3 ">
                                    <select id="signing_authority_1" name="signing_authority_1" class="form-select">
                                        <option selected>Select</option>
                                        <!-- Add more options as needed -->
                                        @foreach ($Sign1 as $option)
                                            <option value="{{ $option['id'] }}"
                                                {{ $option['id'] == $option['authority_name'] ? 'selected' : '' }}
                                                required>
                                                {{ $option['authority_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div> <br>
                            <div class="row">
                                <div class="col-sm-2">

                                </div>
                                <div class="col-sm-3 " style="text-align: left;">

                                    <label for="signing_authority_2" class="form-label">Signing Authority 2</label>
                                </div>
                                <div class="col-sm-3 ">
                                    <select id="signing_authority_2" name="signing_authority_2" class="form-select">
                                        <option selected>Select</option>
                                        <!-- Add more options as needed -->
                                        @foreach ($Sign2 as $option)
                                            <option value="{{ $option['id'] }}"
                                                {{ $option['id'] == $option['name'] ? 'selected' : '' }} required>
                                                {{ $option['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>
                            @foreach ($records as $data)
                                <input type="hidden" name="ein[]" value="{{ $data }}">
                                <!--to get the ein -->
                            @endforeach

                            <br>

                            <p>

                            <div class="row ">
                                <div class="col-sm-2">

                                </div>

                                <div class="col-sm-8">
                                    <div class="d-grid gap-5 d-md-flex justify-content-center">
                                        <a href="{{ url('ddo-assist/selectDeptByDPApprove') }}"
                                            class="btn btn-primary ">Close</a>

                                        <button type="submit" class="btn btn-primary"> Save </button>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                </div>
                            </div>


                        </div>





                    </form>
                </div>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Submission</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" id="confirmCancel" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button> -->
                </div>
                <div class="modal-body">
                    Are you sure you want to save this as draft?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="confirmCancel"
                        data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSubmit">OK</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>






    <!-- From this Function to enable/disable and clear elements based on radio button selection -->
    <script>
        $(document).ready(function() {

            function toggleElements() {
                var option2Elements = $("#dept_id_option, #third_post_id, #third_grade_id");
                var option1Elements = $("#postSelect, #gradeDisplay, #departmentDisplay"); // Update option1Elements

                if ($("#exampleRadios1").is(":checked")) {
                    // Option 1 selected
                    option2Elements.prop("disabled", true).val("");
                    option1Elements.prop("disabled", false);
                } else if ($("#exampleRadios2").is(":checked")) {
                    // Option 2 selected
                    option2Elements.prop("disabled", false);
                    option1Elements.prop("disabled", true).val("");
                    // Set the value of spans to an empty string or hide them
                    $("#gradeDisplay, #departmentDisplay").val(""); // Set value to empty string



                }
            }

            // Initial toggle on page load
            toggleElements();

            // Toggle elements on radio button change
            $("input[name='post_option']").change(function() {
                toggleElements();
            });
        });
    </script>
    <!-- Upto this Function to enable/disable and clear elements based on radio button selection -->



    <script>
        $(document).ready(function() {
            // Function to enable/disable and clear elements based on radio button selection
            function toggleElements() {
                var option2Elements = $("#dept_id_option, #third_post_id, #third_grade_id");
                var option1Elements = $("#postSelect, #gradeDisplay, #departmentDisplay");

                if ($("#exampleRadios1").is(":checked")) {
                    // Option 1 selected
                    option2Elements.prop("disabled", true).val("");
                    option1Elements.prop("disabled", false);
                } else if ($("#exampleRadios2").is(":checked")) {
                    // Option 2 selected
                    option2Elements.prop("disabled", false);
                    option1Elements.prop("disabled", true).val("");
                    // Set the value of spans to an empty string or hide them
                    $("#gradeDisplay, #departmentDisplay").val("");
                }
            }

            // Initial toggle on page load
            toggleElements();

            // Toggle elements on radio button change
            $("input[name='post_option']").change(function() {
                toggleElements();
            });

            // Intercept the form submission
            $('#fill_uo').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Check if any required fields are blank
                if (isFormValid()) {
                    // Show confirmation modal
                    $('#confirmationModal').modal('show');
                } else {
                    // Display an error message or take any other appropriate action
                    alert('Please fill in all required fields.');
                }
            });

            // Function to check if the form is valid
            function isFormValid() {


                // Get the selected value from the dropdown
                var selectedApplicant = document.getElementById('applicantSelect').value;

                // Check if a valid option is selected
                if (selectedApplicant === "Select Applicant") {

                    return false; // Form is not valid
                }
                // Check if either radio option is selected
                if (!$('input[name="post_option"]:checked').length) {

                    return false; // Form is not valid
                }


                // Add checks for Signing Authority 1 dropdown
                var signingAuthority1 = $('#signing_authority_1').val();
                if (signingAuthority1 === 'Select') {
                    return false;
                }

                // Add checks for Signing Authority 2 dropdown
                var signingAuthority2 = $('#signing_authority_2').val();
                if (signingAuthority2 === 'Select') {
                    return false;
                }

                return true; // Form is valid
            }

            // Handle the confirmation button click
            $('#confirmSubmit').click(function() {
                // Get form data
                var formDataArray = $('#fill_uo').serializeArray();
                var formData = $.param(formDataArray);

                // Hide the modal
                $('#confirmationModal').modal('hide');
                // Log formData to console
                //   console.log(formData);

                // Perform Ajax request
                $.ajax({
                    type: 'POST',
                    url: "{{ route('fill_uo_save_checked', Crypt::encryptString($data->ein)) }}",
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Handle success, e.g., show a success message
                        // console.log(response);
                        alert('Save Successfully....');
                        // alert(response.message);
                        // Redirect to the desired page if needed
                        //   window.location.href = "{{ route('selectDeptByDPApprove') }}";
                    },
                    error: function(error) {
                        // Handle errors, e.g., show an error message
                        // console.log(error);
                        alert('Error saving record. Please try again.');
                    }
                });
            });

            // Handle the cancel button click
            $('#confirmCancel').click(function() {
                // Hide the modal
                $('#confirmationModal').modal('hide');
            });
        });
    </script>








    <!-- FROM THIS WE COPIED FROM FROM proforma_save -->
    <script>
        $('#third_post_id').change(function() {
            var id = $(this).find('option:selected').val();
            var item = $(this).find(item => item.dsg_serial_no === id);
            //alert($('#third_post_id option[value="'+this.value+'"]').data('grade'));
            $('#third_grade_id').val($('#third_post_id option[value="' + this.value + '"]').data(
                'grade'));

        })


        $('#dept_id_option').change(function() {

            //make blank  
            //alert($(this).find('option:selected').data('deptid'));return;
            $("#third_post_id").empty();
            $('#third_post_id').append(new Option('Select Post', ''));

            $('#third_post_id option[value=""]').attr('disabled', false);

            $('#third_grade_id').val('');


            var id = $(this).find('option:selected').val();
            var data_dept_id = {
                'dept_id': $(this).find('option:selected').data('deptid'),
            };
            // console.log(data_dept_id);
            $.get('{{ route('retrieve_dept') }}', data_dept_id, function(id, textStatus, xhr) {

                //now load the result data in id third_post_id i.e. for designation  

                $.each(id, function(index, element) {

                    //$('#third_post_id').append(new Option(element.dsg_desc, element.dsg_serial_no)); //only value and text
                    //Below is for adding extra attribute
                    $('<option>').val(element.dsg_desc).text(element.dsg_desc)
                        // .attr('data-postid',element.dsg_serial_no)
                        .attr('data-grade', element.group_code).appendTo('#third_post_id');

                });

            });


        })
    </script>

    <!-- Upto THIS WE COPIED FROM FROM proforma_save -->

    <script>
        $(document).ready(function() {
            // Event handler for the change event on the applicant preferences dropdown
            $('#applicant_preferences').change(function() {
                // Get selected values
                var selectedApplicant = $(this).val();
                var selectedFirst = $('#applicant_preferences option:selected').data('first');
                var selectedSecond = $('#applicant_preferences option:selected').data('second');
                var selectedThird = $('#applicant_preferences option:selected').data('third');
                var selectedRelationship = $('#applicant_preferences option:selected').data('relationship');
                // Display selected preferences in the container

            });
        });
    </script>


    <script>
        var applicantData = [
            @foreach ($records as $data)
                @php
                    $relationshipId = $data->relationship;
                    $relationship = \App\Models\RelationshipModel::find($relationshipId);
                    $relationship_multi = $relationship->relationship;

                   
                    $department = \App\Models\DepartmentModel::where('dept_id', $data->dept_id_option)->first();

                    // Third preference department
                    $third_preference_dept = $department != null ? "$department->dept_name" : '';
                    // Third preference grade
                    $third_preference_grade = "$data->third_grade_id";
                @endphp

                @foreach ($api_preference as $item)
                    @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->applicant_desig_id)
                        @php
                            $first_preference = $item['dsg_desc'];
                        @endphp
                        @break
                    @endif
                @endforeach

                @foreach ($api_preference as $item)
                    @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->second_post_id)
                        @php $second_preference = $item['dsg_desc'];@endphp
                        @break
                    @endif
                @endforeach


                @foreach ($api_preference as $item)
                    @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->third_post_id)
                        @php $third_preference = $item['dsg_desc'];@endphp
                        @break
                    @endif
                @endforeach


                @foreach ($api_preference as $item)
                    @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->applicant_desig_id)
                        @php $first_preference_grade = $item['group_code'];@endphp
                        @break
                    @endif
                @endforeach


                @foreach ($api_preference as $item)
                    @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->second_post_id)
                        @php $second_preference_grade = $item['group_code'];@endphp
                        @break
                        @endif
                        @endforeach


                @foreach ($api_preference as $item)
                @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->applicant_desig_id)
                        @php $first_preference_dept = $item['field_dept_desc'];@endphp
                        @break
                         @endif
                @endforeach


                @foreach ($api_preference as $item)
                    @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->second_post_id)
                        @php $second_preference_dept = $item['field_dept_desc'];@endphp
                        @break
                    @endif
                @endforeach

                @if ($data->transfer_dept_id != null || $data->transfer_dept_id != 0)

                    @foreach ($api_preference_for_transfer_dept_id as $item)
                        @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->transfer_post_id)
                            @php $fourth_preference = $item['dsg_desc'];@endphp
                            @break
                        @endif
                    @endforeach


                    @foreach ($api_preference_for_transfer_dept_id as $item)
                        @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->transfer_post_id)
                            @php $fourth_preference_grade = $item['group_code'];@endphp
                            @break
                        @endif
                    @endforeach


                    @foreach ($api_preference_for_transfer_dept_id as $item)
                        @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data->transfer_post_id)
                            @php $fourth_preference_dept = $item['field_dept_desc'];@endphp
                            @break
                        @endif
                    @endforeach
                @endif




                {
                    "applicant_name": "{{ $data->applicant_name }}",
                    "ein": "{{ $data->ein }}",
                    "relationship": "{{ $relationship_multi }}",
                    "applicant_desig_id": "{{ $first_preference }}",
                    "second_post_id": "{{ $second_preference ?? '' }}",
                    "third_post_id": "{{ $third_preference ?? '' }}",
                    "transfer_post_id": "{{ $fourth_preference ?? '' }}",
                    "grade1": "{{ $first_preference_grade }}",
                    "grade2": "{{ $second_preference_grade ?? '' }}",
                    "grade3": "{{ $third_preference_grade ?? '' }}",
                    "grade4": "{{ $fourth_preference_grade ?? '' }}",
                    "dept1": " {{ $first_preference_dept }}",
                    "dept2": " {{ $second_preference_dept ?? '' }}",
                    "dept3": " {{ $third_preference_dept ?? '' }}",
                    "dept4": " {{ $fourth_preference_dept ?? '' }}",

                },
            @endforeach
        ];
        //sise kak asu yarani 
        var designationData = [
            @foreach ($api_preference as $designation)
                {
                    "dsg_serial": " {{ $designation['dsg_serial_no'] }}",
                    "grade": " {{ $designation['group_code'] }}",
                    "department": "{{ $designation['field_dept_desc'] }}"
                },
            @endforeach
        ];
    </script>

    <script>
        $("#applicantSelect").change(function() {

            var select = document.getElementById("applicantSelect");
            var selectedApplicant = select.value;
            // Find the selected applicant's data
            var selectedApplicantData = applicantData.find(function(applicant) {
                return applicant.ein === selectedApplicant;
            });

            // Update the options of the postSelect based on the selected applicant
            var postSelect = document.getElementById("postSelect");
            postSelect.innerHTML = ''; // Clear existing options

            // Assuming einDisplay is the JavaScript variable containing the EIN

            if (selectedApplicantData) {
                // Add options for posts
                addOption(postSelect, 'Select Post');

                $('<option>').val(selectedApplicantData.applicant_desig_id).text(selectedApplicantData
                        .applicant_desig_id)
                    .attr('data-grade', selectedApplicantData.grade1).attr('data-dept', selectedApplicantData.dept1)
                    .appendTo('#postSelect');
             
                  // Check if "second_post_id" exists before adding the second_preference
if (selectedApplicantData.second_post_id) {
    $('<option>').val(selectedApplicantData.second_post_id).text(selectedApplicantData.second_post_id)
        .attr('data-grade', selectedApplicantData.grade2).attr('data-dept', selectedApplicantData.dept2)
        .appendTo('#postSelect');
}

// Check if "third_post_id" exists before adding the third_preference
if (selectedApplicantData.third_post_id) {
    $('<option>').val(selectedApplicantData.third_post_id).text(selectedApplicantData.third_post_id)
        .attr('data-grade', selectedApplicantData.grade3).attr('data-dept', selectedApplicantData.dept3)
        .appendTo('#postSelect');
}
               
              // Check if "transfer_post_id" exists before adding the fourth_preference
    if (selectedApplicantData.transfer_post_id) {
        // fourth_preference
        $('<option>').val(selectedApplicantData.transfer_post_id).text(selectedApplicantData.transfer_post_id)
            .attr('data-grade', selectedApplicantData.grade4).attr('data-dept', selectedApplicantData.dept4)
            .appendTo('#postSelect');
    }


            }
            updatePostInfo();
        });


        function updatePostInfo() {
            var postSelect = document.getElementById("postSelect");
            var selectedPost = postSelect.value;

            // Find the selected post's data
            var selectedPostData = designationData.find(function(designation) {
                return designation.dsg_serial_no === selectedPost;
            });
            // Update grade and department based on the selected post

            $('#gradeDisplay').val($('#postSelect option[value="' + postSelect.value + '"]').data('grade'));
            $('#departmentDisplay').val($('#postSelect option[value="' + postSelect.value + '"]').data('dept'));



        }


        function addOption(select, value) {
            if (value.trim() !== '') {
                var option = document.createElement("option");
                option.value = value;
                option.text = value;
                select.add(option);
            }
        }
    </script>
@endsection
