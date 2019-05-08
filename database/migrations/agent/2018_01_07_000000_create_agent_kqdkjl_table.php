<?php
/**
*	应用.打卡记录
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentKqdkjlTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'kqdkjl'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '打卡记录';
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('aid')->default(0)->comment('单位用户usera.id');

			$table->datetime('dkdt')->nullable()->comment('打卡时间');
			$table->datetime('optdt')->nullable()->comment('添加时间');
			$table->tinyInteger('type')->default(0)->comment('类型0在线打卡,1考勤机,2手机定位,3手动添加,4异常添加,5数据导入,6接口导入');
		
			$table->string('address',100)->default('')->comment('定位地址');
			$table->string('lat',50)->default('')->comment('纬度');
			$table->string('lng',50)->default('')->comment('经度');
			$table->integer('accuracy')->default(0)->comment('精确范围');
			
			$table->string('ip',50)->default('')->comment('IP');
			$table->string('mac',50)->default('')->comment('MAC地址');
			
			$table->string('explain',500)->default('')->comment('说明');
			$table->string('imgpath',100)->default('')->comment('相关图片');
			
			$table->integer('snid')->default(0)->comment('考勤机设备id');
			$table->tinyInteger('sntype')->default(0)->comment('考勤机打卡方式0密码,1指纹,2刷卡');
			
			$table->index('cid');
			$table->index('aid');
			$table->index('dkdt');

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
