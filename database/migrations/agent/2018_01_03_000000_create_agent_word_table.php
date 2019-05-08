<?php
/**
*	应用.文档分区文件夹
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-10
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentWordTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'word'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '文档文件';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->integer('fqid')->default(0)->comment('分区worc.id');
			$table->integer('folderid')->default(0)->comment('文件夹word.id');
			$table->tinyInteger('type')->default(0)->comment('类型0文件,1文件夹');
			
			$table->string('filename',100)->default('')->comment('文件名');
			$table->string('filenum',50)->default('')->comment('文件编号关联文件系统上');
            $table->string('fileext',30)->default('')->comment('文件扩展名');
            $table->string('thumbpath',200)->default('')->comment('图片缩略图');
			
			$table->string('filesizecn',20)->default('')->comment('文件大小');
            $table->integer('filesize')->default(0)->comment('文件大小');
			
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->string('optname',20)->default('')->comment('操作人');

			$table->string('shateid',200)->default('')->comment('共享给Id');
			$table->string('shatename',200)->default('')->comment('共享给');
			
			$table->integer('sort')->default(0)->comment('排序号');
			$table->integer('downci')->default(0)->comment('下载/查看次数');
			$table->tinyInteger('isdel')->default(0)->comment('是否删除了');
			
			$table->index('cid');
			$table->index('fqid');
			$table->index('folderid');
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
