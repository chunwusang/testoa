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

use App\Model\Base\UseraModel;
use App\Model\Base\UsersModel;
use App\Model\Base\DeptModel;
use App\Model\Base\SjoinModel;
use App\Model\Base\GroupModel;
use DB;


class ChajianBase_usera extends ChajianBase
{
	private $deptdata;
	private $useadata;
	
	/**
	*	更新数据，更新是比较慢
	*	$cid 单位Id $where='条件'
	*/
	public function reloaddata($cid=0, $aids=null)
	{
		if($cid==0)$cid = $this->companyid;
		$uobj 			= UseraModel::where('cid', $cid);
		
		if(is_array($aids))$uobj  	= $uobj->whereIn('id', $aids);
		if(is_numeric($aids))$uobj  = $uobj->where('id', $aids);
		
		$this->useadata	= $uobj->get();
		$this->deptdata	= DeptModel::where('cid', $cid)->get();
		$deptobj		= $useadata = array();
		$this->deptobj	= $this->useaobj = array();
		foreach($this->deptdata as $k=>$rs){
			$deptobj[$rs->id] = $rs->name;
			$this->deptobj[$rs->id] = $rs;
		}
		foreach($this->useadata as $k=>$rs){
			$useadata[$rs->id] = $rs->name;
			$this->useaobj[$rs->id] = $rs;
		}
		$oi	= 0;
		
		$guar   = array();
		$sgjor	= SjoinModel::where('cid', $cid)->where('type','gu')->get();
		foreach($sgjor as $k=>$rs){
			$guar[$rs->sid][] = $rs->mid;
		}
		
		foreach($this->useadata as $k=>$rs){
			$uarr		= array();
			$uarr['deptname'] = arrvalue($deptobj, $rs->deptid);
			$superid	= '';
			$superman	= '';
			$superpath	= '';
			$grouppath  = '';
			
			//读取组
			if(isset($guar[$rs->id])){
				$grouppath = join(',', $guar[$rs->id]);
			}
			
			if(isempt($uarr['deptname'])){
				$uarr['deptid'] = 0;
				$uarr['deptallname'] = '';
				$uarr['deptpath'] = '';
			}else{
				//部门全部路径，和全名称
				$sstra = $this->getdeptpid($rs->deptid);
				$uarr['deptallname'] = $sstra[1];
				$uarr['deptpath'] 	 = $sstra[0];
			}
			
			//直属上级
			if(!isempt($rs->superid)){
				$superida = explode(',', $rs->superid);
				foreach($superida as $sid){
					$_names = arrvalue($useadata, $sid);
					if(!isempt($_names)){
						$superman.=','.$_names.'';
						$superid.=','.$sid.'';
					}
				}
			}
			if($superid!=''){
				$uarr['superid']  = substr($superid, 1);
				$uarr['superman'] = substr($superman, 1);
				$uarr['superpath'] = $this->getsuperpid($rs->id);
			}else{
				$uarr['superid']  = '';
				$uarr['superman'] = '';
				$uarr['superpath']= '';
			}
			$uarr['grouppath']	 = $grouppath;
			
			foreach($uarr as $uk=>$uv)if($uv===$rs->$uk)unset($uarr[$uk]);
			if($uarr){
				UseraModel::where('id', $rs->id)->update($uarr);
				$oi++;
			}
		}
		$uparr	= UseraModel::select(DB::raw('count(*) as flasks, cid'))->groupBy('cid')->get();
		foreach($uparr as $k=>$rs){
			DB::table('company')->where('id', $rs->cid)->update(['flasks'=>$rs->flasks]);
		}
		
		return $oi;
	}
	
	//获取部门路径
	private function getdeptpid($id)
	{
		$this->pidss = '';
		$this->pidsa = '';
		$this->getdeptpids($id);
		if($this->pidss!=''){
			$this->pidss = substr($this->pidss, 1);
			$this->pidsa = substr($this->pidsa, 1);
		}
		return [$this->pidss, $this->pidsa];
	}
	private function getdeptpids($id)
	{
		$objarr 	 = $this->deptobj[$id];
		if($objarr){
			$pid = $objarr->pid;
			$this->pidss=','.$objarr->id.''.$this->pidss;
			$this->pidsa='/'.$objarr->name.''.$this->pidsa;
			if($pid>0){
				$this->getdeptpids($pid);
			}
		}
	}
	
	//获取上级路径
	private function getsuperpid($id)
	{
		$this->pidss = '';
		$this->getsuperpids($id);
		if($this->pidss!=''){
			$this->pidss = substr($this->pidss, 1);
		}
		return $this->pidss;
	}
	private function getsuperpids($id)
	{
		$objarr 	 = $this->useaobj[$id];
		if($objarr){
			$supid = $objarr->superid;
			if(!isempt($supid)){
				$this->pidss.=','.$supid.'';
				$supaa = explode(',', $supid);
				foreach($supaa as $sid){
					if(!contain(','.$this->pidss.',', ','.$sid.','))
						$this->getsuperpids($sid);
				}
			}
		}
	}
	
