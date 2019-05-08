<?php
/**
*	客户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_crm extends ChajianBase
{
	/*
	*	我的客户
	*/
	public function getmycust($data, $frs)
	{
		$flow 	= $this->getFlow('customer');
		$rows	= $flow->getWhereobj('my')->get();
		$barr	= array();
		foreach($rows as $k=>$rs){
			$barr[] = array(
				'value' => $rs->id,
				'name' => $rs->name,
				'subname'=> $rs->unitname,
			);
		}
		return $barr;
	}
	
	/*
	*	我的订单
	*/
	public function getmyorder($data, $frs)
	{
		$flow 	= $this->getFlow('custorder');
		$obj 	= $flow->getWhereobj('my')->where(['isturn'=>1,'status'=>1]);
		//收款单
		if($this->nowflow->agenhnum=='custfina'){
			$obj= $obj->where(['type'=>0,'isover'=>0]);
		}
		//付款单
		if($this->nowflow->agenhnum=='custfinb'){
			$obj= $obj->where(['type'=>1,'isover'=>0]);
		}
		$rows	= $obj->get();
		$barr	= array();
		foreach($rows as $k=>$rs){
			$barr[] = array(
				'value' 	=> $rs->id,
				'name' 		=> $rs->custname.'('.$rs->num.')',
				'custname' 	=> $rs->custname,
				'custid' 	=> $rs->custid,
				'money' 	=> $rs->money,
			);
		}
		return $barr;
	}
	
	/**
	*	更新订单首付款状态
	*/
	public function custUpdate($custid)
	{
		$orderrows = $this->getModel('custorder')->where(['custid' => $custid])->get();
		foreach($orderrows as $k=>$orderrs){
			$this->orderUpdate($orderrs);
		}
	}
	
	public function orderUpdate($orderrs)
	{
		if(is_numeric($orderrs))$orderrs = $this->getModel('custorder')->find($orderrs);
		if(!$orderrs)return false;
		
		$orderid 	= $orderrs->id;
		$moneys		= $orderrs->money;//待收
		$money		= $orderrs->money;//待收
		$ispay		= 0;//0待,1已完成,2部分
		$isover		= 0;//0是否已全部创建收付款单
		//读取收付款信息
		$finarows = $this->getModel('custfina')->where(['orderid' => $orderid])->where('status','<>', 5)->get();
		$ysmoeny	= 0; //已收
		$zzmoeny	= 0; //全部创建
		foreach($finarows as $k=>$rs){
			if($rs->ispay==1)$ysmoeny+=$rs->money;
			$zzmoeny+=$rs->money; 
		}
		if($zzmoeny>=$money)$isover = 1; //全部创建了
		$moneys		= $money-$ysmoeny; //待收
		if($ysmoeny>0)$ispay = 2;//部分收
		if($moneys<=0)$ispay = 1;//全部
		$htshu		= $this->getModel('custract')->where(['orderid' => $orderid])->where('status','<>', 5)->count();
		
		if($moneys != $orderrs->moneys || $htshu != $orderrs->htshu || $ispay!=$orderrs->ispay || $isover!=$orderrs->isover){
			$orderrs->moneys 	= $moneys;
			$orderrs->ispay 	= $ispay;
			$orderrs->isover 	= $isover;
			$orderrs->htshu 	= $htshu;
			$orderrs->save();
		}
		
		return $orderrs;
	}
}