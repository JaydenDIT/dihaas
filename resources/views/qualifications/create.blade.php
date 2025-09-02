@extends('layouts.app')

@section('content')
    <h2>Add Qualification</h2>

    <form action="{{ route('qualifications.store') }}" method="POST">
        @csrf
        <label>Name:</label>
        <input type="text" name="qualification_name" required>
        <button type="submit">Save</button>
    </form>
@endsection
