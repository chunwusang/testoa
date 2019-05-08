<?php
/**
*	上传文件下载
*	主页：http://www.rockoa.com/
*	软件：信呼平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

class updownClassAction extends Action
{
	
	/**
	*	获取下载/预览地址
	*/
	public function geturlAction()
	{
		$num 	= $this->post('num');
		$glx 	= (int)$this->post('glx','0');
		if(isempt($num))return returnerror('要下载文件编号为空');
		$frs 	= m('fileda')->getone("`filenum`='$num'");
		if(!$frs)return returnerror('记录不存在了', 404);
		$filepath	= $frs['filepath'];
		$fileext	= $frs['fileext'];
		$isimg		= $this->isimg($fileext) ? '1' : '2';
		$xinhukey	= $this->post('xinhukey');
		c('cache')->set('xinhukey', $xinhukey);
		if(substr($filepath,0,4)=='http'){
			$durl 	= $filepath;
		}else{
			if(isempt($filepath) || !file_exists($filepath))
				return returnerror('文件不存在了',404);
			
			$downval= $this->jm->encrypt($num);
			$downkey= md5(''.$num.'_'.time().'');
			c('cache')->set($downkey, $downval, 5*60);//生成key5分钟，防止链接分享
			$url 	= getconfig('baseurl', URL);
			if($glx==1){
				$durl 	= $url.'?m=upview&a=down&showkey='.$downkey.'';
			}else{
				if($isimg=='1'){
					$durl 	= $url.''.$filepath.'';
				}else{
					$durl 	= $url.'?m=upview&showkey='.$downkey.'';
				}
			}
		}
		
		return returnsuccess(array(
			'upurl'		=> $durl,
			'fileext' 	=> $fileext,
			'filename' 	=> $frs['filename'],
			'isimg'		=> $isimg
		));
	}
	
	public function isimg($ext)
	{
		return contain('|jpg|png|gif|bmp|jpeg|', '|'.$ext.'|');
	}
	
	/**
	*	上传签名图片内容
	*/
	public function sendcontAction()
	{
		$cont 		= $this->post('cont');
		$thumbnail 	= $this->post('thumbnail');
		$cont 		= str_replace(' ','', $cont);
		if(contain($cont,',')){
			$conta = explode(',', $cont);
			$cont  = $conta[1];
		}
		$cont	= base64_decode($cont);
		$fileext 	= 'png';
		$filename 	= $this->post('filename','图片');

		$barr 	= c('down')->createimage($cont, $fileext, $filename, $thumbnail);
		if(!is_array($barr))return returnerror($barr);

		return returnsuccess($barr);
	}
}