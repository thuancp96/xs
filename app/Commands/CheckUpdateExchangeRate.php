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
use Illuminate\Support\Facades\DB;

class CheckUpdateExchangeRate extends Command implements SelfHandling {

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
			
			// $lastestBettime = XoSoRecordHelpers::lastestBetTime($game_code);
			// $TotalBetTodayByGame = XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);

			// Cache::put('TotalBetTodayByGameThau-'.$game_code, $TotalBetTodayByGame, env('CACHE_TIME', 24*60));

			// $totalBetAll = $TotalBetTodayByGame[0];
			// if ($totalBetAll==0){
			// 	$game->totalbet = $totalBetAll;
			// 	// continue;
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
			// $game->totalbet = $totalBetAll;
			
			// if ($game->lastestBet >= $lastestBettime){
			// 	$game->status_cal = 0;
			// 	$game->save();
			// 	echo 'save status_cal -1';
			// 	return;
			// }

			// $game->lastestBet = $lastestBettime;
			$savetotalbetnumber='';

			// $game->locknumberauto = "";
			// $game->locksuper = "";

			// $TotalBetTodayByNumberAll = DB::table('xoso_record')
			// 	        ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
            //             ->orderBy('sumbet', 'desc')
            //             ->where('isDelete',false)
            //             ->where('date',date('Y-m-d'))
            //             ->where('game_id', $game_code)
            //             ->groupBy('bet_number')
            //             ->get();

			$TotalBetTodayByGame = XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);

			Cache::put('TotalBetTodayByGameThau-'.$game_code, $TotalBetTodayByGame, env('CACHE_TIME', 24*60));

			for($i=0;$i<10;$i++)
				for($j=0;$j<10;$j++){
					$bet_number = $i.$j;
					if ($game_code>=721 && $game_code<=739){
						if ($bet_number != '00') break;
					}
					
					$TotalBetTodayByNumber = [0,0];
					// $TotalBetTodayByNumber = XoSoRecordHelpers::TotalBetTodayByNumberThau($game_code,$bet_number);

					// if ($TotalBetTodayByNumber[1]<=0){	
					// 	continue;
					// }

					// $totalBetByNumber = $TotalBetTodayByNumber[1];
					// $totalBetByNumber1 = $TotalBetTodayByNumber[0];

					// $arrbetnumber[$i*10+$j]= $totalBetByNumber;
					// $arrbetnumber1[$i*10+$j]= $totalBetByNumber1;

					// if ($totalBetByNumber<=0){	
					// 	continue;
					// }
					$min=1;
					$max=1;
					// if ($min==0 && $max==0){
					// 	continue;
					// }

					// $customerType =  CustomerType_Game::where('game_id',$game_code)
					// 	->where('created_user',274)
					// 	->where('code_type','A')
					// 	->first();
					// if ($totalBetByNumber-$min > $customerType->change_max_one){
					// 	$game->locknumberauto .= ','.$bet_number;
					// }
					// $lockSuperNumber = XoSoRecordHelpers::checkLockSuper($game_code,$bet_number);
					// $game->locksuper .= $lockSuperNumber . "||";
					
					Queue::pushOn("high",new UpdateBetPriceAllUser($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max));

				}

			// $savetotalbetnumber = implode("|",$arrbetnumber);
			// $savetotalbetnumber1 = implode("|",$arrbetnumber1);

			// $game->totalbetnumber = $savetotalbetnumber;
			// $game->totalbetnumber1 = $savetotalbetnumber1;

			if ($min==0 && $max==0){
				$game->totalbet = null;
			}
			$game->status_cal = 0;
			$game->save();
			echo 'save status_cal -1';
			
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
			echo 'save status_cal -1';
			$game = $this->game;
			$game->status_cal = 0;
			$game->save();
		}
	}
}
