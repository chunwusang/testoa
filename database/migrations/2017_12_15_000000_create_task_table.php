<?php
/**
*	系统计划任务/定时任务
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	private $tablename = 'task'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '系统计划任务';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
            $table->string('name',50)->default('')->comment('名称');
            $table->string('fenlei',50)->default('')->comment('分类');
            $table->string('url',200)->default('')->comment('运行地址');
			
            $table->string('type',50)->default('')->comment('运行类型d每天,h,m等');
            $table->string('time',100)->default('')->comment('运行时间');
            $table->string('ratecont',100)->default('')->comment('运行说明');
			
			$table->integer('sort')->default(0)->comment('排序号越大越靠后');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->tinyInteger('state')->default(0)->comment('运行结果');
			$table->datetime('lastdt')->nullable()->comment('最后运行时间');
			$table->string('lastcont',500)->default('')->comment('最后运行返回内容');
			$table->string('explain',100)->default('')->comment('说明');
            
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
