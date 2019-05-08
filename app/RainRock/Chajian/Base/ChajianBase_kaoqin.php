<?php
/**
*	插件-考勤模块
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-30
*/

namespace App\RainRock\Chajian\Base;

use DB;
use Illuminate\Support\Facades\Cache;

class ChajianBase_kaoqin extends ChajianBase
{
	protected function initChajian()
	{
		$this->dtobj 	= c('date');
	}

	/**
	*	匹配当前我对应那条考勤规则
	*/
	private $getdistrows = array();
	private $uarrinfo	 = array();
	public function getdistid($aid, $dt, $type=0)
	{
		$dt 	= substr($dt, 0, 10);
		if(!isset($this->getdistrows[$type])){
			$distall = DB::table('kqdist')->where(['cid'=>$this->companyid,'status'=>1,'type'=>$type])->get();
			$this->getdistrows[$type] = $distall;
		}else{
			$distall = $this->getdistrows[$type];
		}
		
		//匹配出来，优先级：人员>组>部门>顶级部门
		$parr = array();
		$demid= 0;
		foreach($distall as $k=>$rs){
			if($k==0)$demid = $rs->mid;
			if($rs->startdt<=$dt && $dt<=$rs->enddt)$parr[] = $rs;
		}
		
		if(!$parr)return $demid;//没匹配到
		
		$ainfo = $this->getainfo($aid);
		if(!$ainfo)return $demid; //用户不存在了
		
		$auarr[] = 'u'.$ainfo->id.'';
		if(!isempt($ainfo->grouppath)){
			$uara = explode(',', $ainfo->grouppath);
			foreach($uara as $uara1)$auarr[] = 'g'.$uara1.'';
		}
		if(!isempt($ainfo->deptpath)){
			$uara = explode(',', $ainfo->deptpath);
			$len1 = count($uara);
			for($i=$len1-1;$i>=0;$i--)$auarr[] = 'd'.$uara[$i].'';
		}
		$poi = -1;
		foreach($auarr as $aid1){
			if($poi>-1)break;
			foreach($parr as $k=>$rs){
				if(contain(','.$rs->receid.',', ','.$aid1.',')){
					$poi = $k;
					break;
				}
			}
		}
		if($poi>-1)$demid = $parr[$poi]->mid; //最后匹配

		return $demid;
	}
	
	private function getainfo($aid)
	{
		if(!is_numeric($aid)){
			$ainfo	= $aid;
		}else{
			if(!isset($this->uarrinfo[$aid])){
				$ainfo 	= $this->getModel('userinfo')->where('aid', $aid)->first();
				$this->uarrinfo[$aid] = $ainfo;
			}else{
				$ainfo 	= $this->uarrinfo[$aid];
			}
		}
		return $ainfo;
	}
	
	/**
	*	读取考勤时间
	*	$lx0 考勤时段, $lx1上班时间
	*/
	public function getkqsj($uid, $dt, $lx=0)
	{
		$mid 	= $this->getdistid($uid, $dt, 0);
		$farr	= explode(',','id,name,stime,etime,qtype,iskq,iskt,isxx,sort');	
		
		$rowsa 	= DB::table('kqsjgz')->where('pid', $mid)->orderBy('sort')->get();
		$rows	= array();
		foreach($rowsa as $k1=>$rs1){
			$narr = array();
			foreach($farr as $fid)$narr[$fid] = $rs1->$fid;
			$rows[] = $narr;
		}
		if($lx==1){
			return $rows;
		}
		foreach($rows as $k=>$rs){
			$rowsa 	= DB::table('kqsjgz')->where('pid', $rs['id'])->orderBy('sort')->get();
			
			$rows1	= array();
			foreach($rowsa as $k1=>$rs1){
				$narr = array();
				foreach($farr as $fid)$narr[$fid] = $rs1->$fid;
				$rows1[] = $narr;
			}
			$rows[$k]['children'] = $rows1;
		}
		return $rows;
	}
	
	private function geetdkarr($aid, $dt)
	{
		$rowa = DB::table('kqdkjl')->where('aid', $aid)->where('dkdt', 'like', ''.$dt.'%')->orderBy('dkdt')->get();
		$rows = array();
		foreach($rowa as $k=>$rs){
			$rows[] = array(
				'dkdt' => $rs->dkdt
			);
		}
		return $rows;
	}
	
