<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FilesToUploadModel;
use App\Models\FileStatusModel;
use App\Models\FileMandatoryModel;
use App\Models\FileDescriptionModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FilesToUploadController extends Controller
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
            $files =FilesToUploadModel::orderBy('doc_id')->get(); //fetch all sites from DB
            return view('file.list', ['files_name' => $files]);
            //views/grade/list
        }
     } else{
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


            $filestatus = FileStatusModel::orderBy('status')->get();
            $mandatoryfile = FileMandatoryModel::all();
           // $file_description = FileMandatoryModel::all();
            
           // $file_description = FileDescriptionModel::all();
            return view('file.add', compact('filestatus','mandatoryfile'));
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
           // dd( $getUser);
            $role = $getUser->role_id;
            if ($role == 999){
            // Define conversion mappings
        //     $isMandatoryMap = [
        //         'Yes' => 'Y',
        //         'No' => 'N',
        //     ];
    
             
        //     // Get values from the request
        //     $isMandatory = $request->input('is_mandatory');
        //    // $fileDescription = $request->input('description');
    
        //     // Apply the conversions using the mappings
        //     $isMandatory = $isMandatoryMap[$isMandatory]; 
           // $fileDescription = $fileDescriptionMap[$fileDescription]; 
    
            // Create a new record in the database
            $newPost = FilesToUploadModel::create([
                'doc_name' => $request->doc_name,
                'is_mandatory' => $request->is_mandatory,
                'status' => $request->status
              //  'description' => $fileDescription,
            ]);
    
            return redirect('files_name');
        }
    } else {
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
            $file = FilesToUploadModel::find($id);
    
            $filestatus = FileStatusModel::orderBy('status')->get();
            $mandatoryfile = FileMandatoryModel::all();
          
            return view('file.edit', compact('file', 'filestatus', 'mandatoryfile'));       
              
               
        } 
    }else {
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
            $file = FilesToUploadModel::find($id);
            

            //     // Define conversion mappings
            //     $isMandatoryMap = [
            //         'Yes' => 'Y',
            //         'No' => 'N',
            //     ];
        
                
        
            //     // Get values from the request
            //     $isMandatory = $request->input('is_mandatory');
            //    // $fileDescription = $request->input('description');
        
            //     // Apply the conversions using the mappings
            //     $isMandatory = $isMandatoryMap[$isMandatory]; 
               // $fileDescription = $fileDescriptionMap[$fileDescription]; 


            $file->update([
                'doc_name' => $request->doc_name,
                'is_mandatory' => $request->is_mandatory,
                'status' => $request->status
                //'description' => $fileDescription,
              
                
            ]);
             
               return redirect('files_name');
        }
    }else{
            return view('errors.404');
        } 
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FilesToUploadModel$site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
      
            $id = $request->route('id');
            $file = FilesToUploadModel::find($id);
            $file->delete();
    
            return redirect('files_name/');
        
    }


}






    