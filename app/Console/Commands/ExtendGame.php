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
use App\User;
use App\XoSoRecord;
use DateTime;

class ExtendGame extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:extendgame';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Extend Game';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        // $user = User::where('id',1175)->first();
        // $gameid=14;
        // $number='03';
        // print_r(XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($gameid,$number,$user));
        // return;
        // echo XoSoRecordHelpers::checkLockSuper(14,'97');
        // return;
        // echo GameHelpers::LockNumberUser(12,true);
        // return;
        // $count = 0;
        // while(true){
		// 	XoSoRecordHelpers::ReportMessageTelegram($count++);
		// }
        // return;
        // $xsrecord = XoSoRecord::where("id",480)->first();
        // XoSoRecordHelpers::PaymentLottery($xsrecord);
        // $arrgamenew= array(721,722,723,724,725,726,727,728,729,730,731,732,733,734,735,736,737,738,739,700,701,702,709,710,711);
        // // $arrgamenew= array(700);
        // $arrgameclone= array(29,16,29,16,29,16,29,16);

        // // $arrgamenew= array(56);
        // // $arrgameclone= array(17);
        // $count11=0;
        // for ($i=0; $i <count($arrgamenew) ; $i++) { 

        //     $alluser = UserHelpers::GetAllUser1();
        //     foreach ($alluser as $eachuser) {
        //         # code...
        //         if ($eachuser->roleid == 6){

        //             $custom = CustomerType_Game::where('code_type',$eachuser->customer_type)->where('created_user',$eachuser->id)->where('game_id',$arrgamenew[$i])->get();
        //             if (isset($custom) && count($custom)>1){
        //                 $custom[1]->delete();
        //                 // echo 'delete';
        //                 $count11++;
        //             }
        //         }else{
        //             $custom = CustomerType_Game::where('code_type','A')->where('created_user',$eachuser->id)->where('game_id',$arrgamenew[$i])->get();
        //             if (isset($custom) && count($custom)>1){
        //                 $custom[1]->delete();
        //                 // echo 'delete';
        //                 $count11++;
        //             }

        //             $custom = CustomerType_Game_Original::where('code_type','A')->where('created_user',$eachuser->id)->where('game_id',$arrgamenew[$i])->get();
        //             if (isset($custom) && count($custom)>1){
        //                 $custom[1]->delete();
        //                 // echo 'delete';
        //                 $count11++;
        //             }
        //         }
        //     }
        // }

        // echo $count11;

        // return;
        // print_r(XoSoRecordHelpers::getRecordById(886985)[0]);
        // $record = XoSoRecordHelpers::getRecordById(886985);
        // print_r($record);
        // XoSoRecordHelpers::PaymentLottery($record);
        // return;
        // $datetime = new DateTime('yesterday');
        // $yesterday = $datetime->format('Y-m-d');
        // $now = $yesterday;
        // // $now = date('Y-m-d');
        // $xoso = new XoSo();
        // echo $now;
        // try{
        //     // try code
        //     $rs = $xoso->getKetQua(1,$now);
        // } 
        // catch(\Exception $e){
        //     // catch code
        // }
        // // print_r($rs);
        // $win = GameHelpers::CheckLoDe('188',$rs,"3_Cang");
        // echo $win;
        // return;
        // XoSoRecordHelpers::hoahong();
        // return;
        // $arrgamenew= array(114,107,108,109,110,111,112,115,116,119,120,121,122,123);
        // $arrgameclone= array(14,7,8,9,10,11,12,15,16,19,20,21,22,23);

        // $arrgamenew= array(314,307,308,309,310,311,317,315,352,353);
        // $arrgameclone= array(14,7,8,9,10,11,17,15,52,52);

        // $arrgamenew= array(317,315,352,353);
        // $arrgameclone= array(17,15,52,52);

        // $arrgamenew= array(414,407,408,409,410,411,417,415,452,453, 514,507,508,509,510,511,517,515,552,553 ,614,607,608,609,610,611,617,615,652,653);
        // $arrgameclone= array(14,7,8,9,10,11,17,15,52,52,14,7,8,9,10,11,17,15,52,52,14,7,8,9,10,11,17,15,52,52);
            // return;

            // UPDATE `games` SET location_id=50 WHERE location_id=2;

        // $arrgamenew= array(3001,3003,3005,3006,3007,3008,3011,3012,3015,3016,3017,3018,3021,3025,3026,3027,3028,3029,3030,3031,3032,3033,3034,3036,3037,3038,3039,3042,3043,3045,3046,3047,3048);
        // // $arrgamenew= array(700);
        // $arrgameclone= array(29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29,29);

        $arrgamenew= array(8003,8004,8005,8006);
        // $arrgamenew= array(700);
        $arrgameclone= array(8001);
        // $arrgamenew= array(56);
        // $arrgameclone= array(17);
        for ($i=0; $i <count($arrgamenew) ; $i++) { 
        // for ($i=31; $i <=55 ; $i++) { 
            # code...
            $game_id_new = $arrgamenew[$i];//$i;//
            // $game_id_clone = $arrgameclone[$i];//23;//
        
            // $gameO = GameHelpers::GetGameByCode($game_id_clone);
            $changeEX = 0;
            $changeOdd = 10;
            $max_point = 300000;
            $max_point_one = 1500000;
            $change_max = 0;
            $change_max_one = 12000000;
            // switch ($game_id_new) {

                // case 329:
                // case 429:
                // case 529:
                // case 629:
                //     $changeEX = 1000 - $gameO->exchange_rates;
                //     $changeOdd = 20000 - $gameO->exchange_rates;
                //     break;

                // case 316:
                // case 416:
                // case 516:
                // case 616:
                //     // $changeEX = 540 - $gameO->exchange_rates;
                //     $changeOdd = 14520 - $gameO->exchange_rates;
                //     break;

                // case 307:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 308:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 309:
                //     $changeEX = 690 - $gameO->exchange_rates;
                //     $changeOdd = 20000 - $gameO->exchange_rates;
                //     break;
                // case 310:
                //     $changeEX = 630 - $gameO->exchange_rates;
                //     $changeOdd = 100000 - $gameO->exchange_rates;
                //     break;
                // case 310:
                //     $changeEX = 540 - $gameO->exchange_rates;
                //     $changeOdd = 1000000 - $gameO->exchange_rates;
                //     break;

                // case 407:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 408:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 409:
                //     $changeEX = 690 - $gameO->exchange_rates;
                //     $changeOdd = 20000 - $gameO->exchange_rates;
                //     break;
                // case 410:
                //     $changeEX = 630 - $gameO->exchange_rates;
                //     $changeOdd = 100000 - $gameO->exchange_rates;
                //     break;
                // case 410:
                //     $changeEX = 540 - $gameO->exchange_rates;
                //     $changeOdd = 1000000 - $gameO->exchange_rates;
                //     break;


                // case 507:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 508:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 509:
                //     $changeEX = 690 - $gameO->exchange_rates;
                //     $changeOdd = 20000 - $gameO->exchange_rates;
                //     break;
                // case 510:
                //     $changeEX = 630 - $gameO->exchange_rates;
                //     $changeOdd = 100000 - $gameO->exchange_rates;
                //     break;
                // case 510:
                //     $changeEX = 540 - $gameO->exchange_rates;
                //     $changeOdd = 1000000 - $gameO->exchange_rates;
                //     break;

                // case 607:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 608:
                //     $changeEX = 14470 - $gameO->exchange_rates;
                //     break;
                // case 609:
                //     $changeEX = 690 - $gameO->exchange_rates;
                //     $changeOdd = 20000 - $gameO->exchange_rates;
                //     break;
                // case 610:
                //     $changeEX = 630 - $gameO->exchange_rates;
                //     $changeOdd = 100000 - $gameO->exchange_rates;
                //     break;
                // case 610:
                //     $changeEX = 540 - $gameO->exchange_rates;
                //     $changeOdd = 1000000 - $gameO->exchange_rates;
                //     break;
            //     case 700:
            //     case 721:
            //         case 722:
            //             case 723:
            //                 case 724:
            //                     case 725:
            //                         case 727:
            //             $changeEX = 975;
            //             $changeOdd = 1960;
            //             $max_point_one = 10000;
            //             $max_point = 50000;

            //             $change_max = 1;
            //             $change_max_one = 100000;
            //             break;

                

            // case 726:
            //     $changeEX = 975;
            //     $changeOdd = 9000;
            //     $max_point_one = 5000;
            //     $max_point = 25000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 728:
            //     case 730:
            //         $changeEX = 975;
            //         $changeOdd = 2300;
            //         $max_point_one = 10000;
            //         $max_point = 50000;

            //         $change_max = 1;
            //         $change_max_one = 100000;
            //         break;

            // case 729:
            //     $changeEX = 975;
            //     $changeOdd = 4300;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 731:
            //     case 732:
            //         case 733:
            //             case 734:
            //     $changeEX = 950;
            //     $changeOdd = 3700;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 735:
            //     case 739:
            //     $changeEX = 975;
            //     $changeOdd = 9200;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;
                
            // case 736:
            //     case 738:
            //     $changeEX = 975;
            //     $changeOdd = 4600;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 737:
            //     $changeEX = 975;
            //     $changeOdd = 2400;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 701:
            //     $changeEX = 950;
            //     $changeOdd = 3700;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 702:
            //     $changeEX = 950;
            //     $changeOdd = 3700;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 709:
            //     $changeEX = 800;
            //     $changeOdd = 12000;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 710:
            //     $changeEX = 753;
            //     $changeOdd = 45000;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;

            // case 711:
            //     $changeEX = 700;
            //     $changeOdd = 120000;
            //     $max_point_one = 10000;
            //     $max_point = 50000;

            //     $change_max = 1;
            //     $change_max_one = 100000;
            //     break;
                
            //     default:
            //         # code...
            //         break;
            // }
            $alluser = UserHelpers::GetAllUser1();
            foreach ($alluser as $eachuser) {
                # code...
                
                if ($eachuser->roleid == 6){
                    // $ctg = GameHelpers::GetOneGameByCusType($eachuser->customer_type,$eachuser->id,$game_id_clone);
                    // $ctgo = GameHelpers::GetOneGameParentByCusType($eachuser->customer_type,$eachuser->id,$game_id_clone);
                    
                    // if (isset($ctg))
                    {
                    $ctgn = new CustomerType_Game;
                    $ctgn->code_type = $eachuser->customer_type;
                    // $ctgn->exchange_rates = $ctg->exchange_rates + $changeEX;
                    // $ctgn->odds = $ctg->odds + $changeOdd;
                    // $ctgn->change_odds = $ctg->change_odds;
                    // $ctgn->change_ex = $ctg->change_ex;
                    // $ctgn->max_point = $ctg->max_point;
                    // $ctgn->max_point_one = $ctg->max_point_one;
                    // $ctgn->change_max = $ctg->change_max;
                    // $ctgn->change_max_one = $ctg->change_max_one;
                    $ctgn->exchange_rates = $changeEX;
                    $ctgn->odds = $changeOdd;
                    $ctgn->change_odds = 0;
                    $ctgn->change_ex = 1;
                    $ctgn->max_point = $max_point;
                    $ctgn->max_point_one = $max_point_one;
                    $ctgn->change_max = $change_max;
                    $ctgn->change_max_one = $change_max_one;

                    $ctgn->game_id = $game_id_new;
                    $ctgn->created_user = $eachuser->id;
                    $ctgn->save();
                    }
                    // else {echo $eachuser->customer_type.','.$eachuser->id.'-';}

                    // if (isset($ctgo))
                    {
                    $ctgon = new CustomerType_Game_Original;
                    $ctgon->code_type = $eachuser->customer_type;
                    $ctgon->game_id = $game_id_new;
                    $ctgon->created_user = $eachuser->id;
                    // $ctgon->exchange_rates = $ctgo->exchange_rates + $changeEX;
                    // $ctgon->odds = $ctgo->odds + $changeOdd;
                    // $ctgon->change_odds = $ctgo->change_odds;
                    // $ctgon->change_ex = $ctgo->change_ex;
                    // $ctgon->max_point = $ctgo->max_point;
                    // $ctgon->max_point_one = $ctgo->max_point_one;
                    // $ctgon->change_max = $ctgo->change_max;
                    // $ctgon->change_max_one = $ctgo->change_max_one;

                    $ctgon->exchange_rates = $changeEX;
                    $ctgon->odds = $changeOdd;
                    $ctgon->change_odds = 0;
                    $ctgon->change_ex = 1;
                    $ctgon->max_point = $max_point;
                    $ctgon->max_point_one = $max_point_one;
                    $ctgon->change_max = $change_max;
                    $ctgon->change_max_one = $change_max_one;

                    $ctgon->save();
                    }
                }else{
                    //A
                    // $actg = GameHelpers::GetOneGameByCusType('A',$eachuser->id,$game_id_clone);
                    // $actgo = GameHelpers::GetOneGameParentByCusType('A',$eachuser->id,$game_id_clone);

                    // if (isset($actg))
                    {
                        $actgn = new CustomerType_Game;
                        $actgn->code_type = 'A';
                        $actgn->game_id = $game_id_new;
                        $actgn->created_user = $eachuser->id;
                        // $actgn->exchange_rates = $actg->exchange_rates + $changeEX;
                        // $actgn->odds = $actg->odds + $changeOdd;
                        // $actgn->change_odds = $actg->change_odds;
                        // $actgn->change_ex = $actg->change_ex;
                        // $actgn->max_point = $actg->max_point;
                        // $actgn->max_point_one = $actg->max_point_one;
                        // $actgn->change_max = $actg->change_max;
                        // $actgn->change_max_one = $actg->change_max_one;

                        $actgn->exchange_rates = $changeEX;
                        $actgn->odds = $changeOdd;
                        $actgn->change_odds = 0;
                        $actgn->change_ex = 1;
                        $actgn->max_point = $max_point;
                        $actgn->max_point_one = $max_point_one;
                        $actgn->change_max = $change_max;
                        $actgn->change_max_one = $change_max_one;

                        $actgn->save();
                    }

                    // if (isset($actgo))
                    {
                        $actgon = new CustomerType_Game_Original;
                        $actgon->code_type = 'A';
                        $actgon->game_id = $game_id_new;
                        $actgon->created_user = $eachuser->id;
                        
                        // $actgon->exchange_rates = $actgo->exchange_rates + $changeEX;
                        // $actgon->odds = $actgo->odds + $changeOdd;
                        // $actgon->change_odds = $actgo->change_odds;
                        // $actgon->change_ex = $actgo->change_ex;
                        // $actgon->max_point = $actgo->max_point;
                        // $actgon->max_point_one = $actgo->max_point_one;
                        // $actgon->change_max = $actgo->change_max;
                        // $actgon->change_max_one = $actgo->change_max_one;

                        $actgon->exchange_rates = $changeEX;
                        $actgon->odds = $changeOdd;
                        $actgon->change_odds = 0;
                        $actgon->change_ex = 1;
                        $actgon->max_point = $max_point;
                        $actgon->max_point_one = $max_point_one;
                        $actgon->change_max = $change_max;
                        $actgon->change_max_one = $change_max_one;

                        $actgon->save();
                    }

                    //B
                    // $bctg = GameHelpers::GetOneGameByCusType('B',$eachuser->id,$game_id_clone);
                    // $bctgo = GameHelpers::GetOneGameParentByCusType('B',$eachuser->id,$game_id_clone);

                    // if (isset($bctg))
                    {
                        $bctgn = new CustomerType_Game;
                        $bctgn->code_type = 'B';
                        $bctgn->game_id = $game_id_new;
                        $bctgn->created_user = $eachuser->id;
                        // $bctgn->exchange_rates = $bctg->exchange_rates + $changeEX;
                        // $bctgn->odds = $bctg->odds + $changeOdd;
                        // $bctgn->change_odds = $bctg->change_odds;
                        // $bctgn->change_ex = $bctg->change_ex;
                        // $bctgn->max_point = $bctg->max_point;
                        // $bctgn->max_point_one = $bctg->max_point_one;
                        // $bctgn->change_max = $bctg->change_max;
                        // $bctgn->change_max_one = $bctg->change_max_one;

                        $bctgn->exchange_rates = $changeEX;
                        $bctgn->odds = $changeOdd;
                        $bctgn->change_odds = 0;
                        $bctgn->change_ex = 1;
                        $bctgn->max_point = $max_point;
                        $bctgn->max_point_one = $max_point_one;
                        $bctgn->change_max = $change_max;
                        $bctgn->change_max_one = $change_max_one;

                        $bctgn->save();
                    }

                    // if (isset($bctgo))
                    {
                        $bctgon = new CustomerType_Game_Original;
                        $bctgon->code_type = 'B';
                        $bctgon->game_id = $game_id_new;
                        $bctgon->created_user = $eachuser->id;
                        // $bctgon->exchange_rates = $bctgo->exchange_rates + $changeEX;
                        // $bctgon->odds = $bctgo->odds + $changeOdd;
                        // $bctgon->change_odds = $bctgo->change_odds;
                        // $bctgon->change_ex = $bctgo->change_ex;
                        // $bctgon->max_point = $bctgo->max_point;
                        // $bctgon->max_point_one = $bctgo->max_point_one;
                        // $bctgon->change_max = $bctgo->change_max;
                        // $bctgon->change_max_one = $bctgo->change_max_one;

                        $bctgon->exchange_rates = $changeEX;
                        $bctgon->odds = $changeOdd;
                        $bctgon->change_odds = 0;
                        $bctgon->change_ex = 1;
                        $bctgon->max_point = $max_point;
                        $bctgon->max_point_one = $max_point_one;
                        $bctgon->change_max = $change_max;
                        $bctgon->change_max_one = $change_max_one;

                        $bctgon->save();
                    }

                    //C
                    // $cctg = GameHelpers::GetOneGameByCusType('C',$eachuser->id,$game_id_clone);
                    // $cctgo = GameHelpers::GetOneGameParentByCusType('C',$eachuser->id,$game_id_clone);

                    // if (isset($cctg))
                    {
                        $cctgn = new CustomerType_Game;
                        $cctgn->code_type = 'C';
                        $cctgn->game_id = $game_id_new;
                        $cctgn->created_user = $eachuser->id;
                        // $cctgn->exchange_rates = $cctg->exchange_rates + $changeEX;
                        // $cctgn->odds = $cctg->odds + $changeOdd;
                        // $cctgn->change_odds = $cctg->change_odds;
                        // $cctgn->change_ex = $cctg->change_ex;
                        // $cctgn->max_point = $cctg->max_point;
                        // $cctgn->max_point_one = $cctg->max_point_one;
                        // $cctgn->change_max = $cctg->change_max;
                        // $cctgn->change_max_one = $cctg->change_max_one;

                        $cctgn->exchange_rates = $changeEX;
                        $cctgn->odds = $changeOdd;
                        $cctgn->change_odds = 0;
                        $cctgn->change_ex = 1;
                        $cctgn->max_point = $max_point;
                        $cctgn->max_point_one = $max_point_one;
                        $cctgn->change_max = $change_max;
                        $cctgn->change_max_one = $change_max_one;

                        $cctgn->save();
                    }

                    // if (isset($cctgo))
                    {
                        $cctgon = new CustomerType_Game_Original;
                        $cctgon->code_type = 'C';
                        $cctgon->game_id = $game_id_new;
                        $cctgon->created_user = $eachuser->id;
                        // $cctgon->exchange_rates = $cctgo->exchange_rates + $changeEX;
                        // $cctgon->odds = $cctgo->odds + $changeOdd;
                        // $cctgon->change_odds = $cctgo->change_odds;
                        // $cctgon->change_ex = $cctgo->change_ex;
                        // $cctgon->max_point = $cctgo->max_point;
                        // $cctgon->max_point_one = $cctgo->max_point_one;
                        // $cctgon->change_max = $cctgo->change_max;
                        // $cctgon->change_max_one = $cctgo->change_max_one;

                        $cctgon->exchange_rates = $changeEX;
                        $cctgon->odds = $changeOdd;
                        $cctgon->change_odds = 0;
                        $cctgon->change_ex = 1;
                        $cctgon->max_point = $max_point;
                        $cctgon->max_point_one = $max_point_one;
                        $cctgon->change_max = $change_max;
                        $cctgon->change_max_one = $change_max_one;

                        $cctgon->save();
                    }

                    //D
                    // $dctg = GameHelpers::GetOneGameByCusType('D',$eachuser->id,$game_id_clone);
                    // $dctgo = GameHelpers::GetOneGameParentByCusType('D',$eachuser->id,$game_id_clone);

                    // if (isset($dctg))
                    {
                        $dctgn = new CustomerType_Game;
                        $dctgn->code_type = 'D';
                        $dctgn->game_id = $game_id_new;
                        $dctgn->created_user = $eachuser->id;

                        // $dctgn->exchange_rates = $cctg->exchange_rates + $changeEX;
                        // $dctgn->odds = $cctg->odds + $changeOdd;
                        // $dctgn->change_odds = $cctg->change_odds;
                        // $dctgn->change_ex = $cctg->change_ex;
                        // $dctgn->max_point = $cctg->max_point;
                        // $dctgn->max_point_one = $cctg->max_point_one;
                        // $dctgn->change_max = $cctg->change_max;
                        // $dctgn->change_max_one = $cctg->change_max_one;

                        $dctgn->exchange_rates = $changeEX;
                        $dctgn->odds = $changeOdd;
                        $dctgn->change_odds = 0;
                        $dctgn->change_ex = 1;
                        $dctgn->max_point = $max_point;
                        $dctgn->max_point_one = $max_point_one;
                        $dctgn->change_max = $change_max;
                        $dctgn->change_max_one = $change_max_one;

                        $dctgn->save();
                    }

                    // if (isset($dctgo))
                    {
                        $dctgon = new CustomerType_Game_Original;
                        $dctgon->code_type = 'D';
                        $dctgon->game_id = $game_id_new;
                        $dctgon->created_user = $eachuser->id;
                        // $dctgon->exchange_rates = $cctgo->exchange_rates + $changeEX;
                        // $dctgon->odds = $cctgo->odds + $changeOdd;
                        // $dctgon->change_odds = $cctgo->change_odds;
                        // $dctgon->change_ex = $cctgo->change_ex;
                        // $dctgon->max_point = $cctgo->max_point;
                        // $dctgon->max_point_one = $cctgo->max_point_one;
                        // $dctgon->change_max = $cctgo->change_max;
                        // $dctgon->change_max_one = $cctgo->change_max_one;

                        $dctgon->exchange_rates = $changeEX;
                        $dctgon->odds = $changeOdd;
                        $dctgon->change_odds = 0;
                        $dctgon->change_ex = 1;
                        $dctgon->max_point = $max_point;
                        $dctgon->max_point_one = $max_point_one;
                        $dctgon->change_max = $change_max;
                        $dctgon->change_max_one = $change_max_one;

                        $dctgon->save();
                    }
                }
            }
        }
	}

}
