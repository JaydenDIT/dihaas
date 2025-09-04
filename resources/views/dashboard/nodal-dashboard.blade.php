@extends('layouts.app')
@section('content')
    <div class="pagetitle">
        <h4>Nodal Dashboard</h4>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-sm-12">
                @include('dashboard._proforma-counts')
            </div>
        </div>
    </section>
    @include('dashboard._proforma-list-section')
@endsection
