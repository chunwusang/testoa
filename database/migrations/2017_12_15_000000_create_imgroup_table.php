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

class CreateImgroupTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'imgroup'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = 'REIM会话组';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('创建人IDusers.id');
			$table->integer('aid')->default(0)->comment('创建人IDusera.id');
			
			$table->tinyInteger('type')->default(0)->comment('类型0固定群,1讨论组');
			$table->string('name',50)->default('')->comment('会话名称');
			$table->integer('deptid')->default(0)->comment('对应部门Id,如果存在自动加入');
			$table->string('face',200)->default('')->comment('头像');
			
			$table->string('gonggao',200)->default('')->comment('公告');
			$table->datetime('optdt')->nullable()->comment('添加时间');
			
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
