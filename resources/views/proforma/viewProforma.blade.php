@extends('layouts.app')


@section('content')
<div class="container-fluid">


    <div class="row">
        <div class="col-md-5">
            @include('proforma.viewParts._status')
        </div>

        <div class="col-md-7">
            @include('proforma.viewParts._viewDetails')
        </div>


    </div>
</div>
@endsection