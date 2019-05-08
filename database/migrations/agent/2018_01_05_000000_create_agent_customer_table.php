<?php
/**
*	应用.客户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-10
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCustomerTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'customer'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '客户';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('所属人usera.id');
			
			$table->string('name',50)->default('')->comment('客户名称');
			$table->string('type',20)->default('')->comment('客户类型');
			
			$table->string('jibie',20)->default('')->comment('客户级别');
			
			$table->string('linkname',20)->default('')->comment('联系人');
			$table->string('unitname',100)->default('')->comment('单位名称');
			$table->string('laiyuan',20)->default('')->comment('来源');
			$table->string('tel',50)->default('')->comment('电话');
			$table->string('mobile',50)->default('')->comment('手机号');
			$table->string('wxhao',50)->default('')->comment('微信号');
			$table->string('qqhao',50)->default('')->comment('QQ号');
			$table->string('email',100)->default('')->comment('邮箱');
			$table->string('explain',200)->default('')->comment('说明');
			$table->string('sheng',50)->default('')->comment('省');
			$table->string('shi',50)->default('')->comment('市');
			$table->string('xian',50)->default('')->comment('县');
			$table->string('address',200)->default('')->comment('地址');
			$table->string('routeline',100)->default('')->comment('行走路线');
			
			$table->string('createname',20)->default('')->comment('创建人');
			$table->integer('createid')->default(0)->comment('创建人id');
			$table->datetime('createdt')->nullable()->comment('创建时间');
		
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
			
			$table->tinyInteger('status')->default(0)->comment('0存库,1成交,2丢失,3跟进');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			$table->tinyInteger('isgys')->default(0)->comment('是否供应商');
			$table->tinyInteger('isgh')->default(0)->comment('放入公海');
			
			$table->string('shateid',200)->default('')->comment('共享给');
			$table->string('shatename',200)->default('')->comment('共享给');
			
			$table->datetime('lastdt')->nullable()->comment('最后跟进时间');
			
			$table->integer('htshu')->default(0)->comment('合同数');
			$table->integer('ddshu')->default(0)->comment('订单数');
			$table->decimal('moneyz',12,2)->default(0)->comment('销售总额');
			$table->decimal('moneyd',12,2)->default(0)->comment('待收金额');
			$table->decimal('moneyg',12,2)->default(0)->comment('供应总金额');
			
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
