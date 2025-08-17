<!-- Post Proposed for Appointment in Parent Department -->



<div class="accordion-item p-0">
    <h2 class="accordion-header text-white">
        <button class="accordion-button bg-success bg-gradient text-white fw-bold p-2" type="button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-PostProposed"
            aria-expanded="true"
            aria-controls="viewCollapse-PostProposed">
            Post Proposed for Appointment in Parent Department
        </button>
    </h2>

    <div id="viewCollapse-PostProposed" class="accordion-collapse collapse show p-2 ps-3  bg-pink">
        <div class="accordion-body">

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">First Preference:</div>
                <div class="col-sm-8">
                    {{ $proforma->request_dsg_desc_1 ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Grade for First Preference:</div>
                <div class="col-sm-8">{{ $proforma->request_group_code_1 ?? '-' }}</div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Second Preference:</div>
                <div class="col-sm-8">
                    {{ $proforma->request_dsg_desc_2 ?? '-' }}
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Grade for Second Preference:</div>
                <div class="col-sm-8">{{ $proforma->request_group_code_2 ?? '-' }}</div>
            </div>

        </div>
    </div>
</div>