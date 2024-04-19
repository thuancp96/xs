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

class GetLiveKeno extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:getlivekeno';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'get live keno';
    
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        // NotifyHelpers::SendMailNotification('Start TT');
        $hour = date('H');
		if ($hour >= 6 && $hour < 22){
            $count =0;
            while (!$this->generate())
            {
                echo 'run';
                sleep(3);
                if ($count++ > 200) return;
                // if (date('i') > 40) return;
            }
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
        $getkq = $this->generateByJson();
        // $getkq = $this->generateByMinhChinh();
        if ($getkq == 1){
            return true;
        }
        else if ($getkq == 0){
            return false;
        }
        else if ($getkq == 2){
            return $this->generateByMinhChinh();
        }
    }

    public function generateByMinhChinh(){
        for($i=1;$i<=2;$i++)
            try{
                // echo 'generateByMinhChinh';
                $now = date('Y-m-d H:i:s');

                // $kqxs = XoSoResult::where('location_id', 1)
                // ->where('date', $now)->get();
                
                // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                //     echo 1;
                //     return 1;
                // }else 

                // $kqxs = $kqxs->first();
                $curl = new Curl();
                $linkminhngoc = 'https://www.minhchinh.com/truc-tiep-xo-so-tu-chon-keno.html';
                $response = $curl->get($linkminhngoc);
                $domHtml = HtmlDomParser::str_get_html($response->body);
                if (!isset($domHtml))
                    return 2;

                $mainBody = $domHtml->find("#ttkqxsdt",0)->children()[0];

                if (!isset($mainBody))
                    return 2;

                $kq_ky = trim($mainBody->find("#kq_ky",0)->plaintext);
                $kq_ngay = trim($mainBody->find("#kq_ngay",0)->plaintext);
                $kq_time = trim($mainBody->find("#kq_time",0)->plaintext);
                $kq_full_time = $kq_ngay .' '. $kq_time;
                $merge_time =strtotime($kq_full_time);
                $kq_updated_at = DateTime::createFromFormat('d/m/Y H:i', $kq_full_time)->format('Y-m-d H:i:s');

                $kqxs = XoSoResult::where('location_id', 5)
                ->where('updated_at', $kq_updated_at)
                ->where('Giai_8', $kq_ky)
                ->get();
                
                if (count($kqxs) > 0){
                        echo 1;
                        return 1;
                }
                // echo $kq_full_time;
                // echo DateTime::createFromFormat('d/m/Y H:i', $kq_full_time)->format('Y-m-d H:i');
                
                // return 1;
                $ball = [];
                $totalBall = 0;
                for($i=1; $i<=20; $i++){
                    $ball_i = trim($mainBody->find('#ball_'.$i,0)->plaintext);
                    // printf($ball_i);
                    // return 1;
                    $totalBall += (int)$ball_i;
                    array_push($ball,$ball_i);
                }

                print_r($ball);
                
                echo 'update';

                DB::table('xoso_result')->insert([
                    'location_id' =>  5,
                    'DB' => implode(",",$ball),
                    'Giai_1' => $totalBall,
                    'Giai_2' => '',
                    'Giai_3' => '',
                    'Giai_4' => '',
                    'Giai_5' => '',
                    'Giai_6' => '',
                    'Giai_7' => '',
                    'Giai_8' => $kq_ky,
                    'date' => DateTime::createFromFormat('d/m/Y', $kq_ngay)->format('Y-m-d'),
                    'updated_at' => $kq_updated_at,
                    'created_at' => $now
                ]);

                return 1;
                
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                echo 'error '.$ex->getMessage().'-'.$ex->getLine();
                return 2;
            }
        return 1;
    }

    public function generateByJson(){

        for($i=1;$i<=2;$i++)
        try{
            echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d H:i:s');

            $minuteNow = date('i') - date('i')%10;

            $curl = new Curl();
            $linkminhngoc = 'https://www.minhchinh.com/livekqxs/xstt/js/KN.js';
            $response = $curl->get($linkminhngoc);
            $domHtml = HtmlDomParser::str_get_html($response->body);
            $mainBody = str_replace('xsdt[8]=','',$domHtml);

            $jsonLastResult = json_decode($mainBody);
            // 2020-05-14 16:00:00
            $minuteLive = DateTime::createFromFormat('Y-m-d H:i:s', $jsonLastResult->lastResult->date)->format('i');
            
            if ($minuteLive != $minuteNow)
                return 0;

            $kqxs = XoSoResult::where('location_id', 5)
                ->where('updated_at', $jsonLastResult->lastResult->date)
                ->where('Giai_8', '#0'.$jsonLastResult->lastResult->ky)
                ->get();
                
                if (count($kqxs) > 0){
                        echo 1;
                        return 1;
                }
                $totalBall = 0;
            foreach ($jsonLastResult->lastResult->kq as $item){
                $totalBall+= (int)$item;
            }
            DB::table('xoso_result')->insert([
                'location_id' =>  5,
                'DB' => implode(",",$jsonLastResult->lastResult->kq),
                'Giai_1' => $totalBall,
                'Giai_2' => '',
                'Giai_3' => '',
                'Giai_4' => '',
                'Giai_5' => '',
                'Giai_6' => '',
                'Giai_7' => '',
                'Giai_8' => '#0'.$jsonLastResult->lastResult->ky,
                'date' => $jsonLastResult->lastResult->date,
                'updated_at' => $jsonLastResult->lastResult->date,
                'created_at' => $now
            ]);

            return 1;

        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            echo 'stop';
            $is_ok = false;
            // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
        }
        
        return 2;
    }
	
}


