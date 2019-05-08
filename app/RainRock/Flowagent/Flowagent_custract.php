<?php
/**
*	应用.客户合同
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_custract  extends Rockflow
{
	protected function flowfieldsname()
	{
		return [
			'base_deptname' => '所属人部门',
			'base_name' 	=> '所属人'
		];
	}
	
	//保存之前判断
	public function flowsavebefore($arr)
	{
		$data 				= array();
		$orderid 			= (int)$arr->orderid;
		$data['orderid'] 	= $orderid;
		if($orderid>0){
			$orderrs = $this->getModel('custorder')->find($orderid);
			if(!$orderrs)return '客户订单不存在的';
			$data['ordernum'] 	= $orderrs->num;
			$data['custid'] 	= $orderrs->custid;
			$data['custname'] 	= $orderrs->custname;
			$data['type'] 		= $orderrs->type;
		}
		if($arr->startdt>$arr->enddt)return '生效日期不能大于截止日期';
		
		return array(
			'data' => $data
		);
	}
}