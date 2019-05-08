<?php
/**
*	组观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;

use App\Model\Base\UsersModel;
use App\Model\Base\SjoinModel;

class GroupObservers extends Observers
{
	
	
	//删除时
	public function deleted($model)
    {
		$mid = $model->id;
		SjoinModel::where('type','gu')->where('mid', $mid)->delete();
    }
}
