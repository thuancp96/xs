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
use App\Helpers\XosobotHelpers;
use App\User;
use \Cache;
use DateTime;

class ClearMessageTelegram extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:clearmessagetelegram';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'clearmessagetelegram';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		echo date("Y-m-d H:i:s",(time() - 60 * 30)).PHP_EOL;
        $userInactive1H = User::where("updated_at","<=",date("Y-m-d H:i:s",(time() - 60 * 30)))->whereNotNull("chat_id")
							->where("lock_tele",0)
							// ->where("name","zzz113a")
							->get();
		// var_dump($userInactive1H);
		echo count($userInactive1H).PHP_EOL;
		$xsbot = new XosobotHelpers("","");
		foreach ($userInactive1H as $key => $user) {
			$user->lock_tele = 1;
			$user->save();
			$xsbot->clearMessageQueue($user->chat_id);
		}
	}

}
