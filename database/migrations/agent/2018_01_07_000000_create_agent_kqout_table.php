<?php
/**
*	应用.外出出差
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqoutTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqout'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '外出出差';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('假期人idusera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			$table->string('applyname',20)->default('')->comment('申请人');
			$table->string('atype',20)->default('')->comment('外出类型@外出,出差');

			$table->datetime('startdt')->nullable()->comment('外出时间');
			$table->datetime('enddt')->nullable()->comment('回岗时间');
			
			$table->string('outname',200)->default('')->comment('外出人员');
			$table->string('outnaid',200)->default('')->comment('外出人员ID');
			
			$table->string('address',50)->default('')->comment('外出地点');
			$table->string('reason',500)->default('')->comment('外出事由');
			
			$table->string('explain',500)->default('')->comment('说明');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			
			$table->decimal('totday',8,2)->default(0)->comment('外出天数');
		
			$table->index('cid');
		
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
