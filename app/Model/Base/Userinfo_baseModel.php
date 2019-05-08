<?php
/**
*	单位用户档案
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;

class Userinfo_baseModel extends Model
{
	protected $table = 'userinfo';
	
	protected $visible = ['name','deptname','aid','position','deptallname'];
}