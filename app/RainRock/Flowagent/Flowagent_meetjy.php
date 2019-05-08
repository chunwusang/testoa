<?php
/**
*	应用.会议
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_meetjy  extends Rockflow
{
	//7天内容的可添加纪要，超过就不要了。
	public function meetdata()
	{
		$enddt= c('date')->adddate(nowdt(),'d', -7);
		$data = $this->getModel('meet')->where(['cid'=>$this->companyid,'status'=>1,'isturn'=>1])
					->where('startdt','<', nowdt()) //必须是开始
					->where('enddt','>', $enddt) //7天内
					->get();
		$barr = array();
		foreach($data as $k=>$rs){
			$barr[] = array(
				'value' => $rs->id,
				'name' => $rs->title,
				'receid'=> $rs->joinid,
				'recename'=> $rs->joinname,
				'subname' => $rs->hyname,
			);
		}
		return $barr;
	}		
}