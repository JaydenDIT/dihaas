<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubDivision;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SubdivisionController extends Controller {
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
                $subdivisions = SubDivision::all();
                //fetch all sites from DB
                return view( 'subdivision.list', [ 'subdivisions' => $subdivisions ] );
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
                return view( 'subdivision.add' );
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
                $newPost = SubDivision::create( [
                    //'title' => $request->title,
                    'district_code_census' => $request->district_cd_cmis,
                    'sub_district_cd_lgd' => $request->sub_district_cd_lgd,
                    'sub_division_name' => $request->sub_division_name,

                ] );

                return redirect( 'subdivisions' );
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
                $subdivision = SubDivision::find( $id );
                // dd( $subdivision );
                return view( 'subdivision.edit', [
                    'subdivision' => $subdivision,
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
                $subdivision = SubDivision::find( $id );
                //dd( $subdivision );
                $subdivision->update( [
                    'district_code_census' => $request->district_code_census,
                    'sub_district_cd_lgd' => $request->sub_district_cd_lgd,
                    'sub_division_name' => $request->sub_division_name,

                ] );

                return redirect( 'subdivisions' );
            }
        } else {
            return view( 'errors.404' );
        }
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\SubDivision$site
    * @return \Illuminate\Http\Response
    */

    public function destroy( Request $request ) {

        $id = $request->route( 'id' );
        $subdivision = SubDivision::find( $id );
        $subdivision->delete();

        return redirect( 'subdivisions/' );

    }
}

