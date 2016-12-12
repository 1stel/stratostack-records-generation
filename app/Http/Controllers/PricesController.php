<?php namespace App\Http\Controllers;

use App\ElementPrice;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\SiteConfig;
use App\StorageType;
use Illuminate\Http\Request;

class PricesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function index()
    {
        $priceMethod = SiteConfig::whereParameter('priceMethod')->first();
        $storageTypes = StorageType::whereActive('1')->get();

        $prices = SiteConfig::where('parameter', 'LIKE', '%Price')->get();
        $ratioPrices = [];

        if ($prices->count() > 0) {
            foreach ($prices as $price) {
                $ratioPrices[$price->parameter] = $price->data;
            }
        }

        $elementPrices = ElementPrice::whereActive('1')->orderBy('element')->get();

        return view('prices.index')->with(compact('priceMethod', 'storageTypes', 'ratioPrices', 'elementPrices'));
    }

    public function updateMethod(Request $request)
    {
        $priceMethod = SiteConfig::whereParameter('priceMethod')->first();

        $priceMethod->data = $request->priceMethod;
        $priceMethod->save();

        return response()->json(['blah' => $request->priceMethod]);
    }

    public function updateFixed(Request $request)
    {
        foreach ($request->all() as $key => $val) {
            if (strpos($key, 'Price') !== false) {
                $price = SiteConfig::firstOrCreate(['parameter' => $key]);
                $price->data = $val;
                $price->save();
                unset($price);
            }
        }
        return redirect()->back();
    }

    public function create()
    {
        $storageTypes = StorageType::whereActive('1')->get();

        $elements = ['CPU' => 'CPU Cores', 'RAM' => "RAM"];

        foreach ($storageTypes as $st) {
            $elements[$st->tag] = "$st->storage_type - $st->tag";
        }

        return view('prices.create')->with(compact('elements'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        if ($data['element'] == 'CPU') {
            $data['quantity_type'] = null;
        }

        ElementPrice::create($data);

        return redirect()->route('prices.index');
    }

    public function edit($id)
    {
        $element = ElementPrice::findOrFail($id);

        $storageTypes = StorageType::whereActive('1')->get();

        $elements = ['CPU' => 'CPU Cores', 'RAM' => "RAM"];

        foreach ($storageTypes as $st) {
            $elements[$st->tag] = "$st->storage_type - $st->tag";
        }

        return view('prices.edit')->with(compact('element', 'elements'));
    }

    public function update($id, Request $request)
    {
        $ep = ElementPrice::findOrFail($id);

        $ep->element = $request->element;
        $ep->quantity = $request->quantity;
        $ep->quantity_type = ($request->element == 'CPU') ? null : $request->quantity_type;
        $ep->price = $request->price;

        $ep->save();

        return redirect()->route('prices.index');
    }

    public function destroy($id)
    {
        $ep = ElementPrice::findOrFail($id);
        $ep->delete();

        return 1;
    }
}