	/**
	*	是不是工作日
	*/
	private $isworkdtarr = array();
	public function isworkdt($uid, $dt)
	{
		$month 	= substr($dt, 0, 7);
		$key 	= 'a'.$month.'';
		$barr 	= array();
		if(!isset($this->isworkdtarr[$key])){
			$rows = DB::table('kqxxsj')->where('cid', $this->companyid)->where('dt','like', ''.$month.'%')->get();
			foreach($rows as $k=>$rs){
				$barr['a'.$rs->pid.''.$rs->dt.'_0'] = 0;
			}
			$this->isworkdtarr[$key] = $barr;
		}else{
			$barr = $this->isworkdtarr[$key];
		}
		$isw	= 1;
		$mid 	= $this->getdistid($uid, $dt, 1);
		$type 	= arrvalue($barr, 'a'.$mid.''.$dt.'_0');
		if($type=='0')$isw = 0;

		return $isw;
	}
	
	/**
	*	考勤分析
	*/
	public function kqanay($uid, $dt)
	{
		$dt 	= substr($dt,0, 10); 
		if($dt>nowdt('dt'))return;
		$dkarr 	= $this->geetdkarr($uid, $dt);
		$iswork	= $this->isworkdt($uid, $dt);
		
		$sjarr	= $this->getkqsj($uid, $dt);
		
		$ids 	= array(0);
		
		//是否有跨天的
		$isjy_1	=  $isjy_2	= 0;
		foreach($sjarr as $k=>$rs){
			foreach($rs['children'] as $k1=>$rs1){
				if($rs1['iskt']==2)$isjy_2=1; //-1天
				if($rs1['iskt']==1)$isjy_1=1; //+1天
			}
		}
		if($isjy_2==1){
			$dt2 	= $this->dtobj->adddate($dt,'d', -1);
			$dtarr2 = $this->geetdkarr($uid, $dt2);
			if($dtarr2)$dkarr = array_merge($dtarr2, $dkarr);
		}
		if($isjy_1==1){
			$dt1 	= $this->dtobj->adddate($dt,'d', 1);
			$dtarr1 = $this->geetdkarr($uid, $dt1);
			if($dtarr1)$dkarr = array_merge($dkarr, $dtarr1);
		}
		$this->_dkarr = $dkarr;
		foreach($sjarr as $k=>$rs){
			$ztname = $rs['name'];
			$arrs 	= $this->kqanaysss($uid, $dt, $rs, $this->_dkarr);
			$state	= $arrs['state'];
			$states	= $arrs['states'];
			$timesb	= $timeys = 0;
			
			//判断是否有请假和外出。。
			if($iswork==1 && $state !='正常'){
				$zcarr	= array();
				foreach($rs['children'] as $k2=>$cog2){
					if($cog2['name']=='正常')$zcarr = $cog2;
				}
				if($zcarr)$states = $this->getstates($zcarr, $dt, $uid);	
			}
			
			
			$emiao	= $arrs['emiao']; //迟到早退秒数
			$time	= $arrs['time'];
			
			
			if($rs['isxx']=='0'){
				$mshu	= strtotime(''.$dt.' '.$rs['stime'].'') - strtotime(''.$dt.' '.$rs['etime'].'');
				$timesb	= abs(round($mshu / 60 / 60,1));
				if($state=='正常' || !isempt($states)){
					$timeys = $timesb;
				}else{
					if($emiao>0){
						$timeys = round((abs($mshu) - $emiao)/60/60, 1);
					}
				}
			}
			if($time=='')$time = null;
			$arr	= array(
				'ztname' 	=> $ztname,
				'cid'		=> $this->companyid,
				'state' 	=> $state,
				'states' 	=> $states,
				'time' 		=> $time,
				'aid' 		=> $uid,
				'dt' 		=> $dt,
				'sort' 		=> $k,
				'iswork' 	=> $iswork,
				'emiao' 	=> $emiao,
				'timesb' 	=> $timesb,
				'timeys' 	=> $timeys
			);
			
			$garr	= array(
				'aid'	=> $uid,
				'dt'	=> $dt,
				'ztname'=> $ztname,
			);
			$fistrs = DB::table('kqanay')->where($garr)->first();
			if($fistrs){
				$id = $fistrs->id;
				DB::table('kqanay')->where('id', $id)->update($arr);
			}else{
				$id = DB::table('kqanay')->insertGetId($arr);
			}
			$ids[] = $id;
		}
		DB::table('kqanay')->where(array(
			'aid' 	=> $uid,
			'dt'	=> $dt
		))->whereNotin('id', $ids)->delete();
	}
	private function kqanaysss($uid, $dt, $kqrs, $dkarr)
	{
		$kqarr	= $kqrs['children'];
		$state	= '未打卡';$states = $remark = ''; $emiao	= 0; $tpk=-1; $time	= ''; $pdtime	= 0;
		if($dkarr && $kqarr)foreach($kqarr as $k=>$rs){
			$stime 	= strtotime(''.$dt.' '.$rs['stime'].'');
			$etime 	= strtotime(''.$dt.' '.$rs['etime'].'');
			$qtype	= $rs['qtype'];
			$iskt	= $rs['iskt'];
			
			//-1跨天
			if($iskt==2){
				$dt2	= $this->dtobj->adddate($dt,'d', -1);
				$stime 	= strtotime(''.$dt2.' '.$rs['stime'].'');
				if($rs['stime']<$rs['etime']){
					$etime 	= strtotime(''.$dt2.' '.$rs['etime'].'');
				}
			}
			
			//+1跨天
			if($iskt==1){
				$dt1	= $this->dtobj->adddate($dt,'d', 1);
				$etime 	= strtotime(''.$dt1.' '.$rs['etime'].'');
				if($rs['stime']<$rs['etime']){
					$stime 	= strtotime(''.$dt1.' '.$rs['stime'].'');
				}
			}
			
			foreach($dkarr as $k1=>$rs1){
				$dkdt = strtotime($rs1['dkdt']);
				if($stime>$dkdt || $etime<$dkdt)continue;
				$time	= $dkdt;
				$state	= $rs['name'];
				$tpk	= $k1;
				if($qtype==0)break;
			}
			$pdtime	= $stime;
			if($qtype==1)$pdtime = $etime;
			if($time!='')break;
		}
		if($time!=''){
			if($state!='正常'){
				$emiao = $pdtime-$time;
			}
			unset($this->_dkarr[$tpk]);//一次打卡记录只能使用一次
		}
		$barr['state'] 		= $state;
		$barr['emiao'] 		= abs($emiao);
		if($time!='')$time	= date('Y-m-d H:i:s', $time);
		$barr['time'] 		= $time;
		$barr['states'] 	= $states;
		$barr['remark'] 	= $remark;
		if($pdtime!=0)$barr['pdtime'] = date('Y-m-d H:i:s', $pdtime);
		return $barr;
	}
	
