<?php
/**
*	基本服务
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Services\RockServices;

class RockServiceProvider extends ServiceProvider
{
    

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
		
    }
	
	
	/**
     * Register the application services.
     *
     * @return void
     */
	public function register()
    {
        $this->app->singleton('rock', function ($app) {
			$config = $app->config->get('rock');
			return new RockServices($config);
		});
    }
    
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'rock'
		];
	}
}
