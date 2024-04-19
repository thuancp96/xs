<?php namespace App\Console\Commands;

use App\Commands\AutoLockNumber;
use App\Commands\AutoLockNumberMerge;
use App\Commands\CheckUpdateExchangeRate;
use App\Commands\CheckUpdateExchangeRate_v2;
use App\Commands\CheckUpdateExchangeRate_v3;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Commands\UpdateBetPriceAllUser;
use App\Helpers\XoSo;
use App\XoSoResult;
use \Queue;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AutofetchLuk79 extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'autofetchLuk79';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command AutofetchLuk79.';

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
		$now = date('Y-m-d');// 
        if ('2024-02-08' < $now && $now < '2024-02-13'){
            // $xoso = new XoSo();
            // $xoso->insertDump();
            return;
        }

		try{
			if( 
				(intval(date('H') ) ==18 && intval(date('i'))>=14) 
				|| (intval(date('H') ) ==17 && intval(date('i'))<30) 
				|| intval(date('H') ) >18
				|| intval(date('H') ) <17 

			){
				//lock
				return;
			}

			XoSo::setCheckLuk();
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
		}
	}
}
