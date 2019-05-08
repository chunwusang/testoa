<?php
/**
*	rock队列服务表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRockqueueTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'rockqueue'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = 'rock队列服务表';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('aid')->default(0)->comment('单位用户id');
            $table->string('atype',20)->default('')->comment('类型');
            $table->string('title',50)->default('')->comment('标题');
            $table->string('url',100)->default('')->comment('运行地址');
            $table->string('params',4000)->default('')->comment('运行参数');
			$table->tinyInteger('status')->default(0)->comment('状态@0等运行,1成功,2失败');
			$table->datetime('rundt')->nullable()->comment('需运行时间');
			$table->datetime('optdt')->nullable()->comment('添加时间');
			$table->string('runcont',4000)->default('')->comment('运行结果');
			$table->datetime('lastdt')->nullable()->comment('运行时间');
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
