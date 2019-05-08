<?php
/**
*	会议
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_meet extends ChajianRockmos
{

	
	//权限
	public function getAuthory($num)
	{
		if($num!='meet')return array();
		$data['meet'][] = array(
			'atype'			=> 'add'
		);
		$data['meet'][] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '`aid`={aid} and `state`=0'
		);
		$data['meet'][] = array(
			'atype'			=> 'del',
			'wherestr'		=> '`aid`={aid} and `state`=0'
		);
		return $data[$num];
	}
}	