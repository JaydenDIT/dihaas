<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PercentageModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VPercentageController extends Controller
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
            $vpercent = PercentageModel::all(); // Fetch all percentage from the DB
            $percentageCount = $vpercent->count();
            return view('vpercentage.list', ['vpercent' => $vpercent, 'percentageCount' => $percentageCount],compact('vpercent'));
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
            $percentageCount = PercentageModel::count();
            return view('vpercentage.add', ['percentageCount' => $percentageCount]);
      
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
            $newPost = PercentageModel::create([
                //'title' => $request->title,
                'vpercentage' => $request->vpercentage,
                'year' => $request->year,
            ]);
            
            return redirect('vpercent');
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
            $vpercentage = PercentageModel::find($id);
           
            $old_percentage= $vpercentage->vpercentage;
             $old_year= $vpercentage->year;
             //dd($old_percentage,$old_year );
            return view('vpercentage.edit', [
                'vpercentage' => $vpercentage,
                'old_percentage'=> $old_percentage,
                'old_year'=> $old_year,

                //capture old record
                  'year' => $vpercentage,
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
            $vpercent = PercentageModel::find($id);
  $old_percentage= $vpercent->vpercentage;
             $old_year= $vpercent->year;

            $vpercent->update([
                'vpercentage'=>$request->vpercentage,
                 'year'=>$request->year,
                  'old_percentage'=> $old_percentage,
                'old_year'=> $old_year,

            ]);
             
               return redirect('vpercent');
        }
     } else{
            return view('errors.404');
        }
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PercentageModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       
            $id = $request->route('id');
            $percentage = PercentageModel::find($id);
            $percentage->delete();
    
            return redirect('vpercent/');
       
    }}


