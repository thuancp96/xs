<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\UserHelpers;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\XoSo;
use App\Helpers\GameHelpers;
use App\Helpers\NotifyHelpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;

use Illuminate\Http\Request;
use App\Commands\SendEmail;
use \Log;

class TestController extends Controller {

    public function randomGiai($chuso,$so){
		$giai = '';
		for ($i=0; $i < $so; $i++) { 
			for ($j=0; $j < $chuso; $j++) { 
			$giai.= rand(0,9);
			}
			if ($i != $so-1)
				$giai.=',';
		}
        return $giai;
	}

    public function clean()
    {
        NotifyHelpers::SendMailNotification('test');
        return;
        $countbetnumber=4;
        $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
        echo $Ank;
        return;
        
        Log::info("Request Cycle with Queues Begins");
        $this->dispatch(new SendEmail('test job'));
        Log::info("Request Cycle with Queues Ends");

        return;
        // DB::table('xoso_record')->delete();
        // DB::table('game_number')->delete();
        // $listuser = UserHelpers::GetUserByRole(6);
        // foreach ($listuser as $user) {
        // 	# code...
        // 	$user->remain = $user->credit;
        // 	$user->save();
        // }

        $xosoao = new \App\XoSoResult();
		$xosoao->location_id = 4;
		$xosoao->DB= $this->randomGiai(5,1);
		$xosoao->Giai_1= $this->randomGiai(5,1);
		$xosoao->Giai_2= $this->randomGiai(5,2);
		$xosoao->Giai_3= $this->randomGiai(5,6);
		$xosoao->Giai_4= $this->randomGiai(4,4);
		$xosoao->Giai_5= $this->randomGiai(4,6);
		$xosoao->Giai_6= $this->randomGiai(3,3);
		$xosoao->Giai_7= $this->randomGiai(2,4);
		$xosoao->date = date('Y-m-d');
		$xosoao->session = (date('H') / 2)+1;
        $xosoao->save();
        return view('frontend/time-zone');
    }

    public function hoahong(){
        XoSoRecordHelpers::hoahong();
        // GameHelpers::UpdateMeFromParentEX( UserHelpers::GetUserById(174) );
        return view('frontend/time-zone');
    }
    public function trathuong()
    {
        // \Log::info('i was @ trathuong');
        // return view('frontend/time-zone');
        try{
        $now = date('Y-m-d');
        $hour = 12;
        
        // $today              = strtotime($hour . ':00:00');
        $yesterday          = date('Y-m-d',strtotime("-1 days"));
        // $dayBeforeYesterday = strtotime('-1 day', $yesterday);
        // $hour = date('H');
        // $min = date('i');

        // $datetime = new DateTime('tomorrow');
        // $tomorrow = $datetime->format('Y-m-d');
        // if ($hour >18 || ($hour==18 && $min>=40 ) )
        // {
        //     $now = $tomorrow;
        // }
        $xoso = new XoSo();

        try{
            // try code
            $rs = $xoso->getKetQua(1,$now);
        } 
        catch(\Exception $e){
            // catch code
        }
        
        while (count($rs)==0)
        {
            try{
            // try code
                $rs = $xoso->getKetQua(1,$now);
            } 
            catch(\Exception $e){
                // catch code
            }
        }
        $records = XoSoRecordHelpers::GetByDate($now);
        // \Log::info('i was @ check trathuong');
        foreach ($records as $record)
        {
            if($record->total_bet_money == 0)
                continue;

            if($record['game_id']==22) //check de 6
            {

                $win = GameHelpers::CheckDe6($record['bet_number'],$rs);
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0 - $record->total_bet_money;
            }

            if($record['game_id']==23) //check de 7
            {

                $win = GameHelpers::CheckDe7($record['bet_number'],$rs);
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0 - $record->total_bet_money;
            }


            if($record['game_id']==14) //check de
            {

                $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"DB");
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0 - $record->total_bet_money;
            }

            if($record['game_id']==12) //check nhat
            {
                $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"Giai_1");
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if(count($win) > 0)
                     XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                 else
                    $record->total_win_money = 0-$record->total_bet_money;
            }

            if($record['game_id']==17) //check 3 cang
            {

                $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"3_cang");
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0-$record->total_bet_money;
            }


