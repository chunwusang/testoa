<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	
	private $tablename = 'group'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '分组';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
            $table->string('name')->comment('组名');
			$table->integer('sort')->default(0)->comment('排序号越大越靠前');
			$table->index('cid');
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
