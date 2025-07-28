<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DistrictModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DistrictController extends Controller
{


/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
                $districts =DistrictModel::all(); //fetch all sites from DB
            return view('district.list', ['districts' => $districts]);
            //views/grade/list
        }
     } else{
            return view('errors.404');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
                return view('district.add');
        }
    }else{
            return view('errors.404');
        }
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
                $newPost = DistrictModel::create([
                    'district_code_census' => $request->district_code_census,
                    'district_name_english' => $request->district_name_english,
                    'state_code_census' => $request->state_code_census,
                
    
            ]);
            
            return redirect('districts');
        }
     } else{
            return view('errors.404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ $site
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
		

        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
           // dd( $getUser);
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
           
            $district = DistrictModel::find($id);
            return view('district.edit', [
                'district' => $district,
            ]);
        }
    }else{
            return view('errors.404');
        }
    }
  /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		 if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
            $district = DistrictModel::find($id);
            $district->update([
                'district_code_census' => $request->district_code_census,
            'district_name_english' => $request->district_name_english,
            'state_code_census' => $request->state_code_census,
              
                
            ]);
             
            return redirect('districts');
        } 
    } else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DistrictModel$site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->route('id');
		$district = DistrictModel::find($id);
        $district->delete();

        return redirect('districts/');
    }


}






    