<?php
/**
*	客户根据：默认值
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_custgen extends ChajianRockmos
{

	//跟进方式
	public $crmgentype 	= '电话联系,上门拜访,微信联系,其他..';
	
	
	
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
		$data[] 		= array(
			'atype'			=> 'add'
		);
		return $data;
	}
}