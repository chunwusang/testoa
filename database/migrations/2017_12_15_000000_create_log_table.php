<?php
/**
*	日志表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	private $tablename = 'log'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '平台系统日志表';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
            $table->string('ltype',50)->default('')->comment('类型');
            $table->string('optname',50)->default('')->comment('操作人');
            $table->string('remark',500)->default('')->comment('操作人');
            $table->datetime('optdt')->nullable()->comment('添加时间');
            $table->string('ip',30)->default('')->comment('IP');
            $table->string('web',30)->default('')->comment('浏览器');
            $table->string('url',500)->default('')->comment('相关地址');
			$table->tinyInteger('level')->default(0)->comment('日志级别0普通,1提示,2错误');
			$table->index('cid');
			$table->index('ltype');
			$table->index('level');
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
