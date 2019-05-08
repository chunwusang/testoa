<?php
/**
*	用户单位下主页
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

namespace App\Http\Controllers\Users;

use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use App\Model\Base\CompanyModel;
use App\Model\Base\AgentmenuModel;
use Auth;
use App\Model\Base\BaseModel;
use Rock;


class HouseController extends UsersController
{
	/**
	*	显示视图
	*/
	public function house()
	{
        $d = 2122;
		return $d;
	}
}
