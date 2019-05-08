<?php
/**
*	插件-操作菜单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\FlowmenuModel;

class ChajianBase_flowmenu extends ChajianBase
{
	
	/**
	*	获取菜单
	*/
	public function getoptMenu($agentid)
	{
		$rows = FlowmenuModel::where('agentid',$agentid)->where('status',1)->orderBy('sort')->get();
		return $rows;
	}
	
	public function getrs($id)
	{
		return FlowmenuModel::find($id);
	}
}