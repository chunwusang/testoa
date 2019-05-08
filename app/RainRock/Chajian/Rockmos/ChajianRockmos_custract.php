<?php
/**
*	客户合同：默认值
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_custract extends ChajianRockmos
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
	
	//默认流程审核步骤
	public function getCoursedata($num)
	{
		
		$fdata['custract'][]	= array(
			'name'		=> '上级审批',
			'checktype' => 'super'
		);
		return arrvalue($fdata, $num, array());
	}
}