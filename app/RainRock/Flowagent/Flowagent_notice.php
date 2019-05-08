<?php
/**
*	应用.通知公告
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_notice  extends Rockflow
{
	//标题返回空就是去掉
	public function getDatailtitle()
	{
		return '';
	}
	
	//显示替换
	public function flowreplacers($rs, $lx=0)
	{
		
		$h 	  		= c('html');
		$smallcont 	= $h->htmlremove($rs->content);
		$smallcont 	= $h->substrstr($smallcont,0, 50);
		$rs->smallcont	= $smallcont;
		$yuanpath	= $this->getimgyuan($rs->fengmian);
		if(!isempt($yuanpath)){
			$rs->content='<img style="max-width:100%" onclick="c.imgviews(this)" src="'.$yuanpath.'"><br>'.$rs->content;
		}
		
		if($lx==3)$rs->content = '';//移动端
		
		//列表
		if($lx==2){
			//if(!isempt($rs->fengmian))$rs->fengmian='<img  height="50" src="'.$rs->fengmian.'">';
		}
		if($rs->isread==1){
			$rs->ishui = 1;
		}else{
			$rs->statustext = '未读';
			$rs->statuscolor = 'red';
		}
		
		return $rs;
	}
	
	//默认投票的是隐藏的
	protected function flowinputfields($farr)
	{
		$istp = false;
		if($this->mid>0 && $this->rs->mintou>0)$istp = true;
		$toux = array('maxtou','toupianitem');
		foreach($farr as $k=>$rs){
			if(!$istp && in_array($rs->fields, $toux)){
				$farr[$k]->mstyle='display:none';
			}
		}
		return $farr;
	}
	
	//头部字段1列表，2详情
	protected function flowfieldsname($lx=0)
	{
		if($lx!=1)return;
		$barr['fields_after'] = [
			'base_name' 	=> '添加人'
		];
		return $barr;
	}	
	
	/**
	*	根据开始和截止时间过滤
	*/
	public function flowbillwhere($obj, $atype)
	{
		if($atype=='all' || $atype=='allunread'){
			$obj	= $obj->where(function($query){
				$query = $query->whereNull('startdt');
				$query = $query->orWhere('startdt','<=', nowdt());
			});
			$obj	= $obj->where(function($query){
				$query = $query->whereNull('enddt');
				$query = $query->orWhere('enddt','>=', nowdt());
			});
			
			//发布时间必须大于用户创建时间
			if(!isempt($this->useainfo->createdt))
				$obj	= $obj->where('optdt','>', $this->useainfo->createdt);
			
			return $obj;
		}
		
		return false;
	}
}