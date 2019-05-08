<?php
/**
*	创建的表-token
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'token'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '用户登录Token';
			
            $table->increments('id');
            $table->integer('uid')->default(0)->comment('用户Id');
            $table->string('token',10)->unique()->comment('token');
            $table->string('useragent',50)->comment('绑定信息');
            $table->string('cfrom',20)->comment('来源');
			$table->string('ip',30)->default('')->comment('ip');
			$table->string('web',30)->default('')->comment('浏览器');
			$table->string('device',50)->default('')->comment('驱动');
			$table->tinyInteger('online')->default(1)->comment('状态0离线,1在线');
			$table->index('uid');
            $table->timestamps();
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
