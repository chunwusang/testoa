<?php
/**
*	应用.物品出入库详情
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_goodx  extends Rockflow
{
	public $classtypenum		= 'goodstype';
	
	protected function flowinit()
	{
		$this->goodsobj 	= $this->getNei('goods');
		$this->classifyobj 	= $this->getNei('classify');
		$this->ckarr		= $this->goodsobj->godepotarr(1);
	}
	
	public function flowbillwhere($obj,$atype)
	{
		$classid 	 	= (int)\Request::get('classid','0');
		$month 	 		= \Request::get('month');
		$this->classid 	= $classid;
		if(!isempt($month))$obj->where('applydt','like',''.$month.'%');
		
		if($classid>0){
			return $obj->whereHas('goodsinfo', function($query)use ($classid){
				$query->where('classid',$classid);
			});
		}
		
		//关键词关联搜索
		if($this->keyword!=''){
			$this->belongstofields = 'goodsinfo,goods.name,goods.num,goods.xinghao,goods.guige';
		}
		
		return false;
	}
	
	public function flowreplacers($rs, $lx=0)
	{
		$rs->depotid	= arrvalue($this->ckarr, $rs->depotid);
		if($rs->goodmid==0)$rs->goodmid='';
		return $rs;
	}
	
	protected function flowlistoption()
	{
		$flarr		= $this->classifyobj->getdata($this->classtypenum);
		$leftstr	= '<select class="form-control" id="souclassid" style="width:130px">';
		$leftstr	.= '<option value="0">所有分类</option>';
		foreach($flarr as $k=>$rs){
			$str = '';
			if($rs['level']>0){
				for($i=0;$i<$rs['level'];$i++)$str.='&nbsp;&nbsp;';
				$str.='├';
			}
			$leftstr	.= '<option value="'.$rs['value'].'">'.$str.''.$rs['name'].'</option>';
		}
		$leftstr	.= '</select></td><td style="padding-left:10px">';
		
		$leftstr	.= '<input readonly placeholder="月份" id="soumonth" onclick="js.datechange(this,\'month\')" class="form-control input_date" style="width:110px">';

		return [
			'leftstr'	=> $leftstr,
			//'optcolums'	=> false,
			//'checkcolums'	=> true,
		];
	}
	
	//应用数据处理，获取到路径
	public function flowgetdata()
	{
		$lujarr[] = array(
			'name' 		=> '所有物品',
			'id' 		=> 0,
			'pid'  		=> 0,
		);
		if($this->classid>0){
			$lujarrs = $this->classifyobj->getpatharr($this->classid);
			unset($lujarrs[0]);
			$lujarr  = array_merge($lujarr, $lujarrs);
		}
		return [
			'lujarr' => $lujarr,
		];
	}
	
	//删除判断
	protected function flowdelbillbefore()
	{
		if($this->rs->goodmid>0)return '此详情关联了申请单，不能在删除';
	}
	
	protected function flowdelbill()
	{
		$this->goodsobj->setstock();
	}
}