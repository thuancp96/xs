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
use DateTime;

class TraThuongXoSoAo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:trathuongxosoao';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'tra thuong xo so ao';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        //$this->generate();
        \Log::info('i was @ trathuong when' . \Carbon\Carbon::now());
        $this->trathuong();
        Game_Number::where('code_type','=','107')->orWhere('code_type','=','114')->delete();
	}

	public function trathuong(){
		$now = date('Y-m-d');
        $hour = date('H');
        // $hour = 0;
        $xoso = new XoSo();
        if (date('H') == 0){
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');
		    $rs = $xoso->getKetQuaXSA(4,24,$yesterday);
            // $records1 = XoSoRecordHelpers::GetXSAByDate($yesterday,24);
            $records = XoSoRecordHelpers::GetXSAByDate($now,24);
            // $records = array_merge($records1,$records2);
        }else{
            $rs = $xoso->getKetQuaXSA(4,round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN),$now);
            // print_r($rs);
            $records = XoSoRecordHelpers::GetXSAByDate($now,round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN));    
        }
        echo(count($records));
        if (!isset($rs) || count($rs) < 1){
            echo "Chua co ket qua";
            return;
        }
        foreach ($records as $record)
        {
            if($record->total_bet_money == 0)
                continue;
            if($record['game_id']==122) //check de 6
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

            if($record['game_id']==123) //check de 7
            {

                $win = GameHelpers::CheckDe7($record['bet_number'],$rs,'');
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


            if($record['game_id']==114 || $record['game_id']==100) //check de
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

            if($record['game_id']==112) //check nhat
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

            if($record['game_id']==117) //check 3 cang
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


            if($record['game_id']==107) //check lo 2 so
            {
                echo 'tra thuong 107';
                $win = GameHelpers::CheckLo2($record['bet_number'],$rs);
                print_r($win);
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
            
            if($record['game_id']==108) //check lo 3 so
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

            if($record['game_id']==102) //check lo xien
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
            if($record['game_id']==109) //check lo xien 2
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

            if($record['game_id']==110) //check lo xien 3
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

            if($record['game_id']==111) //check lo xien 4
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

            if($record['game_id']==116) //check lo truot
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

            if($record['game_id']==119) //check lo truot 4
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

            if($record['game_id']==120) //check lo truot
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

            if($record['game_id']==121) //check lo truot
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

            if($record['game_id']==115) //check de truot
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
	}
	public function generate(){
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
        $xosoao->Giai_8= '0';
        $xosoao->date = date('Y-m-d');
        $xosoao->session = (round(date('H') / 2, 0, PHP_ROUND_HALF_DOWN));
        if (date('H') == 0){
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');
		    $xosoao->date = $yesterday;
            $xosoao->session = 13;
        }
		
		$xosoao->save();
        \Log::info('i was @ tao ket qua when' . \Carbon\Carbon::now());
	}
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
}


