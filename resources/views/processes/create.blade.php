@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <form id="process-form" method="POST">
                @csrf
                <h4>{{ isset($action) && $action === 'update' ? 'Update Process' : 'Create New Process' }}</h4>

                <input type="hidden" name="action" value="{{$action}}" />
                <div class="mb-3">
                    <label for="process_name" class="form-label">Process Name</label>
                    <input type="text" class="form-control" id="process_name" name="process_name"
                        value="{{ old('process_name', $process->process_name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="process_description" class="form-label">Description</label>
                    <textarea class="form-control" id="process_description" name="process_description" rows="3">{{ old('process_description', $process->process_description ?? '') }}</textarea>
                </div>
                <hr>

                <h5>New Condition</h5>
                <div class="row g-2 mb-3">
                    <div class="col-md-4">
                        <select class="form-select" id="field-select">
                            <option value="">Select Field</option>
                            @foreach($criteria as $key => $item)
                            <option value="{{ $key }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="operation-select" disabled>
                            <option value="">Select Operator</option>
                        </select>
                    </div>
                    <div class="col-md-4" id="value-wrapper">
                        <input type="text" class="form-control" id="value-input" placeholder="Value" />
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success" id="add-condition">+</button>
                    </div>
                </div>

                <h5>Conditions Table</h5>
                <table class="table table-bordered" id="conditions-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Operator</th>
                            <th>Value</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="conditions-container"></tbody>
                </table>

                <input type="hidden" name="process_criteria" id="process_criteria" value="{{ old('process_criteria', $process->process_criteria ?? '') }}">

                <button type="submit" class="btn btn-primary mt-3">
                    {{ isset($action) && $action === 'update' ? 'Update' : 'Submit' }}
                </button>
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
    const action = "{{ $action }}";
    const criteriaJson = @json($criteria);
    const existingProcesses = @json($existingProcesses);
    const saveUrl = "{{ $action === 'update' ? route('admin.process.update', ['id'=>$process->process_id]) : route('admin.process.store') }}";
    const process = @json($process ?? []);
</script>
<script src="{{ asset('js/process-create.js') }}"></script>
@endpush