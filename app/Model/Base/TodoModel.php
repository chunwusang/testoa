<?php
/**
*	提醒表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;


class TodoModel extends Model
{
	protected $table 	= 'todo';
	public $timestamps 	= false;
}
