<?php
/**
*	创建的表-部门表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeptTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'dept'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '部门组织结构表';
            $table->integer('id')->default(0)->comment('部门Id');
			$table->integer('pid')->default(0)->comment('上级ID顶级');
			$table->integer('cid')->default(0)->comment('对应单位ID');
            $table->string('name', 50)->comment('名称');
            $table->string('num', 30)->default('')->comment('编号');
            $table->string('headman',50)->default('')->comment('负责人');
            $table->string('headid',50)->default('')->comment('负责人ID');
			$table->integer('sort')->default(0)->comment('排序号越大越靠前');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->index('cid');
			$table->index('pid');
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
