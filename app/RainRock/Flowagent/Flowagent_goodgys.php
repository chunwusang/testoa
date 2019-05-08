<?php
/**
*	应用.供应商
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_goodgys  extends Rockflow
{
	protected $flowisturnbool	= false;
	protected function flowinit()
	{
		$this->goodsobj = $this->getNei('goods');
	}
	
	public function flowsavebefore($arr)
	{
		$data['isgys'] = 1;
		return [
			'data' => $data
		];
	}
	
	//删除判断
	protected function flowdelbillbefore()
	{
		$to 	 = \DB::table('goodm')->where('custid', $this->mid)->count();
		if($to>0)
			return '有关联采购单，不能在删除';
	}
	
	protected function flowlistoption()
	{
	
		$barr[] 	 = [
			'name' 	=> '刷新采购金额',
			'click'	=> 'reloadcgmoney',
			'class'	=> 'default'
		];
		
		return [
			'btnarr' => $barr,
		];
	}
	
	//刷新采购金额
	public function get_reloadcgmoney()
	{
		$this->goodsobj->reloadcgmoney();
		return returnsuccess();
	}
}