<?php
/**
*	应用.假期信息
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqjiaqi  extends Rockflow
{
	//不保存草稿
	protected $flowisturnbool	= false;
	
	
	public function flowdaorutestdata()
	{
		$barr[] = array(
			'applyname' 	=> $this->adminname,
			'jiatype' 		=> '年假',
			'startdt' 		=> nowdt(),
			'enddt' 		=> '',
			'totals' 		=> '8',
			'totday' 		=> '1',
			'explain' 		=> '导入年假8小时1天',
		);
		return $barr;
	}
	
	//导入之前
	public function flowdaorubefore($rows)
	{
		$inarr = array();
		$cobj  = $this->getNei('usera');
		foreach($rows as $k=>$rs){
			$name = $rs['applyname']; //根据名称获取id
			$nafir= $cobj->getnametors($name);
			if(!$nafir)return '['.$name.']用户不存在';
			$rs['aid'] 	= $nafir->id;
			$rs['isturn'] 	= 1;
			$rs['status'] 	= 1;
			$inarr[] 	= $rs;
		}
		return $inarr;
	}
}