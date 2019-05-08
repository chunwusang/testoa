<?php
/**
*	创建的表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	private $tablename = 'admin'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '后台管理员表';
            $table->increments('id');
            $table->string('name',20)->default('')->comment('姓名');
            $table->string('user', 50)->default('')->comment('用户名');
            $table->string('email')->unique()->comment('邮箱');
			 $table->string('bootstyle',200)->default('')->comment('后台样式地址');
            $table->string('password');
            $table->rememberToken();
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
