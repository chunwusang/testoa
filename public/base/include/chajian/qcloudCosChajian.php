<?php 
/**
*	腾讯云上文件存储上传管理
*/
include_once(ROOT_PATH.'/include/cos-php-sdk-v4-master/include.php');
use qcloudcos\Cosapi;
class qcloudCosChajian extends Chajian{
	
	private $bucket	= 'xinhu';
	
	private $folder	= 'rockxinhuweb'; //默认目录
	
	
	protected function initChajian()
	{
		Cosapi::initConf(); //初始设置
	}
	
	/**
	*	上传文件
	*	filepath 要上传的文件全路径
	*	updir 上传到哪个目录
	*	upname 上传后保存文件名
	*/
	public function upload($filepath, $updir='', $upname='')
	{
		if(!file_exists($filepath))return false;
		$filea 	= explode('/', $filepath);
		if($upname=='')$upname = $filea[count($filea)-1];
		if($updir=='')$updir = $this->folder;
		$ret = Cosapi::upload($this->bucket, $filepath, ''.$updir.'/'.$upname.'');
		return $ret;
	}
	
	/**
	*	创建文件夹
	*/
	public function createFolder($folder)
	{
		$ret = Cosapi::createFolder($this->bucket, $folder);
		return $ret;
	}
	
	/**
	*	获取目录下的文件
	*/
	public function listFolder($folder='', $num=20)
	{
		if($folder=='')$folder = $this->folder;
		$ret = Cosapi::listFolder($this->bucket, $folder, $num);
		if($ret['code'] != 0){
			return returnerror($ret['message']);
		}else{
			$barr = returnsuccess($ret['data']['infos']);
			$barr['folder'] = $folder;
			return $barr;
		}
	}
	
	/**
	*	删除文件
	*/
	public function delFile($path)
	{
		$ret = Cosapi::delFile($this->bucket, $path);
		return $ret;
	}
	
	public function delListFile()
	{
		$barr = $this->listFolder('',100);
		if($barr['success']){
			foreach($barr['data'] as $k=>$rs){
				$this->delFile($this->folder.'/'.$rs['name']);
			}
		}
	}
}