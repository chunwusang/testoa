<?php
/**
*	流程审核人表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowchecksTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowchecks'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '流程审核人员表';
			
            $table->increments('id');
		
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('agenhid')->default(0)->comment('对应应用表agenh.id');
			
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			
			$table->integer('courseid')->default(0)->comment('步骤flowcourse.id');
			
			$table->string('checkname', 100)->default('')->comment('当前审核人');
			$table->string('checkid',100)->default('')->comment('当前审核人id');
			
			$table->integer('optid')->default(0)->comment('操作人id');
			$table->string('optname', 20)->default('')->comment('操作人');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			
			$table->tinyInteger('addlx')->default(0)->comment('添加类型0上步指定');
			
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
