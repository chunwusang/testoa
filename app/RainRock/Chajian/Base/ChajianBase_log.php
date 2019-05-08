<?php
/**
*	插件-系统日志
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	使用方法 $obj = c('log')->add('');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\LogModel;

class ChajianBase_log extends ChajianBase
{
	/**
	*	添加日志
	*/
	public function add($ltype, $remark='',$uarr=array())
	{
		$obj = new LogModel();
		$obj->ltype 	= $ltype;
		$obj->remark 	= $remark;
		foreach($uarr as $k=>$v)$obj->$k = $v;
		if($this->useaid>0){
			$obj->cid 	= $this->companyid;
			$obj->uid 	= $this->useainfo->uid;
			$obj->aid 	= $this->useaid;
			$obj->optname = $this->useainfo->name;
		}
		$bobj 	= $this->getNei('base');
		$obj->optdt 	= nowdt();
		$obj->ip 		= $bobj->getclientip();
		$obj->web 		= $bobj->getbrowser();
		$obj->save();
	}
	
	/**
	*	错误日志添加
	*/
	public function adderror($ltype, $remark, $optname='')
	{
		return $this->add($ltype, $remark, [
			'optname' 	=> $optname,
			'level'		=> 2
		]);
	}
	
	public function adds($ltype, $remark, $optname='', $uid=0, $level=0)
	{
		return $this->add($ltype, $remark, [
			'optname' 	=> $optname,
			'uid'		=> $uid,
			'level'		=> $level,
		]);
	}
}