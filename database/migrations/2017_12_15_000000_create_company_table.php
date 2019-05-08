<?php
/**
*	创建的表-单位表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */

	private $tablename = 'company'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '单位表';
			
            $table->increments('id');
            $table->string('name')->comment('单位名称');
            $table->string('num',10)->unique()->comment('单位编号');
            $table->string('shortname',20)->comment('单位简称');
            $table->string('logo')->default('')->comment('单位logo');
            $table->string('tel',50)->default('')->comment('电话');
            $table->string('contacts',20)->default('')->comment('联系人');
			
			$table->integer('flaskm')->default(100)->comment('用户容量');
			$table->integer('flasks')->default(0)->comment('已添加用户');
			
			$table->integer('uid')->default(0)->comment('创建用户ID');
			
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->index('uid'); //索引
			
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
