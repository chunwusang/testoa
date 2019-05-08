<?php
/**
*	创建的表-系统基本提醒表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodoTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'todo'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '提醒消息';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');	
			$table->integer('aid')->default(0)->comment('单位下用户ID');
            $table->string('typename',20)->default('')->comment('类型');
            $table->string('title',50)->default('')->comment('提醒类型标题');
            $table->string('mess',500)->default('')->comment('信息内容');
			$table->tinyInteger('status')->default(1)->comment('状态@0|未读,1|已读');
			
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->string('agenhnum',50)->default('')->comment('对应应用编号');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			
			$table->datetime('optdt')->nullable()->comment('添加时间');
			$table->datetime('tododt')->nullable()->comment('提醒时间');
			$table->datetime('readdt')->nullable()->comment('已读时间');
			$table->string('optname',20)->default('')->comment('发送人');
			
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
