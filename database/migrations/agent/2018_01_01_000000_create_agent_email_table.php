<?php
/**
*	应用-邮件
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentEmailTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'email'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '邮件';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('发送人usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');	
			
			$table->string('title',200)->default('')->comment('主题');
			$table->string('applyname',20)->default('')->comment('发送人');
			$table->longtext('content')->nullable()->comment('邮件内容');
			
			$table->string('receid',2000)->default('')->comment('接收人Id');
			$table->string('recename',2000)->default('')->comment('接收ID');
			$table->tinyInteger('isfile')->default(0)->comment('是否有附件');
			
			$table->string('huifuid',2000)->default('')->comment('回复人ID');
			$table->string('delid',2000)->default('')->comment('删除人ID');
			
			$table->datetime('dingdt')->nullable()->comment('定时发送时间');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->datetime('senddt')->nullable()->comment('发送时间');
			$table->integer('emailid')->default(0)->comment('回复主ID');
			
			$table->index('cid');
			$table->index('aid');
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
