<?php
/**
*	应用-会议室
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-09-08
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentMeethysTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'meethys'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '会议室';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->string('hyname',100)->default('')->comment('会议室名称');
            $table->string('address',100)->default('')->comment('会议室地址');
            $table->string('explain',500)->default('')->comment('会议室说明');
            $table->integer('renshu')->default(0)->comment('容纳人数0不限制');
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
