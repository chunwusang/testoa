<?php
/**
*	创建的表-单位下应用表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgenhTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'agenh'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '单位应用表';
			
            $table->increments('id');
		
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('agentid')->default(0)->comment('对应应用表agent.id');
			
            $table->string('name',50)->default('')->comment('应用名称');
			$table->string('pnum',50)->default('')->comment('对应系统应用的菜单分组');
            $table->string('num',30)->comment('应用编号');
			
            $table->string('description')->default('')->comment('应用介绍');
           
            $table->string('face')->default('')->comment('应用图标');
			$table->string('urlm')->default('')->comment('应用移动端地址');
            $table->string('urlpc')->default('')->comment('应用PC端地址');
			
            $table->string('atype',20)->default('')->comment('应用分类');
			$table->string('atypes',20)->default('')->comment('应用子分类');
			$table->string('summarx', 500)->default('')->comment('应用摘要');

			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->tinyInteger('isflow')->default(0)->comment('是否有流程');
			$table->tinyInteger('issy')->default(0)->comment('是否显示首页,5自动');
			$table->tinyInteger('yylx')->default(0)->comment('应用类型0全部,1pc,2手机,5自动');
			
			$table->tinyInteger('mctx')->default(0)->comment('app提醒');
			$table->tinyInteger('wxtx')->default(0)->comment('微信提醒');
			$table->tinyInteger('emtx')->default(0)->comment('邮件提醒');
			$table->tinyInteger('ddtx')->default(0)->comment('钉钉提醒');
			$table->tinyInteger('isgbjl')->default(0)->comment('是否关闭操作记录');
			$table->tinyInteger('isgbcy')->default(0)->comment('是否不显示查阅记录');
			$table->tinyInteger('isflowlx')->default(0)->comment('从新提交时0从新走流程,1原来');
			
			$table->string('usablename', 1000)->default('')->comment('可用对象,为空全部人可用');
			$table->string('usableid', 1000)->default('')->comment('可用对象ID');
			
			$table->string('fields_islu', 1000)->default('')->comment('录入字段');
			$table->string('fields_islb', 1000)->default('')->comment('列表字段');
			$table->string('fields_ispx', 1000)->default('')->comment('排序字段');
			$table->string('fields_isss', 1000)->default('')->comment('搜索字段');
			$table->string('fields_isdr', 1000)->default('')->comment('导入字段');
			
			$table->index('cid');
			$table->index('agentid');
			$table->unique(['cid','num']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tablename);
    }
}
