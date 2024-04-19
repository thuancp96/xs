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
use App\Helpers\NotifyHelpers;
use DateTime;
use Sunra\PhpSimple\HtmlDomParser;
use App\Helpers\Curl;
use App\XoSoResult;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use \Cache;

class GetLiveThanTai extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:getlivethantai';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'get live than tai';
    
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        // NotifyHelpers::SendMailNotification('Start TT');
        $now = date('Y-m-d');// 
        if ('2024-02-08' < $now && $now < '2024-02-13') return;
        $count =0;
        while (!$this->generate())
        {
            echo 'run';
            // if ($count++ > 1500) return;
            if (date('i') > 40) return;
        }
        // NotifyHelpers::SendMailNotification('Finish TT');
        // \Log::info('i was @ update when' . \Carbon\Carbon::now());
        // $now = date('Y-m-d');
        // $xoso = new XoSo();
        // $rs = $xoso->getKetQua2today(1,$now);
        // var_dump($rs);
	}

    /**
     * split a string to array
     * if value of array is zero or empty, this will return blank array
     * @param $string
     * @param string $separator
     * @return array
     */
     private function SplitStringToArray($string,$separator=','){
        $array = explode($separator,$string);
        $counter = count($array);
        if($counter>1){
            return $array;
        }
        if($counter==0 || ($counter==1 && empty($array[0]))){
            return [];
        }
        return [];
    }

    public function fullKq($result)
    {
        if(count($result)>0)
        {
            $countkq = 0;

            if (is_numeric($result[0]->than_tai)){
                return true;
            }
            else return false;
        }
        return false;
    }
    
	public function generate(){
        // return $this->generateByKqNet();
        $getkq = $this->generateByMinhNgoc();
        if ($getkq == 1){
            return true;
        }
        else if ($getkq == 0){
            return false;
        }
        else if ($getkq == 2){
            // return $this->generateByKqNet();
        }
    }

    public function generateByMinhNgoc(){
        for($i=1;$i<=2;$i++)
            try{
                echo 'generateByMinhNgoc ';
                $now = date('Y-m-d');

                $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();
                
                if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                    echo 1;
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    $curl = new Curl();
                    $linkminhngoc = 'https://www.minhngoc.net.vn/xo-so-dien-toan/than-tai-4.html';
                    $response = $curl->get($linkminhngoc);
                    $domHtml = HtmlDomParser::str_get_html($response->body);
                    if (!isset($domHtml))
                        return 2;

                    $mainBody = $domHtml->find("#noidung > div > center",0)->children()[0];

                    if (!isset($mainBody))
                        return 2;

                    $date = $mainBody->find("div.title > table > tbody > tr > td.mothuong > a.thu_mo_thuong",0);

                    if (!isset($date))
                        return 2;

                    $today = date('d/m/Y');

                    if (strpos($date->innertext, $today) === false){
                        return 2;
                    }

                    $kq = $mainBody->find("div.content",0);
                    echo trim($kq->plaintext);
                    
                    echo 'update';
                    DB::table('xoso_result')
                    ->where('id', $kqxs[0]->id)
                    ->update([
                        'location_id' =>  1,
                        'than_tai' => trim($kq->plaintext),
                        'date' => $now,
                    ]);
                }


                    return 0;
                
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                echo 'error '.$ex->getMessage().'-'.$ex->getLine();
                return 2;
            }
        return 1;
    }

    public function generateByKqNet(){
        $is_ok = false;
        for($i=1;$i<=2;$i++)
        try{
            echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            
            if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                return true;
            }else {
                
                $curl = new Curl();
                $linkminhngoc = 'http://ketqua.net/kq-mb.raw';
                $response = $curl->get($linkminhngoc);
                $domHtml = HtmlDomParser::str_get_html($response->body);
                $mainBody = $domHtml;
                
                $kqraw = $this->SplitStringToArray($mainBody,';');
                // print_r ($kqraw);
                
                $date = new DateTime();
                $date->setTimestamp($kqraw[0]);
                $newformat = $date->format('Y-m-d');

                $countkq = 0;
                
                $now = date('Y-m-d');
                // print_r ($newformat);
                // print_r ($now);
                if ($newformat != $now) return false;
                try{
                    // $giaidbr = $this->SplitStringToArray($kqraw[9],'-'); 
                    $giaidb = '';
                    
                    if (is_numeric($kqraw[9])){
                        $giaidb .= $kqraw[9];
                        $countkq++;
                    }else{
                        $giaidb .= '-----';
                    }
                    
                    //echo "</br>";
                }catch(\Exception $ex){}
                
                try{
                    // $giai1r = $this->SplitStringToArray($kqraw[8],'-'); 
                    $giai1='';
                    //echo 'giai1 ';
                    if (is_numeric($kqraw[8])){
                        $giai1 .= $kqraw[8];
                        $countkq++;
                    }else{
                        $giai1 .= '-----';
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                try{
                    $giai2r = $this->SplitStringToArray($kqraw[7],'-'); 
                    $giai2='';
                    //echo 'giai2 ';
                    foreach($giai2r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item)){
                            $giai2 .= $item;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
                    
                try{
                    $giai3r = $this->SplitStringToArray($kqraw[6],'-'); 
                    $giai3='';
                    //echo 'giai3 ';
                    foreach($giai3r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item)){
                            $giai3 .= $item;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                try{
                    $giai4r = $this->SplitStringToArray($kqraw[5],'-'); 
                    $giai4='';
                    //echo 'giai4 ';
                    foreach($giai4r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item)){
                            $giai4 .= $item;
                            $countkq++;
                        }else{
                            $giai4 .= '----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                try{
                    $giai5r = $this->SplitStringToArray($kqraw[4],'-'); 
                    $giai5='';
                    //echo 'giai5 ';
                    foreach($giai5r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item)){
                            $giai5 .= $item;
                            $countkq++;
                        }else{
                            $giai5 .= '----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                try{
                    $giai6r = $this->SplitStringToArray($kqraw[3],'-'); 
                    $giai6='';
                    // echo 'giai6 ';
                    foreach($giai6r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item)){
                            $giai6 .= $item;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                try{
                    $giai7r = $this->SplitStringToArray($kqraw[2],'-'); 
                    $giai7='';
                    //echo 'giai7 ';
                    foreach($giai7r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item)){
                            $giai7 .= $item;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                // try{
                //     $giaikhr = $this->SplitStringToArray($kqraw[1],'-'); 
                //     $giaikh=$giaikhr;

                //     if ($giaikhr != 'SC SC SC'){
                //         $giaikh = $giaikhr;
                //         $countkq++;
                //     }
                // }catch(\Exception $ex){}
                    
                if (count($kqxs) < 1){
                    echo 'insert';
                    DB::table('xoso_result')->insert([
                        'location_id' =>  1,
                        'DB' => $giaidb,
                        'Giai_1' => $giai1,
                        'Giai_2' => $giai2,
                        'Giai_3' => $giai3,
                        'Giai_4' => $giai4,
                        'Giai_5' => $giai5,
                        'Giai_6' => $giai6,
                        'Giai_7' => $giai7,
                        'Giai_8' => $countkq,
                        'date' => $now,
                    ]);
                    // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                }else{
                    $kqxs = $kqxs->first();
                    if ($countkq != $kqxs->Giai_8 || $kqxs->Giai_8==28 || $kqxs->Giai_8==27){
                        echo 'update';
                        DB::table('xoso_result')
                        ->where('id', $kqxs->id)
                        ->update([
                            'location_id' =>  1,
                            'DB' => $giaidb,
                            'Giai_1' => $giai1,
                            'Giai_2' => $giai2,
                            'Giai_3' => $giai3,
                            'Giai_4' => $giai4,
                            'Giai_5' => $giai5,
                            'Giai_6' => $giai6,
                            'Giai_7' => $giai7,
                            'Giai_8' => $countkq,
                            'date' => $now,
                        ]);
                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                        // NotifyHelpers::SendMailNotification('Cap nhat kq '.$countkq);
                    }
                }

                return false;
            }
        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            echo 'stop';
            $is_ok = false;
            // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
        }
        if ($is_ok==false){
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            if (count($kqxs)>0){
                $kqxs = $kqxs->first();
                DB::table('xoso_result')
                ->where('id', $kqxs->id)
                ->update([
                    'location_id' =>  1,
                    'Giai_8' => 28,
                    'date' => $now,
                ]);
                // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
            }else{
                DB::table('xoso_result')->insert([
                    'location_id' =>  1,
                    'Giai_8' => 28,
                    'date' => $now,
                ]);
                // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
            }
        }
        return false;
    }
	
}


