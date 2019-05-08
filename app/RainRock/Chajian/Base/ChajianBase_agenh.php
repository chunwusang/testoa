<?php
/**
*	插件-单位下用户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*	使用方法 $obj = c('usera');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\BaseModel;
use App\Model\Base\AgenhModel;
use App\Model\Base\AgentmenuModel;


class ChajianBase_agenh extends ChajianBase
{
	/**
	*	获取应用树形菜单
	*/
	public function getMenuArr($agenhid,$simbo=false)
	{
		if(is_numeric($agenhid)){
			$agenhinfo = AgenhModel::find($agenhid);
		}else{
			$agenhinfo = $agenhid;
		}
		$agentid	= (int)$agenhinfo->agentid;//系统应用ID
		if($agentid==0)return array();
		$pnum		= $agenhinfo->pnum;
		$this->getMenuArrss = array();
		$rows = AgentmenuModel::where('agentid', $agentid)->where('pnum', $pnum)->orderBy('sort')->get();
		$this->getMenuArrs($rows, 0, 0);
		$barr = $this->getMenuArrss;
		if(!$simbo)return $barr;
		$carr = array();
		foreach($barr as $k=>$rs){
			if($rs->status==1){
				$stra = new \StdClass();
				$stra->id 	= $rs->id;
				$stra->pid 	= $rs->pid;
				$stra->isbag 	= $rs->isbag;
				$stra->name 	= $rs->name;
				$stra->type 	= $rs->type;
				$stra->url 		= $rs->url;
				$stra->stotal 	= $rs->stotal;
				$stra->level 	= $rs->level;
				$carr[] = $stra;
			}
		}
		return $carr;
	}
	private $getMenuArrss;
	private function getMenuArrs($rows, $pid, $level)
	{
		$xu = 0;
		foreach($rows as $k=>$rs){
			if($rs->pid == $pid){
				$rs->level = $level;
				$xu++;
				$this->getMenuArrss[] = $rs;
				$cix = count($this->getMenuArrss)-1;
				$dxu = $this->getMenuArrs($rows, $rs->id, $level+1);
				$this->getMenuArrss[$cix]->stotal = $dxu;
			}
		}
		return $xu;
	}
	
	
	public function getAtypeAgenh()
	{
		$barr = $this->getAgenh(3,1);
		return $barr[0];
	}
	
	/**
	*	获取应用$slx1pc,2移动,3后台,$glx 0要脚标，1不要
	*/
	public function getAgenh($slx=1, $glx=0)
	{
		$agenharr 	= $this->getAgenhlist($this->companyid, $slx);
		if($slx!=3)$agenharr	= $this->getNei('contain')->getcontarr($agenharr,'usableid', true);
		
		$agenh 		= array();
		$agenhtarr	= array();
		$agentids 	= array();
		foreach($agenharr as $k=>$rs){
			if($slx==3 && !isempt($rs->pnum))continue;
			$url 	= $rs->agenhurlpc;
			$stotal	= 0;
			if($rs->agentid>0){
				$agentids[] = $rs->agentid;
				if($slx==1)$url 	= route('list',[$this->useainfo->company->num, $rs->num]);
			}
			
			$rs->url 	= $url;
			$rs->stotal = $stotal;
			$atype		= $rs->atype;
			$agenh[$atype][] = $rs;
			if(!isset($agenhtarr[$atype]))$agenhtarr[$atype] = 0;
		}
		$agenharr	= $agenh;
		
		//徽章角标读取
		if($agentids && $glx==0){
			$arows 		= AgentmenuModel::whereIn('agentid', $agentids)->where('isbag',1)->get();
			$arowssa	= array();
			foreach($arows as $k1=>$rs1){
				if(!isempt($rs1['url']))
				$arowssa[$rs1->pnum.'_'.$rs1->agentid][] = $rs1['url'];
			}
			
			foreach($agenharr as $atype=>$agearr){
				foreach($agearr as $k=>$rs){
					$k2 	 = $rs->pnum.'_'.$rs->agentid;
					
					$stotals = 0;
					if(isset($arowssa[$k2])){
						$flow = \Rock::getFlow($rs->num, $this->useainfo);
						foreach($arowssa[$k2] as $lex){
							$stotal = $flow->getstotal($lex);//读取未读数
							$stotals += $stotal;
						}
					}
					//有未读的
					if($stotals>0){
						$agenharr[$atype][$k]->stotal = $stotals;
						$agenhtarr[$atype] += $stotals;
					}
				}
			}
		}
		
		return [$agenharr, $agenhtarr];
	}
	
	//$slx1获取PC,2手机,9所有导入默认数据选项用到
	public function getAgenhlist($cid, $slx=1)
	{
		if($cid==0)return array();
		$data 	= AgenhModel::select()
					->where('cid', $cid)
					->where('status', 1)
					->orderBy('sort')->get();		
		$barr		= array();
		foreach($data as $k=>$rs){
			$rso 			= new \StdClass();
			$rso->id 		= $rs->id;
			$rso->atype 	= $rs->atype;
			$rso->atypes 	= $rs->atypes;
			$rso->usableid 	= $rs->usableid;
			$rso->face 		= $rs->agenhface;
			$rso->agenhurlm 	= $rs->agenhurlm;
			$rso->agenhurlpc 	= $rs->agenhurlpc;
			$rso->name 			= $rs->name;
			$rso->num 			= $rs->num;
			$rso->pnum 			= $rs->pnum;
			$rso->agentid		= $rs->agentid;
			
			$rso->islu			= ($rso->agentid==0) ? 0 : $rs->sysAgent->islu;//是否录入
			
			$yylx				= $rs->yylx; //应用类型0,1,2
			if($yylx==5)$yylx	= $rs->sysAgent->yylx;
			if($yylx==5)$yylx	= 0;
			$rso->yylx			= $yylx;
			
			$issy				= $rs->issy;
			if($issy==5)$issy	= $rs->sysAgent->issy;
			if($issy==5)$issy	= 0;
			$rso->issy			= $issy;
			
			if($slx==9 && $rso->agentid>0){
				$rso->mtable = $rs->sysAgent->table;
			}
			$bo 				= ($slx==$yylx || $yylx==0 || $slx==9);
			if(!$bo)continue;	
			$barr[$k]			= $rso;
		}
		return $barr;
	}
}