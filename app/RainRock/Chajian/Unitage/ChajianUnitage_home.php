<?php
/**
*	管理首页
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;

use App\Model\Base\AgenhModel;
use App\Model\Base\DeptModel;
use App\Model\Base\UseraModel;

class ChajianUnitage_home extends ChajianUnitage
{
	
	public function getForm()
	{
		$agenhtotal	= AgenhModel::where('cid', $this->companyid)->count();
		$depttotal	= DeptModel::where('cid', $this->companyid)->count();
		$usehtotal	= UseraModel::where('cid', $this->companyid)->where('uid',0)->count();
		
		return [
			'agenhtotal' => $agenhtotal,
			'depttotal' => $depttotal,
			'usehtotal' => $usehtotal,
		];
	}
}