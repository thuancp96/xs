<?php namespace App\Commands;

use App\Commands\Command;
use App\Helpers\GameHelpers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;
use App\User;
use \Cache;
use DateTime;

class UpdateBetPriceAllUser_v4 extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $gameTarget,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max,$userSuper;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($gameTarget,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max,$userSuper)
	{
		//
		$this->game_code = $game_code;
		$this->bet_number = $bet_number;
		$this->TotalBetTodayByNumber = $TotalBetTodayByNumber;
		$this->TotalBetTodayByGame = $TotalBetTodayByGame;
		$this->gameTarget = $gameTarget;
		$this->min = $min;
		$this->max = $max;
		$this->userSuper = $userSuper;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		// $TotalBetTodayByNumber = $this->TotalBetTodayByNumber;
		//XoSoRecordHelpers::calThauAdminGameNumber($this->game_code,$this->bet_number);
		//XoSoRecordHelpers::TotalBetTodayByNumberThau($this->game_code,$this->bet_number);

		// if ($this->TotalBetTodayByNumber[1]<=0){	
			// return;
		// }

		// if (isset($this->gameTarget->totalbet) && intval($this->gameTarget->totalbet) == $this->TotalBetTodayByNumber[0]){
			// $game->status_cal = 0;
			// $game->save();
			// echo 'save status_cal';
			// return;
		// } 

		// $this->TotalBetTodayByNumber = $TotalBetTodayByNumber;

		// XoSoRecordHelpers::UpdateBetPriceAllUser($this->gameTarget,$this->game_code,$this->bet_number,$this->TotalBetTodayByNumber,$this->TotalBetTodayByGame,$this->min,$this->max);
		XoSoRecordHelpers::UpdateBetPriceAllUser_v4($this->gameTarget,$this->game_code,$this->bet_number,$this->TotalBetTodayByNumber,$this->TotalBetTodayByGame,$this->min,$this->max,$this->userSuper);

		// GameHelpers::UpdateMeFromParentEX6();
	}

}
