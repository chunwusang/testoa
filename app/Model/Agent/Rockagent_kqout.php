<?php
/**
*	应用.外出出差
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

namespace App\Model\Agent;


class Rockagent_kqout  extends Rockagent
{
	protected $table = 'kqout';
	protected $jointype 	= 2; //关联userinfo档案
}