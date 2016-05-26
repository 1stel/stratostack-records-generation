<?php

namespace App\Http\Controllers;

use App\StorageType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class StorageTypeController extends Controller
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
        $storageTypes = StorageType::whereActive('1')->get();

        return view('storagetypes.index')->with(compact('storageTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        // NOT IN USE
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        // NOT IN USE
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        // NOT CURRENTLY IN USE
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
        $st = StorageType::findOrFail($id);

        return view('storagetypes.edit')->with(compact('st'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        //
        $this->validate($request, ['storage_type' => 'required|in:Local HDD,Network HDD,Local SSD,Network SSD']);

        $st = StorageType::findOrFail($id);
        $st->storage_type = $request->storage_type;

        return redirect()->route('storagetypes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
        $st = StorageType::findOrFail($id);
        $st->delete();

        return 1;
    }
}
