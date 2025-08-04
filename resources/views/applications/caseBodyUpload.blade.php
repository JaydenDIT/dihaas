@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Case Body Upload - Application ID: {{ $application->application_id }}</h4>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('casebody.store', $application->application_id) }}">
        @csrf
        <div class="mb-3">
            <label>Digitize Name</label>
            <input type="text" name="digitize_name" class="form-control" value="{{ $existing->digitize_name ?? '' }}" required>
        </div>

        <button type="submit" class="btn btn-success">Upload</button>
    </form>

    @if ($showForward)
    <hr>
    <form method="POST" action="{{ route('application.forward', $application->application_id) }}">
        @csrf
        <button type="submit" class="btn btn-primary mt-3">Forward</button>
    </form>
    @endif

    @if ($currentTaskMap && $currentTaskMap->allow_reject)
    <form method="POST" action="{{ route('application.reject', $application->application_id) }}">
        @csrf
        <button type="submit" class="btn btn-danger mt-2">Reject</button>
    </form>
    @endif

    @if ($currentTaskMap && $currentTaskMap->allow_drop)
    <form method="POST" action="{{ route('application.drop', $application->application_id) }}">
        @csrf
        <button type="submit" class="btn btn-warning mt-2">Drop</button>
    </form>
    @endif
</div>
@endsection