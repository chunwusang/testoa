<?php
/**
*	创建的表-系统基本已读记录
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-16
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowreadTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowread'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '单据查阅记录';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			
			$table->integer('aid')->default(0)->comment('单位下用户ID');
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			$table->integer('billid')->default(0)->comment('单据flowbill.id');
			$table->integer('agentid')->default(0)->comment('主应用agent.id');
			
			$table->datetime('optdt')->comment('添加时间');
			$table->datetime('adddt')->comment('第一次浏览时间');
			$table->integer('stotal')->default(1)->comment('总浏览次数');
			
			$table->index('cid');
			$table->index('aid');
			$table->index('agentid');
			$table->index(['mtable','mid']);
			$table->unique(['aid','mtable','mid']);
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
