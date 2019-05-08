<?php
/**
*	api单位管理-数据选项
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use App\Model\Base\OptionModel;

class ChajianUnitapi_option extends ChajianUnitapi
{
	
	/**
	*	保存
	*/
	public function postData($request)
	{
		$pid 	= (int)$request->input('pid');
		
		$data 	= new OptionModel();
		
		$data->pid 		= $pid;
		$data->name 	= 'new add';

		$data->cid 		= $this->companyid;
		$data->optdt 	= nowdt();
		
		$data->save();	
		return returnsuccess();
	}
	
	/**
	*	删除
	*/
	public function postdelcheck($request)
	{
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('iderror');
		
		$to 	= OptionModel::select()->where('pid', $id)->count();
		if($to>0)return returnerror(trans('table/option.delinfo'));
		
		OptionModel::find($id)->delete();
		
		return returnsuccess();
	}
	
	/*
	*	双击保存
	*/
	public function postsaveediter($request)
	{
		$mtable	= $request->input('mtable');
		$id		= (int)$request->input('id','0');
		$value	= nulltoempty($request->input('value'));
		$fields	= $request->input('fields');
		$table 	= c('rockjm')->uncrypt($mtable);
		$tabarr = explode(',','usera,dept,group,authory,option,agenh,flowcourse,wxqyagent');
		if(!in_array($table, $tabarr))
			return returnerror(trans('validation.notextent').'1');
		
		if($table=='usera' && $fields=='status')
			return $this->getNei('usera')->changestatus($id, (int)$value);
		
		\DB::table($table)
			->where('id', $id)
			->where('cid', $this->companyid)
			->update([
			$fields => $value
		]);
		return returnsuccess();
	}
	
	/**
	*	录入保存其他值
	*/
	public function postsaveother($request)
	{
		$name 	= $request->input('name');
		$num 	= $request->input('num');
		$sort 	= (int)$request->input('sort',0);
		if(isempt($name) || isempt($num))return returnsuccess();
		$optobj = $this->getNei('option');
		$pid 	= $optobj->getval($num, 3,0);
		if($pid==0)return returnsuccess();
		
		$onrs 	= $optobj->getone("`pid`='$pid' and `name`='其他..'");
		if($onrs){
			$sort 	= $onrs['sort'];
			$obj 	= OptionModel::where('id',$onrs['id']);
			$obj->update(['sort'=>$sort+1]);
		}			
		$data 	= new OptionModel();
		
		$data->pid 		= $pid;
		$data->name 	= $name;
		$data->sort 	= $sort;

		$data->cid 		= $this->companyid;
		$data->optdt 	= nowdt();
		
		$data->save();	
		return returnsuccess();
	}
	
	/**
	*	导入默认数据选项
	*/
	public function importxuan()
	{
		$barr = $this->getNei('agenh')->getAgenhlist($this->companyid, 9);
		$toa  = 0;
		foreach($barr as $k=>$nrs){
			$table = objvalue($nrs, 'mtable');
			if(!isempt($table)){
				$obubj = $this->getNei('Rockmos:'.$table.'');
				if($obubj && method_exists($obubj, 'adddefaultOption')){
					$to = $obubj->adddefaultOption($nrs->atype);
				}
				$toa+=$to;
			}
		}
		$msg = '没有可导入选项';
		if($toa>0)$msg = '成功导入'.$toa.'条';
		return returnsuccess($msg);
	}
}