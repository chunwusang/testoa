<?php
/**
*	日程的默认权限
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_schedule extends ChajianRockmos
{

	
	//权限
	public function getAuthory($num)
	{
		//日程
		$data[] = array(
			'atype'			=> 'add' //默认全部人都可以添加
		);
		$data[] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '`aid`={aid}'
		);
		$data[] = array(
			'atype'			=> 'del',
			'wherestr'		=> '`aid`={aid}'
		);
		return $data;
	}
}