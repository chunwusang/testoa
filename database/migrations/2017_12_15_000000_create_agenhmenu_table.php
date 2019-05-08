<?php
/**
*	创建的表-单位应用的菜单表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-22
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgenhmenuTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'agenhmenu'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '单位应用的菜单表';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('agenhid')->default(0)->comment('对应应用agenh.id');
			
            $table->string('name',50)->default('')->comment('名称');
            $table->string('num',50)->default('')->comment('编号');
            $table->string('type',20)->default('')->comment('菜单类型,url,sql等');
			
            $table->string('url',500)->default('')->comment('对应地址');
            $table->string('color',20)->default('')->comment('菜单颜色');
			
			$table->integer('sort')->default(0)->comment('排序号');
			$table->integer('pid')->default(0)->comment('上级ID');
			
			$table->string('recename', 1000)->default('')->comment('可用对象,为空全部人可用');
			$table->string('receid', 1000)->default('')->comment('可用对象ID');
			
			$table->tinyInteger('status')->default(1)->comment('状态');
			
			$table->index('cid');
			$table->index('agenhid');
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
