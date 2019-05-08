<?php
/**
*	REIM
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImgroupuserTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'imgroupuser'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = 'REIM会话组下人员';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('gid')->default(0)->comment('会话组imgroup.id');
			$table->integer('aid')->default(0)->comment('加入人usera.id');
			
			$table->tinyInteger('istx')->default(0)->comment('是否提醒');
			
			$table->index('cid');
			$table->unique(['gid','aid']);
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
