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
use Illuminate\Support\Facades\Auth;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use DateTime;

class ExtendCodeType extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:extendcodetype';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Extend CodeType';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        $alluser = UserHelpers::GetAllUser1();
        echo count($alluser) . ' ';

        foreach ($alluser as $eachuser) {
            # code...
            
            if ($eachuser->roleid == 6){
                
            }else{
                //A
                $customerTypeGameList = CustomerType_Game::where('code_type','C')
                    ->where('created_user',$eachuser->id)
                    ->get();

                echo $eachuser->id .'-'.count($customerTypeGameList) . ' ';

                if ( count($customerTypeGameList) > 0 ){
                    foreach($customerTypeGameList as $customerTypeGameOne){
                        $actgn = new CustomerType_Game;
                        $actgn->code_type = 'D';
                        $actgn->game_id = $customerTypeGameOne->game_id;
                        $actgn->created_user = $customerTypeGameOne->created_user;
                        $actgn->exchange_rates = $customerTypeGameOne->exchange_rates;
                        $actgn->odds = $customerTypeGameOne->odds;
                        $actgn->change_odds = $customerTypeGameOne->change_odds;
                        $actgn->change_ex = $customerTypeGameOne->change_ex;
                        $actgn->max_point = $customerTypeGameOne->max_point;
                        $actgn->max_point_one = $customerTypeGameOne->max_point_one;
                        $actgn->change_max = $customerTypeGameOne->change_max;
                        $actgn->change_max_one = $customerTypeGameOne->change_max_one;
                        $actgn->save();
                    }
                }
                // break;

                $customerTypeGameOriginalList = CustomerType_Game_Original::where('code_type','C')
                    ->where('created_user',$eachuser->id)
                    ->get();

                echo $eachuser->id .'-'.count($customerTypeGameOriginalList) . ' ';

                if ( count($customerTypeGameOriginalList) > 0 ){
                    foreach($customerTypeGameOriginalList as $customerTypeGameOriginalOne){
                        $actgn = new CustomerType_Game_Original;
                        $actgn->code_type = 'D';
                        $actgn->game_id = $customerTypeGameOriginalOne->game_id;
                        $actgn->created_user = $customerTypeGameOriginalOne->created_user;
                        $actgn->exchange_rates = $customerTypeGameOriginalOne->exchange_rates;
                        $actgn->odds = $customerTypeGameOriginalOne->odds;
                        $actgn->change_odds = $customerTypeGameOriginalOne->change_odds;
                        $actgn->change_ex = $customerTypeGameOriginalOne->change_ex;
                        $actgn->max_point = $customerTypeGameOriginalOne->max_point;
                        $actgn->max_point_one = $customerTypeGameOriginalOne->max_point_one;
                        $actgn->change_max = $customerTypeGameOriginalOne->change_max;
                        $actgn->change_max_one = $customerTypeGameOriginalOne->change_max_one;
                        $actgn->save();
                    }
                }
            }
        }

	}

}
