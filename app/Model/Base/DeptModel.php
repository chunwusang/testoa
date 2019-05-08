<?php
/**
*	部门模型,不写任何业务逻辑，要就写到c('dept')下，只配置表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;


class DeptModel extends Model
{
	protected $table 	= 'dept';
	public $timestamps 	= false;
}
