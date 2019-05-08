<?php
/**
*	创建的表-流程单据表日志
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowlogTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowlog'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '流程单据表日志表';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('单位下用户usera.id');
			$table->string('checkname',20)->default('')->comment('处理人');
			
			$table->string('actname',30)->default('')->comment('动作名称');
			$table->string('agenhnum',30)->default('')->comment('应用agenh的编号');
		
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			
			$table->integer('courseid')->default(0)->comment('步骤进程IDflowcourse.id');
			$table->tinyInteger('status')->default(0)->comment('状态');
			$table->string('statusname',50)->default('')->comment('状态名称');
			
			$table->string('ip',50)->default('')->comment('IP');
			$table->string('web',50)->default('')->comment('浏览器');
			$table->string('color',20)->default('')->comment('状态的颜色');
			
			$table->string('explain',500)->default('')->comment('说明');
			$table->datetime('optdt')->comment('操作时间');
			$table->string('qmimg',200)->default('')->comment('签名图片');
			$table->string('fileid',200)->default('')->comment('相关文件Id');
			
			$table->index(['mtable','mid']);
			$table->index('cid');
			$table->index('aid');
			$table->index('agenhnum');
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
