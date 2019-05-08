<?php
/**
*	应用.物品
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGoodsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'goods'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '物品管理';
			$table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('aid')->default(0)->comment('单位用户usera.id');
			
			//用名称+规格+型号判断唯一值
			
            $table->string('name',50)->default('')->comment('名称');
			$table->string('num',50)->default('')->comment('物品编号');
			$table->string('classname',50)->default('')->comment('对应分类');
			$table->integer('classid')->default(0)->comment('对应分类classify.id');
			$table->string('guige',50)->default('')->comment('规格');
			$table->string('xinghao',50)->default('')->comment('型号');
			$table->string('unit',10)->default('')->comment('计量单位');
			$table->decimal('price',10,2)->default(0)->comment('单价');
           
			$table->integer('sort')->default(0)->comment('排序号');
			$table->integer('stock')->default(0)->comment('库存');
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
