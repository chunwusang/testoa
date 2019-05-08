<?php
/**
*	创建的表-文件表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'file'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '文件表';
            $table->increments('id');
			
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('uid')->default(0)->comment('创建用户ID');
			$table->integer('aid')->default(0)->comment('创建单位下用户ID');
            $table->string('filenum',50)->default('')->comment('文件编号关联文件系统上');
            $table->string('filename',200)->default('')->comment('文件名');
            $table->string('fileext',30)->default('')->comment('文件扩展名');
            $table->string('filepath',200)->default('')->comment('文件路径');
            $table->string('thumbpath',200)->default('')->comment('缩略图路径');
            $table->string('pdfpath',200)->default('')->comment('转为pdf路径');
			
            $table->string('filesizecn',20)->default('')->comment('文件大小');
            $table->integer('filesize')->default('0')->comment('文件大小');
			
			$table->integer('downci')->default(0)->comment('下载次数');
			
			$table->datetime('optdt')->nullable()->comment('添加时间');
			$table->datetime('lastdt')->nullable()->comment('最后下载');
			
			$table->string('mtable',50)->default('')->comment('对应表');
			$table->integer('mid')->default(0)->comment('对应主表的id');
			$table->tinyInteger('isxg')->default(0)->comment('相关文件');
			$table->tinyInteger('isdel')->default(0)->comment('是否删除了');
			
			$table->index('cid');
			$table->index('uid');

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
