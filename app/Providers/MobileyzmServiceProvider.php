<?php
/**
*	手机验证码服务
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\RainRock\Mobilesms\MobilesmsService;

class MobileyzmServiceProvider extends ServiceProvider
{
    

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
		$this->app['validator']->extend('mobileyzm', function($attribute, $value, $parameters)
        {
			//$obj	= app('rock');
			$gbarr	= c('rockapi')->checkcode(arrvalue($parameters, 0), $value,arrvalue($parameters, 1),arrvalue($parameters, 2));
			return $gbarr['success'];
        });
    }
	
	
}
