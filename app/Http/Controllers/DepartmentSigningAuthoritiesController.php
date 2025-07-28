<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sign2Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DepartmentSigningAuthoritiesController extends Controller
{
    public function index()
    {
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
                $deptauthorities =Sign2Model::all(); //fetch all sites from DB
                return view('deptAuthority.list', ['deptauthorities' => $deptauthorities]);
            
            }
        }
        else{
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
                return view('deptAuthority.add');
            }
        }
        else{
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
                $newPost = Sign2Model::create([
                    //'title' => $request->title,
                
                    'name' => $request->dept_authorities,
                    
                    
        
                ]);
                
                return redirect('deptauthorities');
            }
        }
        else{
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
                $deptauthority = Sign2Model::find($id);
                return view('deptAuthority.edit', [
                    'deptauthority' => $deptauthority,
                ]);
            }
        }
        else{
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
                $deptauthority = Sign2Model::find($id);
                $deptauthority->update([
                
                    'name' => $request->dept_authorities,
                
                
                    
                ]);
                
                return redirect('deptauthorities');
            }
        }
		else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sign2Model$site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->route('id');
            $deptauthority = Sign2Model::find($id);
            $deptauthority->delete();
    
            return redirect('deptauthorities/');
    }  
       
  


}
