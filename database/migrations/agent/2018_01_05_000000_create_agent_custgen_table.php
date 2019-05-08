<?php
/**
*	应用.客户跟进
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-10
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCustgenTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'custgen'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '客户跟进';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('所属人usera.id');
			$table->tinyInteger('status')->default(0)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');

			$table->string('custname',50)->default('')->comment('客户名称');
			$table->integer('custid')->default(0)->comment('客户Id');
			$table->string('gentype',20)->default('')->comment('跟进方式');
			
			$table->tinyInteger('state')->default(0)->comment('跟进状态0计划,1完成,2取消');
			$table->datetime('plandt')->nullable()->comment('计划时间');
			$table->string('explain',500)->default('')->comment('说明');
			
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
	
			$table->index('cid');
			$table->index('custid');
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
