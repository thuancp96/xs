<?php namespace App\Console\Commands;

use App\Helpers\XoSoRecordHelpers;
use App\User;
use CURLFile;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Excel;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class test_excel_export extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:test_excel_export';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
// 		$request =  new stdClass;

// 		$request->game_code = 14;
// 		$request->choices = [
// 			["name" => "00", "value" => 1, "exchange" => 1000, "total" => 1000]
// 		];
// 		// array_push($request->choice,);
// 		$request->odds = 70000;
// 		$request->ipaddr = 'undetected';
// 		// ipaddr: undetected
// // 	    choices[0][name]: 00
// // choices[0][value]: 1
// // choices[0][exchange]: 1000
// // choices[0][total]: 1000
// // game_code: 14
// // odds: 70000
// 		print_r($request->choices[0]);
// 		// print_r(User::where('id',1521)->first());
// 		// echo count($request->choices);
// 		try{
// 			for($i=0; $i<=1000; $i++)
// 			echo XoSoRecordHelpers::InsertXosoRecord($request,User::where('id',1521)->first());
// 		}catch(Exception $ex){
// 			echo $ex->getLine();
// 		}
		
// 	    // XoSoRecordHelpers::hoahong(28497);
// 		return;
	   // var_dump($arrName);
		// $xs = new XoSoRecordHelpers();
		// XoSoRecordHelpers::ReportTelegram();
		// return;
		$game_record = DB::table('games')
            ->where('location_id', 1)
            ->where('active', 1)
            ->get();
        $minIsReport = 0;
// 		while(true){
// 			if (date("i") == 5 || date("i") >= 14)
        $arr = [];
        foreach ($game_record as $record) {
            if(!in_array(substr($record->close, -2), $arr, true)){
                array_push($arr,substr($record->close, -2));
            }
        }
        
        sort($arr);
        
        while(true){
            $current = (int)$arr[0];
            if(date("i") >= $current){
                
                
                
                $game_record_by_min = DB::table('games')
                ->where('location_id', 1)
                ->where('active', 1)
                ->where('close', '18:'. $current)
                ->whereNotIn('id', [9,10,11,68,18,2]) // xiên + lô live
                ->get();
                
                XoSoRecordHelpers::ReportMessageTelegramByName($game_record_by_min, "-720602361");
                
                var_dump(date('h:i:s'));
                
                
                array_shift($arr);
                if(count($arr) == 0){
                    break;
                }
                $current = (int)$arr[0];
                
               
            }
        }
            
            // var_dump($arr);
				// XoSoRecordHelpers::ReportMessageTelegramToday();
			
// 			if (date("i") >= 5 && date("i") <= 14)
// 				sleep(60);
// 			else
// 				sleep(25);
				
// 			if (date("i") >= 29) return;
// 		}
		
		// Create CURLFile
		// $filename = storage_path('excel/exports')."/"."xs20220630_122500.xlsx";
		// $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
		// $cFile = new CURLFile($filename, 'text/plain', 'file');
	}

}
