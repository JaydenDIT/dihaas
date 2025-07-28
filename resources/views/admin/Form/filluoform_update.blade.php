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
                            <b>Applicant Name: {{ $data['applicant_name'] }} &nbsp; EIN of the Deceased:
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

                            <div class="col-sm-3 " style="text-align: left;">

                                <label for="dp_file_no" class="form-label">DP File No.</label>
                            </div>
                            <div class="col-sm-3 ">

                                <!-- <input type="text" name="efile_dp" value="{{$data->efile_dp}}" readonly> -->

                                <h5>{{$data->efile_dp}}</h5>
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

                                <h5>{{$data->efile_ad}}</h5>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-3 " style="text-align: left;">

                                <label for="relationship" class="form-label"> Relationship with Deceased </label>
                            </div>
                            <div class="col-sm-3 ">



                                <h5>{{$relationship->relationship}}</h5>

                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div>


                        <br>

                        <div class="row" id="option1Div">

                            <div class="col-sm-2">

                            </div>
                            <div class=" col-sm-3 form-check">

                                @if($uo_filled_data->preference != null)
                                <input class="form-check-input" type="radio" name="post_option" id="exampleRadios1"
                                    value="option1" checked>
                                @else
                                <input class="form-check-input" type="radio" name="post_option" id="exampleRadios1"
                                    value="option1">

                                @endif
                                <label class="form-check-label" for="exampleRadios1">
                                    <b> Employee Preferred Post</b>

                                </label>
                            </div>
                            @if($data['transfer_post_id'] != null)
                            {{-- Displaying fourth preference post --}}
                            @php $fourth_preference = null; @endphp
                            @foreach ($api_preference_for_transfer_dept_id as $item)
                            @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['transfer_post_id'])
                            @php $fourth_preference = $item['dsg_desc']; @endphp

                            @break
                            @endif
                            @endforeach

                            {{-- Displaying fourth preference dept --}}
                            @php $fourth_preference_dept = null; @endphp
                            @foreach ($api_preference_for_transfer_dept_id as $item)
                            @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['transfer_post_id'])
                            @php $fourth_preference_dept = $item['field_dept_desc']; @endphp

                            @break
                            @endif
                            @endforeach

                            {{-- Displaying fourth preference grade --}}
                            @php $fourth_preference_grade = null; @endphp
                            @foreach ($api_preference_for_transfer_dept_id as $item)
                            @if (isset($item['dsg_serial_no']) && $item['dsg_serial_no'] == $data['transfer_post_id'])
                            @php $fourth_preference_grade = $item['group_code']; @endphp

                            @break
                            @endif
                            @endforeach




                            @endif


                            @if ($data['transfer_post_id'] == null )

                            <div class="col-sm-3">
                                <select id="preferenceSelect" class="form-select" aria-label="Default select example"
                                    name="preferenceSelect" disabled>
                                    <option value="" selected>Select Post</option>
                                    <!-- <option value="{{$uo_filled_data['preference']}}">
                                            {{$preference_update_filled}}</option> -->
                                    <option value="{{ $first_preference }}">
                                        {{ $first_preference }}


                                    </option>
                                    <!-- seema -->

                                  @if ($data['second_post_id'] !== null )
                                    <option value="{{$second_preference}}">{{$second_preference}}</option>
                                    @endif

                                    @if ($data['third_post_id'] !== null)
                                    <option value="{{$third_preference}}">{{$third_preference}} </option>
                                     @endif
                                     
                                     <!-- seema -->        



                                </select>
                            </div>

                            @endif
                            @if ($data['transfer_post_id'] !== null)


                            <div class="col-sm-3">
                                <select id="preferenceSelect" class="form-select" aria-label="Default select example"
                                    name="preferenceSelect" disabled>
                                    <option value="" selected>Select Post</option>
                                    <!-- <option value="{{$uo_filled_data['preference']}}">
                                            {{$preference_update_filled}}</option> -->
                                    <option value="{{ $first_preference }}">
                                        {{ $first_preference }}

                                    

                                    </option>

                                    <!-- seema -->

                                    @if ($data['second_post_id'] !== null )
                                    <option value="{{$second_preference}}">{{$second_preference}}</option>
                                    @endif

                                    @if ($data['third_post_id'] !== null )
                                    <option value="{{$third_preference}}">{{$third_preference}} </option>
                                     @endif
                                    <!-- seema -->
                                    <option value="{{ $fourth_preference }}">{{ $fourth_preference }}</option>
                                   


                                </select>
                            </div>

                            @endif

                            @if ($data['transfer_post_id']== null)
                            <div class="col-sm-4" id="firstPreferenceInfo" style="display: none;">

                                Department : <input type="text" id="deptname1" name="deptname1"
                                    value="{{$first_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade1" name="grade1" value="{{$first_preference_grade}}"
                                    readonly>


                            </div>

                            <div class="col-sm-4" id="secondPreferenceInfo" style="display: none;">

                                Department : <input type="text" id="deptname2" name="deptname2"
                                    value="{{$second_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade2" name="grade2"
                                    value="{{$second_preference_grade}}" readonly>


                            </div>


                            <div class="col-sm-4" id="thirdPreferenceInfo" style="display: none;">
                                Department : <input type="text" id="deptname3" name="deptname3"
                                    value="{{$third_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade3" name="grade3" value="{{$third_preference_grade}}"
                                    readonly>

                            </div>
                            @endif
                            @if ($data['transfer_post_id']!== null)                      
                            <div class="col-sm-4" id="firstPreferenceInfo" style="display: none;">
                                Department : <input type="text" id="deptname1" name="deptname1"
                                    value="{{$first_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade1" name="grade1" value="{{$first_preference_grade}}"
                                    readonly>

                            </div>

                            <div class="col-sm-4" id="secondPreferenceInfo" style="display: none;">

                                Department : <input type="text" id="deptname2" name="deptname2"
                                    value="{{$second_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade2" name="grade2"
                                    value="{{$second_preference_grade}}" readonly>

                            </div>


                            <div class="col-sm-4" id="thirdPreferenceInfo" style="display: none;">
                                Department : <input type="text" id="deptname3" name="deptname3"
                                    value="{{$third_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade3" name="grade3" value="{{$third_preference_grade}}"
                                    readonly>

                            </div>
                            <div class="col-sm-4" id="fourthPreferenceInfo" style="display: none;">
                                Department : <input type="text" id="deptname4" name="deptname4"
                                    value="{{$fourth_preference_dept}}" readonly><br>
                                Grade : <input type="text" id="grade4" name="grade4"
                                    value="{{$fourth_preference_grade}}" readonly>

                            </div>



                            @endif






                        </div>

                        <br>


                        <div class="row">


                            <div class="col-sm-2">

                            </div>



                            <div class="col-sm-2">




                            </div>




                        </div>

                        <br>
                        <div class="row">

                            <div class="col-sm-2">

                            </div>
                            <div class=" col-sm-3 form-check">

                                @if($uo_filled_data->dept_id_option != null)

                                <input class="form-check-input" type="radio" name="post_option" id="exampleRadios2"
                                    value="option2" checked>
                                @else
                                <input class="form-check-input" type="radio" name="post_option" id="exampleRadios2"
                                    value="option2">
                                @endif

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
                            <div class="col-sm-3 " style="text-align: left;">

                                <label for="post_id" class="form-label">Department </label>
                            </div>
                            <div class="col-sm-3 ">

                                <select class="form-select" aria-label="Default select example" id="dept_id_option"
                                    name="dept_id_option" disabled>
                                    <option value="" selected>All Department</option>
                                    @foreach($deptListArray as $option)
                                    <option value="{{ $option['dept_id'] == null ? null : $option['dept_name'] }}"
                                        required {{($selected == $option['dept_name'])?'selected':''}}
                                        data-deptid="{{$option['dept_id']}}"> {{$option['dept_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div> <br>



                        <div class=" row" id="option2Div">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-3" style="text-align: left;">

                                <label for="post_id" class="form-label"> Post </label>
                            </div>
                            <div class="col-sm-3 ">


                                <select class="form-select  @error('third_post_id') is-invalid @enderror "
                                    aria-label="Default select example" id="third_post_id" name="third_post_id"
                                    disabled>
                                    <option value="" selected disabled>Select Designation</option>

                                    <option value="{{ $option['dsg_desc'] == null ? null : $option['dsg_desc'] }}"
                                        required {{($selected == $option['dsg_desc'])?'selected':''}}>
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
                            <div class="col-sm-3 " style="text-align: left;">

                                <label for="post_id" class="form-label">Grade </label>
                            </div>
                            <div class="col-sm-3 ">

                                <input class="form-control" type="text" value="" id="third_grade_id"
                                    name="third_grade_id" readonly>
                            </div>
                            <div class="col-sm-2">

                            </div>
                        </div><br>




                        <div class="row">
                            <div class="col-sm-2">

                            </div>
                            <div class="col-sm-3" style="text-align: left;">

                                <label for="signing_authority_1" class="form-label">Signing Authority 1</label>
                            </div>
                            <div class="col-sm-3 ">
                                <select id="signing_authority_1" name="signing_authority_1" class="form-select">
                                    <option selected value="{{$empname->signing_authority_1}}">{{$authorityName1}}
                                    </option>
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
                            <div class="col-sm-3 " style="text-align: left;">

                                <label for="signing_authority_2" class="form-label">Signing Authority 2</label>
                            </div>
                            <div class="col-sm-3 ">
                                <select id="signing_authority_2" name="signing_authority_2" class="form-select">
                                    <option selected value="{{$empname->signing_authority_2}}">{{$authorityName2}}
                                    </option>
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

                    <!-- Section to display selected department and grade -->
                    <div class="col-sm-6" id="selectedInfo" style="display: none;">
                        <span id="selectedDept" name="selectedDept" style="display: none;"></span><br>
                        <span id="selectedGrade" name="selectedGrade" style="display: none;"></span>
                    </div>
                    <!-- Hidden input fields to store the selected values -->
                    <input type="hidden" id="hiddenDept" name="hiddenDept">
                    <input type="hidden" id="hiddenGrade" name="hiddenGrade">

                </form>
            </div>
        </div>

    </div>

</div>



<!-- {{$first_preference}} -->


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

<!--  From this Function to enable/disable and clear elements based on radio button selection -->
<script>
$(document).ready(function() {
    // Function to enable/disable and clear elements based on radio button selection
    function toggleElements() {
        var option2Elements = $("#dept_id_option, #third_post_id, #third_grade_id");
        var option1Elements = $(
            "#preferenceSelect, #firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo , #fourthPreferenceInfo"
        );
        var threePost = $(
            "#firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo ,#fourthPreferenceInfo");
        if ($("#exampleRadios1").is(":checked")) {
            // Option 1 selected
            option2Elements.prop("disabled", true).val(" ");
            option1Elements.prop("disabled", false);

        } else if ($("#exampleRadios2").is(":checked")) {
            // Option 2 selected
            option2Elements.prop("disabled", false);
            option1Elements.prop("disabled", true).val(" ");
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



<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- From this The below code is to display the department and Grades for the three option -->
@if ($data['transfer_post_id'] !== null)
<script>
$(document).ready(function() {
    $("#preferenceSelect").change(function() {
        var selectedValue = $(this).val();

        // Hide all preference info divs
        $("#firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo, #fourthPreferenceInfo")
            .hide();

        // Show the corresponding preference info div based on the selected option
        if (selectedValue == "{{ $first_preference }}") {
            $("#firstPreferenceInfo").show();
        } else if (selectedValue == "{{ $second_preference }}") {
            $("#secondPreferenceInfo").show();
        } else if (selectedValue == "{{ $third_preference }}") {
            $("#thirdPreferenceInfo").show();
        } else if (selectedValue == null || selectedValue == "{{ $fourth_preference }}") {
            $("#fourthPreferenceInfo").show();
        }
    });

    // Compare $preference_update_filled with each preference and show the corresponding info
    var preferenceUpdateFilled = "{{ $uo_filled_data['preference'] }}";

    if (preferenceUpdateFilled == "{{ $first_preference }}") {
        $("#firstPreferenceInfo").show();
    } else if (preferenceUpdateFilled == "{{ $second_preference }}") {
        $("#secondPreferenceInfo").show();
    } else if (preferenceUpdateFilled == "{{ $third_preference }}") {
        $("#thirdPreferenceInfo").show();
    } else if (preferenceUpdateFilled == null || preferenceUpdateFilled == "{{ $fourth_preference }}") {
        $("#fourthPreferenceInfo").show();
    }
});
</script>



@else


<script>
$(document).ready(function() {
    $("#preferenceSelect").change(function() {
        var selectedValue = $(this).val();

        // Hide all preference info divs
        $("#firstPreferenceInfo, #secondPreferenceInfo, #thirdPreferenceInfo")
            .hide();

        // Show the corresponding preference info div based on the selected option
        if (selectedValue == "{{ $first_preference }}") {
            $("#firstPreferenceInfo").show();
        } else if (selectedValue == "{{ $second_preference }}") {
            $("#secondPreferenceInfo").show();
        } else if (selectedValue == "{{ $third_preference }}") {
            $("#thirdPreferenceInfo").show();
        }
    });

    // Compare $preference_update_filled with each preference and show the corresponding info
    var preferenceUpdateFilled = "{{ $uo_filled_data['preference'] }}";

    if (preferenceUpdateFilled == "{{ $first_preference }}") {
        $("#firstPreferenceInfo").show();
    } else if (preferenceUpdateFilled == "{{ $second_preference }}") {
        $("#secondPreferenceInfo").show();
    } else if (preferenceUpdateFilled == "{{ $third_preference }}") {
        $("#thirdPreferenceInfo").show();
    }
});
</script>

@endif

<!-- Upto this The below code is to display the department and Grades for the three option -->


<!-- Upto this we call the ajax for SAVE -->



<!-- Below code is added by Kanan -->
<script>
$(document).ready(function() {
    $("#third_post_id").empty();
    $('#third_post_id').append(new Option('{{$third_post}}', ''));
    $('#third_post_id option[value=""]').attr('disabled', true);

    var id = $('#dept_id_option').find('option:selected').val();
    // var data_dept_id = {
    //     'dept_id': $('#dept_id_option').find('option:selected').val(),
    // };
    var data_dept_id = {
        'dept_id': $(this).find('option:selected').data('deptid'),
    };

    $.get('{{ route("retrieve_dept") }}', data_dept_id, function(id, textStatus, xhr) {

        //now load the result data in id third_post_id i.e. for designation  
        console.log(data_dept_id.dept_id);
        console.log(JSON.stringify(id));

        $.each(id, function(index, element) {

            //$('#third_post_id').append(new Option(element.dsg_desc, element.dsg_serial_no)); //only value and text
            //Below is for adding extra attribute
            // $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr( for taking value as id
            $('<option>').val(element.dsg_desc).text(element.dsg_desc)
                .attr('data-grade', element.group_code).appendTo('#third_post_id');

        });

    });


    var hiddenThirdPostId = $('#hiddenThirdPostId').val();
    $('#third_post_id option[value="' + hiddenThirdPostId + '"]').attr('selected', true)

    //      alert(JSON.stringify(selectid));

    //     $('#third_post_id').find('[value="'+this.value+'"]').text();
    // $('#third_grade_id').val($('#third_post_id option[value="'+this.value+'"]').data('grade'));


});

$('#applicant_desig_id').change(function() {
    var id = $(this).find('option:selected').val();
    var array = <?php echo json_encode($api_preference); ?>;
    var item = array.find(item => item.dsg_serial_no === id);
    $('#applicant_grade').val(item.group_code);

})

$('#second_post_id').change(function() {
    var id = $(this).find('option:selected').val();
    var array = <?php echo json_encode($api_preference); ?>;
    var item = array.find(item => item.dsg_serial_no === id);
    $('#second_grade_id').val(item.group_code);

})


$('#third_post_id').change(function() {
    var id = $(this).find('option:selected').val();
    var item = $(this).find(item => item.dsg_serial_no === id);
    // console.log(third_post_id)   ;
    // alert($('#third_post_id option[value="'+this.value+'"]').data('grade'));
    $('#third_grade_id').val($('#third_post_id option[value="' + this.value + '"]').data('grade'));

})


$('#dept_id_option').change(function() {

    //make blank      

    $("#third_post_id").empty();
    $('#third_post_id').append(new Option('', ''));
    $('#third_post_id option[value=""]').attr('disabled', true);

    $('#third_grade_id').val('');


    var id = $(this).find('option:selected').val();
    // var data_dept_id = {
    //     'dept_id': $(this).find('option:selected').val(),
    // };
    var data_dept_id = {
        'dept_id': $(this).find('option:selected').data('deptid'),
    };
    // console.log(data_dept_id);
    $.get('{{ route("retrieve_dept") }}', data_dept_id, function(id, textStatus, xhr) {

        //now load the result data in id third_post_id i.e. for designation  

        $.each(id, function(index, element) {

            //$('#third_post_id').append(new Option(element.dsg_desc, element.dsg_serial_no)); //only value and text
            //Below is for adding extra attribute
            $('<option>').val(element.dsg_desc).text(element.dsg_desc)
                // $('<option>').val(element.dsg_serial_no).text(element.dsg_desc).attr(
                .attr('data-grade', element.group_code).appendTo('#third_post_id');

        });

    });


})
</script>


<script>
$(document).ready(function() {
    // Intercept the form submission
    $('#fill_uo').submit(function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Show confirmation modal
        $('#confirmationModal').modal('show');
    });

    // Handle the confirmation button click
    $('#confirmSubmit').click(function() {
        // Get form data
        var formData = $('#fill_uo').serialize();

        // Hide the modal
        $('#confirmationModal').modal('hide');

        // Perform Ajax request
        $.ajax({
            type: 'POST',
            url: "{{ route('update_uo', Crypt::encryptString($data->ein)) }}",
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





<!-- seema -->
@if ($data['transfer_post_id'] !== null)

<script>
var preferenceSelect = document.getElementById('preferenceSelect');
var selectedDept = document.getElementById('selectedDept');
var selectedGrade = document.getElementById('selectedGrade');
var hiddenDeptInput = document.getElementById('hiddenDept');
var hiddenGradeInput = document.getElementById('hiddenGrade');
var preferenceForm = document.getElementById('preferenceForm');

preferenceSelect.addEventListener('change', function() {
    // Hide all preference info sections
    hideAllPreferenceInfo();

    // Show the selected department and grade
    var selectedPreference = preferenceSelect.value;

    if (selectedPreference === '{{ $first_preference }}') {
        showSelectedInfo('{{ $first_preference_dept }}', '{{ $first_preference_grade }}');
    } else if (selectedPreference === '{{ $second_preference }}') {
        showSelectedInfo('{{ $second_preference_dept }}', '{{ $second_preference_grade }}');
    } else if (selectedPreference === '{{ $third_preference }}') {
        showSelectedInfo('{{ $third_preference_dept }}', '{{ $third_preference_grade }}');
    } else if (selectedPreference === '{{ $fourth_preference }}') {
        showSelectedInfo('{{ $fourth_preference_dept }}', '{{ $fourth_preference_grade }}');
    }
});

function hideAllPreferenceInfo() {
    document.getElementById('selectedInfo').style.display = 'none';
}

function showSelectedInfo(dept, grade) {
    selectedDept.innerText = dept;
    selectedGrade.innerText = grade;
    hiddenDeptInput.value = dept; // Update the hidden input fields with the selected values
    hiddenGradeInput.value = grade;
    document.getElementById('selectedInfo').style.display = 'block';

    // Automatically submit the form when the values are updated
    preferenceForm.submit();
}
</script>



@else



<script>
var preferenceSelect = document.getElementById('preferenceSelect');
var selectedDept = document.getElementById('selectedDept');
var selectedGrade = document.getElementById('selectedGrade');
var hiddenDeptInput = document.getElementById('hiddenDept');
var hiddenGradeInput = document.getElementById('hiddenGrade');
var preferenceForm = document.getElementById('preferenceForm');

preferenceSelect.addEventListener('change', function() {
    // Hide all preference info sections
    hideAllPreferenceInfo();

    // Show the selected department and grade
    var selectedPreference = preferenceSelect.value;

    if (selectedPreference === '{{ $first_preference }}') {
        showSelectedInfo('{{ $first_preference_dept }}', '{{ $first_preference_grade }}');
    } else if (selectedPreference === '{{ $second_preference }}') {
        showSelectedInfo('{{ $second_preference_dept }}', '{{ $second_preference_grade }}');
    } else if (selectedPreference === '{{ $third_preference }}') {
        showSelectedInfo('{{ $third_preference_dept }}', '{{ $third_preference_grade }}');
    } 
});

function hideAllPreferenceInfo() {
    document.getElementById('selectedInfo').style.display = 'none';
}

function showSelectedInfo(dept, grade) {
    selectedDept.innerText = dept;
    selectedGrade.innerText = grade;
    hiddenDeptInput.value = dept; // Update the hidden input fields with the selected values
    hiddenGradeInput.value = grade;
    document.getElementById('selectedInfo').style.display = 'block';

    // Automatically submit the form when the values are updated
    preferenceForm.submit();
}
</script>

@endif

<!-- seema -->

@endsection