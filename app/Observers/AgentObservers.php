<?php
/**
*	应用观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;



class AgentObservers extends Observers
{

	public function saving($model)
	{
		$model->num 	= strtolower($model->num);
	}
}
