<?php

namespace App\Http\Controllers\Misc;

use App\Http\Controllers\Controller;
use App\Models\District;
use Exception;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    //
    public function loadByState($id)
    {
        try {
            $result = District::where('state_id', $id)->get();
            if (empty($result)) {
                return response()->json(['message' => 'No District Found'], 404);
            }
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
