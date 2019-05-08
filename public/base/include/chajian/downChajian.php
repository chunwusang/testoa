<?php
/**
	下载文件类插件
*/

class downChajian extends Chajian{
	
	private $upobj;
	private $messign;
	
	protected function initChajian()
	{
		$this->messign = '';
		$this->upobj = c('upfile');
	}
	
	/**
	*	获取随机文件名
	*/
	public function getallfilename($ext)
	{
		$mkdir 	= ''.UPDIR.'/'.date('Y-m').'';
		if(!is_dir($mkdir))mkdir($mkdir);
		$allfilename			= ''.$mkdir.'/'.date('d_His').''.rand(10,99).'.'.$ext.'';
		return $allfilename;
	}
	
	/**
	*	根据扩展名保存文件(一般邮件附件下载)
	*/
	public function savefilecont($ext, $cont)
	{
		$bo  = $this->upobj->issavefile($ext);
		if(isempt($cont))return;
		$file= '';
		if(!$bo){
			$file	= $this->getallfilename('uptemp');
			$bo 	= @file_put_contents($file, base64_encode($cont));
		}else{
			$file 	= $this->getallfilename($ext);
			$bo 	= @file_put_contents($file, $cont);
		}
		if(!$bo){
			$file = '';
		}else{
			if($this->upobj->isimg($ext)){
				$bo = $this->upobj->isimgsave($ext, $file);
				if(!$bo)$file = '';
			}
		}
		return $file;
	}
	
	private function reutnmsg($msg)
	{
		$this->messign = $msg;
		return false;
	}
	
	//获取提示内容
	public function gettishi($msg1='')
	{
		$msg = $this->messign;
		if(isempt($msg))$msg = $msg1;
		return $msg;
	}
	
	/**
	*	根据内容创建文件
	*/
	public function createimage($cont, $ext, $filename, $thumbnail='')
	{
		if(isempt($cont))return $this->reutnmsg('创建内容为空');
		$allfilename			= $this->getallfilename($ext);
		$upses['oldfilename'] 	= $filename.'.'.$ext;
		$upses['fileext'] 	  	= $ext;
		@file_put_contents($allfilename, $cont);
		
		if(!file_exists($allfilename))return $this->reutnmsg('无法写入:'.$allfilename.'');
		
		$fileobj				= getimagesize($allfilename);
		$mime					= strtolower($fileobj['mime']);
		$next 					= 'jpg';
		if(contain($mime,'bmp'))$next = 'bmp';
		if($mime=='image/gif')$next = 'gif';
		if($mime=='image/png')$next = 'png';
		
		if($ext != $next){
			@unlink($allfilename);
			$ext = $next;
			$allfilename			= $this->getallfilename($ext);
			$upses['oldfilename'] 	= $filename.'.'.$ext;
			$upses['fileext'] 	  	= $ext;
			@file_put_contents($allfilename, $cont);
			if(!file_exists($allfilename))return $this->reutnmsg('无法写入:'.$allfilename.'');
		}
		
		$filesize 			  	= filesize($allfilename);
		$filesizecn 		  	= $this->upobj->formatsize($filesize);
		$picw					= $fileobj[0];				
		$pich					= $fileobj[1];
		if($picw==0||$pich==0){
			@unlink($allfilename);
			return $this->reutnmsg('无效的图片');;
		}
		$upses['filesize']	 	= $filesize;
		$upses['filesizecn']	= $filesizecn;
		$upses['allfilename']	= $allfilename;
		$upses['picw']	 		= $picw;
		$upses['pich']	 		= $pich;
		$upses['filetype']	 	= $mime;
	
		$arr 					= $this->uploadback($upses, $thumbnail);
		return $arr;
	}
	
	public function uploadback($uparr, $thumbnail='')
	{
		//如果是图片就生成缩略图
		if(isempt($thumbnail))$thumbnail='150x150';
		
		$barr['filesize'] 	= $uparr['filesize'];
		$barr['filesizecn'] = $uparr['filesizecn'];
		$barr['fileext'] 	= $uparr['fileext'];
		$barr['filetype'] 	= $uparr['filetype'];
		$barr['filepath'] 	= $uparr['allfilename'];
		$barr['filename'] 	= $uparr['oldfilename'];
		
		$thumbpath	= $uparr['allfilename'];
		$sttua		= explode('x', $thumbnail);
		$lw 		= (int)$sttua[0];
		$lh 		= (int)$sttua[1];
		if($uparr['picw']>$lw || $uparr['pich']>$lh){
			$imgaa	= c('image', true);
			$imgaa->createimg($thumbpath);
			$thumbpath 	= $imgaa->thumbnail($lw, $lh, 1);
		}
		if($uparr['picw'] == 0 && $uparr['pich']==0)$thumbpath = '';
		$barr['thumbpath'] 	= $thumbpath;
		
		//如果是用第三方存储上传到第三方上并返回
		$barr['filenum'] 	= $this->db->ranknum('[Q]basefileda','filenum');
		$barr['adddt']		= $this->rock->now;
		$barr['ip']			= $this->rock->ip;
		$barr['web']		= $this->rock->web;
		$barr['id']			= m('fileda')->insert($barr);
			
		$barr['height'] 	= $uparr['pich'];
		$barr['width'] 		= $uparr['picw'];
		
		if($this->upobj->isimg($barr['fileext'])){
			$barr['imgpath']	= '{baseurl}'.$barr['thumbpath'];//缩略图片地址
			$barr['allpath']	= '{baseurl}'.$barr['filepath'];//图片地址
			$barr['viewpath']	= getconfig('baseurl', URL).$barr['filepath'];
			$barr['viewpats']	= getconfig('baseurl', URL).$barr['thumbpath'];
		}
		return $barr;
	}
	
	//过滤特殊文件名
	private function replacefile($str)
	{
		$s 			= strtolower($str);
		$lvlaraa  	= explode(',','user(),found_rows,(),select*from,select*,%20');
		$lvlarab	= array();
		foreach($lvlaraa as $_i)$lvlarab[]='';
		$s = str_replace($lvlaraa, $lvlarab, $s);
		if($s!=$str)$str = $s;
		return $str;
	}
	
	//获取扩展名
	public function getext($file)
	{
		return strtolower(substr($file,strrpos($file,'.')+1));
	}
}
