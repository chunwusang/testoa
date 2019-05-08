<?php
/**
*	应用.单据提醒设置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemindTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'remind'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '单据提醒设置';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			$table->datetime('startdt')->nullable()->comment('开始时间');
			$table->datetime('enddt')->nullable()->comment('截止时间');
            
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
 
			$table->integer('agenhid')->default(0)->comment('应用agenh.id');
			$table->string('agenhname',50)->default('')->comment('应用名');
			
			$table->string('todotit',100)->default('')->comment('提醒标题');
			$table->string('todocont',500)->default('')->comment('提醒内容');
			
			$table->string('ratecont',500)->default('')->comment('提醒频率');
			$table->string('rate',100)->default('')->comment('提醒频率o仅一次,d天,w周,m月');
			$table->string('rateval',500)->default('')->comment('对应提醒');
			
			$table->string('receid',200)->default('')->comment('提醒给');
			$table->string('recename',200)->default('')->comment('提醒给');
			
			$table->string('explain',500)->default('')->comment('说明');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->datetime('lastdt')->nullable()->comment('最后提醒时间');
 
			$table->index('cid');
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
