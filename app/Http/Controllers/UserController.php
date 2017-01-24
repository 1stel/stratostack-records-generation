<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Flash;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProfile()
    {
        return view('user.profile');
    }

    public function postProfile(Request $request)
    {
        $this->validate($request, ['pass'    => 'required',
                                   'newpass' => 'required|confirmed|min:10']);

        $user = Auth::User();

        if (Auth::attempt(['email' => $user->email, 'password' => $request->pass])) {
            $user->password = bcrypt($request->newpass);
            $user->save();

            return redirect()->back();
        } else {
            // Return back with errors
            Flash::error('Current password is incorrect.');

            return redirect()->back();
        }
    }
}
