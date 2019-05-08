<?php
/**
*	队列运行-考勤
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Queue;


class ChajianQueue_kaoqin extends ChajianQueue
{
	
	/**
	*	分析考勤
	*/
	public function anay($arr)
	{
		$dt = arrvalue($arr, 'dt', nowdt('dt'));
		$this->getNei('kaoqin')->kqanayall($dt);
		return 'success';
	}
}