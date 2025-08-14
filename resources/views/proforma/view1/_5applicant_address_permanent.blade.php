<!-- Applicant Permanent Address -->
<div class="accordion-item p-0">
    <h2 class="accordion-header d-flex justify-content-between align-items-center bg-success bg-gradient text-white fw-bold p-2 ps-3">
        <button class="accordion-button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-ApplicantPermanentAddress"
            aria-expanded="true"
            aria-controls="viewCollapse-ApplicantPermanentAddress">
            Applicant Permanent Address
        </button>
        <span class="toggle-icon fw-bold" style="font-size: 1.2rem;">−</span>
    </h2>



    <div id="viewCollapse-ApplicantPermanentAddress" class="accordion-collapse collapse show p-2 ps-3">
        <div class="accordion-body">

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Address:</div>
                <div class="col-sm-8">{{ $proforma->applicant_permanent_locality ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">State:</div>
                <div class="col-sm-8">{{ $proforma->permanentState->state_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">District:</div>
                <div class="col-sm-8">{{ $proforma->permanentDistrict->district_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Sub-Division:</div>
                <div class="col-sm-8">{{ $proforma->permanentSubdivision->subdivision_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Pin Code:</div>
                <div class="col-sm-8">{{ $proforma->applicant_permanent_pincode ?? '-' }}</div>
            </div>

        </div>
    </div>
</div>