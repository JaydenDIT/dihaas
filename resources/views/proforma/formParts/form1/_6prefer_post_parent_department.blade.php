<h5 class="py-2 bg-success bg-gradient text-white fw-bold rounded mt-3">Post Proposed for Appointment in Parent
    Department</h5>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>First Preference: </b></label>
    <div>
        <select name="request_dsg_srno_1" id="request_dsg_srno_1" class="form-select request_post"
            data-change-id="request_group_code_1" required>
            <option value="" selected disabled>Choose...</option>
            @if ($action == 'edit')
                @foreach ($parentPostList as $row)
                    <option value="{{ $row['dsg_srno'] }}" data-group="{{ $row['group_cd'] }}"
                        {{ $row['dsg_srno'] == $proforma->request_dsg_srno_1 ? 'selected' : '' }}>
                        {{ $row['dsg_desc'] }}</option>
                @endforeach
            @endif
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Grade for First Preference : </b></label>
    <div>
        <input type="text" name="request_group_code_1" id="request_group_code_1" placeholder="First Preference Grade"
            class="form-control" value="{{ $proforma->request_group_code_1 ?? '' }}" required readonly>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Second Preference: </b></label>
    <div>
        <select name="request_dsg_srno_2" id="request_dsg_srno_2" class="form-select request_post"
            data-change-id="request_group_code_2" required>
            <option value="" selected disabled>Choose...</option>
            @if ($action == 'edit')
                @foreach ($parentPostList as $row)
                    <option value="{{ $row['dsg_srno'] }}" data-group="{{ $row['group_cd'] }}"
                        {{ $row['dsg_srno'] == $proforma->request_dsg_srno_2 ? 'selected' : '' }}>
                        {{ $row['dsg_desc'] }}</option>
                @endforeach
            @endif
        </select>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>

<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Grade for Second Preference : </b></label>
    <div>
        <input type="text" name="request_group_code_2" id="request_group_code_2"
            placeholder="Second Preference Grade" class="form-control"
            value="{{ $proforma->request_group_code_2 ?? '' }}" required readonly>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
