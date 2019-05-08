<?php
/**
*	应用.物品出入库详情
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGoodxTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'goodx'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '物品出入库详情';
			$table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('aid')->default(0)->comment('单位用户usera.id');
			
			$table->integer('goodsid')->default(0)->comment('物品goods.id');
			$table->integer('goodmid')->default(0)->comment('申请主表goodm.id');
			$table->integer('goodnid')->default(0)->comment('申请子表goodn.id');
			$table->integer('count')->default(0)->comment('数量');
			$table->integer('depotid')->default(0)->comment('存放仓库ID');
			$table->tinyInteger('type')->default(0)->comment('0入库,1出库');
			$table->tinyInteger('kind')->default(0)->comment('出入库类型');
			$table->date('applydt')->nullable()->comment('申请日期');	
			$table->decimal('price',10,2)->default(0)->comment('单价');
			
			$table->string('explain',500)->default('')->comment('说明');
			$table->datetime('optdt')->nullable()->comment('操作时间');	
			$table->string('optname',20)->default('')->comment('操作人');
			
			
			$table->index('cid');
			$table->index('goodsid');
			$table->index('depotid');
			
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
