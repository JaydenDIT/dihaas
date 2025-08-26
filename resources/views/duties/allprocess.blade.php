@extends('layouts.app')

@section('content')
    <div class="container">
        <h5 class="mb-2">Dashboard</h5>

        <div class="row">
            @forelse($cards as $card)
                <div class="col-sm-4">

                    <div class="card mb-4 shadow-sm text-center">
                        <div class="card-body">
                            <div style="height: 70px;overflow-y:hidden">
                                <h6 class="card-title mb-3">{{ $card['task'] }}</h6>
                            </div>

                            <div class="row"
                                style="background-color: #e2f2fc;padding-top:15px;padding-bottom:15px;border-radius:8px;">
                                <div class="col">
                                    <div class="stat-box text-warning">
                                        <h5>{{ $card['pending'] }}</h5>
                                        <small>Pending</small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="stat-box text-success">
                                        <h5>{{ $card['completed'] }}</h5>
                                        <small>Completed</small>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="stat-box text-info">
                                        <h5>{{ $card['total'] }}</h5>
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
