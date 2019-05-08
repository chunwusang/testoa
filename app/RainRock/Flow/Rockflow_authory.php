<?php
/**
*	工作流-核心文件_权限判断
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flow;

use DB;

trait Rockflow_authory
{
	
	/**
	*	是否编辑权限
	*/
	public function iseditqx()
	{
		$bo = $this->flowactrun('flowiseditqx');
		if($bo===1 || $bo===0)return $bo;
		return $this->ispanduanbool(0);
	}
	
	/**
	*	是否删除权限
	*/
	public function isdelqx()
	{
		$bo = $this->flowactrun('flowisdelqx');
		if($bo===1 || $bo===0)return $bo;
		return $this->ispanduanbool(1);
	}
	
	/**
	*	是否删除权限
	*/
	public function isaddqx()
	{
		return $this->authoryobj->isadd($this->agenhinfo->id);
	}
	
	//判断$lx=1删除，在没有流程时超级管理员可编辑和删除
	private function ispanduanbool($lx)
	{
		$bo 	= 0;
		$ismy	= ($this->nowaid==$this->useaid || objvalue($this->rs,'optid')==$this->useaid);
		if($this->isflow>0){
			//操作人或者申请人等当前用户
			if($ismy){
				if($this->rs->status==2 || $this->billrs->nstatus==0)$bo = 1; //未同意
				if($this->rs->isturn==0 && $this->rs->status!=5)$bo = 1;//未作废未提交
				
				if($lx==1 && $this->rs->isturn==0)$bo = 1;//未提交可删除
			}
			
		}else{
			
			//$useatype	= $this->authoryobj->useatype();
			//if($useatype==2)$bo = 1;//超级管理员可编辑和删除
		}
		
		if(isset($this->rs->isturn) && $this->rs->isturn==0 && $ismy){
			$bo = 1;
		}
		
		//作废可以删
		if($lx==1 && $ismy && $this->rs->status==5){
			$bo = 1;
		}
		
		//超级管理员可删除，管理员不允许随随便便编辑人家数据的
		//if($bo==0 && $lx==1){
		//	$useatype	= $this->authoryobj->useatype();
		//	if($useatype==2)$bo = 1;
		//}
		
		//审核完成就不允许编辑
		if($this->isflow>0){
			if($lx==0 && $this->rs->status==1)return 0;
		}
		
		if($bo==0){
			if($lx==0){
				$barr 	= $this->authoryobj->geteditwhere($this->agenhid);
			}else{
				$barr 	= $this->authoryobj->getdelwhere($this->agenhid);
			}
			
			if(!$barr)return $bo;
			
			//全部的
			foreach($barr as $k=>$rs){
				if($rs->wherestr=='1=1'){
					return 1;
				}
			}
			
			foreach($barr as $k=>$rs){
				$wherestr 	= $rs->wherestr;
				$obj		= $this->getModel();
				$obj		= $obj->where('id', $this->mid);
				$obj 		= $obj->whereRaw($wherestr);
				
				if($obj->count()==1){
					return 1;
				}
			}
		}
		return $bo;
	}
	
	
	/**
	*	删除单据
	*/
	public function delbill($mid, $sm='', $delpx=true)
	{
		$cbar = $this->initdata($mid);
		if(!$cbar['success'])return $cbar;
		
		if($delpx && $this->isdelqx()==0)return returnerror('没权限删除');

		$msg = $this->flowactrun('flowdelbillbefore');
		if(!isempt($msg))return returnerror($msg);
	
		$dearra	= explode(',', 'flowlog,flowchao,flowchecks,flowcourss,flowbill,remind,todo,flowread,file');
		foreach($dearra as $tab)
			DB::table($tab)->where([
				'mtable'=> $this->mtable,
				'mid'	=> $mid,
			])->delete();
		
		//加入系统日志
		$this->getNei('log')->add('删除单据','删除应用['.$this->agenhname.']id为'.$mid.'的单据');
	
		$obj	= $this->getModel();
		$obj->find($mid)->delete();
		
		//删除子表
		$tables = $this->agentinfo->tables;
		if(!isempt($tables)){
			$tablea = explode(',', $tables);
			foreach($tablea as $tab)
				$this->getModels($tab)->where('mid',$mid)->delete();
		}
		
		
		$this->agenttodosend('bodel');
		$this->flowactrun('flowdelbill', $sm);
		
		return returnsuccess();
	}
	
	/**
	*	作废
	*/
	public function zuofei($sm)
	{
		$isturn = $this->rs->isturn;
		$status = $this->rs->status;
		
		if($isturn==0)return returnerror('该单据还未提交，可直接删除');
		if($status==5)return returnerror('该单据已作废过了');

		$dearra	= explode(',', 'flowchao,flowchecks,flowcourss,remind,todo');
		foreach($dearra as $tab)
			DB::table($tab)->where('mtable', $this->mtable)->where('mid', $this->mid)->delete();
		
		$this->updateData(['status'=>5]);
		$this->agenttodosend('bozuofei');
		
		$this->getflow(true);//匹配流程
		$this->flowactrun('flowzuofei', $sm);
		
		return returnsuccess('单据作废成功');
	}
	
	public function zhuijiaexplain($sm)
	{
		$arr	= $this->getflow(true);
		
		$this->nexttodo($arr['nowcheckid'],'zhui','', $sm);
		
		return returnsuccess('追加说明成功');
	}
	
	
	/**
	*	清空数据
	*/
	public function delall()
	{
		$useatype	= $this->authoryobj->useatype();
		if($useatype != 2)return returnerror('不是超级管理员不能操作');
		
		$obj		= $this->getModel()->where('cid', $this->companyid);
		$mwhere 	= $this->agentinfo->mwhere;
		if(!isempt($mwhere))$obj = $obj->whereRaw($mwhere);
		if($this->agenhnum=='usera'){
			$obj = $obj->where('id','<>', $this->useaid); //用户不清空自己
			$obj = $obj->where('uid','<>', $this->userid);
		}
		$obj->delete();
		
		$dearra	= explode(',', 'flowlog,flowchao,flowcourss,flowbill,remind,todo,flowread,file');
		foreach($dearra as $tab)
			DB::table($tab)->where('cid', $this->companyid)->where('mtable', $this->mtable)->delete();
		
		//加入系统日志
		c('log', $this->useainfo)->add('清空数据','清空应用['.$this->agenhname.']的数据');
		
		return returnsuccess('数据清除成功');
	}
	
	
	/**
	*	获取单据操作菜单
	*/
	public function getOptmenu($mid)
	{
		$cbar	= $this->initdata($mid);
		if(!$cbar['success'])return $cbar;
		
		$barr = array();
		
		//单据提醒
		$isturn = objvalue($this->rs, 'isturn', 1);
		if($isturn ==1){
			
			if($this->isflow>0){
				if(!in_array($this->rs->status,[1,5]) && ($this->optid==$this->useaid or $this->nowaid==$this->useaid)){
					$barr[] = array('name'=>'作废申请','lx'=>'optm','optmid'=>-2,'issm'=>1,'islog'=>1,'type'=>0);
					$barr[] = array('name'=>'追加说明','lx'=>'optm','optmid'=>-3,'issm'=>1,'islog'=>1,'type'=>0);
				}
			}
			
			
			$optarr 	= $this->getNei('flowmenu')->getoptMenu($this->agentid);
			$devobj		= $this->getNei('devdata');
			foreach($optarr as $k=>$rs){
				$wherestr 	= $rs->wherestr;
				$bo 		= true;
				if(!isempt($wherestr)){
					$obj = $this->getModel()->where('id',$this->mid)->whereRaw($devobj->replacesql($wherestr, false));
					if($obj->count()==0)$bo=false;
				}
				if($bo){
					$upgcont	= '';
					if($rs->type==4)$upgcont = $this->reparr($rs->upgcont, $this->rs);
					$barr[] = array('name'=>'<font color="'.$rs->statuscolor.'">'.$rs->name.'</font>','lx'=>'optm','upgcont'=>$upgcont, 'issm'=>$rs->issm, 'type'=>$rs->type,'optmid'=>$rs->id);
				}
			}
			
			if(objvalue($this->agentinfo,'istxset',0)==1){
				if(!in_array($this->rs->status,[2,5])){
					$remindid 	= c('remind', $this->useainfo)->getmid($this->mtable, $this->mid);
					$remname 	= '设置';
					if($remindid>0)$remname  = '编辑';
					$barr[] = array('name'=>'单据提醒'.$remname.'..','remindid'=>$remindid,'lx'=>'remind');
				}
			}
			
		}
		
		if(objvalue($this->agentinfo,'ispl')==1 && $this->rs->status != 5){
			$barr[] = array('name'=>'评论','lx'=>'optm','optmid'=>-1,'issm'=>1,'islog'=>1,'type'=>0);
		}

		if($this->iseditqx()==1){
			$barr[] = array('name'=>'编辑','lx'=>'edit');
		}
		
		if($this->isdelqx()==1){
			$barr[] = array('name'=>'<font color=red>删除</font>','lx'=>'del');
		}
		
		return returnsuccess($barr);
	}
	
	/**
	*	单据操作菜单执行
	*/
	public function postOptmenu($mid, $optmid, $sm='')
	{
		$cbar	= $this->initdata($mid);
		if(!$cbar['success'])return $cbar;
		
		$optmrs		= new \StdClass();
		$optmrs->id = $optmid;
		$optmrs->islog 			= 0;
		$optmrs->actname 		= '';
		$optmrs->statusvalue 	= '0';
		$optmrs->statusname 	= '';
		$optmrs->statuscolor 	= '';
		$optmrs->upgcont 		= '';
		$optmrs->num 			= '';
		
		//评论
		if($optmid==-1){
			$optmrs->islog 		= 1;
			$optmrs->actname 	= '评论';
		}else if($optmid==-2){
			$optmrs->islog 		= 1;
			$optmrs->actname 	= '作废';
		}else if($optmid==-3){
			$optmrs->islog 		= 1;
			$optmrs->actname 	= '追加说明';	
		}else{
			$optmrs	= c('flowmenu', $this->useainfo)->getrs($optmid);
		}
		
		$islog	= $optmrs->islog;
		if($islog==1){
			$actname 	= $optmrs->actname ? : $optmrs->name;
			$this->addLog([
				'explain' 		=> $sm,
				'status' 		=> $optmrs->statusvalue,
				'statusname' 	=> $optmrs->statusname,
				'color' 		=> $optmrs->statuscolor,
			], $actname);
		}
		
		//作废
		if($optmid==-2){
			return $this->zuofei($sm);
		}
		
		if($optmid==-3){
			return $this->zhuijiaexplain($sm);
		}
		
		//更新内容
		$upgcont	= $optmrs->upgcont;
		if(!isempt($upgcont)){
			$devobj		= $this->getNei('devdata');
			$upgcont	= str_replace(' ', '', $upgcont);
			$upgcont 	= $devobj->replace($upgcont);
			$this->getModel()->where('id',$this->mid)->update(c('mysql')->strtoarr($upgcont));
		}
		
		//编号和通知设置的一样时触发通知
		$num 		= $optmrs->num;
		if(!isempt($num)){
			$rows 	= $this->getNei('agenttodo')->gettodonum($num, $this->agentid);
			foreach($rows as $k=>$rs)$this->agenttodosend($rs, $sm);
		}
		
		//评论
		if($optmrs->id==-1){
			$this->agenttodoping($sm);
			if(!in_array($this->optid, $this->todoaida))
				$this->nexttodo($this->optid, 'pinglun', $optmrs->actname, $sm); //通知给提交人
			
		}
		
		$this->flowactrun('flowoptmenu', $optmrs);

		return returnsuccess('处理成功');
	}
}