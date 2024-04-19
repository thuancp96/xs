<?php namespace App\Console\Commands;

use App\Helpers\XoSoRecordHelpers;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\XoSo;
use App\Helpers\GameHelpers;

class GetResultXoSoNew extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:xosonew';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Lay ket qua xo so';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        $now = date('Y-m-d');
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
            $rs = $xoso->getKetQua2today(1,$now);
        } 
        catch(\Exception $e){
            // catch code
        }
        
        while (count($rs)==0)
        {
            try{
            // try code
                $rs = $xoso->getKetQua2today(1,$now);
            } 
            catch(\Exception $e){
                // catch code
            }
        }
        $records = XoSoRecordHelpers::GetByDate($now);
        foreach ($records as $record)
        {
            if($record->total_bet_money == 0)
                continue;

            if($record['game_id']==22 || $record['game_id']==122) //check de 6
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

            if($record['game_id']==23 || $record['game_id']==123) //check de 7
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

            if($record['game_id']==14 || $record['game_id']==114) //check de
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

            if($record['game_id']==12 || $record['game_id']==112) //check nhat
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

            if($record['game_id']==17 || $record['game_id']==117) //check 3 cang
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


            if($record['game_id']==7 || $record['game_id']==107) //check lo 2 so
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
            
            if($record['game_id']==8 || $record['game_id']==108) //check lo 3 so
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

            if($record['game_id']==2 || $record['game_id']==102) //check lo xien
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
            if($record['game_id']==9 || $record['game_id']==109) //check lo xien 2
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

            if($record['game_id']==10 || $record['game_id']==110) //check lo xien 3
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

            if($record['game_id']==11 || $record['game_id']==111) //check lo xien 4
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

            if($record['game_id']==16 || $record['game_id']==116) //check lo truot
            {
                $win = GameHelpers::CheckLoTruot1($record['bet_number'],$rs);
                try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                } 
                catch(\Exception $e){
                    // catch code
                }
                if(count($win) == 1 && $win[0] > 0)
                    XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                else
                    if($win[0] < 0)
                        $record->total_win_money = 0-($record->total_bet_money*count($win));
            }

            if($record['game_id']==19 || $record['game_id']==119) //check lo truot 4
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

            if($record['game_id']==20 || $record['game_id']==120) //check lo truot
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
                        
                                $win = GameHelpers::CheckLoTruot($listbets[$i1].','.$listbets[$i2].','.$listbets[$i3].','.$listbets[$i4].','.$listbets[$i5].','.$listbets[$i6].','.$listbets[$i7].','.$listbets[$i8],$rs);
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

            if($record['game_id']==21 || $record['game_id']==121) //check lo truot
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
                        
                                $win = GameHelpers::CheckLoTruot($listbets[$i1].','.$listbets[$i2].','.$listbets[$i3].','.$listbets[$i4].','.$listbets[$i5].','.$listbets[$i6].','.$listbets[$i7].','.$listbets[$i8].','.$listbets[$i9].','.$listbets[$i10],$rs);
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

            if($record['game_id']==15 || $record['game_id']==115) //check de truot
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
                    $record->total_win_money -= $record->total_bet_money;
            }            
            $record->save();    
        }


		$this->comment("Lay ket qua ngay ".$now);
	}

}
