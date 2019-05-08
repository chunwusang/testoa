<?php
/**
*	api-应用接口
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

class AgentapiController extends WebController
{
	/**
	*	get方法
	*/
	public function getApidata($cnum, $num, $act, Request $request)
	{
		// $bcd = 123123;
    	// return $bcd;
		$this->getAgenh($cnum, $num);
		if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));
		$acta	= explode('_', $act);
		$runa	= arrvalue($acta, 1, 'getData');
		$obj 	= c('Agentapi:'.$acta[0].'', $this->useainfo);
		$obj->initFlow($num); //初始化流程
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
	
	/**
	*	post方法
	*/
	public function postApidata($cnum, $num, $act, Request $request)
	{
		// $bcd = 123123;
  //   	return $bcd;
		$this->getAgenh($cnum, $num);
		if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));
		$acta	= explode('_', $act);
		$runa	= 'post'.arrvalue($acta, 1, 'Data');
		$obj 	= c('Agentapi:'.$acta[0].'', $this->useainfo);
		$obj->initFlow($num); //初始化流程
		$barr 	= $obj->$runa($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
		return $barr['data'];
	}
}