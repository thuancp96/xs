<?php namespace App\Commands;

use App\Commands\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\GameHelpers;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use App\User;

class InitDataForNewUser extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $username, $parent_id, $tableTarget,$copy_data,$userrole,$customer_type;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($username,$userrole,$customer_type,$copy_data,$parent_id,$tableTarget)
	{
		//
		$this->username = $username;
		$this->userrole = $userrole;
		$this->customer_type = $customer_type;
		$this->copy_data = $copy_data;
		$this->parent_id = $parent_id;
		$this->tableTarget = $tableTarget;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		try{
			if ($this->copy_data == 'non'){
				if($this->userrole != 6)
					{
					$customer_users = CustomerType_Game::where('created_user',$this->parent_id)->get();
					//    $customer_users = CustomerType_Game_Original::where('created_user',$id)->get();
					//    customer_original_users
					}else{
					$customer_users = CustomerType_Game::where('created_user',$this->parent_id)->where('code_type',$this->customer_type)->get();
					// $customer_original_users = null;
					}
		
					$new_user = User::where('name', '=', $this->username)->first();

					foreach ($customer_users as $cus)
					{
						if ($this->tableTarget == 'CustomerType_Game'){
							$new_cus = new CustomerType_Game;
							$new_cus->code_type = $cus->code_type;
							$new_cus->game_id = $cus->game_id;
							$new_cus->exchange_rates = $cus->exchange_rates;
							$new_cus->odds = $cus->odds;
							$new_cus->created_user = $new_user->id;
							$new_cus->change_odds = $cus->change_odds;
							$new_cus->change_ex = $cus->change_ex;
							$new_cus->change_max_one = $cus->change_max_one;
	
							$new_cus->max_point = $cus->max_point;
							$new_cus->max_point_one = $cus->max_point_one;
	
							$new_cus->save();
						}
						
						if ($this->tableTarget == 'CustomerType_Game_Original'){
							$new_cusOg = new CustomerType_Game_Original;
							$new_cusOg->code_type = $cus->code_type;
							$new_cusOg->game_id = $cus->game_id;
							$new_cusOg->exchange_rates = $cus->exchange_rates;
							$new_cusOg->odds = $cus->odds;
							$new_cusOg->created_user = $new_user->id;
							$new_cusOg->change_odds = $cus->change_odds;
							$new_cusOg->change_ex = $cus->change_ex;
							$new_cusOg->change_max_one = $cus->change_max_one;
							$new_cusOg->max_point = $cus->max_point;
							$new_cusOg->max_point_one = $cus->max_point_one;

							$new_cusOg->save();
						}
					}
			}else{
				if($this->userrole != 6)
					{
					$customer_users = CustomerType_Game::where('created_user',$this->copy_data)->get();
					// $customer_original_users = CustomerType_Game_Original::where('created_user',$id)->get();
					}else{
						$current_user_copy = User::where('id', '=', $this->copy_data)->first();
						$customer_users = CustomerType_Game::where('created_user',$this->copy_data)->where('code_type',$current_user_copy->customer_type)->get();
					// $customer_original_users = null;
					}
		
					$new_user = User::where('name', '=', $this->username)->first();
		
					foreach ($customer_users as $cus)
					{
						if ($this->tableTarget == 'CustomerType_Game'){
							$new_cus = new CustomerType_Game;
							$new_cus->code_type = $cus->code_type;
							$new_cus->game_id = $cus->game_id;
							$new_cus->exchange_rates = $cus->exchange_rates;
							$new_cus->odds = $cus->odds;
							$new_cus->created_user = $new_user->id;
							$new_cus->change_odds = $cus->change_odds;
							$new_cus->change_ex = $cus->change_ex;

							$new_cus->max_point = $cus->max_point;
							$new_cus->max_point_one = $cus->max_point_one;

							$new_cus->save();
						}

						if ($this->tableTarget == 'CustomerType_Game_Original'){
							$new_cusOg = new CustomerType_Game_Original;
							$new_cusOg->code_type = $cus->code_type;
							$new_cusOg->game_id = $cus->game_id;
							$new_cusOg->exchange_rates = $cus->exchange_rates;
							$new_cusOg->odds = $cus->odds;
							$new_cusOg->created_user = $new_user->id;
							$new_cusOg->change_odds = $cus->change_odds;
							$new_cusOg->change_ex = $cus->change_ex;

							$new_cusOg->max_point = $cus->max_point;
							$new_cusOg->max_point_one = $cus->max_point_one;

							$new_cusOg->save();
						}
					}
			}
			GameHelpers::UpdateMeFromParentEX($new_user,$new_user);
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
		}
	}
}
