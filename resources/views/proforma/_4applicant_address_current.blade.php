<!--  Address -->
<h5 class="py-1 mt-4 bg-success bg-gradient text-white fw-bold">
    Applicant Current Address
</h5>

<!--  Address1 -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Address: </b></label>
    <div>
        <input type="text" name="applicant_current_locality" id="applicant_current_locality" placeholder="Locality"
            class="form-control  is_address address" maxlength="75" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
<!--  state -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>State: </b></label>
    <div>
        <select name="applicant_current_state_id" id="applicant_current_state_id" class="form-select state_id_flag address"
            data-change-id="applicant_current_district_id" required>
            <option value="" selected disabled>Choose...</option>
            @foreach($states as $row)
            <option value="{{$row->state_id}}">{{$row->state_name}}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
<!--  district -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>District: </b></label>
    <div>
        <select name="applicant_current_district_id" id="applicant_current_district_id"
            class="form-select district_id_flag address"
            data-change-id="applicant_current_subdivision_id" required>
            <option value="" selected disabled>Choose...</option>
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
<!--  subdivision -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Sub-Division: </b></label>
    <div>
        <select name="applicant_current_subdivision_id" id="applicant_current_subdivision_id"
            class="form-select address" required>
            <option value="" selected disabled>Choose...</option>
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
<!--  Pin -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Pin Code: </b></label>
    <div>
        <input type="text" name="applicant_current_pincode" id="applicant_current_pincode" placeholder="Pin Code"
            class="form-control address  is_number"
            pattern="[0-9]+" title="please enter number only" minlength="6" maxlength="6" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>