<?php namespace App\Console\Commands;

use App\Helpers\XoSoRecordHelpers;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\XoSo;
use App\Helpers\GameHelpers;
use Illuminate\Support\Facades\DB;
use App\Helpers\UserHelpers;
use App\Game_Number;
use \Cache;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\game_1478;
use App\Game_1533;
use App\Game_1561;
use App\Game_1650;
use App\Game_1698;

class ClearDataGameNumber extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:cleardatagamenumber';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update data new day';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		try{
			Cache::put('xacnhan_sokhoado_bot', false, env('CACHE_TIME', 12*60));
			$datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');
			$gameall = GameHelpers::GetAllGame(1);
			foreach ($gameall as $game)
			if ($game->game_code==14 || $game->game_code==27 ||
					$game->game_code==28 || $game->game_code==7 || $game->game_code==12 || $game->game_code==9
					|| $game->game_code==10 || $game->game_code==11 || $game->game_code==29 
					|| $game->game_code==114 || $game->game_code==107 || $game->game_code==112
					|| ($game->game_code >= 721 && $game->game_code <= 739)
					|| $game->game_code==709 || $game->game_code==701 || $game->game_code==710 || $game->game_code==711 
					
					|| $game->game_code==314 || $game->game_code==414 || $game->game_code==514 || $game->game_code==614
					|| $game->game_code==307 || $game->game_code==407 || $game->game_code==507 || $game->game_code==607
					|| $game->game_code==309 || $game->game_code==409 || $game->game_code==509 || $game->game_code==609
					|| $game->game_code==310 || $game->game_code==410 || $game->game_code==510 || $game->game_code==610
					|| $game->game_code==311 || $game->game_code==411 || $game->game_code==511 || $game->game_code==611
					|| $game->game_code==329 || $game->game_code==429 || $game->game_code==529 || $game->game_code==629
					// || ($game->game_code >= 31 && $game->game_code <= 55)
					|| $game->game_code == 24
					|| $game->game_code == 25 || $game->game_code == 26 || $game->game_code == 27 || $game->game_code == 28
					) {
				# code...
				// $game->totalbetnumber = null;
				
				$game->totalbet = null;
				$game->locknumber = null;
				$game->locknumberred = null;
				$game->locknumberauto = null;
				$game->locksuper = null;
				$game->totalbetnumber = null;
				$game->totalbetnumber1 = null;
				$game->status_cal = 0;
				$game->lastestBet = $yesterday;
				$game->save();
				$game_code = $game->game_code;
				echo $game_code .' '. Cache::get('TotalBetTodayByNumberThau-'.$game_code) .' ';
				Cache::put('TotalBetTodayByNumberThau-'.$game_code, 0, env('CACHE_TIME', 24*60));
				Cache::put('TotalBetTodayByGameOrg-'.$game_code, 0, env('CACHE_TIME', 24*60));
				$users = UserHelpers::GetAllUserChildv2Admin("luk79", 274, 2);

				foreach ($users as $key => $userSuper) {
					Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$userSuper->id, 0, env('CACHE_TIME', 24*60));
					Cache::put('TotalBetTodayByGameOrg-'.$game_code.'-'.$userSuper->id, 0, env('CACHE_TIME', 24*60));

					//using dynamic class
					$gameTableId = 'App\Game_'.$userSuper->id;
					$ref = new $gameTableId;
					$gameSup = $ref::where('game_code',$game_code)->first();

					$gameSup->totalbet = null;
					$gameSup->locknumber = null;
					$gameSup->locknumberred = null;
					$gameSup->locknumberauto = null;
					$gameSup->locksuper = null;
					$gameSup->totalbetnumber = null;
					$gameSup->totalbetnumber1 = null;
					$gameSup->status_cal = 0;
					$gameSup->lastestBet = $yesterday;
					$gameSup->save();
				}
				for($i=0;$i<10;$i++)
						for($j=0;$j<10;$j++){
							$bet_number = $i.$j;
							if ($game_code>=721 && $game_code<=739){
								if ($bet_number != '00') break;
							}
							Cache::forget('fetchOne789DataLockBlackNumber-'.$game_code);
							Cache::forget('fetchOne789DataRaw1-'.$game_code.'-'.$bet_number);
							Cache::forget('fetchOne789Data-'.$game_code.'-'.$bet_number);
							// $TotalBetTodayByNumber =  [$TotalBetTodayByGameArr['totalNumber'][$i*10+$j],$TotalBetTodayByGameArr['totalNumberThau'][$i*10+$j]];
							Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0], env('CACHE_TIME', 24*60));

							foreach ($users as $key => $userSuper) {
								Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number.'-'.$userSuper->id, [0,0], env('CACHE_TIME', 24*60));
							}

							// echo 'TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number;
							
							// echo 'TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number;
							// print_r($TotalBetTodayByNumber);
							// echo $bet_number;
							// print_r(Cache::get('TotalBetTodayByNumberThau-14-'.$bet_number,[0,0]));
							// break;
						}
				// for($i=0;$i<10;$i++)
				// 	for($j=0;$j<10;$j++){
				// 		$bet_number = $i.$j;
				// 		Cache::put('checkLockSuper-'.$game->game_code.'-'.$bet_number, "", env('CACHE_TIME', 24*60));
				// 	}
			}
			// break;
		}
		catch(\Exception $e){
				// catch code
				echo $e->getMessage() ." " .$e->getLine();
		}
		Game_Number::truncate();
	}

}
