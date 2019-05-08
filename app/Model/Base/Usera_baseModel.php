<?php
/**
*	单位上用户数据，用来关联单据
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;

class Usera_baseModel extends Model
{
	protected $table = 'usera';
	
	protected $visible = ['name','deptname','id','position','deptallname'];
}