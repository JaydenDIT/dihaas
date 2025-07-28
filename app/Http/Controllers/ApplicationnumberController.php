<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ApplicationnumberModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class ApplicationnumberController extends Controller {

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
                $applicationnumbers = ApplicationnumberModel::all();
                //fetch all sites from DB
                return view( 'applicationnumber.list', [ 'applicationnumbers' => $applicationnumbers ] );
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
                return view( 'applicationnumber.add' );
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

    public function store( Request $request ) {
        if ( Auth::user() != null ) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where( 'id', $user_id )->first();
            $role = $getUser->role_id;

            if ( $role == 999 ) {
                $newPost = ApplicationnumberModel::create( [
                    //'title' => $request->title,
                    'prefix' => $request->prefix,
                    'suffix' => $request->suffix,

                ] );

                return redirect( 'applicationnumbers' );
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

    public function edit( Request $request ) {
        if ( Auth::user() != null ) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where( 'id', $user_id )->first();
            $role = $getUser->role_id;

            if ( $role == 999 ) {
                $id = $request->route( 'id' );
                $applicationnumber = ApplicationnumberModel::find( $id );
                return view( 'applicationnumber.edit', [
                    'applicationnumber' => $applicationnumber,
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

    public function update( Request $request ) {
        if ( Auth::user() != null ) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where( 'id', $user_id )->first();
            $role = $getUser->role_id;

            if ( $role == 999 ) {
                $id = $request->route( 'id' );
                $applicationnumber = ApplicationnumberModel::find( $id );
                $applicationnumber->update( [
                    'prefix' => $request->prefix,
                    'suffix' => $request->suffix,

                ] );

                return redirect( 'applicationnumbers' );
            }
        } else {
            return view( 'errors.404' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Applicationnumbermodel$site
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request ) {

        $id = $request->route( 'id' );
        $applicationnumber = Applicationnumbermodel::find( $id );
        $applicationnumber->delete();

        return redirect( 'applicationnumbers/' );
    }
}
