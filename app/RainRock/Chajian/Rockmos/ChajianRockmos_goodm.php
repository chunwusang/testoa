<?php
/**
*	物品的：默认值
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_goodm extends ChajianRockmos
{

	//默认流程审核步骤，还需要通知对应人
	public function getCoursedata($num)
	{
		$fdata[]	= array(
			'name'		=> '上级审批',
			'checktype' => 'super'
		);
		
		$fdata[]	= array(
			'name'			=> '行政审批',
			'checktype' 	=> 'rank',
			'checktypename' => '行政主管',
		);
		
		return $fdata;
	}
	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'add'
		);
		return $data;
	}
	
}