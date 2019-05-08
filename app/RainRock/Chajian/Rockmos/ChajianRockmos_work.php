<?php
/**
*	任务
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_work extends ChajianRockmos
{

	//任务类型
	public $worktype 	= '开发,改进,建议';

	public $workgrade 	= '普通,平级,紧急';
	
	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'add'
		);
		$data[] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '(`aid`={aid} or {join,ddid})'
		);
		$data[] = array(
			'atype'			=> 'del',
			'wherestr'		=> '{my}'
		);
		return $data;
	}
}