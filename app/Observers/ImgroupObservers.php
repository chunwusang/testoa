<?php
/**
*	会话组观察者
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;

use App\Model\Base\ImchatModel;
use App\Model\Base\ImmessztModel;
use App\Model\Base\ImgroupuserModel;
use App\Model\Base\ImmessModel;

class ImgroupObservers extends Observers
{
	
	//删除时
	public function deleted($model)
    {
		$gid = $model->id;
		ImmessztModel::where([
			'type' 	=> 1,
			'gid'	=> $gid,
		])->delete();
		
		ImchatModel::where([
			'type' 		=> 1,
			'receid'	=> $gid,
		])->delete();
		
		ImmessModel::where([
			'type' 		=> 1,
			'receid'	=> $gid,
		])->delete();
		
		ImgroupuserModel::where('gid', $gid)->delete();
    }
}
