<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EducationController extends Controller
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
            $educations =EducationModel::all(); //fetch all sites from DB
            return view('education.list', ['educations' => $educations]);
        }
     } else{
            return view('errors.404');
        }
        //views/grade/list
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
            return view('education.add');
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
            //dd($getUser->role_id, $request->edu_name);
            if ($role == 999){
            $newPost = EducationModel::create([
                //'title' => $request->title,
                'edu_name' => $request->edu_name,
            ]);
           // dd( $newPost);
            
            return redirect('educations');
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
    public function edit(Request $request)
    {
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
            $edu = EducationModel::find($id);
            return view('education.edit', [
                'edu' => $edu,
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
            $edu = EducationModel::find($id);
            $edu->update([
                'edu_name'=>$request->edu_name,
            ]);
             
               return redirect('educations');
        }
      }  else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EducationModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       
            $id = $request->route('id');
            $edu = EducationModel::find($id);
            $edu->delete();
    
            return redirect('educations/');
       
    }}


