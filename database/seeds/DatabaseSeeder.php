<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//Model::unguard();


        //$this->call('UserTableSeeder');
		 $this->call('RoleTableSeeder');
	}

}
class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'email'    => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'credit' => 0,
                'consumer' => 0,
                'remain' => 0,
                'fullname'=> 'Hoa An',
                'bet'=> 'Chuẩn A',
                'lock'=>false,
                'roleid'=>'1',
                'user_create'=>'0',
            ]
        ]);

    }


}
class LocationTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('location')->insert([
            ['name' => 'Miền Bắc',
                'slug'    => '1',
                'order' => '1',
                'active' => true,
                'url_api' =>'http://xskt.com.vn/rss-feed/mien-bac-xsmb.rss'
            ]
        ]);

    }

}
class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role')->insert([
            [
                'name' => 'SuperAdmin',
                'isSuperAdmin'=>true,
                'functions'=>'0,1,2,3,4'
            ],
            [
                'name' => 'Admin',
                'isSuperAdmin'=>false,
                'functions'=>'0,1,2,3,4'
            ],
            [
                'name' => 'Hội viên',
                'isSuperAdmin'=>false,
                'functions'=>''
            ]
        ]);

    }
}
class GamesTableSeeder extends Seeder
{
    public function run()
    {
        $this->step1();
        $this->step2();
    }
    public function step1(){
        DB::table('games')->insert([
            [
                'name' =>'Đánh Lô',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>0,
                'game_guide'=>'',
                'odds'=>80,
                'exchange_rates'=>22
            ],
            [
                'name' =>'Đánh Đề',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>0,
                'game_guide'=>'',
                'odds'=>70,
                'exchange_rates'=>1
            ],
            [
                'name' =>'3 Càng',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>0,
                'game_guide'=>'',
                'odds'=>700,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Lô xiên',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>0,
                'game_guide'=>'',
                'odds'=>12,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Đầu đuôi',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>0,
                'game_guide'=>'',
                'odds'=>9,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Lô trượt',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>0,
                'game_guide'=>'',
                'odds'=>2,
                'exchange_rates'=>1
            ],
        ]);
    }
    public function step2(){
        DB::table('games')->insert([
            [
                'name' =>'Đánh lô 2 số',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>1,
                'game_guide'=>'',
                'odds'=>80,
                'exchange_rates'=>22
            ],
            [
                'name' =>'Đánh lô 3 số',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>1,
                'game_guide'=>'',
                'odds'=>800,
                'exchange_rates'=>23
            ],
            [
                'name' =>'Xiên 2',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>4,
                'game_guide'=>'',
                'odds'=>12,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Xiên 3',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>4,
                'game_guide'=>'',
                'odds'=>40,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Xiên 4',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>4,
                'game_guide'=>'',
                'odds'=>130,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Giải 7',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>2,
                'game_guide'=>'',
                'odds'=>85,
                'exchange_rates'=>4
            ],
            [
                'name' =>'Giải đặc biệt',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>2,
                'game_guide'=>'',
                'odds'=>85,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Đề đầu',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>5,
                'game_guide'=>'',
                'odds'=>9,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Đề đuôi',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>5,
                'game_guide'=>'',
                'odds'=>9,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Lô trượt xiên 4',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>6,
                'game_guide'=>'',
                'odds'=>2,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Lô trượt xiên 8',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>6,
                'game_guide'=>'',
                'odds'=>7,
                'exchange_rates'=>1
            ],
            [
                'name' =>'Lô trượt xiên 10',
                'order'=>0,
                'active'=>true,
                'location_id'=>1,
                'parent_id'=>6,
                'game_guide'=>'',
                'odds'=>10,
                'exchange_rates'=>1
            ],
        ]);
    }
}