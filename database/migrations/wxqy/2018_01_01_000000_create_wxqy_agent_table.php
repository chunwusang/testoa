<?php
/**
*	企业微信-应用
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxqyagentTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'wxqyagent'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '企业微应用';	
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->string('name',50)->default('')->comment('应用名称');
			$table->string('agentid',20)->default('')->comment('应用ID');
			$table->integer('agenhid')->default(0)->comment('管理系统自己应用Id');
			
			$table->integer('sort')->default(0)->comment('排序号');
			$table->string('square_logo_url',500)->default('')->comment('应用logo');
			$table->string('redirect_domain',50)->default('')->comment('可信任域名');
			$table->string('description',50)->default('')->comment('应用介绍');
			
			$table->tinyInteger('close')->default(0)->comment('是否禁言');
			
			$table->string('home_url',100)->default('')->comment('主页地址');
			$table->string('secret',100)->default('')->comment('secret管理密钥');
			$table->string('menujson',1000)->default('')->comment('菜单json数组');
			
			$table->string('allow_partys',500)->default('')->comment('企业应用可见范围（部门）');
			$table->string('allow_userinfos',500)->default('')->comment('企业应用可见范围（人员）');
			$table->string('allow_tags',500)->default('')->comment('企业应用可见范围（标签）');
			$table->tinyInteger('report_location_flag')->default(0)->comment('企业应用是否打开地理位置上报');
			$table->tinyInteger('isreportenter')->default(0)->comment('是否上报用户进入应用事件');
			
			$table->index('cid');
			$table->index('name');
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
