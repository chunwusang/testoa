<?php
/**
*	创建的表-任务
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentWorkTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'work'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '任务';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
            $table->string('applyname',50)->comment('发起人');
            $table->string('title',200)->comment('标题');
            $table->string('type',20)->comment('任务类型');
            $table->string('grade',20)->comment('任务等级');
			
			$table->string('content',1000)->default('')->comment('内容');
			
			$table->datetime('startdt')->nullable()->comment('开始时间');
			$table->datetime('enddt')->nullable()->comment('截止时间');
			
			$table->string('distid',200)->default('')->comment('分配给');
			$table->string('dist',200)->default('')->comment('分配给');
	
            $table->integer('projectid')->default(0)->comment('关联项目ID');
            $table->integer('custid')->default(0)->comment('关联客户ID');
			
			$table->string('ddid',200)->default('')->comment('督导人员ID');
			$table->string('ddname',200)->default('')->comment('督导人员');
			
			$table->datetime('optdt')->nullable()->comment('操作时间');
			
			$table->index('cid');
			$table->index('aid');
           
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
