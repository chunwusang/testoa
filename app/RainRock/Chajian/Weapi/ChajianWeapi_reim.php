<?php
/**
*	插件-REIM接口数据
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-17
*	访问地址：/api/we/reim_方法
*/

namespace App\RainRock\Chajian\Weapi;



class ChajianWeapi_reim extends ChajianWeapi
{
	
	private $reimobj;
	
	private function initREIM()
	{
		$this->reimobj	= $this->getNei('reim');
	}
	
	/**
	*	获取消息记录
	*/
	public function getrecord($request)
	{
		$this->initREIM();
		$type 	= $request->get('type','user');
		$gid  	= (int)$request->get('gid','0');
		$minid  = (int)$request->get('minid','0');
		$loadci  = (int)$request->get('loadci','0');
		$lastdt  = nulltoempty($request->input('lastdt'));
		
		$sendinfo 		= new \StdClass();
		$sendinfo->id 	= $this->useainfo->id;
		$sendinfo->name = $this->useainfo->name;
		$sendinfo->face = $this->useainfo->face;
		
		$data 		= $this->reimobj->getrecord($type, $gid, $minid, $lastdt);
		
		$barr = [
			'sendinfo' 	=> $sendinfo,
			'rows'		=> $data['rows'],
			'type'		=> $type,
			'nowdt'		=> time(),
			'loadci'	=> $loadci,
			'baseurl'	=> config('rock.baseurl'),
			'wdtotal'	=> 0 //未读消息
		];
		
		//首次加载
		if($loadci==0){
			$usershu 	= 2;
			if($type=='group')$usershu = $this->reimobj->getgroupuser($gid,1);
			$barr['receinfo']	= $this->reimobj->getreceinfo($type, $gid);
			$barr['usershu']	= $usershu;
		}
		
		foreach($data as $k=>$v)$barr[$k] = $v;
		
		return returnsuccess($barr);
	}
	
	/**
	*	获取发送和接收人信息
	*/
	public function getainfo()
	{
		
	}
	
	
	/**
	*	发送消息
	*/
	public function postsendinfor($request)
	{
		$this->initREIM();
		$type 	= $request->input('type','user');
		$nuid 	= $request->input('nuid');
		$optdt 	= $request->input('optdt');
		$cont 	= $request->input('cont');
		
		if(isempt($optdt))$optdt = nowdt();
		
		$fileid 	= (int)$request->input('fileid','0');
		$gid  		= (int)$request->get('gid','0');
		
		$id 		= $this->reimobj->sendinfor($type, $gid, $cont,[
			'optdt'	=> $optdt,
			'fileid'=> $fileid,
			'optdt' => $optdt
		]);
		if(!is_numeric($id))return returnerror($id);
		
		$barr['nuid'] 	= $nuid;
		$barr['id'] 	= $id;

		return returnsuccess($barr);
	}
	
	/**
	*	删除会话
	*/
	public function delhchat($request)
	{
		$this->initREIM();
		$type 	= $request->input('type','user');
		$gid  	= (int)$request->get('gid','0');
		$this->reimobj->delhchat($type, $gid);
		return returnsuccess();
	}
	
	/**
	*	创建会话
	*/
	public function postcreatechat($request)
	{
		$this->initREIM();
		$name = $request->input('name');
		if(isempt($name))return returnerror('会话名称不能为空');
		$deptid = (int)$request->input('deptid','0');
		$this->reimobj->createchat($name, $deptid);
		return returnsuccess();
	}
	
	/**
	*	获取会话信息
	*/
	public function chatinfo($request)
	{
		$this->initREIM();
		$type 	= $request->input('type','user');
		$gid  	= (int)$request->input('gid','0');
		$data 	=  $this->reimobj->getchatinfo($type, $gid);
		
		if(!$data)return returnerror('会话已经不存在了');
		$data['recordshu'] = $this->reimobj->getrecordcount($type, $gid);
		
		return returnsuccess($data);
	}
	
	/**
	*	邀请人
	*/
	public function postchatadduser($request)
	{
		$this->initREIM();
		$type 	= $request->input('type','user');
		$gid  	= (int)$request->input('gid','0');
		$sid  	= $request->input('sid');
		if(isempt($sid))return returnerror('邀请人不能为空');
		
		$info 	=  $this->reimobj->getchatinfo('group', $gid, 1);
		if(!$info)return returnerror('会话已经不存在了');
		if(!$info['receinfo']->isin)return returnerror('你不在此会话中');
		
		$addshu = $this->reimobj->chatadduser($gid,$sid);
		$data 	=  $this->reimobj->getchatinfo($type, $gid);
		$data['addshu'] = $addshu;
		return returnsuccess($data);
	}
	
	/**
	*	修改会话名称
	*/
	public function posteditchatname($request)
	{
		$this->initREIM();
		$gid  	= (int)$request->input('gid','0');
		$name  	= trim($request->input('name'));
		
		$info 	=  $this->reimobj->getchatinfo('group', $gid, 1);
		if(!$info)return returnerror('会话已经不存在了');
		if(!$info['receinfo']->isin)return returnerror('你不在此会话中');
		
		if(isempt($name))return returnerror('名称不能为空');
		$this->reimobj->editchatname($gid, $name);
		
		return returnsuccess();
	}
	
	/**
	*	修改会话名称
	*/
	public function posteditchatgong($request)
	{
		$this->initREIM();
		$gid  	= (int)$request->input('gid','0');
		$cont  	= trim($request->input('cont'));
		
		$info 	=  $this->reimobj->getchatinfo('group', $gid, 1);
		if(!$info)return returnerror('会话已经不存在了');
		if(!$info['receinfo']->isin)return returnerror('你不在此会话中');

		$this->reimobj->editchatgong($gid, $cont);
		
		return returnsuccess();
	}
	
	/**
	*	退出会话
	*/
	public function exitchat($request)
	{
		$this->initREIM();
		$gid  	= (int)$request->input('gid','0');
		$this->reimobj->exitchat($gid);
		return returnsuccess();
	}
	
	/**
	*	清空聊天记录
	*/
	public function clearrecord($request)
	{
		$this->initREIM();
		$type 	= $request->input('type','user');
		$gid  	= (int)$request->input('gid','0');
		$oi 	= $this->reimobj->clearrecord($type,$gid);
		return returnsuccess($oi);
	}
	
	/**
	*	删除聊天记录
	*/
	public function postdelrecord($request)
	{
		$this->initREIM();
		$ids  	= $request->input('ids');
		if(isempt($ids))return returnsuccess();
		$oi 	= $this->reimobj->delrecord($ids);
		return returnsuccess($oi);
	}
	
	/**
	*	历史会话改成0
	*/
	public function biaoyd($request)
	{
		$this->initREIM();
		$type 	= $request->input('type','user');
		$gid  	= (int)$request->input('gid','0');
		$type 	= $this->reimobj->gettypeid($type);
		$this->reimobj->biaoyd($type,$gid);
		return returnsuccess();
	}
}