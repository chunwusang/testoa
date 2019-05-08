<?php
/**
*	命令系统初始化
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-06
*/

namespace App\Console\Commands;

use DB;

class Rockinit extends Rockcommandsbase
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'rock:cloudoa {param}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'xinhuoa platform service init checkbase';

   

    /**
     * Execute the console command.
     * php artisan rock init
     * @return mixed
     */
    public function handle()
    {
		$param 	= $this->argument('param');
		
		//创建数据库
		if($param=='checkbase'){
			$this->checkbases();
			return;
		}
		
		//创建短信服务的数据库配置
		$path 	= base_path('public/base/webmain/webmainConfig.php');
		$conf	= file_get_contents($path);
		
		$dbpzt 	= config('database.connections.mysql');
		
		$suarr['db_host']	= $dbpzt['host'];
		$suarr['db_user']	= $dbpzt['username'];
		$suarr['db_pass']	= $dbpzt['password'];
		$suarr['db_base']	= $dbpzt['database'];
		$suarr['perfix']	= $dbpzt['prefix'];
		
		$cont 	= '';
		$conarr	= explode("\n", $conf);
		foreach($conarr as $hstr){
			$str = $hstr;
			foreach($suarr as $k=>$v){
				if(contain($str,"	'$k'")){
					$str= "	'$k'	=> '".$v."',";
					unset($suarr[$k]);
					break;
				}
			}
			if($str==');')break;
			$cont.="".$str."\n";
		}
		
		foreach($suarr as $k=>$v){
			$str= "	'$k'	=> '".$v."',";
			$cont.="".$str."\n";
		}
		
		file_put_contents($path, $cont.');');
		
		echo 'init success';
    }
	
	//不存在就创建数据库
	private function checkbases()
	{
		$dbpzt 	= config('database.connections.mysql');
		$base	= $dbpzt['database'];
		$charset	= $dbpzt['charset'];
		$collation	= $dbpzt['collation'];
		
		$allrows	= DB::select('show databases');
		foreach($allrows as $dors)$allbase[] = $dors->Database;
		if(in_array($base, $allbase)){
			echo 'database '.$base.' exists';
		}else{
			$sql = "CREATE DATABASE `$base` DEFAULT CHARACTER SET $charset COLLATE $collation";
			DB::select($sql);
			echo 'database '.$base.' create success';
		}
	}
		
}
