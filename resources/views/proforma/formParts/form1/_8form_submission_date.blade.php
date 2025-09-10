<h5 class="py-2 bg-success bg-gradient text-white fw-bold rounded mt-3">Date of Submission of the form</h5>

<!-- input for proforma_submission_date -->
<div class="row col-sm-6 mb-2 ps-5">
    <label class="col-form-label  required_label"><b>Proforma Submission Date: </b></label>
    <p class="text-muted">This is very much mandatory if form is to be submitted by an official / departmental user.</p>
    <div>
        <input type="date" name="proforma_submission_date" id="proforma_submission_date"
            value="{{ $proforma->proforma_submission_date ?? '' }}" class="form-control" required>
        <div class="invalid-feedback" role="alert">
            This field is required.
        </div>
    </div>
</div>
