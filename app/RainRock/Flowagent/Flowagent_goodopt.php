<?php
/**
*	应用.物品出入库
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use DB;

class Flowagent_goodopt  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	public $defaultorder		= 'sort,asc|id,desc';
	public $classtypenum		= 'goodstype';
	
	
	protected function flowinit()
	{
		$this->changeStatus();	
		$this->classifyobj = $this->getNei('classify');
		$this->goodsobj = $this->getNei('goods');
		$this->classifyobj->changetype($this->classtypenum, '物品分类');
		$this->goodmid = 0;
	}
	
	public function flowreplacers($rs)
	{
		if($this->goodmid==0){
			if($rs->stock<=0)$rs->ishui=1;
			$rs->churukushu = '<input type="number" onfocus="js.focusval=this.value" onblur="js.number(this)" tempid="'.$rs->id.'" min="0" class="form-control" style="width:80px">';
			$rs->agenhnum	= 'goods';
			$rs->mid		= $rs->id;
		}else{
			
			$maxshu	= $rs->count-$rs->couns;
			$rs->agenhnum	= 'goods';
			$rs->mid		= $rs->goodsid;
			$rs->churukushu = '<input type="number" tempid="'.$rs->id.'" min="0" max="'.$maxshu.'" class="form-control" onfocus="js.focusval=this.value" onblur="js.number(this)" style="width:80px">可在输入'.$maxshu.''.$rs->unit.'';
			$rs->name 		= $rs->goodsname;
			
			$goodsinfo 		= $this->getModel()->find($rs->goodsid);
			if($goodsinfo){
				$rs->xinghao = $goodsinfo->xinghao;
				$rs->guige 	= $goodsinfo->guige;
				$rs->stock 	= $goodsinfo->stock;
				if($rs->price==0)$rs->price = $goodsinfo->price;
				$rs->classname = $goodsinfo->classname;
				$rs->num = $goodsinfo->num;
			}
			
		}
		return $rs;
	}
	
	public function flowbillwhere($obj, $atype, $glx=1)
	{
		$classid 	 	= (int)\Request::get('classid','0');
		$goodmid 	 	= (int)\Request::get('goodmid','0');
		$this->classid 	= $classid;
		$this->goodmid 	= $goodmid; //操作出入库
		if($goodmid==0){
			if($classid>0){
				return $obj->where('classid', $classid);
			}
		}else if($atype=='all'){
			return DB::table('goodn')->where(['mid'=>$goodmid,'cid'=>$this->companyid])->whereRaw('`couns`<`count`');
		}
		
		if($atype=='daichurku' && $glx==2){
			return $this->getoptobjs();
		}
		
		return false;
	}
	
	private function getoptobjs()
	{
		return DB::table('goodm')->where([
				'cid'=>$this->companyid,
				'status' => 1,
				'isturn' => 1
			])->where('state','<>', 1);
	}
	
	//应用数据处理，获取到路径
	public function flowgetdata()
	{
		$lujarr[] = array(
			'name' 		=> '所有物品',
			'id' 		=> 0,
			'pid'  		=> 0,
		);
		if($this->classid>0){
			$lujarrs = $this->classifyobj->getpatharr($this->classid);
			unset($lujarrs[0]);
			$lujarr  = array_merge($lujarr, $lujarrs);
		}
		return [
			'lujarr' => $lujarr,
		];
	}
	
	
	protected function flowlistoption()
	{
		
		$flarr		= $this->classifyobj->getdata($this->classtypenum);
		$leftstr	= '<select class="form-control" id="souclassid" style="width:130px">';
		$leftstr	.= '<option value="0">所有分类</option>';
		foreach($flarr as $k=>$rs){
			$str = '';
			if($rs['level']>0){
				for($i=0;$i<$rs['level'];$i++)$str.='&nbsp;&nbsp;';
				$str.='├';
			}
			$leftstr	.= '<option value="'.$rs['value'].'">'.$str.''.$rs['name'].'</option>';
		}
		$leftstr	.= '</select>';
		
		$barr[] 	 = [
			'name' 	=> '刷新库存',
			'click'	=> 'shuaxinstock',
			'class'	=> 'default'
		];
		
		return [
			'btnarr' => $barr,
			'leftstr'=> $leftstr,
		];
	}
	
	public function flowlistview($lx)
	{
		$kindarr = $this->goodsobj->getoptkindarr();
		$godepot = $this->goodsobj->godepotarr();
		$typearr = array('入库','出库');
		if($this->goodmid>0){
			$typeaa  = $this->goodsobj->gettypearr($this->goodmid);
			$type 	 = $typeaa[0];
			$kind 	 = $typeaa[1].'';
			$typearrarr = array();
			$typearrarr[$type] = $typearr[$type]; //出库还入库
			 
			$kindarrarr = array();
			$kindarrarr[$kind] = $kindarr[$kind]; //出入库类型
		}else{
			$typearrarr = $typearr;
			$kindarrarr = $kindarr;
		}
		
		return array(
			'typearr' => $typearrarr,
			'goodmid' => $this->goodmid,
			'kindarr' => $kindarrarr,
			'godepot' => $godepot,
		);
	}
	
	/**
	*	提交
	*/
	public function post_optgoods($request)
	{
		$cont	= $request->input('adstr');
		if(isempt($cont))return returnerror('没有选择出入库数量');
		$type	= (int)$request->input('type','0');
		$goodmid= (int)$request->input('goodmid','0');
		$kind	= (int)$request->input('kind','0');
		$depotid= (int)$request->input('depotid','0');
		$dt		= $request->input('dt');
		$sm		= nulltoempty($request->input('explain'));
		$sharr	= c('array')->strtoarray($cont);
		if($depotid==0)return returnerror('没有选择仓库');
		$inusrr	= array();
		$goodns = array();
		
		foreach($sharr as $k=>$rs){
			$goodsid 	= $rs[0]; //物品ID
			$count 		= (int)$rs[1];
			if($count<=0)return returnerror('出入库数量不能为0');
			$inarr 		= array();
			
			if($goodmid>0){
				$nobj = DB::table('goodn')->find($goodsid);
				if(!$nobj)continue; //子表不存了
				$goodsid = $nobj->goodsid;
				if($nobj->couns>=$nobj->count)continue;//已经全部出入库了
				$ques	= $nobj->count - $nobj->couns; //未出入库的
				if($count>$ques)$count = $ques;//不能超过
				
				//记录起来要更新的
				$goodns[$nobj->id] = array(
					'couns' => $nobj->couns+$count 
				);
				$inarr['goodmid'] = $goodmid;
				$inarr['goodnid'] = $nobj->id;
			}
			
			if($type==1)$count = 0- $count;//出库为负数

			$inarr['goodsid'] = $goodsid;
			$inarr['goodmid'] = $goodmid;
			$inarr['cid'] = $this->companyid;
			$inarr['depotid'] 	= $depotid;
			$inarr['count'] 	= $count;
			$inarr['type'] = $type;
			$inarr['kind'] = $kind;
			$inarr['applydt'] = $dt;
			$inarr['explain'] = $sm;
			$inarr['aid'] = $this->useaid;
			$inarr['optdt'] = nowdt();
			$inarr['optname'] = $this->adminname;
			
			$inusrr[] = $inarr;
		}
		if($inusrr)DB::table('goodx')->insert($inusrr);
		
		$this->goodsobj->setstock(); //更新总库存
		
		if($goodns){
			foreach($goodns as $sid=>$snar){
				DB::table('goodn')->where('id', $sid)->update($snar);
			}
			//更新出库状态
			$this->goodsobj->upstatem($goodmid);
		}
		return returnsuccess('操作成功');
	}
	
	//刷新库存
	public function get_reloadstock()
	{
		$this->goodsobj->setstock(); //更新总库存
		$this->goodsobj->upstatem();
		return returnsuccess('刷新成功');
	}
	
	/**
	*	获取单据
	*/
	public function get_optbill()
	{
		$rows = $this->getoptobjs()->get();
		foreach($rows as $k=>$rs){
			$rows[$k] = $this->goodsobj->showgoodmtype($rs);
		}
		return returnsuccess($rows);
	}
}