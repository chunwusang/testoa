<?php
/**
*	应用.客户合同
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCustractTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'custract'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '客户合同';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('所属人usera.id');
			
			$table->string('htname',50)->default('')->comment('合同名称');
			$table->integer('ractid')->default(0)->comment('关联合同如续签');
			$table->integer('orderid')->default(0)->comment('关联订单');
			$table->string('ordernum',30)->default('')->comment('关联订单号');
			$table->string('custname',50)->default('')->comment('客户名称');
			$table->integer('custid')->default(0)->comment('客户Id');
	
			$table->string('explain',500)->default('')->comment('说明');
			$table->date('applydt')->nullable()->comment('签约日期');
			$table->date('startdt')->nullable()->comment('生效日期');
			$table->date('enddt')->nullable()->comment('截止日期');
	
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
			
			$table->string('createname',20)->default('')->comment('创建人');
			$table->integer('createid')->default(0)->comment('创建人id');
			$table->datetime('createdt')->nullable()->comment('创建时间');
			$table->tinyInteger('type')->default(0)->comment('0收款合同，1付款合同');
	
			$table->tinyInteger('status')->default(0)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
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
