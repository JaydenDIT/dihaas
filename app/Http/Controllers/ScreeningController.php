<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScreeningController extends Controller
{
    function screeningcontroller(){
        return view("admin/dpDealing/form_SC_report");
    }
}
