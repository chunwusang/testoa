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

class CreateImmessztTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'immesszt'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = 'REIM聊天消息状态表';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('发送人IDusers.id');
			
			$table->integer('aid')->default(0)->comment('对应人usera.id');
			$table->integer('mid')->default(0)->comment('消息immess.id');
			$table->tinyInteger('type')->default(0)->comment('类型0单人,1群,2应用');
			$table->integer('gid')->default(0)->comment('对应组id/接收人');
			
			$table->index('cid');
			$table->unique(['aid','mid']);
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
