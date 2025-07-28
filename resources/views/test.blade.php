@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Completed!</h1><br>
    <a href="{{ url('/viewStartEmp') }}" id="view_emp" class="btn btn-success btn-sm" tabindex="-1" role="button" aria-disabled="true" style="display:show">Started Employee List</a>
</div>
@endsection