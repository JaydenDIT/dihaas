@extends('layouts.app')

@section('content')
    <h2>Qualification Details</h2>

    <p><strong>ID:</strong> {{ $qualification->qualification_id }}</p>
    <p><strong>Name:</strong> {{ $qualification->qualification_name }}</p>

    <a href="{{ route('qualifications.index') }}">Back to list</a>
@endsection
