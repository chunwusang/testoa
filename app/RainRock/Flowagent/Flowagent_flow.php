<?php
/**
*	应用.流程
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use App\Model\Base\AgentModel;
use Rock;

class Flowagent_flow  extends Rockflow
{
	//默认排序
	public $defaultorder = 'updated_at,desc|id,desc';
	private $flowobjarr	 = array();
	
	public function flowinit()
	{
		$this->htmlobj	= c('html');
	}
	
	public function flowreplacers($rs)
	{
		$agentnum	= $rs->agenhnum;
	
		$mtable 	= $rs->mtable;
		$mid 		= $rs->mid;
		
		//摘要替换的
		if(!isset($this->flowobjarr[$agentnum])){
			$this->flowobjarr[$agentnum] = Rock::getFlow($agentnum, $this->useainfo, '', false);
		}
		$flow 		= $this->flowobjarr[$agentnum];
		$sbar		= $flow->initdata($mid);
		if(!$sbar['success']){
			$rs->nowstatus 	= '不存在了';
			$rs->ishui 		= 1;
			return $rs;
		}
		$flow->rs 		= $flow->flowreplacers($flow->rs, 2);
		$rs->summary 	= $flow->getsummary();
		if(!isset($rs->optdt))$rs->optdt 	= $flow->rs->optdt;
		
		
		if(isempt($rs->nowstatus) ||  $rs->isturn==0)$rs->nowstatus = $this->getnowstatus($rs);
		$rs->statustext		= $this->htmlobj->htmlremove($rs->nowstatus);
		$rs->statuscolor	= arrvalue($this->statuscolarr, $rs->status);
		if($this->pnum=='yican'){
			$rs->errshuom		= '<font color=red>当前没有处理人<br>可流程设置下设置处理人</font>';
		}
		return $rs;
	}
	
	public function flowlistoption()
	{
		$barr	= array();
		if($this->pnum=='yican'){
			$barr['checkcolums'] = true;
			$barr['btnarr'][] 	 = [
				'name' => '重新匹配流程',
				'click'=> 'repipeixliuc',
				'class'=> 'danger'
			];
			
			$barr['btnarr'][] 	 = [
				'name' => '异常退给申请人',
				'click'=> 'tuihuisenqr',
				'class'=> 'success'
			];
		}
		if($this->pnum=='daiban'){
			$barr['checkcolums'] = true;
			$barr['btnarr'][] 	 = [
				'name' => '选中批量处理同意',
				'click'=> 'chulttongy',
				'class'=> 'primary'
			];
		}
		
		$leftstr	= '<input readonly placeholder="申请日期" class="form-control input_date" type="text" id="search-applydt" onclick="js.datechange(this)" style="width:110px">';
		$leftstr .='</td><td>&nbsp;至&nbsp;</td><td>';
		
		$leftstr	.= '<input readonly class="form-control input_date" type="text" id="search-enddt" onclick="js.datechange(this)" style="width:110px">';
		
		
		$barr['leftstr'] = $leftstr;
		return $barr;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		$dt 	= \Request::get('applydt');
		$enddt 	= \Request::get('enddt');
		if(!isempt($dt))$obj->where('applydt','>=', $dt);
		if(!isempt($enddt))$obj->where('applydt','<=', $enddt);
		return false;
	}
	
	//从新匹配流程
	public function post_repipeixliuc()
	{
		$msg = $this->getNei('flow')->pipeiall($this->companyid);
		return returnsuccess($msg);
	}
	
	//异常退回
	public function post_tuihuisenqr($request)
	{
		$sid 	= $request->input('sid');
		$sm 	= nulltoempty($request->input('sm'));
		if(isempt($sid))return returnerror('没有选择异常单据');
		$rows 	= $this->getModel()->where('cid', $this->companyid)->whereIn('id', explode(',', $sid))->get();
		$k 		= -1;
		foreach($rows as $k=>$rs){
			$num = $rs->agenhnum;
			$mid = $rs->mid;
			$flow= Rock::getFlow($num, $this->useainfo);
			$flow->initdata($mid);
			$flow->checkyitui($sm);
		}
		return returnsuccess('已退回'.($k+1).'条异常记录');
	}
	
	protected function flowfieldsname()
	{
		if($this->pnum!='yican')return;
		$barr['fields_after']=[
			'errshuom' => [
				'name' => '异常原因',
				'isml'	=> 1,
			],
		];
		return $barr;
	}
}