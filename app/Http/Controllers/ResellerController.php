<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Reseller;
use Flash;

class ResellerController extends Controller {

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
        $resellers = Reseller::all();
        return view('reseller.index')->with(compact('resellers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('reseller.create', ['apikey' => strtoupper(bin2hex(openssl_random_pseudo_bytes(11)))]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Requests\ResellerRequest $request)
    {
        //
        Reseller::create($request->all());

        return redirect('reseller');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $reseller = Reseller::findOrFail($id);
        return view('reseller.show')->with(compact('reseller'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $reseller = Reseller::findOrFail($id);
        $apikey = $reseller->apikey;
        return view('reseller.edit')->with(compact('reseller', 'apikey'));
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
        $reseller = Reseller::findOrFail($id);
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
        $reseller = Reseller::findorFail($id);

        Flash::success("Deleted reseller $reseller->name.");

        $reseller->delete();

        return 1;
    }

}
