<?php
/**
*	单位下用户观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;

use App\Model\Base\CompanyModel;
use App\Model\Base\SjoinModel;


class UseraObservers extends Observers
{
	
	//新增单位时
	public function created($model)
	{
		$cid = $model->cid;
		$data= CompanyModel::find($cid);
		$data->flasks	= $data->flasks+1;
		$data->save();
	}
	
	//删除时
	public function deleted($model)
    {
		$cid = $model->cid;
		$data= CompanyModel::find($cid);
		$data->flasks	= $data->flasks-1;
		$data->save();
		
		SjoinModel::where('type','gu')->where('sid', $model->id)->delete();
    }
}
