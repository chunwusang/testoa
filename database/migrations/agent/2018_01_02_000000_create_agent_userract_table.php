<?php
/**
*	应用.员工合同
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentUserractTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'userract'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '员工合同';

			$table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
			$table->string('htname',50)->default('')->comment('合同名称');
			$table->string('uname',50)->default('')->comment('签署人');
			
			$table->date('startdt')->nullable()->comment('开始日期');
			$table->date('enddt')->nullable()->comment('截止日期');
			$table->date('tqenddt')->nullable()->comment('提前截止');
			$table->string('httype',50)->default('')->comment('合同类型');
			$table->string('company',50)->default('')->comment('签署单位');
			
			$table->string('explain',500)->default('')->comment('说明');
			$table->tinyInteger('state')->default(0)->comment('0|待执行,1|生效中,2|已终止,3|已过期');
			
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->integer('optid')->default(0)->comment('操作人Id');
			$table->string('optname',20)->default('')->comment('操作人');
			
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
