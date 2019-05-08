<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthoryTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	
	private $tablename = 'authory'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '权限设置表';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
			$table->integer('agenhid')->default(0)->comment('对应应用agenh.id');
			
			$table->string('objectid',500)->default('')->comment('针对对象');
			$table->string('objectname',500)->default('')->comment('针对对象姓名');
            //$table->tinyInteger('mtype')->default(0)->comment('类型mid来源0usera.id,1dept.id,2group.id,3所有');
			//$table->string('mname',500)->default('')->comment('名称');
			//$table->integer('mid')->default(0)->comment('对应主id');
			$table->tinyInteger('status')->default(1)->comment('状态0停用,1启用');
			//授权类型:0普通管理员管理单位后台等,1超级管理员,2应用数据查看,3应用数据新增,4应用数据编辑,5应用数据删除,6应用数据导入,7应用数据导出,
			$table->tinyInteger('atype')->default(0)->comment('授权类型');
			
			
			$table->string('receid',500)->default('')->comment('对应人员数据,u人员,g组,d部门,all所有');
			$table->string('recename',500)->default('')->comment('对应人员数据');
			$table->string('wherestr',500)->default('')->comment('对应条件');
			$table->string('explain',500)->default('')->comment('说明');
		
			
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
