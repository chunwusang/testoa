<?php
/**
*	应用.请假条子表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqleavsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqleavs'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '请假时间';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('mid')->default(0)->comment('主表上kqlevea.id');
			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('sslx')->default(1)->comment('第几个子表');
			$table->integer('aid')->default(0)->comment('用户usera.id');
			$table->tinyInteger('status')->default(0)->comment('状态');
			
			$table->string('qjtype',20)->default('')->comment('请假类型');
			$table->datetime('stime')->nullable()->comment('请假开始时间');
			$table->datetime('etime')->nullable()->comment('请假截止时间');
			$table->decimal('totals',6,1)->default(0)->comment('时间');
			$table->decimal('totday',8,2)->default(0)->comment('请假天数');
 
			$table->index('cid');
			$table->index('mid');
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
