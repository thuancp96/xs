<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;

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
		XoSoRecordHelpers::UpdateBetPriceAllUser($this->gameTarget,$this->game_code,$this->bet_number,$this->TotalBetTodayByNumber,$this->TotalBetTodayByGame,$this->min,$this->max);
	}

}
