<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RemarksApproveModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RemarksApproveController extends Controller
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
                $remarksapproves =RemarksApproveModel::all(); //fetch all sites from DB
                return view('remarksapprove.list', ['remarksapproves' => $remarksapproves]);
            
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
                return view('remarksapprove.add');
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
                $newPost = RemarksApproveModel::create([
                    //'title' => $request->title,
                
                    'probable_remarks' => $request->probable_remarks,
                    
                    
        
                ]);
                
                return redirect('remarksapproves');
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
                $remarksapprove = RemarksApproveModel::find($id);
                return view('remarksapprove.edit', [
                    'remarksapprove' => $remarksapprove,
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
                $remarksapprove = RemarksApproveModel::find($id);
                $remarksapprove->update([
                
                    'probable_remarks' => $request->probable_remarks,
                
                
                    
                ]);
                
                return redirect('remarksapproves');
            }
        }
		else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RemarksApproveModel$site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->route('id');
            $remarksapprove = RemarksApproveModel::find($id);
            $remarksapprove->delete();
    
            return redirect('remarksapproves/');
    }  
       
  


}