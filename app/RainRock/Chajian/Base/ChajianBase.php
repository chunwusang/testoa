<?php
/**
*	插件-基础
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use App\RainRock\Chajian\Chajian;

class ChajianBase extends Chajian
{
	protected $nowflow;
	
	/**
	*	设置当前流程信息
	*/
	public function setFlow($flow)
	{
		$this->nowflow = $flow;
		return $this;
	}
	
}