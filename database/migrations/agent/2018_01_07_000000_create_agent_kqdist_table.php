<?php
/**
*	应用.考勤相关分配
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-24
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqdistTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqdist'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '考勤相关分配';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('mid')->default(0)->comment('对应规则主id');
			
			$table->string('receid',500)->default('')->comment('适用对象ID');
			$table->string('recename',500)->default('')->comment('适用对象');
			$table->tinyInteger('type')->default(0)->comment('0考勤时间,1休息,2定位的');
			
			$table->date('startdt')->nullable()->comment('开始日期');
			$table->date('enddt')->nullable()->comment('截止日期');
			
			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('status')->default(1)->comment('状态');
 
			$table->index('cid');
			$table->index('mid');
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
