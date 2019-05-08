<?php
/**
*	客户：默认值
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_customer extends ChajianRockmos
{

	//客户类型
	public $crmtype 	= '互联网,软件,个体经营,个人,政府机构,其他..';
	
	public $crmlaiyuan 	= '网上开拓,电话开拓,其他..';
	
	public $crmjibie 	= '普通客户,重要客户,其他..';
	
	
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