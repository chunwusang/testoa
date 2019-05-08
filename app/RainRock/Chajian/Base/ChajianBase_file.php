<?php 
/**
*	文件相关
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\FileModel;

class ChajianBase_file extends ChajianBase{
	
	
	public function initChajian()
	{
		$this->fileall = ',aac,ace,ai,ain,amr,app,arj,asf,asp,aspx,av,avi,bin,bmp,cab,cad,cat,cdr,chm,com,css,cur,dat,db,dll,dmv,doc,docx,dot,dps,dpt,dwg,dxf,emf,eps,et,ett,exe,fla,ftp,gif,hlp,htm,html,icl,ico,img,inf,ini,iso,jpeg,jpg,js,m3u,max,mdb,mde,mht,mid,midi,mov,mp3,mp4,mpeg,mpg,msi,nrg,ocx,ogg,ogm,pdf,php,png,pot,ppt,pptx,psd,pub,qt,ra,ram,rar,rm,rmvb,rtf,swf,tar,tif,tiff,txt,url,vbs,vsd,vss,vst,wav,wave,wm,wma,wmd,wmf,wmv,wps,wpt,wz,xls,xlsx,xlt,xml,zip,';
	}
	
	public function filelxext($flx)
	{
		if(!contain($this->fileall,','.$flx.','))$flx='wz';
		return $flx;
	}
	
	public function savefile($fileid, $mtable, $mid)
	{
		if(!$fileid)return;
		if(is_string($fileid))$fileid = explode(',', $fileid);
		
		FileModel::whereIn('id', $fileid)->update([
			'mtable' => $mtable,
			'mid' => $mid,
		]);
	}
	
	/**
	*	glx=0录入页,1详情
	*/
	public function getlist($mtable, $mid, $glx=0)
	{
		if($glx==0){
			return FileModel::select('id','filenum','filename','fileext','filesizecn','thumbpath')->where('mtable',$mtable)->where('mid',$mid)->get();
		}else{
			return FileModel::where('mtable',$mtable)->where('mid',$mid)->get();
		}
	}
	
	public function isimg($ext)
	{
		return contain('|jpg|png|gif|bmp|jpeg|', '|'.$ext.'|');
	}
	
	public function isoffice($ext)
	{
		return contain('|doc|docx|xls|xlsx|ppt|pptx|pdf|', '|'.$ext.'|');
	}
	
	public function getfilestr($fileid)
	{
		if(!$fileid)return;
		if(is_string($fileid))$fileid = explode(',', $fileid);
		$farr	= FileModel::whereIn('id', $fileid)->get();
		$str 	= '';
		foreach($farr as $k=>$rs){
			$str.= $this->getfilestrs($rs);
		}
		$str.='';
		return $str;
	}
	
	public function getfilexg($mtable, $mid)
	{
		$farr	= FileModel::where('mtable',$mtable)->where('mid',$mid)->where('isxg', 1)->get();
		$str 	= '';
		foreach($farr as $k=>$rs){
			$str.= $this->getfilestrs($rs);
		}
		$str.='';
		return $str;
	}
	
	public function getfilestrs($rs)
	{
		$url  = config('rock.baseurl');
		$surl = ''.$url.'/'.$rs->thumbpath.'';
		$isimg= $this->isimg($rs->fileext);
		if(!$isimg){
			$surl	= '/images/fileicons/'.$this->filelxext($rs->fileext).'.gif';
		}
		if($rs->isdel==1)$rs->filename='<s>'.$rs->filename.'</s>';
		$fstr  = '<div style="padding:5px 0px">';
		$fstr .= '<img height="20" width="20" src="'.$surl.'" align="absmiddle"> '.$rs->filename.' <span style="color:#aaaaaa;font-size:12px">('.$rs->filesizecn.')</span>';
		if($rs->isdel==1){
			$fstr .= ' <span style="color:#aaaaaa;font-size:12px">已删除</span>';
		}else{
			$fstr .= ' <a href="javascript:;" onclick="c.openfile(\''.$rs->filenum.'\', \''.$rs->filesizecn.'\')">下载</a>';
			if($isimg || $this->isoffice($rs->fileext)){
				$fstr.='&nbsp; <a href="javascript:;" onclick="c.openfiles(\''.$rs->filenum.'\',0)">预览</a>';
			}
		}
		$fstr .= '</div>';
		return $fstr;
	}
	
	public function getfilestrs121($rs)
	{
		$url  = config('rock.baseurl');
		$fstr = '<div class="upload_items">';
		if($this->isimg($rs->fileext)){
			$fstr .= '<img class="imgs" onclick="c.imgview(\''.$url.'/'.$rs->filepath.'\')" src="'.$url.'/'.$rs->thumbpath.'">';
		}else{
			$fstr .= '<div onclick="c.openfile(\''.$rs->filenum.'\',\''.$rs->filesizecn.'\')" class="upload_items_items"><img src="/images/fileicons/'.$this->filelxext($rs->fileext).'.gif" alian="absmiddle"> ('.$rs->filesizecn.')<br>'.$rs->filename.'</div>';
		}
		$fstr.= '</div>';
		return $fstr;
	}
	
	public function updatedel($num)
	{
		FileModel::where('filenum', $num)->update(array(
			'isdel' => 1
		));
		$obj 	= $this->getModel('word');
		if($obj!=null)$obj->where('filenum', $num)->update(array(
			'isdel' => 1
		));
	}
}                                  