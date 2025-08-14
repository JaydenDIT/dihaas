<div class="accordion-item p-0">
    <h2 class="accordion-header d-flex justify-content-between align-items-center bg-success bg-gradient text-white fw-bold p-2 ps-3">
        <button class="accordion-button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-Applicant"
            aria-expanded="true"
            aria-controls="viewCollapse-Applicant">
            Applicant/Claimant Details
        </button>
        <span class="toggle-icon fw-bold" style="font-size: 1.2rem;">−</span>
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
                    {{ $proforma->relationship->relationship_name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Caste:</div>
                <div class="col-sm-8">
                    {{ $proforma->caste->caste_name ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Educational Qualification:</div>
                <div class="col-sm-8">
                    @if( strtolower($proforma->qualification->qualification_name ?? '') === 'others' )
                    {{ $proforma->applicant_qualification_name ?? '-' }}
                    @else
                    {{ $proforma->qualification->qualification_name ?? '-' }}
                    @endif

                </div>
            </div>



        </div>
    </div>
</div>