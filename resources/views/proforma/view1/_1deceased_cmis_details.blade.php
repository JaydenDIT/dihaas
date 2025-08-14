<div class="accordion-item p-0">
    <h2 class="accordion-header d-flex justify-content-between align-items-center bg-success bg-gradient text-white fw-bold p-2 ps-3">
        <button class="accordion-button" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-deceasedDetail"
            aria-expanded="true"
            aria-controls="viewCollapse-deceasedDetail">
            Deceased Details
        </button>
        <span class="toggle-icon fw-bold" style="font-size: 1.2rem;">−</span>
    </h2>
    <div id="viewCollapse-deceasedDetail" class="accordion-collapse collapse show p-2 ps-3">
        <div class="accordion-body">
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">EIN of the Deceased Employee:</div>
                <div class="col-sm-8">{{ $proforma->deceased_ein ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Name of the Deceased Employee:</div>
                <div class="col-sm-8">{{ $proforma->deceased_emp_name ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Post Held:</div>
                <div class="col-sm-8">{{ $proforma->deceased_emp_desig ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Administrative Department:</div>
                <div class="col-sm-8">{{ $proforma->deceased_adm_dept_desc ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Department:</div>
                <div class="col-sm-8">{{ $proforma->deceased_field_dept_desc ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Date of Appointment:</div>
                <div class="col-sm-8">{{ $proforma->deceased_doa ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Date of Birth:</div>
                <div class="col-sm-8">{{ $proforma->deceased_dob ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-sm-4 fw-bold">Grade/Group:</div>
                <div class="col-sm-8">{{ $proforma->deceased_emp_group ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>