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
use App\Observers\AgentObservers;


class AgentModel extends Model
{
	protected $table = 'agent';
	
	
	public static function boot()
	{
		parent::boot();

		static::observe(new AgentObservers());
	}
	
	/**
	*	对应的字段
	*/
	public function getFields()
	{
		return $this->hasMany(AgentfieldsModel::class, 'agentid');
	}
	
	/**
	*	对应的菜单
	*/
	public function getMenu()
	{
		return $this->hasMany(AgentmenuModel::class, 'agentid');
	}
	
	/**
	*	获取树形菜单
	*/
	public function getMenuArr($agentid)
	{
		$this->getMenuArrss = array();
		$rows = AgentmenuModel::where('agentid', $agentid)->orderBy('sort')->get();
		$this->getMenuArrs($rows, 0, 0);
		return $this->getMenuArrss;
	}
	private $getMenuArrss;
	private function getMenuArrs($rows, $pid, $level)
	{
		foreach($rows as $k=>$rs){
			if($rs->pid == $pid){
				$rs->level = $level;
				$this->getMenuArrss[] = $rs;
				$this->getMenuArrs($rows, $rs->id, $level+1);
			}
		}
	}
}
