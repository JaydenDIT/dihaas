<!-- Applicant Current Address -->
<div class="accordion-item p-0">
    <h2 class="accordion-header text-white">
        <button class="accordion-button bg-success bg-gradient text-white fw-bold p-2" type="button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-ApplicantAddress"
            aria-expanded="true"
            aria-controls="viewCollapse-ApplicantAddress">
            Applicant Current Address
        </button>
    </h2>


    <div id="viewCollapse-ApplicantAddress" class="accordion-collapse collapse show p-2 ps-3  bg-pink">
        <div class="accordion-body">

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Address:</div>
                <div class="col-sm-8">{{ $proforma->applicant_current_locality ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">State:</div>
                <div class="col-sm-8">{{ $proforma->currentState->state_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">District:</div>
                <div class="col-sm-8">{{ $proforma->currentDistrict->district_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Sub-Division:</div>
                <div class="col-sm-8">{{ $proforma->currentSubdivision->subdivision_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Pin Code:</div>
                <div class="col-sm-8">{{ $proforma->applicant_current_pincode ?? '-' }}</div>
            </div>

        </div>
    </div>
</div>