@extends('layouts.app')
@section('content')

<div class="container-fluid pt-3 px-5">
    <style>
        .roles_class {
            float: left;
            margin: 10px;
            width: 200px;
            height: 20px;
            font-size: 10pt;
        }
    </style>
    <h3><b>@if(isset($task)) Edit Duty @else Create Duty @endif</b>
        <span class="alert alert-light" style="font-size:10pt;">
            A duty is a specific activity that must be carried out in a process. A sequence of duties makes a process.
        </span>
    </h3>
    <hr>
    <div>
        <form name="create_task_form" class="needs-validation" method="POST" id="create_task_form">

            @csrf
            @if(isset($task))
            {{-- use POST with custom update route --}}
            <input type="hidden" name="tasks_id" value="{{ $task->tasks_id }}">
            @endif

            <div class="row mb-3 mt-3">
                <div class="col-sm-12">
                    <label for="tasks_name"><b>Duty Name:</b></label>
                    <input type="text" name="tasks_name" id="tasks_name" value="{{ $task->tasks_name ?? '' }}" class="form-control" required />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <label for="tasks_description"><b>Duty Description:</b></label>
                    <textarea name="tasks_description" id="tasks_description" class="form-control" required>{{ $task->tasks_description ?? '' }}</textarea>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <label><b>Activity of the Duty:</b></label>
                    <select name="tasks_duty" class="form-control" required>
                        <option value="" selected disabled>--Select--</option>
                        @foreach($tasks_file as $key => $label)
                        <option value="{{ $key }}" {{ (isset($task) && $task->tasks_duty === $key) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-12">
                    <label><b>Select Roles for this Duty:</b></label>
                </div>
                @foreach($roles as $role)
                <div class="col-sm-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role_{{ $role->role_id }}"
                            name="roles[]" value="{{ $role->role_id }}"
                            {{ isset($task_roles) && in_array($role->role_id, $task_roles) ? 'checked' : '' }}>
                        <label class="form-check-label" for="role_{{ $role->role_id }}">
                            <strong>{{ $role->role_name }}</strong>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row">
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">@if(isset($task)) Update @else Create @endif Duty</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('js')
<script>
    $(document).on("submit", "#create_task_form", async function(e) {
        e.preventDefault();
        try {
            await validateForm(this);
            const form = this;
            let formData = new FormData(form);
            let id = formData.get("tasks_id");
            const url = id ?
                "{{ route('admin.task.update', ':id') }}".replace(':id', id) :
                "{{ route('admin.task.store') }}";
            let res = await ajax_send_multipart({
                url,
                method: 'POST',
                param: formData,
            });
            success_message(res.message || "Saved successfully");
            setTimeout(() => window.location.reload(), 1000);
        } catch (err) {
            error_message(err.message || "Something went wrong");
        }
    });
</script>
@endpush