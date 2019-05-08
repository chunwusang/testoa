<?php
/**
*	人事档案：默认值
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;


class ChajianRockmos_userinfo extends ChajianRockmos
{

	//人员状态
	public $userinfostate 	= '0|试用期,1|正式,2|实习生,3|兼职,4|临时工,5|离职';
	
	//权限
	public function getAuthory($num)
	{
		$data[] = array(
			'atype'			=> 'edit',
			'wherestr'		=> '{my}'
		);
		return $data;
	}
	
}