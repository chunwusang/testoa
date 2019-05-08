<?php
/**
*	应用.用户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-15
*/

namespace App\Model\Agent;

use App\Observers\UseraObservers;

class Rockagent_usera  extends Rockagent
{
	protected $table = 'usera';
	
	public static function boot()
	{
		parent::boot();
		static::observe(new UseraObservers());
	}
}