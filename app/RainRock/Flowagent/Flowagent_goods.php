<?php
/**
*	应用.物品管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_goods  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	public $defaultorder		= 'sort,asc|id,desc';
	public $classtypenum		= 'goodstype'; //对应分类编号
	
	
	protected function flowinit()
	{
		$this->changeStatus();	
		$this->classifyobj = $this->getNei('classify');
		$this->goodsobj = $this->getNei('goods');
		$this->classifyobj->changetype($this->classtypenum, '物品分类');
	}
	
	public function flowreplacers($rs)
	{
		if($rs->stock<=0)$rs->ishui=1;
		return $rs;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		$classid 	 	= (int)\Request::get('classid','0');
		$this->classid 	= $classid;
		if($classid>0){
			
			return $obj->where('classid', $classid);
		}
		return false;
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
		
		$barr[] 	 = [
			'name' 	=> '分类管理',
			'click'	=> 'fenquguan',
			'class'	=> 'default'
		];
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

		return [
			'btnarr' => $barr,
			'leftstr'=> $leftstr,
		];
	}
	
	
	//导入数据的测试显示
	public function flowdaorutestdata()
	{
		$barr[] = array(
			'classname' 	=> '办公耗材/笔',
			'name' 			=> '红色粉笔',
			'num' 			=> 'WP-829',
			'guige' 		=> '红色',
			'xinghao' 		=> '5厘米',
			'price' 		=> '1',
			'unit' 			=> '盒',
			'sort' 			=> '0',
		);
		$barr[] = array(
			'classname' 	=> '办公耗材/笔',
			'name' 			=> '白色粉笔',
			'num' 			=> '',
			'guige' 		=> '红色',
			'xinghao' 		=> '5厘米',
			'price' 		=> '1',
			'unit' 			=> '盒',
			'sort' 			=> '0',
		);
		
		return $barr;
	}
	
	public function flowsavebefore($arr)
	{
		$odi 		= $this->goodsobj->existsgoods([
			'name' 		=> $arr->name,
			'xinghao' 	=> $arr->xinghao,
			'guige' 	=> $arr->guige,
		], $this->mid);
		if($odi)return '物品已存在';
	}
	
	//导入之前
	public function flowdaorubefore($rows)
	{
		$inarr = array();
		foreach($rows as $k=>$rs){

			$classarr		= $this->classifyobj->gettypeid($this->classtypenum, $rs['classname']);
			$rs['classid'] 	= $classarr[0];
			$rs['classname']= $classarr[1];
			if($rs['classid']==0)return '行'.($k+1).'的分类不存在';
			

			$rs['price']	= floatval(arrvalue($rs,'price', '0')); //金额
			$rs['sort']		= (int)arrvalue($rs,'sort', '0');
			$inarr[] = $rs;
		}
		return $inarr;
	}
	
	//删除判断
	protected function flowdelbillbefore()
	{
		$to 	 = \DB::table('goodx')->where('goodsid', $this->mid)->count();
		if($to>0)
			return '此物品已出入库过，不能在删除';
	}
}