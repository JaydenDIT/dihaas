<h5 class="py-2 bg-success bg-gradient text-white fw-bold rounded mt-3">Post Proposed for Appointment in Other Department</h5>



<!-- Administrative Department -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Administrative Department: </b></label>
    <div>
        <select name="request_adm_dept_cd_3" id="request_adm_dept_cd_3" class="form-select" required>
            <option value="" selected disabled>Choose...</option>
            @foreach($adminDepartments as $department)
            <option value="{{ $department['adm_dept_cd'] }}" {{ $department['adm_dept_cd'] == $proforma->request_adm_dept_cd_3 ? 'selected' : '' }}>{{ $department['adm_dept_desc'] }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- Department -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Department: </b></label>
    <div>
        <select name="request_field_dept_cd_3" id="request_field_dept_cd_3" class="form-select" required>
            <option value="" selected disabled>Choose...</option>
            @if($action == 'edit' )
            @foreach($otherDepartList['field_dept'] as $row)
            <option value="{{ $row['field_dept_cd'] }}" {{ $row['field_dept_cd'] == $proforma->request_field_dept_cd_3 ? 'selected' : '' }}>{{ $row['field_dept_desc'] }}</option>
            @endforeach
            @endif

        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Preference: </b></label>
    <div>
        <select name="request_dsg_srno_3" id="request_dsg_srno_3" class="form-select" required>
            <option value="" selected disabled>Choose...</option>
            @if($action == 'edit' )
            @foreach($otherPostList as $row)
            <option value="{{ $row['dsg_serial_no'] }}" data-group="{{$row['group_code']}}"
                {{ $row['dsg_serial_no'] == $proforma->request_dsg_srno_3 ? 'selected' : '' }}>{{ $row['dsg_desc'] }}</option>
            @endforeach
            @endif
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Grade : </b></label>
    <div>
        <input type="text" name="request_group_code_3" id="request_group_code_3" placeholder="Grade" class="form-control"
            value="{{ $proforma->request_group_code_3 ?? '' }}" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>