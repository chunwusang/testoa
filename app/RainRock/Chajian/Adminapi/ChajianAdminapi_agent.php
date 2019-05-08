<?php
/**
*	应用管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Adminapi;

use App\Model\Base\AgentModel;
use App\Model\Base\AgentfieldsModel;
use App\RainRock\Systems\Databeifen;

class ChajianAdminapi_agent extends ChajianAdminapi
{
	
	/**
	*	备份应用
	*/
	public function beifen()
	{
		$msg = Databeifen::beifen('agent');
		if($msg=='')$msg = Databeifen::beifen('agentfields');
		if($msg=='')$msg = Databeifen::beifen('agenttodo');
		if($msg=='')$msg = Databeifen::beifen('agentmenu');
		if($msg=='')$msg = Databeifen::beifen('flowmenu');
		if($msg)return returnerror($msg);
		return returnsuccess('备份成功');
	}
	
	/**
	*	导入备份的应用
	*/
	public function daoru()
	{
		$to  = Databeifen::insert('agent');
		$to1 = Databeifen::insert('agentfields');
		$to2 = Databeifen::insert('agentmenu');
		$to3 = Databeifen::insert('agenttodo');
		$to4 = Databeifen::insert('flowmenu');
		$msg = '导入应用('.$to.'),字段('.$to1.'),菜单('.$to2.'),通知设置('.$to3.'),操作菜单('.$to4.')';
		c('log')->add('平台应用', $msg);
		return returnsuccess($msg);
	}
	
	/**
	*	分别创建各个模块
	*/
	public function creates()
	{
		$msg 	= Databeifen::createupgde();
		if($msg)return returnerror($msg);
		return returnsuccess('生成成功');
	}
	
	/**
	*	保存双击编辑
	*/
	public function postsaveediter($request)
	{
		$mtable	= $request->input('mtable');
		$id		= (int)$request->input('id','0');
		$value	= nulltoempty($request->input('value'));
		$fields	= $request->input('fields');
		$table 	= c('rockjm')->uncrypt($mtable);
		$tabarr = explode(',','company,users,agent,agentfields,agentmenu,agenttodo,admin,task,flowmenu');
		if(!in_array($table, $tabarr))
			return $this->returnerror(trans('validation.notextent').'1');
		
		if($fields=='password'){
			if(isempt($value))return;
		}
		
		$cls	= '\App\Model\Base\\'.ucfirst($table).'Model';
		$obj	= new $cls();
		$data 	= $obj->find($id);
		$data->$fields	= $value;
		$data->save();
		return returnsuccess();
	}
	
	/**
	*	保存自定义布局
	*/
	public function postsavebuju($request)
	{
		$cont 	= nulltoempty($request->input('content'));
		$lx 	= (int)$request->input('lx','0');
		$num 	= $request->input('agentnum');
		$path	= base_path('resources/views/web/detail/'.$num.'_input.blade.php');
		if($lx==1)$path	= base_path('resources/views/web/detail/'.$num.'.blade.php');
		if(isempt($cont)){
			@$bo = unlink($path);
		}else{
			@$bo= file_put_contents($path, $cont);	
		}
		if(!$bo)return returnerror('保存失败：无发写入'.$path.'');
		return returnsuccess();
	}
	
	/**
	*	刷新排序号
	*/
	public function postupxuhao($request)
	{
		$sid = $request->input('sid');
		$sida= explode(',', $sid);
		$k = -1;
		foreach($sida as  $id){
			$k++;
			$obj = new AgentfieldsModel();
			$obj->where(['id'=>$id, 'iszb'=>0])->update(['sort'=>$k]);
		}
		return returnsuccess();
	}
}