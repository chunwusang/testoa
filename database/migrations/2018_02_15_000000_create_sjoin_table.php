<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSjoinTable extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     * @return void
     */
	
	private $tablename = 'sjoin'; 
	 
    public function up()
    {
        Schema::create($this->tablename, function (Blueprint $table) {
			
			$table->comment = '对应关系表';
			
            $table->increments('id');
			$table->integer('cid')->default(0)->comment('对应单位ID');
            $table->string('type',10)->comment('类型');
			$table->integer('mid')->default(0)->comment('对应主id');
			$table->integer('sid')->default(0)->comment('对应子id');
			
			$table->index('cid');
			$table->unique(['type','mid','sid']);
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
