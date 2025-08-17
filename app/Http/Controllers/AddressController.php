<?php
namespace App\Http\Controllers;

use App\Library\Senitizer;
use App\Models\District;
use App\Models\SubDivision;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(Request $request)
    {
        if (isset($_REQUEST)) {
            $_REQUEST = Senitizer::senitize($_REQUEST, $request);
        }
    }

    public function getDistrictOption(Request $request)
    {

        $id   = $request->post("id");
        $data = District::getOptionByState($id)->get();
        if (is_null($data)) {
            return response()->json([
                'status' => 0,
                'msg'    => "No Data",
            ]);
        }
        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }

    public function getSubDivisionOption1(Request $request)
    {
        $district_id = $request->post("id");
        $data        = SubDivision::getOptionByDistrict($district_id)->get();
        if (is_null($data)) {
            return response()->json([
                'status' => 0,
                'msg'    => "No Data",
            ]);
        }
        return response()->json([
            'status' => 1,
            'data'   => $data,
        ]);
    }
}
