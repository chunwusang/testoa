<?php
/**
*	单位观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;

use App\Model\Base\UsersModel;


class CompanyObservers extends Observers
{
	
	//新增单位时
	public function created($model)
	{
		$uid = $model->uid;
		$data= UsersModel::find($uid);
		$data->flasks	= $data->flasks+1;
		$data->save();
	}
	
	//删除时
	public function deleted($model)
    {
		$uid = $model->uid;
		$data= UsersModel::find($uid);
		$data->flasks	= $data->flasks-1;
		$data->save();
    }
}
