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

class ClearDataNewKeno extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:cleardatakeno';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update data new keno';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        //DB::table('xoso_record')->delete();
        // DB::table('game_number')->delete();
		// while(true){
		// 	try{
		// 		$listuser = UserHelpers::GetUserByRole(6);
		// 		foreach ($listuser as $user) {
		// 			if (isset($user->bet) && $user->bet == 'sample')
		// 				continues;
		// 			# code...
		// 			$user->remain = $user->credit;
		// 			$user->save();
		// 		}
		// 		break;
		// 	}
		// 	catch(\Exception $e){
		// 			// catch code
		// 	}
		// 	sleep(30);
		// }

		while(true){
			try{
				$gameall = GameHelpers::GetAllGame(5);
				foreach ($gameall as $game) {
					# code...
					// $game->totalbetnumber = null;
					$game->totalbet = null;
					$game->save();
				}
				break;
			}
			catch(\Exception $e){
					// catch code
			}
			sleep(30);
		}

		Game_Number::where('code_type','>',699)
		->where('code_type','<',799)
		->where('code_type','<>',7)->delete();
		//Lock account
		// try{
		// 	$listuser = UserHelpers::GetUserUsing();

		// 	// $date1 = "2007-03-24";
		// 	// $date2 = "2009-06-26";

		// 	foreach($listuser as $user){
		// 		$diff = abs(time() - strtotime($user->latestlogin));
		// 		$years = floor($diff / (365*60*60*24));
		// 		$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
		// 		$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

		// 		// if ($years>1000)
		// 		// 	break;
		// 		if ($months >= 1){
		// 			$user->lock=3;
		// 			// $user->active=1;
		// 			$user->save();
		// 		}
		// 		if ( $months>=2 || $years >=1){
		// 			$user->lock=3;
		// 			$user->active=1;
		// 			$user->save();
		// 		}
		// 	}
		// }catch(\Exception $e){
		// 	echo $e;
		// }

	}

}
