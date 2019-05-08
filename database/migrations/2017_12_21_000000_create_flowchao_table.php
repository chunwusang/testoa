<?php
/**
*	创建的表-流程抄送表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowchaoTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowchao'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '流程抄送表';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('单位下用户usera.id');
			
			$table->integer('billid')->default(0)->comment('单据flowbill.id');
			$table->integer('agentid')->default(0)->comment('主应用agent.id');
			
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			
			$table->string('chaoname',1000)->default('')->comment('抄送给');
			$table->string('chaoid',1000)->default('')->comment('抄送给ID');
			
			$table->datetime('optdt')->comment('操作时间');
			$table->unique(['mtable','mid']);
			$table->index('cid');
			$table->index('aid');
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
