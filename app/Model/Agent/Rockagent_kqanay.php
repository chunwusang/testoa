<?php
/**
*	应用.考勤分析
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-30
*/

namespace App\Model\Agent;


class Rockagent_kqanay  extends Rockagent
{
	protected $table = 'kqanay';
	protected $jointype 	= 2; //关联userinfo档案
}