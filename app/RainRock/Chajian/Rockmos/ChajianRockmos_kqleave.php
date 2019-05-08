<?php
/**
*	请假条
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-21 12:00:00
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_kqleave extends ChajianRockmos
{
	//请假类型
	public $kaoqinqjtype 	= '事假,年假,病假,调休';
	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'add'
		);
		return $data;
	}
	
	public function getCoursedata($num)
	{
	
		$fdata[]	= array(
			'name'		=> '上级审批',
			'checktype' => 'super'
		);
		$fdata[]	= array(
			'name'			=> '人事审批',
			'checktype' 	=> 'rank',
			'checktypename' => '人事主管',
		);
		
		
		return $fdata;
	}
}