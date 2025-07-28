@extends('layouts.app')

@section('content')
<?php $selected = session()->get('deptId') ?>
<?php $selected = session()->get('desigId') ?>
<div class="container mb-6">
    <br>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-10" style="text-align: left;">
                            <b>Applicant Name : {{ $data['applicant_name'] }} &nbsp; EIN of the Deceased:
                                {{ $data['ein'] }} &nbsp; Deceased Name : {{ $data['deceased_emp_name'] }}</b>
                        </div>
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

                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="dp_file_no" class="form-label">DP File No.</label>
                            </div>
                            <div class="col-sm-4 ">

                                <!-- <input type="text" name="efile_dp" value="{{$data->efile_dp}}" readonly> -->

                                <h5>{{$data->efile_dp}}</h5>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="ad_file_number" class="form-label">AD File No.</label>
                            </div>
                            <div class="col-sm-4 ">

                                <h5>{{$data->efile_ad}}</h5>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="relationship" class="form-label"> Relationship with Deceased </label>
                            </div>
                            <div class="col-sm-4 ">



                                <h5>{{$relationship->relationship}}</h5>

                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>


                        <div class="row">

                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-8">

                                <b> Applied Post (Select Only One)</b>

                            </div>
                            <div class="col-sm-2">

                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-2">

                            </div>
                            <div class=" col-sm-8 form-check">
                                <input class="form-check-input" type="radio" name="post_option" id="exampleRadios1"
                                    value="option1">
                                <label class="form-check-label" for="exampleRadios1">
                                    <b> Employee Prefer Post</b>

                                </label>
                            </div>

                            <div class="col-sm-2">

                            </div>


                        </div>

                        <br>


                        <div class="row" id="option1Div">


                            <div class="col-sm-2">

                            </div>

                            <div class="col-sm-3">
                                <select id="preferenceSelect" class="form-select" aria-label="Default select example"
                                    name="preference">
                                    <option selected>Select Post</option>
                                 
                                    <option value="{{$data->applicant_desig_id}}">{{$first_preference}}</option>
                                 

                                    <option value="{{$data->second_post_id}}">{{$second_preference}}</option>
                                  

                                  
                                    <option value="{{$data->third_post_id}}">{{$third_preference}}</option>
                                 
                                </select>
                            </div>
                            <div class="col-sm-5  " id="firstPreferenceInfo" style="display:none;">



                                Department : <b>{{$first_preference_dept}} </b>
                                <br>
                                Grade : <b>{{$first_preference_grade}} </b>

                                <br>

                            </div>
                            <div class="col-sm-5" id="secondPreferenceInfo" style="display: none;">
                                Department : <b>{{$second_preference_dept}} </b><br>
                                Grade : <b>{{$second_preference_grade}} </b>
                            </div>


                            <div class="col-sm-5" id="thirdPreferenceInfo" style="display: none;">
                                Department : <b>{{$third_preference_dept}} </b><br>
                                Grade : <b>{{$third_preference_grade}} </b>
                            </div>




                            <div class="col-sm-2">
                            </div>




                        </div>

                        <br>
                        <div class="row">

                            <div class="col-sm-2">

                            </div>
                            <div class=" col-sm-8 form-check">
                                <input class="form-check-input" type="radio" name="post_option" id="exampleRadios2"
                                    value="option2">
                                <label class="form-check-label" for="exampleRadios2">
                                    <b> Alloted Post when Employee prefer post is not vacant</b>

                                </label>
                            </div>

                            <div class="col-sm-2">

                            </div>


                        </div>

                        <br>


                        <div class=" row" id="option2Div">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="post_id" class="form-label">Department </label>
                            </div>
                            <div class="col-sm-4 ">

                                <select class="form-select" aria-label="Default select example" id="dept_id_option"
                                    name="dept_id_option" disabled>
                                    <option value="" selected>All Department</option>
                                    @foreach($deptListArray as $option)

                                    <option value="{{$option['dept_id']}}"
                                        {{$option['dept_id'] == $data['dept_id_option'] ? 'selected' : ''}} required>
                                        {{$option['dept_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div> <br>



                        <div class=" row" id="option2Div">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="post_id" class="form-label"> Post </label>
                            </div>
                            <div class="col-sm-4 ">

                                <select class="form-select  @error('third_post_id') is-invalid @enderror "
                                    aria-label="Default select example" id="third_post_id" name="third_post_id">
                                    <option value="" selected disabled>Select Designation</option>

                                    <option
                                        value="{{ $option['dsg_serial_no'] == null ? null : $option['dsg_serial_no'] }}"
                                        required {{($selected == $option['dsg_serial_no'])?'selected':''}}>
                                        {{$option['dsg_desc']}}
                                    </option>


                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div> <br>



                        <div class=" row" id="option2Div">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="post_id" class="form-label">Grade </label>
                            </div>
                            <div class="col-sm-4 ">

                                <input class="form-control" type="text" value="" id="third_grade_id"
                                    name="third_grade_id" readonly>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div> <br>





                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4" style="text-align: left;">

                                <label for="signing_authority_1" class="form-label">Signing Authority 1</label>
                            </div>
                            <div class="col-sm-4 ">
                                <select id="signing_authority_1" name="signing_authority_1" class="form-select">
                                    <option selected>Select</option>
                                    <!-- Add more options as needed -->
                                    @foreach($Sign1 as $option)

                                    <option value="{{$option['id']}}"
                                        {{$option['id'] == $option['authority_name'] ? 'selected' : ''}} required>
                                        {{$option['authority_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div> <br>
                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-4 " style="text-align: left;">

                                <label for="signing_authority_2" class="form-label">Signing Authority 2</label>
                            </div>
                            <div class="col-sm-4 ">
                                <select id="signing_authority_2" name="signing_authority_2" class="form-select">
                                    <option selected>Select</option>
                                    <!-- Add more options as needed -->
                                    @foreach($Sign2 as $option)

                                    <option value="{{$option['id']}}"
                                        {{$option['id'] == $option['name'] ? 'selected' : ''}} required>
                                        {{$option['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div> <br>

                        <p>

                        <div class="row ">
                            <div class="col-sm-2">

                            </div>

                            <div class="col-sm-8">
                                <div class="d-grid gap-5 d-md-flex justify-content-center">
                                    <a href="{{ url('ddo-assist/selectDeptByDPApprove') }}"
                                        class="btn btn-primary ">Close</a>

                                    <button type="submit" class="btn btn-primary"> Save Update</button>
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
                <button type="button" class="btn btn-secondary" id="confirmCancel" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">OK</button>
            </div>
        </div>
    </div>
</div>




<!-- From this Function to enable/disable and clear elements based on radio button selection -->
<script>
$(document).ready(function() {

    function toggleElements() {
        var option2Elements = $("#dept_id_option, #third_post_id, #third_grade_id");
        var option1Elements = $(
            "#preferenceSelect, #firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo");
        var threePost = $("#firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo");
        if ($("#exampleRadios1").is(":checked")) {
            // Option 1 selected
            option2Elements.prop("disabled", true).val("");
            option1Elements.prop("disabled", false);
        } else if ($("#exampleRadios2").is(":checked")) {
            // Option 2 selected
            option2Elements.prop("disabled", false);
            option1Elements.prop("disabled", true).val("");
            threePost.hide();


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
    $("#preferenceSelect").change(function() {
        var selectedValue = $(this).val();

        // Hide all preference info divs
        $("#firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo")
            .hide();

        // Show the corresponding preference info div based on the selected option
        if (selectedValue == "{{ $data->applicant_desig_id }}") {
            $("#firstPreferenceInfo").show();
        } else if (selectedValue == "{{ $data->second_post_id }}") {
            $("#secondPreferenceInfo").show();
        } else if (selectedValue == "{{ $data->third_post_id }}") {
            $("#thirdPreferenceInfo").show();
        }
    });
});
</script>

<!-- From this we call the ajax for SAVE-->
<script>
$(document).ready(function() {
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

        var preferenceOption = $('input[name="post_option"]:checked').val();

        if (!preferenceOption) {
            // If neither radio button is selected
            return false;
        }
        if (preferenceOption === 'option1') {
            // Check if the selected preference is not blank
            var preferenceSelect = $('#preferenceSelect').val();
            if (!preferenceSelect) {
                return false;
            }
        } else if (preferenceOption === 'option2') {
            // Check if the selected department and post are not blank
            var deptIdOption = $('#dept_id_option').val();
            var thirdPostId = $('#third_post_id').val();
            if (!deptIdOption || !thirdPostId) {
                return false;
            }
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
        var formData = $('#fill_uo').serialize();

        // Hide the modal
        $('#confirmationModal').modal('hide');

        // Perform Ajax request
        $.ajax({
            type: 'POST',
            url: "{{ route('fill_uo_save', Crypt::encryptString($data->ein)) }}",
            data: formData,
            dataType: 'json', // Change the dataType according to your backend response
            success: function(response) {
                // Handle success, e.g., show a success message
                // console.log(response);
                alert('Save Successfully');
                // alert(response.message);
                // Redirect to the desired page if needed
                window.location.href = "{{ route('selectDeptByDPApprove') }}";
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

<!-- Upto this we call the ajax for SAVE -->









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

    $("#third_post_id").empty();
    $('#third_post_id').append(new Option('Select Post', ''));
    $('#third_post_id').append(new Option('Unselect Post', ''));
    $('#third_post_id option[value=""]').attr('disabled', false);

    $('#third_grade_id').val('');


    var id = $(this).find('option:selected').val();
    var data_dept_id = {
        'dept_id': $(this).find('option:selected').val(),
    };
    // console.log(data_dept_id);
    $.get('{{ route("retrieve_dept") }}', data_dept_id, function(id, textStatus, xhr) {

        //now load the result data in id third_post_id i.e. for designation  

        $.each(id, function(index, element) {

            //$('#third_post_id').append(new Option(element.dsg_desc, element.dsg_serial_no)); //only value and text
            //Below is for adding extra attribute
            $('<option>').val(element.dsg_serial_no).text(element.dsg_desc)
                .attr('data-grade',
                    element.group_code).appendTo('#third_post_id');

        });

    });


})
</script>

<!-- Upto THIS WE COPIED FROM FROM proforma_save -->





@endsection