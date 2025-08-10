@extends('layouts.app')

@section('content')
<div class="container mt-4">
    Login as: {{ Auth::user()->fullname }}
</div>
@endsection