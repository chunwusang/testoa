<?php
/**
*	应用.客户收付款
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCustfinaTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'custfina'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '客户收付款';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('所属人usera.id');		

			$table->integer('orderid')->default(0)->comment('关联订单');
			$table->string('ordernum',30)->default('')->comment('关联订单号');
			$table->string('custname',50)->default('')->comment('客户名称');
			$table->integer('custid')->default(0)->comment('客户Id');
	
			$table->string('explain',500)->default('')->comment('说明');
			$table->date('applydt')->nullable()->comment('所属日期');

			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
			
			$table->string('createname',20)->default('')->comment('创建人');
			$table->integer('createid')->default(0)->comment('创建人id');
			$table->datetime('createdt')->nullable()->comment('创建时间');
			
			$table->tinyInteger('type')->default(0)->comment('0收款单,1付款单退款');
			$table->tinyInteger('ispay')->default(0)->comment('是否收款,付款');
			$table->datetime('paydt')->nullable()->comment('收付款时间');
	
			$table->tinyInteger('status')->default(0)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
			$table->decimal('money',10,2)->default(0)->comment('金额');
			$table->decimal('ticheng',10,2)->default(0)->comment('提成');
			
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
