<?php
/**
*	短信记录表，可以独立出来的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2015-05-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasesmsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'basesms'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '短信记录表';
            $table->increments('id');
			
            $table->string('mobile',2000)->default('')->comment('手机号');
            $table->string('cont',200)->default('')->comment('短信内容');
            $table->string('code',20)->nullable()->comment('验证码');
			$table->datetime('optdt')->comment('添加时间');
			$table->string('ip',50)->default('')->comment('ip');
			$table->string('device',100)->nullable()->comment('来源渠道');
			$table->string('web',30)->nullable()->comment('浏览器类型');
			$table->tinyInteger('status')->default(0)->comment('是否使用');
           
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
