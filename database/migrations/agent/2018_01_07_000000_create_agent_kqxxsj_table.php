<?php
/**
*	应用.休息时间规则
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqxxsjTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqxxsj'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '休息时间规则';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('pid')->default(0)->comment('对应上级id');
			$table->string('name',50)->default('')->comment('规则名称');
			$table->date('dt')->nullable()->comment('对应日期');
		
			$table->index('cid');
			$table->index('pid');
			$table->index('dt');
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
