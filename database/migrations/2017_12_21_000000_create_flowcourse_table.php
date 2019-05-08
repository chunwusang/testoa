<?php
/**
*	创建的表-流程
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-21
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowcourseTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'flowcourse'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '模块流程步骤';
			
            $table->increments('id');
		
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('agenhid')->default(0)->comment('对应应用表agenh.id');
			
            $table->string('name',50)->default('')->comment('步骤名称');
            $table->string('num',30)->default('')->comment('步骤编号');
			
            $table->string('checktype',30)->default('')->comment('审核人类型,super直属上级');
            $table->string('checktypeid',200)->default('')->comment('审核人ID');
            $table->string('checktypename',200)->default('')->comment('审核人');
            $table->string('checkwhere',300)->default('')->comment('审核条件,主表SQL');
			
			$table->string('recename', 1000)->default('')->comment('可用对象,为空全部人可用');
			$table->string('receid', 1000)->default('')->comment('可用对象ID');
			
			$table->string('courseact', 200)->default('')->comment('审核动作');
			$table->string('checkfields', 500)->default('')->comment('审核处理表单');
			$table->string('explain', 100)->default('')->comment('步骤说明');
		
			$table->integer('sort')->default(0)->comment('排序号越大越靠后');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			
			$table->tinyInteger('checkshu')->default(1)->comment('至少几人审核,0全部');
			$table->tinyInteger('iszb')->default(0)->comment('是否可以转办');
			$table->tinyInteger('isqm')->default(0)->comment('手写签名0不用,1都需要,2只需要同意,3只需要不同意');
			$table->tinyInteger('checksmlx')->default(0)->comment('处理说明显示情况');
			
			$table->tinyInteger('pid')->default(0)->comment('0第一个流程,1第二个流程');
			$table->integer('zshtime')->default(0)->comment('超时时间(分钟)');
			$table->tinyInteger('zshstate')->default(1)->comment('自动审核类型1同意,2不同意');
	
			$table->index('cid');
			$table->index('agenhid');
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
