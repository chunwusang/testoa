<?php
/**
*	创建的表-单位用户表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUseraTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	private $tablename = 'usera'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '单位下用户表';

            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('uid')->default(0)->comment('关联平台用户ID');
			
            $table->string('name',50)->default('')->comment('姓名');
            $table->string('user',30)->default('')->comment('用户名');
			
            $table->string('position',50)->default('')->comment('职位');
            $table->string('mobile',50)->default('')->comment('手机号');
            $table->string('mobilecode',10)->default('')->comment('手机号区号，默认+86');
            $table->string('email',100)->default('')->comment('单位分配的邮箱');
			
			$table->integer('deptid')->default(0)->comment('所在部门ID');
			$table->string('deptname',30)->default('')->comment('部门名称');
			$table->string('deptids',50)->default('')->comment('多部门ID,多个,分开');
			$table->string('deptallname')->default('')->comment('部门全名');
			$table->string('deptpath',200)->default('')->comment('部门路径,如1,2,3');
			
			$table->string('superid',30)->default('')->comment('上级主管Id');
			$table->string('superman',50)->default('')->comment('上级主管姓名,多个,分开');
			$table->string('superpath',200)->default('')->comment('上级主管全部人');
			
			$table->string('grouppath',200)->default('')->comment('组Id');
			$table->string('tel',50)->default('')->comment('办公电话');
			
			$table->integer('sort')->default(0)->comment('排序号越大越靠前');
			
			$table->tinyInteger('gender')->default(1)->comment('性别0未知,1男,2女');
			$table->tinyInteger('status')->default(0)->comment('状态0待激活,1已激活,2停用');
			$table->tinyInteger('istxl')->default(1)->comment('通讯录显示');
			
			$table->tinyInteger('type')->default(0)->comment('用户级别0普通用户,1管理员');
			$table->string('pingyin',50)->default('')->comment('名字拼音');
	
			$table->datetime('joindt')->nullable()->comment('激活时间');
			$table->datetime('createdt')->nullable()->comment('添加时间');
			$table->datetime('optdt')->nullable()->comment('操作时间');
				
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
