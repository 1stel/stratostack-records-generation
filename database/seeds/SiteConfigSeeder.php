<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SiteConfig;

class SiteConfigSeeder extends Seeder {

    public function run()
    {
        DB::table('site_config')->delete();

        SiteConfig::create(['parameter' => 'priceMethod']);
        SiteConfig::create(['parameter' => 'corePrice']);
        SiteConfig::create(['parameter' => 'ramPrice']);
        SiteConfig::create(['parameter' => 'setupComplete', 'data' => '0']);

        // Management server settings
        SiteConfig::create(['parameter' => 'mgmtServer']);
        SiteConfig::create(['parameter' => 'apiKey']);
        SiteConfig::create(['parameter' => 'secretKey']);

        // Billing settings
        SiteConfig::create(['parameter' => 'hoursInMonth']);
    }

}