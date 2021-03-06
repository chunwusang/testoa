<?php
/**
*	创建的表-日程
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentScheduleTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'schedule'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '日程';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
            $table->string('title',50)->comment('标题');
			$table->string('content',1000)->default('')->comment('内容');
			
			$table->datetime('startdt')->nullable()->comment('开始时间');
			$table->datetime('enddt')->nullable()->comment('截止时间');
			
			$table->string('receid',200)->default('')->comment('日程安排给,为空就我自己');
			$table->string('recename',200)->default('')->comment('对应人ID');
			$table->datetime('optdt')->nullable()->comment('操作时间');
            
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
