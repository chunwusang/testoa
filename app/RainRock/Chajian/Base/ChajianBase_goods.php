<?php
/**
*	插件-公共分类
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use DB;

class ChajianBase_goods extends ChajianBase
{
	
	
	//判断是否存在相同物品
	public function existsgoods($rs, $id=0)
	{
		$to 	= $this->getModel('goods')->where('id','<>', $id)->where([
			'cid' 		=> $this->companyid,
			'name' 		=> $rs['name'],
			'guige' 	=> $rs['guige'],
			'xinghao' 	=> $rs['xinghao'],
		])->count();
		return $to>0;
	}
	
	/**
	*	0入库,1出库
	*/
	public function getopttype($type)
	{
		$typeaa = array('入库','出库');
		return $typeaa[$type];
	}
	
	public function getoptkindarr()
	{
		$kind0 = explode(',','初始入库,采购入库,生产入库,归还入库,退货入库,调拨入库');

		$kind0[21] = '领用出库';
		$kind0[22] = '销售出库';
		$kind0[23] = '调拨出库';
		
		return $kind0;
	}
	
	/**
	*	获取仓库下拉框
	*/
	public function godepotarr($glx=0)
	{
		$depotarr = $this->getModel('godepot')->where('cid', $this->companyid)->orderBy('sort')->get();
		$rows 		= array();
		foreach($depotarr as $k=>$rs){
			$name = $rs->depotname;
			if($rs->depotnum)$name = ''.$rs->depotnum.'.'.$name.'';
			if($glx==1){
				$rows[$rs->id] = $name;
			}else{
				$rows[] = array(
					'name' 	=> $name,
					'value' => $rs->id,
				);
			}
		}
		return $rows;
	}
	
	
	/**
	*	更新总库存
	*/
	public function setstock()
	{
		$rows = DB::table('goodx')
				->select(DB::raw('sum(count)stock, goodsid'))
				->where('cid', $this->companyid)
				->groupBy('goodsid')
				->get();
		DB::table('goods')->where('cid', $this->companyid)->update(['stock'=>0]);
		foreach($rows as $k=>$rs){
			DB::table('goods')->where('id', $rs->goodsid)->update(['stock'=>$rs->stock]);
		}
	}
	
	/**
	*	获取物品列表
	*/
	public function getdata()
	{
		$rows 	= $this->getModel('goods')->where('cid', $this->companyid)->orderBy('sort')->get();
		$barr   = array();
		foreach($rows as $k=>$rs){
			$subname = $rs->xinghao.$rs->guige;
			$barr[] = array(
				'value' => $rs->id,
				'name' 	=> $rs->name,
				'subname'=> $subname,
				'unit' 	=> $rs->unit,
				'price' => $rs->price,
			);
		}
		return $barr;
	}
	
	/**
	*	供应商数据
	*/
	public function gysdata()
	{
		$rows 	= $this->getModel('goodgys')->where(['cid'=>$this->companyid,'isgys'=>1,'status'=>1,'isturn'=>1])->get();
		$barr   = array();
		foreach($rows as $k=>$rs){
			$barr[] = array(
				'value' => $rs->id,
				'name' 	=> $rs->name,
				'subname'=> $rs->unitname,
			);
		}
		return $barr;
	}
	
	/**
	*	根据主表Id获取申请物品信息, $glx 0原始数组,1字符串
	*/
	public function getgoodninfo($rows, $mgx=5)
	{
		$str 	= '';
		foreach($rows as $k1=>$rs1){
			if($k1>$mgx)break;
			$str.=''.$rs1->goodsname.'';
			$str .=':'.$rs1->count.''.$rs1->unit.';';
		}
		return $str;
	}
	
	/**
	*	单据类型
	*/
	public function showgoodmtype($rs)
	{
		$type 	 = $rs->type;
		$typearr = explode(',', '0领用,1采购,2调拨,3归返,4销售,5生产');
		$typeara = explode(',', 'goodling,goodcai,gooddiao,goodgui,goodxiao,goodmake');
		
		$lxda	 = '出';
		if(in_array($type,[1,3,5]))$lxda='入';
		$rs->type = substr($typearr[$rs->type],1).'单';
		
		$zt   = $rs->state;
		$ztna = array('待'.$lxda.'库','已'.$lxda.'库','已部分'.$lxda.'库');
		$ztnc = array('red','green','#ff6600');
		$rs->state 		= '<font color="'.$ztnc[$zt].'">'.$ztna[$zt].'</font>';
		$rs->agenhnum 	= $typeara[$type];
		return $rs;
	}
	
	//跟进type他是啥样类型
	public function gettypearr($goodmid)
	{
		$rs = DB::table('goodm')->find($goodmid);
		$type = $rs->type;
		$lx  = 1;//1出
		if(in_array($type,[1,3,5]))$lx=0;

		$kinda[0] = 21; //领用 
		$kinda[1] = 1; //采购
		$kinda[2] = 5;
		$kinda[3] = 3;
		$kinda[4] = 22;
		$kinda[5] = 2;
		
		return array($lx, $kinda[$type]);
	}
	
	
	/**
	*	主表goodm部分出入库状态更新
	*/
	public function upstatem($mid=0)
	{
		$where['cid'] 			= $this->companyid; 
		if($mid>0)$where['id']  = $mid; 
		$rows = DB::table('goodm')->where($where)->get();
		
		foreach($rows as $k=>$rs){
			$id 	= $rs->id;
			$state 	= $rs->state;
			$rsone 	= DB::table('goodn')->select(DB::raw('sum(`couns`)couns'),DB::raw('sum(`count`)count'))->where('mid', $id)->first();
			$count 	= floatval($rsone->count);
			$couns 	= floatval($rsone->couns);
			if($couns>=$count){
				$zt = 1;
			}else if($couns==0){
				$zt = 0;
			}else{
				$zt = 2;
			}
			if($state!=$zt)DB::table('goodm')->where('id', $id)->update(['state'=>$zt]);
		}
	}
	
	/**
	*	刷新采购金额，已审核的
	*/
	public function reloadcgmoney()
	{
		$this->getModel('goodgys')->where('cid', $this->companyid)->update(['moneyg'=>'0']);
		$rows 	= DB::table('goodm')->select('custid', DB::raw('sum(`money`)money'))->where([
			'cid'	=> $this->companyid,
			'status'=> 1,
			'isturn'=> 1,
			'type'	=> 1 //1是采购的
		])->groupBy('custid')->get();
		foreach($rows as $k=>$rs){
			$this->getModel('goodgys')->where('id', $rs->custid)->update(['moneyg'=>$rs->money]);
		}
	}
}