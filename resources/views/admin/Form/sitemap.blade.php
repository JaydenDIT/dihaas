@extends('layouts.app')

@section('content')

    <div>
    

        <div class="container border border-border mt-4 ">

            <div class="text-center orange-border-bottom mt-2">
                <p style="color:rgb(5,86,24); font-size:20px;">Sitemap</p>

            </div>

            <div class="mt-4">
                <ul class="custom-list">
                    <li><a href="/">Home Page</a></li>
                    <li><a href="/citizen/register" >Citizen Registration</a></li>
                    <li><a href="/" >Citizen Login</a></li>
                    <li><a href="/department_login">Department Login</a></li>
                </ul>
            </div>

        </div>
    </div>

    @endsection
