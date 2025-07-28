<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesignationModel;
use Illuminate\Support\Facades\Session;

class DesignationController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::get('role_id')!=null){
            $designations =DesignationModel::all(); //fetch all sites from DB
            return view('designation.list', ['designations' => $designations]);
            //views/grade/list
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
        if(Session::get('role_id')!=null){
            return view('designation.add');
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
        if(Session::get('role_id')!=null){
            $newPost = DesignationModel::create([
                //'title' => $request->title,
                'desig_name' => $request->desig_name,
            ]);
            
            return redirect('designations');
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
        if(Session::get('role_id')!=null){
            $id = $request->route('id');
            $desig = DesignationModel::find($id);
            return view('designation.edit', [
                'desig' => $desig,
            ]);
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
        if(Session::get('role_id')!=null){
            $id = $request->route('id');
            $desig = DesignationModel::find($id);
            $desig->update([
                'desig_name'=>$request->desig_name,
            ]);
             
               return redirect('designations');
            }
            else{
                return view('errors.404');
            }
        }
		
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DesignationModel  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      
            $id = $request->route('id');
            $desig = DesignationModel::find($id);
            $desig->delete();
    
            return redirect('designations/');
      
        
    }
}


