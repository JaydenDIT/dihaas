<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DepartmentController extends Controller
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
            $departments =DepartmentModel::all(); //fetch all sites from DB
            return view('department.list', ['departments' => $departments]);
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
            $role = $getUser->role_id;
            if ($role == 999){
            return view('department.add');
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
            $newPost = DepartmentModel::create([
                //'title' => $request->title,
                'dept_name' => $request->dept_name,
            ]);
            
            return redirect('departments');
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
    public function edit(Request $request)
    {
        if ( Auth::user() != null){

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999){
            $id = $request->route('id');
            $dept = DepartmentModel::find($id);
            return view('department.edit', [
                'dept' => $dept,
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
            $dept = DepartmentModel::find($id);
            $dept->update([
                'dept_name'=>$request->dept_name,
            ]);
             
               return redirect('departments');
        }
    }else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepartmentModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
     
            $id = $request->route('id');
            $dept = DepartmentModel::find($id);
            $dept->delete();
    
            return redirect('departments/');
       
    }}


