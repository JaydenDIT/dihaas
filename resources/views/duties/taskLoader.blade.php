@php
$tasks = getPrevNextTasks($empDetails->ein);

$temp_array = [];
$ein = $empDetails->ein;
$appl_no = $empDetails->appl_number;
$temp_array['ein'] = $ein;
$temp_array['appl_no'] = $appl_no;


@endphp



<div action="#" class="text-center w-100">
    <!-- for revert button -->
    @include('duties.tasks._revert')


    @switch($tasks['current']['tasks_duty'])

    @case ('verify_and_forward')
    @include('duties.tasks._forward')
    @break

    @endswitch



    <!-- for reject button -->
    @include('duties.tasks._reject')

</div>



<div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="revertForm" action="{{ route('revertPersonalDetailsFrom', Crypt::encryptString($empDetails->ein)) }}" method="Post">
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












<script>
    $(document).on('click', '.modal-revert', function(e) {

        Swal.fire({
            title: 'Revert?',
            text: "Are You Sure that the Applicant File is NOT OK?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6', // Customize colors
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, do it!'
        }).then((result) => {
            if (result.isConfirmed) {
                setRevertData(<?= json_encode($temp_array) ?>);
            }
        });


    });

    function setRevertData(temp_array) {
        $("#remarkModal").modal('show');
        let form = document.forms['revertForm'];
        // form.appl_number.value = temp_array['appl_number'];
        form.ein.value = temp_array['ein'];
        console.log(temp_array['ein'])

    }

    $(document).on('click', '.modal-reject', function(e) {
        $("#rejectModalForm").modal('show');
    });
    $(document).on('click', '.modal-forward', function(e) {
        $("#forwardModalForm").modal('show');
    });

    $(document).on('click', '.modal-close', function(e) {
        const focusedElement = document.activeElement;
        if (this.contains(focusedElement)) {
            focusedElement.blur();
        }
        $(".modal").modal('hide');
    });
</script>