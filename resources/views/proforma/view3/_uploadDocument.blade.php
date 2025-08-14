<div class="row  border bg-pink   rounded mt-3">
    <h5 class="bg-success bg-gradient text-white fw-bold  p-2 ps-3">Uploaded Documents</h5>
    @foreach($proforma->uploadedDocuments as $doc)

    <div class="row mb-2">
        <div class="col-sm-8 fw-bold">{{ $doc->document->document_name }}:</div>
        <div class="col-sm-4">
            <a href="{{ route('duties.upload.document.load', $doc->uploaded_document_id) }}"
                target="_blank"
                class="btn btn-link">
                View
            </a>
        </div>
    </div>


    <hr class="hr-brown">
    @endforeach

    <div class="hstack gap-3 my-3 p-3">
        <div class="ms-auto">
            <button class="btn btn-md btn-success prevBtn" type="button" data-step="3">Prev</button>
        </div>
    </div>
</div>