<?php
/**
*	应用.考勤分析
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqanayTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqanay'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '考勤分析';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('aid')->default(0)->comment('单位用户usera.id');
			
			$table->date('dt')->nullable()->comment('日期');
			$table->string('ztname',20)->default('')->comment('状态名称');
			$table->datetime('time')->nullable()->comment('时间');
			$table->string('state',20)->default('')->comment('状态');
			$table->string('states',20)->default('')->comment('其他状态,如请假外出');
			$table->integer('sort')->default(0)->comment('排序号');
			
			$table->tinyInteger('iswork')->default(0)->comment('是否工作日');
			$table->integer('emiao')->default(0)->comment('迟到/早退/加班秒数');
			
			$table->decimal('timesb',6,1)->default(0)->comment('应上班时间(小时)');
			$table->decimal('timeys',6,1)->default(0)->comment('已上班时间(小时)');
			
			$table->index('cid');
			$table->index(['aid','dt']);

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
