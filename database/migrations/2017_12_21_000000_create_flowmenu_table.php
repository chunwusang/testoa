<?php
/**
*	创建的表-单据操作菜单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowmenuTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowmenu'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '单据操作菜单';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位Id');
			$table->integer('agentid')->default(0)->comment('对应应用agent.id');
			$table->integer('uid')->default(0)->comment('创建用户ID');
			
            $table->string('name',50)->default('')->comment('操作菜单显示名称');
            $table->string('actname',50)->default('')->comment('动作名称');
            $table->string('num',50)->default('')->comment('编号');
            $table->string('statusname',50)->default('')->comment('状态名称');
            $table->string('statuscolor',50)->default('')->comment('状态颜色');
            $table->integer('statusvalue')->default(0)->comment('状态值保存flowlog');
			$table->integer('type')->default(0)->comment('菜单类型');
			$table->string('wherestr',500)->default('')->comment('过滤SQL条件');
			
            $table->string('explain',500)->default('')->comment('说明');
            $table->string('upgcont',500)->default('')->comment('更新内容');
			$table->integer('sort')->default(0)->comment('排序号');
			
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('islog')->default(1)->comment('写入日志');
			$table->tinyInteger('issm')->default(1)->comment('必须写说明');
			$table->tinyInteger('iszs')->default(1)->comment('详情页展示');
			$table->tinyInteger('isup')->default(0)->comment('是否上传文件');
			
			$table->index('agentid');
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
