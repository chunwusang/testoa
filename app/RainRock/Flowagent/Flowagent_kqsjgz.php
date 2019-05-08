<?php
/**
*	应用.考勤时间规则
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqsjgz  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	public $defaultorder		= 'pid,asc|sort,asc';
	
	public function flowgetdata($rows)
	{
		$nrows = array();
		foreach($rows as $k=>$rs){
			$rs->iskt = '';
			$rs->qtype = '';
			$rs->iskq = '';
			$rs->isxx = '';
			$rs->level = 1;
			$nrows[] = $rs;
			$drows1 = $this->getModel()->where('pid', $rs->id)->orderBy('sort')->get();
			foreach($drows1 as $k1=>$rs1){
				$rs1->name = '&nbsp; '.$rs1->name.'';
				if($rs1->iskq=='1'){
					$rs1->iskq = '<font color=green>√</font>';
				}else{
					$rs1->iskq = '';
				}
				if($rs1->isxx=='1'){
					$rs1->isxx = '';
				}else{
					$rs1->isxx = '<font color=green>√</font>';
				}
				if($rs1->qtype=='0'){
					$rs1->qtype = '最小值';
				}else{
					$rs1->qtype = '<font color=#ff6600>最大值</font>';
				}
				$rs1->level = 2;
				$nrows[] = $rs1;
				
				$drows2 = $this->getModel()->where('pid', $rs1->id)->orderBy('sort')->get();
				foreach($drows2 as $k2=>$rs2){
					$rs2->name = '&nbsp; &nbsp; &nbsp; &nbsp; '.$rs2->name.'';
					$rs2->iskq = '';
					$rs2->isxx = '';
					if($rs2->qtype=='0'){
						$rs2->qtype = '最小值';
					}else{
						$rs2->qtype = '<font color=#ff6600>最大值</font>';
					}
					$rs2->level = 3;
					$nrows[] = $rs2;
				}
			}
		}
		foreach($nrows as $k1=>$d){
			$v = $d->stime;
			$s = $v;
			if($d->level>1 && $d->iskt==2){
				$s=''.$v.'<font color=red>(-1天)</font>';
			}
			if($d->level>1 && $d->iskt==1 && $v<$d->etime){
				$s=''.$v.'<font color=red>(+1天)</font>';
			}
			$d->stime = $s;	
		}
		
		foreach($nrows as $k1=>$d){
			$v = $d->etime;
			$s = $v;
			if($d->level>1 && $d->iskt==1){
				$s=''.$v.'<font color=red>(+1天)</font>';
			}
			if($d->level>1 && $d->iskt==2 && $d->stime<$v){
				$s=''.$v.'<font color=red>(-1天)</font>';
			}
			$d->etime = $s;	
		}
		return array(
			'rows' => $nrows
		);
	}
	
	//判断是否可以删除
	protected function flowdelbillbefore()
	{
		$to 	 = $this->getModel()->where('pid', $this->mid)->count();
		if($to>0)
			return '有下级规则名称不能删除';
	}
	
	public function flowlistoption()
	{
		$barr	= array();
		$barr['btnarr'][] 	 = [
			'name' => '导入默认考勤规则',
			'click'=> 'daorudevgui',
			'class'=> 'default',
			'icons'=> 'plus'
		];
		return $barr;
	}
	
	//导入默认考勤规则
	public function get_daorudevgui()
	{
		$pid = $this->getModel()->insertGetId([
			'cid' => $this->companyid,
			'name' => '默认考勤规则',
		]);
		$sjar[] = array(
			'name' => '上班',
			'stime' => '09:00:00',
			'etime' => '12:00:00',
		);
		$sjar[] = array(
			'name' => '下班',
			'stime' => '13:00:00',
			'etime' => '18:00:00',
			'qtype'	=> 1
		);
		
		$stimes[0][] = array(
			'name' => '正常', 
			'stime' => '06:00:00', 
			'etime' => 	'09:00:00', 
		);
		$stimes[0][] = array(
			'name' => '迟到', 
			'stime' => '09:00:01', 
			'etime' => 	'12:00:00', 
		);
		
		$stimes[1][] = array(
			'name' => '正常', 
			'stime' => '18:00:00', 
			'etime' => 	'23:59:59', 
			'qtype'	=> 1
		);
		$stimes[1][] = array(
			'name' => '早退', 
			'stime' => '13:00:00', 
			'etime' => 	'17:59:59', 
			'qtype'	=> 1
		);
		
		foreach($sjar as $k1=>$rs){
			$rs['cid'] = $this->companyid;
			$rs['pid'] = $pid;
			$rs['sort'] = $k1;
			$sid = $this->getModel()->insertGetId($rs);
			
			foreach($stimes[$k1] as $k2=>$rs2){
				$rs2['cid'] = $this->companyid;
				$rs2['pid'] = $sid;
				$rs2['sort'] = $k2;
				$this->getModel()->insert($rs2);
			}
		}

		return returnsuccess();
	}
}