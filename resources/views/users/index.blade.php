@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $title }}</h5>
                @if ($users->isEmpty())
                    <div class="alert alert-info">No official users found.</div>
                @else
                    <table class="table table-bordered table-striped small">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Role</th>
                                @if ($title == 'Official Users')
                                    <th></th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->fullname }}</td>
                                    <td>{{ $user->mobile }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role->role_name ?? '-' }}</td>
                                    @if ($title == 'Official Users')
                                        <td>
                                            <a href="{{ route('user.editOfficialUser', $user->user_id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
