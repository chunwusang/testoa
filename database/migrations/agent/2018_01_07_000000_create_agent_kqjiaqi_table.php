<?php
/**
*	应用.假期
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqjiaqiTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqjiaqi'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '假期信息';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('假期人idusera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
		
			$table->string('applyname',20)->default('')->comment('假期人');
			$table->string('jiatype',20)->default('')->comment('假期类型');
			
			$table->datetime('startdt')->nullable()->comment('有效开始时间');
			$table->datetime('enddt')->nullable()->comment('有效截止时间，为空长期');
			
			$table->decimal('totals',6,1)->default(0)->comment('假期时间(时)');
			$table->decimal('totday',8,2)->default(0)->comment('假期时间(天)');
			
			$table->string('explain',500)->default('')->comment('说明');
			
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
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
