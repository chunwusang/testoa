<?php
/**
*	队列服务表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;


class RockqueueModel extends Model
{
	protected $table 	= 'rockqueue';
	public $timestamps 	= false;
	
}