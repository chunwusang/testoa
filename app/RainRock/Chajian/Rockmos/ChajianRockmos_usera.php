<?php
/**
*	单位用户应用默认权限
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_usera extends ChajianRockmos
{

	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'daoru', //导入
			'objectid'		=> 'my',
		);
		$data[] = array(
			'atype'			=> 'daochu', //导出
			'objectid'		=> 'my',
		);
		$data[] = array(
			'atype'			=> 'del',
			'objectid'		=> 'my',
		);
		return $data;
	}
}