	/**
	*	上班: (当前qtype==0)请假开始时间小于等于 设置正常的截止时间（取最小值）
	*	下班: (当前qtype==1)请假截止时间大于等于 设置正常的开始时间（取最大值）
	*/
	private function getstates($ztarr, $dts, $uid)
	{
		$st1	= strtotime($dts.' '.$ztarr['stime']);
		$et1	= strtotime($dts.' '.$ztarr['etime']);
		$s 		= '';
		$rows 	= DB::table('kqleavs')
						->where(['aid'=>$uid,'status'=>1])
						->where('stime','<=',''.$dts.' 23:59:59')
						->where('etime','>=',''.$dts.' 00:00:00')
						->get();
		foreach($rows as $k=>$rs){
			$qst = strtotime($rs->stime);
			$qet = strtotime($rs->etime);
			if($ztarr['qtype']==1){
				if($qet >= $st1){
					$s = $rs->qjtype;
				}
			}else{
				if($qst <= $et1){
					$s = $rs->qjtype;
				}
			}
		}
		if($s==''){
			$rows 	= DB::table('kqout')
						->where(['aid'=>$uid,'status'=>1])
						->where('startdt','<=',''.$dts.' 23:59:59')
						->where('enddt','>=',''.$dts.' 00:00:00')
						->get();
			foreach($rows as $k=>$rs){
				$qst = strtotime($rs->startdt);
				$qet = strtotime($rs->enddt);
				if($ztarr['qtype']==1){
					if($qet >= $st1){
						$s = $rs->atype;
					}
				}else{
					if($qst <= $et1){
						$s = $rs->atype;
					}
				}
			}
		}
		return $s;
	}
	
