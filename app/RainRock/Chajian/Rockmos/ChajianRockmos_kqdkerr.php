<?php
/**
*	打卡异常
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-09-02 12:00:00
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_kqdkerr extends ChajianRockmos
{
	
	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'add'
		);
		return $data;
	}
	
	//默认审批
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
	
	/**
	*	默认选项
	*/
	public function defaultOption()
	{
		$barr[] = array(
			'num' 	=> 'kaoqindkerr',
			'value'	=> '0',
			'name' 	=> '打卡异常可申请次数',
			'explain' => '打卡异常每月可申请次数：为0不限制'
		);
		return $barr;
	}
}