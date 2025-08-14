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

@push('js')
<script>
    $(function() {
        $('.accordion-collapse').on('show.bs.collapse', function() {
            $(this).closest('.accordion-item').find('.toggle-icon').text('−');
        }).on('hide.bs.collapse', function() {
            $(this).closest('.accordion-item').find('.toggle-icon').text('+');
        });
    });
</script>
@endpush