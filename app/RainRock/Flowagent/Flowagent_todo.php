<?php
/**
*	应用.提醒
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_todo  extends Rockflow
{
	public $statusbilarr	= array('未读','已读');
	public $statuscolarr	= array('red','#aaaaaa');
	
	public function flowreplacers($rs, $lx=0)
	{
		if($rs->status==1)$rs->ishui = 1;
		$rs->agenhnumshow = $rs->agenhnum;
		unset($rs->agenhnum);
		return $rs;
	}
	
	//打开详情时
	public function flowgetdetail()
	{
		if($this->rs->status==0)
			$this->updateData(['status'=>1]); //更新已读
	}
	
	public function flowlistoption()
	{
		$barr['checkcolums'] = true;
		$barr['btnarr'][] 	 = [
			'name' => '删除',
			'click'=> 'deldetebti',
			'class'=> 'danger',
			'icons'=> 'trash'
		];
		$barr['btnarr'][] 	 = [
			'name' => '选中标识已读',
			'click'=> 'biaoshiyud',
			'class'=> 'default'
		];
		
		return $barr;
	}
	
	/**
	*	全部标识已读
	*/
	public function flowoptmenu($optrs)
	{
		if($optrs->num=='ydoptall'){
			$this->getModel()->where('aid', $this->useaid)->update(['status'=>1]);
		}
	}
	
	//选中标识已读
	public function post_biaoshiyud($request)
	{
		$sid = $request->input('sid');
		$sida= explode(',', $sid);
		$this->getModel()->whereIn('id', $sida)->where('aid', $this->useaid)->update(['status'=>1]);
		return returnsuccess('');
	}
	
	//选中删除
	public function post_deldetebti($request)
	{
		$sid = $request->input('sid');
		$sida= explode(',', $sid);
		$this->getModel()->whereIn('id', $sida)->where('aid', $this->useaid)->delete();
		return returnsuccess();
	}
}