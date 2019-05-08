<?php
/**
*	工作流-核心文件_流程匹配
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flow;

use App\Model\Base\FlowcourseModel;
use App\Model\Base\FlowcourssModel;
use App\Model\Base\FlowchecksModel;
use App\Model\Base\UseraModel;
use Illuminate\Http\Request;
use Rock;

trait Rockflow_flowpipei
{
	
	public function iscreateflow()
	{
		$status 		= $this->rs->status;
		$isturn 		= $this->rs->isturn;
		if($isturn==1 && $status!=5){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	*	录入时获取第一步
	*/
	public function getflowfirstinput()
	{
		if($this->mid==0){
			$das		= new \StdClass();
			$das->id 	= 0;
			$das->status = 0;
			$das->isturn = 1;
			$lrows		= $this->getflowpipei($this->useainfo, $das);
		}else{
			$lrows		= $this->getflowpipei($this->nowars, $this->rs);
		}
		$boss = false;
		if($lrows){
			$boss = $lrows[0];
			if($boss['checktype']=='change'){
				$cuanwe	= [
					'cid'		=> $this->companyid,
					'courseid'	=> $boss['id'],
					'mtable'	=> $this->mtable,
					'mid'		=> $this->mid,
					'addlx'		=> 1
				];
				$frs = FlowchecksModel::where($cuanwe)->first();
				if($frs){
					$boss['checkid'] 	= $frs['checkid'];
					$boss['checkname'] 	= $frs['checkname'];
				}else{
					$boss['checkid'] 	= '';
					$boss['checkname'] 	= '';
				}
			}
		}
		return $boss;
	}
	
	/**
	*	匹配流程流程
	*/
	public function getflowpipei($usea, $billrs)
	{
		$status 		= $billrs->status;
		$isturn 		= $billrs->isturn;
		$mid			= $billrs->id;
		
		$lrows						= array();
		$this->showcoursearr 		= array(); //流程信息
		$data = FlowcourseModel::where('cid', $this->companyid)
				->where('agenhid', $this->agenhid)
				->where('status', 1)
				->orderByRaw('pid,sort')
				->get();
		
		$dapar	= array();
		$xunibu	= array();
		if($data)foreach($data as $k=>$rs){
			$dsts = $rs->toArray();
			$dapar[$rs->pid][] = $dsts;
			$this->showcoursearr[$rs->id]= $dsts;
			if(!$xunibu)$xunibu	= $dsts;//虚拟步骤
		}
		//虚拟步骤
		if($xunibu){
			$xunibu['name'] 	= '申请人';
			$xunibu['checktype']= 'apply';
			$xunibu['checkshu'] = 1;
			$xunibu['iszb'] 	= 0;
			$xunibu['isqm'] 	= 0;
			$xunibu['checktypeid'] 		= '';
			$xunibu['checktypename'] 	= '';
			$xunibu['courseact'] 		= '流转,退回到自己';
			for($i=0;$i<$k;$i++){
				$snar	= $xunibu;
				$snar['id'] = 0-$i-1;
				$this->showcoursearr[$snar['id']] 	= $snar;
			}
		}
		
		//需要是提交的并且未审核未作废的，都没有设置条件以最后一个流程为准
		if($isturn==1 && !in_array($status, [1,5])){
			
			
			
			$neobj	= $this->getNei('contain');
			$devd	= $this->getNei('devdata');
			foreach($dapar as $pid=>$rows){
				$neo	= $rows[0];
				if(!$lrows)$lrows = $rows;
				$receid	= $neo['receid'];//第一步适用对象
				$chwe	= $neo['checkwhere'];
				if(isempt($receid) && isempt($chwe)){
					$lrows	= $rows; 
				}else{
					if(!isempt($receid)){
						$bo = $neobj->iscontain($receid, $usea);
						if(!$bo)continue;
					}
					if(!isempt($chwe)){
						$to = $this->getModel()->
								where('id', $mid)
								->whereRaw($devd->replacesql($chwe))
								->count();
						if($to==0)continue;
					}
					$lrows	= $rows;
					break;
				}
			}
			//判断审核条件
			if($lrows){
				$lrowsa = $lrows;
				foreach($lrowsa as $k=>$rs){
					if($k==0)continue; //第一步上面已经判断过了
					$receid	= $rs['receid'];
					$chwe	= $rs['checkwhere'];
					if(!isempt($receid)){
						$bo = $neobj->iscontain($receid, $usea);
						if(!$bo){unset($lrows[$k]);continue;}
					}
					if(!isempt($chwe)){
						$to = $this->getModel()->
								where('id', $mid)
								->whereRaw($devd->replacesql($chwe))
								->count();
						if($to==0){unset($lrows[$k]);continue;}
					}
				}
			}
		}
		return $lrows;
	}
	
	/**
	*	获取流程
	*/
	public function getflow($sbo=false)
	{
		$this->flowarr 	= array();
		$status 		= $this->rs->status;
		$isturn 		= $this->rs->isturn;
		$lrows			= $this->getflowpipei($this->nowars, $this->rs); //匹配
		$coursearr		= $this->showcoursearr;
		
		$nowcheckid			= $nowcheckname = $allcheckid = $nowstatus = '';
		$this->nowcourse	= array();
		$this->nextcourse	= array();
		
		$allcheckids		= array();
		$this->flowarrall	= array();
		if($isturn==1 && !in_array($status, [1,5])){
			$ssid	= '0';
			$step	= 0;
			
			
			if($lrows){
				//读取转办记录
				$this->zhuangbantrows	= FlowchecksModel::where([
								'cid'	=> $this->companyid,
								'mtable'=> $this->mtable,
								'mid'	=> $this->mid,
								'addlx'	=> 4
							])->get();
				foreach($lrows as $k=>$rs){
					$uarr 		= $this->getcheckname($rs);	
					$step++;
					$checkid	= $uarr[0];
					$checkname	= $uarr[1];
					$ischeck 	= 0;
					$checkids	= $checknames = '';
					
					$rs['checkid'] 		= $checkid;
					$rs['checkname'] 	= $checkname;
					$rs['step']			= $step;
					
					$coursearr[$rs['id']]	= $rs;
					$this->flowarr[]		= $rs;
					$ssid.=','.$this->savecourss($rs);
					
				}
			}
			//删除多余的步骤
			FlowcourssModel::where($this->mwhere)
				->where('checkstatus', 0)
				->whereRaw('id not in('.$ssid.')')
				->delete();
			
			//已处理更新为无效记录
			FlowcourssModel::where($this->mwhere)
				->whereRaw('checkstatus<>0 and id not in('.$ssid.')')
				->update(['valid'=>0]);
				
		}
		
		//表中读取审核信息
		$lorows	= FlowcourssModel::where($this->mwhere)->where('valid', 1)->orderBy('step')->get();
				
		$lcarr	= array(); //每一步需要多少人审核保存
		foreach($lorows->toArray() as $k=>$rs){
			$courseid 	= $rs['courseid'];
			$rs['crs'] 	= $coursearr[$courseid];
			$lcarr[$courseid][] = $rs;
		}
		
		//判断是否已审核了
		if($this->iscreateflow()){
			//$lcarr每个步骤对应审核人可以是多个
			$useaobj 		= $this->getNei('usera');
			foreach($lcarr as $cid=>$crows){
				$crs 		= $crows[0]['crs'];
				$checkshu	= $crs['checkshu']; //至少审核人数
				$ischeck 	= 0;
				$yshu 		= 0; //已审核数
				$zshu 		= 0; //总审核人数
				$nzts		= 0;
				$steparr	= array();
				$checkids 	= $checknames = '';
				$dsara		= array();//已审核的步骤人
				foreach($crows as $k=>$rs){
					$zshu++;
					$zt 	= $rs['checkstatus'];
					$csid 	= $rs['checkid'];
					$chbo 	= false;
					if(in_array($zt, $this->checkstartarr)){
						$yshu++;
						$chbo 	 = true;	
					}else{
						if($csid>0){
							$checkids.=','.$csid.'';
							$checknames.=','.$rs['checkname'].'';
						}	
					}
					
					//0是还没有审核的
					if($zt==0){
						$rs['checkstatustext'] = '';
						$rs['checkstatuscolor'] = '';
						$rs['checksm'] = '';
						$rs['checkdate'] = '';
						$rs['checkqmimg'] = '';
					}
					
					$rs['ars']['face']  = '/images/noface.png';
					if($csid>0 && !in_array($csid, $allcheckids)){
						$allcheckids[] 	= $csid;
						$rs['ars'] 		= $useaobj->getuserainfo($csid,1);//人员信息
					}
					
					$rs['checkstatustext']	= $this->getcheckstatustext($rs['checkstatus'],$rs['checkstatustext'],$rs['checkstatuscolor']);
					
					if($zt==2){
						$nowstatus	= $rs['checkname'].$rs['checkstatustext'];
					}
					if($zt==0 && $status==2)continue;//不同意，不显示其他待审核的
					if($chbo)$dsara[] = $rs;
					$steparr[] = $rs;
				}
				if($checkids!=''){
					$checkids = substr($checkids, 1);
					$checknames = substr($checknames, 1);
				}
				
				if($checkshu==0 || $checkshu>$zshu)$checkshu = $zshu;
				if($yshu>=$checkshu)$ischeck = 1; //已审核大于总数，就说明审核了
				$crs['ischeck'] = $ischeck;
				$crs['isnow']	= 0;
				
				if($status==1 && $ischeck==0)continue;
				
				//如果多人的审批，不需要他就不用显示了
				if($ischeck==1 && $dsara)$steparr = $dsara;
				
				//下一步的
				if($this->nextcourse===false)$this->nextcourse = $crs;
				
				//当前步骤信息
				if($ischeck==0 && !$this->nowcourse){
					$crs['isnow']	 = 1;
					$this->nowcourse = $crs; //当前审核步骤
					$this->nextcourse= false;
					
					
					$nowcheckid 	= $checkids;
					$nowcheckname 	= $checknames;
					if(!in_array($status, [2,5]))$nowstatus		= '待<font color=blue>'.$checknames.'</font>处理';
				}
				$style 		  = '';
				if($crs['isnow']==1){
					$style='font-weight:bold';
				}elseif($ischeck==0){
					$style='color:#aaaaaa';
				}
				$crs['style'] 	= $style;
				
				$this->flowarrall[] = array(
					'crs' 		=> $crs,
					'steparr'	=> $steparr
				);
			}
			
			$allcheckid	= join(',', $allcheckids);
			$arrbill['allcheckid'] 		= $allcheckid;
		}else{
			$nowstatus = $this->getnowstatus($this->rs);
			if($isturn==0)$arrbill['allcheckid'] = ''; //没有提交
		}

		$arrbill['nowcheckid'] 		= $nowcheckid;
		$arrbill['nowcheckname']	= $nowcheckname;
		$arrbill['nowstatus']		= $nowstatus;
		
		if($sbo)$this->billflowsave($arrbill);
		
		return $arrbill;
	}
	
	//审批的状态
	private function getcheckstatustext($zt, $txt, $col)
	{
		if(isempt($txt))$txt = arrvalue($this->statusdevarr, $zt);
		if(isempt($col))$col = arrvalue($this->statuscolarr, $zt);
		return '<font color='.$col.'>'.$txt.'</font>';
	}
	
	
	//获取审核人
	private function getcheckname($crs)
	{
		$type		= $crs['checktype'];
		$typename	= $crs['checktypename'];
		$typeid		= $crs['checktypeid'];
		$cuid 		= $name = '';
		$courseid 	= $crs['id'];
		
		//上步指定
		if($type=='change'){
			$cuanwe	= [
				'cid'		=> $this->companyid,
				'courseid'	=> $courseid,
				'mtable'	=> $this->mtable,
				'mid'		=> $this->mid,
				'addlx'		=> 1
			];
			$frs = FlowchecksModel::where($cuanwe)->first();
			if($frs){
				$cuid	= $frs['checkid'];
				$name	= $frs['checkname'];
			}
		}
		if($type=='user'){
			$cuid	= $typeid;
			$name	= $typename;
		}
		//直属上级
		if($type=='super'){
			$cuid	= $this->nowars->superid;
			$name	= $this->nowars->superman;
		}
		//部门负责人
		if($type=='dept' || $type=='super'){
			if(isempt($cuid) && $this->nowdrs){
				$cuid = $this->nowdrs->headid;
				$name = $this->nowdrs->headman;
			}
		}
		//职位
		if($type=='rank' && !isempt($typename)){
			$urs = UseraModel::where(['cid'=>$this->companyid,'position'=>$typename])->where('status','<>', 2)->first();//值读取一个
			if($urs){
				$cuid = $urs->id;
				$name = $urs->name;
			}
		}
		//申请人||提交人
		if($type=='apply' || $type=='opt'){
			$aid 	= $this->nowars->id;
			if($type=='opt')$aid 	= objvalue($this->rs, 'optid', $aid);
			$urs	= UseraModel::where(['cid'=>$this->companyid,'id'=>$aid])->first();
			if(!$urs)$urs = $this->nowars;
			$cuid 	= $urs->id;
			$name 	= $urs->name;
		}
		
		//自定义
		if($type=='auto' && !isempt($typename)){
			$autb	= $this->flowactrun($typename);
			if($autb && is_array($autb)){
				$cuid 	= $autb[0];
				$name 	= $autb[1];
			}
		}
		
		return array($cuid, $name);
	}
	
	
	//循环递归转办
	private function zhuangbanchul($courseid, $checkidsa, $checknamesa, $xuci=0)
	{
		//转办的判断
		$ztarrs			= array();
		if($this->zhuangbantrows)foreach($this->zhuangbantrows as $k=>$rsss){
			if($rsss->courseid==$courseid){
				$rsss->xuhao = $k;
				$ztarrs[$rsss->optid] = $rsss;
			}
		}
		if($ztarrs){
			$newcheida 	= $checkidsa;
			$newchecn  	= $checknamesa;
			$iszb		= false;
			foreach($checkidsa as $k1=>$cids){
				if(isset($ztarrs[$cids])){
					$rsss = $ztarrs[$cids];
					unset($newcheida[$k1]);
					unset($newchecn[$k1]);
					$nwear = explode(',', $rsss->checkid);
					$nwean = explode(',', $rsss->checkname);
					$newcheida = array_merge($newcheida, $nwear);
					$newchecn  = array_merge($newchecn, $nwean);
					unset($this->zhuangbantrows[$rsss->xuhao]);
					$iszb 	= true;
				}
			}
			$checkidsa 		= $newcheida;
			$checknamesa 	= $newchecn;
			
			//递归判断有没有在转办
			if($xuci<5 && $iszb){
				$zparr			= $this->zhuangbanchul($courseid, $checkidsa, $checknamesa, $xuci+1);
				$checkidsa		= $zparr[0];
				$checknamesa	= $zparr[1];
			}
		}
		return [$checkidsa, $checknamesa];
	}
	//保存步骤，以下处理逻辑复杂，久了就会忘了，所以注释写的多
	private function savecourss($rs)
	{
		$checkidsa		= explode(',', emptytodev($rs['checkid'],'0'));
		$checknamesa	= explode(',', $rs['checkname']);
		$courseid		= $rs['id'];
		
		$sids 			= '0'; //保存存储的id
		$rows 			= FlowcourssModel::where($this->mwhere)->where('courseid', $courseid)->get(); //读取记录
		
		//转办的判断
		$zparr			= $this->zhuangbanchul($courseid, $checkidsa, $checknamesa, 0);
		$checkidsa		= $zparr[0];
		$checknamesa	= $zparr[1];
		
		//没有就新增
		if($rows->count()==0){
			foreach($checkidsa as $k1=>$cids){
				$sids .= ','.$this->insertFlowcourss([
					'courseid' 	=> $courseid,
					'step' 		=> $rs['step'],
					'checkname' => arrvalue($checknamesa, $k1),
					'checkid' 	=> emptytodev($cids,0),
				]);
			}
		}else{
			$slar = $sla1	= array();
			foreach($rows as $k1=>$ors1){
				$slar[] = $ors1->checkid; //存储已保存用户Id
				$sla1[$ors1->checkid] = $k1; //对应用户保存哪一条
			}
			$sdar	= array();//要保存的
			foreach($checkidsa as $k1=>$cids){
				if(!in_array($cids, $slar)){
					$sdar[] = $k1; //不存在需要新增的
				}else{
					$iu		= $sla1[$cids];
					$uors 	= $rows[$iu];
					$sids  .= ','.$uors->id.''; //已保存了
					$uors->checkname = arrvalue($checknamesa, $k1);
					$uors->checkid   = emptytodev($cids,0);
					$uors->step   	 = $rs['step']; //步骤
					$uors->valid   	 = 1;
					$uors->save();//更新
				}
			}
			
			//不存在需要新增
			foreach($sdar as $i){
				$sids .= ','.$this->insertFlowcourss([
					'courseid' 	=> $courseid,
					'checkname' => $checknamesa[$i],
					'checkid'	=> $checkidsa[$i],
					'step' 		=> $rs['step'],
				]);
			}
		}
		return $sids;
	}
	
	//步骤新增
	private function insertFlowcourss($sarr)
	{
		$sda			= new FlowcourssModel();
		$sda->cid 		= $this->nowcompanyid;
		$sda->agenhid 	= $this->agenhid;
		$sda->mtable 	= $this->mtable;
		$sda->mid 		= $this->mid;
		$sda->valid 	= 1;
		foreach($sarr as $k=>$v)$sda->$k = $v;
		$sda->save();
		return $sda->id;
	}
	

	/**
	*	当前单据状态
	*/
	public function getnowstatus($rs)
	{
		$zt 	= objvalue($rs, 'status', 1);
		$isturn = objvalue($rs, 'isturn', 1);
		$str 	= $this->flownowstatus($rs);
		if($str!='')return $str;
		if($zt==5)return '<font color=#aaaaaa>已作废</font>';
		if($isturn==0){
			return '<font color=#ff6600>待提交</font>';
		}else{
			$txt = arrvalue($this->statusbilarr, $zt);
			$col = arrvalue($this->statuscolarr, $zt);
			return '<font color='.$col.'>'.$txt.'</font>';
		}
	}
	
	/**
	*	获取第2个状态
	*/
	public function getnowstate($rs, $ztva=array(), $ztvc=array())
	{
		$zt 	= objvalue($rs, 'status', 1);
		$isturn = objvalue($rs, 'isturn', 1);
		$st 	= objvalue($rs, 'state', 0);
		if($zt==5)return '<font color=#aaaaaa>已作废</font>';
		if($isturn==0){
			return '<font color=#ff6600>待提交</font>';
		}else{
			if($zt==1){
				$zt   = $st;
			}else{
				$ztva = $this->statusbilarr;
				$ztvc = $this->statuscolarr;
			}
			$txt = arrvalue($ztva, $zt);
			$col = arrvalue($ztvc, $zt);
			if(!isempt($col))$txt = '<font color='.$col.'>'.$txt.'</font>';
			return $txt;
		}
	}
	
	
	////添加类型addlx:1上步选择,2撤回添加,3退回添加,4转移添加
	private function addcheckname($courseid, $checkid, $checkname, $addlx=1)
	{
		$cuanwe	= [
			'cid'		=> $this->companyid,
			'courseid'	=> $courseid,
			'mtable'	=> $this->mtable,
			'mid'		=> $this->mid,
			'addlx'		=> $addlx
		];
		if($addlx==4)$cuanwe['optid'] = $this->useaid;
		$firstrs = FlowchecksModel::where($cuanwe)->first();
		if(!$firstrs)$firstrs = new FlowchecksModel();
		$firstrs->cid = $this->companyid;
		$firstrs->courseid = $courseid;
		$firstrs->checkid = $checkid;
		$firstrs->agenhid = $this->agenhid;
		$firstrs->checkname = $checkname;
		$firstrs->mtable = $this->mtable;
		$firstrs->mid = $this->mid;
		$firstrs->addlx = $addlx;
		$firstrs->optid = $this->useaid;
		$firstrs->optname = $this->useainfo->name;
		$firstrs->optdt = nowdt();
		$firstrs->save();
		
		//删除他人转办的
		if($addlx==4)FlowchecksModel::where([
			'cid'		=> $this->companyid,
			'courseid'	=> $courseid,
			'mtable'	=> $this->mtable,
			'mid'		=> $this->mid,
			'addlx'		=> $addlx
		])->whereIn('optid', explode(',', $checkid))->delete();
	}
	/**
	*	说明追加
	*/
	public function strappend($sm, $str, $fh=',')
	{
		if(isempt($str))return $sm;
		if(!isempt($sm))$sm.=$fh;
		$sm.=$str;
		return $sm;
	}
	
	/**
	*	单据异常退回处理，退回到对应人
	*/
	public function checkyitui($sm='')
	{
		$uarr['status'] = 0;
		$uarr['isturn'] = 0; //待提交
		$this->updateData($uarr);
		if($this->billrs){
			$this->billrs->allcheckid = '';
			$this->billrs->nowcheckname = '';
			$this->billrs->nowcheckid = '';
			$this->billrs->nowstatus = '';
			$this->billrs->save();
		}
		$this->addLog([
			'explain' => $sm
		],'异常退回');
		$this->nexttodo($this->optid,'yitui','异常退回', $sm);
	}
	
	/**
	*	流程审核处理
	*/
	public function check($zt, $sm='', Request $request=null)
	{
		if($zt<1)return returnerror('处理动作不能小于1');
		$status 	= $this->rs->status;
		
		if($status==1)return returnerror('此单据已处理完成了');
		if($status==5)return returnerror('此单据已作废了');
		
		$info 		= $this->getflowinfo();
		if($info['ischeck']==0)return returnerror('当前不是你审核处理');
	
		$nowrs		= $this->nowcourse; //当前步骤信息
		$nextrs		= $this->nextcourse; //下一步的
		$courseid	= $nowrs['id'];
		$checkact 	= $info['checkact'][$zt-1];
		$act		= $checkact['act']; //处理动作名称
		$loarr		= [
			'courseid' 	=> $courseid,
			'actname' 	=> $nowrs['name'],
			'status' 	=> $zt,
			'statusname'=> $checkact['act'],
			'color'		=> $checkact['color'],
			'explain'	=> $sm,
			'qmimg'		=> ''
		];
		$istiend	= false;
		
		
		//签名图片处理
		$qmimgstr		= $request->input('qmimgstr');
		if(!isempt($qmimgstr)){
			$upbarr 	= c('rockapi')->curlpost('updown','sendcont', [
				'cont' 		=> $qmimgstr,
				'filename' 	=> ''.$this->useainfo->name.'签名图片',
				'thumbnail'	=> '1000x1000'
			]);
			if(!$upbarr['success'])return returnerror($upbarr['msg']);
			$loarr['qmimg'] = $upbarr['data']['allpath'];
		}
		
		if($zt==1){
			$ztnameid 	= $request->input('zhuanbannameid');
			$ztname 	= $request->input('zhuanbanname');
			//转办
			if(!isempt($ztnameid)){
				if($ztnameid==$this->useaid)return returnerror('不能转给自己');
				$smnew 	= $this->strappend($sm, '转给：'.$ztname.'');
				$this->addcheckname($courseid, $ztnameid, $ztname, 4);
				$istiend = true;
				$loarr['actname'] = '转办';
				$loarr['explain'] = $smnew;
				$billarr	= $this->getflow(true);
				$this->nexttodo($billarr['nowcheckid'],'zhuanban','', $sm);
			}
			
			//下一步审核人
			if($nextrs && $nextrs['checktype']=='change'){
				$nextna		= $request->input('nextname');
				$nextnaid	= $request->input('nextnameid');
				if(!$nextnaid)return returnerror('必须选择下一步处理人');
				
				$this->addcheckname($nextrs['id'], $nextnaid, $nextna, 1);
			}
			
			//保存数据
			$checkfarr	= $info['checkfarr'];
			$osarr		= array();
			foreach($checkfarr as $k=>$rs){
				if($rs->islu==1){
					$val 	= $request->input($rs->fields);
					if($rs->isbt==1 && isempt($val))return returnerror(''.$rs->name.'不能为空');
					$osarr[$rs->fields] = $val;
				}
			}
			
			
			
			if($osarr)$this->updateData($osarr);
			$this->getflow(); //在获取
		}
		
		
		$this->addLog($loarr); //写入到日志中
		
		if($istiend)return returnsuccess();
		
		//更新审核状态
		$ssarr		= [
			'checkstatus' 		=> $zt,
			'checksm' 			=> $sm,
			'checkstatustext' 	=> $loarr['statusname'],
			'checkstatuscolor' 	=> $loarr['color'],
			'checkdate' 		=> nowdt(),
			'checkqmimg'		=> $loarr['qmimg']
		];
		FlowcourssModel::where($this->mwhere)
				->where('courseid', $courseid)
				->where('checkid', $this->useaid)
				->update($ssarr);
		
		
		//获取下一步审核信息
		$uarr 		= array();
		if($zt==2 || !$this->nextcourse)$uarr['status'] = $zt; //不同意/没有下一步
		if($uarr)$this->updateData($uarr);
		
		$arr 		= $this->getflow(true);
		
		$ners		= $this->nowcourse;
		$this->billflowsave(['nstatus'=>$zt]); //单据状态的
		
		//不同意通知提交人/申请人
		if($zt==2){
			$this->nexttodo($this->optid,'nothrough', $act, $sm);
		}else{
			//通知下一步
			if($ners){
				$this->nexttodo($arr['nowcheckid'],'next');
			}else{
				//已完成通知
				$this->nexttodo($this->optid,'finish');
				
				//删除转办指定人信息
				FlowchecksModel::where($this->mwhere)->delete();
				
				$this->flowcheckfinsh($zt);
			}
		}
		
		//最后一步的
		if(!$ners){
			
		}
		
		return returnsuccess();
	}
	
	
	/**
	*	获取流程信息
	*/
	public function getflowinfo()
	{
		$ischeck 	= 0;
		$nowstatus	= '';
		$this->flowarrall = $this->nextcourse = $this->nowcourse = array();
		$carr 		= array();
		$checkact 	= array();
		
		$nstatus 	= $this->rs->status;
		$isturn 	= $this->rs->isturn;
		if($this->isflow>0){
			$arrbill 	= $this->getflow(false);
			$bofao		= false;
			foreach($arrbill as $k=>$v){
				if($v!=$this->billrs->$k){$bofao=true;break;}
			}
			if($bofao)$this->billflowsave($arrbill);
			$nowstatus	= $arrbill['nowstatus'];
			if($isturn==1 && $nstatus!=5){
				$nowcheckid = ','.$arrbill['nowcheckid'].',';
				
				//只有一人不同意，其他人就不用审批了
				if($nstatus !=1 && contain($nowcheckid, ','.$this->useaid.',') && !in_array($nstatus, [2,5])){
					$ischeck = 1;
				}
				$crs 	= array('name'=>'流程结束','id'=>-999,'isnow'=>0,'style'=>'color:#aaaaaa');
				$this->flowarrall[] = array('crs'=>$crs);
				//审核动作
				if($ischeck==1){
					$checkactae = explode(',', arrvalue($this->nowcourse,'courseact','同意|green,不同意|red'));
					foreach($checkactae as $checkacta1){
						$checkacta2 = explode('|', $checkacta1);
						$checkact[] = array(
							'act'=>$checkacta2[0],
							'color'=>arrvalue($checkacta2, 1)
						);
					}
				}
				if($nowstatus=='')$nowstatus = $this->getnowstatus($this->rs);
				if($nstatus==2){
					$nowstatus.=',待提交人'.$this->nowname.'处理';
				}
			}else{
				
			}
		}
		
		$checkfarr	= array();
		$store		= array();
		
		//print_r($this->flowarrall);
		$barr 	= array(
			'isflow'	=> $this->isflow,
			'ischeck'	=> $ischeck,
			'checkfarr'	=> $checkfarr,
			'store'		=> $store,
			'flowarr'	=> $this->flowarrall,
			'nextcourse'=> $this->nextcourse, //下一步审核步骤信息
			'nowcourse'	=> $this->nowcourse, //当前审核步骤信息
			'checkact'	=> $checkact,
			'status'	=> $this->rs->status,
			'isturn'	=> $this->rs->isturn,
			'nowstatus'	=> $nowstatus,
		);
		
			
		//审核处理表单
		if($ischeck==1){
			$cfields 	= $this->nowcourse['checkfields'];
			
			//流程审核元素
			if(!isempt($cfields)){
				$chfiearr = $this->getFieldsArr('lu');
				foreach($chfiearr as $k=>$rs){
					if(contain(','.$cfields.',', ','.$rs->fields.','))$checkfarr[] = $rs;
				}
				
				$farr			= $this->inputfieldsarr($checkfarr, $this->rs);
				$checkfarr		= $farr['fieldsarr'];
				$store 			= $farr['store'];
				foreach($checkfarr as $k1=>$rs1)$checkfarr[$k1]->islu = 1;
			}
			
			
			
			//是否可以转办
			if($this->nowcourse['iszb']>0){
				$firarr	= $this->createFields('转给','zhuanbanname','changeuser');
				$firarr->data = 'zhuanbannameid';
				$firarr->placeholder = '可以选择人员转给他人办理';
				$checkfarr[]  = $firarr;
			}
			
			$barr['checkfarr']	= $checkfarr;
			$barr['store']		= $store;
		}
		
		return $barr;
	}
	
	
	
	
	
	
	public function pipei()
	{
		$parr			= $this->getflow(false);
		$parr['savebo'] = $this->billflowsave($parr);
		return $parr;
	}
}