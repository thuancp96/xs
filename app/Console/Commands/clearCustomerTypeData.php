<?php namespace App\Console\Commands;

use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class clearCustomerTypeData extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:clearCustomerTypeData';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		//
		// CustomerType_Game::where("id",221231)->delete();
		// return;
		ini_set('memory_limit', '-1');
		$customerType = CustomerType_Game::get();
		
		echo count($customerType) . " ";
		$count=0;
		foreach($customerType as $item){
			$user = User::where("id",$item->created_user)->get();
			if (count($user) < 1){
				// echo '.';
				$count++;
				// CustomerType_Game::where("id",$item->id)->delete();
				// $item->delete();
			}
		}
		echo $count;
		return;
		$customerTypeOrg = CustomerType_Game_Original::get();
		$count=0;
		foreach($customerTypeOrg as $item){
			$user = User::where("id",$item->created_user)->get();
			if (count($user) < 1){
				echo '.';
				$count++;
				// CustomerType_Game::where("id",$item->id)->delete();
				// $item->delete();
			}
		}
		echo $count;
	}


}
