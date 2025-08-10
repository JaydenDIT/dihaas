<h5 class="mt-4 py-1 bg-success bg-gradient text-white fw-bold">Applicant/Claimaint Details</h5>

<!-- Applicant details  -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Applicant Name: </b></label>
    <div>
        <input type="text" name="applicant_name" id="applicant_name" placeholder="Name of Applicant"
            class="form-control is_name" maxlength="75" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- SEX -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Sex: </b></label>
    <div class="hstack gap-3">
        <div class="form-check">
            <input type="radio" name="applicant_sex" value="male" class="form-check-input"
                required>
            Male
        </div>
        <div class="form-check">
            <input type="radio" name="applicant_sex" value="female" class="form-check-input"
                required>
            Female
        </div>
        <div class="form-check">
            <input type="radio" name="applicant_sex" value="transgender" class="form-check-input"
                required>
            Transgender
        </div>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<!-- DOB -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Date of Birth: </b></label>
    <div>
        <input type="date" name="applicant_dob" id="applicant_dob" placeholder="Date of birth"
            class="form-control" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Handicapped: </b></label>
    <div class="hstack gap-3">
        <div class="form-check">
            <input type="radio" name="physically_handicapped" value="1" class="form-check-input"
                required>
            Yes
        </div>
        <div class="form-check">
            <input type="radio" name="physically_handicapped" value="0" class="form-check-input" checked
                required>
            No
        </div>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<!-- Mobile Number -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Mobile Number: </b></label>
    <div>
        <input type="text" name="applicant_mobile" id="applicant_mobile" placeholder="Mobile"
            class="form-control is_number" minlength="10" maxlength="10" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<!-- Email -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Email: </b></label>
    <div>
        <input type="email" name="applicant_email" id="applicant_email" placeholder="Email ID"
            class="form-control" maxlength="75" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Relationship with the Deceased/Retired: </b></label>
    <div class="">
        <select name="relationship_id" id="relationship_id" class="form-select" required>
            <option value="" selected disabled>Choose...</option>
            @foreach($relationships as $row)
            <option value="{{$row->relationship_id}}">{{$row->relationship_name}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>




<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Caste: </b></label>
    <div class="">
        <select name="caste_id" id="caste_id" class="form-select" required>
            <option value="" selected disabled>Choose...</option>
            @foreach($castes as $row)
            <option value="{{$row->caste_id}}">{{$row->caste_name}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Educational Qualification: </b></label>
    <div class="">

        <input type="hidden" name="applicant_qualification_name" id="applicant_qualification_name"
            placeholder="Specify qualification_name" class="form-control is_name" maxlength="75" required>


        <select name="applicant_qualification_id" id="applicant_qualification_id" class="form-select" required>
            <option value="" selected disabled>Choose...</option>
            @foreach($qualifications as $row)
            <option value="{{$row->qualification_id}}">{{$row->qualification_name}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<!-- applicant_qualification for others -->
<div class="row col-sm-6 mb-2 ps-5 under_applicant_qualification_id">
    <label class="col-form-label  required_label"><b>Specify Educational Qualification: </b></label>
    <div class="">
        <input type="text" name="applicant_qualification_other" id="applicant_qualification_other"
            placeholder="Specify Qualification" class="form-control is_name under_applicant_qualification_id" maxlength="75">
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>