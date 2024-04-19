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

class GetLiveXoSoMienTrung extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:getlivexosomientrung';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'get live xo so mien trung';
    
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        // $this->generate();
        // return;
        // NotifyHelpers::SendMailNotification('Start XS Mien Trung');
        $count =0;
        while (!$this->generate())
        {
            echo 'run';
            // if ($count++ > 1500) return;
            if (date('i') >= 54) return;
        }
        // NotifyHelpers::SendMailNotification('Finish XS Mien Trung');
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
        if ($result[0]->session == 36){
            echo 'full';
            return true;
        }
        return false;
        
        if(count($result)>0)
        {
            $countkq = 0;

            if (is_numeric($result[0]->DB)){
                $countkq++;
            }
            if (is_numeric($result[0]->Giai_1)){
                $countkq++;
            }

            foreach($this->SplitStringToArray($result[0]->Giai_2) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_3) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_4) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_5) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_6) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_7) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            
            if ( $countkq == 27 && date('i') >= 32 ) return true;
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
            return false;
        }
    }

    private function getGiai($mainBody,$giaiSelect,$countkq){
        try{
            $giair = 
            // $mainBody->find("div.content table.bkqmiennam > tbody > tr > td:nth-child(1) > table > tbody > tr > td:nth-child(1) > table > tbody > tr:nth-child(11) > td.giaidb",0)->children();
            // $mainBody->find("div.content table.bkqmiennam > tbody > tr > td:nth-child(1) > table > tbody > tr > td:nth-child(1) > table > tbody > tr:nth-child(11) > td.giaidb",0)->children();
            $mainBody->find($giaiSelect);
            $giai = [];
            // echo 'giaidb ';
            $countGiai = 0;
            unset($giair[0]);
            foreach($giair as $item){
                // $giai[] =  $item->plaintext;
                // echo $item->plaintext . '';
                $countGiai++;
                // if (strlen($giai) > 0) $giai.="|";
                $subgiaidb = '';
                foreach($item->children() as $subItem){
                    echo $subItem->plaintext . ' ';
                    if (strlen($subgiaidb) > 0) $subgiaidb.=",";
                    if (is_numeric($subItem->plaintext)){
                        $subgiaidb .= $subItem->plaintext;
                        $countkq++;
                    }else{
                        $subgiaidb .= '------';
                    } 
                }
                // $giai .= $subgiaidb;
                array_push($giai,$subgiaidb);
                if ($countGiai==2) break;
            }
            // print_r($giai);
            // echo "</br>";
            return [$giai,$countkq];
        }catch(\Exception $ex){
            echo $ex->getMessage();
        }
        
    }

    public function generateByMinhNgoc(){
        for($i=1;$i<=1;$i++)
            try{
                // echo 'generateByMinhNgoc'.$i;
                $now = date('Y-m-d');
                $kqxs1 = XoSoResult::where('location_id', 31)
                ->where('date', $now)->get();

                $kqxs2 = XoSoResult::where('location_id', 32)
                ->where('date', $now)->get();
                
                if (count($kqxs1) > 0 && count($kqxs2) > 0 && $kqxs1->first()->session == 36){
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    $curl = new Curl();
                    // $linkminhngoc = 'https://www.minhngoc.net.vn/xo-so-truc-tiep/mien-trung.html';
                    $linkminhngoc = 'https://www.minhngoc.net.vn/xo-so-truc-tiep/mien-trung.html';
                    $response = $curl->get($linkminhngoc);
                    $domHtml = HtmlDomParser::str_get_html($response->body);
                    if (!isset($domHtml))
                        return 2;
                    $mainBody = $domHtml->find("#box_tructiepkqxs > div.box_kqxs",0);
                    if (!isset($mainBody))
                        return 2;
                    $info = $mainBody->find("div.top > div > div > div > div.title",0);
                    // echo $info->innertext;
                    if (!isset($info))
                        return 2;
                    $date = $info->innertext;
                    if (!isset($date))
                        return 2;
                    $date = str_replace('TRỰC TIẾP XỔ SỐ Miền Trung - ','',$date);
                    $date = str_replace('/', '-', $date);
                    $date = strtotime($date);
                    $countkq = 0;
                    $newformat = date('Y-m-d',$date);
                    // echo $newformat ."</br>";
                    $now = date('Y-m-d');
                    if ($newformat != $now) return 0;

                    // get giai dac biet
                    $giaidb = $this->getGiai($mainBody,'.giaidb',$countkq);
                    $countkq = $giaidb[1];
                    // get giai nhat
                    $giai1 = $this->getGiai($mainBody,'.giai1',$countkq);
                    $countkq = $giai1[1];
                    // get giai 2
                    $giai2 = $this->getGiai($mainBody,'.giai2',$countkq);
                    $countkq = $giai2[1];
                    // get giai 3
                    $giai3 = $this->getGiai($mainBody,'.giai3',$countkq);
                    $countkq = $giai3[1];
                    // get giai 4
                    $giai4 = $this->getGiai($mainBody,'.giai4',$countkq);
                    $countkq = $giai4[1];
                    // get giai 5
                    $giai5 = $this->getGiai($mainBody,'.giai5',$countkq);
                    $countkq = $giai5[1];
                    // get giai 6
                    $giai6 = $this->getGiai($mainBody,'.giai6',$countkq);
                    $countkq = $giai6[1];
                    // get giai 7
                    $giai7 = $this->getGiai($mainBody,'.giai7',$countkq);
                    $countkq = $giai7[1];
                    // get giai 8
                    $giai8 = $this->getGiai($mainBody,'.giai8',$countkq);
                    $countkq = $giai8[1];
                    echo '1';
                    if (count($kqxs1) < 1){
                        echo 'insert';
                        DB::table('xoso_result')->insert([
                            'location_id' =>  31,
                            'DB' => $giaidb[0][0],
                            'Giai_1' => $giai1[0][0],
                            'Giai_2' => $giai2[0][0],
                            'Giai_3' => $giai3[0][0],
                            'Giai_4' => $giai4[0][0],
                            'Giai_5' => $giai5[0][0],
                            'Giai_6' => $giai6[0][0],
                            'Giai_7' => $giai7[0][0],
                            'Giai_8' => $giai8[0][0],
                            'session' => $countkq,
                            'date' => $now,
                        ]);

                        // DB::table('xoso_result')->insert([
                        //     'location_id' =>  22,
                        //     'DB' => $giaidb[0][1],
                        //     'Giai_1' => $giai1[0][1],
                        //     'Giai_2' => $giai2[0][1],
                        //     'Giai_3' => $giai3[0][1],
                        //     'Giai_4' => $giai4[0][1],
                        //     'Giai_5' => $giai5[0][1],
                        //     'Giai_6' => $giai6[0][1],
                        //     'Giai_7' => $giai7[0][1],
                        //     'Giai_8' => $giai8[0][1],
                        //     'session' => $countkq,
                        //     'date' => $now,
                        // ]);

                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs1 = $kqxs1->first();
                        if ($countkq != $kqxs1->Giai_8){
                            echo 'update';
                            DB::table('xoso_result')
                            ->where('id', $kqxs1->id)
                            ->update([
                                'location_id' =>  31,
                                'DB' => $giaidb[0][0],
                                'Giai_1' => $giai1[0][0],
                                'Giai_2' => $giai2[0][0],
                                'Giai_3' => $giai3[0][0],
                                'Giai_4' => $giai4[0][0],
                                'Giai_5' => $giai5[0][0],
                                'Giai_6' => $giai6[0][0],
                                'Giai_7' => $giai7[0][0],
                                'Giai_8' => $giai8[0][0],
                                'session' => $countkq,
                                'date' => $now,
                            ]);

                            // DB::table('xoso_result')
                            // ->where('id', $kqxs->id)
                            // ->update([
                            //     'location_id' =>  22,
                            //     'DB' => $giaidb[0][1],
                            //     'Giai_1' => $giai1[0][1],
                            //     'Giai_2' => $giai2[0][1],
                            //     'Giai_3' => $giai3[0][1],
                            //     'Giai_4' => $giai4[0][1],
                            //     'Giai_5' => $giai5[0][1],
                            //     'Giai_6' => $giai6[0][1],
                            //     'Giai_7' => $giai7[0][1],
                            //     'Giai_8' => $giai8[0][1],
                            //     'session' => $countkq,
                            //     'date' => $now,
                            // ]);
                            // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                            // NotifyHelpers::SendMailNotification('Cap nhat kq '.$countkq);
                        }
                    }

                    if (count($kqxs2) < 1){
                        echo 'insert';

                        DB::table('xoso_result')->insert([
                            'location_id' =>  32,
                            'DB' => $giaidb[0][1],
                            'Giai_1' => $giai1[0][1],
                            'Giai_2' => $giai2[0][1],
                            'Giai_3' => $giai3[0][1],
                            'Giai_4' => $giai4[0][1],
                            'Giai_5' => $giai5[0][1],
                            'Giai_6' => $giai6[0][1],
                            'Giai_7' => $giai7[0][1],
                            'Giai_8' => $giai8[0][1],
                            'session' => $countkq,
                            'date' => $now,
                        ]);

                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs2 = $kqxs2->first();
                        if ($countkq != $kqxs2->Giai_8){
                            echo 'update';

                            DB::table('xoso_result')
                            ->where('id', $kqxs2->id)
                            ->update([
                                'location_id' =>  32,
                                'DB' => $giaidb[0][1],
                                'Giai_1' => $giai1[0][1],
                                'Giai_2' => $giai2[0][1],
                                'Giai_3' => $giai3[0][1],
                                'Giai_4' => $giai4[0][1],
                                'Giai_5' => $giai5[0][1],
                                'Giai_6' => $giai6[0][1],
                                'Giai_7' => $giai7[0][1],
                                'Giai_8' => $giai8[0][1],
                                'session' => $countkq,
                                'date' => $now,
                            ]);
                            // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                            // NotifyHelpers::SendMailNotification('Cap nhat kq '.$countkq);
                        }
                    }

                    return 0;
                }
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                echo $ex->getMessage().' '.$ex->getLine();
                // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
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
                            'Giai_8' => $giai8,
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


