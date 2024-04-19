<?php namespace App\Commands;

use App\Commands\Command;
use App\Helpers\GameHelpers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;
use App\User;
use DateTime;

class UpdateChildEX extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $user,$game_number,$exchange_ratesPlus,$exchange_rates,$lock_price;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($user,$game_number,$exchange_ratesPlus,$exchange_rates,$lock_price=0)
	{
		//
		$this->user = $user;
		$this->game_number = $game_number;
		$this->exchange_ratesPlus = $exchange_ratesPlus;
		$this->exchange_rates = $exchange_rates;
		$this->lock_price = $lock_price;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		GameHelpers::UpdateChildEXv2($this->user,$this->game_number,$this->exchange_ratesPlus,$this->exchange_rates,$this->lock_price);
		
		// if de lo nhat
		// 	update theo lock_price
		// else
		// 	update theo gia 99

		if (($this->game_number->code_type == 7 || $this->game_number->code_type == 14 ||$this->game_number->code_type == 12))
		{
			$allUsersMember = User::where('roleid', 6)
			->where('active', 0)
			->where('lock_price', $this->lock_price)
			->where('remain','>', 0)
			->orderBy('latestlogin','desc')
			->limit(1000)
			->get();
			foreach($allUsersMember as $user){
				$secs = (new DateTime())->getTimestamp() - (new DateTime($user->latestlogin))->getTimestamp();// == <seconds between the two times>
				$days = $secs / 86400;
				if ($days < 1) 
					GameHelpers::UpdateMeFromParentEX6($user,$user,$this->game_number->code_type,$this->game_number->number);
			}
		}
		else{
			$allUsersMember = User::where('roleid', 6)
			->where('active', 0)
			->whereIn('lock_price', [0,2])
			->where('remain','>', 0)
			->orderBy('latestlogin','desc')
			->limit(1000)
			->get();
			foreach($allUsersMember as $user){
				$secs = (new DateTime())->getTimestamp() - (new DateTime($user->latestlogin))->getTimestamp();// == <seconds between the two times>
				$days = $secs / 86400;
				if ($days < 1) 
					GameHelpers::UpdateMeFromParentEX6($user,$user,$this->game_number->code_type,$this->game_number->number);
			}
		}
			
		// XoSoRecordHelpers::UpdateChildEX($this->gameTarget,$this->game_code,$this->bet_number,$this->TotalBetTodayByNumber,$this->TotalBetTodayByGame,$this->min,$this->max);
	}

}
