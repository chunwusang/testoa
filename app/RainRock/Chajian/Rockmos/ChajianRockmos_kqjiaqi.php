<?php
/**
*	假期信息
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-21 12:00:00
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_kqjiaqi extends ChajianRockmos
{
	//假期类型
	public $kaoqinjiatype 	= '年假,调休,其他..';
	
	
	/**
	*	默认选项
	*/
	public function defaultOption()
	{
		$barr[] = array(
			'num' 	=> 'kaoqintiaoxyou',
			'value'	=> '0',
			'name' 	=> '加班换调休的有效期',
			'explain' => '0不限制,1一个月,2二个月'
		);
		$barr[] = array(
			'num' 	=> 'kaoqinsbtotal',
			'value'	=> '0',
			'name' 	=> '每天上班时间',
			'explain' => '0读取考勤规则,没有就默认8小时'
		);
		$barr[] = array(
			'num' 	=> 'kaoqinsqtype',
			'value'	=> '病假,事假',
			'name' 	=> '可直接申请请假类型',
			'explain' => '多个,分开，没有设置的请假类型时，申请请假条需要有剩余假期才可以申请。'
		);
		return $barr;
	}
	
	//权限一般是给人事的，这不需要设置默认
	/*
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'add'
		);
		return $data;
	}*/
}