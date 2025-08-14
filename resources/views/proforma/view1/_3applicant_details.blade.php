<!-- Applicant/Claimant Details -->
<div class="accordion-item p-0">
    <h2 class="accordion-header">
        <button class="accordion-button bg-success bg-gradient text-white fw-bold p-2 ps-3"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-Applicant"
            aria-expanded="true"
            aria-controls="viewCollapse-Applicant">
            Applicant/Claimant Details
        </button>
    </h2>
    <div id="viewCollapse-Applicant" class="accordion-collapse collapse show p-2 ps-3">
        <div class="accordion-body">

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Applicant Name:</div>
                <div class="col-sm-8">{{ $proforma->applicant_name ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Sex:</div>
                <div class="col-sm-8 text-capitalize">{{ ucwords($proforma->applicant_sex ?? '-') }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Date of Birth:</div>
                <div class="col-sm-8">{{ $proforma->applicant_dob ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Handicapped:</div>
                <div class="col-sm-8">{{ $proforma->physically_handicapped ? 'Yes' : 'No' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Mobile Number:</div>
                <div class="col-sm-8">{{ $proforma->applicant_mobile ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Email:</div>
                <div class="col-sm-8">{{ $proforma->applicant_email ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Relationship with the Deceased/Retired:</div>
                <div class="col-sm-8">
                    {{ $relationships->firstWhere('relationship_id', $proforma->relationship_id)->relationship_name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Caste:</div>
                <div class="col-sm-8">
                    {{ $castes->firstWhere('caste_id', $proforma->caste_id)->caste_name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Educational Qualification:</div>
                <div class="col-sm-8">
                    {{ $qualifications->firstWhere('qualification_id', $proforma->applicant_qualification_id)->qualification_name ?? '-' }}
                </div>
            </div>

            @if($proforma->applicant_qualification_other)
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Specify Educational Qualification:</div>
                <div class="col-sm-8">{{ $proforma->applicant_qualification_other ?? '-' }}</div>
            </div>
            @endif

        </div>
    </div>
</div>