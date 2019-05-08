<?php
/**
*	单据提醒设置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_remind extends ChajianRockmos
{

	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'daochu', //导出
		);
		$data[] = array(
			'atype'			=> 'add',
		);
		$data[] = array(
			'atype'			=> 'del',
			'wherestr'		=> '{my}',
		);
		$data[] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '{my}',
		);
		return $data;
	}
}