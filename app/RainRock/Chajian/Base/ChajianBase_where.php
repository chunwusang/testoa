<?php
/**
*	插件-条件处理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*	使用方法 $obj = c('where');
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_where extends ChajianBase
{
	
	public function recewhere($fields, $useainfo=null)
	{
		if($useainfo==null)$useainfo = $this->useainfo;
		$fids	= "instr(concat(',', $fields, ','),',?,')>0";
		$stra[]	= str_replace('?','u'.$this->useainfo->id.'', $fids);
		
		$deptpath = $this->useainfo->deptpath;
		if(!isempt($deptpath)){
			$depta = explode(',', $deptpath);
			foreach($depta as $dids)$stra[]	= str_replace('?','d'.$dids.'', $fids);
		}
		
		//获取人员组ID
		
		$strs	= join(' or ', $stra);
		$str 	= "($strs)";
		return $str;
	}
}