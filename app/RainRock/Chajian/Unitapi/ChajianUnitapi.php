<?php
/**
*	插件-单位管理api
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Unitapi;

use App\RainRock\Chajian\Chajian;

class ChajianUnitapi extends Chajian
{
	
	public function setCompanyinfo($cinfo)
	{
		$this->companyinfo	= $cinfo;
		$this->companyid	= $cinfo->id;
	}
}