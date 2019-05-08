<?php
/**
*	插件-内置加密的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	使用方法 $obj = c('rockjm');
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_rockjm extends ChajianBase
{
	
	private $keystr = 'abcdefghijklmnopqrstuvwxyz';
	private $jmsstr = '';

	
	protected function initChajian()
	{
		$this->initJm();
	}
	
	public function initJm()
	{
		$this->jmsstr = config('rock.randkey');
		$this->setRandkey($this->jmsstr);
		$this->getkeyshow();
	}
	
	public function setRandkey($str)
	{
		$this->jmsstr = $str;
		if(strlen($this->jmsstr)<26)$this->jmsstr = $this->keystr;
	}
	
	public function getRandkey()
	{
		$str = $this->keystr;
		$s 	 = '';$len = strlen($str);
		$j 	 = $len-1;
		for($i=0; $i<$len; $i++){
			$r = rand(0, $j);
			$zm= substr($str, $r, 1);
			$s.= $zm;
			$str = str_replace($zm,'',$str);
			$j--;
		}
		return $s;
	}
	
	public function getint($str)
	{
		$len = strlen($str);
		$oi  = 0;
		for($i=0; $i<$len; $i++){
			$l = substr($str,$i,1);
			$j = ord($l)-90;
			$oi+=$j;
		}
		if($oi<0)$oi=0-$oi;
		return $oi;
	}
	
	private function getrandstr($oi, $str='')
	{
		if($str=='')$str=$this->keystr;
		if($oi>100)$oi=100;
		$len = strlen($str);
		$qs  = 6;
		$s1  = substr($str, 0, $qs);
		$s2	 = substr($str, $qs, $qs);
		$s3  = substr($str, $qs*2, $len-$qs*2);
		$s   = $s3.$s2.$s1;
		if($oi>0)$s=$this->getrandstr($oi-1, $s);
		return $s;
	}
	
	public function getkeyshow()
	{
		$str = '~!@#$%^&*()_+{}[];"<>?:-=.';
		$len = strlen($this->jmsstr);
		$s 	 = '';
		for($i=0;$i<$len;$i++){
			$l = substr($this->jmsstr,$i,1);
			$j = ord($l)-97;
			$s.= substr($str,$j,1);
		}
		return $this->base64encode($s);
	}

	public function base64encode($str)
	{
		if(isempt($str))return '';
		$str	= base64_encode($str);
		$str	= str_replace(array('+', '/', '='), array('!', '.', ':'), $str);
		return $str;
	}
	
	public function base64decode($str)
	{
		if(isempt($str))return '';
		$str	= str_replace(array('!', '.', ':'), array('+', '/', '='), $str);
		$str	= base64_decode($str);
		return $str;
	}
	
	private function _getss($lx)
	{
		$st = '';
		if(is_numeric($lx)&&$lx>0){
			$st = $this->getrandstr($lx);
		}else if(is_string($lx)){
			if(strlen($lx)==26)$st=$lx;
		}
		return $st;
	}
	
	public function encrypt($str, $lx='')
	{
		$st = $this->_getss($lx);
		$s	= $this->base64encode($str);
		$s	= $this->encrypts($s, $st);
		return $s;
	}

	public function uncrypt($str, $lx='')
	{
		$st = $this->_getss($lx);
		$s	= $this->uncrypts($str, $st);
		$s	= $this->base64decode($s);
		return $s;
	}
	
	public function encrypts($str, $a='')
	{
		if($a=='')$a = $this->jmsstr;
		$nstr	= '';
		if(isempt($str)) return $nstr;
		$len	= strlen($str);
		$t		= rand(1, 14);
		if($t == 10)$t++;
		for($i=0; $i<$len; $i++){
			$nstr.='0';
			$sta	= substr($str,$i,1);
			$orstr	= ''.ord($sta).'';
			$ile	= strlen($orstr);
			for($j=0; $j<$ile; $j++){
				$oi	= (int)substr($orstr,$j,1)+$t;
				$nstr.= substr($a,$oi,1);
			}
		}
		if($nstr != ''){
			$nstr = substr($nstr,1);
			$nstr.= '0'.$t.'';
		}	
		return $nstr;
	}
	
	public function uncrypts($str, $a1='')
	{
		$nstr	= '';
		if(isempt($str)) return $nstr;
		if($a1=='')$a1	= $this->jmsstr;
		$a	= array();
		for($i=0; $i<strlen($a1); $i++)$a[substr($a1, $i, 1)] = ''.$i.'';
		$na	= explode('0', $str);
		$len= count($na);
		$r	= (int)$na[$len-1];
		for($i=0; $i<$len-1; $i++){
			$st	= $na[$i];
			$sl = strlen($st);
			$sa	= '';
			for($j=0; $j<$sl; $j++){
				$ha	= substr($st,$j,1);
				if(isset($a[$ha]))$ha = $a[$ha] - $r;
				$sa.=$ha;
			}
			$sa	= (int)$sa;
			$nstr.=chr($sa);
		}
		return $nstr;
	}
	
	/**
	*	对返回的数据加密
	*/
	public function encryptarr($rows, $jmlx=0, $fields='mobile,email')
	{
		if($rows){
			$fielda	= explode(',', $fields);
			if($jmlx==0)foreach($fielda as $fid)$rows->$fid = $this->encrypt($rows->$fid);
			if($jmlx==1)foreach($fielda as $fid)$rows->$fid = $this->base64encode($rows->$fid);
		}
		return $rows;
	}
}