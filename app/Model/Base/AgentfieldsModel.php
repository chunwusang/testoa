<?php
/**
*	应用字段
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Rock;
use DB;


class AgentfieldsModel extends Model
{
	protected $table 	= 'agentfields';
	
	public $timestamps 	= false;
	
	/**
	*	元素字段类型
	*/
	public function fieldstypeArr($dev)
	{
		$typear = ['text','select','rockcombo','hidden','textarea','htmlediter','number','date','datetime','time','month','checkbox','checkboxall','radio','uploadimg','uploadfile','changeuser','changeusercheck','changedept','changedeptcheck','changedeptusercheck','selectdatafalse','selectdatatrue','num','auto','subtable'];
		$arr 	= array();
		foreach($typear as $lx){
			$sel = '';
			if($dev==$lx)$sel='selected';
			$arr[] = ['value'=>$lx,'name'=>trans('table/agentfields.fieldstype_'.$lx.'').'('.$lx.')','selected'=>$sel];
		}
		return $arr;
	}
	
	/**
	*	字段不存在就创建
	*/
	public function createFields($agentid, $fieldsid, $oldfieldrs=false, $iszb=0)
	{
		$agentrs 	= AgentModel::find($agentid);
		$fieldsr 	= $this->find($fieldsid);
		
		$mtable 	 = $agentrs->table;
		$table 	 	= $agentrs->table;
		
		
		if($iszb>0){
			$tables = $agentrs->tables;
			$names  = $agentrs->names;
			if(!isempt($tables)){
				$tablesa = explode(',', $tables);
				$table	 = arrvalue($tablesa, $iszb-1);
			}
		}

		if(isempt($table))return;
		
		$table1		= ''.DB::getTablePrefix().''.$table.'';
		
		//创建表
		if(!Schema:: hasTable($table)){
			if($iszb==0){
				Schema::create($table, function($table){
					$table->increments('id');
					$table->integer('cid')->default(0)->comment('单位company.id');
					$table->integer('uid')->default(0)->comment('平台用户users.id');
					$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
					$table->tinyInteger('status')->default(1)->comment('状态');
					$table->tinyInteger('isturn')->default(0)->comment('是否提交');
					$table->index('cid');
					$table->index('aid');
				});
			}else{
				Schema::create($table, function($table) use($mtable){
					$table->increments('id');
					$table->integer('cid')->default(0)->comment('单位company.id');
					$table->integer('mid')->default(0)->comment('主表上'.$mtable.'.id');
					$table->integer('sort')->default(0)->comment('排序号');
					$table->tinyInteger('sslx')->default(1)->comment('第几个子表');
					$table->index('cid');
					$table->index('mid');
				});
			}
		}
		
		$fieldstype 	= $fieldsr->fieldstype;
		
		
		//字段不存在
		$fields 	= $fieldsr->fields;
		$str 		= $this->getFieldsstr($fieldsr);
		if (!Schema::hasColumn($table, $fields)){
			DB::select('alter table `'.$table1.'` add '.$str.'');
		}else{
			$oldstr = '';
			if($oldfieldrs)$oldstr = $this->getFieldsstr($oldfieldrs);
			if($str != $oldstr)DB::select('alter table `'.$table1.'` MODIFY '.$str.'');
		}
		
		//选择人员子表
		if(substr($fieldstype,0,6)=='change'){
			$data	= $fieldsr->data;
			if(!isempt($data)){
				$fieldsr->fields = $data;
				$fieldsr->name 	 = $fieldsr->name.'ID';
				$fields 	= $fieldsr->fields;
				$str 		= $this->getFieldsstr($fieldsr);
				if (!Schema::hasColumn($table, $fields)){
					DB::select('alter table `'.$table1.'` add '.$str.'');
				}else{
					$oldstr = '';
					if($oldfieldrs){
						$oldfieldrs->fields = $data;
						$oldfieldrs->name 	= $fieldsr->name.'ID';
						$oldstr = $this->getFieldsstr($oldfieldrs);
					}
					if($str != $oldstr)DB::select('alter table `'.$table1.'` MODIFY '.$str.'');
				}
			}
		}
	}
	
	public function getFieldsstr($frs)
	{
		$type= $frs->fieldstype;
		$text= $frs->fieldstext;
		$lengs = $frs->lengs;
		if($lengs<=0)$lengs = 50;
		$isdtbo	= in_array($type, ['date','datetime']);
		
		
		if(isempt($text)){
			$text = 'varchar('.$lengs.')';
			if($isdtbo){
				$text = $type;
			}else if($type=='number'){
				$text = 'int(10)';
			}
		}
		
		if($type=='checkbox')$text = 'tinyint(1)';
		
		$str = '`'.$frs->fields.'` '.$text;
		$dev = $frs->dev;
		//if(substr($dev,0,1)=='{')$dev = '';
		
		//不是日期
		if(!$isdtbo){
			if($type=='number' && $dev==''){
				$str.=" DEFAULT NULL";
			}else if(!in_array($text, ['text','longtext','mediumtext'])){
				$str.=" NOT NULL DEFAULT '$dev'";
			}
		}else{
			$str.= " DEFAULT NULL";
		}
		
		
		$bz  = $frs->name;
		if(!isempt($frs->explain))$bz.=':'.$frs->explain.'';	
		$str.=" COMMENT '$bz'";
	
		return $str;
	}
}
