<?php namespace App\Commands;

use App\Commands\Command;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Commands\UpdateBetPriceAllUser;
use App\CustomerType_Game;
use App\Game;
use App\Helpers\UserHelpers;
use App\User;
use \Cache;
use \Queue;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\game_1478;
use App\Game_1533;
use App\Game_1561;
use App\Game_1650;
use App\Game_1698;

class CheckUpdateExchangeRate_v4_20240220 extends Command implements SelfHandling {

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

			echo 'CheckUpdateExchangeRate_v4_20240220 ' . $game_code;
			$TotalBetTodayByGameArr = XoSoRecordHelpers::calThauAdminGameLatest($game_code,$game->latestID,$game->latestIDTemp);
			
			//tong thau cua admin
			$TotalBetTodayByGame = $TotalBetTodayByGameArr['total9'] + ($game->latestID == 0 ? 0 : Cache::get('TotalBetTodayByGameThau-'.$game_code, 0));
			//XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);
			Cache::put('TotalBetTodayByGameThau-'.$game_code, $TotalBetTodayByGame, env('CACHE_TIME', 24*60));

			//tong cuoc toan bo cua admin
			$TotalBetTodayByGameOrg = $TotalBetTodayByGameArr['total8'] + ($game->latestID == 0 ? 0 : Cache::get('TotalBetTodayByGameOrg-'.$game_code, 0));
			Cache::put('TotalBetTodayByGameOrg-'.$game_code, $TotalBetTodayByGameOrg, env('CACHE_TIME', 24*60));
			//

			if ($game_code==9){
				$TotalBetTodayByGameOrg29 = Cache::get('TotalBetTodayByGameOrg-29', 0);
				$TotalBetTodayByGameOrg += $TotalBetTodayByGameOrg29;
			}
			if ($game_code==29){
				$TotalBetTodayByGameOrg9 = Cache::get('TotalBetTodayByGameOrg-9', 0);
				$TotalBetTodayByGameOrg += $TotalBetTodayByGameOrg9;
			}

			$totalBetAll = $TotalBetTodayByGameOrg;
			// if ($totalBetAll==0){
			// // 	$game->totalbet = $totalBetAll;
			// // 	// continue;
			// 	$game->status_cal = 0;
			// 	$game->save();
			// 	echo 'save status_cal -1';
			// 	return;
			// }
			
