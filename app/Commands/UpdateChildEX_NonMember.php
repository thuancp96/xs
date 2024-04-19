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

class UpdateChildEX_NonMember extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $user,$game_number,$exchange_ratesPlus,$exchange_rates,$lockPrice;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($user,$game_number,$exchange_ratesPlus,$exchange_rates,$lockPrice)
	{
		//
		$this->user = $user;
		$this->game_number = $game_number;
		$this->exchange_ratesPlus = $exchange_ratesPlus;
		$this->exchange_rates = $exchange_rates;
		$this->lockPrice = $lockPrice;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		GameHelpers::UpdateChildEXv2($this->user,$this->game_number,$this->exchange_ratesPlus,$this->exchange_rates,$this->lockPrice);
	}

}
