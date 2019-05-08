<?php
/**
*	分区
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_worc extends ChajianRockmos
{

	
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
		$data[] = array(
			'atype'			=> 'add'
		);
		return $data;
	}
}