<?php
/**
*	应用.分类管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_classify  extends Rockflow
{
	protected $flowisturnbool	= false;
	public $edittablebool		= true; //开启可编辑
	
	public $defaultorder		= 'sort,asc';
	
	
	protected function flowinit()
	{
		$this->changeStatus();	
		$this->classifyobj = $this->getNei('classify');
	}
	
	public function flowreplacers($rs)
	{
		$downshu = $this->getModel()->where('pid', $rs->id)->count();
		if($downshu>0)$rs->downshu = $downshu;
		return $rs;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		$pid 	 	= (int)\Request::get('pid','0');
		$num		= \Request::get('num');
		$mpid		= 0;
		if(!isempt($num)){
			$pid 	= $this->classifyobj->numtoid($num);
			$mpid	= $pid;
		}
		
		$this->pid = $pid;
		$this->mpid = $mpid;
		if($pid>=0)return $obj->where('pid', $pid);
		
		return false;
	}
	
	//应用数据处理，获取到路径
	public function flowgetdata()
	{
		$lujarr[] = array(
			'name' 		=> '所有分类管理',
			'id' 		=> 0,
			'pid'  		=> 0,
		);
		if($this->pid>0){
			$lujarrs = $this->classifyobj->getpatharr($this->pid);
			$lujarr  = array_merge($lujarr, $lujarrs);
		}
		return [
			'lujarr' => $lujarr,
			'pid' => $this->pid,
			'mpid' => $this->mpid,
		];
	}
	
	
	public function flowdelbillbefore()
	{
		$to = $this->getModel()->where('pid', $this->mid)->count();
		if($to>0)return '有下级分类不能删除';
	}
	
	public function flowsaveafter($arr)
	{
		$name = $arr->name;
	}
}