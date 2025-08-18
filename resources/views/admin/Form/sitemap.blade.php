@extends('layouts.guest')

@section('content')
    <div>
        <div class="container border border-border mt-4 ">

            <div class="text-center orange-border-bottom mt-4">
                <p style="color:rgb(5,86,24); font-size:20px;">Sitemap</p>
            </div>

            <div class="mt-4">
                <ul class="custom-list">
                    <li><a href="/">Home Page</a></li>
                    <li><a href="/citizen/register">Citizen Registration</a></li>
                    <li><a href="/">User Login</a></li>
                </ul>
            </div>

        </div>
    </div>
@endsection