            if($record['game_id']==7) //check lo 2 so
            {
                $win = GameHelpers::CheckLo2($record['bet_number'],$rs);
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if(count($win) > 0)
                XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            else
                    $record->total_win_money = 0-$record->total_bet_money;
            }
            
            if($record['game_id']==8) //check lo 3 so
            {
                $win = GameHelpers::CheckLo3($record['bet_number'],$rs);
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if(count($win) > 0)
                XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0-$record->total_bet_money;
            }

            if($record['game_id']==2) //check lo xien
            {

                $win = GameHelpers::CheckLoXien($record['bet_number'],$rs);
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if(count($win) > 0)
                XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0-$record->total_bet_money;
            }
            $haswin = true;
            if($record['game_id']==9) //check lo xien 2
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) { 
                        $win = GameHelpers::CheckLoXien(trim($listbets[$i]).','.trim($listbets[$j]),$rs);
                        try{
                            // try code
                            //XoSoRecordHelpers::PaymentLottery($record);
                        } 
                        catch(\Exception $e){
                            // catch code
                        }
                        if(count($win) > 0){
                            // \Log::info('win was @ ' . implode(",",$win));
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                            }
                    }
                }
                if ($haswin == false)
                    $record->total_win_money -= $record->total_bet_money;
                else
                    XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==10) //check lo xien 3
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) {
                        for ($k=$j+1; $k < count($listbets); $k++) { 
                            $win = GameHelpers::CheckLoXien(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]),$rs);
                            
                            try{
                                // try code
                                //XoSoRecordHelpers::PaymentLottery($record);
                            } 
                            catch(\Exception $e){
                                // catch code
                            }
                            if(count($win) > 0){
                                    // \Log::info('win was @ ' . implode(",",$win));
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                            }
                        }
                    }
                }
                if ($haswin == false)
                    $record->total_win_money -= $record->total_bet_money;
                else
                    XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==11) //check lo xien 4
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) {
                        for ($k=$j+1; $k < count($listbets); $k++) { 
                            for ($l=$k+1; $l < count($listbets); $l++) { 
                                $win = GameHelpers::CheckLoXien(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]).','.trim($listbets[$l]),$rs);
                                // \Log::info('win was @ ' . implode(",",$win));
                                try{
                                    // try code
                                    //XoSoRecordHelpers::PaymentLottery($record);
                                } 
                                catch(\Exception $e){
                                    // catch code
                                }
                                if(count($win) > 0){
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                                }
                            }
                        }
                    }
                }
                if ($haswin == false)
                    $record->total_win_money -= $record->total_bet_money;
                else
                    XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==16) //check lo truot
            {
                $win = GameHelpers::CheckLoTruot1($record['bet_number'],$rs);
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if(count($win) == 1 && (int)$win[0] >= 0)
                    XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                else
                    if((int)$win[0] < 0)
                        $record->total_win_money = 0-($record->total_bet_money*count($win));
            }

            if($record['game_id']==19) //check lo truot 4
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) {
                        for ($k=$j+1; $k < count($listbets); $k++) { 
                            for ($l=$k+1; $l < count($listbets); $l++) { 
                                $win = GameHelpers::CheckLoTruot(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]).','.trim($listbets[$l]),$rs);
                                // \Log::info('listbet was @ ' . $listbets[$i] .' '.$listbets[$j].' '.$listbets[$k].' '.$listbets[$l]);
                                // \Log::info('win was @ ' . implode(",",$win));
                                
                                // \Log::info('betnumber was @ ' . $record['bet_number']);
                                
                                try{
                                    // try code
                                    //XoSoRecordHelpers::PaymentLottery($record);
                                } 
                                catch(\Exception $e){
                                    // catch code
                                }
                                if(count($win) > 0){
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                                }
                            
                        // $record->total_win_money -= $record->total_bet_money;
                            }
                        }
                    }
                }
                if ($haswin == false)
                    $record->total_win_money -= $record->total_bet_money;
                else
                    XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
                
                // $win = GameHelpers::CheckLoTruot($record['bet_number'],$rs);
                // try{
                //     // try code
                //     //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                // if(count($win) > 0)
                //     XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                // else
                //     $record->total_win_money = 0-$record->total_bet_money;
            }

            if($record['game_id']==20) //check lo truot
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                for ($i1=0; $i1 < count($listbets); $i1++) { 
                    for ($i2=$i1+1; $i2 < count($listbets); $i2++) {
                        for ($i3=$i2+1; $i3 < count($listbets); $i3++) {
                            for ($i4=$i3+1; $i4 < count($listbets); $i4++) {
                                for ($i5=$i4+1; $i5 < count($listbets); $i5++) {
                                    for ($i6=$i5+1; $i6 < count($listbets); $i6++) {
                                        for ($i7=$i6+1; $i7 < count($listbets); $i7++) {
                                            for ($i8=$i7+1; $i8 < count($listbets); $i8++) {
                        
                                $win = GameHelpers::CheckLoTruot($listbets[$i1]+','+$listbets[$i2]+','+$listbets[$i3]+','+$listbets[$i4]+','+$listbets[$i5]+','+$listbets[$i6]+','+$listbets[$i7]+','+$listbets[$i8],$rs);
                                try{
                                    // try code
                                    //XoSoRecordHelpers::PaymentLottery($record);
                                } 
                                catch(\Exception $e){
                                    // catch code
                                }
                                if(count($win) > 0){
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                                }

                            //     if(count($win) > 0)
                            //     XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                            // else
                            //     $haswin = false;
                        // $record->total_win_money -= $record->total_bet_money;
                            }
                        }
                    }
                                }
                            }
                        }
                    }
                }
                
                if ($haswin == false)
                    $record->total_win_money -= $record->total_bet_money;
                else
                    XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==21) //check lo truot
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                for ($i1=0; $i1 < count($listbets); $i1++) { 
                    for ($i2=$i1+1; $i2 < count($listbets); $i2++) {
                        for ($i3=$i2+1; $i3 < count($listbets); $i3++) {
                            for ($i4=$i3+1; $i4 < count($listbets); $i4++) {
                                for ($i5=$i4+1; $i5 < count($listbets); $i5++) {
                                    for ($i6=$i5+1; $i6 < count($listbets); $i6++) {
                                        for ($i7=$i6+1; $i7 < count($listbets); $i7++) {
                                            for ($i8=$i7+1; $i8 < count($listbets); $i8++) {
                                                for ($i9=$i8+1; $i9 < count($listbets); $i9++) {
                                                    for ($i10=$i9+1; $i10 < count($listbets); $i10++) {
                        
                                $win = GameHelpers::CheckLoTruot($listbets[$i1]+','+$listbets[$i2]+','+$listbets[$i3]+','+$listbets[$i4]+','+$listbets[$i5]+','+$listbets[$i6]+','+$listbets[$i7]+','+$listbets[$i8]+','+$listbets[$i9]+','+$listbets[$i10],$rs);
                                try{
                                    // try code
                                    //XoSoRecordHelpers::PaymentLottery($record);
                                } 
                                catch(\Exception $e){
                                    // catch code
                                }
                                if(count($win) > 0){
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                                }
                            }
                        }
                    }
                                }
                            }
                        }
                    }
                }
                    }
                }
                
                if ($haswin == false)
                    $record->total_win_money -= $record->total_bet_money;
                else
                    XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==15) //check de truot
            {
                $win = GameHelpers::CheckDeTruot($record['bet_number'],$rs,'');
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if($win != null)
                XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            else
                    $record->total_win_money = 0-$record->total_bet_money;
            }            
            $record->save();       
        }
        }catch(\Exception $e){
            // \Log::info('exception was @ ' . $e);
            return $e;
        }

        // $this->comment("Lay ket qua ngay ".$yesterday);

        return view('frontend/time-zone');
    }
    
    public function themphanchoi()
    {
        // $ctg = GameHelpers::GetAllGameCus(133);
        // $ctgo = GameHelpers::GetAllGameParentCus(133);

        // foreach($ctg as $ctgone)
        // {$ctgn = new CustomerType_Game;
        //     $ctgn = $ctgone;
        //     $ctgn->created_user = 274;
        //     $ctgn->save();
        // }
        // foreach($ctgo as $ctgone)
        // {
        //     $ctgon = new CustomerType_Game_Original;
        //     $ctgon = $ctgone;
        //     $ctgon->created_user = 274;
        //     $ctgon->save();
        // }


        // return view('frontend/time-zone');
        $game_id_new = 107;
        $alluser = UserHelpers::GetAllUser1();
        foreach ($alluser as $eachuser) {
            # code...
            if ($eachuser->roleid == 6){
                $ctg = GameHelpers::GetAllGameByCusType($eachuser->customer_type,$eachuser->id);
                $ctgo = GameHelpers::GetAllGameParentByCusType($eachuser->customer_type,$eachuser->id);

                $ctgn = new CustomerType_Game;
                $ctgn->code_type = $eachuser->customer_type;
                $ctgn->game_id = $game_id_new;
                $ctgn->created_user = $eachuser->id;
                $ctgn->save();

                $ctgon = new CustomerType_Game_Original;
                $ctgon->code_type = $eachuser->customer_type;
                $ctgon->game_id = $game_id_new;
                $ctgon->created_user = $eachuser->id;
                $ctgon->save();
            }else{
                //A
                $actg = GameHelpers::GetAllGameByCusType($eachuser->customer_type,$eachuser->id);
                $actgo = GameHelpers::GetAllGameParentByCusType($eachuser->customer_type,$eachuser->id);

                $actgn = new CustomerType_Game;
                $actgn->code_type = 'A';
                $actgn->game_id = $game_id_new;
                $actgn->created_user = $eachuser->id;
                $actgn->save();

                $actgon = new CustomerType_Game_Original;
                $actgon->code_type = 'A';
                $actgon->game_id = $game_id_new;
                $actgon->created_user = $eachuser->id;
                $actgon->save();

                //B
                $bctg = GameHelpers::GetAllGameByCusType($eachuser->customer_type,$eachuser->id);
                $bctgo = GameHelpers::GetAllGameParentByCusType($eachuser->customer_type,$eachuser->id);

                $bctgn = new CustomerType_Game;
                $bctgn->code_type = 'B';
                $bctgn->game_id = $game_id_new;
                $bctgn->created_user = $eachuser->id;
                $bctgn->save();

                $bctgon = new CustomerType_Game_Original;
                $bctgon->code_type = 'B';
                $bctgon->game_id = $game_id_new;
                $bctgon->created_user = $eachuser->id;
                $bctgon->save();

                //C
                $cctg = GameHelpers::GetAllGameByCusType($eachuser->customer_type,$eachuser->id);
                $cctgo = GameHelpers::GetAllGameParentByCusType($eachuser->customer_type,$eachuser->id);

                $cctgn = new CustomerType_Game;
                $cctgn->code_type = 'C';
                $cctgn->game_id = $game_id_new;
                $cctgn->created_user = $eachuser->id;
                $cctgn->save();

                $cctgon = new CustomerType_Game_Original;
                $cctgon->code_type = 'C';
                $cctgon->game_id = $game_id_new;
                $cctgon->created_user = $eachuser->id;
                $cctgon->save();

            }
        }
    }
        
}