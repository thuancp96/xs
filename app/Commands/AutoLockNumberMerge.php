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

class AutoLockNumberMerge extends Command implements SelfHandling {

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
		// $game->status_cal = 1;
		// $game->save();
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
			
			$TotalBetTodayByGame = Cache::get('TotalBetTodayByGameThau-'.$game_code,[0,0]);
			// $TotalBetTodayByGame = XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);
			$totalBetAll = $TotalBetTodayByGame[0];
			
			if ($totalBetAll==0){
				// $game->totalbet = $totalBetAll;
				// continue;
				// $game->status_cal = 0;
				// $game->save();
				// echo 'save status_cal';
				// $game->status_cal -= 1;
				// $game->save();
				return;
			}
			
			if (isset($game->totalbet) && intval($game->totalbet) == $totalBetAll){
				// $game->status_cal = 0;
				// $game->save();
				// echo 'save status_cal';
				// $game->status_cal -= 1;
				// $game->save();
				// return;
			} 

			// $game->totalbet = $totalBetAll;
			// $game->save();
			$savetotalbetnumber='';
			$arrbetnumber=array();
			$arrbetnumber1=array();

			$min = 999999;
			$max = 0;

			// $game->locknumberauto = "";
			$game->locksuper = "";
			// $customerType =	CustomerType_Game::where('game_id',$game_code)
			// 		->where('created_user',274)
			// 		->where('code_type','A')
			// 		->first();
			for($i=0;$i<10;$i++)
				for($j=0;$j<10;$j++){
					$bet_number = $i.$j;
					if ($game_code>=721 && $game_code<=739){
						if ($bet_number != '00') break;
					}
					// \Log::info('AutoLockNumber ' .$game_code.'-'.$bet_number);
					// $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number,[0,0]);
					// $TotalBetTodayByNumber = XoSoRecordHelpers::TotalBetTodayByNumberThau($game_code,$bet_number);
					// XoSoRecordHelpers::TotalBetTodayByNumberThau($game_code,$bet_number);

					// $totalBetByNumber = $TotalBetTodayByNumber[1];
					// $totalBetByNumber1 = $TotalBetTodayByNumber[0];

					// $arrbetnumber[$i*10+$j]= $totalBetByNumber;
					// $arrbetnumber1[$i*10+$j]= $totalBetByNumber1;

					// if ($totalBetByNumber<=0){
					// 	continue;
					// }

					// if (isset($game->totalbet) && intval($game->totalbet) == $totalBetByNumber1){
					// 	continue;
					// } 
					// $min=1;
					// $max=1;
					// if ($min==0 && $max==0){
					// 	continue;
					// }
					
					// $customerType =	Cache::remember('CustomerType_Game-274-'.$game_code,10, function() use ($game_code) {
					// 		    return CustomerType_Game::where('game_id',$game_code)
					// 			->where('created_user',274)
					// 			->where('code_type','A')
					// 			->first();
					// });

					// if ($totalBetByNumber-$min > $customerType->change_max_one){
						// $game->locknumberauto .= ','.$bet_number;
					// }
					// $lockSuperNumber = XoSoRecordHelpers::checkLockSuper($game_code,$bet_number);
					$lockSuperNumber = Cache::get('checkLockSuper-'.$game_code.'-'.$bet_number,$game_code.'-');
					if ($lockSuperNumber == $game_code.'-' || $lockSuperNumber == ''){
						continue;
					}
					// Cache::put('checkLockSuper-'.$this->game_code.'-'.$this->bet_number, $lockSuperNumber, env('CACHE_TIME', 24*60));
					// Queue::pushOn("high",new checkLockSuper($game_code,$bet_number));
					$game->locksuper .= $lockSuperNumber . "||";
				}
			// $savetotalbetnumber = implode("|",$arrbetnumber);
			// $savetotalbetnumber1 = implode("|",$arrbetnumber1);

			// $game->totalbetnumber = $savetotalbetnumber;
			// $game->totalbetnumber1 = $savetotalbetnumber1;

			// if ($min==0 && $max==0){
				// $game->totalbet = null;
			// }
			// $game->status_cal -= 1;
			if ($game->locksuper != ""){
				$game->save();
			}
		}catch(\Exception $ex){
			// $game = $this->game;
			// $game->status_cal -= 1;
			// $game->save();
			\Log::info($ex->getMessage().'-'.$ex->getLine());
		}
	}
}