	/**
	*	根据月份分析全部
	*/
	public function kqanayall($month, $uid=0)
	{
		if($uid>0){
			$this->kqanaymonth($uid, $month);
			return 1;
		}
		$month	= substr($month, 0, 7);
		$max 	= $this->dtobj->getmaxdt($month);
		$urows 	= $this->getModel('userinfo')->where('cid', $this->companyid)->whereRaw($this->getuwhere($month))->get();
		$oi 	= 0;
		foreach($urows as $k=>$urs){
			$this->kqanaymonth($urs->aid, $month, $urs, $max);
			$oi++;
		}
		return $oi;
	}
	
	public function getuwhere($month)
	{
		$month	= substr($month, 0, 7);
		$start	= ''.$month.'-01';
		$enddt 	= $this->dtobj->getenddt($month);
		$where 	= "(`quitdt` is null or `quitdt`>='$start') and (`workdate` is null or `workdate`<='$enddt')";
		return $where;
	}
	
	/**
	*	人员分析整月
	*/
	public function kqanaymonth($uid, $month, $urs=false, $max=0)
	{
		$month	= substr($month, 0, 7);
		if(!$urs)$urs 	= $this->getainfo($uid);
		if(!$urs)return;
		$urs	= array(
			'id' => $uid,
			'workdate' => $urs->workdate,
			'quitdt' => $urs->quitdt,
		);
		if($max==0)$max = $this->dtobj->getmaxdt($month);
		for($i=1; $i<=$max; $i++){
			$oi = $i;if($oi<10)$oi='0'.$i.'';
			$dt = ''.$month.'-'.$oi.'';
			if(!isempt($urs['workdate']) && $urs['workdate']>$dt)continue;
			if(!isempt($urs['quitdt']) && $urs['quitdt']<$dt)continue;
			$this->kqanay($uid, $dt);
		}
		$this->delquitwork($urs, $month, $max);
	}
	private function delquitwork($urs, $month, $max)
	{
		$ds = array();
		$dt1= ''.$month.'-01'; $dt2= ''.$month.'-'.$max.'';
		$uid= $urs['id'];
		if(!isempt($urs['workdate'])){
			if($urs['workdate']>$dt1)$ds[] = "dt<'".$urs['workdate']."'";
		}
		if(!isempt($urs['quitdt'])){
			if($urs['quitdt']<$dt2)$ds[] = "dt>'".$urs['quitdt']."'";
		}
		$str = join(' or ', $ds);
		if(!isempt($str))
		DB::table('kqanay')->where('aid', $uid)->whereRaw('('.$str.')')->delete();
	}
	
	
	/**
	*	考勤状态
	*/
	public function getkqstate($rs)
	{
		$s 	 	= $rs->state;
		$state 	= $rs->state;
		$iswork = $rs->iswork;
		
		$miaocn	= '';
		if($rs->emiao>0){
			$stssa = explode(':', $this->dtobj->sjdate($rs->emiao,'H:i:s'));
			if($stssa[0]>0)$miaocn=''.$stssa[0].'时';
			$miaocn.=''.$stssa[1].'分'.$stssa[2].'秒';
		}
		
		
		if($iswork==0 && $state == '未打卡')$s='休息日';
		if($miaocn!=''){
			$s.='['.$miaocn.']';
		}
		if(!isempt($rs->time))$s.='('.substr($rs->time,11).')';
		
		if(!isempt($rs->states)){
			$state = '正常';
			$s	= $rs->states;
		}
		
		if($state != '正常' && $iswork==1){
			if($s=='未打卡'){
				$s='<font color=red>'.$s.'</font>';
			}else{
				$s='<font color=blue>'.$s.'</font>';
			}
		}
		if($s=='休息日')$s='<font color=#888888>'.$s.'</font>';
		
		return $s;
	}
	
