<?php
/**
*	创建的表-系统数据选项表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'option'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '数据选项';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('pid')->default(0)->comment('对应上级id');
			
            $table->string('num',50)->default('')->comment('选项编号');
            $table->string('name',50)->default('')->comment('名称');
            $table->string('value',500)->default('')->comment('对应值');	
           
			$table->integer('sort')->default(0)->comment('排序号');
			$table->string('explain',500)->default('')->comment('说明');
			$table->datetime('optdt')->nullable()->comment('时间');	
			
			$table->index('cid');
			$table->index('num');
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
