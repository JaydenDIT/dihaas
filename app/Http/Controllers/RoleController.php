<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
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
           // dd( $getUser);
            $role = $getUser->role_id;
            if ($role == 999){
            $roles =RoleModel::all(); //fetch all sites from DB
            return view('role.list', ['roles' => $roles]);
            //views/grade/list
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
           // dd( $getUser);
            $role = $getUser->role_id;
            if ($role == 999){
            return view('role.add');
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
           // dd($request->role_id,$request->role_name);
            $role = $getUser->role_id;
            if ($role == 999){
            $newPost = RoleModel::create([               
                'role_id' => $request->role_id,
                'role_name' => $request->role_name,          
                
    
            ]);
           // dd( $newPost);
            
            return redirect('roles');
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
            $role = RoleModel::find($id);
            return view('role.edit', [
                'role' => $role,
            ]);
        }
      }  else{
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
           // dd( $getUser);
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
            $role = RoleModel::find($id);
            $role->update([
                'role_id' => $request->role_id,
                'role_name' => $request->role_name,
               
              
                
            ]);
             
               return redirect('roles');
        }
    }else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoleModel$site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->route('id');
            $role = RoleModel::find($id);
            //dd($role);
            $role->delete();
    
            return redirect('roles/');
    }  
       
  


}






    