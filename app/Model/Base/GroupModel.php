<?php
/**
*	单位上组数据模型
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;
use App\Observers\GroupObservers;

class GroupModel extends Model
{
	protected $table = 'group';
	public $timestamps 	= false;
	
	public static function boot()
	{
		parent::boot();

		static::observe(new GroupObservers());
	}
}