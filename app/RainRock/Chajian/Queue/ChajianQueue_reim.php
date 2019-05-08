<?php
/**
*	从服务端来的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Queue;

use App\Model\Base\UsersModel;

class ChajianQueue_reim extends ChajianQueue
{
	
	public function runclient($str)
	{
		$arr 	= explode(',', $str);
		$type   = $arr[0];
		
		//REIM客户端在线时间
		if($type=='online'){
			$uid	= (int)arrvalue($arr,1, '0');
			$online	= (int)arrvalue($arr,2, '0');
			if($uid>0){
				$obj	= UsersModel::find($uid);
				$obj->online = $online;
				$obj->onlinedt = nowdt();
				$obj->save();
			}
		}
		return 'ok'.$str.'';
	}
}