<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuidelinesController extends Controller
{
    function guidelines(){
        return view("admin/dpDealing/form_Guidelines");
    }
}
