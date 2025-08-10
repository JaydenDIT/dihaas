<h5 class="py-1 bg-success bg-gradient text-white fw-bold">Upload Documents</h5>

<div class="row mb-3">
    <label class="col-form-label"><b>Bank Passbook/Cancel Cheque: </b>
        <i>(Optional) (PDF only, max: 500kB)</i>
    </label>
    <div class="row">
        <div class="col-8">
            <!-- Pass document info in data-upload -->
            <button class="btn btn-primary btn-upload" type="button"
                data-upload='{"document_id":1,"document_name":"Bank Passbook/Cancel Cheque"}'>
                Upload Now
            </button>
        </div>
        <div class="col-4">
            Preview here
        </div>
    </div>
</div>

<hr class="hr-brown">

<div class="row mb-3">
    <label class="col-form-label"><b>Class X Marksheet: </b>
        <i>(Optional) (PDF only, max: 500kB)</i>
    </label>
    <div class="row">
        <div class="col-8">
            <button class="btn btn-primary btn-upload" type="button"
                data-upload='{"document_id":2,"document_name":"Class X Marksheet"}'>
                Upload Now
            </button>
        </div>
        <div class="col-4">
            Preview here
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="uploadModalLabel">Upload Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- Document Name -->
                    <div class="mb-3">
                        <label class="form-label">Document</label>
                        <input type="text" class="form-control" id="docName" readonly>
                        <input type="hidden" id="docId" name="document_id">
                    </div>

                    <!-- File Number -->
                    <div class="mb-3">
                        <label class="form-label">File Number</label>
                        <input type="text" class="form-control" name="file_number" required>
                    </div>

                    <!-- Choose File -->
                    <div class="mb-3">
                        <label class="form-label">Choose PDF</label>
                        <input type="file" class="form-control" name="document_file" accept="application/pdf" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('js')
<script>
    $(document).ready(function() {
        // Open modal when upload button clicked
        $('.btn-upload').click(function() {
            let docData = $(this).data('upload'); // Read from data-upload attribute
            // Fill modal fields
            $('#docName').val(docData.document_name);
            $('#docId').val(docData.document_id);

            // Show modal
            $('#uploadModal').modal('show');
        });

        // Handle upload form submit
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
        });
    });
</script>
@endpush