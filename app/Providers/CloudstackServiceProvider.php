<?php namespace App\Providers;

use Cloudstack\CloudStackClient;
use Illuminate\Support\ServiceProvider;
use App\SiteConfig;

class CloudstackServiceProvider extends ServiceProvider {

	protected $cloudstack;

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$mgmtServer = SiteConfig::whereParameter('mgmtServer')->first();
		$apiKey = SiteConfig::whereParameter('apiKey')->first();
		$secretKey = SiteConfig::whereParameter('secretKey')->first();


		// Endpoint, API Key, Secret Key
        try
        {
			$this->cloudstack = new CloudStackClient($mgmtServer->data, $apiKey->data, $secretKey->data);
        }
        catch (\Exception $e)
        {
            $this->cloudstack = ['error' => $e->getMessage()];
        }
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
		$this->app->singleton('cloudstack', function () {
			return $this->cloudstack;
		});
	}

}
