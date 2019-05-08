<?php
/**
*	应用.分类管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentClassifyTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'classify'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '分类管理';
			$table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('aid')->default(0)->comment('单位用户usera.id');
		
            $table->string('name',50)->default('')->comment('名称');
			$table->string('num',50)->default('')->comment('分类编号');
			$table->integer('pid')->default(0)->comment('对应上级id');
           
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
