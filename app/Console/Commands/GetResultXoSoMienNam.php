<?php namespace App\Console\Commands;

use App\Helpers\XoSoRecordHelpers;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\XoSo;
use App\Helpers\GameHelpers;
use App\Helpers\NotifyHelpers;

class GetResultXoSoMienNam extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:xosomiennam';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'tra thuong xo so mien nam';

    public function fullKq($result)
    {
        if(count($result)>0)
        {
            $countkq = 0;

            if (is_numeric($result->DB)){
                $countkq++;
            }
            if (is_numeric($result->Giai_1)){
                $countkq++;
            }

            foreach($this->SplitStringToArray($result->Giai_2) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result->Giai_3) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result->Giai_4) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result->Giai_5) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result->Giai_6) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result->Giai_7) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            
            if ( $countkq == 27 && date('i') >= 32 ) return true;
            else return false;
        }
        return false;
    }
    
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        
        $now = date('Y-m-d');
        $hour = date('H');
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
            
            if ($hour==16){
                // Tra thuong mien nam
                $rs = $xoso->getKetQua(21,$now);
                $records = XoSoRecordHelpers::GetMienNamTrungByDate(21,$now);
                $this->trathuong($records,$rs,$now);

                $rs = $xoso->getKetQua(22,$now);
                $records = XoSoRecordHelpers::GetMienNamByDate(22,$now);
                $this->trathuong($records,$rs,$now);
            }

            if ($hour==17){
                // Tra thuong mien trung
                $rs = $xoso->getKetQua(31,$now);
                $records = XoSoRecordHelpers::GetMienNamTrungByDate(31,$now);
                $this->trathuong($records,$rs,$now);

                $rs = $xoso->getKetQua(32,$now);
                $records = XoSoRecordHelpers::GetMienNamByDate(32,$now);
                $this->trathuong($records,$rs,$now);
            }
        } 
        catch(\Exception $e){
            // catch code
        }
        
        // while ($this->fullKq($rs))
        // {
        //     try{
        //     // try code
        //         $rs = $xoso->getKetQua(1,$now);
        //     } 
        //     catch(\Exception $e){
        //         // catch code
        //     }
        // }
        
        
        
        // NotifyHelpers::SendMailNotification('Tra thuong hoan thanh!');
    }
    
    private function trathuong($records,$rs,$now){
        try{
        $totalBetMoney = 0;
        $totalWinMoney = 0;
        
        foreach ($records as $record)
        {
            if($record->total_bet_money == 0)
                continue;
            $totalBetMoney+=$record->total_bet_money;
            // if($record['game_id']==22 || $record['game_id']==122) //check de 6
            // {

            //     $win = GameHelpers::CheckDe6($record['bet_number'],$rs);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //     $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            if($record['game_id']==352 || $record['game_id']==452) //check de 7
            {

                $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,'7');
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0 - $record->total_bet_money;
            }

            if($record['game_id']==353 || $record['game_id']==453) //check de 8
            {

                $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,'8');
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0 - $record->total_bet_money;
            }

            // if($record['game_id']==25) //check dau than tai
            // {

            //     $win = GameHelpers::CheckDauThanTai($record['bet_number'],$rs,'than_tai');
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==26) //check duoi than tai
            // {

            //     $win = GameHelpers::CheckDuoiThanTai($record['bet_number'],$rs,'than_tai');
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==27) //check dau de
            // {

            //     $win = GameHelpers::CheckDauLoDe($record['bet_number'],$rs,'DB');
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==28) //check dau nhat
            // {

            //     $win = GameHelpers::CheckDauLoDe($record['bet_number'],$rs,'Giai_1');
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==31) //check de 2.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'2',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==32) //check de 2.2
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'2',2);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==33) //check de 3.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==34) //check de 3.2
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',2);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==35) //check de 3.3
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',3);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==36) //check de 3.4
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',4);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==37) //check de 3.5
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',5);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==38) //check de 3.6
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',6);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==39) //check de 4.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==40) //check de 4.2
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',2);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==41) //check de 4.3
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',3);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==42) //check de 4.4
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',4);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==43) //check de 5.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==44) //check de 5.2
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',2);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==45) //check de 5.3
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',3);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==46) //check de 5.4
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',4);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==47) //check de 5.5
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',5);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==48) //check de 5.6
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',6);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==49) //check de 6.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'6',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==50) //check de 6.2
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'6',2);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==51) //check de 6.3
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'6',3);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==352) //check de 7.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==353) //check de 8.1
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'8',1);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==53) //check de 7.2
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',2);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==54) //check de 7.3
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',3);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            // if($record['game_id']==55) //check de 7.4
            // {

            //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',4);
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0 - $record->total_bet_money;
            // }

            if($record['game_id']==314 || $record['game_id']==414) //check de
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0 - $record->total_bet_money;
            }

            // if($record['game_id']==12 || $record['game_id']==112) //check nhat
            // {
            //     $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"Giai_1");
            //     try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     } 
            //     catch(\Exception $e){
            //         // catch code
            //     }
            //     if(count($win) > 0)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //      else
            //         $record->total_win_money = 0-$record->total_bet_money;
            // }

            if($record['game_id']==317 || $record['game_id']==417) //check 3 cang
            {

                $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"3_Cang");
                // try{
                    // try code
                    //XoSoRecordHelpers::PaymentLottery($record);
                // } 
                // catch(\Exception $e){
                //     // catch code
                // }
                if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0-$record->total_bet_money;
            }

            // if($record['game_id']==56) //check 3 cang nhat
            // {

            //     $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"3_Cang_nhat");
            //     // try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     if($win != null)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0-$record->total_bet_money;
            // }

            if($record['game_id']==307 || $record['game_id']==407) //check lo 2 so
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            else
                    $record->total_win_money = 0-$record->total_bet_money;
            }

            // if($record['game_id']==18) //check lo live 2 so
            // {
            //     $win = GameHelpers::CheckLoLive2($record['bet_number'],$record['xien_id'],$rs);
            //     try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     } 
            //     catch(\Exception $e){
            //         // catch code
            //     }
            //     if(count($win) > 0)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            // else
            //         $record->total_win_money = 0-$record->total_bet_money;
            // }
            
            if($record['game_id']==308 || $record['game_id']==408) //check lo 3 so
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                else
                    $record->total_win_money = 0-$record->total_bet_money;
            }

            // if($record['game_id']==2 || $record['game_id']==102) //check lo xien
            // {

            //     $win = GameHelpers::CheckLoXien($record['bet_number'],$rs);
            //     try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     } 
            //     catch(\Exception $e){
            //         // catch code
            //     }
            //     if(count($win) > 0)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            //     else
            //         $record->total_win_money = 0-$record->total_bet_money;
            // }

            // $haswin = true;
            // if($record['game_id']==29) //check lo xien 29
            // {
            //     $haswin = false;
            //     $countwin = 0;
            //     $winnumber="";
            //     $listbets = explode(",",str_replace(" ","",$record['bet_number']));
            //     for ($i=0; $i < count($listbets); $i++) { 
            //         for ($j=$i+1; $j < count($listbets); $j++) { 
            //             $win = GameHelpers::CheckLoXienNhay(trim($listbets[$i]).','.trim($listbets[$j]),$rs);
            //             try{
            //                 // try code
            //                 //XoSoRecordHelpers::PaymentLottery($record);
            //             } 
            //             catch(\Exception $e){
            //                 // catch code
            //             }
            //             if(count($win) > 0){
            //                 // \Log::info('win was @ ' . implode(",",$win));
            //                         $countwin++;
            //                         $haswin = true;
            //                         $winnumber.='|'.implode(",",$win);
            //                 }
            //         }
            //     }
            //     if ($haswin == false)
            //         $record->total_win_money -= $record->total_bet_money;
            //     else
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            // }

            $haswin = true;
            if($record['game_id']==309 || $record['game_id']==409) //check lo xien 2
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==310 || $record['game_id']==410) //check lo xien 3
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            if($record['game_id']==311 || $record['game_id']==411) //check lo xien 4
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            }

            // if($record['game_id']==16 || $record['game_id']==116) //check lo truot
            // {
            //     $win = GameHelpers::CheckLoTruot1($record['bet_number'],$rs);
            //     try{
            //         // try code
            //         //XoSoRecordHelpers::PaymentLottery($record);
            //     } 
            //     catch(\Exception $e){
            //         // catch code
            //     }
            //     if(count($win) == 1 && $win[0] > 0)
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            //     else
            //         if($win[0] < 0)
            //             $record->total_win_money = 0-($record->total_bet_money*count($win));
            // }

            // if($record['game_id']==19 || $record['game_id']==119) //check lo truot 4
            // {
            //     $haswin = false;
            //     $countwin = 0;
            //     $winnumber="";
            //     $listbets = explode(",",str_replace(" ","",$record['bet_number']));
            //     for ($i=0; $i < count($listbets); $i++) { 
            //         for ($j=$i+1; $j < count($listbets); $j++) {
            //             for ($k=$j+1; $k < count($listbets); $k++) { 
            //                 for ($l=$k+1; $l < count($listbets); $l++) { 
            //                     $win = GameHelpers::CheckLoTruot(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]).','.trim($listbets[$l]),$rs);
            //                     // \Log::info('listbet was @ ' . $listbets[$i] .' '.$listbets[$j].' '.$listbets[$k].' '.$listbets[$l]);
            //                     // \Log::info('win was @ ' . implode(",",$win));
                                
            //                     // \Log::info('betnumber was @ ' . $record['bet_number']);
                                
            //                     try{
            //                         // try code
            //                         //XoSoRecordHelpers::PaymentLottery($record);
            //                     } 
            //                     catch(\Exception $e){
            //                         // catch code
            //                     }
            //                     if(count($win) > 0){
            //                         $countwin++;
            //                         $haswin = true;
            //                         $winnumber.='|'.implode(",",$win);
            //                     }
                            
            //             // $record->total_win_money -= $record->total_bet_money;
            //                 }
            //             }
            //         }
            //     }
            //     if ($haswin == false)
            //         $record->total_win_money -= $record->total_bet_money;
            //     else
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
                
            //     // $win = GameHelpers::CheckLoTruot($record['bet_number'],$rs);
            //     // try{
            //     //     // try code
            //     //     //XoSoRecordHelpers::PaymentLottery($record);
            //     // } 
            //     // catch(\Exception $e){
            //     //     // catch code
            //     // }
            //     // if(count($win) > 0)
            //     //     XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            //     // else
            //     //     $record->total_win_money = 0-$record->total_bet_money;
            // }

            // if($record['game_id']==20 || $record['game_id']==120) //check lo truot
            // {
            //     $haswin = false;
            //     $countwin = 0;
            //     $winnumber="";
            //     $listbets = explode(",",str_replace(" ","",$record['bet_number']));
            //     for ($i1=0; $i1 < count($listbets); $i1++) { 
            //         for ($i2=$i1+1; $i2 < count($listbets); $i2++) {
            //             for ($i3=$i2+1; $i3 < count($listbets); $i3++) {
            //                 for ($i4=$i3+1; $i4 < count($listbets); $i4++) {
            //                     for ($i5=$i4+1; $i5 < count($listbets); $i5++) {
            //                         for ($i6=$i5+1; $i6 < count($listbets); $i6++) {
            //                             for ($i7=$i6+1; $i7 < count($listbets); $i7++) {
            //                                 for ($i8=$i7+1; $i8 < count($listbets); $i8++) {
                        
            //                     $win = GameHelpers::CheckLoTruot($listbets[$i1].','.$listbets[$i2].','.$listbets[$i3].','.$listbets[$i4].','.$listbets[$i5].','.$listbets[$i6].','.$listbets[$i7].','.$listbets[$i8],$rs);
            //                     try{
            //                         // try code
            //                         //XoSoRecordHelpers::PaymentLottery($record);
            //                     } 
            //                     catch(\Exception $e){
            //                         // catch code
            //                     }
            //                     if(count($win) > 0){
            //                         $countwin++;
            //                         $haswin = true;
            //                         $winnumber.='|'.implode(",",$win);
            //                     }

            //                 //     if(count($win) > 0)
            //                 //     XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
            //                 // else
            //                 //     $haswin = false;
            //             // $record->total_win_money -= $record->total_bet_money;
            //                 }
            //             }
            //         }
            //                     }
            //                 }
            //             }
            //         }
            //     }
                
            //     if ($haswin == false)
            //         $record->total_win_money -= $record->total_bet_money;
            //     else
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            // }

            // if($record['game_id']==21 || $record['game_id']==121) //check lo truot
            // {
            //     $haswin = false;
            //     $countwin = 0;
            //     $winnumber="";
            //     $listbets = explode(",",str_replace(" ","",$record['bet_number']));
            //     for ($i1=0; $i1 < count($listbets); $i1++) { 
            //         for ($i2=$i1+1; $i2 < count($listbets); $i2++) {
            //             for ($i3=$i2+1; $i3 < count($listbets); $i3++) {
            //                 for ($i4=$i3+1; $i4 < count($listbets); $i4++) {
            //                     for ($i5=$i4+1; $i5 < count($listbets); $i5++) {
            //                         for ($i6=$i5+1; $i6 < count($listbets); $i6++) {
            //                             for ($i7=$i6+1; $i7 < count($listbets); $i7++) {
            //                                 for ($i8=$i7+1; $i8 < count($listbets); $i8++) {
            //                                     for ($i9=$i8+1; $i9 < count($listbets); $i9++) {
            //                                         for ($i10=$i9+1; $i10 < count($listbets); $i10++) {
                        
            //                     $win = GameHelpers::CheckLoTruot($listbets[$i1].','.$listbets[$i2].','.$listbets[$i3].','.$listbets[$i4].','.$listbets[$i5].','.$listbets[$i6].','.$listbets[$i7].','.$listbets[$i8].','.$listbets[$i9].','.$listbets[$i10],$rs);
            //                     try{
            //                         // try code
            //                         //XoSoRecordHelpers::PaymentLottery($record);
            //                     } 
            //                     catch(\Exception $e){
            //                         // catch code
            //                     }
            //                     if(count($win) > 0){
            //                         $countwin++;
            //                         $haswin = true;
            //                         $winnumber.='|'.implode(",",$win);
            //                     }
            //                 }
            //             }
            //         }
            //                     }
            //                 }
            //             }
            //         }
            //     }
            //         }
            //     }
                
            //     if ($haswin == false)
            //         $record->total_win_money -= $record->total_bet_money;
            //     else
            //         $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
            // }

            if($record['game_id']==315 || $record['game_id']==415) //check de truot
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
            else
                    $record->total_win_money -= $record->total_bet_money;
            }
            // if ($record->total_win_money > 0)
            //     $totalWinMoney+=$record->total_win_money;
            $record->save();    
        }
        }catch(\Exception $ex){
            // catch code
            // print($e);
            // NotifyHelpers::SendMailNotification('Tra thuong loi '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            echo 'Tra thuong loi '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine();
        }

        if ($totalBetMoney > 0 )
            NotifyHelpers::SendMailNotification('Thong ke ngay '.$now.' : '.number_format($totalBetMoney).'-'.number_format($totalWinMoney));
    }

}
