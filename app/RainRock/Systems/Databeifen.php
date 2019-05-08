<?php
/**
*	应用字段
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-07 18:38:51
*/

namespace App\RainRock\Systems;

use DB;

class Databeifen
{
	
	public static function insert($table)
	{
		$path	= rockpath('Systems/Json/data_'.$table.'.json');
		$cont 	= '';
		if(file_exists($path))$cont = file_get_contents($path);
		if(isempt($cont))return 0;
		$data 	= json_decode($cont, true);
		$cls 	= 'App\Model\Base\\'.ucfirst($table).'Model';
		$obj	= new $cls();
		$k		= -1;
		foreach($data as $k=>$rs){
			if($obj->find($rs['id'])){
				$obj->where('id',$rs['id'])->update($rs);
			}else{
				$obj->insert($rs);
			}
		}
		return $k+1;
	}
	
	public static function beifen($table)
	{
		$data 	= DB::table($table)->get();
		$cont 	= json_encode($data, JSON_UNESCAPED_UNICODE);
		$path	= rockpath('Systems/Json/data_'.$table.'.json');
		@$bo 	= file_put_contents($path, $cont);
		if(!$bo)return 'No permission to create:'.$path.'';
		return '';
	}
	
	public static function createupgde()
	{
		$data = DB::table('agent')->get();
		
		foreach($data as $k=>$rs){
			$path	= rockpath('Systems/Json/upgde_'.$rs->num.'.json');
			$data 	= array();
			$data['agent'] 	= $rs;
			$agentid		= $rs->id;
			$data['agentfields']= DB::table('agentfields')->where('agentid', $agentid)->get();
			$data['agentmenu']	= DB::table('agentmenu')->where('agentid', $agentid)->get();
			$data['agenttodo']	= DB::table('agenttodo')->where('agentid', $agentid)->get();
			$data['flowmenu']	= DB::table('flowmenu')->where('agentid', $agentid)->get();
			$cont 	= json_encode($data, JSON_UNESCAPED_UNICODE);
			@$bo 	= file_put_contents($path, $cont);
			if(!$bo)return 'No permission to create:'.$path.'';
		}
		return '';
	}
}