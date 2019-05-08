<?php
/**
*	创建的表-应用表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'agent'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '应用表';
			
            $table->increments('id');
		
			$table->integer('uid')->default(0)->comment('创建用户ID');
			
            $table->string('name',50)->comment('应用名称');
            $table->string('num',30)->unique()->comment('应用编号');
            $table->string('table', 50)->default('')->comment('应用对应表');
            $table->string('description')->default('')->comment('应用介绍');
            $table->string('face')->default('')->comment('应用图标');
            $table->string('urlm')->default('')->comment('应用移动端地址');
            $table->string('urlpc')->default('')->comment('应用PC端地址');
			
            $table->string('atype',20)->default('')->comment('应用分类');
            $table->string('atypes',20)->default('')->comment('应用子分类');
			
			$table->string('tables', 200)->default('')->comment('对应子表');
			$table->string('names', 200)->default('')->comment('对应子表名称');
			
			$table->string('mwhere',50)->default('')->comment('主表条件');
			$table->string('sericnum',50)->default('')->comment('单号规则');
			$table->tinyInteger('yylx')->default(0)->comment('应用类型0全部,1pc,2手机');
			
			$table->string('summary', 500)->default('')->comment('列表摘要');
			$table->string('summarx', 500)->default('')->comment('应用摘要');
			$table->integer('sort')->default(0)->comment('排序号');
	
			$table->tinyInteger('type')->default(0)->comment('应用类型0系统,1第三方应用');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			$table->tinyInteger('iscs')->default(0)->comment('抄送0不抄送,1必须抄送,2可选');
			$table->tinyInteger('istxset')->default(0)->comment('单据提醒设置');
			$table->tinyInteger('ispl')->default(0)->comment('是否可评论');
			$table->tinyInteger('islu')->default(0)->comment('是否可录入');
			$table->tinyInteger('issy')->default(0)->comment('是否显示首页');
			
			$table->integer('zfeitime')->default(0)->comment('超过分钟自动作废');
			$table->string('pmenustr', 500)->default('')->comment('菜单分组');
			
			$table->tinyInteger('isup')->default(0)->comment('相关文件0不显示,1必须上传,2可选');
			$table->string('uptype',100)->default('')->comment('相关文件限制类型');
			
			$table->index('uid');
            $table->timestamps();
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
