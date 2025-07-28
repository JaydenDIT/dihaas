<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\StateModelRegister;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class StateController extends Controller
{

    public function getStateOption(Request $request)
    {

        $id = $request->post('id');

        $data = StateModelRegister::getOptionByCountry($id)->get();
        if (is_null($data)) {
            return response()->json([
                'status' => 0,
                'msg' => 'No Data'
            ]);
        }
        return response()->json([
            'status' => 1,
            'data' => $data
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if (Auth::user() != null) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999) {
                $states = State::all();
                //fetch all sites from DB
                return view('state.list', ['states' => $states]);
                //views/grade/list
            }
        } else {
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
        if (Auth::user() != null) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999) {
                return view('state.add');
            }
        } else {
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
        if (Auth::user() != null) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999) {
                $newPost = State::create([
                    //'title' => $request->title,
                    'state_name' => $request->state_name,
                    // 'state_desc' => $request->state_desc,
                    'state_code_census' => $request->state_code_census,

                ]);

                return redirect('states');
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
        if (Auth::user() != null) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999) {
                $id = $request->route('id');
                $state = State::find($id);
                return view('state.edit', [
                    'state' => $state,
                ]);
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

    public function update(Request $request)
    {
        if (Auth::user() != null) {

            $user_id = Auth::user()->id;
            $getUser = User::get()->where('id', $user_id)->first();
            $role = $getUser->role_id;
            if ($role == 999) {
                $id = $request->route('id');
                $state = State::find($id);
                $state->update([
                    'state_name' => $request->state_name,
                    //  'state_desc' => $request->state_desc,
                    'state_code_census' => $request->state_code_census,

                ]);

                return redirect('states');
            }
        } else {
            return view('errors.404');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\State$site
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request)
    {

        $id = $request->route('id');
        $state = State::find($id);
        $state->delete();

        return redirect('states/');
    }
}