	/**
	*	获取我直属下级id
	*/
	public function getdownaid($aid=0, $dv=true)
	{
		if($aid==0)$aid = $this->useaid;
		$rows 	= UseraModel::where('cid', $this->companyid)
						->whereRaw($this->dbinstr('superid', $aid))
						->get();
		$aids 	= array();	
		if($dv)$aids 	= array(0);	
		foreach($rows as $k=>$rs)$aids[] = $rs->id;
		return $aids;
	}
	
	/**
	*	获取我全部直属下级id
	*/
	public function getdownallaid($aid=0, $dv=true)
	{
		if($aid==0)$aid = $this->useaid;
		$rows 	= UseraModel::where('cid', $this->companyid)
						->whereRaw($this->dbinstr('superpath', $aid))
						->get();
		$aids 	= array();	
		if($dv)$aids 	= array(0);	
		foreach($rows as $k=>$rs)$aids[] = $rs->id;
		return $aids;
	}
	
	/**
	*	获取可选择人员
	*/
	public function getUseraData($cid=0, $range='')
	{
		if($cid==0)$cid = $this->companyid;
		$obj  	= UseraModel::select()
				->where('cid', $cid)->where('status','<>', 2);
		if(!isempt($range)){
			$aida 	= $this->getNei('contain')->getaida($range);
			if($aida!='all')$obj->whereIn('id', $aida);
		}else{
			//判断是否有设置选择权限。
		}			
				
		$data 	= $obj->orderBy('sort','desc')->get();	
		$arr 	= array();	
		foreach($data as $k=>$rs){
			$arr[] = array(
				'id' 		=> $rs->id,
				'name' 		=> $rs->name,
				'pingyin' 	=> $rs->pingyin,
				'position' 	=> $rs->position,
				'status' 	=> $rs->status,
				'deptid' 	=> $rs->deptid,
				'deptids' 	=> $rs->deptids,
				'deptpath' => $rs->deptpath,
				'deptname' => $rs->deptname,
				'uid' 		=> $rs->uid,
				'face' 		=> $rs->face,
			);
		}
		return $arr;
	}
	
	public function getGroupData($cid=0, $qx=true)
	{
		if($cid==0)$cid = $this->companyid;
		$groupdata	= GroupModel::where('cid', $cid)->orderBy('sort','desc')->get();
		
		foreach($groupdata as $k=>$rs){
			$groupdata[$k]->usershu = SjoinModel::where('cid', $cid)->where('type','gu')->where('mid', $rs->id)->count();
		}
		
		return $groupdata;
	}
	
	/**
	*	返回用户基本信息,$glx=0对象,1数组
	*/
	public function getuserainfo($aid, $glx=0, $fiedss='')
	{
		$info = UseraModel::where('cid',$this->companyid)->find($aid);
		$bar  = new \StdClass();
		$baa  = array();
		$fieds = 'name,face,deptname,id,deptallname,position';
		if($fiedss!='')$fieds.=','.$fiedss.'';
		$fiada= explode(',', $fieds);
		foreach($fiada as $fid){
			$bar->$fid = $info ? $info->$fid : '';
			$baa[$fid] = $bar->$fid;
		}
		if($glx==0)return $bar;
		if($glx==1)return $baa;
	}
	
	/**
	*	启用停用单位用户状态
	*/
	public function changestatus($aid,$zt)
	{
		$info = UseraModel::where('cid',$this->companyid)->find($aid);
		if(!$info)return '用户不存在';
		if($this->companyinfo->uid==$info->uid && $zt != 1)
			return returnerror('不允许改变单位创建人状态');
		
		//加入时，搜索平台用户
		$uarr	= 0;
		if($info->uid==0 && $zt==1){
			$uinfo= UsersModel::where(['mobilecode'=>$info->mobilecode,'mobile'=>$info->mobile])->first();
			if(!$uinfo)return returnerror('对应手机号没有在平台注册，无法加入');
			
			$info->status 	= 1;
			$info->name 	= $uinfo->name;
			$info->uid 		= $uinfo->id;
			$info->joindt 	= nowdt();
			$uarr = 2;
			
		}else{
			$uarr = 1;
			$info->status = $zt;
		}
		if($uarr>0){
			$info->save();
			if($uarr ==2)$this->reloaddata($this->companyid, $aid);
		}
		return returnsuccess();
	}
	
	public function getnametors($name)
	{
		return UseraModel::where(['cid'=>$this->companyid,'name'=>$name])->first();
	}
}