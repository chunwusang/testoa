<?php
/**
*	工作流-核心文件_保存数据
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flow;

use App\Model\Base\FlowbillModel;
use App\Model\Base\FlowcourssModel;
use App\Model\Base\FlowreadModel;
use App\Model\Base\FlowchecksModel;
use App\Model\Base\FlowchaoModel;
use App\Model\Base\AgentfieldsModel;
use App\Model\Base\UseraModel;
use Schema;
use Request;
use Rock;

trait Rockflow_savedata
{
	protected $isdaorubool	= false;
	protected $isfujian		= 0; //判断是否有附件
	
	//旧的数据
	public $dataold;
	
	//提交过来子表数据
	public $subtabledata	= array();
	
	
	public function flowsavebeforesubdata($zb, $sdata, $arr){return false;}
	
	
	/**
	*	主表数据保存
	*/
	public function saveData($mid, $data, $zidanarr=array(), $isdr=false)
	{
		$obj	= false;
		$this->isdaorubool	= $isdr; //是不是导入
		if($mid>0){
			$obj 			= $this->getModel()->find($mid);
			if(!$obj)return returnerror('记录'.$mid.'不存在了');
			$this->dataold 	= clone $obj;
		}
		$this->mid		= $mid;
		
		if(!$obj)$obj 	= $this->getModel();
		$isturn 		= (int)arrvalue($data,'isturn', 0);
		
		if(!$zidanarr)$zidanarr		= Schema::getColumnListing($this->mtable);//获取到主表全部字段
		$isadd			= false;
		if($mid==0){
			$obj->cid 	= $this->companyid;
			$obj->status= 0;
			
			in_array('aid', $zidanarr) and $obj->aid 	= $this->useainfo->id;
			in_array('uid', $zidanarr) and $obj->uid 	= $this->useainfo->uid;
			in_array('createid', $zidanarr) and $obj->createid 		= $this->useainfo->id;
			in_array('createname', $zidanarr) and $obj->createname 	= $this->useainfo->name;
			in_array('createdt', $zidanarr) and $obj->createdt 		= nowdt();
			in_array('applydt', $zidanarr) and $obj->applydt 		= nowdt('dt');
			$isadd 		= true;
		}
		
		if(!in_array('isturn', $zidanarr))unset($data['isturn']);
		
		//相关文件Id
		$fileid	= arrvalue($data, 'xiangfileid');
		if(!isempt($fileid))$this->isfujian = 1;//有附件的
		
		unset($data['xiangfileid']);
		unset($data['sysupfile']);
		
		foreach($data as $k=>$v){
			$kj = substr($k,0,5);
			if($kj=='temp_' || $kj=='base_')continue;
			
			if(!in_array($k, $zidanarr))
				return returnerror(''.$this->mtable.'主表上'.$k.'字段不存在');
			
			$obj->$k 	= $v;
		}
		
		if(isset($data['applydt']) && isempt($data['applydt']))$obj->applydt 	= nowdt('dt');
		
		
		if(isset($obj->aid)){
			if(!$obj->aid) $obj->aid = 0;
			$nowusea	= UseraModel::find($obj->aid);
			in_array('applyname', $zidanarr) and $obj->applyname = $nowusea->name;
		}
		
		in_array('isturn', $zidanarr) and $obj->isturn 	= $isturn;
		
		
		if($this->isflow>0){
			$obj->status = 0;
		}else{
			$obj->status = (int)arrvalue($data,'status', 1);
		}
		
		if(!in_array('status', $zidanarr))unset($obj->status);
	
		in_array('optdt', $zidanarr) and $obj->optdt = nowdt();
		in_array('optid', $zidanarr) and $obj->optid = $this->useainfo->id;
		in_array('optname', $zidanarr) and $obj->optname = $this->useainfo->name;
			
		//自由流程
		$snextbo2 = false;
		
		
		//清空已读
		FlowreadModel::where([
			'cid'	=> $this->companyid,
			'mtable'=> $this->mtable,
			'mid'	=> $this->mid,
		])->delete();
		
		//删除原来预先设置
		FlowchecksModel::where([
			'cid'	=> $this->companyid,
			'mtable'=> $this->mtable,
			'mid'	=> $this->mid,
			'addlx'	=> $this->isflow
		])->delete();
		
	
		//保存前判断
		$msg 	= $this->flowsavebefore($obj, $mid);
		if(is_string($msg) && !isempt($msg))return returnerror($msg);
		
		if(is_array($msg)){
			$osara= arrvalue($msg, 'data'); //保存其他字段
			if($osara)foreach($osara as $k1=>$v1)$obj->$k1 = $v1;
			
			$nosave= arrvalue($msg, 'nosave');//不保存
			if($nosave){
				$nosavea = explode(',', $nosave);
				foreach($nosavea as $k2)unset($obj->$k2);
			}
			$msg = arrvalue($msg, 'msg');
			if(!isempt($msg))return returnerror($msg);
		}
		
		$obj->save(); //保存
		
		$this->mid 		= $obj->id;
		$mid			= $obj->id;
		$this->rs 		= $obj;

		//自由流程
		if($snextbo2){
			$this->addcheckname($nextcourseid, $nextcheckid, $nextcheckname, 2);
		}
		
		
		//相关文件保存
		$this->getNei('file')->savefile($fileid, $this->mtable, $mid);
		if($this->isflow>0)$this->saveFlowBill();

		$this->addLog(false, ($isturn==0)?'保存':'提交');
		
		//初始化数据
		$this->initdata($this->mid);
		
		
		//选择下一步审核人判断
		if($this->isflow==1){
			$nowcourse	= $this->getflowfirstinput();
			if($nowcourse && $nowcourse['checktype']=='change'){
				$nextna   = Request::input('sys_nextname');
				$nextnaid = Request::input('sys_nextnameid');
				if(isempt($nextnaid))return returnerror('必须选择'.$nowcourse['name'].'处理人');
				$this->addcheckname($nowcourse['id'], $nextnaid, $nextna, 1);
			}
		}
		
		
		//从新提交让可在审核
		if(!$isdr){
			FlowcourssModel::where('mtable', $this->mtable)
					->where('mid', $this->mid)
					->update([
						'checkstatus'		=>0,
						'checksm' 			=> '',
						'checkstatustext' 	=> '',
						'checkstatuscolor' 	=> '',
						'checkdate' 		=> null
					]);
			
			//保存抄送
			$chaoid		= Request::input('sys_chaoid');
			$chaoname	= Request::input('sys_chaoname');
			if(!isempt($chaoid) && !isempt($chaoname)){
				$this->chaosongsave($chaoid, $chaoname);
				if($isturn==1)$this->nexttodo($chaoid, 'chaos','抄送');
			}else{
				$this->chaosongdel();
			}
		}
		
		//提交到流程
		if($this->isflow > 0){
			$farr = $this->getflow(true);
			//推送给待办人员
			if($isturn==1){
				$this->nexttodo($farr['nowcheckid'], 'submit');
			}
		}

		//提交时触发通知
		if($isturn==1 || !in_array('isturn', $zidanarr)){
			$this->flowsubmit();
			$this->agenttodosend($isadd ? 'boturn' : 'boedit');
		}
		
		$this->flowsaveafter($obj, $mid);
		
		return returnsuccess($mid);
	}
	
	
	//保存抄送
	private function chaosongsave($csid, $csname)
	{
		$obj = FlowchaoModel::where('mtable', $this->mtable)->where('mid', $this->mid)->first();
		if(!$obj)$obj = new FlowchaoModel();
		$billid 	= (int)objvalue($this->billrs,'id', '0');
		$obj->cid 	= $this->companyid;
		$obj->uid = $this->userid;
		$obj->aid = $this->useaid;
		$obj->billid = $billid;
		$obj->agentid = $this->agentid;
		$obj->mtable = $this->mtable;
		$obj->mid 	= $this->mid;
		$obj->chaoname 	= $csname;
		$obj->chaoid 	= $csid;
		$obj->optdt = nowdt();
		$obj->save();
		
	}
	private function chaosongdel()
	{
		FlowchaoModel::where('mtable', $this->mtable)->where('mid', $this->mid)->delete();
	}
	
	/**
	*	保存到单据表
	*/
	public function saveFlowBill($mid=0, $rs=null)
	{
		$isadd 	= false;
		if($mid==0)$mid = $this->mid;
		if($rs==null)$rs = $this->rs;
		$aid 	= (int)objvalue($rs, 'aid','0');
		if($aid==0)return;

		$obj 	= FlowbillModel::where('mtable', $this->mtable)->where('mid', $mid)->first();
		if(!$obj){
			$obj 	= new FlowbillModel();
			$isadd 	= true;
		}
		if($isadd){
			$dt1		= '';
			if(isset($rs->applydt))$dt1 = $rs->applydt;
			if(isempt($dt1))$dt1 = 	objvalue($rs,'createdt', 
										objvalue($rs,'optdt', nowdt('dt')));
			
			$obj->mtable 	= $this->mtable;
			$obj->mid 		= $mid;
			$obj->cid 		= $rs->cid;
			$obj->aid 		= $rs->aid;
			$obj->uid 		= $rs->uid;
			$obj->applydt 	= substr($dt1, 0, 10);
			$obj->nstatus 	= 0;
			$obj->sericnum 	= $this->createnum($obj->applydt);
		}
		
		$obj->aid 			= $rs->aid;
		$this->sericnum		= $obj->sericnum;
		$obj->status 		= $rs->status;
		$obj->nstatus 		= $rs->status;
		$obj->isturn 		= $rs->isturn;
		$obj->agenhnum		= $this->agenhnum;
		$obj->agentid		= $this->agentid;
		$obj->agenhname		= $this->agenhinfo->name;
		
		$nowars	= UseraModel::find($aid);
		if($nowars){
			$obj->applyname		= $nowars->name;  //申请人
			$obj->applydeptid	= $nowars->deptid;
			$obj->applydeptname	= $nowars->deptname;
		}
		if(isset($rs->applydeptid)){
			
		}
		
		$obj->optid			= $this->adminid;
		$obj->optname		= $this->adminname;

		$obj->save();
		if($mid == $this->mid)$this->billrs 		= $obj;
		return $obj;
	}
	
	/**
	*	创建流程单号
	*/
	public function createnum($appdt='')
	{
		$num = $this->agentinfo->sericnum;
		if(isempt($num))$num='XH-Ymd-';
		if(isempt($appdt))$appdt 	= objvalue($this->rs,'applydt', nowdt('dt'));
		$apdt 	= str_replace('-','', $appdt);
		$num	= str_replace('Ymd',$apdt, $num);
		return $this->getNei('mysql')->sericnum($num,'flowbill','sericnum',3);
	}
	
	
	/**
	*	保存bill流程信息
	*/
	public function billflowsave($sarr)
	{
		if(!$sarr)return false;
		$bo  = false;
		foreach($sarr as $k=>$v){
			if(!$bo && $this->billrs->$k != $v)$bo = true;
			$this->billrs->$k = $v;
		}
		$this->billrs->save();
		return $bo;
	}
	
	/**
	*	获取单据bill，没有就保存
	*/
	public function getFlowbill()
	{
		$this->billrs 	= FlowbillModel::where($this->mwhere)->first();
		if(!$this->billrs && $this->isflow>0)$this->saveFlowBill();
		$isbo	= false;
		if($this->billrs && (
			$this->rs->status != $this->billrs->status || 
			$this->rs->isturn != $this->billrs->isturn
		)){
			$this->billrs->status = $this->rs->status;
			$this->billrs->isturn = $this->rs->isturn;
			$isbo = true;
		}
		if($this->billrs){
			if($this->nowars)if(isempt($this->billrs->applyname) || isempt($this->billrs->applydeptname)){
				$this->billrs->applyname 	 = $this->rs->base_name;
				$this->billrs->applydeptname = $this->rs->base_deptname;
				if($this->nowars)$this->billrs->applydeptid 	 = $this->nowars->deptid;
				$isbo = true;
			}
		}
		if($isbo)$this->billrs->save();
		if($this->billrs){
			$this->sericnum	= $this->billrs->sericnum;
		}
	}
	
	/**
	*	录入初始化，设置数据源等，返回字段
	*	$glx=0 录入页面，1读取数据源
	*/
	public function inputfieldsarr($farr, $data, $glx=0)
	{
		$devobj 	= $this->getNei('devdata');
		$store 		= array();
		
		//设置默认值
		if(!$data && $glx==0){
			$data = new \StdClass();
			foreach($farr as $k=>$rs){
				$fid = $rs->fields;
				$flx = $rs->fieldstype;
				
				if($rs->iszb==0){
					$dev 	    = $devobj->replace($rs->dev);
					$deva 		= explode('|', $dev);
					$data->$fid = arrvalue($deva, 0);
					//多扩展字段
					if(!isempt($rs->data)){
						$fieds 	= explode(',', $rs->data);
						$fieds1 = '';
						if(substr($flx,0,6)=='change'){
							$fieds1 = $fieds[0];
						}
						if(substr($flx,0,10)=='selectdata'){
							$fieds1	= arrvalue($fieds, 1);
						}
						if(!isempt($fieds1)){
							$data->$fieds1= arrvalue($deva, 1);
							$deff 	= 'def_'.$fieds1.'';
							if(Request::has($deff)){
								$data->$fieds1 = Request::get($deff);
							}
						}
					}
					//url参数传默认值
					$deff 	= 'def_'.$fid.'';
					if(Request::has($deff)){
						$data->$fid = Request::get($deff);
					}
				}
			}
			//默认值
			$devarr	= $this->flowinputdefault();
			if($devarr)foreach($devarr as $k1=>$v1)$data->$k1 = $v1;
			$this->rs = $data;
		}
		
		//不够主表子表，如果主表字段和子表字段相同就有问题了
		foreach($farr as $k=>$rs){
			$fid = $rs->fields;
			if($rs->fieldstype=='auto'){
				$teace = 'inputAuto_'.$rs->fields.'';
				$farr[$k]->autoinput = $this->flowactrun($teace); //自定义的
			}
			
			//数据源处理，下拉框，系统下拉框，多复选框，单选框才有数据源，不够主表和子表
			$stov = $devobj->getStore($rs, $this, $data, $glx);
			if($stov!==false)$store[$fid] = $stov;
		}
		
		$farrs = $this->flowinputfields($farr);
		if($farrs)$farr = $farrs;
		
		return array(
			'fieldsarr' => $farr,
			'store'		=> $store
		);
	}
	
	
	/**
	*	获取某个元素数据源
	*/
	public function getinputStore($fields)
	{
		$item		= AgentfieldsModel::where([
			'agentid' => $this->agentid,
			'fields'  => $fields,
		])->first();
		$devobj 	= $this->getNei('devdata');
		$data 		= new \StdClass();
		$stov 		= $devobj->getStore($item, $this, $data);
		return $stov;
	}
	
	
	
	
	/**
	*	导入数据库
	*/
	public function post_daorudata($request)
	{
		$rows 		= $this->getFieldsArr('daoru');
		if(!$rows)return returnerror('没有导入的字段');
		
		$fields 	= $fieldss = '';
		$onlyfield	= array();
		$fieldsobj 	= array();
		foreach($rows as $k=>$rs){
			$fields.=','.$rs->fields.'';
			if($rs->isbt==1)$fieldss.=','.$rs->fields.'';
			$fieldsobj[$rs->fields] = $rs;
			//if($rs['isonly']=='1')$onlyfield[] = $rs['fields']; //唯一字段
		}
		$fields = substr($fields, 1);
		if($fieldss!='')$fieldss = substr($fieldss,1);
		
		$data  	= c('html')->importdata($fields, $fieldss,$request->input('importcont')); //获取提交过来要导入的数据库
		if(!$data)return returnerror('没有可导入的数据,注意*是必填的哦');
		
		$cdata	= $this->flowactrun('flowdaorubefore', $data);
		if($cdata){
			if(is_string($cdata))return returnerror($cdata);
		}
		if(is_array($cdata))$data = $cdata;
		$ldata 	= $data;
		
		//开始往数据库添加数据
		$zidanarr	= Schema::getColumnListing($this->mtable);//获取到主表全部字段
		$oi 		= 0;
		$dorudat	= array();
		
		foreach($ldata as $k=>$rs){
			foreach($rs as $k1=>$v1)if(isset($fieldsobj[$k1])){
				$frs  =  $fieldsobj[$k1];
				if($frs->fieldstype=='datetime' || $frs->fieldstype=='date'){
					if(isempt($v1))$rs[$k1]=null;//日期是空的
				}
			}
			$barr = $this->saveData(0, $rs, $zidanarr, true);
			if(!$barr['success'])return returnerror('数据行['.($k+1).']错误:'.$barr['msg'].'');
			
			$oi++;
			$dorudat[] = $this->rs;
		}
		
		if($oi>0)$this->flowactrun('flowdaoruafter', $dorudat);
		
		return returnsuccess('成功导入'.$oi.'条');
	}
	
	
}