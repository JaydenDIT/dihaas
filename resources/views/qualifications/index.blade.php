@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between">
                <h2>Qualifications</h2>
                <div><a href="{{ route('qualifications.create') }}" class="btn btn-primary">Add New</a></div>
            </div>
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($qualifications as $q)
                        <tr>
                            <td>{{ $q->qualification_id }}</td>
                            <td>{{ $q->qualification_name }}</td>
                            <td>
                                <a href="{{ route('qualifications.show', $q->qualification_id) }}"
                                    class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('qualifications.edit', $q->qualification_id) }}"
                                    class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('qualifications.destroy', $q->qualification_id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-warning btn-sm"
                                        onclick="return confirm('Delete this qualification?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
