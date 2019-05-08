<?php
/**
*	应用.人员档案子表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentUserinfosTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'userinfos'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '人员档案子表';

			$table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('mid')->default(0)->comment('主表上userinfo.id');
			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('sslx')->default(1)->comment('1工作经历,2教育经历');
			
			$table->date('startdt')->nullable()->comment('开始日期');
			$table->date('enddt')->nullable()->comment('截止日期');
			$table->string('rank',50)->default('')->comment('职位');
			$table->string('unitname',50)->default('')->comment('单位名称');
			$table->string('tupian',200)->default('')->comment('相关图片');
			
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
