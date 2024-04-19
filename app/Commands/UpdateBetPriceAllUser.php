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

class UpdateBetPriceAllUser extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $gameTarget,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($gameTarget,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max)
	{
		//
		$this->game_code = $game_code;
		$this->bet_number = $bet_number;
		$this->TotalBetTodayByNumber = $TotalBetTodayByNumber;
		$this->TotalBetTodayByGame = $TotalBetTodayByGame;
		$this->gameTarget = $gameTarget;
		$this->min = $min;
		$this->max = $max;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$TotalBetTodayByNumber = XoSoRecordHelpers::TotalBetTodayByNumberThau($this->game_code,$this->bet_number);

		Cache::put('TotalBetTodayByNumberThau-'.$this->game_code.'-'.$this->bet_number, $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));

		if ($TotalBetTodayByNumber[1]<=0){	
			return;
		}

		if (isset($this->gameTarget->totalbet) && intval($this->gameTarget->totalbet) == $TotalBetTodayByNumber[0]){
			// $game->status_cal = 0;
			// $game->save();
			// echo 'save status_cal';
			return;
		} 

		$this->TotalBetTodayByNumber = $TotalBetTodayByNumber;

		XoSoRecordHelpers::UpdateBetPriceAllUser($this->gameTarget,$this->game_code,$this->bet_number,$this->TotalBetTodayByNumber,$this->TotalBetTodayByGame,$this->min,$this->max);

		$allUsersMember = User::where('roleid', 6)
			->where('active', 0)
			->where('remain','>', 0)
			->orderBy('latestlogin','desc')
			->limit(10)
			->get();
			foreach($allUsersMember as $user){
				GameHelpers::UpdateMeFromParentEX6($user,$user,$this->game_code,$this->bet_number);
			}
		// GameHelpers::UpdateMeFromParentEX6();
	}

}
