<?php namespace App\Console\Commands;

use App\Helpers\LiveCasinoHelpers;
use App\Helpers\MinigameHelpers;
use App\Helpers\Soccer7zballHelpers;
use CURLFile;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Excel;
use Exception;
use Illuminate\Support\Facades\DB;

class GetMinigameHistory extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:GetMinigameHistory';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Get minigame history.';

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
	   
        MinigameHelpers::GetHistoryLoop();

	}

}
