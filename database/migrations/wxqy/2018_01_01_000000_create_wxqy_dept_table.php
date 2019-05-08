<?php
/**
*	企业微信-部门
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxqydeptTable extends Migration
{
    /**
     * Run the migrations.
	 * php artisan migrate --path=database/migrations/wxqy
     * @return void
     */
	
	private $tablename = 'wxqydept'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '企业微信部门';	
            $table->integer('id')->default(0)->comment('部门Id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->string('name',50)->default('')->comment('名称');
			$table->integer('parentid')->default(0)->comment('上级Id');
			$table->integer('order')->default(0)->comment('排序号');
			$table->index('cid');
			$table->unique(['cid','id']);
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
