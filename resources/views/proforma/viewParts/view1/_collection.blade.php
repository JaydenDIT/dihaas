@push('css')
<style>
    /* Reset Bootstrap's default chevron */
    .accordion-button::after {
        background-image: none !important;
        background: none !important;
        content: '' !important;
    }

    /* Custom +/- toggle */
    .accordion-button:not(.collapsed)::after {
        content: '-' !important;
        font-weight: bold;
        transform: none !important;
    }

    .accordion-button.collapsed::after {
        content: '+' !important;
        font-weight: bold;
    }
</style>
@endpush


<div class="row  bg-pink  rounded">
    @include('proforma.viewParts.view1._1deceased_cmis_details')
    @include('proforma.viewParts.view1._2deceased_entry_details')
    @include('proforma.viewParts.view1._3applicant_details')
    @include('proforma.viewParts.view1._4applicant_address_current')
    @include('proforma.viewParts.view1._5applicant_address_permanent')
    @include('proforma.viewParts.view1._6prefer_post_parent_department')
    @include('proforma.viewParts.view1._7prefer_post_other_department')

    <div class="hstack gap-3 my-3 p-3">
        <div class="ms-auto">
            <button class="btn btn-md btn-success nextBtn" type="button" data-step="1">Next</button>
        </div>
    </div>
</div>