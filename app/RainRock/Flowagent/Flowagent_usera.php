<?php
/**
*	应用.用户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use App\Model\Base\DeptModel;
use App\Model\Base\UseraModel;

class Flowagent_usera  extends Rockflow
{
	//默认排序
	public $defaultorder = 'sort,desc';
	public $fieldsaid = 'id';
	
	protected $flowisturnbool	= false;
	
	protected function flowinit()
	{
		$this->statusbilarr	= array('待加入','已加入','已停用');
		$this->statuscolarr	= array('red','green','#aaaaaa');
		
		$this->grendarr		= array('未知','男','女');	
		$this->tongxlarr	= array('否','是');	
		$this->typearr		= array(trans('table/usera.type0'),trans('table/usera.type1'));	
	}
	
	
	public function flowlistoption()
	{
		$barr[] 	 = [
			'name' => '更新数据',
			'click'=> 'updatealldata',
			'class'=> 'success',
			'icons'=> 'repeat'
		];
		
		return $barr;
	}
	
	//用户级别
	public function typestore()
	{
		$barr[] = array(
			'value' => 0,
			'name'  => trans('table/usera.type0')
		);
		$barr[] = array(
			'value' => 1,
			'name'  => trans('table/usera.type1')
		);
		return $barr;
	}
	
	//默认投票的是隐藏的
	protected function flowinputfields($farr)
	{
		$zt = objvalue($this->rs,'status', 0);
		foreach($farr as $k=>$rs){
			//if($rs->fields=='user' && !isempt($this->rs->user) && $zt==1){
			//	$farr[$k]->attr = 'readonly';
			//}
			if($rs->fields=='mobile' && $zt==1){
				$farr[$k]->attr = 'readonly mobile';
			}
		}
		return $farr;
	}
	
	//保存之前判断
	public function flowsavebefore($rs, $mid=0)
	{
		$data = array();
		if(isempt($rs->pingyin))$data['pingyin'] = c('pingyin')->get($rs->name);
		
		//是不是超过了
		if($this->mid==0){
			$flaskm = $this->companyinfo->flaskm;
			$flasks = $this->companyinfo->flasks;
			if($flasks>=$flaskm)return returnerror(trans('table/usera.extnot'));
		}
		
		//判断手机号
		if(isset($rs->mobile)){
			if(!c('check')->iscnmobile($rs->mobile))return '手机号格式错误';
			$to = UseraModel::where('cid', $this->companyid)
						->where('id','<>', $this->mid)
						->where('mobile', $rs->mobile)
						->count();
			if($to>0)return '手机号'.$rs->mobile.'已存在';			
		}
		
		//判断用户名
		if(isset($rs->user)){
			if(c('check')->isincn($rs->user))return '用户名不能包含中文';
			$to = UseraModel::where('cid', $this->companyid)
						->where('id','<>', $this->mid)
						->where('user', $rs->user)
						->count();
			if($to>0)return '用户名'.$rs->user.'已存在';		
		}
		
		//以下特殊判断
		$nosave	= '';
		if($mid==0){
			$data['uid'] 	= 0;
			$data['status'] = 0;
		}else{
			if($rs->uid==0 && $this->dataold->status==1){
				$data['status'] = 0;//未加入
			}else{
				$nosave = 'status';
			}
		}
		
		return array(
			'data' 		=> $data,
			'nosave' 	=> $nosave
		);
	}
	
	//保存后处理
	public function flowsaveafter($arr)
	{
		if(!$this->isdaorubool)$this->post_updatealldata(); //不是导入要更新
	}
	
	public function post_updatealldata()
	{
		c('usera', $this->useainfo)->reloaddata();
		return returnsuccess('更新成功');
	}
	
	
	public function flowreplacers($rs)
	{
		if($rs->status!=1)$rs->ishui = 1;
		$rs->gender = $this->grendarr[$rs->gender];
		$rs->type = $this->typearr[$rs->type];
		$rs->istxl = $this->tongxlarr[$rs->istxl];
		return $rs;
	}
	
	//是否可以删除
	public function flowisdelqx()
	{
		
		if($this->mid==$this->useaid)return 0; //不能删除自己
		if($this->rs->uid==$this->useainfo->company->uid)return 0; //不能删除创建人
	}
	
	//启用时
	public function flowoptmenu($optrs)
	{
		if($optrs->num=='qiyong'){
			$status = 0;
			if($this->rs->uid>0)$status = 1;
			$this->updateData(['status'=>$status]);
		}
	}
	
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		$this->deptroot = c('dept', $this->useainfo)->getroot();
		return array(
			'user' 		=> 'zhangsan',
			'name' 		=> '张三',
			'gender' 	=> '男',
			'mobile' 	=> '15812345678',
			'position' 	=> '程序员',
			'superman' 	=> '磐石',
			'deptname' 	=> ''.$this->deptroot->name.'/技术部',
			'tel' 		=> '0592-1234567-005',
			'email' 	=> 'zhangsan@rockoa.com',
			'workdate' 	=> '2017-01-17',
		);
	}
	
	//导入之前判断
	private $superarrar = array();
	public function flowdaorubefore($rows)
	{
		$inarr	= array();
		$this->deptroot = c('dept', $this->useainfo)->getroot();
		$dname	= $this->deptroot->name;
		$py		= c('pingyin');
		foreach($rows as $k=>$rs){
			$arr	= $rs;
			
			$arr['pingyin']		= $py->get($rs['name']);
			if(isempt(arrvalue($arr,'user')))$arr['user'] = $arr['pingyin']; //没有用户名默认拼音
			
			//读取上级主管Id
			if(isset($arr['superman'])){
				$this->superarrar[$arr['superman']][] = $arr['user'];
			}
			$arr['superman'] = '';
			$arr['superid']  = '';
			$arr['gender']	 = ($rs['gender']=='女') ? 2 :1;
			
			//读取部门Id
			$deptarr 	= $this->getdeptid($rs['deptname']);
			
			if($deptarr['deptid']==0)return '行'.($k+1).'找不到顶级部门['.$rs['deptname'].'],请写完整部门路径如：'.$dname.'/'.$rs['deptname'].'';
			
			foreach($deptarr as $k1=>$v1)$arr[$k1]=$v1;
			
			$inarr[] = $arr;
		}
		
		return $inarr;
	}
	private function getdeptid($str)
	{
		$deptid = '0';
		if(isempt($str))return $deptid;
		$stra 	= explode(',', $str);
		$depad	= $this->getdeptids($stra[0]);
		$deptids= '';
		$deptnames= '';
		for($i=1;$i<count($stra);$i++){
			$depads	= $this->getdeptids($stra[$i]);
			if($depads[0]>0){
				$deptids.=','.$depads[0].'';
				$deptnames.=','.$depads[1].'';
			}
		}
		if($deptids!='')$deptids = substr($deptids, 1);
		if($deptnames!='')$deptnames = substr($deptnames, 1);
		
		return array(
			'deptid' 	=> $depad[0],
			'deptname' 	=> $depad[1],
			'deptallname' 	=> $stra[0],
			'deptids' 	=> $deptids,
			//'deptnames' 	=> $deptnames,
		);
	}
	private function getdeptids($str)
	{
		$stra	= explode('/', $str);
		$pid 	= 0;
		$id 	= $this->deptroot->id;//默认顶级ID
		$deptname = '';
		for($i=0;$i<count($stra);$i++){
			$name = $stra[$i];
			$deptname = $name;
			$id   	= 0;
			$dors   = DeptModel::where('cid', $this->companyid)->where('pid', $pid)->where('name', $name)->first();
			if($dors)$id = $dors->id;
			//不存在就创建部门
			if($id==0){
				if($pid==0)return array(0, $deptname);
				$obj 		= c('dept', $this->useainfo)->save($this->companyid, 0, [
					'name' 	=> $deptname,
					'pid' 	=> $pid,
				]);
				$id 	= $obj->id;
				$pid 	= $id;
			}else{
				$pid = $id;
			}
		}
		
		return array($id, $deptname);
	}
	
	//导入完成后处理
	public function flowdaoruafter()
	{
		//更新设置上级主管
		foreach($this->superarrar as $superman=>$suparr){
			$nodrs				= UseraModel::where('cid', $this->companyid)->where('name', $superman)->first();
			if(!$nodrs)continue;
			$superid			= $nodrs->id;
			if($superid>0){
				UseraModel::where('cid', $this->companyid)
					->whereIn('user', $suparr)
					->update(array(
						'superman' 	=> $superman,
						'superid' 	=> $superid,
					));
			}
		}
		c('usera', $this->useainfo)->reloaddata();
	}
}