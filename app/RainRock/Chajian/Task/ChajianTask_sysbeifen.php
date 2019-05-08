<?php
/**
*	系统数据库备份
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	cli运行：php artisan rock:taskrun --act=sysbeifen
*/

namespace App\RainRock\Chajian\Task;

use DB;

class ChajianTask_sysbeifen extends ChajianTask
{
		
	/**
	*	备份数据库到storage/app/data下，生成.sql文件
	*/	
	public function run()
	{
		
		$qz 		= DB::getTablePrefix();
		$db 		= c('mysql');
		$alltabls 	= $db->getAllTable();
		$nobeifne	= array(''.$qz.'log',''.$qz.'token'); //不备份的表;
		$data 		= array();
		$strstr 	= "/*
*	备份时间：".nowdt()."		
*/

";
		foreach($alltabls as $tabs){
			if(in_array($tabs, $nobeifne))continue;	
			$strstr	.= "DROP TABLE IF EXISTS `$tabs`;\n";
			$sqla 	 = DB::select('show create table `'.$tabs.'`');
			$key 	 = 'Create Table';
			$strstr	.= "".$sqla[0]->$key.";\n";
			
			$rows  	= DB::select('select * from `'.$tabs.'`');
			foreach($rows as $k=>$rs){
				$vstr = '';
				foreach($rs as $k1=>$v1){
					if(!isempt($v1))$v1 = str_replace("\n",'\n', $v1);
					$v1 = ($v1===null) ? 'null' : "'$v1'";
					$vstr.=",$v1";
				}
				$strstr	.= "INSERT INTO `$tabs` VALUES(".substr($vstr,1).");\n";
			}
			
			$strstr	.= "\n";
		}
		$spath 		= storage_path('app/data');
		if(!is_dir($spath))mkdir($spath);
		$file 		= ''.DB::getDatabaseName().'_'.date('Y.m.d.H.i.s').'.sql';
		$filepath 	= ''.$spath.'/'.$file.'';
		@$bo 		= file_put_contents($filepath, $strstr);
		if(!$bo)return 'error';
		return 'success';
	}
}