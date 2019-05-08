<?php
/**
*	通知
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_notice extends ChajianRockmos
{

	//类型
	public $noticetype 	= '通知公告,处罚通告,奖励通告,规则制度';
	
	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '{my}'
		);
		$data[] = array(
			'atype'			=> 'del',
			'wherestr'		=> '{my}'
		);
		return $data;
	}
}