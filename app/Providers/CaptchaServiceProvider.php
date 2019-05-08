<?php
/**
*	技术验证码服务
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Services\CaptchaServices;

class CaptchaServiceProvider extends ServiceProvider
{
    

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
		$this->app['validator']->extend('captcha', function($attribute, $value, $parameters)
        {
			return \Captcha::check($value);
        });
    }
	
	
	/**
     * Register the application services.
     *
     * @return void
     */
	public function register()
    {
        $this->app->singleton('captcha', function ($app) {
			return new CaptchaServices();
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
			'captcha'
		];
	}
}
