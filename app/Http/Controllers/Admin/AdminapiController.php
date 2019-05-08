<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminapiController extends AdminController
{
	/**
	*	get方法
	*/
	public function getApidata($act, Request $request)
	{
		$acta	= explode('_', $act);
		$runa	= arrvalue($acta, 1, 'getData');
		$obj 	= c('Adminapi:'.$acta[0].'');
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
	
	/**
	*	post方法
	*/
	public function postApidata($act, Request $request)
	{
		$acta	= explode('_', $act);
		$runa	= 'post'.arrvalue($acta, 1, 'Data');
		$obj 	= c('Adminapi:'.$acta[0].'');
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
}
