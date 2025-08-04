@extends('layouts.app_process')

@section('content')
<div class="container">
    <h2 class="mb-4">My Task Dashboard</h2>

    <div class="row">
        @forelse($cards as $card)
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $card['task'] }}</h5>
                    <p class="card-text">
                        <strong>Pending:</strong> {{ $card['pending'] }}<br>
                        <strong>Completed:</strong> {{ $card['completed'] }}<br>
                        <strong>Total:</strong> {{ $card['total'] }}
                    </p>
                    <a href="{{ route('tasks.performa.index', ['task_id' => $card['task_id']
                    ]) }}" class="btn btn-primary btn-sm">View Performa</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <p class="alert alert-info">No tasks assigned to your role.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection