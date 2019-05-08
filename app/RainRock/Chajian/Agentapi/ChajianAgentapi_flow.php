<?php
/**
*	操作flow流程
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Agentapi;

use App\Model\Base\FlowbillModel;

class ChajianAgentapi_flow extends ChajianAgentapi
{
	
	/**
	*	运行应用下方法
	*/
	public function postyunact($request)
	{
		$act = $request->input('act');
		if(isempt($act))return returnerror('action isempty');
		$act = 'post_'.$act.'';
		if(!method_exists($this->flow, $act))
			return returnerror(trans('validation.notact',['act'=>$act]));
		return $this->flow->$act($request);
	}
	
	public function yunact($request)
	{
		$act = $request->input('act');
		if(isempt($act))return returnerror('action isempty');
		$act = 'get_'.$act.'';
		if(!method_exists($this->flow, $act))
			return returnerror(trans('validation.notact',['act'=>$act]));
		return $this->flow->$act($request);
	}
	
	/**
	*	提交审核
	*/
	public function postcheck($request)
	{
		$zt 		= (int)$request->input('check_status','0');
		$mid 		= (int)$request->input('mid');
		$sm 		= nulltoempty($request->input('check_explain'));
		$cbar		= $this->flow->initdata($mid);
		if(!$cbar['success'])return $cbar;
		$barr 		= $this->flow->check($zt, $sm, $request);
		return $barr;
	}
	
	/**
	*	批量审核同意
	*/
	public function postcheckpie($request)
	{
		$billid 	= (int)$request->input('id');
		$sm 		= nulltoempty($request->input('sm'));
		$billrs 	= FlowbillModel::where(['cid'=>$this->companyid,'id'=>$billid])->first();
		if(!$billrs)return returnerror('单据不存在了');
		$num 		= $billrs->agenhnum;
		$mid 		= $billrs->mid;
		$flow 		= $this->getNei('flow')->getFlow($num);
		$cbar		= $flow->initdata($mid);
		if(!$cbar['success'])return $cbar;
		$barr 		= $flow->check(1, $sm, $request);
		return $barr;
	}
	
	public function postsaveediter($request)
	{
		$value 	= nulltoempty($request->input('value'));
		$fields = $request->input('fields');
		if(isempt($fields))return returnerror('无效操作');
		$id 	= (int)$request->input('id','0');
		$uarr[$fields] = $value;
		if(!$this->flow->edittablebool)return returnerror('禁止操作');
		$this->flow->getModel()->where([
			'cid' 	=> $this->companyid,
			'id'	=> $id
		])->update($uarr);
		return returnsuccess();
	}
}