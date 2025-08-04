@extends('layouts.app_process')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <form id="process-form" method="POST">
                @csrf
                <h4>Create New Process</h4>

                <div class="mb-3">
                    <label for="process_name" class="form-label">Process Name</label>
                    <input type="text" class="form-control" id="process_name" name="process_name" required>
                </div>

                <div class="mb-3">
                    <label for="process_description" class="form-label">Description</label>
                    <textarea class="form-control" id="process_description" name="process_description" rows="3"></textarea>
                </div>

                <hr>

                <h5>Conditions</h5>
                <div id="conditions-container"></div>
                <button type="button" class="btn btn-outline-primary my-2" id="add-condition">+ Add Condition</button>

                <input type="hidden" name="process_criteria" id="process_criteria">

                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        <div class="col-md-6">
            <h5>Similar Processes</h5>
            <ul id="similar-processes-list" class="list-group"></ul>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    const criteriaJson = @json($criteria);
    const existingProcesses = @json($existingProcesses);
    const saveUrl = "{{route('admin.process.store')}}";
</script>
<script src="{{asset('js/process-create.js')}}"></script>
@endpush