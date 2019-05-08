<?php
/**
*	创建的表-单据通知设置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-12-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgenttodoTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'agenttodo'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '单据通知设置';
			
            $table->increments('id');  
			$table->integer('cid')->default(0)->comment('单位Id');
			$table->integer('agentid')->default(0)->comment('对应应用表agent.id');
			
			$table->string('name',100)->default('')->comment('名称');
			$table->string('num',50)->default('')->comment('编号');
			$table->string('recename', 1000)->default('')->comment('通知给');
			$table->string('receid', 1000)->default('')->comment('通知给ID');
			
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			
			$table->tinyInteger('boturn')->default(0)->comment('提交时');
			$table->tinyInteger('boedit')->default(0)->comment('编辑时');
			$table->tinyInteger('bodel')->default(0)->comment('删除时');
			$table->tinyInteger('bozuofei')->default(0)->comment('作废时');
			$table->tinyInteger('boping')->default(0)->comment('作废时');
			$table->tinyInteger('botong')->default(0)->comment('步骤处理通过时');
			$table->tinyInteger('bobutong')->default(0)->comment('步骤处理不通过时');
			$table->tinyInteger('bofinish')->default(0)->comment('处理完成时');
			$table->tinyInteger('botask')->default(0)->comment('定时执行');
			$table->string('tasktype',10)->default('')->comment('定时频率');
			$table->string('tasktime',30)->default('')->comment('定时时间');
			
			$table->tinyInteger('toturn')->default(0)->comment('是否通知给提交人optid');
			$table->tinyInteger('toapply')->default(0)->comment('是否通知给申请人aid');
			$table->tinyInteger('tocourse')->default(0)->comment('是否通知给流程所有参与人');
			$table->tinyInteger('tosuper')->default(0)->comment('是否通知给直属上级');
			$table->tinyInteger('tosuperall')->default(0)->comment('是否通知给全部的上级');
			$table->string('todofields',500)->default('')->comment('通知给主表上字段');
			
			$table->string('wherestr',500)->default('')->comment('通知条件');
			
			$table->string('summary',200)->default('')->comment('通知内容摘要');
			$table->string('explain',200)->default('')->comment('说明');
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
