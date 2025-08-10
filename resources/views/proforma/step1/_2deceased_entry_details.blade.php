<h5 class="py-1 bg-success bg-gradient text-white fw-bold mt-4">Information of the Deceased Govererntment Servant</h5>


<p class="text-danger">Expired on Duty: Government servants who died while performing official
    duties viz. election/census /survey /research / official tour/ field
    inspection etc. or who died in insurgency related violence while performing official
    duties.</p>

<!-- Date of Expiry -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label required_label"><b>Date of Expiry: </b></label>
    <div>
        <input type="date" name="deceased_doe" id="deceased_doe" placeholder="date of expiry" class="form-control" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- Expired on duty -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class=" required_label"><b>Is Expired on duty: </b></label>
    <div class="hstack gap-3">
        <div class="form-check">
            <input type="radio" name="expire_on_duty" value="1" class="form-check-input expire_on_duty_flag"
                required>
            Yes
        </div>
        <div class="form-check">
            <input type="radio" name="expire_on_duty" value="0" class="form-check-input expire_on_duty_flag" checked
                required>
            No
        </div>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>


<!-- Date of Expiry -->
<div class="row col-sm-6 mb-2 ps-5 under_expire_on_duty_flag">
    <label class="col-form-label required_label"><b>Cause of Death: </b></label>
    <div>
        <textarea name="deceased_causeofdeath" id="deceased_causeofdeath" placeholder="Cause of Death"
            class="form-control under_expire_on_duty_flag"
            maxlength="300" required></textarea>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>