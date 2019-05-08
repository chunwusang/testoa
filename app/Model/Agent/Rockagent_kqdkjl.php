<?php
/**
*	应用.打卡记录
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

namespace App\Model\Agent;


class Rockagent_kqdkjl  extends Rockagent
{
	protected $table = 'kqdkjl';
	protected $jointype 	= 2; //关联userinfo档案
}