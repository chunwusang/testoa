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

use App\Model\Base\FlowlogModel;
use App\Model\Base\UseraModel;

class ChajianBase_flowlog extends ChajianBase
{
	/*
	*	获取操作记录
	*/
	public function getlog($mtable, $mid)
	{
		$rows 	= FlowlogModel::where('mtable', $mtable)
					->where('mid', $mid)
					->get();
		$aids 	= array();
		foreach($rows as $k=>$rs)$aids[] = $rs->aid;
		if($aids){
			$useraa	= array();
			$usera	= UseraModel::whereIn('id', $aids)->get();
			foreach($usera as $k=>$rs)$useraa[$rs->id] = $rs;
			$fobj 	= c('file');
			foreach($rows as $k=>$rs){
				if(isset($useraa[$rs->aid])){
					$usa = $useraa[$rs->aid];
					$rs->checkname 	= $usa->name;
					$rs->face 		= $usa->face;
					$rs->deptname 	= $usa->deptname;
				}else{
					$rs->face 		= '/images/noface.png';
					$rs->deptname 	= '';
				}
				$filestr = '';
				if(!isempt($rs->fileid))$filestr = $fobj->getfilestr($rs->fileid);
				
				$rs->filestr 	= $filestr;
			}
		}
		
		return $rows;
	}
	
	/**
	*	删除操作记录
	*/
	public function dellog($mtable, $mid)
	{
		FlowlogModel::where('mtable', $mtable)
					->where('mid', $mid)
					->delete();
	}
}