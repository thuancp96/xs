<?php namespace App\Console\Commands;

use App\Commands\AutoLockNumber;
use App\Commands\AutoLockNumberMerge;
use App\Commands\CheckUpdateExchangeRate;
use App\Commands\CheckUpdateExchangeRate_v2;
use App\Commands\CheckUpdateExchangeRate_v3;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Commands\UpdateBetPriceAllUser;
use App\Helpers\XoSo;
use App\XoSoResult;
use \Queue;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AutofetchOne789 extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'autofetchOne789';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command autofetchOne789.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle()
	{
		$now = date('Y-m-d');// 
        if ('2024-02-08' < $now && $now < '2024-02-13'){
            // $xoso = new XoSo();
            // $xoso->insertDump();
            return;
        }

		try{
			if( (intval(date('H') ) ==18 && intval(date('i'))>=30) 
			|| intval(date('H') ) >18
			|| intval(date('H') ) <12 ){
				//lock
				return;
			}
			$maxSeconds = 5;
			if (intval(date('H') ) <15){
				$maxSeconds = 30;
			}
			$timeRun = 60/$maxSeconds;
			for($i=1; $i < $timeRun; $i++)
			{
				XoSo::setTokenLD789();
				if (Cache::get("_TokenLD789Failed") == 0){
					$data = XoSo::fetchOne789AuthDataRaw();
					foreach ($data as $key => $gameNumber) {
						if($gameNumber['BetType'] == 0){
							XoSo::fetchOne789AuthData($gameNumber,14,0,0);
						}
						if($gameNumber['BetType'] == 1){
							XoSo::fetchOne789AuthData($gameNumber,7,0,1);
						}
						if($gameNumber['BetType'] == 22){
							XoSo::fetchOne789AuthData($gameNumber,12,0,22);
						}
					}
				}else{
					XoSo::fetchOne789Data(14,0,0);
					XoSo::fetchOne789Data(7,0,1);
					XoSo::fetchOne789Data(12,0,22);	
				}
				if ($i<$timeRun)
					sleep($maxSeconds);
			}
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
		}
	}
}
