<?php
/**
*	密码加密
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Observers;

use Hash;


class PasswordHashObservers extends Observers
{

	/**
	 * 保存前处理。
	 */
	public function saving($model)
	{
		if (isset($model->password) && Hash::needsRehash($model->password)) {
			$model->password = Hash::make($model->password);
		}
	}
}
