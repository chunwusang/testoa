<?php
/**
*	企业微信-用户人员
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-23
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxqyuserTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'wxqyuser'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '企业微信用户人员';	
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->string('userid',50)->default('')->comment('帐号');
			$table->string('name',50)->default('')->comment('名称');
			$table->string('department',100)->default('')->comment('所在部门');
			$table->string('position',50)->default('')->comment('职位');
			$table->string('mobile',50)->default('')->comment('手机号');
			$table->string('gender',5)->default('')->comment('性别');
			$table->string('email',100)->default('')->comment('邮箱');
		
			$table->tinyInteger('status')->default(0)->comment('状态@1|已关注,2|已冻结,4|未关注');
			$table->tinyInteger('enable')->default(1)->comment('是否启用,1启用');
			$table->string('avatar',500)->default('')->comment('头像url');
			$table->string('telephone',50)->default('')->comment('电话');
			$table->string('english_name',50)->default('')->comment('英文名');
			
			$table->index('cid');
			$table->unique(['cid','userid']);
			
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
