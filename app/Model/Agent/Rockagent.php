<?php
/**
*	应用的通用默认，不写业务逻辑，一般只是这种观察者
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Agent;

use Illuminate\Database\Eloquent\Model;
use App\Model\Base\Usera_baseModel;
use App\Model\Base\Userinfo_baseModel;

class Rockagent  extends Model
{
	public $timestamps 		= false;
	protected $jointype 	= 1; //0关联,1跟单位用户,2跟档案
	
	protected $appends = [
	    'base_name',
	    'base_deptname',
		'base_sericnum'
    ];
	
	
	//得到默认值
	private function getDefaultval($val='', $fields)
	{
		if(!$this->jointype)return $val;
		if(isempt($val) && $this->useainfo){
			$val = $this->useainfo->$fields;
		}
		return $val;
	}
	
	/**
	 * 跟单位用户关联
	 */
	public function useainfo()
	{
		if(!$this->jointype)return false;
		if($this->jointype==1){
			return $this->belongsTo(Usera_baseModel::class, 'aid', 'id');
		}else{
			return $this->belongsTo(Userinfo_baseModel::class, 'aid', 'aid');
		}
	}
	
	//申请人姓名
	public function getBaseNameAttribute()
    {
		return $this->getDefaultval('', 'name');
    }
	
	//申请人部门
	public function getBasedeptnameAttribute()
    {
		return $this->getDefaultval('', 'deptname');
    }
	
	public function getBaseSericnumAttribute($val)
    {
		return $val;
    }
}