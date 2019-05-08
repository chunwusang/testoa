<?php
/**
*	单位后台管理api相关
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;


class UnitapiController extends ApiauthController
{
	
	/**
	*	get方法
	*/
	public function getApidata($cnum, $act, Request $request)
	{
		$cid 	= $this->getCompanyId(null, $cnum);
		if($cid==0)return $this->returnerror(trans('validation.notextent'));
		
		$acta	= explode('_', $act);
		$runa	= arrvalue($acta, 1, 'getData');
		$obj 	= c('Unitapi:'.$acta[0].'', $this->useainfo);
		$obj->setCompanyinfo($this->companyinfo);
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
	
	/**
	*	post方法
	*/
	public function postApidata($cnum, $act, Request $request)
	{
		$cid 	= $this->getCompanyId(null, $cnum);
		if($cid==0)return $this->returnerror(trans('validation.notextent'));
		
		$acta	= explode('_', $act);
		$runa	= 'post'.arrvalue($acta, 1, 'Data');
		$obj 	= c('Unitapi:'.$acta[0].'', $this->useainfo);
		$obj->setCompanyinfo($this->companyinfo);
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
}
