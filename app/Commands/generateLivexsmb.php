<?php namespace App\Commands;

use App\Commands\Command;
use App\Facade\XoSo;
use App\Helpers\XoSo as HelpersXoSo;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use App\Helpers\XoSoRecordHelpers;

class generateLivexsmb extends Command implements SelfHandling, ShouldBeQueued {

	use InteractsWithQueue, SerializesModels;
	protected $functionName = "";
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($functionName)
	{
		$this->functionName = $functionName;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		//generateByMinhNgoc
        //generateByxoso888
        //generateByXosome
        //generateByKetquaveso
        //generateByLotusAPI
        //generateBykqxsvnAPI
        //generateBy99luckeyAPI

		$xoso = new HelpersXoSo();
		$responseData = 0;
		switch ($this->functionName) {
			case 'generateByMinhNgoc':
				$responseData = $xoso->generateByMinhNgoc();
				break;
				
			case 'generateByMinhNgocJS':
				$responseData = $xoso->generateByMinhNgocJS();
				break;
				
			case 'generateByXosome':
				$responseData = $xoso->generateByXosome();
				break;

			case 'generateBy99luckeyAPI':
				$responseData = $xoso->generateBy99luckeyAPI();
				break;

			case 'generateByNineVegas':
				$responseData = $xoso->generateByNineVegas();
				break;
				
			case 'generateByxoso888':
				$responseData = $xoso->generateByxoso888();
				break;

			case 'generateByKetquaveso':
				$responseData = $xoso->generateByKetquaveso();
				break;

			case 'generateByLotusAPI':
				$responseData = $xoso->generateByLotusAPI();
				break;

			case 'generateBykqxsvnAPI':
				$responseData = $xoso->generateBykqxsvnAPI();
				break;

			case 'generateByxoso888Pack':
				$responseData = $xoso->generateByxoso888Pack();
				break;
				
			default:
				# code...
				$responseData = 0;
				break;
		}

		echo $responseData;
	}

}
