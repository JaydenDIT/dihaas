<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UoNomenclatureModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UoNomenclatureController extends Controller
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
            $uonomenclatures =UoNomenclatureModel::all(); //fetch all sites from DB
            return view('uonomenclature.list', ['uonomenclatures' => $uonomenclatures]);
            //views/uonomenclature/list
        }
    }else{
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
            return view('uonomenclature.add');
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
            $newPost = UoNomenclatureModel::create([
                //'title' => $request->title,
                'uo_format' => $request->uo_format,
                'uo_file_no' => $request->uo_file_no,
                'year' => $request->year,
                'suffix' => $request->suffix
            ]);
            
            return redirect('uonomenclatures');
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
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
            $uonomenclature = UoNomenclatureModel::find($id);
            return view('uonomenclature.edit', [
                'uonomenclature' => $uonomenclature,
            ]);
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
    public function update(Request $request)
    {
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
            $uonomenclature =UoNomenclatureModel::find($id);
            $uonomenclature->update([
                'uo_format'=>$request->uo_format,
                'uo_file_no' => $request->uo_file_no,
                'year' => $request->year,
                'suffix' => $request->suffix
            ]);
             
               return redirect('uonomenclatures');
        }
     } else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UoNomenclatureModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //  if(Session::get('role_id')!=null){
            $id = $request->route('id');
            $uonomenclature = UoNomenclatureModel::find($id);
            $uonomenclature->delete();
    
            return redirect('uonomenclatures/');
        // }
        // else{
        //     return view('errors.404');
        // }
    }
}


