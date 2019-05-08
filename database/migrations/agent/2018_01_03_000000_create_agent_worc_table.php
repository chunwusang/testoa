<?php
/**
*	应用.文档分区
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-10
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentWorcTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'worc'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '文档分区';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');

			$table->string('name',20)->default('')->comment('分区名称');
            $table->string('uptype',100)->default('')->comment('上传类型');
			
			$table->string('receid',200)->default('')->comment('可查看人员ID');
			$table->string('recename',200)->default('')->comment('可查看人员');
			$table->string('guanid',200)->default('')->comment('管理人员Id');
			$table->string('guanname',200)->default('')->comment('管理人员');
			$table->string('upuserid',200)->default('')->comment('可上传人员ID');
			$table->string('upuser',200)->default('')->comment('可上传人员');
			
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');
			$table->integer('optid')->default(0)->comment('操作人id');
			$table->bigInteger('size')->default(0)->comment('分配大小0不限制单位字节');
			$table->bigInteger('sizeu')->default(0)->comment('已使用大小字节');
			$table->integer('sort')->default(0)->comment('排序号');
 
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
