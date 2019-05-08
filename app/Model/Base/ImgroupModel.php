<?php
/**
*	REIM 会话组
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;
use Rock;
use App\Observers\ImgroupObservers;

class ImgroupModel extends Model
{
	protected $table 	= 'imgroup';
	public $timestamps 	= false;
	
	//设置默认头像
	public function getFaceAttribute($val)
    {
		if(isempt($val)){
			$val = '/images/group.png';
		}
		return Rock::replaceurl($val);
    }
	
	public static function boot()
	{
		parent::boot();

		static::observe(new ImgroupObservers());
	}
}
