<h5 class="py-2 bg-success bg-gradient text-white fw-bold rounded">Deceased Details</h5>
<!-- EIN -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label "><b class="required_label">EIN of the Deceased Employee (Govt. of Manipur) :
        </b></label>
    <div class="row justify-content-between">
        <div class="col-8">
            <input type="text" name="deceased_ein" id="deceased_ein"
                placeholder="EIN of the Deceased Employee (Govt. of Manipur)" class="form-control is_address"
                value="{{ $proforma->deceased_ein ?? '' }}" maxlength="20" required>

            <div class="invalid-feedback" role="alert">
                This field is required.
            </div>
        </div>
        <div class="col-4">
            <button type="button" class="ein-search-btn form-control btn btn-primary  rounded"> <i
                    class="fa fa-search"></i>
                Search</button>
        </div>
    </div>
</div>


<div class="row col-sm-6 mb-2 ps-5 "></div>
<!-- Deceased name -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Name of the Deceased Employee: </b></label>
    <div>
        <input type="text" name="deceased_emp_name" id="deceased_emp_name" placeholder="Employee Name"
            class="form-control is_name" value="{{ $proforma->deceased_emp_name ?? '' }}" maxlength="75" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<!-- Post Held during emp time-->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label"><b>Post Held: </b></label>
    <div>
        <input type="hidden" name="deceased_desig_cd" id="deceased_desig_cd"
            value="{{ $proforma->deceased_desig_cd ?? '' }}" />
        <input type="text" name="deceased_emp_desig" id="deceased_emp_desig" placeholder="Post Held"
            class="form-control   is_address" maxlength="75" value="{{ $proforma->deceased_emp_desig ?? '' }}">
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- Administrative Department -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Administrative Department: </b></label>
    <div>
        <input type="hidden" name="deceased_adm_dept_cd" id="deceased_adm_dept_cd"
            value="{{ $proforma->deceased_adm_dept_cd ?? '' }}" />
        <input type="text" name="deceased_adm_dept_desc" id="deceased_adm_dept_desc"
            placeholder="Administrative Department's Name" class="form-control is_name" maxlength="75"
            value="{{ $proforma->deceased_adm_dept_desc ?? '' }}" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- Department -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Department: </b></label>
    <div>
        <input type="hidden" name="deceased_field_dept_cd" id="deceased_field_dept_cd"
            value="{{ $proforma->deceased_field_dept_cd ?? '' }}" />
        <input type="text" name="deceased_field_dept_desc" id="deceased_field_dept_desc"
            placeholder="Department Name" class="form-control is_address"
            value="{{ $proforma->deceased_field_dept_desc ?? '' }}" maxlength="75" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<!-- DOA -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Date of Appointment: </b></label>
    <div>
        <input type="date" name="deceased_doa" id="deceased_doa" placeholder="Date of Appointment"
            value="{{ $proforma->deceased_doa ?? '' }}" class="form-control" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- DOB -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Date of Birth: </b></label>
    <div>
        <input type="date" name="deceased_dob" id="deceased_dob" placeholder="Date of Appointment"
            value="{{ $proforma->deceased_dob ?? '' }}" class="form-control" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- Grade/Group of the emp -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Grade/Group: </b></label>
    <div>
        <input type="text" name="deceased_emp_group" id="deceased_emp_group" placeholder="Grade/Group"
            value="{{ $proforma->deceased_emp_group ?? '' }}" class="form-control is_name" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
