<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EligibilityModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EligibilityController extends Controller
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
            $eligibilities = EligibilityModel::all(); // Fetch all eligibilities from the DB
            $eligibilityCount = $eligibilities->count();
            return view('eligibility.list', ['eligibilities' => $eligibilities, 'eligibilityCount' => $eligibilityCount],compact('eligibilities'));
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
            $eligibilityCount = EligibilityModel::count();
            return view('eligibility.add', ['eligibilityCount' => $eligibilityCount]);
      
        }
     } else{
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
            $newPost = EligibilityModel::create([
                //'title' => $request->title,
                'eligible_age' => $request->eligible_age,
            ]);
            
            return redirect('eligibilities');
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
            $eligible = EligibilityModel::find($id);
            
            return view('eligibility.edit', [
                'eligible' => $eligible,
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
            $eligible = EligibilityModel::find($id);
            $eligible->update([
                'eligible_age'=>$request->eligible_age,
            ]);
             
               return redirect('eligibilities');
        }
     } else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EligibilityModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       
            $id = $request->route('id');
            $eligible = EligibilityModel::find($id);
            $eligible->delete();
    
            return redirect('eligibilities/');
       
    }}


