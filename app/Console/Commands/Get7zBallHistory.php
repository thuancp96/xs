<?php namespace App\Console\Commands;

use App\Helpers\LiveCasinoHelpers;
use App\Helpers\Soccer7zballHelpers;
use CURLFile;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class Get7zBallHistory extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:Get7zBallHistory';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get live history.';

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
	   
        Soccer7zballHelpers::GetHistoryLoop();

	}

}
