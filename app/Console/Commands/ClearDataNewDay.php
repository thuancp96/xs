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
use App\Helpers\LiveCasinoHelpers;
use App\Helpers\MinigameHelpers;
use App\Helpers\SabaHelpers;
use App\Helpers\Soccer7zballHelpers;
use \Cache;
use DateTime;

class ClearDataNewDay extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:cleardata';

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
        //DB::table('xoso_record')->delete();
        // DB::table('game_number')->delete();
		// while(true)
		{
			try{
				$listuser = UserHelpers::GetUserByRole(6);
				echo count($listuser);
				foreach ($listuser as $user) {
					if ((isset($user->bet) && $user->bet == 'sample') || (isset($user->rollback_money) && $user->rollback_money == 0))
					{
					}
					else{
						# code...
						echo $user->name.' ';
						$balance = 0;//Soccer7zballHelpers::CheckUsrBalance($user->name);
						$balanceMinigame = 0;//MinigameHelpers::CheckUsrBalance($user->name);
						// $balance = LiveCasinoHelpers::CheckUsrBalance($user->name);
						// $balanceSaba = SabaHelpers::CheckUsrBalance($user->name);
						// $inBetXS = XoSoRecordHelpers::GetByDate()
						$inBetXS = DB::table('xoso_record')
				        ->select(DB::raw('SUM(total_bet_money) AS sumbet'))
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', '<',100)
                        ->where('user_id', $user->id)
                        ->first();

						$inBet7z = DB::table('history_7zball_bet')
				        ->select(DB::raw('SUM(betamount) AS sumbet'))
                        ->whereNull('paid')
                        // ->where('createdate','<=',date('Y-m-d'). ' 11:00:00')
						// ->where('createdate','>=',date('Y-m-d'). ' 00:00:00')
                        ->where('username', $user->name)
                        ->first();

						echo $user->credit.' '.$balance.' '. $balanceMinigame . ' ' .$inBetXS->sumbet . ' | ';
						
						$user->remain = $user->credit - $balance - $balanceMinigame - $inBetXS->sumbet - $inBet7z->sumbet; //-$balanceSaba*1000
						$user->remain = $user->remain > 0 ? $user->remain : 0;
						$user->save();
					}
				}
				// break;
			}
			catch(\Exception $ex){
					// catch code
					echo $ex->getMessage();
			}
			sleep(30);
		}

		// while(true)
		// {
		// 	try{
		// 		$gameall = GameHelpers::GetAllGame(0);
		// 		foreach ($gameall as $game) {
		// 			# code...
		// 			// $game->totalbetnumber = null;
		// 			$game->totalbet = null;
		// 			$game->locknumber = null;
		// 			$game->locknumberauto = null;
		// 			$game->locksuper = null;
		// 			$game->save();

		// 			for($i=0;$i<10;$i++)
		// 				for($j=0;$j<10;$j++){
		// 					$bet_number = $i.$j;
		// 					Cache::put('checkLockSuper-'.$game->game_code.'-'.$bet_number, "", env('CACHE_TIME', 24*60));
		// 				}
		// 		}
		// 		// break;
		// 	}
		// 	catch(\Exception $e){
		// 			// catch code
		// 	}
		// 	sleep(30);
		// }

		// Game_Number::truncate();
		
		//Lock account
		try{
			$listuser = UserHelpers::GetUserUsing();

			// $date1 = "2007-03-24";
			// $date2 = "2009-06-26";

			foreach($listuser as $user){
				$diff = abs(time() - strtotime($user->latestlogin));
				$years = floor($diff / (365*60*60*24));
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
				$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

				// if ($years>1000)
				// 	break;
				if ($months >= 1){
					$user->lock=2;
					// $user->active=1;
					$user->save();
				}
				if ( $months>=2 || $years >=1){
					$user->lock=2;
					$user->active=1;
					$user->save();
				}
			}
		}catch(\Exception $e){
			echo $e;
		}

	}

}
