<h5 class="py-1 bg-success bg-gradient text-white fw-bold">Upload Documents</h5>

@foreach($documents as $doc)
<div class="row mb-3">
    <label class="col-form-label">
        <b class="{{ $doc->required ? 'required_label' : '' }}">{{ $doc->document_name }}:</b>
        <i>
            ({{ strtoupper($doc->document_type) }} only, max: {{ $doc->max_size_kb }}kB)
        </i>
    </label>
    <div class=" row">
        <div class="col-8">
            <button type="button"
                class="btn btn-sm btn-primary upload-doc-btn"
                data-upload="{{ urlencode(json_encode($doc)) }}">
                Upload
            </button>
        </div>
        <div class="col-4" id="document-preview-{{ $doc->document_list_id }}">
            @if(!empty($doc->uploaded_path))
            <a href="{{ asset('storage/'.$doc->uploaded_path) }}" target="_blank" class="btn btn-link">View</a>
            @else
            <span class="text-muted">No file uploaded</span>
            @endif
        </div>
    </div>
</div>

<hr class="hr-brown">
@endforeach