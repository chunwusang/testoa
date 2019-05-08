<?php
/**
*	插件-操作菜单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\AgenttodoModel;

class ChajianBase_agenttodo extends ChajianBase
{
	
	/**
	*	获取通知
	*/
	public function gettodolist($act, $agentid, $agenhid)
	{
		$rows = AgenttodoModel::where($act, 1)
					->whereRaw('((`agentid`='.$agentid.' and `cid`=0) or (`agentid`='.$agenhid.' and `cid`='.$this->companyid.') )')
					->get();
		return $rows;
	}
	
	/**
	*	根据编号获取
	*/
	public function gettodonum($num, $agentid)
	{
		$numa = explode(',', $num);
		$rows = AgenttodoModel::whereIn('num', $numa)
					->whereRaw('`agentid`='.$agentid.' and `cid`=0')
					->get();
		return $rows;
	}
	
}