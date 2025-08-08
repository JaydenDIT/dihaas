<?php

namespace App\Http\Controllers\Duties;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProformaController extends Controller
{
    //
    public function create()
    {
        $action = "create";
        return view('proforma.createProforma', compact('action'));
    }
}
