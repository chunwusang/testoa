<?php
/**
*	应用.日报
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentDailyTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'daily'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '日报';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
            $table->date('dt')->nullable()->comment('日期');
            $table->tinyInteger('type')->default(0)->comment('类型@0|日报,1|周报,2|月报,3|年报');
			$table->string('content',4000)->default('')->comment('日报内容');
            $table->string('plan',2000)->default('')->comment('明日计划');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->datetime('createdt')->nullable()->comment('创建时间');
 
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
