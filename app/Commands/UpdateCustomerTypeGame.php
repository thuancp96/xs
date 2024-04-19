<?php namespace App\Commands;

use App\Commands\Command;
use App\Helpers\GameHelpers;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;
use App\User;

class UpdateCustomerTypeGame extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $change,$user_id;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($change,$user_id)
	{
		//
		$this->change = $change;
		$this->user_id = $user_id;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		GameHelpers::UpdateCustomerTypeGame($this->change,$this->user_id);
	}

}
