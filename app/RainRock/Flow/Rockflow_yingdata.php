<?php
/**
*	工作流-核心文件_应用列表数据
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flow;
use Rock;
use App\Model\Base\AgentmenuModel;
use App\Model\Base\FlowchaoModel;
use App\Model\Base\FlowbillModel;
use Schema;


trait Rockflow_yingdata
{
	protected $fieldsaid		= 'aid'; //主关联字段
		
	public $defaultorder		= 'id,desc';//默认排序
	
	public $page;
	public $atype;
	public $datatitle;
	public $keyword				= '';
	public $belongstofields		= '';
	public $flowbillatype		= ''; //unread未读
	protected $downfields		= 'aid'; //下属down/downall用哪个字段
	
	private $shotstart 			= 0;
	
	/**
	*	获取应用列表数据 $glx=0手机版本读取(弃用)，1列表页面读取,2获取总记录数,3获取自己对象
	*/
	public function getYingData($atype, $page=1, $glx=1)
	{
		$this->datatitle= $this->agenhname;
		if(isempt($this->mtable))return array();
		$this->page 	= $page;
		if(isempt($atype))$atype = 'all';
		$key			= c('rockjm')->base64decode(nulltoempty(\Request::get('key')));
		$zidanarr		= Schema::getColumnListing($this->mtable);
		$this->keyword 	= $key;		
		$this->atype 	= $atype;
		$this->atypeid 	= 0;
		if(contain($atype,'_')){
			$_wz 			= strrpos($atype, '_');
			$this->atypeid 	= (int)substr($atype, $_wz+1);
			$atype			= substr($atype,0, $_wz);
		}
		
		$obj 			= $this->getModel()->select(); //创建个对象
		
		$obj->where('cid', $this->companyid); //自己单位下的数据
		$boolbo			= false; //是否没有设置条件
		$menurs			= false;
		$readqxbo		= false;
		$wherestr		= '';
		
		//读取菜单信息
		if($this->agentid>0){
			$_mwhe['agentid'] = $this->agentid;
			if($this->atypeid>0){
				$_mwhe['id'] = $this->atypeid;
			}else{
				$_mwhe['url'] = $atype;
			}
			$menurs 		= AgentmenuModel::where($_mwhe)->first();
		}
		
		$mwhere 		= $this->agenhinfo->agenhmwhere;
		if(!isempt($mwhere))$obj->whereRaw($mwhere);
		$isgul			= contain($atype, 'manage');
		
		
		//条件
		if(substr($atype,0,2)=='my'){
			$obj->where($this->fieldsaid, $this->useaid);
			$boolbo		= true;
		}
		
		
		if($menurs){
			if(objvalue($menurs,'isturn')==1)$obj->where('isturn', 1); //需提交
			if(objvalue($menurs,'iswzf')==1)$obj->where('status','<>', 5); //未作废	
			$this->datatitle.='('.$menurs->name.')';
		}
		
		//查看直属下级
		if($atype=='down' || $atype=='downunread'){
			$aida		= $this->getNei('usera')->getdownaid();
			$obj->whereIn($this->downfields, $aida);
			$boolbo		= true;
		}
		
		//查看全部直属下级(包括下级的下级)
		if($atype=='downall' || $atype=='downallunread'){
			$aida		= $this->getNei('usera')->getdownallaid();
			$obj->whereIn($this->downfields, $aida);
			$boolbo		= true;
		}
		
		//抄送给我
		if($atype=='chaos' || $atype=='chaosunread'){
			$cobj	= FlowchaoModel::where('cid', $this->companyid);
			$cobj	= $cobj->whereRaw($this->authoryobj->dbinstr('chaoid', $this->useaid));
			$boft	= true;
			if($this->mtable!='flowbill'){
				$cobj	= $cobj->where('agentid', $this->agentid);
				$boft	= false;
			}
			$crows	= $cobj->get();
			$dibaid	= array(0);
			foreach($crows as $k=>$rs){
				$_ids = $rs->billid;
				if(!$boft)$_ids = $rs->mid;
				if(!in_array($_ids, $dibaid))$dibaid[] = $_ids;
			}
			$obj->whereIn('id', $dibaid);
			$boolbo	= true;
		}
		
		//未提交
		if(contain($atype,'noturn')){
			$obj->where('isturn',0);
		}
		
		
		//接口文件自定义条件
		$objsot			= $this->flowbillwhere($obj, $atype, $glx);
		if($objsot !== false){
			if($objsot===0){
				$obj->where('id', 0);
			}else{
				if($objsot)$obj 	= $objsot;
			}
			$boolbo		= true;
		}
		
		
		//管理，判断我是不是管理员
		if($isgul){
			$utype = $this->authoryobj->useatype();
			if($utype==0){
				$readqxbo 	= true;	//普通用户没管理权限，需要读取设置的
			}else{
				$boolbo 	= true; //可以管理全部数据
			}
		}
		
		//已读的Id
		$readmid	= array();
		if($this->agenhnum != 'todo'){
			if($this->mtable=='flowbill'){
				$readmid 	= $this->getNei('flowread')->getreadbill($this->useaid);
			}else{
				$readmid 	= $this->getNei('flowread')->getread($this->mtable, $this->useaid);
			}
			//未读的
			if(contain($atype,'unread') || $this->flowbillatype=='unread'){
				if($readmid)$obj->whereNotin('id', $readmid);
			}else if(contain($atype,'read')){
				$mida = $readmid;
				if(!$mida)$mida[] = 0;
				$obj->whereIn('id', $mida);
			}
		}
		
		//关联条件过滤的
		if($menurs){
			$wherestr = $menurs->wherestr;
			if(!isempt($wherestr)){
				$wherestr 	= $this->getNei('devdata')->replacesql($wherestr);
				if(!$isgul)$boolbo		= true; //不是管理类型时
				$obj->whereRaw($wherestr);
			}
		}
		
		if($atype=='all' || $atype=='allunread'){
			if(!$boolbo){
				$readqxbo = true;
			}
		}
		
		//授权查看的权限管理下设置可查看
		if(substr($atype,0,4)=='view'){
			$readqxbo = true;
		}
		
		
		//默认可以查看全部，这个比较难做哦，是否需要读取权限表上设置可查看的的
		if($readqxbo){
			$useaid		= $this->useaid;
			$wrows		= $this->authoryobj->getviewwhere($this->agenhinfo->id);
			
			if($wrows){
				//判断是否可以查看全部
				$isall	   = false;
				foreach($wrows as $k=>$rs){
					if($rs->wherestr=='1=1'){
						$isall = true;
						break;
					}
				}
				if(!$isall){
					$obj->where(function($query)use($useaid, $wrows){
						$query->where('aid', $useaid);
						foreach($wrows as $k=>$rs){
							$query->orWhereRaw($rs->wherestr);
						}
					});
				}
				$boolbo = true;
			}else{
				//没有设置数据查看权限
			}
		}
		
		//没有条件设置
		if(!$boolbo)$obj->where($this->fieldsaid, $this->useaid);
		
		//关键词搜索
		if(!isempt($key)){
			$farr 		= $this->getFieldsArr(1);
			$tofields	= $this->belongstofields;
			$isflow		= $this->isflow;
			$obj->where(function($query)use($key, $farr, $tofields, $isflow){
				
				$souar  = ['text','textarea','num'];
				$isname = false;
				foreach($farr as $k=>$rs){
					if($rs->iszb==0 && $rs->fields=='name')$isname = true;
					if($rs->isss==0 || $rs->iszb>0)continue;
					if(in_array($rs->fieldstype, $souar) || 
						substr($rs->fieldstype,0,6)=='change'){
						$query->orWhere($rs->fields,'like', '%'.$key.'%');
					}
				}
				
				//关联扩展搜索
				if($tofields!=''){
					$tofieldsa 	= explode(',', $tofields);
					$query->orWhereHas($tofieldsa[0], function($query)use ($key, $tofieldsa){
						for($i=1;$i<count($tofieldsa);$i++){
							if($i==1){
								$query->where($tofieldsa[$i],'like', '%'.$key.'%');
							}else{
								$query->orWhere($tofieldsa[$i],'like', '%'.$key.'%');
							}
						}
					});
				}
				
				//有流程搜索对应人员部门和姓名等
				if($isflow>0 && !is_numeric($key)){
					$query->orWhereHas('useainfo', function($query)use ($key, $isname){
						$query->where('deptallname','like', '%'.$key.'%');
						if(!$isname)$query->orWhere('name','like', '%'.$key.'%'); 
						$query->orWhere('position','like', '%'.$key.'%');
					});
				}
				
			});
		}
		
		$rows			= array();
		$count			= $obj->count(); //总记录数
		
		\DB::connection()->enableQueryLog();

		if($glx==2)return $count; 
		if($glx==3)return $obj; 
		
		//排序
		if(!isempt($this->defaultorder)){
			$ordera 		= explode('|', $this->defaultorder); //排序
			foreach($ordera as $ordera1){
				$orderaa = explode(',', $ordera1);
				$obj->orderBy($orderaa[0], arrvalue($orderaa, 1, 'asc'));
			}
		}
		
		$rowa 			= $obj->simplePaginate($this->limit, ['*'], 'page', $page)
							->getCollection();	
		
		if(\Request::get('printsql')=='true')print_r(\DB::getQueryLog());
		$this->shotstart= ($page-1) * $this->limit;
		
		//要原始数据，管理页面列表页
		$rows 			= $this->yingdatarows($rowa, $readmid);
		$data['rows'] 	= $rows;
		$data['pager'] 	= [
			'count' 	=> $count,
			'page' 		=> $page,
			'maxpage' 	=> ceil($count/$this->limit),
		];
		$data['atype']		= $atype;
		$data['agenhnum']	= $this->agenhnum;
		$data['rowsCount']	= $count;
		$data['datatitle']	= $this->datatitle;
		$data['nowCount']	= count($rows);
		
		$carr 	= $this->flowactrun('flowgetdata', $rows);
		if($carr && is_array($carr))foreach($carr as $k=>$v)$data[$k] = $v;
		
		return $data;
	}
	
	/**
	*	下属的，也就是自动中,分开里面包含我下属，如“5,1,2”，里面有我下属就显示
	*/
	public function getDownobj($obj, $fields, $aid=0)
	{
		$adod = $this->getNei('usera')->getdownaid($aid,false);
		if($adod){
			return $obj->where(function($query)use($adod, $fields){
				foreach($adod as $aid){
					$query->oRwhereRaw("instr(concat(',', `$fields`, ','), ',".$aid.",')>0");
				}
			});
		}else{
			return null; //返回空
		}
	}
	
	//格式化一下数据
	private function yingdatarows($rowa, $readmid=array())
	{
		$rows = array();
		foreach($rowa->toArray() as $k=>$rsdata){
			$rs 		= new \StdClass();
			foreach($rsdata as $k1=>$v1)$rs->$k1 = $v1;
			
			$rs->isread	= (in_array($rs->id, $readmid)) ? 1 : 0;
			$nowstatus 	= $this->getnowstatus($rs);
			
			//有流程的单据状态
			if($this->isflow>0){
				$billrs 	= FlowbillModel::where(['mtable'=>$this->mtable, 'mid'=>$rs->id])->first();
				
				if(!$billrs)$billrs = $this->saveFlowBill($rs->id, $rs);
				$rs->base_sericnum 	= $billrs->sericnum; //不知道这为啥不生效。。
				
				if(!isempt($billrs->nowstatus))$nowstatus = $billrs->nowstatus;
				if(!isset($rs->applydt))$rs->applydt = $billrs->applydt;
				if($billrs){
					if(isempt($rs->base_name)){
						$rs->base_name 		= $billrs->applyname;
						$rs->base_deptname 	= $billrs->applydeptname;
					}
				}
			}
			
			$rs 		= $this->flowreplacers($rs, 2); //替换数据
			if(isset($rs->status)){
				if($rs->status==5)$rs->ishui = 1;//作废了
				$rs->status = $nowstatus;
			}
			$rs->sysxu = $k+1+$this->shotstart;
			$rows[]	= $rs;
		}
		return $rows;
	}
	
	/**
	*	根据条件获取数据
	*/
	public function getWheredata($where)
	{
		if(isempt($this->mtable))return array();
		$obj 	= $this->getModel(); //创建个对象
		$obj 	= $obj->where('cid', $this->companyid);
		$mwhere = $this->agenhinfo->agenhmwhere;
		if(!isempt($mwhere))$obj = $obj->whereRaw($mwhere);
		
		$obj	= $obj->whereRaw($where);
		$rowa	= $obj->get();
		$rows 	= $this->yingdatarows($rowa);
		
		return $rows;
	}
	
	
	/**
	*	获取统计
	*/
	public function getstotal($atype)
	{
		return $this->getYingData($atype, 1, 2);
	}
	
	/**
	*	获取条件对象
	*/
	public function getWhereobj($atype)
	{
		return $this->getYingData($atype, 1, 3);
	}
	
	public function getBaseData($atype ,$limit=5, $order='')
	{
		$this->limit = $limit;
		if($order!='')$this->defaultorder = $order;
		return $this->getYingData($atype, 1, 1);
	}
	
	/**
	*	获取图片原图路径
	*/
	public function getimgyuan($str)
	{
		if(isempt($str))return '';
		$str 	= str_replace('_s.','.', $str);
		return Rock::replaceurl($str);
	}
	
	/**
	*	详情时获取数据
	*/
	public function getDatail($mid)
	{
		$sbar		= $this->initdata($mid);
		if(!$sbar['success'])abort(404, $sbar['msg']);
		
		$fieldsarr 	= $this->getFieldsArr('xiang');
		$flowinfo	= $this->getflowinfo(); //在读取数据之前处理
		$isedit 	= $this->iseditqx();
		$ydata		= clone $this->rs;
		$data 		= $this->flowreplacers($this->rs, 1);

		$fobj 		= c('file');
		if(isset($rs->status))$rs->status = $this->getnowstatus($rs);
		$subdata	= $this->getsubdata(1); //子表
		foreach($fieldsarr as $k=>$rs){
			$flx = $rs->fieldstype;
			$fid = $rs->fields;
			$val = objvalue($data, $fid);
			
			if($flx=='uploadimg'){
				if(!isempt($val)  && !contain($val,'<')){
					$data->$fid = '<img onclick="c.imgviews(this)" src="'.Rock::replaceurl($val).'" height="130">'; //缩略图
				}
			}
			
			if($flx=='uploadfile'){
				if($fid=='sysupfile'){
					$data->$fid = $fobj->getfilexg($this->mtable, $this->mid);
				}else{
					$data->$fid = $fobj->getfilestr($val);
				}
				$ydata->$fid 	= $data->$fid;
			}
			
			//创建子表详情
			if($flx=='subtable'){
				$iszb = (int)objvalue($rs,'dev','1');
				$data->$fid = $this->getsubdataDetail($iszb, $fieldsarr, arrvalue($subdata, $iszb, array()));
			}
		}
		
		$logarr	= $readarr =array();
		
		//写入查阅记录
		c('flowread', $this->useainfo)->addread(
			$this->mtable, 
			$this->mid, 
			$this->agentid,
			objvalue($this->billrs,'id', '0')
		);
		
		//获取查阅记录
		if($this->agenhinfo->isgbcy==0)
			$readarr= c('flowread')->getreadarr($this->mtable, $this->mid);
		
		//操作记录
		if($this->agenhinfo->isgbjl==0)
			$logarr = $this->getlog();
		
		if(isset($data->status)){
			$data->statusval = $data->status;
			$data->status = $this->getnowstatus($data);
		}
		
		$barr = array(
			'data' 			=> $data,	//记录
			'ydata' 		=> $ydata,	//原始记录
			'fieldsarr'		=> $fieldsarr,
			'inputobj'		=> $this->getNei('input'),
			'logarr'		=> $logarr,
			'pagetitle' 	=> objvalue($data, 'title', $this->agenhinfo->name), //页面上标题
			'detailtitle' 	=> $this->getDatailtitle(),
			'readarr' 		=> $readarr,
			'flowinfo'		=> $flowinfo,
			'isedit'		=> $isedit,
			'bodywidth'		=> $this->detailbodywidth
		);
		$cbarr 	 = $this->flowgetdetail();
		if($cbarr)foreach($cbarr as $k=>$v)$barr[$k] = $v;
		return $barr;
	}
	
	
	/**
	*	子表数据
	*	$glx0录入,1展示
	*/
	public function getsubdata($glx=0, $mid=0)
	{
		$data = array();
		if($mid==0)$mid = $this->mid;
		if(isempt($this->agentinfo->tables))return $data;
		$tablea = explode(',', $this->agentinfo->tables);
		foreach($tablea as $k=>$tab){
			$iszb 	= $k+1;
			if($mid>0){
				$objd	= $this->getModels($tab)->where([
						'cid'	=> $this->companyid,
						'mid'	=> $mid,
						'sslx'	=> $iszb
						])->orderBy('sort')->get();
			}else{
				$objd		= array();
			}
			$data[$iszb] 	= $objd;
		}
		return $data;
	}
	
	/**
	*	获取子表数据
	*/
	public function getsubdatalist($iszb=1, $mid=0)
	{
		$subdata = $this->getsubdata(1, $mid);
		return arrvalue($subdata, $iszb, array());
	}
	
	//子表生成表格
	private function getsubdataDetail($iszb, $fieldsarr, $data)
	{
		$data = $this->flowreplacerssub($data); //替换数据
		$farr = array();
		foreach($fieldsarr as $k=>$rs){
			if($rs->iszs==1 && $rs->iszb==$iszb)$farr[] = $rs;
		}
		return $this->createtable($farr, $data);
	}
	
	/**
	*	创建表格
	*/
	public function createtable($farr, $data)
	{
		$str = '<table class="subtable" width="100%">';
		$str.='<tr bgcolor="#f1f1f1"><td width="30"></td>';
		foreach($farr as $k=>$rs){
			$str.='<td>'.$rs->name.'</td>';
		}
		$str.= '</tr>';
		foreach($data as $k1=>$rs1){
			$str.='<tr><td align="center" width="30">'.($k1+1).'</td>';
			foreach($farr as $k=>$rs){
				$str.='<td>'.objvalue($rs1,$rs->fields).'</td>';
			}
			$str.= '</tr>';
		}
		$str.= '</table>';
		return $str;
	}
	
	/**
	*	详情也标题,模块模块名称，可以从写
	*/
	public function getDatailtitle()
	{
		return $this->agenhinfo->name;
	}
	
	
	/**
	*	 读取相关文件
	*/
	public function getFilelist($lx=0)
	{
		if($this->mid==0)return array();
		$rows = c('file')->getlist($this->mtable, $this->mid, $lx);
		return $rows;
	}
	
	
	/**
	*	列表页选项菜单
	*/
	public function listoption()
	{
		$barr = $this->flowactrun('flowlistoption');
		$optcolums 		= true;
		$checkcolums 	= false;
		$btnarr 		= array();
		$leftstr 		= '';
		if($barr && is_array($barr)){
			if(isset($barr['optcolums']))$optcolums = $barr['optcolums'];
			if(isset($barr['checkcolums']))$checkcolums = $barr['checkcolums'];
			if(isset($barr['btnarr']))$btnarr = $barr['btnarr'];
			if(isset($barr['leftstr']))$leftstr = $barr['leftstr'];
			if(isset($barr[0]))$btnarr = $barr;
			
		}
		return [
			'optcolums'	   	=> $optcolums, 	//操作列
			'leftstr'	   	=> $leftstr, 	//左边
			'checkcolums'  	=> $checkcolums , 	//复选框列
			'btnarr'  		=> $btnarr  //右边按钮
		];
	}
	
}