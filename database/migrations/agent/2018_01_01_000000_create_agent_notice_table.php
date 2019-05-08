<?php
/**
*	应用.通知
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentNoticeTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'notice'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '通知表';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
            $table->string('title',50)->default('')->comment('标题');
            $table->string('typename',20)->default('')->comment('类型');
            $table->text('content')->nullable()->comment('内容');
        
			$table->integer('sort')->default(0)->comment('排序号');
			$table->date('indate')->nullable()->comment('发布日期');
			$table->string('zuozhe',30)->default('')->comment('发布者');
			$table->string('fengmian',100)->default('')->comment('封面图片');
			
			$table->string('receid',1000)->default('')->comment('接收人Id,为空是全体人员');
			$table->string('recename',1000)->default('')->comment('接收人');
			
			$table->datetime('startdt')->nullable()->comment('开始时间');
			$table->datetime('enddt')->nullable()->comment('截止时间');
	
			$table->smallInteger('mintou')->default(0)->comment('至少投票');
			$table->smallInteger('maxtou')->default(0)->comment('最多投投票0不限制');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			
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
