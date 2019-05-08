<?php
/**
*	创建的表-平台用户表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	
	private $tablename = 'users'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '平台用户表';
			
            $table->increments('id');
            $table->string('name', 50)->comment('姓名');
			$table->string('userid',50)->default('')->comment('用户名/帐号');
			$table->string('nameen',50)->default('')->comment('英文名');
            $table->string('nickname',50)->default('')->comment('昵称');
            $table->string('mobile',50)->default('')->comment('手机号');
            $table->string('mobilecode',10)->default('')->comment('手机号区号，默认+86');
            $table->string('email',100)->default('')->comment('个人邮箱');
            $table->string('password')->comment('登录密码');
            $table->string('face',200)->default('')->comment('头像');
            $table->string('bootstyle',100)->default('')->comment('后台样式地址');
			$table->integer('flaskm')->default(0)->comment('可创建单位数');
			$table->integer('flasks')->default(0)->comment('已创建单位数');
			
			$table->integer('devcid')->default(0)->comment('默认单位ID');
			
			$table->rememberToken();
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->tinyInteger('online')->default(0)->comment('REIM在线状态');
			$table->datetime('onlinedt')->nullable()->comment('最后在线时间');
			
			$table->unique(['mobile','mobilecode']);
			$table->index('userid');
			$table->index('email');
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
