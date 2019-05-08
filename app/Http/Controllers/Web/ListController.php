<?php
/**
*	应用列表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Model\Base\AgenhModel;
use App\Model\Base\BaseModel;
use Rock;

class ListController extends WebController
{
  
	/**
	*	列表页面
	*/
    public function index($cnum, $num, Request $request)
    {
    	// $bcd = 123123;
    	// return $bcd;
        $this->getAgenh($cnum, $num);
        if($this->useaid==0 || !$this->agenhinfo)return $this->returntishi(trans('validation.notagenh',['num'=>$num]));

        $yinglx		= ($this->agenhinfo->agentid>0) ? 'rocksys' : 'rockuse';

        $tplpath 	= 'web/'.$yinglx.'/'.$num.'';
		$autobo		= true;
		if(!file_exists(base_path('resources/views/'.$tplpath.'.blade.php'))){
			$tplpath = 'web/list/base';
			$autobo	 = false;
		}
		
		$flow 		= $this->flow = Rock::getFlow($num, $this->useainfo);
		
		//是否有新增的菜单
		$menuarr 	= c('agenh')->getMenuArr($this->agenhinfo, true);
		$addxu	 	= -1;
		foreach($menuarr as $k=>$rs)if($rs->type=='add')$addxu = $k;
		$barr['menuarr']	= $menuarr;
		$barr['addxu']		= $addxu;
		$barr['adminid']	= $this->useaid;

		//--头部应用列表--
		$barr['tplpath']	= $tplpath;
		$barr['ltoption']	= $flow->listoption(); //扩展按钮
		$barr['keywordmsg']	= trans('base.keyword');
			
		//默认使用列表	
		if(!$autobo){
			$agenhbarr	= c('agenh',$this->useainfo)->getAgenh(1);
			$agenharr	= $agenhbarr[0];
			$agenhtarr	= $agenhbarr[1];
			
			$barr['agenharr']	= $agenharr;
			$barr['agenhtarr']	= $agenhtarr;
			
			//获取对应应用字段
			
			$fieldsrows	= $flow->getFieldsArr(1);
			$fieldsarr	= new \StdClass();
			foreach($fieldsrows as $k=>$rs){
				$fieldsarr->{$rs->fields} = $rs;
			}
			$barr['fieldsrows']	= $fieldsrows;
			$barr['fieldsarr']	= $fieldsarr;
			
			$authobj 			= c('authory', $this->useainfo);
		
			//是否有导出导入权限
			$barr['isdaochu']	= $authobj->isdaochu($this->agenhinfo->id);
			$barr['isdaoru']	= $authobj->isdaoru($this->agenhinfo->id);
			
			//计算角标的
			$bagstr 			= '';
			$ptotal				= array();
			$addmenu			= false;
			foreach($barr['menuarr'] as $k=>$rs){
				$wdtotal	 = 0;
				if($rs->isbag==1){
					$_atype  = $rs->url.'_'.$rs->id;
					$bagstr .= ','.$_atype;
					$wdtotal = $flow->getstotal($_atype);
				}
				if($rs->pid>0){
					if(!isset($ptotal[$rs->pid]))$ptotal[$rs->pid] = 0;
					$ptotal[$rs->pid] += $wdtotal; //统计到上级
				}
				$barr['menuarr'][$k]->wdtotal = $wdtotal;
				if($rs->type=='add'){
					$rs->disabled = $flow->isaddqx() ? '' : 'disabled';
					$addmenu = $rs;
				}
			}
			foreach($barr['menuarr'] as $k=>$rs){
				$wdtotal	= $rs->wdtotal;
				if(isset($ptotal[$rs->id])){
					$wdtotal+= $ptotal[$rs->id];
				}
				if($wdtotal==0)$wdtotal = '';
				$barr['menuarr'][$k]->wdtotal = $wdtotal;
			}
			
			if($bagstr!='')$bagstr = substr($bagstr, 1);
			$barr['bagstr']		= $bagstr;
			$barr['addmenu']	= $addmenu;
			
			//用户类型0,1,2
			$barr['useatype']	= $authobj->useatype();
			
		}
		$barr['autopath']	= $autobo; //是否自定义页面
		
		$larr	= $flow->flowlistview(0, $barr);
		if($larr && is_array($larr))foreach($larr as $k=>$v)$barr[$k] = $v;
		
		
		//相关js
		$jspath1			= '';
		if($flow->agenhnum!=$num){
			$jspath1		= 'res/agent/'.$flow->agenhnum.'_list.js';
			if(!file_exists(public_path($jspath1)))$jspath1 = '';
		}
		$barr['jspath1']	= $jspath1;
		
		$jspath				= 'res/agent/'.$num.'_list.js';
		if(!file_exists(public_path($jspath)))$jspath = '';
		$barr['jspath']		= $jspath;
		
		$extendpath 	= 'web/'.$yinglx.'/'.$num.'_extend';
		if(!file_exists(base_path('resources/views/'.$extendpath.'.blade.php'))){
			$extendpath = '';
		}
		$barr['extendpath']		= $extendpath;
		
	
		return $this->getShowView('list', $cnum, $num, $request,$barr);
    }
	
	/**
	*	列表应用数据读取
	*/
	public function listdata($cnum, $num, Request $request)
	{
		$this->getAgenh($cnum, $num);
		if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));

		$limit 		= (int)$request->get('limit','0');
		$atype 		= $request->get('atype');
		if($limit<1)$limit = 10;
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		$flow->limit= $limit;
		$page 		= 1;
		if($request->has('offset')){
			$page	= (int)$request->get('offset') / $limit + 1;
		}
		
		//排序
		if($request->has('sort') && $request->has('order')){
			$flow->defaultorder = ''.$request->get('sort').','.$request->get('order').'';
		}
			
		if($request->has('page'))$page	= (int)$request->get('page');
		if($page<1)$page = 1;
		
		$data 		= $flow->getYingData($atype, $page, 1);
		
		//导出处理
		if($request->get('daochu')=='true'){
			$fieldsarr	= $flow->getFieldsArr(1);
			return $this->daochudata($data, $fieldsarr, $request, $cnum, $num);
		}
		
		//微章角标
		$bagstr		= $request->get('bagstr');
		$bagarr		= array();
		if(!isempt($bagstr)){
			$bagstra= explode(',', $bagstr);
			foreach($bagstra as $bag)$bagarr[$bag] = $flow->getstotal($bag);
		}
		
		$data['bagarr'] = $bagarr;
		$data['rows'] 	= arrvalue($data, 'rows', array());
		$data['total'] 	= arrvalue($data, 'rowsCount', 0);
		$data['dataurl']= c('base')->nowurl();

		// $beta['bagarr'] = 1;
		// $beta['rows'] 	= 2;
		// $beta['total'] 	= 3;
		// $beta['dataurl']= 4;
		// return $beta;
		return $data;
	}
	
	private function daochudata($data, $fieldsarr, Request $request,$cnum, $num)
	{
		// $bcc = 1234;

		// return $bcc;
		$rows = $data['rows'];
		$field= c('rockjm')->base64decode($request->get('field',''));
		$headArr = array();
		foreach($fieldsarr as $k=>$rs){
			if($rs->iszb==0 && $rs->status==1){
				if(isempt($field)){
					if($rs->islb==1)$headArr[] = $rs;
				}else{
					if(contain(','.$field.',', ','.$rs->fields.','))$headArr[] = $rs;
				}
			}
		}
		$nums = ''.$cnum.'_'.$num.'_'.time().'';
		$dir  = storage_path('app/'.date('Y-m').'');
		if(!is_dir($dir))mkdir($dir);
		$path = ''.$dir.'/'.$nums.'.xls';
		$title= $data['datatitle'];
		
	
		$borst  = '.5pt';
		$sty 	= 'style="white-space:nowrap;border:'.$borst.' solid #000000;font-size:12px;"';
		$s 		= '<html><head><meta charset="utf-8"><title>'.$title.'</title></head><body>';
		$s 	   .= '<table border="0" style="border-collapse:collapse;">';
		$hlen 	= 0;
		$s1='<tr height="30">';
		//$s1='<tr height="30"><td '.$sty.' bgcolor="#cdf79e">序号</td>';
		foreach($headArr as $na){
			$hlen++;
			$s1.='<td '.$sty.' bgcolor="#cdf79e">'.$na->name.'</td>';
		}
		$s1.='</tr>';
		$s.='<tr height="40"><td '.$sty.' colspan="'.$hlen.'">'.$title.'</td></tr>';
		$s.=$s1;
		foreach($rows as $k=>$rs){
			$s.='<tr height="26">';
			//$s.='<td align="center" '.$sty.'>'.($k+1).'</td>';
			foreach($headArr as $kf=>$na){
				$val = objvalue($rs, $na->fields);
				if(!isempt($val) && $na->fieldstype=='uploadimg'){
					$val = Rock::replaceurl($val);
					$val = '<img src="'.$val.'" width="100">';
				}
				$s.='<td '.$sty.'>'.$val.'</td>';
			}
			$s.='</tr>';
		}
		$s.='<tr height="26"><td '.$sty.' colspan="'.$hlen.'" align="left">共有'.$data['rowsCount'].'条记录，导出'.$data['nowCount'].'条</td></tr>';
		$s.='</table>';
		$s.='</body></html>';
		@$bo = file_put_contents($path, $s);
		if(!file_exists($path))return $this->returntishi('downfile not found');
		
		return response()->download($path, ''.$title.'.xls');
	}
}