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

class UpdateCustomerTypeByUserIdService extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $change_customertype, $userMe; 
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($change_customertype,$userMe)//,$game_code,$number)
	{
		$this->change_customertype = $change_customertype;
		$this->userMe = $userMe;
		// $this->game_code = $game_code;
		// $this->number = $number;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		try{
			// $new_user = User::where('name', '=', $this->username)->first();
			// $change_customertype,$user_id
			
			GameHelpers::UpdateCustomerTypeByUserId($this->change_customertype,$this->userMe);
			// $allUsersMember = User::where('roleid', 6)
			// ->where('active', 0)
			// ->orderBy('lastestlogin','desc')
			// ->get();
			// foreach($allUsersMember as $user){
			// 	GameHelpers::UpdateMeFromParentEX6($user,$user,$this->game_code,$this->bet_number);
			// }
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
		}
	}
}