	/**
	*	获取考勤分析
	*/
	public function getanay($uid, $month, $dt='')
	{
		$month	= substr($month, 0, 7);
		$max 	= $this->dtobj->getmaxdt($month);
		$startdt= ''.$month.'-01';
		$enddt  = ''.$month.'-'.$max.'';
		if($dt!=''){
			$startdt = $dt;
			$enddt   = $dt;
		}
		$rows 	= DB::table('kqanay')->where('aid', $uid)->whereBetween('dt',[$startdt, $enddt])->orderBy('dt')->orderBy('sort')->get();
		$barr 	= array();
		foreach($rows as $k=>$rs){
			if(!isset($barr[$rs->dt]))$barr[$rs->dt]=array();
			
			$barr[$rs->dt][] 	= $rs;
		}
		return $barr;
	}
	
	/**
	*	分析统计对应次数，返回{"正常":0,"迟到":2,"state0":1}
	*/
	public function anaytotal($aid, $month, $ztarr)
	{
		$rows 	= DB::table('kqanay')->where('aid', $aid)->where('dt','like',''.$month.'%')->get();
		$timesb = $timeys = 0;
		$zcraa	= array();
		foreach($rows as $k=>$rs){
			if($rs->iswork==0)continue;
			$timesb+=$rs->timesb;
			$timeys+=$rs->timeys;
			$state  = $rs->state;
			if(!isempt($rs->states))$state = '正常';
			if(isset($ztarr[$state]))$state = $ztarr[$state];
			if(!isset($zcraa[$state]))$zcraa[$state]=0;
			$zcraa[$state]++;
		}
		$zcraa['timesb'] = $timesb;
		$zcraa['timeys'] = $timeys;
		return $zcraa;
	}
	
	/**
	*	请假统计，和加班，外出，返回小时{"事假":0,"年假":2.5,"qjtype0":5}
	*/	
	private $qjtotalarr = array();
	public function qjtotal($aid, $month='')
	{
		if(!$this->qjtotalarr){
			$qjarr	= $this->getNei('option')->getdata('kaoqinqjtype');
			$qjarr[]= array('name'=>'加班');
			foreach($qjarr as $k=>$rs)$this->qjtotalarr[$rs['name']]='qjtype'.$k.'';
		}
		$ztarr	= $this->qjtotalarr;
		if($month=='')$month = date('Y-m');
		$rows = DB::table('kqleavs')->where(['aid'=>$aid,'status'=>1])->where('stime','like',''.$month.'%')->get();
		$zcraa	= array();
		foreach($rows as $k=>$rs){
			$state  = $rs->qjtype;
			
			if(isset($ztarr[$state]))$state = $ztarr[$state];
			if(!isset($zcraa[$state]))$zcraa[$state]=0;
			$zcraa[$state]+=$rs->totals;
		}
		
		//加班统计
		$jrs = DB::table('kqjiaban')->select(DB::raw('sum(totals)totals'))->where(['aid'=>$aid,'status'=>1])->where('startdt','like',''.$month.'%')->first();
		$zcraa['jiaban'] = $jrs->totals;
		
		$zcraa['waichu'] = DB::table('kqout')->where(['aid'=>$aid,'status'=>1])->where('startdt','like',''.$month.'%')->count();

		$zcraa['dkerr']	 = DB::table('kqdkerr')->where(['aid'=>$aid,'status'=>1])->where('dt','like',''.$month.'%')->count();
		
		return $zcraa;
	}
	
	/**
	*	读取某人一天考勤类型状态
	*/
	private $ztbpularr = array();
	public function getkqztarr($uid, $dt)
	{
		if($this->ztbpularr)return $this->ztbpularr;
		$kqzt 	= $this->getkqsj($uid, $dt);
		$kqsr 	= array(); $xu 	= 0;
		foreach($kqzt as $k=>$rs){
			foreach($rs['children'] as $rs1){
				if(!isset($kqsr[$rs1['name']])){
					$kqsr[$rs1['name']]='state'.$xu.'';
					$xu++;
				}
			}
		}
		$kqsr['未打卡']='weidk';
		$this->ztbpularr = $kqsr;
		return $kqsr;
	}
	
