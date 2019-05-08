<?php
/**
*	应用.客户付款单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_custfinb  extends Rockflow
{
	protected function flowfieldsname()
	{
		return [
			'base_deptname' => '所属人部门',
			'base_name' 	=> '所属人'
		];
	}
	
	protected function flowinit()
	{
		
		$this->statuszf		= array('否','<font color=red>是</font>');
	}
	
	public function flowreplacers($rs)
	{
		$rs->ispay = $this->statuszf[$rs->ispay];
		return $rs;
	}
	
	//保存之前判断
	public function flowsavebefore($arr)
	{
		$data 				= array();
		$orderid 			= (int)$arr->orderid;
		$money 				= floatval($arr->money);
		$data['orderid'] 	= $orderid;
		if($orderid>0){
			$orderrs 	= $this->getModel('custorder')->find($orderid);
			if(!$orderrs)return '客户订单不存在的';
			$data['ordernum'] 	= $orderrs->num;
			$data['custid'] 	= $orderrs->custid;
			$data['custname'] 	= $orderrs->custname;
			
			//判断是不是超过金额
			$moneys	= 0;
			$allrows	= $this->getModel('custfina')->where(['orderid'=>$orderid])->where('id','<>', $this->mid)->get();
			foreach($allrows as $k1=>$rs1)$moneys+=$rs1->money;
			
			if($moneys+$money > $orderrs->money)return '付款金额不能超过订单金额';
		}
		if($arr->ispay==1){
			if(isempt($arr->paydt))return '付款时间不能为空';
		}else{
			$data['paydt'] = null;
		}
		$data['type'] = 1;//付款
		return array(
			'data' => $data
		);
	}
	
	public function flowsaveafter($arr)
	{
		$this->getNei('crm')->custUpdate((int)$arr->custid);
	}
	
	public function flowdelbill()
	{
		$this->getNei('crm')->custUpdate((int)$this->rs->custid);
	}
	
	//剩多少
	public function get_moneys($request)
	{
		$orderid = (int)$request->input('orderid');
		$orderrs 	= $this->getModel('custorder')->find($orderid);
		if(!$orderrs)return returnerror('客户订单不存在的');
		
		$moneys	= 0;
		$allrows	= $this->getModel('custfina')->where(['orderid'=>$orderid])->where('id','<>', $this->mid)->get();
		foreach($allrows as $k1=>$rs1)$moneys+=$rs1->money;
		
		$shenmon= $orderrs->money-$moneys;
		if($shenmon<=0){
			$orderrs->isover = 1;
			$orderrs->save();
		}
		
		return returnsuccess([
			'money' 	=> $shenmon,
			'custname' 	=> $orderrs->custname,
			'custid' 	=> $orderrs->custid,
			'ordernum' 	=> $orderrs->num,
		]);
	}
}