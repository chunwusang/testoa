<?php
/**
*	应用.通知-投票项
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentNoticesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'notices'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '通知表-投票项';

			$table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('mid')->default(0)->comment('主表上notice.id');
			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('sslx')->default(1)->comment('第几个子表');
			$table->string('touitems',100)->default('')->comment('投票项');
			$table->string('tousm',100)->default('')->comment('说明');
			$table->integer('touci')->default(0)->comment('得到票数');
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
