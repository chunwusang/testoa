<?php
/**
*	应用
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;
use App\Observers\AgenhObservers;
use Rock;

class AgenhModel extends Model
{
	protected $table 	= 'agenh';
	
	public $timestamps 	= false;
	
	protected $appends = [
	    'agenhurlm', //最后手机端地址
	    'agenhurlpc', //最后手机端地址
		'agenhface',
		'agenhtable',
		'agenhmwhere',
    ];
	
	public static function boot()
	{
		parent::boot();

		static::observe(new AgenhObservers());
	}
	
	//得到默认值
	private function getDefaultval($val, $fields)
	{
		if(isempt($val) && $this->sysAgent){
			$val = $this->sysAgent->$fields;
		}
		return $val;
	}
	
	public function getNameAttribute($val)
    {
		return $this->getDefaultval($val, 'name');
    }
	
	public function getAgenhtableAttribute($val)
    {
		return $this->getDefaultval($val, 'table');
    }
	
	public function getAgenhmwhereAttribute($val)
    {
		return $this->getDefaultval($val, 'mwhere');
    }
	public function getAgenhfaceAttribute($val)
    {
		$val = $this->getDefaultval($this->face, 'face');
		return Rock::replaceurl($val);
    }
	
	public function getFaceAttribute($val)
    {
		return $this->getDefaultval($val, 'face');
    }
	
	//说明
	public function getDescriptionAttribute($val)
    {
		return $this->getDefaultval($val, 'description');
    }
	
	public function getSummarxAttribute($val)
    {
		return $this->getDefaultval($val, 'summarx');
    }
	
	public function getAtypeAttribute($val)
    {
		return $this->getDefaultval($val, 'atype');
    }
	
	public function getAgenhurlmAttribute()
    {
		$url = $this->urlm;
		if(isempt($this->urlm)){
			if($this->agentid>0 && $this->sysAgent)$url = $this->sysAgent->urlm;
		}
		if(isempt($url))$url = route('ying',[$this->company->num,$this->num]);
		return Rock::replaceurl($url);
    }
	
	public function getAgenhurlpcAttribute()
    {
		$url = $this->urlpc;;
		if(isempt($this->urlpc)){
			if($this->agentid>0 && $this->sysAgent)$url = $this->sysAgent->urlpc;
		}
		if(isempt($url))$url = $this->getAgenhurlmAttribute();
		return Rock::replaceurl($url);
    }
	
	public function getUsablenameAttribute($val)
    {
		if(isempt($val)){
			$val = trans('table/agenh.usablename_empty');
		}
		return $val;
    }
	
	/**
	 * 跟单位表关联
	 */
	public function company()
	{
		return $this->belongsTo(CompanyModel::class, 'cid', 'id');
	}
	
	
	/**
	 * 跟系统应用agent表关联
	 */
	public function sysAgent()
	{
		return $this->belongsTo(AgentModel::class, 'agentid', 'id');
	}
	
	/**
	*	1对多对应的菜单
	*/
	public function getMenu()
	{
		return $this->hasMany(AgentmenuModel::class, 'agentid','agentid');
	}
	
	/**
	*	对应的流程
	*/
	public function getFlowcourse()
	{
		return $this->hasMany(FlowcourseModel::class, 'agenhid');
	}

}
