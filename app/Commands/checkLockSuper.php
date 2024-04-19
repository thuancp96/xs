<?php namespace App\Commands;

use App\Commands\Command;
use App\Helpers\GameHelpers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;
use \Cache;

class checkLockSuper extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $game_code,$bet_number;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($game_code,$bet_number)
	{
		//
		$this->game_code = $game_code;
		$this->bet_number = $bet_number;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$lockSuperNumber = XoSoRecordHelpers::checkLockSuper($this->game_code,$this->bet_number);
		Cache::put('checkLockSuper-'.$this->game_code.'-'.$this->bet_number, $lockSuperNumber, env('CACHE_TIME', 24*60));
	}

}
