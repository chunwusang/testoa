<?php
/**
*	管理首页-应用
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;

use App\Model\Base\FlowcourseModel;
use App\Model\Base\AgenhModel;

class ChajianUnitage_agenh extends ChajianUnitage
{
	
	public function getForm($request)
	{
		$obj 	= AgenhModel::select();
		$obj->where('cid', $this->companyid);

		$key 	= trim($request->get('keyword'));
		if(!isempt($key)){
			$obj->where(function($query)use($key){
				$query->where('name','like',"%$key%");
				$query->oRwhere('atype','like',"%$key%");
				$query->oRwhere('atypes','like',"%$key%");
				$query->oRwhere('num',$key);
			});
		}
		
		$total 	= $obj->count();
		$data 	= $obj->orderBy('sort')->simplePaginate($this->limit)->getCollection();

		
		$bdata	= array();
		foreach($data as $k=>$rs){
			$rs->atypeshow = $rs->atype;
			if(!isempt($rs->atypes))$rs->atype.='('.$rs->atypes.')';
			
			$rs->menutotal 		= $rs->getMenu()->count();
			$rs->coursetotal 	= $rs->getFlowcourse()->count();
			
			$bdata[$rs->atype][]	= $rs;
		}
		
		return [
			'bdata' 	=> $bdata,
			'total' 	=> $total,
			'iswxqy' 	=> $this->getNei('option')->iswxqy(),
			'mtable' 	=> c('rockjm')->encrypt('agenh'),
			'fielpda'	=> ['status','mctx','wxtx','emtx','ddtx','isgbjl','isgbcy'],
			'pager'		=> [
				'keyword' 	=> $key,
			],
		];
	}
	
	/**
	*	编辑获取
	*/
	public function editForm($request)
	{
		$id 	= (int)$request->get('id','0');
		
		$data 	= AgenhModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 		= 0;
			$data->name 	= '';
			$data->status	= 1;
			$data->sort		= 0;
			$data->description	= '';
			$data->face		= '';
			$data->facesrc	= '/images/nologo.png';
			$data->atype	= '';
			$data->summarx	= '';
			$data->agentid	= 0;
			$data->isflow	= 0;
			$data->num		= '';
			$data->usablename= '';
			$data->usableid	= '';
			$data->urlm		= '';
			$data->urlpc	= '';
			$data->yylx		= 0;
			$data->issy		= 0;
		}else{
			$data->facesrc = $data->face;
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		return [
			'pagetitles' 	=> trans('table/agenh.'.$ebts.''),
			'data'			=> $data
		];
	}
	
	/**
	*	流程设置
	*/
	public function courseForm($request)
	{
		$agenhid = (int)$request->get('agenhid','0');
		
		$agent= AgenhModel::find($agenhid);
		$data = FlowcourseModel::where('cid', $this->companyid)
				->where('agenhid', $agenhid)
				->orderByRaw('pid,sort')
				->get();
				
		$dapar	= array();
		$pid 	= -1;
		if($data)foreach($data as $k=>$rs){
			$dapar[$rs->pid][] = $rs;
			$pid= $rs->pid;
		}
		return [
			'pagetitles' 	=> '['.$agent->name.']'.trans('table/agenhcourse.pagetitle'),
			'agenhid'		=> $agenhid,
			'pid'			=> $pid+1,
			'dapar'			=> $dapar,
			'mtable' 		=> c('rockjm')->encrypt('flowcourse')
		];
	}
	
	public function courseeditForm($request)
	{
		$agenhid 	= (int)$request->get('agenhid','0');
		$id 		= (int)$request->get('id','0');
		$agent		= AgenhModel::find($agenhid);
		
		$data 	= FlowcourseModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 		= 0;
			$data->agenhid 	= $agenhid;
			$data->name 	= '';
			$data->num 	= '';
			
			$data->sort 	= 0;
			$data->status 	= 1;
			$data->recename 	='';
			$data->receid 		='';
			$data->checktype 	='';
			$data->checktypeid 	='';
			$data->checktypename 	='';
			$data->checkwhere 	='';
			$data->iszb 	= 0;
			$data->isqm 	= 0;
			$data->zshtime 	= 0;
			$data->zshstate = 1;
			$data->checksmlx = 1;
			$data->courseact = '';
			$data->checkfields = '';
			$data->pid 		= (int)$request->get('pid',0);
			
		}
		
		$data->agentid 	= $agent->agentid;
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		$obj 	= new FlowcourseModel();
		
		return [
			'pagetitles' 	=> '['.$agent->name.']'.trans('table/agenhcourse.'.$ebts.''),
			'data'			=> $data,
			'checktypearr'	=> ['super','user','dept','rank','change','auto','apply','opt','field']
		];
	}
	
	public function cogForm($request)
	{
		$agenhid 	= (int)$request->get('agenhid','0');
		
		$agent 		= AgenhModel::find($agenhid);
		$flow 		= \Rock::getFlow($agent->num, $this->useainfo);
		$fieldsrows	= $flow->getFieldsArr(1);
		
		return [
			'pagetitles' 	=> '['.$agent->name.']'.trans('table/agenh.cogtitle'),
			'data'			=> $fieldsrows,
			'agenhid'		=> $agenhid,
		];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	*	菜单管理
	*/
	public function menuForm($request)
	{
		$agenhid 	= (int)$request->get('agenhid','0');
		$agent= AgenhModel::find($agenhid);
		$data = $agent->getMenuArr($agenhid);
		
		return [
			'pagetitles' 	=> '['.$agent->name.']'.trans('table/agentmenu.pagetitle'),
			'data'			=> $data,
			'agenhid'		=> $agenhid,
		];
	}
	
	/**
	*	菜单录入 
	*/
	public function menueditForm($request)
	{
		$agenhid 	= (int)$request->get('agenhid','0');
		$id 	= (int)$request->get('id','0');
		$agent	= AgenhModel::find($agenhid);
		
		$data 	= AgenhmenuModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 		= 0;
			$data->agenhid 	= $agenhid;
			$data->name 	= '';
			$data->dev 		= '';
			$data->sort 	= 0;
			$data->status 	= 1;
			$data->type 	= 'auto';
			$data->num 		= '';
			$data->url 		= '';
			$data->color 	= '';
			$data->receid 	= '';
			$data->recename = '';
			$data->pid 		= (int)$request->get('pid',0);
			
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		$obj 	= new AgentmenuModel();
		
		return [
			'pagetitles' 	=> '['.$agent->name.']'.trans('table/agentmenu.'.$ebts.''),
			'data'			=> $data,
			'fieldstype'	=> $obj->typeArr($data->type)
		];
	}
}