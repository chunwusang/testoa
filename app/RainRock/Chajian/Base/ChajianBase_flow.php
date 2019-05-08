<?php
/**
*	插件-单据日志
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*	使用方法 $obj = c('flowlog');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\FlowbillModel;
use Rock;

class ChajianBase_flow extends ChajianBase
{
	/**
	*	流程重新匹配
	*/
	public function pipeiall($cid=0, $agenhnum='')
	{
		$obj  = new FlowbillModel();
		if($cid>0)$obj  = $obj->where('cid', $cid);
		$obj  = $obj->where('isturn', 1);
		if($agenhnum!='')$obj  = $obj->where('agenhnum', $agenhnum);
		$obj  = $obj->whereNotin('status', [1,5]);
		$rows = $obj->get();
		
		$showarr 	= array();			
		foreach($rows as $k=>$rs){
			$flow 	= $this->getFlow($rs->agenhnum);
			$mid 	= $rs->mid;
			$flow->initdata($mid);
			$sarr 	= $flow->pipei();
			$name 	= $flow->agenhname;
			if(!isset($showarr[$name]))$showarr[$name] = [0,0,0];
			if(isempt($sarr['nowcheckid']))$showarr[$name][1]++;
			if($sarr['savebo'])$showarr[$name][2]++;
			$showarr[$name][0]++;
		}
		$str = '';
		foreach($showarr as $mne=>$tod){
			if($tod[2]>0){
				if($str!='')$str.='<br>';
				$str.=''.$mne.'：匹配('.$tod[2].')条';
				if($tod[1]>0)$str.=',异常<font color=red>('.$tod[1].')</font>条';
			}
		}
		if($str=='')$str = '无可匹配的流程';
		return $str;
	}
	
	/**
	*	获取流程对象
	*/
	public function getFlow($num)
	{
		return Rock::getFlow($num, $this->useainfo);
	}
	
	/**
	*	获取表格数据
	*/
	public function getDatatable($num, $where)
	{
		$flow 		= $this->getFlow($num);
		$fieldaarr 	= $flow->getFieldsArr('list');
		$farr 		= array();
		foreach($fieldaarr as $k=>$rs)if($rs->iszs==1)$farr[] = $rs;
		
		$data 		= $flow->getWheredata($where);
		
		return $flow->createtable($farr, $data);
	}
}