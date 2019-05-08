<?php
/**
*	创建的表-流程步骤记录
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowcourssTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowcourss'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '模块流程步骤记录';
			
            $table->increments('id');
		
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('agenhid')->default(0)->comment('对应应用表agenh.id');
			
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			
			$table->integer('courseid')->default(0)->comment('步骤flowcourse.id');
			$table->tinyInteger('step')->default(0)->comment('当前步骤');
			$table->tinyInteger('valid')->default(1)->comment('是否有效');
			
			$table->string('checkname', 20)->default('')->comment('当前审核人');
			$table->integer('checkid')->default(0)->comment('当前审核人id');
			$table->tinyInteger('checkstatus')->default(0)->comment('审核状态0待审核,1通过,2未通过');
			$table->string('checksm', 500)->default('')->comment('审核说明');
			$table->string('checkstatustext', 30)->default('')->comment('审核状态文字');
			$table->string('checkstatuscolor', 20)->default('')->comment('审核状态颜色');
			$table->string('checkqmimg',200)->default('')->comment('签名图片');
			$table->datetime('checkdate')->nullable()->comment('审核时间');
			
            $table->index('cid');
			$table->index('agenhid');
			$table->index('courseid');
			$table->index(['mtable','mid']);
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
