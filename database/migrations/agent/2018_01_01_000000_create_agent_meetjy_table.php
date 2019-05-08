<?php
/**
*	应用-会议纪要
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-09-08
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentMeetjyTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'meetjy'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '会议纪要';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('aid')->default(0)->comment('纪要人');
			
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			$table->integer('meetid')->default(0)->comment('对应会议');
			$table->string('meetitle',100)->default('')->comment('会议主题');
			
			$table->text('content')->nullable()->comment('纪要内容');
			$table->string('optname',20)->default('')->comment('纪要人');
			$table->datetime('optdt')->nullable()->comment('操作时间');
			
			$table->string('receid',1000)->default('')->comment('通知给');
			$table->string('recename',1000)->default('')->comment('通知给');
			
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
