<?php

use Illuminate\Database\Seeder;
use App\Model\Base\AdminModel;

class DatabaseSeeder extends Seeder
{
   /**
     * Run the database seeds.
     * php artisan make:seeder CityTableSeeder 创建
	 * php artisan db:seed --class=AgentTableSeeder
	 * php artisan migrate:refresh --seed
     * @return void
     */
    public function run()
    {
        //$this->call(AgentTableSeeder::class);
		
		//添加默认管理员用户
		$data = new AdminModel();
		$data->email 	= 'admin@rockoa.com';
		$data->name 	= '管理员';
		$data->user 	= 'admin';
		$data->password = '123456';
		$data->save();
		
    }
}
