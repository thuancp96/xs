<?php namespace App\Console;

use App\Console\Commands\Get7zBallHistory;
use App\Helpers\XoSo;
use App\Location;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DateTime;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
        \App\Console\Commands\Inspire::class,
		\App\Console\Commands\GetResultXoSo::class,
		\App\Console\Commands\GetResultXoSoMienNam::class,
		\App\Console\Commands\GetResultXoSoNew::class,
        \App\Console\Commands\ClearDataNewDay::class,
        \App\Console\Commands\ClearDataGameNumber::class,
		\App\Console\Commands\GenerateXoSoAo::class,
		\App\Console\Commands\TraThuongXoSoAo::class,
        \App\Console\Commands\LogDemo::class,
		\App\Console\Commands\ExtendGame::class,
		\App\Console\Commands\GetLiveXoSo::class,
		\App\Console\Commands\GetLiveThanTai::class,
		\App\Console\Commands\CacheClear::class,
		\App\Console\Commands\AutoUpdateExchangeRate::class,
		\App\Console\Commands\ExtendCodeType::class,
		\App\Console\Commands\GetLiveXoSoMienNam::class,
		\App\Console\Commands\GetLiveXoSoMienTrung::class,
		\App\Console\Commands\GetLiveKeno::class,
		\App\Console\Commands\ClearDataNewKeno::class,
		\App\Console\Commands\test_excel_export::class,
		\App\Console\Commands\clearCustomerTypeData::class,
		\App\Console\Commands\GetLiveCasinoHistory::class,
		\App\Console\Commands\LogoutLiveCasino::class,
		\App\Console\Commands\Get7zBallHistory::class,
		\App\Console\Commands\GetMinigameHistory::class,
		\App\Console\Commands\ClearMessageTelegram::class,
		\App\Console\Commands\AutofetchOne789::class,
		\App\Console\Commands\AutofetchLuk79::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		// $schedule->command('inspire')->hourly();
		// $schedule->command('get:xosonew')->dailyAt("18:35");
				
		// $schedule->command('get:getlivexoso')->dailyAt("18:13");
		
		// if ($slug==1)
		// {
            // $now = date('Y-m-d');
            // if ($now >= '2018-02-15' && $now <= '2018-02-18'){
                
            // }else{

		// $schedule->command('get:getlivexosomiennam')->dailyAt("16:13");
		// $schedule->command('get:getlivexosomientrung')->dailyAt("17:13");
		
		// $schedule->command('get:getlivexoso')->dailyAt("18:13");
		// $schedule->command('get:getlivexoso')->dailyAt("18:35");
		$schedule->command('get:getlivethantai')->dailyAt("18:15");

		//tra thuong
		// $schedule->command('get:xoso')->dailyAt("16:55");
		// $schedule->command('get:xoso')->dailyAt("17:55");
		$schedule->command('get:xoso')->dailyAt("18:36");
		// $schedule->command('get:xoso')->dailyAt("18:56");
		
		for($i=13; $i<40;$i++){
			$schedule->command('get:getlivexoso')->dailyAt("18:".$i);	
		}

		// $schedule->command('command:test_excel_export')->dailyAt("17:05");

		// $schedule->command('command:test_excel_export')->dailyAt("18:00");
		// $schedule->command('command:test_excel_export')->dailyAt("18:14");
		// $schedule->command('command:test_excel_export')->dailyAt("18:28");
		
		// $schedule->command('get:getlivekeno')->cron('1 * * * *'); // every 15 /hours
		// $schedule->command('get:getlivekeno')->cron('11 * * * *'); // every 15 /hours
		// $schedule->command('get:getlivekeno')->cron('21 * * * *'); // every 15 /hours
		// $schedule->command('get:getlivekeno')->cron('31 * * * *'); // every 15 /hours
		// $schedule->command('get:getlivekeno')->cron('41 * * * *'); // every 15 /hours
		// $schedule->command('get:getlivekeno')->cron('51 * * * *'); // every 15 /hours

		// $schedule->command('get:cleardatakeno')->cron('2 * * * *'); // every 15 /hours
		// $schedule->command('get:cleardatakeno')->cron('12 * * * *'); // every 15 /hours
		// $schedule->command('get:cleardatakeno')->cron('22 * * * *'); // every 15 /hours
		// $schedule->command('get:cleardatakeno')->cron('32 * * * *'); // every 15 /hours
		// $schedule->command('get:cleardatakeno')->cron('42 * * * *'); // every 15 /hours
		// $schedule->command('get:cleardatakeno')->cron('52 * * * *'); // every 15 /hours

		// $schedule->command('get:xoso')->cron('3 * * * *'); // every 13 /hours
		// $schedule->command('get:xoso')->cron('13 * * * *'); // every 13 /hours
		// $schedule->command('get:xoso')->cron('23 * * * *'); // every 13 /hours
		// $schedule->command('get:xoso')->cron('33 * * * *'); // every 13 /hours
		// $schedule->command('get:xoso')->cron('43 * * * *'); // every 13 /hours
		// $schedule->command('get:xoso')->cron('53 * * * *'); // every 13 /hours
		
		// 	}
        // }

		// $schedule->command('get:xoso')->dailyAt("18:35");
		// $schedule->command('get:xoso')->dailyAt("19:00");
        // $schedule->command('get:logoutandrecall')->dailyAt("10:40");
        $schedule->command('get:cleardata')->dailyAt("11:00");
        $schedule->command('get:cleardatagamenumber')->dailyAt("00:30");
        // $schedule->command('get:cleardatagamenumber')->dailyAt("18:15");

        // $schedule->command('log:demo')->cron('* * * * *');

		// $schedule->command('get:getlivekeno')->cron('11 * * * *'); // every 15 /hours

		// $schedule->command('get:generatexosoao')->cron('15 * * * *'); // every 15 /hours
		// $schedule->command('get:trathuongxosoao')->cron('35 * * * *'); // every 15 /hours
		
		$schedule->command('update-exrate')->cron('*/1 * * * *');
		$schedule->command('autofetchOne789')->cron('*/1 * * * *');
		$schedule->command('autofetchLuk79')->cron('*/1 * * * *');
		$schedule->command('get:clearmessagetelegram')->cron('*/5 * * * *');
		$schedule->command('command:Get7zBallHistory')->cron('*/1 * * * *');
		$schedule->command('command:GetMinigameHistory')->cron('*/1 * * * *');
    }

}
