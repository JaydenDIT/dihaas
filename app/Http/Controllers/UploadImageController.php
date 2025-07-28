<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UploadImageModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\NotificationModel;

use Illuminate\Support\Facades\Response;

class UploadImageController extends Controller {
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

   
    public function index() {
        if ( Auth::user() != null ) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where( 'id', $user_id )->first();
            $role = $getUser->role_id;
            if ( $role == 999 ) {
                $uploadimages = UploadImageModel::orderBy('status', 'asc')->get();
                //fetch all sites from DB
                
                return view( 'uploadimage.list', [ 'uploadimages' => $uploadimages ] );
                //views/grade/list
            }
        } else {
            return view( 'errors.404' );
        }

    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function create() {
        if ( Auth::user() != null ) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where( 'id', $user_id )->first();
            $role = $getUser->role_id;
            if ( $role == 999 ) {
                return view( 'uploadimage.add' );
            }
        } else {
            return view( 'errors.404' );
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
    if (Auth::user() != null) {
        $user_id = Auth::user()->id;
        $getUser = User::where('id', $user_id)->first();
        $role = $getUser->role_id;

        $request->validate([
         
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($role == 999) {
            $imagePath = null;
        

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $path = $request->file('image')->storeAs('public/images', $imageName);
                        //images is the directory name to store the iamges 
                        //D:\dihaas\storage\app\public\images
                $imageUrl = Storage::url('images/' . $imageName); 

                 // Save the filename to the database
          
    
            }

            // Get the count of existing records
            $existingCount = UploadImageModel::count();
            
            // Increment the status of existing records
            UploadImageModel::where('id', '>', 0)->increment('status');

            // Set status of the newly added record to 1
            $newPost = UploadImageModel::create([
               
                'image' => $imageName,
                'status' => 1,
            ]);

            return redirect('uploadimages');
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

    public function edit( Request $request ) {
        if ( Auth::user() != null ) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where( 'id', $user_id )->first();
            $role = $getUser->role_id;

            if ( $role == 999 ) {
                $id = $request->route( 'id' );
                $dept = UploadImageModel::find( $id );
                return view( 'uploadimage.edit', [
                    'dept' => $dept,
                ] );
            }

        } else {
            return view( 'errors.404' );
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
    if (Auth::user() != null) {
        $user_id = Auth::user()->id;
        $getUser = User::findOrFail($user_id);
        $role = $getUser->role_id;

        $request->validate([
           
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($role == 999) {
            $id = $request->route('id');
            $dept = UploadImageModel::findOrFail($id);
         

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $image->getClientOriginalName();
                $path = $request->file('image')->storeAs('public/images', $imageName);

                // Delete old image if it exists
                if ($dept->image && Storage::disk('public')->exists($dept->image)) {
                    Storage::disk('public')->delete($dept->image);
                }

                $dept->update(['image' =>  $imageName]); // Update image path in the database
            }

            return redirect('uploadimages');
        }
    } else {
        return view('errors.404');
    }
}

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\UploadImageModel  $site
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request ) {

        $id = $request->route( 'id' );
        $dept = UploadImageModel::find( $id );
        $dept->delete();

        return redirect( 'uploadimages/' );

    }
}