<?php
/**
*	应用.仓库管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentGodepotTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'godepot'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '仓库管理';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('aid')->default(0)->comment('单位用户usera.id');
			
			$table->string('depotname',50)->default('')->comment('仓库名称');
			$table->string('depotaddress',100)->default('')->comment('仓库地址');
			$table->string('depotel',100)->default('')->comment('仓库电话');
			$table->string('depotnum',50)->default('')->comment('仓库编号');
			
			$table->string('cgname',50)->default('')->comment('仓库管理员');
			$table->string('cgid',50)->default('')->comment('仓库管理员的ID');
			
			
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->integer('sort')->default(0)->comment('排序号');
			
			$table->integer('wpzshu')->default(0)->comment('物品数量');
			$table->integer('wptshu')->default(0)->comment('物品类型数量');
			
			$table->string('explain',500)->default('')->comment('说明');
			
			$table->index('cid');
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
