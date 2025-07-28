<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DistrictModel;
use App\Library\Senitizer;
use App\Models\District;
use App\Models\StateModel;
use App\Models\StateModelRegister;
use App\Models\SubDivision;

class AddressController extends Controller
{
    public function __construct(Request $request)
    {
       if( isset($_REQUEST) ){
            $_REQUEST = Senitizer::senitize($_REQUEST, $request);
       }
    }
    
    public function getStateOption(Request $request){ 
        
        $id = $request->post("id");        
        $data = StateModelRegister::getOptionByCountry($id)->get();
        if(is_null($data)){
            return response()->json([
                'status' => 0,
                'msg' => "No Data"
            ]);
        }
        return response()->json([
            'status' => 1,
            'data' => $data
        ]);
    }

    public function getDistrictOption(Request $request){ 
        
        $id = $request->post("id");        
        $data = DistrictModel::getOptionByState($id)->get();
        if(is_null($data)){
            return response()->json([
                'status' => 0,
                'msg' => "No Data"
            ]);
        }
        return response()->json([
            'status' => 1,
            'data' => $data
        ]);
    }
   
    public function getDistrictOption1(Request $request){ 
        
        $id = $request->post("id");        
        $data = District::getOptionByState1($id)->get();
        if(is_null($data)){
            return response()->json([
                'status' => 0,
                'msg' => "No Data"
            ]);
        }
        return response()->json([
            'status' => 1,
            'data' => $data
        ]);
    }

    public function getSubDivisionOption1(Request $request){ 
        
        $id = $request->post("id");        
        $data = SubDivision::getOptionByDistrict1($id)->get();
        if(is_null($data)){
            return response()->json([
                'status' => 0,
                'msg' => "No Data"
            ]);
        }
        return response()->json([
            'status' => 1,
            'data' => $data
        ]);
    }
}
