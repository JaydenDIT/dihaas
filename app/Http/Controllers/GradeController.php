<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Session;

use App\Models\Grade;

class GradeController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::get('role_id')!=null){
            $grades =Grade::all(); //fetch all sites from DB
            return view('grade.list', ['grades' => $grades]);
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
            return view('grade.add');
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
        if(Session::get('role_id')!==null){

            $newPost = Grade::create([
                //'title' => $request->title,
                'grade_name' => $request->grade_name,
            ]);
            
            return redirect('grades');
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
        if(Session::get('role_id')!==null){
            $id = $request->route('id');
            $grade = Grade::find($id);
            return view('grade.edit', [
                'grade' => $grade,
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
        if(Session::get('role_id')!==null){
            $id = $request->route('id');
            $grade =Grade::find($id);
            $grade->update([
                'grade_name'=>$request->grade_name,
            ]);
             
               return redirect('grades');
        }
        else{
            return view('errors.404');
        }
	
        }
       
        
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       
            $id = $request->route('id');
            $grade = Grade::find($id);
            $grade->delete();
    
            return redirect('grades/');
       
    }

    
   


}


