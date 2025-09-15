<div class="modal fade" id="esignModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="confirm_title">
                    <i class="bi bi-file-earmark-text"></i> Esign UO Generated Document
                </h5>
                <div class="d-flex justify-content-between align-items-center px-4 py-2">
                    <a href="#" class="btn btn-primary me-2" target="_blank" id="downloadSignedDocBtn"
                        style="display:none"><i class="bi bi-cloud-download"></i> Download e-Signed Doc</a>

                    <div id="pdf-controls" style="display:none;" class="mb-2">
                        <button id="prev-page-btn" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-chevron-left"></i> Previous
                        </button>
                        <span>Page <strong id="page-num"></strong> / <strong id="page-count"></strong></span>
                        <button id="next-page-btn" class="btn btn-outline-secondary btn-sm">
                            Next <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <input type="hidden" value="{{ env('ESIGN_HTTP_PORT', 8095) }}" id="http_port" />
                <input type="hidden" value="{{ env('ESIGN_HTTPS_PORT', 8443) }}" id="https_port" />
                <input type="hidden" name="action_type" id="action_type" value="">

                <div class="d-flex flex-column">
                    <div class="d-flex flex-grow-1 common-height body-section position-relative w-100">
                        <br />
                        <div class="col-sm-2 p-2"><!-- esign-sidebar-->
                            <div id="thumbnail-container" class="overflow-auto"></div>
                        </div>
                        <div class="flex-grow-1 document-preview col-sm-6 position-relative">
                            <div id="pdf-container">
                                <canvas id="pdf-canvas" class="shadow-lg d-none"></canvas>
                                <canvas id="overlay-canvas"></canvas>
                                <!-- Overlay message -->
                                <div id="canvas-overlay-message"
                                    class="position-absolute top-50 start-50 translate-middle text-muted text-center small"
                                    style="pointer-events: none;">
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-start bg-white col-sm-3">
                            <form name="esignPdfForm" id="esignPdfForm"
                                action="{{ env('ESIGN_URL') }}/api/esignBase64PDF" method="POST"
                                enctype="multipart/form-data">

                                <div class="mb-2 border-bottom">
                                    <h6><strong>e-Signing</strong> Details</h6>
                                </div>

                                <div class="mt-2">
                                    <input type="text" name="reason" id="reason"
                                        class="form-control form-control-sm" placeholder="Reason for signing">
                                </div>
                                <div class="mt-2 mb-2">
                                    <input type="text" name="location" id="location"
                                        class="form-control form-control-sm"
                                        placeholder="Place, e.g. Imphal, Guwahati, Kolkata etc.">
                                </div>
                                <div class="mb-1 d-none">
                                    <textarea name="pdf_base64" id="pdf_base64" class="form-control" rows="2" required></textarea>
                                </div>

                                <div class="mt-4 mb-2 border-bottom">
                                    <h6>Signature <span class="fw-bold">Area</span>, <span
                                            class="fw-bold">Position</span> &
                                        <span class="fw-bold">Dimention</span>
                                    </h6>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm field-btn"
                                    data-drawing-mode="false" id="draw-signature-btn"><i
                                        class="bi bi-pencil-square"></i>
                                    Draw on PDF</button>

                                <div class="row mt-2 mb-2">
                                    <label for="#" class="text-sm">The (x,y) co-ordinate where the signature
                                        will appear:</label>
                                    <input type="hidden" name="pageNo" id="pageNo" value="1">
                                    <div class="col">
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-sm">X</span>
                                            <input type="number" id="x" name="x"
                                                class="form-control form-control-sm" required="">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group has-validation">
                                            <span class="input-group-text text-sm">Y</span>
                                            <input type="number" id="y" name="y"
                                                class="form-control form-control-sm" required="">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <label for="#" class="text-sm">The height and width of the rectangular
                                        signature area:</label>
                                    <div class="col">
                                        <input type="number" id="rectangle_height" name="rectangle_height"
                                            placeholder="Height" value="60" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                    <div class="col">
                                        <input type="number" id="rectangle_width" name="rectangle_width"
                                            placeholder="Width" value="170" class="form-control form-control-sm"
                                            readonly>
                                    </div>
                                </div>
                                <div class="d-flex text-end">
                                    <div class="col">
                                        <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-dismiss="modal">
                                            <i class="bi bi-x-circle"></i> Cancel
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-success btn-sm">
                                            <span class="spinner-border spinner-border-sm d-none"
                                                id="spinner"></span>
                                            <i class="bi bi-shield-check"></i> Confirm e-Sign
                                        </button>
                                    </div>
                                </div>

                                <input type="hidden" name="pdf_worker_js" id="pdf_worker_js"
                                    value="{{ asset('esign/pdf/pdf.worker.js') }}">
                            </form>

                            <form method="POST" name="submit_esigned_doc_form" id="submit_esigned_doc_form"
                                action="#" class="d-none">
                                @csrf
                                <h5>Give remarks and submit the signed document.</h5>
                                <div id="eSignResult" class="d-none">
                                    <textarea name="esigned_pdf_base64" id="esigned_pdf_base64" class="form-control" rows="2" readonly></textarea>
                                </div>
                                <!-- Remarks Selection -->
                                <div class="mb-4">
                                    <label for="remark_selection" class="form-label fw-semibold">
                                        Select Remarks <span class="text-danger">*</span>
                                    </label>
                                    <select id="remark_selection" class="form-select" required>
                                        <option value="" selected disabled>--Select--</option>
                                        @isset($remarks)
                                            @foreach ($remarks as $remark)
                                                <option value="{{ $remark->remark }}">{{ $remark->remark }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>

                                <!-- Remarks Textarea -->
                                <div class="mb-3 d-none" id="remarks_div_layout">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="remarks" name="remarks" style="height: 120px" maxlength="200" required></textarea>
                                        <label for="remarks">Enter your remarks</label>
                                    </div>
                                    <small class="text-muted">Maximum 200 characters</small>
                                </div>
                                <div class="d-flex text-end">
                                    <div class="col">
                                        <button type="button" class="btn btn-secondary btn-sm" id="resign-btn">
                                            <i class="bi bi-x-circle"></i> Re-Sign
                                        </button>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-check-circle"></i> Submit Signed Doc
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <div id="serverResponse" class="mt-1 text-sm"></div>
            </div>

        </div>
    </div>
</div>

@push('css')
    <link rel="stylesheet" href="{{ asset('esign/esign.css') }}">
    <link rel="stylesheet" href="{{ asset('prism/prism.css') }}">
@endpush

@push('js')
    <script src="{{ asset('esign/pdf/pdf.js') }}"></script>
    <script src="{{ asset('esign/esign.js') }}"></script>
    <script src="{{ asset('prism/prism.js') }}"></script>
    <script src="{{ asset('prism/prism-javascript.js') }}"></script>
    <script src="{{ asset('prism/prism-markup.js') }}"></script>
    <script>
        // Elements
        const remark_selection = document.getElementById('remark_selection');
        const remarks_div_layout = document.getElementById('remarks_div_layout');
        const remarks = document.getElementById('remarks');
        const resignBtn = document.getElementById('resign-btn');

        // Remarks dropdown handling
        remark_selection.addEventListener('change', (event) => {
            if (event.target.value === 'Others') {
                remarks_div_layout.classList.remove('d-none');
                remarks.setAttribute('required', 'required');
                remarks.value = '';
            } else {
                remarks_div_layout.classList.add('d-none');
                remarks.removeAttribute('required');
                remarks.value = event.target.value;
            }
        });

        // On click event for re-sign button
        resignBtn.addEventListener('click', (event) => {
            event.preventDefault();
            document.forms['submit_esigned_doc_form'].classList.add("d-none");
            document.forms['esignPdfForm'].classList.remove("d-none");
        });

        // Generic form submission handler
        function handleEsignSubmission(callback) {
            const submit_esigned_doc_form = document.forms['submit_esigned_doc_form'];
            submit_esigned_doc_form.addEventListener('submit', (event) => {
                event.preventDefault();
                let formData = new FormData(submit_esigned_doc_form);
                callback(formData);
            });
        }
    </script>
@endpush
