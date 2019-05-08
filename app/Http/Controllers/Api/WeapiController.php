<?php
/**
*	api-移动端/app等apikey接口
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class WeapiController extends ApiauthController
{
	
	public function getApidata($act, $cnum='', Request $request)
	{
		$this->getCompanyInfo();
		$acta	= explode('_', $act);
		$runa	= arrvalue($acta, 1, 'getData');
		$obj 	= c('Weapi:'.$acta[0].'');
		$msg	= $obj->initUseainfo($cnum, $this->userinfo, $this->companyarr); //初始化
		if(!isempt($msg))return $this->returnerror($msg);
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
	
	/**
	*	post方法
	*/
	public function postApidata($act, $cnum='', Request $request)
	{
		$this->getCompanyInfo();
		$acta	= explode('_', $act);
		$runa	= 'post'.arrvalue($acta, 1, 'Data');
		$obj 	= c('Weapi:'.$acta[0].'');
		$msg	= $obj->initUseainfo($cnum, $this->userinfo, $this->companyarr); //初始化
		if(!isempt($msg))return $this->returnerror($msg);
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
}