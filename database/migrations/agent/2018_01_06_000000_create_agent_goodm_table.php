<?php
/**
*	应用.物品申请单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGoodmTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'goodm'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '物品申请单';
			$table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
			$table->tinyInteger('type')->default(0)->comment('0领用,1采购,2调拨,3归返,4销售,5生产');
			$table->string('applyname',20)->default('')->comment('申请人');
			
			$table->string('optname',20)->default('')->comment('操作人');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->integer('optid')->default(0)->comment('操作人Id');
			$table->datetime('createdt')->nullable()->comment('创建时间');
			$table->date('applydt')->nullable()->comment('申请日期');
			
			$table->decimal('money',10,2)->default(0)->comment('总额');
			$table->decimal('discount',10,2)->default(0)->comment('优惠价格');
			$table->tinyInteger('state')->default(0)->comment('0待出入库,2部分出入库,1已全部出入库');
			$table->string('explain',500)->default('')->comment('说明');
			
			$table->string('custname',100)->default('')->comment('供应商名称');
			$table->integer('custid')->default(0)->comment('供应商customer.id');
			$table->integer('mainid')->default(0)->comment('关联主Id');
			$table->string('mainname')->default('')->comment('关联名称');
 
			$table->index('cid');
			$table->index('aid');
			$table->index('type');
			
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
