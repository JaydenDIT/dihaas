<div class="row bg-pink">
    <h5 class="py-1 bg-success bg-gradient text-white fw-bold">Upload Documents</h5>
    @foreach($documents as $doc)
    <div class="row mb-3">
        <label class="col-form-label">
            <b class="{{ $doc->required ? 'required_label' : '' }}">{{ $doc->document_name }}:</b>
            <i>({{ strtoupper($doc->document_type) }} only, max: {{ $doc->max_size_kb }}kB)</i>
        </label>
        <div class="row">
            <div class="col-8">
                <button type="button"
                    class="btn btn-sm btn-primary upload-doc-btn"
                    data-upload="{{ urlencode(json_encode($doc)) }}">
                    Upload
                </button>
            </div>
            <div class="col-4" id="document-preview-{{ $doc->document_list_id }}">
                @if($doc->uploaded_file)
                <a href="{{ route('duties.upload.document.load', $doc->uploaded_file->uploaded_document_id) }}"
                    target="_blank"
                    class="btn btn-link">
                    View
                </a>
                @else
                <span class="text-muted">No file uploaded</span>
                @endif
            </div>
        </div>
    </div>
    <hr class="hr-brown">
    @endforeach

    <!-- I agree -->
    <div class="row mb-4 ps-5 mt-5">
        <div class="form-check  ps-5">
            <input class="form-check-input" type="checkbox" name="flexCheckDefault" id="flexCheckDefault"
                required>
            <label class="form-check-label" for="flexCheckDefault">
                I agree all the above information are correct. I also undertake that the remaining mandatory
                document will be uploaded.
            </label>
        </div>

    </div>

    <div class="hstack gap-3 my-3 p-3">
        <div class="ms-auto">
            <button class="btn btn-md btn-success" id="saveDocumentBtn" type="button">Save</button>
            <button class="btn btn-md btn-success" id="submitFormBtn" type="button">Submit Form</button>
        </div>
    </div>
</div>



<div class="modal fade" id="docUploadModal" tabindex="-1" aria-labelledby="docUploadModalLevel" data-bs-backdrop="static"
    data-bs-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="docUploadModalLevel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form should wrap all content that's part of it -->
            <form class="needs-validation" id="docUploadForm" name="docUploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="proforma_id" value="{{ $proforma->proforma_id }}">
                <input type="hidden" id="upload_document_list_id" name="document_list_id">

                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Document</label>
                            <input type="text" class="form-control" id="upload_document_name" name="document_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" id="fileLabel">Choose File</label>
                            <input type="file" class="form-control" name="document_file" id="upload_document_file" required>
                            <small id="sizeHelp" class="text-muted"></small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="docUploadForm" class="btn btn-success">Upload</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>