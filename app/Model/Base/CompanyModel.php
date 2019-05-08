<?php
/**
*	单位数据模型
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;
use App\Observers\CompanyObservers;

class CompanyModel extends Model
{
	protected $table = 'company';
	
	public static function boot()
	{
		parent::boot();

		static::observe(new CompanyObservers());
	}
	
	//设置默认logo
	public function getLogoAttribute($val)
    {
		if(isempt($val)){
			$val = '/images/nologo.png';
		}else{
			$val = \Rock::replaceurl($val);
		}
		return $val;
    }
}
