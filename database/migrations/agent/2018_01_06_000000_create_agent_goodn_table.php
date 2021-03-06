<?php
/**
*	应用.物品申请单-子表表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGoodnTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'goodn'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '物品申请单-子表';
			$table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('mid')->default(0)->comment('主表上notice.id');
			$table->integer('sort')->default(0)->comment('排序号');
			$table->tinyInteger('sslx')->default(1)->comment('第几个子表');
			
			$table->integer('goodsid')->default(0)->comment('物品goods.id');
			$table->string('goodsname',50)->default('')->comment('物品名称');
			
			$table->integer('count')->default(0)->comment('数量');
			$table->integer('couns')->default(0)->comment('已出库入库数跟count相等时就全部了');
			$table->string('unit',10)->default('')->comment('计量单位');
			$table->decimal('price',10,2)->default(0)->comment('单价');
	
			$table->index('cid');
			$table->index('mid');
			$table->index('goodsid');
			
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
