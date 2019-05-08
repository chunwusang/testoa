<?php
/**
*	插件-单据日志
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*	使用方法 $obj = c('flowread');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\FlowreadModel;
use App\Model\Base\UseraModel;

class ChajianBase_flowread extends ChajianBase
{
	/*
	*	获取已读记录人员数组
	*/
	public function getreadarr($mtable, $mid)
	{
		$rows 	= FlowreadModel::where('mtable', $mtable)
					->where('mid', $mid)
					->orderBy('optdt', 'desc')
					->get();
		$aids 	= array();
		foreach($rows as $k=>$rs)$aids[] = $rs->aid;
		if($aids){
			$useraa	= array();
			$usera	= UseraModel::whereIn('id', $aids)->get();
			foreach($usera as $k=>$rs)$useraa[$rs->id] = $rs;
			
			foreach($rows as $k=>$rs){
				$usa = $useraa[$rs->aid];
				$rs->name 		= $usa->name;
				$rs->face 		= $usa->face;
				$rs->deptname 	= $usa->deptname;
			}
		}
		return $rows;
	}
	
	/**
	*	写入查阅记录
	*/
	public function addread($mtable, $mid, $agentid, $billid)
	{
		$aid 	= $this->useaid;
		$obj	= FlowreadModel::where('aid', $aid)
					->where('mtable', $mtable)
					->where('mid', $mid)
					->first();
		if(!$obj){
			$obj = new FlowreadModel();
			$obj->mtable = $mtable;
			$obj->mid 	 = $mid;
			$obj->aid 	 = $aid;
			$obj->cid 	 = $this->companyid;
			$obj->stotal = 1;
			$obj->adddt  = nowdt();
		}else{
			$obj->stotal= $obj->stotal+1;
		}
		$obj->agentid = $agentid;
		$obj->billid  = $billid;
		$obj->optdt = nowdt();
		$obj->save();
		return $obj->id;
	}
	
	/**
	*	获取查阅记录的mid聚合
	*/
	public function getread($mtable, $aid=0)
	{
		if($aid==0)$aid = $this->useaid;
		$rows 	= FlowreadModel::where('cid', $this->companyid)
							   ->where('mtable', $mtable)
							   ->where('aid', $aid)
							   ->get();
		$mida 	= array();
		foreach($rows as $k=>$rs)$mida[] = $rs->mid;
		return $mida;
	}
	
	/**
	*	获取已读的billid
	*/
	public function getreadbill($aid)
	{
		if($aid==0)$aid = $this->useaid;
		$rows 	= FlowreadModel::where('cid', $this->companyid)
							   ->where('aid', $aid)
							   ->where('billid','>', 0)
							   ->get();
		$mida 	= array();
		foreach($rows as $k=>$rs)$mida[] = $rs->billid;
		return $mida;					   
	}
}