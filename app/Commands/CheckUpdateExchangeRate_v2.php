<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Commands\UpdateBetPriceAllUser;
use App\CustomerType_Game;
use App\User;
use \Cache;
use \Queue;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CheckUpdateExchangeRate_v2 extends Command implements SelfHandling {

	use InteractsWithQueue, SerializesModels;
	protected $game;
	
	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($game)
	{
		//
		$this->game = $game;

		echo 'CheckUpdateExchangeRate';
		$game->status_cal = 1;
		$game->save();
		echo 'save status_cal 1';
		
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		try{

			$game = $this->game;

			$game_code = $game->game_code;
			if ($game_code >= 31 && $game_code <= 55)
				$game_code = 24;
			
			$TotalBetTodayByGameArr = XoSoRecordHelpers::calThauAdminGame($game_code);
			$TotalBetTodayByGame = $TotalBetTodayByGameArr['total9'];
			//XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);
			Cache::put('TotalBetTodayByGameThau-'.$game_code, $TotalBetTodayByGame, env('CACHE_TIME', 24*60));

			$TotalBetTodayByGameOrg = $TotalBetTodayByGameArr['total8'];
			Cache::put('TotalBetTodayByGameOrg-'.$game_code, $TotalBetTodayByGameOrg, env('CACHE_TIME', 24*60));

			$totalBetAll = $TotalBetTodayByGameOrg;
			if ($totalBetAll==0){
			// 	$game->totalbet = $totalBetAll;
			// 	// continue;
				$game->status_cal = 0;
				$game->save();
				echo 'save status_cal -1';
				return;
			}
			
			if (isset($game->totalbet) && intval($game->totalbet) == $totalBetAll){
				$game->status_cal = 0;
				$game->save();
				echo 'save status_cal -1';
				return;
			} 
			$game->totalbet = $totalBetAll;
			$game->save();
			// $arrbetnumber=array();
			// $arrbetnumber1=array();
			$arrbetnumber = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        	$arrbetnumber1 = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

			$savetotalbetnumber='';

			$game->locknumberauto = "";
			// $game->locksuper = "";

			// $TotalBetTodayByNumberAll = DB::table('xoso_record')
			// 	        ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
            //             ->orderBy('sumbet', 'desc')
            //             ->where('isDelete',false)
            //             ->where('date',date('Y-m-d'))
            //             ->where('game_id', $game_code)
            //             ->groupBy('bet_number')
            //             ->get();

			$customerType = Cache::remember('CustomerType_Game-'.$game_code.'-A'.'-'.'274', env('CACHE_TIME_SHORT', 0), function () use ($game_code) {
				return 
				CustomerType_Game::where('game_id',$game_code)
					->where('created_user',274)
					->where('code_type','A')
					->first();
				});

			for($i=0;$i<10;$i++)
				for($j=0;$j<10;$j++){
					$bet_number = $i.$j;
					if ($game_code>=721 && $game_code<=739){
						if ($bet_number != '00') break;
					}
					
					$TotalBetTodayByNumber =  [$TotalBetTodayByGameArr['totalNumber'][$i*10+$j],$TotalBetTodayByGameArr['totalNumberThau'][$i*10+$j]];
					// $TotalBetTodayByNumber = XoSoRecordHelpers::TotalBetTodayByNumberThau($game_code,$bet_number);
					Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));
					$totalBetByNumber = $TotalBetTodayByNumber[1];
					$totalBetByNumber1 = $TotalBetTodayByNumber[0];

					$arrbetnumber[$i*10+$j]= $totalBetByNumber;
					$arrbetnumber1[$i*10+$j]= $totalBetByNumber1;

					if ($totalBetByNumber<=0){	
						continue;
					}
					if (isset($game->totalbet) && intval($game->totalbet) == $totalBetByNumber1){
						continue;
					} 
					$min=1;
					$max=1;
					// if ($min==0 && $max==0){
					// 	continue;
					// }

					// $customerType =  CustomerType_Game::where('game_id',$game_code)
					// 	->where('created_user',274)
					// 	->where('code_type','A')
					// 	->first();
					if ($totalBetByNumber-$min > $customerType->change_max_one){
						$game->locknumberauto .= ','.$bet_number;
					}
					// $lockSuperNumber = XoSoRecordHelpers::checkLockSuper($game_code,$bet_number);
					// $game->locksuper .= $lockSuperNumber . "||";
					
					Queue::pushOn("high",new UpdateBetPriceAllUser_v2($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max));

				}

			$savetotalbetnumber = implode("|",$arrbetnumber);
			$savetotalbetnumber1 = implode("|",$arrbetnumber1);

			$game->totalbetnumber = $savetotalbetnumber;
			$game->totalbetnumber1 = $savetotalbetnumber1;

			$game->status_cal = 0;
			$game->save();
			echo 'save status_cal -1';
			
		}catch(\Exception $ex){
			Log::info($ex->getMessage().'-'.$ex->getLine());
			echo $ex->getMessage().'-'.$ex->getLine();
			echo 'save status_cal -1';
			$game = $this->game;
			$game->status_cal = 0;
			$game->save();
		}
	}
}
