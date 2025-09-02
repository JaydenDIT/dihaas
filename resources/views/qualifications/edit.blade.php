@extends('layouts.app')

@section('content')
    <h2>Edit Qualification</h2>

    <form action="{{ route('qualifications.update', $qualification->qualification_id) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" name="qualification_name" value="{{ $qualification->qualification_name }}" required>
        <button type="submit">Update</button>
    </form>
@endsection
