<!-- Post Proposed for Appointment in Parent Department -->

<div class="accordion-item p-0">
    <h2 class="accordion-header text-white">
        <button class="accordion-button bg-success bg-gradient text-white fw-bold p-2" type="button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-PostOther"
            aria-expanded="true"
            aria-controls="viewCollapse-PostOther">
            Post Proposed for Appointment in Other Department
        </button>
    </h2>




    <div id="viewCollapse-PostOther" class="accordion-collapse collapse show p-2 ps-3 bg-pink">
        <div class="accordion-body">

            <!-- Administrative Department -->
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Administrative Department:</div>
                <div class="col-sm-8">
                    {{ $proforma->request_adm_dept_desc_3 ?? '-' }}
                </div>
            </div>

            <!-- Department -->
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Department:</div>
                <div class="col-sm-8">
                    {{ $proforma->request_field_dept_desc_3 ?? '-' }}
                </div>
            </div>




            <!-- Preference -->
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Preference:</div>
                <div class="col-sm-8">
                    {{ $proforma->request_dsg_desc_3 ?? '-' }}
                </div>
            </div>

            <!-- Grade -->
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Grade:</div>
                <div class="col-sm-8">
                    {{ $proforma->request_group_code_3 ?? '-' }}
                </div>
            </div>

        </div>
    </div>
</div>