<div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="revertForm" action="{{ route('') }}" method="Post">
            @csrf
            <!-- @method('GET') -->


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="remarkModalTitle">Remark</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="name"><b>Select a reason: </b></label>

                    <input type="hidden" class="form-control" id="ein" name="ein" value="{{ $empDetails['ein'] == null ? null : $empDetails['ein'] }}">

                    <select class="form-select" aria-label="Default select example" id="remark" name="remark">
                        <option selected>Select</option>
                        @foreach($Remarks as $option)
                        <option value="{{ $option['probable_remarks'] }}" required> {{$option['probable_remarks']}}</option>
                        @endforeach


                    </select><br>

                    <label for="remark_details"><b>Remark (Less than 250 words)</b></label>
                    <input type="text" placeholder="Description" class="form-control" id="remark_details" name="remark_details" value="{{ $empDetails['remark_details'] == null ? null : $empDetails['remark_details'] }}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="BtnSvData" type="submit" class="btn btn-success">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>