<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sign1Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DPSigningAuthoritiesController extends Controller
{
    public function index()
    {
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
                $dpauthorities =Sign1Model::all(); //fetch all sites from DB
                return view('dpAuthority.list', ['dpauthorities' => $dpauthorities]);
            
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
                return view('dpAuthority.add');
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
                $newPost = Sign1Model::create([
                    //'title' => $request->title,
                
                    'authority_name' => $request->dp_authorities,
                    
                    
        
                ]);
                
                return redirect('dpauthorities');
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
                $dpauthority = Sign1Model::find($id);
                return view('dpAuthority.edit', [
                    'dpauthority' => $dpauthority,
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
                $dpauthority = Sign1Model::find($id);
                $dpauthority->update([
                
                    'authority_name' => $request->dp_authorities,
                
                
                    
                ]);
                
                return redirect('dpauthorities');
            }
        }
		else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sign1Model$site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->route('id');
            $dpauthority = Sign1Model::find($id);
            $dpauthority->delete();
    
            return redirect('dpauthorities/');
    }  
       
  


}
