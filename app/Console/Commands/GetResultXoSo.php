<?php namespace App\Console\Commands;

use App\Helpers\XoSoRecordHelpers;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\XoSo;
use App\Helpers\GameHelpers;
use App\Helpers\HistoryHelpers;
use App\Helpers\NotifyHelpers;
use App\XoSoResult;
use DateTime;

class GetResultXoSo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:xoso';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Lay ket qua xo so';

    // public function fullKq($result)
    // {
    //     if(count($result)>0)
    //     {
    //         $countkq = 0;

    //         if (is_numeric($result->DB)){
    //             $countkq++;
    //         }
    //         if (is_numeric($result->Giai_1)){
    //             $countkq++;
    //         }

    //         foreach($this->SplitStringToArray($result->Giai_2) as $item){
    //             if (is_numeric($item)){
    //                 $countkq++;
    //             }
    //         }
    //         foreach($this->SplitStringToArray($result->Giai_3) as $item){
    //             if (is_numeric($item)){
    //                 $countkq++;
    //             }
    //         }
    //         foreach($this->SplitStringToArray($result->Giai_4) as $item){
    //             if (is_numeric($item)){
    //                 $countkq++;
    //             }
    //         }
    //         foreach($this->SplitStringToArray($result->Giai_5) as $item){
    //             if (is_numeric($item)){
    //                 $countkq++;
    //             }
    //         }
    //         foreach($this->SplitStringToArray($result->Giai_6) as $item){
    //             if (is_numeric($item)){
    //                 $countkq++;
    //             }
    //         }
    //         foreach($this->SplitStringToArray($result->Giai_7) as $item){
    //             if (is_numeric($item)){
    //                 $countkq++;
    //             }
    //         }
            
    //         if ( $countkq == 27 && date('i') >= 32 ) return true;
    //         else return false;
    //     }
    //     return false;
    // }
    
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        $now = date('Y-m-d');// 
        if ('2024-02-08' < $now && $now < '2024-02-13'){
            // $xoso = new XoSo();
            // $xoso->insertDump();
            return;
        }

        ini_set('memory_limit', '-1');
        
        $now = date('Y-m-d');
        $hour = date('H');

        $minus = date('i');

        $xoso = new XoSo();
        //$datetime = new DateTime('yesterday');
        //$yesterday = "2022-03-21";//$datetime->format('Y-m-d');
        // if ($hour >18 || ($hour==18 && $min>=40 ) )
        // {
        //     $now = $tomorrow;
        // }
        
        //$rs = $xoso->getKetQua(1,$yesterday);
        //$records = XoSoRecordHelpers::GetByDate($yesterday,1);
        //$this->trathuong($records,$rs,$yesterday);

        // try{
        //     // try code
        //     $rs = $xoso->getKetQua(1,$now);
        // } 
        // catch(\Exception $e){
        //     // catch code
        // }
        
        try{
            // try code
            // if (true)
            if ($minus%10==3)
            {
                // $hour = date('i');
		        // if ($hour >= 6 && $hour < 22){
                //     // Tra thuong keno
                //     $rs = $xoso->getKetQuaKeno(5,($hour/1),($minus - $minus%10),$now);

                //     $records = XoSoRecordHelpers::GetByDate($now,5);
                //     // print_r($rs);
                //     $this->trathuong($records,$rs,$now);
                // }
                // return;
            }else{
                // if ($hour==16){
                
                //     $rs = $xoso->getKetQua(21,$now);
    
                //     $records = XoSoRecordHelpers::GetByDate($now,21);
                //     $this->trathuong($records,$rs,$now);
    
                //     $rs = $xoso->getKetQua(22,$now);
                //     $records = XoSoRecordHelpers::GetByDate($now,22);
                //     $this->trathuong($records,$rs,$now);
                // }
    
                // if ($hour==17){
                //     // Tra thuong mien trung
                //     $rs = $xoso->getKetQua(31,$now);
                //     $records = XoSoRecordHelpers::GetByDate($now,31);
                //     $this->trathuong($records,$rs,$now);
    
                //     $rs = $xoso->getKetQua(32,$now);
                //     $records = XoSoRecordHelpers::GetByDate($now,32);
                //     $this->trathuong($records,$rs,$now);
                // }
    
                if ($hour==18)
                {
                    $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                    $channelid = "-1001667315543";
                    if (env("scanKQ",0) == 1 && XoSoRecordHelpers::scanCheatNumber()){
                        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Tạm dừng trả thưởng Miền Bắc!!!');
                        return;
                    }
                    // Tra thuong mien bắc
                    $rs = $xoso->getKetQua(1,$now);
                    $records = XoSoRecordHelpers::GetByDate($now,1);

                    $xoso = new XoSo();

                    $now = date('Y-m-d');
                    $kqxs = XoSoResult::where('location_id', 1)
                        ->where('date', $now)->get();

                    $datetime = new DateTime('yesterday');
                    $yesterday = $datetime->format('Y-m-d');

                    $kqxs_yesterday = XoSoResult::where('location_id', 1)
                    ->where('date', $yesterday)->get();

                    // // var_dump($kqxs);
                    if (count($kqxs) > 0 && $xoso->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
                        XoSoRecordHelpers::trathuong($records,$rs,$now);
                        if (count($records) > 0){
                            HistoryHelpers::notification2User();
                            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Trả thưởng Miền Bắc hoàn thành!');
                        }
                    }else{
                        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Trả thưởng Miền Bắc không hoàn thành! Lỗi kết quả');
                    }
                }
            }
            
        } 
        catch(\Exception $e){
            // catch code
            echo $e->getMessage();
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
        
	}

}
