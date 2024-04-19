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
use App\Helpers\SabaHelpers;
use \Cache;
use DateTime;

class LogoutLiveCasino extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:logoutandrecall';

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
        LiveCasinoHelpers::LogoutAndRecallAllMember();
		// SabaHelpers::LogoutAndRecallAllMember();
	}

}
