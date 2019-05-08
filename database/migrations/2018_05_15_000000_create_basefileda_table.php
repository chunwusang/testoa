<?php
/**
*	文件上传表，可以独立出来的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2015-05-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasefiledaTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'basefileda'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '上传文件记录表';
            $table->increments('id');
			
            $table->string('filenum',50)->default('')->comment('文件编号');
            $table->string('filename',200)->default('')->comment('文件名');
            $table->string('fileext',30)->default('')->comment('文件扩展名');
            $table->string('filetype',200)->nullable()->comment('文件类型');
            $table->string('filepath',200)->nullable()->comment('文件路径');
            $table->string('thumbpath',200)->nullable()->comment('缩略图路径');
            $table->string('pdfpath',200)->nullable()->comment('转为pdf路径');
			
            $table->string('filesizecn',20)->default('')->comment('文件大小');
            $table->integer('filesize')->default(0)->comment('文件大小');
            $table->integer('oid')->default(0)->comment('关联旧ID');
			
			$table->integer('downci')->default(0)->comment('下载次数');
			$table->datetime('adddt')->comment('添加时间');
			$table->string('ip',50)->nullable()->comment('ip');
			$table->string('web',50)->nullable()->comment('浏览器');
			$table->string('remark',200)->nullable()->comment('备注');
			
			$table->unique('filenum'); 
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
