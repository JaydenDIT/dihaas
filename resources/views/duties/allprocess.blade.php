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
        <h5 class="mb-2">{{ $process_name ? 'Jobs for ' . $process_name : 'Proforma jobs' }}</h5>

        <div class="row">
            @forelse($cards as $key => $card)
                <div class="col-sm-4">

                    <div class="card mb-4 shadow-lg">
                        <div class="card-body">
                            <div class="card-heading text-start d-flex justify-content-between">
                                <h6 class="card-title">{{ $key + 1 }}. {{ $card['task'] }}</h6>
                                <div class="card-title">
                                    <a class="text-info small" data-bs-toggle="collapse" href="#desc{{ $key }}"
                                        role="button" aria-expanded="false">
                                        <i class="bi bi-info-circle-fill"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="collapse mt-2 mb-3" id="desc{{ $key }}">
                                <p class="text-muted small">{{ $card['task_description'] }}</p>
                            </div>
                            <div class="row counts-section text-center">
                                <div class="col">
                                    <div class="stat-box text-primary">
                                        <h5 class="font-bold">{{ $card['pending'] }}</h5>
                                        <small>Pending</small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="stat-box text-secondary">
                                        <h5 class="font-bold">{{ $card['forwarded'] }}</h5>
                                        <small>Forwarded</small>
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
                                    View Proforma
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
