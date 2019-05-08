<?php
/**
*	应用.假期信息
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\Model\Agent;


class Rockagent_kqjiaqi  extends Rockagent
{
	protected $table = 'kqjiaqi';
	protected $jointype 	= 2; //关联userinfo档案
}