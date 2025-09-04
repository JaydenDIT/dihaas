@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h4>Nodal Dashboard</h4>
    </div>
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-8">
                @include('dashboard._proforma-counts')
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">
                <!-- Notifications -->
                @include('dashboard._notifications', compact('notifications'))
                <!-- End Notifications -->
            </div><!-- End Right side columns -->
        </div>
    </section>
    @include('dashboard._proforma-list-section')
@endsection
