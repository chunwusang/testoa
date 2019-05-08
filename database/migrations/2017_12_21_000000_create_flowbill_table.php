<?php
/**
*	创建的表-流程单据表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowbillTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowbill'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '流程单据表';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('单位下用户usera.id');
			
			$table->string('applyname',20)->default('')->comment('申请人');
			$table->string('applydeptname',50)->default('')->comment('申请人部门');
			$table->integer('applydeptid')->default(0)->comment('申请人部门ID');
			
			$table->string('sericnum',50)->default('')->comment('单据编号单号');
			$table->string('agenhnum',30)->default('')->comment('应用agenh的编号');
			$table->string('agenhname',30)->default('')->comment('应用agenh的名称');
			$table->integer('agentid')->default(0)->comment('主应用agent.id');
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			
			$table->tinyInteger('status')->default(0)->comment('状态跟主表status对应');
			$table->tinyInteger('nstatus')->default(0)->comment('当前状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			$table->date('applydt')->comment('申请日期');
			
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人Id');
			
			$table->string('allcheckid', 500)->default('')->comment('所有审核人');
			$table->string('nowcheckname', 500)->default('')->comment('当前审核人');
			$table->string('nowcheckid', 500)->default('')->comment('当前审核人id');
			$table->string('nowstatus', 500)->default('')->comment(' 当前状态');

			$table->unique(['mtable','mid']);
			$table->index('cid');
			$table->index('aid');
			$table->index('agentid');
			$table->timestamps();
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
