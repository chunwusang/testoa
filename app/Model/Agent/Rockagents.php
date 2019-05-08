<?php
/**
*	应用多行子表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Agent;

use Illuminate\Database\Eloquent\Model;

class Rockagents  extends Model
{
	public $timestamps 		= false;
	
	//主表名称
	protected $mtablename 	= '';
	
}