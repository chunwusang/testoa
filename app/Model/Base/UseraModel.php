<?php
/**
*	单位上用户数据模型
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;
use App\Observers\UseraObservers;
use Rock;

class UseraModel extends Model
{
	protected $table 	= 'usera';
	public $timestamps 	= false;
	
	private $mobilejm	= true;
	
	protected $appends = [
	    'companyname',
	    'companylogo',
	    'companynum',
		'face',
		'online'
    ];
	
	
	public static function boot()
	{
		parent::boot();
		static::observe(new UseraObservers());
	}
	

	/**
	*	单位名称
	*/
	public function getCompanynameAttribute()
    {
        return $this->company->name;
    }
	
	public function getCompanylogoAttribute()
    {
        return $this->company->logo;
    }
	
	public function getCompanynumAttribute()
    {
        return $this->company->num;
    }
	
	public function getFaceAttribute()
    {
		$val = '/images/noface.png';
        if($this->platusers)$val = $this->platusers->face;
		return Rock::replaceurl($val);
    }
	
	public function getOnlineAttribute()
    {
		$val = 0;
        if($this->platusers)$val = $this->platusers->online;
		return $val;
    }
	
	/**
	*	跟平台用户表关联
	*/
	public function platusers()
	{
		return $this->belongsTo(UsersModel::class, 'uid', 'id')->select('id','face','name','online');
	}
	
	/**
	 * 跟单位表关联
	 */
	public function company()
	{
		return $this->belongsTo(CompanyModel::class, 'cid', 'id');
	}
}
