<?php
/**
*	应用.人员档案
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use App\Model\Base\UseraModel;

class Flowagent_kquser  extends Rockflow
{
	public $edittablebool		= true;
	
	protected function flowinit()
	{
		$this->changeStatus();	
		$this->grendarr		= array('未知','男','女');
		$this->iskqarr		= array('','<font color=green>√</font>');
		$this->userinfostate= $this->getNei('option')->getdata('userinfostate');
		//人员状态
	}
	

	
	public function flowreplacers($rs)
	{
		if($rs->status!=1 || $rs->state==5)$rs->ishui = 1;
		$rs->iskqval= $rs->iskq;
		$rs->isdailyval= $rs->isdaily;
		$rs->isdwdkval= $rs->isdwdk;
		$rs->gender = $this->grendarr[$rs->gender];
		$rs->iskq = $this->iskqarr[$rs->iskq];
		$rs->isdaily = $this->iskqarr[$rs->isdaily];
		$rs->isdwdk = $this->iskqarr[$rs->isdwdk];
		foreach($this->userinfostate as $k1=>$rs1){
			if($rs1['value']==$rs->state){
				$rs->state = $rs1['name'];
				break;
			}
		}
		return $rs;
	}
	
}