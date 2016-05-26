<?php namespace App\Http\Controllers;

use App\FirewallRule;
use App\Reseller;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class FirewallController extends Controller {

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
        $rules = FirewallRule::with('reseller')->get();

        return view('firewall.index')->with(compact('rules'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $resellers = Reseller::whereActive('1')->get();

        $resellerList = [];

        foreach ($resellers as $reseller) {
            $resellerList[$reseller->id] = $reseller->name;
        }
        $resellerList = array_merge(['-1' => 'None'], $resellerList);

        return view('firewall.create')->with(compact('resellerList'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//
        $this->validate($request, [
            'src' => 'required',
            'src_cidr' => 'max:128',
            'dst_port' => 'required|numeric|max:65535',
            'protocol' => 'required|in:tcp,udp,icmp',
            'reseller_id' => 'required',
        ]);

        FirewallRule::create($request->all());

        return redirect()->route('firewall.index');
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
        $resellers = Reseller::whereActive('1')->get();

        $resellerList = [];

        foreach ($resellers as $reseller) {
            $resellerList[$reseller->id] = $reseller->name;
        }
        $resellerList = array_merge(['-1' => 'None'], $resellerList);

        $rule = FirewallRule::findOrFail($id);

        return view('firewall.edit')->with(compact('resellerList', 'rule'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Request $request)
	{
        $this->validate($request, [
            'src' => 'required',
            'src_cidr' => 'max:128',
            'dst_port' => 'required|numeric|max:65535',
            'protocol' => 'required|in:tcp,udp,icmp',
            'reseller_id' => 'required',
        ]);

		// Delete the old rule, create a new one.  The old one will get removed by the backend.
        $rule = FirewallRule::findOrFail($id);
        $rule->delete();

        FirewallRule::create($request->all());

        return redirect()->route('firewall.index');
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
        $rule = FirewallRule::findOrFail($id);

        $rule->delete();

        return 1;
	}

}