	/**
	*	获取默认某天应该上班时间段
	*	返回array(array('每天上班时间断'))
	*/
	public function getsbarr($uid, $dt)
	{
		$arr 	= $this->getkqsj($uid, $dt, 1);
		$barr	= array();
		foreach($arr as $k=>$rs){
			if(!isempt($rs['stime']) && !isempt($rs['etime']))$barr[] = $rs;
		}
		return $barr;
	}
	
	public function sendanay($dt, $flow)
	{
		$putss	= Cache::get('kaoqinanay'.$this->companyid.'');
		if(!isempt($putss)){
			return returnerror('考勤重新分析不要太频繁，可在'.(300-time()+$putss).'秒后在操作');
		}
		$flow->addqueue('kaoqin','anay', array(
			'dt' => $dt
		));
		Cache::put('kaoqinanay'.$this->companyid.'', time(), 5);
		return returnsuccess('已加入异步队列，可稍后来查看');
	}

	
	/**
	*	根据时间间隔获取上班时间小时
	*	$lx=0 计算请假时间 1算当前应上班时间
	*/
	public function getsbtime($uid,$sdt, $edt, $lx=0)
	{
		$tot	= 0;
		$sdt1	= strtotime($sdt);
		$edt1	= strtotime($edt);
		$dtsa	= explode(' ', $sdt);
		$dts	= $dtsa[0];
		$jg		= $this->dtobj->datediff('d', $sdt, $edt);
		for($i=0; $i<$jg+1; $i++){
			if($i>0)$dts = $this->dtobj->adddate($dts, 'd', 1);
			if($lx==0)if($this->isworkdt($uid, $dts)==0)continue;//休息日就不用算了
			$arr 	= $this->getsbarr($uid, $dts);
			foreach($arr as $k=>$rs){
				$iskt	= $rs['iskt'];
				if($rs['iskq']==1 && $rs['isxx']=='0'){
					$_sts = strtotime($dts.' '.$rs['stime']);
					$_ets = strtotime($dts.' '.$rs['etime']);
					
					//开始时间-1
					if($iskt=='2'){
						
					}
					
					//结束时间+1
					if($iskt=='1'){
					}
					
					if($_sts<$sdt1)$_sts=$sdt1;
					if($_ets>$edt1)$_ets=$edt1;
					$_tisg = $_ets - $_sts;
					if($_tisg>0)$tot+=$_tisg;
				}
			}
		}
		return $tot / 3600;
	}
	
	/**
	*	每天上班时间断，返回8小时
	*/
	public function getworktime($uid, $dt='')
	{
		if($dt=='')$dt = nowdt('dt');
		$dt 	= substr($dt, 0, 10);
		$to 	= (int)$this->getNei('option')->getval('kaoqinsbtotal');
		if($to==0)$to 	= $this->getsbtime($uid, $dt.' 00:00:00', $dt.' 23:59:59', 1);
		if($to<=0)$to = 8;//默认1天上班时间8小时
		return $to;
	}
	
	/**
	*	计算剩余假期时间,如果审核未通过，申请人不删除照样也会扣除时间
	*/
	public function getqjsytime($uid, $type, $dt='', $id=0)
	{
		
		if($dt=='')$dt = nowdt();
		$zto	= $to1 = 0;
		$enddt 	= '`id`<>'.$id.'';//截止
		
		//总共的
		$zrs	= DB::table('kqjiaqi')
					->select(DB::raw('sum(totals)totals'), DB::raw('min(enddt)enddt'))
					->where(['aid'=>$uid,'jiatype'=>$type,'status'=>1])
					->where('startdt','<=', $dt)
					->whereRaw("(`enddt` is null or `enddt`>='$dt')")
					->first();
		if($zrs){
			$zto 	= floatval($zrs->totals);
			if(!isempt($zrs->enddt))$enddt.="and `stime`>='".$zrs->enddt."'";
		}

		//已使用了
		if($zto>0){
			$srs 	= DB::table('kqleavs')
						->select(DB::raw('sum(totals)totals'))
						->where(['aid'=>$uid,'qjtype'=>$type])
						->where('status','<>',5)
						->whereRaw($enddt)
						->first();
			if($srs){
				$to1 = floatval($srs->totals);
			}
		}
		$wjg 	= $zto - $to1;
		return $wjg;
	}
}