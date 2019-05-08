<?php
/**
*	应用.人员档案
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use App\Model\Base\UseraModel;

class Flowagent_userinfo  extends Rockflow
{
	
	//详情页宽度
	//protected $detailbodywidth = '900px';	
	
	protected function flowinit()
	{
		$this->changeStatus();	
		$this->grendarr		= array('未知','男','女');
		$this->userinfostate= $this->getNei('option')->getdata('userinfostate');
		//人员状态
	}
	
	//标题返回空就是去掉
	public function getDatailtitle1()
	{
		return '';
	}

	
	public function flowreplacers($rs)
	{
		if($rs->status!=1 || $rs->state==5)$rs->ishui = 1;
		$rs->gender = $this->grendarr[$rs->gender];
		foreach($this->userinfostate as $k1=>$rs1){
			if($rs1['value']==$rs->state){
				$rs->state = $rs1['name'];
				break;
			}
		}
		return $rs;
	}
	
	//保存之前判断
	public function flowsavebefore($arr)
	{
		$data 	= array();
		$aid 	= (int)$arr->aid;
		if($aid>0){
			$to = $this->getModel()->where('id','<>', $this->mid)->where('aid',$aid)->count();
			if($to>0)return '此用户人员已创建过了档案了';
		}
		
		return array(
			'data' => $data
		);
	}
	
	//保存之后，跟用户表上同步
	public function flowsaveafter($arr)
	{
		$aid 	= (int)$arr->aid;
		if($aid>0){
			$this->tongbu($aid, $this->mid);
		}
	}
	
	//同步
	public function tongbu($usera, $obj)
	{
		if(is_numeric($usera))$usera = UseraModel::find($usera);
		if(!$usera)return;
		$ufield= explode(',','name,user,position,mobile,mobilecode,email,deptid,deptname,deptids,deptallname,deptpath,superid,superman,superpath,grouppath,tel,sort,gender,pingyin');
		if(is_numeric($obj))$obj 	= $this->getModel()->find($obj);

		foreach($ufield as $fid){
			$obj->$fid = $usera->$fid;
		}
		return $obj->save();
	}
	
	public function flowlistoption()
	{
		$barr	= array();
		$barr['checkcolums'] = true;
		$barr['btnarr'][] 	 = [
			'name' => '从用户同步更新到档案',
			'click'=> 'updateshuju',
			'class'=> 'success',
		];
		$barr['btnarr'][] 	 = [
			'name' => '从用户中创建档案',
			'click'=> 'creanedanan',
			'class'=> 'info',
		];
		return $barr;
	}
	
	//同步数据
	public function post_updateshuju()
	{
		$rows = $this->getModel()->where('cid',$this->companyid)->where('aid','<>',0)->get();
		$uobj = $this->getNei('usera');
		foreach($rows as $k=>$rs){
			$uobj->reloaddata(0, $rs->aid);
			$this->tongbu($rs->aid, $rs);
		}
		return returnsuccess();
	}
	
	//创建不存在用户的档案
	public function post_creanedanan()
	{
		$rows = UseraModel::where('cid',$this->companyid)->get();
		foreach($rows as $k=>$rs){
			$aid 	= $rs->id;
			$obj 	= $this->getModel()->where(['cid'=>$this->companyid,'aid'=>$aid])->first();
			if(!$obj){
				$obj = $this->getModel();
				$obj->cid = $this->companyid;
				$obj->aid = $aid;
				$obj->isturn 	= 1;
				$obj->state 	= 1;
				$obj->workdate = nowdt('dt');
				$obj->minzu = '汉族';
				$obj->optdt = nowdt();
				$obj->createdt = nowdt();
				$this->tongbu($rs, $obj);
			}
		}
		return returnsuccess();
	}
	
	
	//获取合同
	public function get_gethetong($request)
	{
		$aid = $request->input('aid');
		$str = $this->getNei('flow')->getDatatable('userract', '`aid`='.$aid.'');
		return returnsuccess($str);
	}
}