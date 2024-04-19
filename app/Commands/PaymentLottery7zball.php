<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;

class PaymentLottery7zball extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $record;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($record)
	{
		//
		$this->record = $record;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		//
		XoSoRecordHelpers::PaymentLottery7zball($this->record);
	}

}
