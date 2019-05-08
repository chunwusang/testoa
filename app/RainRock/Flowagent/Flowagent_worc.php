<?php
/**
*	应用.文档分区
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;


class Flowagent_worc extends Rockflow
{
	
	//不保存草稿
	protected $flowisturnbool	= false;
	
	
	protected function flowinit()
	{
		$this->changeStatus();
		$this->bobj = c('base');
	}
	
	public function flowreplacers($rs)
	{
		if($rs->size==0)$rs->size='不限制';
		$rs->sizeu= $this->bobj->formatsize($rs->sizeu);
		return $rs;
	}
	
	public function flowdelbillbefore()
	{
		$to = $this->getModel('word')->where('fqid', $this->mid)->count();
		if($to>0)return '分区下有文件/文件夹不能删除';
	}
}