<?php
/**
*	应用.订单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_custorder  extends Rockflow
{
	protected function flowfieldsname()
	{
		return [
			'base_deptname' => '所属人部门',
			'base_name' 	=> '所属人'
		];
	}
	
	//判断是否有合同订单
	protected function flowdelbillbefore()
	{
		$orderid = $this->mid;
		$to 	 = $this->getModel('custract')->where('orderid', $orderid)->count();
		if($to>0)
			return '订单已关联合同不能删除';
		
		if($this->getModel('custfina')->where('orderid', $orderid)->count()>0)
			return '订单已生成收付款不能删除';
	}
	
	//保存之前判断
	public function flowsavebefore($arr)
	{
		$money = floatval($arr->money);
		if($money<=0)return '订单金额必须大于0';
	}
	
	public function flowsaveafter($arr)
	{
		$this->getNei('crm')->orderUpdate($this->mid);
	}
}