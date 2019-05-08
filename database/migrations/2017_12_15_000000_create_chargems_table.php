<?php
/**
*	创建的表-平台升级文件
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChargemsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'chargems'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment='平台升级记录表';
            $table->increments('id');
			$table->tinyInteger('type')->default(0)->comment('类型');
			$table->integer('mid')->default(0)->comment('对应id');
			
			$table->datetime('optdt')->nullable()->comment('添加时间');
			$table->datetime('updatedt')->nullable()->comment('更新时间');
			
			$table->string('key',200)->default('')->comment('key');
			$table->integer('modeid')->default(0)->comment('模块id');

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
