<!-- application -->
<div class="accordion-item p-0">
    <h2 class="accordion-header text-white">
        <button class="accordion-button bg-success bg-gradient text-white fw-bold p-2" type="button"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#viewCollapse-deceasedEntry"
            aria-expanded="true"
            aria-controls="viewCollapse-deceasedEntry">
            Information of the Deceased Government Servant
        </button>
    </h2>

    <div id="viewCollapse-deceasedEntry" class="accordion-collapse collapse show p-2  ps-3  bg-pink">
        <div class="accordion-body">
            <p class="text-danger">Expired on Duty: Government servants who died while performing official
                duties viz. election/census /survey /research / official tour/ field
                inspection etc. or who died in insurgency related violence while performing official
                duties.</p>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Date of Expiry:</div>
                <div class="col-sm-8">{{ $proforma->deceased_doe ?? '-' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Expired on duty:</div>
                <div class="col-sm-8">{{ $proforma->expire_on_duty ? 'Yes' : 'No' }}</div>
            </div>
            @if( $proforma->expire_on_duty )
            <div class="row mb-2">
                <div class="col-sm-4 fw-bold">Cause of Death:</div>
                <div class="col-sm-8">{{ $proforma->deceased_causeofdeath ?? '-' }}</div>
            </div>
            @endif

        </div>
    </div>
</div>