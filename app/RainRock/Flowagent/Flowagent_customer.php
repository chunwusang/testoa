<?php
/**
*	应用.客户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;


class Flowagent_customer  extends Rockflow
{
	

	protected function flowinit()
	{
		$this->statusbilarr	= array('存库','已成交','已丢失','跟进中');
		$this->statuscolarr	= array('','green','#aaaaaa','blue');
		
		$this->statuszf		= array('否','是');
	}
	
	public function flowreplacers($rs)
	{
		if($rs->status==2)$rs->ishui = 1;
		$rs->isgh = $this->statuszf[$rs->isgh];
		$rs->isgys = $this->statuszf[$rs->isgys];
		return $rs;
	}
	
	public function get_custgen($request)
	{
		$mid = (int)$request->input('mid','0');
		$mokui = $request->input('mokui','custgen');
		$str = $this->getNei('flow')->getDatatable($mokui, '`custid`='.$mid.'');
		return returnsuccess($str);
	}
	
	//判断是否有合同订单
	protected function flowdelbillbefore()
	{
		$custid = $this->mid;
		if($this->getModel('custorder')->where('custid', $custid)->count()>0)
			return '客户已生成订单不能删除';
		if($this->getModel('custract')->where('custid', $custid)->count()>0)
			return '客户已生成合同不能删除';
		if($this->getModel('custfina')->where('custid', $custid)->count()>0)
			return '客户已生成收付款不能删除';
	}
	
	//更新名称
	public function flowsaveafter($arr)
	{
		$custname = $arr->name;//更新客户名称
		$custid   = $this->mid;
		$uarr['custname'] = $custname;
		$this->getModel('custgen')->where('custid', $custid)->update($uarr);
		$this->getModel('custorder')->where('custid', $custid)->update($uarr);
		$this->getModel('custract')->where('custid', $custid)->update($uarr);
		$this->getModel('custfina')->where('custid', $custid)->update($uarr);
	}
}