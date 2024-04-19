<?php namespace App\Commands;

use App\Commands\Command;
use App\Helpers\GameHelpers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;

class TotalBetTodayByNumberThau extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $user,$game_number,$exchange_ratesPlus,$exchange_rates;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($user,$game_number,$exchange_ratesPlus,$exchange_rates)
	{
		//
		$this->user = $user;
		$this->game_number = $game_number;
		$this->exchange_ratesPlus = $exchange_ratesPlus;
		$this->exchange_rates = $exchange_rates;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		// GameHelpers::UpdateChildEX($this->user,$this->game_number,$this->exchange_ratesPlus,$this->exchange_rates);
		// XoSoRecordHelpers::UpdateChildEX($this->gameTarget,$this->game_code,$this->bet_number,$this->TotalBetTodayByNumber,$this->TotalBetTodayByGame,$this->min,$this->max);
	}

}
