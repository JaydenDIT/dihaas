@extends('layouts.app')
@push('css')
    <style>
        .font-bold {
            font-weight: bold;
        }

        .counts-section {
            background-color: #e4f1ec;
            padding-top: 15px;
            padding-bottom: 15px;
            border-radius: 8px;
        }

        .card-heading {
            height: 70px;
            overflow-y: hidden
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <h5 class="mb-2">Dashboard</h5>

        <div class="row">
            @forelse($cards as $card)
                <div class="col-sm-4">

                    <div class="card mb-4 shadow-lg text-center">
                        <div class="card-body">
                            <div class="card-heading">
                                <h6 class="card-title mb-3">{{ $card['task'] }}</h6>
                            </div>

                            <div class="row counts-section">
                                <div class="col">
                                    <div class="stat-box text-warning">
                                        <h5 class="font-bold">{{ $card['pending'] }}</h5>
                                        <small>Pending</small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="stat-box text-success">
                                        <h5 class="font-bold">{{ $card['completed'] }}</h5>
                                        <small>Completed</small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="stat-box text-info">
                                        <h5 class="font-bold">{{ $card['total'] }}</h5>
                                        <small>Total</small>
                                    </div>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('tasks.performa.index', ['tasks_id' => $card['tasks_id']]) }}"
                                    class="btn btn-outline-primary btn-sm mt-3">
                                    View Performa
                                </a>
                            </div>
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
