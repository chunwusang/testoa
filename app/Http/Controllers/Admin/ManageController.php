<?php
/**
*	平台管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-06
*/

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Base\AdminModel;
use App\Model\Base\LogModel;
use App\Model\Base\TaskModel;
use App\Model\Base\RockqueueModel;
use Auth;
use App\RainRock\Systems\Databeifen;

class ManageController extends AdminController
{
  

    /**
	*	操作
    */
    public function index($act, Request $request)
    {
    	// $bbb = 2222;
    	// return $bbb;
		$actt 	= 'showForm_'.$act.'';
		$actt1 	= 'getAction_'.$act.'';
		if(method_exists($this, $actt1))return $this->$actt1($request);
		
		$barr 	= $this->$actt();
		$barr['pagetitle']	= trans('admin/public.menu.plat'.$act.'');
		$barr['tpl']		= 'plat'.$act.'';
		$barr['kzq']		= 'Admin/Manage';
		$barr['lang']		= 'admin/platcog';

		return $this->getShowView('admin/manage'.$act.'', $barr);
    }
	
	//设置
	private function showForm_cog()
	{
		return [];
	}
	

	private function showForm_admin()
	{
		$data	= AdminModel::get();
		
		return [
			'data' 		=> $data,
			'mtable' 	=> c('rockjm')->encrypt('admin'),
			'stylearr'	=> c('bootstyle')->getStylearr()
		];
	}
	
	private function showForm_log()
	{
		$this->getLimit();
		$obj 	= new LogModel();
		$total 	= $obj->count();
		$data 	= $obj->orderBy('id', 'desc')->simplePaginate($this->limit)->getCollection();
		
		return [
			'data' 	=> $data,
			'pager'		=> $this->getPager('adminmanage', $total, [], 'log')
		];
	}
	
	private function showForm_task()
	{
		$data	= TaskModel::select()->orderBy('sort')->get();
		return [
			'data' 		=> $data,
			'mtable' 	=> c('rockjm')->encrypt('task')
		];
	}
	private function showForm_queue()
	{
		$this->getLimit();
		$obj 	= new RockqueueModel();
		$total 	= $obj->count();
		$data 	= $obj->orderBy('id', 'desc')->simplePaginate($this->limit)->getCollection();
		
		return [
			'data' 	=> $data,
			'pager'		=> $this->getPager('adminmanage', $total, [], 'queue')
		];
	}
	private function showForm_upgdefile()
	{
	
		return [
			
		];
	}
	
	private function getAction_addtask()
	{
		$data = new TaskModel();
		$data->name 	= 'newadd';
		$data->save();
		return 'ok';
	}
	private function saveData_deltask(Request $request)
	{
		$id = $request->input('id');
		TaskModel::find($id)->delete();
	}
	private function saveData_delqueue(Request $request)
	{
		$id = $request->input('id');
		RockqueueModel::find($id)->delete();
	}
	private function getAction_taskstart()
	{
		return '请查看帮助，看如何启动';
	}
	private function getAction_taskclear()
	{
		\DB::table('task')->update([
			'state' => 0,
			'lastdt' => null,
			'lastcont' => ''
		]);
		return '已清空任务状态';
	}
	private function getAction_taskbeifen()
	{
		$msg = Databeifen::beifen('task');
		if($msg=='')$msg = '已备份';
		return $msg;
	}
	private function getAction_taskdaoru()
	{
		$bo = Databeifen::insert('task');
		return '导入了'.$bo.'条';
	}
	
	private function getAction_taskrun(Request $request)
	{
		$taskid = $request->get('id');
		$msg	= c('Task:start')->run($taskid);
		if($msg=='success'){
			$msg = '运行成功';
		}else{
			$msg = 'error:'.$msg.'';
		}
		return $msg;
	}
	
	private function getAction_queueclear()
	{
		RockqueueModel::where('id','>', 0)->delete();
		return '已清空队列';
	}
	
	private function getAction_queuerun(Request $request)
	{
		$queid = $request->get('id');
		$msg	= c('Queue:start')->run($queid);
		return $msg;
	}
	
