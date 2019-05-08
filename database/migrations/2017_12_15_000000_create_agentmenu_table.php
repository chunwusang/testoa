<?php
/**
*	创建的表-系统应用的菜单表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-22
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentmenuTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'agentmenu'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '系统应用的菜单表';
			
            $table->increments('id');
			$table->integer('agentid')->default(0)->comment('对应应用agent.id');
			$table->integer('uid')->default(0)->comment('创建用户ID');
			
            $table->string('name',50)->default('')->comment('名称');
            $table->string('num',50)->default('')->comment('编号');
            $table->string('pnum',50)->default('')->comment('分组编号');
            $table->string('type',20)->default('')->comment('菜单类型,url,sql等');
			
            $table->string('url',500)->default('')->comment('对应地址');
            $table->string('wherestr',500)->default('')->comment('过滤SQL条件');
            $table->string('explain',500)->default('')->comment('说明');
            $table->string('color',20)->default('')->comment('菜单颜色');
			
			$table->integer('sort')->default(0)->comment('排序号');
			$table->integer('pid')->default(0)->comment('上级ID');
			
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isbag')->default(0)->comment('徽章角标');
			$table->tinyInteger('isturn')->default(0)->comment('需提交');
			$table->tinyInteger('iswzf')->default(0)->comment('未作废的');
			
			$table->index('agentid');
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
