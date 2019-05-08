<?php
/**
*	文件相关接口
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-17
*/

namespace App\RainRock\Chajian\Weapi;


use App\Model\Base\FileModel;

class ChajianWeapi_file extends ChajianWeapi
{
	/**
	*	保存文件
	*/
	public function postsave($request)
	{
		$isxg		= 0;
		if($request->input('fields')=='sysupfile')$isxg = 1;
		
		$fieldsarr	= explode(',','filenum,filename,fileext,filepath,thumbpath,pdfpath,filesizecn,filesize');
		
		$obj 		= new FileModel();
		$obj->aid 	= $this->useaid;
		$obj->uid 	= $this->userid;
		$obj->cid 	= $this->companyid;
		foreach($fieldsarr as $fid){
			$val 		= nulltoempty($request->input($fid));
			if($val=='null')$val = '';
			$obj->$fid 	= $val;
		}
		$obj->optdt 	= nowdt();
		$obj->isxg 		= $isxg;
		$obj->save();
		return returnsuccess($obj);
	}
	
	/**
	*	上传删除
	*/
	public function postdel($request)
	{
		$id = (int)$request->input('id','0');
		FileModel::find($id)->delete();
		return returnsuccess();
	}
	
	/**
	*	创建下载连接地址
	*/
	public function postdown($request)
	{
		$num = $request->input('num');
		if(isempt($num))return returnerror('filenum is empty');
		$glx = (int)$request->input('glx',0);
		return c('rockapi')->getdownurl($num, $glx);
	}
}