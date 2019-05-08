<?php
/**
*	单位应用观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;

use App\Model\Base\AuthoryModel;
use App\Model\Base\ImchatModel;

class AgenhObservers extends Observers
{

	public function saving($model)
	{
		$model->num 	= strtolower($model->num);
	}
	
	//删除时
	public function deleted($model)
    {
		$agenhid = $model->id;
		AuthoryModel::where('agenhid', $agenhid)->delete();
		ImchatModel::where([
			'type' 	 => 2,
			'receid' => $agenhid
		])->delete();
    }
}
