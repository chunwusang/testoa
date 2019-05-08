<?php
/**
*	应用.考勤时间规则
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-24
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqsjgzTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqsjgz'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '考勤时间规则';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('pid')->default(0)->comment('对应上级id');
			$table->string('name',20)->default('')->comment('规则名称');
			$table->integer('sort')->default(0)->comment('排序号');
			
			$table->string('stime',20)->default('')->comment('开始时间');
			$table->string('etime',20)->default('')->comment('截止时间');
			
			$table->tinyInteger('qtype')->default(0)->comment('取值类型@0|最小值,1|最大值');
			$table->tinyInteger('iskq')->default(1)->comment('需考勤?');
			$table->tinyInteger('isxx')->default(0)->comment('非工作时间段?');
			$table->tinyInteger('iskt')->default(0)->comment('跨天类型0不跨天,1结束时间+1天,2开始时间-1天');
		

			$table->index('cid');
			$table->index('pid');
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
