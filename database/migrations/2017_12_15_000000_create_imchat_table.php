<?php
/**
*	创建的表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImchatTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'imchat'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '会话历史表';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('发送人单位company.id');
			$table->integer('senduid')->default(0)->comment('发送人users.id');
			$table->integer('sendaid')->default(0)->comment('发送人usera.id');
			
			$table->tinyInteger('type')->default(0)->comment('类型0单人,1群,2应用');
			$table->integer('aid')->default(0)->comment('接收用户usera.id');
			$table->integer('receid')->default(0)->comment('接收来自users.id,类型1来自imgroup.id,类型2来自agenh.id');
			
			$table->integer('stotal')->default(0)->comment('未读数');
			
			$table->string('cont',4000)->default('')->comment('消息内容');
			$table->string('title',50)->default('')->comment('消息标题');
			$table->datetime('optdt')->nullable()->comment('添加时间');
		
			$table->index('cid');
			$table->index('aid');
			$table->unique(['type','aid','receid']);
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
