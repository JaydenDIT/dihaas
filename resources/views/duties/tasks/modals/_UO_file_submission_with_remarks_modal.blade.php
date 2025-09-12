<div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3 border-0">
            <form method="POST" name="remark_form" id="remark_form" action="#">
                @csrf
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="confirm_title">
                        <i class="bi bi-file-earmark-text"></i> Process for Approval of Appointment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="action_type" value="">

                    <!-- File Upload -->
                    <div class="mb-4" id="file_upoad_layout">
                        <label class="form-label fw-semibold" id="fileLabel">
                            Upload Document <span class="text-danger">* (Office Order / Minutes of meeting)</span>
                        </label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="document_file" id="upload_document_file"
                                accept=".pdf" required />
                            <label class="input-group-text bg-primary text-white" for="upload_document_file">
                                <i class="bi bi-upload"></i> Browse
                            </label>
                        </div>
                        <div class="form-text text-muted">
                            Allowed file type: <strong>PDF only</strong>
                        </div>
                    </div>

                    <!-- PDF Preview -->
                    <div class="mb-4 d-none" id="pdf_preview_container">
                        <label class="form-label fw-semibold">Preview</label>
                        <div class="card shadow-sm border rounded-3">
                            <div class="card-body p-0" style="height: 600px;">
                                <iframe id="pdf_preview" class="w-100 h-100 rounded-bottom"
                                    style="border: none;"></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks Selection -->
                    <div class="mb-4">
                        <label for="select_remark" class="form-label fw-semibold">
                            Select Remarks <span class="text-danger">*</span>
                        </label>
                        <select id="select_remark" class="form-select" required>
                            <option value="" selected disabled>--Select--</option>
                            @isset($remarks)
                                @foreach ($remarks as $remark)
                                    <option value="{{ $remark->remark }}">{{ $remark->remark }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <!-- Remarks Textarea -->
                    <div class="mb-3 d-none" id="remarks_div">
                        <div class="form-floating">
                            <textarea class="form-control" id="remarks" name="remarks" style="height: 120px" maxlength="200" required></textarea>
                            <label for="remarks">Enter your remarks</label>
                        </div>
                        <small class="text-muted">Maximum 200 characters</small>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        // Elements
        const select_remark = document.getElementById('select_remark');
        const remarks_div = document.getElementById('remarks_div');
        const remarks = document.getElementById('remarks');
        const fileInput = document.getElementById('upload_document_file');
        const pdfPreviewContainer = document.getElementById('pdf_preview_container');
        const pdfPreview = document.getElementById('pdf_preview');

        // File preview
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type === "application/pdf") {
                const fileURL = URL.createObjectURL(file);
                pdfPreview.src = fileURL;
                pdfPreviewContainer.classList.remove('d-none');
            } else {
                pdfPreview.src = "";
                pdfPreviewContainer.classList.add('d-none');
            }
        });

        // Remarks dropdown handling
        select_remark.addEventListener('change', (event) => {
            if (event.target.value === 'Others') {
                remarks_div.classList.remove('d-none');
                remarks.setAttribute('required', 'required');
                remarks.value = '';
            } else {
                remarks_div.classList.add('d-none');
                remarks.removeAttribute('required');
                remarks.value = event.target.value;
            }
        });

        // Generic form submission handler
        function handleRemarkSubmission(callback) {
            const remark_form = document.forms['remark_form'];
            remark_form.addEventListener('submit', (event) => {
                event.preventDefault();
                callback();
            });
        }
    </script>
@endpush
