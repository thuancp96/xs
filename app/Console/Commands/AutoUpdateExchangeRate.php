<?php namespace App\Console\Commands;

use App\Commands\AutoLockNumber;
use App\Commands\AutoLockNumberMerge;
use App\Commands\CheckUpdateExchangeRate;
use App\Commands\CheckUpdateExchangeRate_v2;
use App\Commands\CheckUpdateExchangeRate_v3;
use App\Commands\CheckUpdateExchangeRate_v4_20240220;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Commands\UpdateBetPriceAllUser;
use App\XoSoResult;
use \Cache;
use \Queue;
use DateTime;
use Illuminate\Support\Facades\DB;

class AutoUpdateExchangeRate extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'update-exrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		try{
            echo "update-exrate".PHP_EOL;
			// $jobs = DB::table('jobs')->where('queue','high')->get();
			// $jobs = DB::table('jobs')->get();
			// if ( count($jobs) > 2000) return;
			// \Log::info('jobs ' . count($jobs));
			$now = date('Y-m-d');
			$maxSeconds = 3;
			$timeRun = 60/$maxSeconds - 1;
			//60/$maxSeconds;
			for($i=1; $i <= $timeRun; $i++)
			{
				// $jobs = DB::table('jobs')->where('queue','high')->get();

				// if ( count($jobs) > 100) return;
				// echo $i.' ';
				// $stat1 = file('/proc/stat'); 
				// sleep(1); 
				// $stat2 = file('/proc/stat'); 
				// $info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0])); 
				// $info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0])); 
				// $dif = array(); 
				// $dif['user'] = $info2[0] - $info1[0]; 
				// $dif['nice'] = $info2[1] - $info1[1]; 
				// $dif['sys'] = $info2[2] - $info1[2]; 
				// $dif['idle'] = $info2[3] - $info1[3]; 
				// $total = array_sum($dif); 
				// $cpu = array(); 
				// foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 1);
				// echo "run". date('H-i') . $cpu['idle'];
				// if ($cpu['idle'] <= 40) return;
				$gameall = GameHelpers::GetAllGame(1);
				foreach($gameall as $game)
					if ($game->game_code==14 || $game->game_code==27 ||
					$game->game_code==28 || $game->game_code==7 || $game->game_code==12 || $game->game_code==9
					|| $game->game_code==10 || $game->game_code==11 || $game->game_code==29 
				//	|| $game->game_code==114 || $game->game_code==107 || $game->game_code==112
				//	|| ($game->game_code >= 721 && $game->game_code <= 739)
				//	|| $game->game_code==709 || $game->game_code==701 || $game->game_code==710 || $game->game_code==711 
					
					// || $game->game_code==314 || $game->game_code==414 || $game->game_code==514 || $game->game_code==614
					// || $game->game_code==307 || $game->game_code==407 || $game->game_code==507 || $game->game_code==607
					// || $game->game_code==309 || $game->game_code==409 || $game->game_code==509 || $game->game_code==609
					// || $game->game_code==310 || $game->game_code==410 || $game->game_code==510 || $game->game_code==610
					// || $game->game_code==311 || $game->game_code==411 || $game->game_code==511 || $game->game_code==611
					// || $game->game_code==329 || $game->game_code==429 || $game->game_code==529 || $game->game_code==629
					// || ($game->game_code >= 31 && $game->game_code <= 55)
					|| $game->game_code == 24
					|| $game->game_code == 25 || $game->game_code == 26 || $game->game_code == 27 || $game->game_code == 28
					)
					{
						// if(intval(date('H') )==18 && intval(date('i'))>=14 && intval(date('i') )<=30){
						// 	$rs = XoSoResult::where('location_id', 1)
                		// 		->where('date', $now)->first();

						// 	if ( !isset($rs) || !isset($rs['8']) )
						// 	{
						// 		// ok
						// 	}
						// 	else if ( $game->game_code >= 31 && $game->game_code <= 32 && $rs['8']>=1 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 33 && $game->game_code <= 38 && $rs['8']>=3 )
						// 	{
						// 		//lock
						// 		continue;
						// 	}
						// 	else if ( $game->game_code >= 39 && $game->game_code <= 42 && $rs['8']>=9 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 43 && $game->game_code <= 48 && $rs['8']>=13 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 49 && $game->game_code <= 51 && $rs['8']>=19 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 52 && $game->game_code <= 55 && $rs['8']>=22 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else
						// 		{
						// 			//ok
						// 		}
						// 	}

						if( (intval(date('H') ) ==18 && intval(date('i'))>=35) || intval(date('H') ) >18){
							//lock
							return;
						}

						// if ($game->status_cal == 0)
						{
							echo $game->game_code .' ';
							$lastestBettime = XoSoRecordHelpers::lastestBetTime($game->game_code);
							echo ' '.$lastestBettime[0].' ';
							if ($lastestBettime[0] == null || $game->lastestBet >= $lastestBettime[0]){
								// $game->lastestBet = $lastestBettime;
								// $game->save();
								continue;
								// echo 'save status_cal -1';
								// return;
							}
							// echo "insert job " . $game->game_code .' ';
							$game->lastestBet = $lastestBettime[0];
							$game->latestIDTemp = $lastestBettime[1];
							$game->save();
							echo ' push Update';
							Queue::pushOn("high",new CheckUpdateExchangeRate_v4_20240220($game));
							// Queue::pushOn("medium",new AutoLockNumber($game));
							// Queue::pushOn("low",new AutoLockNumberMerge($game));
							
						}	
					}
				if ($i<$timeRun)
					sleep($maxSeconds);
			}
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
		}
	}
}
