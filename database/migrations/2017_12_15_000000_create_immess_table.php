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

class CreateImmessTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'immess'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = 'REIM聊天记录表';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('发送人单位company.id');
			$table->integer('uid')->default(0)->comment('发送人IDusers.id');
			$table->integer('aid')->default(0)->comment('发送人IDusera.id');
			
			$table->tinyInteger('type')->default(0)->comment('类型0单人,1群,2应用');

			$table->integer('receid')->default(0)->comment('接收人usera.id,类型1来自imgroup.id,类型2来自agenh.id');

			$table->string('cont',4000)->default('')->comment('消息内容');
			$table->string('url',200)->default('')->comment('消息相关地址');
			
			$table->integer('fileid')->default(0)->comment('关联文件file.id');
			$table->datetime('optdt')->nullable()->comment('添加时间');
			$table->string('receids',4000)->default('')->comment('接收人aid聚合');
			
			$table->index('cid');
			$table->index(['type','receid']);
			
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
