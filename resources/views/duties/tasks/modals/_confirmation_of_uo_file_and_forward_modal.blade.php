<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form method="POST" name="confirmation_form" id="confirmation_form" action="#">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="confirm_title">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="proforma_id" id="confirm_proforma_id" value="">
                    <div class="mb-2">
                        <label for="dp_nodal">Select DP Nodal Officer for esigning the UO file:</label>
                        <select name="dp_nodal_user_id" id="dp_nodal" class="form-select" required>
                            <option value="">Select a DP Nodal</option>
                            @foreach ($dpNodalUsers as $user)
                                <option value="{{ $user->user_id }}">{{ $user->fullname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="confirm_remarks" name="remarks" rows="4" maxlength="600" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>