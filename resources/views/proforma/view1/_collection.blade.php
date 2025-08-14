<div class="row  border bg-pink   rounded mt-3">
    @include('proforma.view1._1deceased_cmis_details')
    @include('proforma.view1._2deceased_entry_details')
    @include('proforma.view1._3applicant_details')

    <div class="hstack gap-3 my-3 p-3">
        <div class="ms-auto">
            <button class="btn btn-md btn-success nextBtn" type="button" data-step="1">Next</button>
        </div>
    </div>
</div>