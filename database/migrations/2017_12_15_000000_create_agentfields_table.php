<?php
/**
*	创建的表-应用的字段元素表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentfieldsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'agentfields'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '应用的字段元素表';
			
            $table->increments('id');
		
			$table->integer('agentid')->default(0)->comment('对应应用ID');
			$table->integer('uid')->default(0)->comment('创建用户ID');
			
            $table->string('name',50)->default('')->comment('名称');
			$table->integer('sort')->default(0)->comment('排序号');
            $table->string('fields',50)->default('')->comment('对应字段');
            $table->string('fieldstype',50)->default('')->comment('对应字段元素类型');
            $table->string('fieldstext',50)->default('')->comment('字段类型');
			$table->integer('lengs')->default(50)->comment('长度');
			
            $table->string('placeholder',50)->default('')->comment('提示内容');
            $table->string('data',500)->default('')->comment('数据源');
            $table->string('dev',100)->default('')->comment('默认值');
			
            $table->string('explain',500)->default('')->comment('字段说明');
            
            $table->string('attr',500)->default('')->comment('属性');
            
			
			$table->tinyInteger('islu')->default(0)->comment('是否录入字段');
			$table->tinyInteger('isbt')->default(0)->comment('是否必填');
			$table->tinyInteger('iszs')->default(0)->comment('是否详情展示');
			$table->tinyInteger('iszb')->default(0)->comment('0主表1子表');
			$table->tinyInteger('isss')->default(0)->comment('是否搜索字段');
			$table->tinyInteger('islb')->default(0)->comment('是否列表页');
			$table->tinyInteger('isdr')->default(0)->comment('是否可导入');
			$table->tinyInteger('ispx')->default(0)->comment('列排序');
			$table->tinyInteger('isml')->default(0)->comment('移动列表');
	
			$table->tinyInteger('status')->default(1)->comment('状态');
			
			$table->tinyInteger('type')->default(0)->comment('应用类型0系统,1用户创建');
			
			$table->string('width',20)->default('')->comment('宽度');
			$table->string('height',20)->default('')->comment('高度');
			$table->string('gongsi',500)->default('')->comment('公式');
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