			// if (isset($game->totalbet) && intval($game->totalbet) == $totalBetAll){
			// 	$game->status_cal = 0;
			// 	$game->save();
			// 	echo 'save status_cal -1';
			// 	return;
			// } 

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
					$TotalBetTodayByNumberPre = ($game->latestID == 0 ? [0,0] : Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0]));
					$TotalBetTodayByNumber =  [$TotalBetTodayByGameArr['totalNumber'][$i*10+$j] + $TotalBetTodayByNumberPre[0],
												$TotalBetTodayByGameArr['totalNumberThau'][$i*10+$j] + $TotalBetTodayByNumberPre[1]];
					Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));
				}

			// for($i=0;$i<10;$i++)
			// 	for($j=0;$j<10;$j++){
			// 		$bet_number = $i.$j;
			// 		if ($game_code>=721 && $game_code<=739){
			// 			if ($bet_number != '00') break;
			// 		}
					
			// 		$TotalBetTodayByNumberPre = ($game->latestID == 0 ? [0,0] : Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0]));
			// 		$TotalBetTodayByNumber =  [$TotalBetTodayByNumberPre[0],$TotalBetTodayByNumberPre[1]];

			// 		if ($game_code==9){
			// 			$TotalBetTodayByNumberPre29 = Cache::get('TotalBetTodayByNumberThau-29-'.$bet_number, [0,0]);
			// 			$TotalBetTodayByNumber[1] += $TotalBetTodayByNumberPre29[1];
			// 			$TotalBetTodayByNumber[0] += $TotalBetTodayByNumberPre29[0];
			// 		}
			// 		if ($game_code==29){
			// 			$TotalBetTodayByNumberPre9 = Cache::get('TotalBetTodayByNumberThau-9-'.$bet_number, [0,0]);
			// 			$TotalBetTodayByNumber[1] += $TotalBetTodayByNumberPre9[1];
			// 			$TotalBetTodayByNumber[0] += $TotalBetTodayByNumberPre9[0];
			// 		}

			// 		$totalBetByNumber = $TotalBetTodayByNumber[1];
			// 		$totalBetByNumber1 = $TotalBetTodayByNumber[0];

			// 		$arrbetnumber[$i*10+$j]= $totalBetByNumber;
			// 		$arrbetnumber1[$i*10+$j]= $totalBetByNumber1;
			// 		if ($totalBetByNumber<=0){
			// 			continue;
			// 		}
					
			// 		if (isset($game->totalbet) && intval($game->totalbet) == $totalBetByNumber1){
			// 			continue;
			// 		} 
			// 		// $min=XoSoRecordHelpers::getMinCategory($game_code);
			// 		// $max=1;
			// 		// if ($totalBetByNumber-$min > $customerType->change_max_one){
			// 		// 	$game->locknumberauto .= ','.$bet_number;
			// 		// }
			// 		// $lockSuperNumber = XoSoRecordHelpers::checkLockSuper($game_code,$bet_number);
			// 		// $game->locksuper .= $lockSuperNumber . "||";
			// 		// echo 'UpdateBetPriceAllUser_v2';
			// 		// Queue::pushOn("high",new UpdateBetPriceAllUser_v2($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max));
			// 	}


			//xu ly tung super
			$users = UserHelpers::GetAllUserChildv2Admin("luk79", 274, 2);
			
			foreach ($users as $userSuper) {
				echo 'process super' . $userSuper->id . PHP_EOL;
				//get gamecode by super
				//using dynamic class
				$gameTableId = 'App\Game_'.$userSuper->id;
				// $ref = new ReflectionClass($gameTableId);
				$ref = new $gameTableId;
				$gameSuper = $ref::where('game_code',$game_code)->first();
				// $gameSuper = DB::table('game_'.$userSuper->id)->where('game_code',$game_code)->first();
				$TotalBetTodayByGameArrSuper = XoSoRecordHelpers::calThauSuperGameLatest($userSuper,$game_code,$game->latestID,$game->latestIDTemp);
				echo 'process super' . $userSuper->id .': '. count($TotalBetTodayByGameArrSuper) . PHP_EOL;
				//tong thau cua super
				$TotalBetTodayByGameSuper = $TotalBetTodayByGameArrSuper['total9'] + ($game->latestID == 0 ? 0 : Cache::get('TotalBetTodayByGameThau-'.$game_code.'-'.$userSuper->id, 0));
				//XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);
				Cache::put('TotalBetTodayByGameThau-'.$game_code.'-'.$userSuper, $TotalBetTodayByGameSuper, env('CACHE_TIME', 24*60));

				//tong cuoc toan bo cua super
				$TotalBetTodayByGameOrgSuper = $TotalBetTodayByGameArrSuper['total8'] + ($game->latestID == 0 ? 0 : Cache::get('TotalBetTodayByGameOrg-'.$game_code.'-'.$userSuper->id, 0));
				Cache::put('TotalBetTodayByGameOrg-'.$game_code.'-'.$userSuper->id, $TotalBetTodayByGameOrg, env('CACHE_TIME', 24*60));
				$this->processSuper($userSuper,$gameSuper,$game_code,$TotalBetTodayByGameOrgSuper,$TotalBetTodayByGameArrSuper,$TotalBetTodayByGameSuper);
			}
			

			$savetotalbetnumber = implode("|",$arrbetnumber);
			$savetotalbetnumber1 = implode("|",$arrbetnumber1);

			$game->totalbetnumber = $savetotalbetnumber;
			$game->totalbetnumber1 = $savetotalbetnumber1;

			$game->totalbet = $totalBetAll;
			$game->status_cal = 0;
			$game->latestID = $game->latestIDTemp;
			$game->save();
			echo 'save status_cal -1_end line 216';


		}catch(\Exception $ex){
			Log::info($ex->getMessage().'-'.$ex->getLine());
			echo $ex->getMessage().'-'.$ex->getLine();
			echo 'save status_cal -1';
			$game = $this->game;
			$game->status_cal = 0;
			$game->save();
		}
	}

	public function processSuper($userSuper,$game,$game_code,$TotalBetTodayByGameOrgSuper,$TotalBetTodayByGameArrSuper,$TotalBetTodayByGameSuper){
		if ($game_code==9){
			$TotalBetTodayByGameOrg29 = Cache::get('TotalBetTodayByGameOrg-29'.'-'.$userSuper->id, 0);
			$TotalBetTodayByGameOrgSuper += $TotalBetTodayByGameOrg29;
		}
		if ($game_code==29){
			$TotalBetTodayByGameOrg9 = Cache::get('TotalBetTodayByGameOrg-9'.'-'.$userSuper->id, 0);
			$TotalBetTodayByGameOrgSuper += $TotalBetTodayByGameOrg9;
		}

		$totalBetAll = $TotalBetTodayByGameOrgSuper;
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

		$customerType = Cache::remember('CustomerType_Game-'.$game_code.'-A'.'-'.$userSuper->id, env('CACHE_TIME_SHORT', 0), function () use ($game_code,$userSuper) {
			return 
			CustomerType_Game::where('game_id',$game_code)
				->where('created_user',$userSuper->id)
				->where('code_type','A')
				->first();
			});

		for($i=0;$i<10;$i++)
			for($j=0;$j<10;$j++){
				$bet_number = $i.$j;
				if ($game_code>=721 && $game_code<=739){
					if ($bet_number != '00') break;
				}
				$TotalBetTodayByNumberPre = ($game->latestID == 0 ? [0,0] : Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number.'-'.$userSuper->id, [0,0]));
				$TotalBetTodayByNumber =  [$TotalBetTodayByGameArrSuper['totalNumber'][$i*10+$j] + $TotalBetTodayByNumberPre[0],
											$TotalBetTodayByGameArrSuper['totalNumberThau'][$i*10+$j] + $TotalBetTodayByNumberPre[1]];
				Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number.'-'.$userSuper->id, $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));
			}

		for($i=0;$i<10;$i++)
			for($j=0;$j<10;$j++){
				$bet_number = $i.$j;
				if ($game_code>=721 && $game_code<=739){
					if ($bet_number != '00') break;
				}
				
				$TotalBetTodayByNumberPre = ($game->latestID == 0 ? [0,0] : Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number.'-'.$userSuper->id, [0,0]));
				$TotalBetTodayByNumber =  [$TotalBetTodayByNumberPre[0],$TotalBetTodayByNumberPre[1]];

				if ($game_code==9){
					$TotalBetTodayByNumberPre29 = Cache::get('TotalBetTodayByNumberThau-29-'.$bet_number.'-'.$userSuper->id, [0,0]);
					$TotalBetTodayByNumber[1] += $TotalBetTodayByNumberPre29[1];
					$TotalBetTodayByNumber[0] += $TotalBetTodayByNumberPre29[0];
				}
				if ($game_code==29){
					$TotalBetTodayByNumberPre9 = Cache::get('TotalBetTodayByNumberThau-9-'.$bet_number.'-'.$userSuper->id, [0,0]);
					$TotalBetTodayByNumber[1] += $TotalBetTodayByNumberPre9[1];
					$TotalBetTodayByNumber[0] += $TotalBetTodayByNumberPre9[0];
				}

				$totalBetByNumber = $TotalBetTodayByNumber[1];
				$totalBetByNumber1 = $TotalBetTodayByNumber[0];

				$arrbetnumber[$i*10+$j]= $totalBetByNumber;
				$arrbetnumber1[$i*10+$j]= $totalBetByNumber1;
				if ($totalBetByNumber<=0){
					echo "break";
					continue;
				}
				
				if (isset($game->totalbet) && intval($game->totalbet) == $totalBetByNumber1){
					echo "break";
					continue;
				} 
				$min=XoSoRecordHelpers::getMinCategorySuper($game_code,$userSuper);
				$max=1;
				if ($totalBetByNumber-$min > $customerType->change_max_one && $customerType->change_max_one > 0){
					$game->locknumberauto .= ','.$bet_number;
				}
				// $lockSuperNumber = XoSoRecordHelpers::checkLockSuper($game_code,$bet_number);
				// $game->locksuper .= $lockSuperNumber . "||";
				// echo 'UpdateBetPriceAllUser_v2';
				// Queue::pushOn("high",new UpdateBetPriceAllUser_v2($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGameSuper,$min,$max));
				echo "one UpdateBetPriceAllUser_v4";
				// ->where('lock_price',$lockPrice)
				if ($userSuper->lock_price == 0)
					Queue::pushOn("high",new UpdateBetPriceAllUser_v4($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGameSuper,$min,$max,$userSuper));

				if ($userSuper->lock_price == 2 && ($game_code!=12 && $game_code!=7 && $game_code!=14))
					Queue::pushOn("high",new UpdateBetPriceAllUser_v4($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGameSuper,$min,$max,$userSuper));
			}

		$savetotalbetnumber = implode("|",$arrbetnumber);
		$savetotalbetnumber1 = implode("|",$arrbetnumber1);

		$game->totalbetnumber = $savetotalbetnumber;
		$game->totalbetnumber1 = $savetotalbetnumber1;

		$game->totalbet = $totalBetAll;
		$game->status_cal = 0;
		$game->latestID = $game->latestIDTemp;
		$game->save();
		echo 'save status_cal -1_end';

		if ($game_code==9){
			$datetime = new DateTime('yesterday');
			$yesterday = $datetime->format('Y-m-d');
			$game29 = Game::where('active', 1)
					->where('game_code', 29)
					->select('games.*')
					->first();
			$game29->lastestBet = $yesterday;
			$game29->save();
		}
		if ($game_code==29){
			$datetime = new DateTime('yesterday');
			$yesterday = $datetime->format('Y-m-d');
			$game9 = Game::where('active', 1)
					->where('game_code', 9)
					->select('games.*')
					->first();
			$game9->lastestBet = $yesterday;
			$game9->save();
		}
	}
}
