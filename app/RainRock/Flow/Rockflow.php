<?php
/**
*	工作流-核心文件
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flow;


use App\Model\Base\BaseModel;
use App\Model\Base\AgentModel;
use App\Model\Base\FlowbillModel;
use App\Model\Base\FlowlogModel;
use App\Model\Base\UseraModel;
use App\Model\Base\DeptModel;
use Rock;


class Rockflow
{
	use Rockflow_yingdata,Rockflow_authory,Rockflow_savedata,Rockflow_getfields,Rockflow_flowpipei;
	
	public $now;
	
	//应用编号
	public $agenhnum;
	
	//分组编号
	public $pnum;
	
	//应用名称
	public $agenhname;
	
	//应用ID
	public $agenhid;
	
	//应用信息agenh记录信息
	public $agenhinfo;
	
	//主应用信息
	public $agentinfo;
	
	//系统应用的ID
	public $agentid	= 0;
	
	//单位人员信息usera的记录
	public $useainfo;
	
	//元素字段
	public $fieldsArr;
	
	public $userid	= 0;
	
	//当前单据提交人
	public $optid;
	
	public $useaid	= 0;
	public $adminid	= 0;
	
	//当前用户信息
	public $useaname;
	public $adminname;
	
	public $isflow	= 0;
	
	//每页数量
	public $limit	= 10;
	
	public $sericnum;
	
	
	//单位Id
	public $companyid;
	
	public $companyinfo;
	
	//当前单据记录mid
	public $mid		= 0;
	
	//对应主表
	public $mtable 	= '';
	
	//当前单据对应flowbill记录
	public $billrs	= false;
	
	//当前单据记录
	public $rs;
	
	//单据保存前判断
	public function flowsavebefore($arr){return '';}
	
	//单据保存后处理
	public function flowsaveafter($arr){}
	
	//数据替换,$lx=0默认,1详情,2列表,3移动端列表
	public function flowreplacers($rs){return $rs;}
	
	//子表数据替换
	public function flowreplacerssub($rows){return $rows;}
	
	//录入页面默认数据
	public function flowinputdefault(){return array();}
	
	//默认提醒模版数据
	public function flowreminddata(){return array();}
	
	public function flowgetdetail(){return array();}
	
	//流程全部完成后调用
	protected function flowcheckfinsh($zt){}
	
	//默认是可以保存草稿的
	protected $flowisturnbool	= true;

	
	//条件过滤
	protected function flowbillwhere($obj, $atype){return false;}
	
	
	protected $nowars,$nowdrs,$nowuid,$nowname,$nowaid,$nowcompanyid;
	
	//审核为状态是1就算审核，也可以自定义
	protected $checkstartarr	= array(1);
	
	
	
	//字段名称字段
	protected function flowfieldsname(){return array();}
	
	protected function flowinputfields($farr){return false;}
	
	protected function flownowstatus($rs){return '';}
	
	//提交时触发
	protected function flowsubmit(){}	
	
	protected function flowinit(){}	
	
	//应用列表显示时,$lx信息0pc,1手机，
	public function flowlistview($lx){}	
	
	//详情页宽度
	protected $detailbodywidth 	= '680px';	
	protected $mwhere;	
	
	//是否可直接编辑表格
	public $edittablebool	= false;
	
	public $flowweixinarr	= array();
	
	public $todoaida		= array();
	
	
	
	public $statusdevarr	= array('待处理','同意','不同意','','','已作废'); //每个步骤处理默认状态
	
	public $statusbilarr	= array('待处理','已审核','不同意','','','已作废'); //全部处理完成状态
	public $statuscolarr	= array('blue','green','red','#ff6600','#526D08','#aaaaaa');
	
	public function __construct($num, $usea, $pnum='', $boqx=true)
    {
		$this->now		= nowdt();
        $this->agenhnum = $num; //应用编号
        $this->pnum 	= $pnum; //分组编号
		
		$this->useainfo = $usea;
		$this->userid 	= $this->useainfo->uid;
		$this->useaid 	= $this->useainfo->id;
		$this->adminid 	= $this->useainfo->id;
		$this->useaname	= $this->useainfo->name; //当前用户姓名
		$this->adminname= $this->useainfo->name; //当前用户姓名
		$this->companyid= $this->useainfo->cid;
		$this->companyinfo 	= $this->useainfo->company; //单位信息
		$this->agenhinfo	= BaseModel::agenhInfo($this->companyid, $num, $pnum);
		if(!$this->agenhinfo && $boqx)abort(404,'应用['.$num.']不存在哦');
		
		$this->mtable 	= $this->agenhinfo->agenhtable; //对应主表
		$this->isflow 	= $this->agenhinfo->isflow;
		$this->agenhname= $this->agenhinfo->name;
		$this->agenhid 	= $this->agenhinfo->id;
		$this->agentid 	= $this->agenhinfo->agentid;
		if($this->agentid>0)$this->agentinfo= AgentModel::find($this->agentid);
			
		$this->authoryobj 	= $this->getNei('authory');
		$this->rs 			= new \StdClass();
		$this->flowinit();
    }
	
	
	public function getNei($num)
	{
		return c($num, $this->useainfo);
	}
	
	public function changeStatus()
	{
		$this->statuscolarr[0] = '#888888';
		$this->statusbilarr[0] = '停用';
		$this->statusbilarr[1] = '启用';
	}
	
	public function flowactrun($act, $can1=null, $can2=null)
	{
		if(method_exists($this, $act)){
			return $this->$act($can1, $can2);
		}
	}
	
	public function getFlowobj($num)
	{
		return Rock::getFlow($num, $this->useainfo);
	}
	
	/**
	*	获取model，$nus应用编号
	*/
	public function getModel($nus='')
	{
		$obj	= null;
		if($nus=='')$nus = $this->agenhnum;
		$cls 	= '\App\Model\Agent\Rockagent_'.$nus.'';
		if(class_exists($cls))$obj 	= new $cls();
		return $obj;
	}
	
	public function getModeldata($nus='')
	{
		return $this->getModel()->where('cid', $this->companyid);
	}
	
	/**
	*	子表的Model
	*/
	public function getModels($tabs, $nus='')
	{
		$obj	= null;
		if($nus=='')$nus = $this->agenhnum;
		$cls 	= '\App\Model\Agent\Rockagents_'.$nus.'_'.$tabs.'';
		if(class_exists($cls))$obj 	= new $cls();
		return $obj;
	}
	
	
	/**
	*	初始化数据
	*/
	public function initdata($mid)
	{
		$this->rs		= $this->getData($mid, 0);
		if(!$this->rs)return returnerror('单据不存在');
		$this->optid	= objvalue($this->rs,'optid', $this->rs->aid);//提交人
		$this->nowaid	= $this->rs->aid;
		$this->nowars 	= UseraModel::where(['cid'=>$this->companyid,'id'=>$this->rs->aid])->first(); //当前记录单位用户信息
		$this->mwhere	= ['mtable'=> $this->mtable,'mid' => $this->mid];
		
		//基本信息
		if($this->nowars){
			$this->rs->base_name 		= $this->nowars->name;
			$this->rs->applyname 		= $this->nowars->name;
			$this->rs->base_deptname 	= $this->nowars->deptname;
			$this->nowuid	= $this->nowars->uid;
			$this->nowaid	= $this->nowars->id;
			$this->nowname	= $this->nowars->name;
			$this->nowcompanyid	= $this->nowars->cid;
			$this->nowdrs 		= DeptModel::find($this->nowars->deptid);
		}
		$this->rs->adminname = $this->adminname;
		
		$this->getFlowbill();
		if($this->billrs){
			$this->rs->base_sericnum = $this->billrs->sericnum;
			if(isempt($this->rs->base_name)){
				$this->rs->base_name 	 = $this->billrs->applyname;
				$this->rs->applyname 	 = $this->billrs->applyname;
				$this->rs->base_deptname = $this->billrs->applydeptname;
			}
		}
		return returnsuccess($this->rs);
	}
	
	/**
	*	获取数据
	*	$lx=0初始化,1编辑读取
	*/
	public function getData($mid, $lx=0)
	{
		$this->mid = $mid;
		if($mid<=0)return false;
		return $this->getModel()->where(['cid'=>$this->companyid,'id'=>$mid])->first();
	}
	
	/**
	*	更新主记录
	*/
	public function updateData($uarr, $mid=0)
	{
		if(!$uarr)return;
		if($mid==0)$mid =  	$this->mid;
		$obj	= $this->getData($mid);
		if(!$obj)return;
		foreach($uarr as $k=>$v){
			$this->rs->$k = $v;
			$obj->$k = $v;
		}
		if($mid==$this->mid && $this->billrs){
			$bos = false;
			if(isset($uarr['status'])){
				$this->billrs->status 	= $uarr['status'];
				$this->billrs->nstatus 	= $uarr['status'];
				$bos = true;
			}
			if(isset($uarr['isturn'])){
				$this->billrs->isturn = $uarr['isturn'];
				$bos = true;
			}
			if($bos)$this->billrs->save();
		}
		$obj->save();
		
		//状态的改变,子表也要更新
		if(isset($uarr['status'])){
			$zt 	= $uarr['status'];
			$tables = $this->agentinfo->tables;
			if(!isempt($tables)){
				$tablea = explode(',', $tables);
				foreach($tablea as $tab){
					$allfields 	= \Schema::getColumnListing($tab);
					if(in_array('status', $allfields)){
						$this->getModels($tab)->where('mid', $mid)->update(array(
							'status' => $zt
						));
					}
				}
			}
		}
	}
	
	
	/**
	*	添加到flowlog日志中
	*/
	public function addLog($arr=array(), $act='')
	{
		$bobj= c('base');
		$obj = new FlowlogModel();
		$obj->cid 		= $this->companyid;
		$obj->aid 		= $this->useainfo->id;
		$obj->uid 		= $this->useainfo->uid;
		$obj->checkname = $this->useainfo->name;
		$obj->mtable 	= $this->mtable;
		$obj->mid 		= $this->mid;
		$obj->actname 	= $act;
		$obj->courseid 	= 0;
		$obj->status 	= 1;
		$obj->optdt 	= nowdt();
		$obj->ip 		= $bobj->getclientip();
		$obj->web 		= $bobj->getbrowser();
		$obj->agenhnum	= $this->agenhnum;
		if($arr)foreach($arr as $k=>$v)$obj->$k = $v;
		$obj->save();
		$logid = $obj->id;
		$fileid= objvalue($obj,'fileid');
		if(!isempt($fileid)){
			$this->getNei('file')->savefile($fileid, 'flowlog', $logid);
		}
		return $logid;
	}
	
	/*
	*	获取操作记录
	*/
	public function getlog()
	{
		$rows = $this->getNei('flowlog')->getlog($this->mtable, $this->mid);
		return $rows;
	}
	
	
	/**
	*	添加提醒推送
	*	$gname 往哪个应用推送
	*/
	public function todo($aids, $title, $cont, $gname='', $params=array())
	{
		if(isempt($aids))return;
		if($title=='')$title = $this->agenhname;
		if($gname=='')$gname = $this->agenhname;
		
		if(contain($title, '{') && contain($title, '}'))
			$title = $this->reparr($title, $this->rs);
		if(contain($cont, '{') && contain($cont, '}'))
			$cont = $this->reparr($cont, $this->rs);
		
		$aids 	= $this->getNei('contain')->getaids($aids);
		$aida	= $this->getNei('todo')->adds($aids, $title, $cont, $this); //加到提醒todo表里
		if(!$aida)return;
		$url 	= arrvalue($params,'url', $this->getdetialurl());
		
		//是否有企业微信
		if($this->agenhinfo->wxtx==1 && $this->getNei('option')->iswxqy()==1){
			$wxarra  = $this->flowweixinarr;
			$wxarr	 = array(
				'title' 		=> $title,
				'description' 	=> $cont,
				'url' 			=> $url
			);
			$picurl  = objvalue($this->rs, 'fengmian');
			if(!isempt($picurl)){
				$wxarr['picurl'] = $this->getimgyuan($picurl);
			}
			foreach($wxarra as $k=>$v)$wxarr[$k]=$v;
			
			$barr = $this->getNei('Wxqy:index')->sendxiao($aida, ''.$gname.',办公助手', $wxarr);
			if($barr['errcode']!=0)
			$this->getNei('log')->adderror('企业微信',''.$barr['errcode'].':'.$barr['msg'].'');
		}
		//邮件提醒
		if($this->agenhinfo->emtx==1){
			
		}
		//app提醒
		if($this->agenhinfo->mctx==1){
			
		}
		
		//推送到REIM客户端上
		$this->getNei('reim')->pushagenh($aida, $this->agenhid, $title, $cont, $url);
		
		$this->todoaida		= array_merge($this->todoaida, $aida);
		
		$this->flowweixinarr=array();
		return $aida;
	}
	
	public function getdetialurl($num='', $mid=0)
	{
		if($num=='')$num = $this->agenhnum;
		if($mid==0)$mid = $this->mid;
		$url = config('app.url').'/detail/'.$this->companyinfo->num.'/'.$num.'/'.$mid.'';
		return $url;
	}
	
	/**
	*	发通知提醒
	*/
	public function nexttodo($aids, $type, $act='', $sm='')
	{
		if(isempt($aids))return;
		$cont = '';
		$gname= '';
		$summary= $this->getsummary();
		if($type=='submit' || $type=='next' || $type == 'cuiban'){
			$cont = '你有['.$this->nowname.']的['.$this->agenhname.',单号:'.$this->sericnum.']，需要处理';
			if($sm!='')$cont.='，说明:'.$sm.'';
			$gname= '流程待办';
		}
		if($type=='zhuanban'){
			$cont = ''.$this->adminname.'将单据['.$this->agenhname.',单号:'.$this->sericnum.']，转移给你';
			if($sm!='')$cont.='，说明:'.$sm.'';
			$gname= '流程待办';
		}
		
		//不同意
		if($type == 'nothrough'){
			$cont = '你提交['.$this->agenhname.',单号:'.$this->sericnum.']'.$this->adminname.'处理['.$act.']，原因:'.$sm.'';
			$gname= '申请未通过';
		}
		
		//退出退回
		if($type == 'yitui'){
			$cont = '你提交['.$this->agenhname.',单号:'.$this->sericnum.']'.$this->adminname.'处理['.$act.']，说明:'.$sm.'';
			$gname= '我的申请';
		}
		
		//完成
		if($type == 'finish'){
			$cont = '你提交的['.$this->agenhname.',单号:'.$this->sericnum.']已全部处理完成';
		}
		
		//评论
		if($type=='pinglun'){
			$cont  = ''.$this->adminname.''.$act.'你的['.$this->agenhname.']单据，说明:'.$sm.'';
		}
		
		//追加说明
		if($type == 'zhui'){
			$cont = ''.$this->adminname.'追加单据说明['.$this->agenhname.',单号:'.$this->sericnum.']，说明:['.$sm.']';
		}
		
		//提交抄送
		if($type == 'chaos'){
			$str1 = '';
			if($this->isflow>0)$str1=',单号'.$this->sericnum.'';
			$cont = ''.$this->adminname.''.$act.'['.$this->agenhname.''.$str1.']的单据给你';
			if(!isempt($summary))$cont.='，摘要“'.$summary.'”';
		}
		
		
		if($cont!='')$this->todo($aids, $gname, $cont);
	}
	
	public function reparr($str, $arr)
	{
		if(isempt($str))return '';
		$bars	= Rock::matcharr($str);
		$dvarr	= c('devdata', $this->useainfo)->getreparr();
		foreach($dvarr as $k=>$v)if(!isset($arr->$k))$arr->$k = $v;
		$s 		= $str;
		foreach($bars as $nsts){
			$nts = objvalue($arr, $nsts);
			$s	= str_replace('{'.$nsts.'}', $nts, $s);
		}
		return $s;
	}
	
	
	/**
	*	获取摘要
	*/
	public function getsummary()
	{
		return $this->reparr($this->agentinfo->summary, $this->rs);
	}
	
	/**
	*	从单据通知上发送提醒通知
	*/
	public function agenttodosend($act, $sm='')
	{
		$data 			= $this->flowreplacers($this->rs, 1);
		$data->inputsm	= $sm; //录入的说明
		$data->adminname= $this->adminname;
		if(!is_string($act)){
			$this->agenttodosends($act, $data);
			return;
		}
		$rows 	= $this->getNei('agenttodo')->gettodolist($act, $this->agentid, $this->agenhid);
		foreach($rows as $k=>$rs){
			$wherestr = $rs->wherestr;
			if(!isempt($wherestr)){
				$to 	= $this->getModel()->where('id', $this->mid)->whereRaw($wherestr)->count();
				if($to==0)continue;
			}
			$this->agenttodosends($rs, $data);
		}			
	}
	private function agenttodosends($rs, $data)
	{
		$aids = '';
		if($rs->toturn==1){
			$aid = (int)objvalue($data,'optid','0');
			if($aid>0)$aids .= ','.$aid.'';
		}
		if($rs->toapply==1){
			$aid = (int)objvalue($data,'aid','0');
			if($aid>0)$aids .= ','.$aid.'';
		}
		//上级主管
		if($this->nowars){
			if($rs->tosuper==1){
				$sid	=$this->nowars->superid;
				if(!isempt($sid))$aids .= ','.$sid.'';
			}
			if($rs->tosuperall==1){
				$sid	=$this->nowars->superpath;
				if(!isempt($sid))$aids .= ','.$sid.'';
			}
		}
		//流程参与人
		if($rs->tocourse==1){
			if($this->billrs){
				$sid	=$this->billrs->allcheckid;
			}
		}
		
		//主表上字段
		$todofields	= $rs->todofields;
		if(!isempt($todofields)){
			$todoarr= explode(',', $todofields);
			foreach($todoarr as $fid){
				if($fid=='')continue;
				$sid 	= objvalue($data,$fid);
				if(!isempt($sid))$aids .= ','.$sid.'';
			}
		}
		
		$receid 	= objvalue($rs, 'receid');
		if(!isempt($receid))$aids .= ','.$receid.'';
		
		if($aids=='')return;
		$aids	= substr($aids, 1);
		
		$title 	= $this->reparr($rs->name, $data);
		$cont 	= $this->reparr($rs->summary, $data);
		
		$this->todo($aids, $title, $cont);
	}
	
	/**
	*	根据设置编号发送通知
	*/
	public function agenttodonum($num, $receid, $sm='')
	{
		$torsa = $this->getNei('agenttodo')->gettodonum($num, $this->agentid);
		if($torsa){
			foreach($torsa as $tors){
				$tors->receid = $receid;
				$this->agenttodosend($tors, $sm);
			}
		}
	}
	
	/**
	*	评论时推送
	*/
	public function agenttodoping($sm='')
	{
		$this->agenttodosend('boping', $sm);
	}
	
	public function getturnbool()
	{
		return $this->flowisturnbool;
	}
	
	/**
	*	加入异步队列
	*/
	public function addqueue($mstr, $act, $params='', $title='')
	{
		if($title=='')$title = $this->agenhinfo->name;
		$cbarr = $this->getNei('Queue:start')->push($mstr,$act, $params, $this->agenhinfo->atype, $title);
		return $cbarr;
	}
}