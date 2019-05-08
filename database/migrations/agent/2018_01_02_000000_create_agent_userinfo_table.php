<?php
/**
*	应用.人员档案
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-10
*/

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentUserinfoTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	
	private $tablename = 'userinfo'; 
	
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			$table->comment = '人员档案';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('单位company.id');
			$table->integer('uid')->default(0)->comment('平台用户users.id');
			$table->integer('aid')->default(0)->comment('平台单位用户usera.id');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->tinyInteger('isturn')->default(0)->comment('是否提交');
			
			//---跟单位用户表信息一样----
			$table->string('num',50)->default('')->comment('编号');
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
			$table->string('pingyin',50)->default('')->comment('名字拼音');
			//---跟单位用户表信息一样----

			$table->tinyInteger('state')->default(0)->comment('员工状态0试用期,1正式');
	
			$table->date('workdate')->nullable()->comment('入职时间');
			$table->date('quitdt')->nullable()->comment('离职日期');
			$table->date('birthday')->nullable()->comment('生日');
			
			$table->tinyInteger('iskq')->default(1)->comment('是否考勤');
			$table->tinyInteger('isdwdk')->default(1)->comment('是否定位打卡');
			$table->tinyInteger('isdaily')->default(1)->comment('是否需要写日报');
			
			$table->string('xueli',20)->default('')->comment('最高学历');
			$table->string('xuexiao',50)->default('')->comment('毕业学校');
			$table->string('minzu',20)->default('')->comment('民族');
			$table->string('hunyin',20)->default('')->comment('婚姻');
			$table->string('jiguan',50)->default('')->comment('籍贯');
			$table->string('nowdizhi',50)->default('')->comment('现住址');
			$table->string('housedizhi',50)->default('')->comment('家庭地址');
			$table->date('syenddt')->nullable()->comment('试用期到');
			$table->date('positivedt')->nullable()->comment('转正日期');
			
			$table->string('bankname',50)->default('')->comment('工资卡开户行');
			$table->string('banknum',50)->default('')->comment('工资卡帐号');
			$table->string('zhaopian',100)->default('')->comment('个人照片');
			$table->string('idnum',50)->default('')->comment('身份证号');
			$table->string('spareman',30)->default('')->comment('备用联系人');
			$table->string('sparetel',50)->default('')->comment('备用联系人电话');
			
			$table->datetime('optdt')->nullable()->comment('操作时间');
			$table->datetime('createdt')->nullable()->comment('创建时间');
			
			$table->string('dkip',200)->default('')->comment('在线打卡ip');
			$table->string('dkmac',200)->default('')->comment('在线打卡MAC地址');
			$table->string('finger',20)->default('')->comment('考勤机人员编号');
 
			$table->index('cid');
			$table->index('aid');
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
