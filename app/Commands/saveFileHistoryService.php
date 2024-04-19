<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;

class saveFileHistoryService extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $gameType;
	protected $customerName;
	protected $id;
	protected $betNumber;
	protected $TotalBet;
	protected $idXien;
	protected $isHuy;
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($gameType, $customerName, $id, $betNumber, $TotalBet, $idXien, $isHuy = false)
	{
		//
		$this->gameType = $gameType;
		$this->customerName =$customerName;
		$this->id = $id;
		$this->betNumber = $betNumber;
		$this->TotalBet = $TotalBet;
		$this->idXien = $idXien;
		$this->isHuy = $isHuy;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		//
		XoSoRecordHelpers::saveFileHistory($this->gameType, $this->customerName, $this->id, $this->betNumber, $this->TotalBet, $this->idXien, $this->isHuy);
	}

}
