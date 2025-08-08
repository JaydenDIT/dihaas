<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Models\SubDivision;
use Exception;
use Illuminate\Http\Request;

class SubDivisionController extends Controller
{
    //
    public function loadByDistrict($id)
    {
        try {
            $result = SubDivision::where('district_id', $id)->get();
            if (empty($result)) {
                return   response()->json(['message' => 'No Subdivion Found'], 404);
            }
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
