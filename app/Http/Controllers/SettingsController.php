<?php

namespace App\Http\Controllers;

use App\SiteConfig;
use App\StorageType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Laracasts\Flash\Flash;

class SettingsController extends Controller {

    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // List all settings
        $mgmtServer = SiteConfig::whereParameter('mgmtServer')->first();
        $apiKey = SiteConfig::whereParameter('apiKey')->first();
        $secretKey = SiteConfig::whereParameter('secretKey')->first();
        $hoursInMonth = SiteConfig::whereParameter('hoursInMonth')->first();

        return view('settings.index')->with(compact('mgmtServer', 'apiKey', 'secretKey', 'hoursInMonth'));
    }

    public function update(Request $request)
    {
        $this->validate($request, ['mgmtServer'   => 'required|url',
                                   'apiKey'       => 'required',
                                   'secretKey'    => 'required',
                                   'hoursInMonth' => 'required|numeric']);

        foreach (['mgmtServer', 'apiKey', 'secretKey', 'hoursInMonth'] as $setting) {
            $$setting = SiteCOnfig::whereParameter($setting)->first();

            if ($$setting->data != $request->$setting)
            {
                $$setting->data = $request->$setting;
                $$setting->save();
            }
        }

        return redirect()->back();
    }

    public function testACS(Request $request)
    {
        $mgmtServer = SiteConfig::whereParameter('mgmtServer')->first();
        $apiKey = SiteConfig::whereParameter('apiKey')->first();
        $secretKey = SiteConfig::whereParameter('secretKey')->first();

        foreach (['mgmtServer', 'apiKey', 'secretKey'] as $setting)
        {
            if ($$setting->data != $request->$setting)
            {
                $$setting->data = $request->$setting;
                $$setting->save();
            }
        }

        try
        {
            $acs = app('cloudstack');

            if (is_array($acs))
            {
                Flash::error($acs['error']);

                return -1;
            }

            $result = $acs->listCapabilities();

            if (isset($result->capability->cloudstackversion))
            {
                Flash::success('Successfully contacted management server.');

                return 1;
            }
            else
            {
                Flash::error('Unable to contact management server.');

                return -1;
            }
        }
        catch (\Exception $e)
        {
            Flash::error($e->getMessage());

            return -1;
        }
    }

    public function syncACS()
    {
        $acs = app('cloudstack');

        if (is_array($acs))
            return -1;

        $offerings = $acs->listDiskOfferings();

        $tags = [];
        foreach ($offerings as $offering)
        {
            // Extract a list of tags
            if (!isset($offering->tags))
                continue;

            if (!in_array($offering->tags, $tags))
                $tags[] = $offering->tags;
        }

        foreach ($tags as $tag)
        {
            // Create a config price and a storage tag if they don't exist.
            StorageType::firstOrCreate(['tag' => $tag]);
            SiteConfig::firstOrCreate(['parameter' => $tag . 'Price']);
        }

        return 1;
    }
}
