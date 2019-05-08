<?php
/**
*	应用.打卡异常
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-09-02
*/

namespace App\Model\Agent;


class Rockagent_kqdkerr  extends Rockagent
{
	protected $table = 'kqdkerr';
	protected $jointype 	= 2; //关联userinfo档案
}