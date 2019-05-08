<?php
/**
*	插件-默认值替换
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*	使用方法 $obj = c('devdata');
*/

namespace App\RainRock\Chajian\Base;
use Rock;


class ChajianBase_devdata extends ChajianBase
{
	private $bastarr;
	
	protected function initChajian()
	{
		$casa	= array(
			'now' 	=> nowdt(),
			'date' 	=> nowdt('dt'),
			'dtcn' 	=> nowdt('Y年m月d日'),
			'month' => nowdt('Y-m'),
			'time' 	=> nowdt('H:i:s'),
		);
		$this->bastarr	= $casa;
		if($this->useainfo){
			$utarr	= $this->useainfo->toArray();
			$this->bastarr['aid']= $utarr['id'];
			$this->bastarr['uid']= $utarr['uid'];
			$this->bastarr['cid']= $utarr['cid'];
			$this->bastarr['nowname']= $utarr['name'];
			foreach($utarr as $k=>$v)$this->bastarr['us.'.$k.''] = $v;
		}
	}
	
	public function getreparr()
	{
		return $this->bastarr;
	}
	
	/**
	*	替换,$lx=0默认,1sql的
	*/
	public function replace($str, $dev='', $lx=0)
	{
		if(isempt($str))return '';
		$val = $str;
		$nstr= Rock::matcharr($str);
		if($nstr)foreach($nstr as $kv){
			$val = str_replace('{'.$kv.'}', arrvalue($this->bastarr, $kv), $val);
		}
		if(isempt($val))$val = $dev;
		return $val;
	}
	
	/**
	*	数据源的处理
	*/
	public function getStore($frs, $flow, $datars, $glx=0)
	{
		$type = $frs->fieldstype;
		$dev  = objvalue($datars, $frs->fields); //值
		$data = $frs->data; //格式：option:编号,取值字段默认name
		$sjya = ['select','rockcombo','checkboxall','radio'];
		if($glx==1){
			$sjya[] = 'selectdatafalse';
			$sjya[] = 'selectdatatrue';
		}
		
		if(!in_array($type, $sjya) || isempt($data))return false;
		$xulz = 'selected';
		if($type=='radio' || $type=='checkboxall')$xulz = 'checked';
		
		$barr 	= $cdata = array();
		$readop = true;
		$dataa  = explode(',', $data);
		$num 	= $dataa[0];
		$defv 	= 'value';
		
		//判断是不是sql等：table$fields$where
		
		//直接写数据源格式如：1|男,2|女
		if(contain($data,'|') || count($dataa)>2){
			$cdata  = $this->getNei('baseda')->strtostore($data);
			$readop = false;
		//格式为：Chajian:actin	
		}else if(contain($num,':')){
			$readop = false;
			$lxs 	= strrpos($num, ':');
			$act1	= substr($num, 0, $lxs);
			$act2	= substr($num, $lxs+1);
			$chaj 	= c($act1, $this->useainfo);
			if(method_exists($chaj, $act2)){
				$cdata	= $chaj->setFlow($flow)->$act2($datars, $frs);
			}
		//是不是自定义方法	
		}else{
			if(method_exists($flow, $num)){
				$cdata	= $flow->$num($datars, $frs);
				$readop = false;
			}
		}
		
		//从数据选项读取
		if($readop){
			$cdata 	= $this->getNei('option')->getdata($num, $flow->agenhinfo->atype, $frs->name, $flow->mtable);
			$defv   = arrvalue($dataa, 1, 'name');
		}
		
		if($readop && !$cdata && count($dataa)>=2){
			$cdata  = $this->getNei('baseda')->strtostore($data);
		}
	
	
		if($cdata)foreach($cdata as $k=>$rs){
			$val 	= $rs['name'];
			if(isset($rs[$defv]))$val = $rs[$defv];
			$bsra 	= array(
				'name'  => $rs['name'],
				'value' => $val,
			);
			
			if($glx==1){
				unset($rs['name']);unset($rs['value']);
				foreach($rs as $k1=>$v1)$bsra[$k1] = $v1;
			}
			
			$barr[] = $bsra;
		}
		
		return $barr;
	}
	
	
	/**
	*	特殊sql条件替换
	*/
	public function replacesql($str, $kh=true)
	{
		$val = str_replace(['[',']'],['\'','\''] ,$str);
		$nstr= Rock::matcharr($str);
		$dtobj 		= $this->getNei('date');
		if($nstr)foreach($nstr as $kv){
			$fida	= explode(',', $kv);
			$lx 	= $fida[0];
			$fid	= arrvalue($fida, 1, 'receid'); //字段
			$thstr 	= '';
			if($lx=='receid'){
				$whea[] = $this->dbinstr("`$fid`", 'u'.$this->useaid.'');
				$whea[] = $this->dbinstr("`$fid`", ''.$this->useaid.'');
				
				//部门
				$depts  = $this->useainfo->deptpath;
				if(!isempt($depts)){
					$depta = explode(',', $depts);
					foreach($depta as $did){
						$whea[] = $this->dbinstr("`$fid`", 'd'.$did.'');
					}
				}
				
				//组
				$depts  = $this->useainfo->grouppath;
				if(!isempt($depts)){
					$depta = explode(',', $depts);
					foreach($depta as $did){
						$whea[] = $this->dbinstr("`$fid`", 'g'.$did.'');
					}
				}
				
				$thstr	= sprintf('(%s)', join(' or ', $whea));
			}
			
			//直属下级
			if($lx=='down'){
			}
			
			//全部下级
			if($lx=='downall'){
			}
			
			//字段中包含我，一般字段保存人员id，如：2,3格式{join,字段Id}
			if($lx=='join'){
				$thstr	= $this->dbinstr("`$fid`", $this->useaid);
			}
			
			
			//是否日期加减{date+1},{second-20}
			$match	= $kv;
			if(contain($match,'+') || contain($match,'-')){
				$add = 1;
				if(contain($match,'-'))$add=-1;
				$strss1	= explode('-', str_replace('+','-', $match));
				$dats  	= $strss1[0];
				$jg    	= (int)$strss1[1] * $add;;
				$cval  	= 'Y-m-d H:i:s';
				$lxs 	= 'd';
				if($dats=='date')$cval = 'Y-m-d';
				if($dats=='month'){
					$cval = 'Y-m';
					$lxs  = 'm';
				}
				if($dats=='hour')$lxs   = 'H';
				if($dats=='minute')$lxs = 'i';
				if($dats=='second')$lxs = 's';
				$thstr    = $dtobj->adddate($this->bastarr['now'], $lxs, $jg, $cval);
			}
			
			if($thstr!='')$val = str_replace('{'.$kv.'}', $thstr, $val);
		}
		$val 	= $this->replace($val, '', 1);
		
		if($val!='' && $kh)$val = '('.$val.')';//加上()
		return $val;
	}
}