	private function getAction_taskhelp()
	{
		$ljth  = str_replace('/','\\',base_path());
		$ljth1 = str_replace('\\','/',base_path());
		
		echo '<title>'.config('app.name').'计划任务开启方法</title>';
	
		echo '<font color="red">php的路径必须加入环境变量中哦</font><br><a target="_blank" style="color:blue" href="'.config('rock.urly').'view_taskrun.html">查看官网上帮助</a><br><br>';

		
		echo '一、<b>初始化</b>，在服务器上操作，进入程序的根目录下，输入命令<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo 'php '.$ljth1.'/artisan rock:taskinit';
		echo '</div><br>';
		
		echo '二、<b>Windows服务器</b><br>';
		echo '1、在win服务器上，开始菜单→运行 输入 cmd 回车(管理员身份运行)，输入以下命令(每5分钟运行一次)：<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo 'schtasks /create /sc DAILY /mo 1 /du "24:00" /ri 5 /sd "2017/01/17" /st "00:00:05"  /tn "'.config('app.name').'计划任务" /ru System /tr '.$ljth.'\storage\app\rocktaskrun.bat';
		echo '</div>';
	
		echo '<br>三、<b>Linux服务器</b><br>';
		echo '根据以下命令设置运行，使用crontab：<br>';
		echo '<div style="background:#caeccb;padding:5px;border:1px #888888 solid;border-radius:5px;">';
		echo '<font color=blue>crontab</font> -e<br>';
		echo '*/5 * * * * php '.$ljth1.'/artisan rock:taskrun >> /dev/null 2>&1</div>';
		
		
		return;
	}
	
	
	private function getAction_queuechange()
	{
		$barr 	= \Rock::qpush('start','test','','系统','检测服务测试');
		if(!$barr['success'])return $barr['msg'];
		sleep(2);
		$bobj	= $barr['data'];
		$id 	= $bobj->id;
		$farr	= RockqueueModel::find($id);
		if($farr->status>0)return '队列服务正常运行中';
		return '<font color=red>队列服务没有启动，请查看帮助去开启</font>';
	}
	
	private function showForm_upgde()
	{
		return [];
	}
	
	//添加管理
	private function getAction_addadmin()
	{
		$texur = 'test@rockoa.com';
		if(AdminModel::where('email', $texur)->count()>0)return '已添加不要重复在添加';
		
		$data = new AdminModel();
		$data->email 	= $texur;
		$data->name 	= 'newadd';
		$data->password = '123456';
		$data->save();
		return 'ok';
	}
	private function saveData_deladmin(Request $request)
	{
		$id = $request->input('id');
		if($id==Auth::user()->id)
			return $this->returnerror('不能删除自己');
		AdminModel::find($id)->delete();
	}
	
	//清空日志
	private function getAction_clearlog()
	{
		LogModel::truncate();
	}
	
	
	public function saveData($act, Request $request)
	{
		$actt 	= 'saveData_'.$act.'';
		return $this->$actt($request);
	}
	private function saveData_cog(Request $request)
	{
		$satst= 'APP_NAME,APP_NAMEADMIN,APP_URL,APP_URLLOCAL,APP_LOGO,APP_DEBUG,APP_OPENREG,ROCK_SMSPROVIDER,APP_ENV,ROCK_BASEURL,ROCK_RANDKEY,ROCK_URLY,ROCK_XINHUKEY,ROCK_XINHU,ROCK_BASEKEY';
		$savea= explode(',', $satst);
		$path = base_path('.env');
		$cont = file_get_contents($path);
		
		$arr  = array();
		foreach($savea as $v1)$arr[$v1] = $request->input($v1);
		if($arr['APP_DEBUG']=='true')$arr['APP_ENV'] = 'local'; //debug时本地
		
		$envarr = explode("\n", $cont);
		$sstr 	= '';
		foreach($envarr as $env){
			if(isempt($env))continue;
			$bo 	= '';
			$envs 	= str_replace(' ', '', $env);
			$envsa 	= explode('=', $envs);
			foreach($savea as $v1){
				if($v1==$envsa[0]){
					$bo = $v1;
					break;
				}
			}
			if($bo!=''){
				if($arr[$bo]!=null){
					$sstr .= "".$bo."=".$arr[$bo]."\n";
				}
				unset($arr[$bo]);
			}else{
				$sstr .= "".$env."\n";
			}
		}
		
		foreach($arr as $k=>$v){
			if(!isempt($v)){
				$sstr .= "".$k."=".$v."\n";
			}
		}
		
		@$bo 	= file_put_contents($path, $sstr);
		if(!$bo)return $this->returnerror('无权限修改配置.env文件');
	}
}
