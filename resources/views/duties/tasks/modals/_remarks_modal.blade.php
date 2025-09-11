<div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" name="remark_form" id="remark_form" action="#">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="confirm_title">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action_type" id="action_type" value="">
                    <div class="mb-3">
                        <label for="select_remark">Select Remarks:</label>
                        <select id="select_remark" class="form-select" required>
                            <option value="" selected disabled>--Select--</option>
                            @isset($remarks)
                                @foreach ($remarks as $remark)
                                    <option value="{{ $remark->remark }}">{{ $remark->remark }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="remarks_div">
                        <textarea class="form-control" id="remarks" name="remarks" rows="4" maxlength="600" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
    <script>
        // Set on change  effect of the select_remark dropdown selection
        const select_remark = document.getElementById('select_remark');
        const remarks_div = document.getElementById('remarks_div');
        const remarks = document.getElementById('remarks');

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

        /* 
        Handling the form submission. Below is a generic function to handle the submission
        There will be a callable/callback function passed as parameter to handle the actual action.

        */

        function handleRemarkSubmission(callback) {
            const remark_form = document.forms['remark_form'];
            remark_form.addEventListener('submit', (event) => {
                event.preventDefault();
                callback();
            })
        }
    </script>
@endpush
