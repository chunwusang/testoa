<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;


use App\Model\Base\CompanyModel;
use Illuminate\Http\Request;
use DB;


class CompanyController extends AdminController
{
  

    /**
	*	单位列表
    */
    public function index(Request $request)
    {
		$this->getLimit();
		
		$obj 	= CompanyModel::latest();
		$total 	= $obj->count();
		$data 	= $obj->simplePaginate($this->limit)->getCollection();
	


        return $this->getShowView('admin/company', [
			'pagetitle' => trans('table/company.pagetitle'),
			'data' 		=> $data,
			'pager'		=> $this->getPager('admincompany', $total),
			'mtable' 	=> c('rockjm')->encrypt('company'),
			'tpl'		=> 'companylist',
			'kzq'		=> 'Admin/Company',
			'lang'		=> 'table/company',
			
		]);
    }
	
	/**
	*	单位编辑新增
	*/
	public function getForm($id=0, Request $request)
	{
		$data 	= CompanyModel::find($id);
		if(!$data){
			$data	= new \StdClass();
			$data->id 	= 0;
			$data->flaskm 	= 100;
			$data->name 	= '';
			$data->num 	= '';
			$data->shortname 	= '';
			$data->tel 			= '';
			$data->contacts 	= '';
			$data->status 	= 1;
			$data->logo 	= '/images/nologo.png';
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		return $this->getShowView('admin/companyedit', [
			'pagetitle' 	=> trans('table/company.'.$ebts.''),
			'data'			=> $data,
			'tpl'			=> 'companylist'
		]);
	}
	
	/**
	*	数据保存
	*/
	public function saveData(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		
		if($id==0)return $this->returnerror('不支持新增');
		
		$this->validate($request, [
            'name' 		=> 'required',
            'shortname' => 'required',
            'num'       => 'required|unique:company,num,'.$id.'',
        ],[
			'num.unique' => trans('table/company.num_unique')
		]);
		
		
		$data 	= ($id > 0) ? CompanyModel::find($id) : new CompanyModel();
		
		$data->name 	= $request->name;
		$data->shortname= $request->shortname;
		$data->num 		= $request->num;
		$data->logo 	= $request->logo;
		$data->tel 		= nulltoempty($request->tel);
		$data->contacts = nulltoempty($request->contacts);
		$data->flaskm 	= (int)$request->flaskm;
		$data->status 	= (int)$request->status;
		
		$data->save();
	}
	
	
	
}
