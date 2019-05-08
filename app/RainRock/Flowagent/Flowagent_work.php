<?php
/**
*	应用.任务
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_work  extends Rockflow
{
	protected function flowinit()
	{
		$this->statusbilarr[0] = '待执行';
		$this->statusbilarr[1] = '已完成';
		$this->statusbilarr[2] = '无法完成';
		$this->statusbilarr[3] = '执行中';
		$this->statusbilarr[4] = '结束';
	}
	
	public function flowreplacers($rs)
	{
		$rs->statusstr 	= $this->getnowstatus($rs);
		$rs->ztvalue 	= $rs->status;
		return $rs;
	}
	
	public function flowgetdetail()
	{
		$ztvaluestore = $this->getinputStore('status');
		return array(
			'ztvaluestore' => $ztvaluestore
		);
	}
	
	public function flowbillwhere($obj, $atype)
	{
		//我下属任务,distid包含我的下属id
		if($atype=='wodown'){
			return $this->getDownobj($obj, 'distid');
		}
		return false;
	}
	
	/**
	*	提交处理
	*/
	public function post_chuli($request)
	{
		$mid 	= (int)$request->input('mid','0');
		$zt  	= (int)$request->input('ztvalue','0');
		$sm  	= nulltoempty($request->input('explain'));
		$fileid = nulltoempty($request->input('fileid'));
		$distid	= $request->input('distid');
		$isfenp = false;
		$this->initdata($mid);
		
		if($distid!=$this->rs->distid)$isfenp = true; //不相等就是分配
		
		$this->updateData([
			'status' 	=> $zt,
			'dist'		=> $request->input('dist'),
			'distid'	=> $distid,
			'enddt'		=> $request->input('enddt')
		]);
		$statusname		= $this->statusbilarr[$zt];
		$this->addLog([
			'status' 	=> $zt,
			'statusname'=> $statusname,
			'color'		=> $this->statuscolarr[$zt],
			'explain'	=> $sm,
			'fileid'	=> $fileid
		],'处理');
		
		if($isfenp){
			$this->agenttodonum('dist', $distid, $sm);
		}else{
			$this->agenttodoping($sm);
		}
		return returnsuccess();
	}
}