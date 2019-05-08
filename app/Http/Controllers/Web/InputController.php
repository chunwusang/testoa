<?php
/**
*	录入
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Web;

use App\Model\Base\BaseModel;
use App\Model\Base\FlowcourseModel;
use App\Model\Base\FlowchecksModel;
use Illuminate\Http\Request;
use Rock;

class InputController extends WebController
{
	
	protected $authform	= 'we';
	
	/**
	*	显示录入页面
	*/
    public function index($cnum, $num, $mid=0, Request $request)
    {
		$this->getAgenh($cnum, $num);
		if($this->useaid==0)return $this->returntishi('not found usera');
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		$flow->initdata($mid);
		$isedit		= 1;
		//判断是否有新增权限
		if($mid==0){
			$isadd 	= $flow->isaddqx();
			if($isadd==0)
				return $this->returntishi(trans('validation.notadd',['name'=>$this->agenhinfo->name]));
		}else{
			$isedit = $flow->iseditqx();
			//if($isedit==0)
			//	return $this->returntishi(trans('validation.notedit',['name'=>$this->agenhinfo->name]));
		}

		$fieldsarr 	= $flow->getFieldsArr('lu'); //获取字段
		$farr		= $flow->inputfieldsarr($fieldsarr, $flow->rs, 0); //初始化
		$fieldsarr	= $farr['fieldsarr'];
		$store		= $farr['store'];
		$data 		= $flow->rs;
		
		//相关js
		$jspath		= 'res/agent/'.$num.'_input.js';
		if(!file_exists(public_path($jspath)))$jspath = '';
		
		//是否需要抄送的
		$chaoarr	= $flow->getChaosong();
		if($chaoarr->iscs>0){
			$fieldsarr[] = $chaoarr->fieldsobj;
			$data->sys_chaoname  = $chaoarr->chaoname;
			$data->sys_chaoid	 = $chaoarr->chaoid;
		}
		
		//第一步流程信息，上一步指定的
		if($flow->isflow==1){
			$nowcourse	= $flow->getflowfirstinput();
			if($nowcourse && $nowcourse['checktype']=='change'){
				$firarr	= $flow->createFields($nowcourse['name'],'sys_nextname','changeusercheck');
				$firarr->placeholder = '选择'.$nowcourse['name'].'处理人';
				$firarr->isbt = 1;
				$firarr->data = 'sys_nextnameid';
				$firarr->changerange		= $nowcourse['checktypeid']; //选择范围
				$fieldsarr[]  = $firarr;
				$data->sys_nextname  	= $nowcourse['checkname'];
				$data->sys_nextnameid	= $nowcourse['checkid'];	
			}
		}
		
		

		$inputobj	= c('input', $this->useainfo);
		$inputobj->setFlow($flow);

		$barr 		= [
			'mid' 		=> $mid,
			'fieldsarr' => $fieldsarr,
			'data' 		=> $data,
			'subdata' 	=> $flow->getsubdata(0),
			'store' 	=> $store,
			'isedit' 	=> $isedit,
			'turnbool' 	=> $flow->getturnbool(),
			'inputcontent' 	=> '',
			'filelist'  => $flow->getFilelist(),
			'jspath' 	=> $jspath,
		];
		
		
		//自定义录入模版，只用到PC
		$ismobile 	= c('base')->ismobile();
		if($ismobile==0){
			$path		= base_path('resources/views/web/detail/'.$num.'_input.blade.php');
			if(file_exists($path)){
				$farrobj= array();
				foreach($fieldsarr as $k=>$rs)$farrobj[$rs->fields] = $rs;
				
				$inputcontent = file_get_contents($path);
				$inputcontent = str_replace('*','<font color=red>*</font>', $inputcontent);
				
				$nstr	= Rock::matcharr($inputcontent);
				foreach($nstr as $fid){
					if(isset($farrobj[$fid])){
						$item 	  = $farrobj[$fid];
						if($item->fieldstype=='subtable'){
							$inputstr = $inputobj->showsubtable($item, $fieldsarr, $barr['subdata'], $store, $isedit);
						}else{
							$inputstr = $inputobj->show($item, $data, $store);
						}
						$inputcontent = str_replace('{'.$fid.'}', $inputstr, $inputcontent);
					}
				}
				
				$barr['inputcontent'] 	= $inputcontent;
				$inputobj 				= false;
			}
		}
		
		$barr['inputobj']		= $inputobj;
		return $this->getShowView('input', $cnum, $num, $request, $barr);
    }
	
	
	/**
	*	导入view
	*/
	public function indexDaoru($cnum, $num, Request $request)
    {
		$this->getAgenh($cnum, $num);
		if($this->useaid==0)return $this->returntishi('not found usera');
		
		if(!$this->agenhinfo)return $this->returntishi('应用['.$num.']不存在，无法使用导入');
		
		$authobj 	= c('authory', $this->useainfo);
		$isdaoru 	= $authobj->isdaoru($this->agenhinfo->id);
		if($isdaoru==0)return $this->returntishi('没有导入['.$num.']应用的权限');
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		
		$fieldsarr 	= $flow->getFieldsArr('daoru');
		$barr 		= [
			'fieldsarr' => $fieldsarr
		];
		
		return $this->getShowView('daoru', $cnum, $num, $request, $barr);
	}

	/**
	*	下载模版
	*/
	public function indexDaorudown($cnum, $num, Request $request)
    {
		$this->getAgenh($cnum, $num);
		if($this->useaid==0)return $this->returntishi('not found usera');
		$authobj 	= c('authory', $this->useainfo);
		
		$isdaoru 	= $authobj->isdaoru($this->agenhinfo->id);
		if($isdaoru==0)return $this->returntishi('没有导入['.$num.']应用的权限');
		
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		$rows 		= $flow->getFieldsArr('daoru');
		
		$testdata	= $flow->flowactrun('flowdaorutestdata');
		if(!$testdata)$testdata = array();
		
		$str1 	= '';
		$str2 	= '';
		$col 	= 0;
		foreach($rows as $k=>$rs){
			$col++;
			$xi 	= $rs->isbt==1? '<font color=red>*</font>' : '';
			$str1.='<td style="border:.5pt #000000 solid; background:#cdf79e" height="30" align="center">'.$xi.'<b>'.$rs->name.'('.$rs->fields.')</b></td>';
		}
		if($testdata){
			$texdata = $testdata;
			if(!isset($testdata[0]))$texdata = array($testdata);
			foreach($texdata as $j=>$jrs){
				$str2.='<tr>';
				foreach($rows as $k=>$rs){
					$val  = arrvalue($jrs, $rs->fields);
					$str2.='<td style="border:.5pt #000000 solid;" height="30" align="center">'.$val.'</td>';
				}
				$str2.='</tr>';
			}
		}

		$str = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><table style="border-spacing: 0;border-collapse: collapse;"><tr bgcolor="#f1f1f1">'.$str1.'</tr>'.$str2.'';
		for($i=1;$i<=100;$i++){
			$str.='<tr>';
			for($j=1;$j<=$col; $j++){
				$str.='<td style="border:.5pt #000000 solid" height="30" align="center"></td>';
			}
			$str.='</tr>';
		}
		$str.= '</table>';
		return response($str)->withHeaders([
			'Content-type' 		=> 'application/vnd.ms-excel',
			'Content-disposition' => 'attachment;filename='.$num.'import.xls'
		]);
	}
}