<?php
/**
*	日报
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_daily extends ChajianRockmos
{

	//日报类型
	public $dailytype 	= '0|日报,1|周报,2|月报,3|年报';
	
	
	//权限
	public function getAuthory($num)
	{
		$data['daily'][] = array(
			'atype'			=> 'add'
		);
		$data['daily'][] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '`aid`={aid} and `dt`>[{date-3}]',
			'explain'		=> '可编辑3天前日报'
		);
		$data['daily'][] = array(
			'atype'			=> 'del',
			'wherestr'		=> '`aid`={aid} and `dt`>[{date-3}]',
			'explain'		=> '可删除3天前日报'
		);
		return $data[$num];
	}
}