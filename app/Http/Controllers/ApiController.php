<?php

namespace App\Http\Controllers;

use Config;
use App\ElementPrice;
use App\SiteConfig;
use App\UsageDisk;
use App\UsageGeneral;
use App\UsageVm;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    // Test URL: http://admin.app/api/getRecords/domainid/46b9a380-a5ab-4947-bd44-e2e277dee4b5/apiKey/1H1h5alGZD7ovMC3ZzB2xDQSmaqHv7-x9vFzURb8xCDd--qlJJtewip0urz7OvFGtUjbp_2oKDOozxYy0fw6TA/secretKey/e2FmmXlgkVnY1Dkzf2xgS-5YOliGUweapqoUweAzlAr7w3iaxsMzpS0W16zH71IOyXjzQTddHdt7KwyoiygRVw/lastDate/2015-03-01

    //
    public function getRecords($domainid, $apiKey, $secretKey, $lastDate)
    {
        // Request should contain an API key, Secret key, start date and end date.
        $acs = app('cloudstack');

        if (is_array($acs)) {
            return response()->json(['error' => 'Internal server error. ' . $acs['error']]);
        }

        $loginResponse = $acs->listUsers(['domainid' => $domainid]);

        foreach ($loginResponse as $user) {
            if (!isset($user->apikey, $user->secretkey)) {
                continue;
            }

            if ($apiKey == $user->apikey && $secretKey == $user->secretkey) {
            // User found, give up the records.
                $usageGeneral = UsageGeneral::where('domainId', '=', $domainid)->where('startDate', '>=', $lastDate)->get()->toArray();
                $usageVMs = UsageVm::where('domainId', '=', $domainid)->where('startDate', '>=', $lastDate)->get()->toArray();
                $usageDisk = UsageDisk::where('domainId', '=', $domainid)->where('startDate', '>=', $lastDate)->get()->toArray();

                return response()->json(['general' => $usageGeneral, 'instances' => $usageVMs, 'disk' => $usageDisk]);
            }
        }

        return response()->json(['error' => 'Invalid credentials.']);
    }

    public function getPricing()
    {
        $priceMethod = SiteConfig::whereParameter('priceMethod')->first();

        if ($priceMethod->data == 'fixedRatio') {
            $prices = SiteConfig::where('parameter', 'LIKE', '%Price')->get();
        } else if ($priceMethod->data == 'elementPrice') {
            $prices = ElementPrice::whereActive('1')->get();
        }

        return response()->json(['priceMethod' => $priceMethod->data, 'prices' => $prices->toArray()]);
    }

    public function getResourceLimits()
    {
        $config = Config::get('cloud.resourceLimits');

        return response()->json($config);
    }
}
