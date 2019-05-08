<?php
/**
*	平台用户观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-20
*/

namespace App\Observers;

use App\Model\Base\UseraModel;


class UsersObservers extends Observers
{
	
	//保存时
	public function saved($model)
	{
		$uid = $model->id;
		$uarr= array();
		if(isset($model->name))$uarr['name'] 		= $model->name;
		if(isset($model->mobile))$uarr['mobile'] 	= $model->mobile;
		if(isset($model->mobilecode))$uarr['mobilecode'] 	= $model->mobilecode;
		if($uarr)UseraModel::where('uid', $uid)->update($uarr);
	}
	
	//删除后单位用户状态修改
	public function deleted($model)
    {
		$uid = $model->id;
		$uarr['uid'] 	= 0;
		$uarr['status'] = 0;
		UseraModel::where('uid', $uid)->update($uarr);
    }
}
