<?php namespace App\Console\Commands;

use App\Commands\generateByMinhNgoc;
use App\Commands\generateByXosome;
use App\Commands\generateLivexsmb;
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
use \Queue;

class GetLiveXoSo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:getlivexoso';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'get live xo so';
    
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        // NotifyHelpers::SendMailNotification('Start XS');
        $now = date('Y-m-d');// 
        if ('2024-02-08' < $now && $now < '2024-02-13'){
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            if (count($kqxs) > 0) return;
            $xoso = new XoSo();
            $xoso->insertDump();
            return;
        }
            
        // else
        $first_minus = date('i');
        if ($first_minus == 13){
            $this->insertDump();
            sleep(3);
        }

        {
            //generateByMinhNgoc
            //generateByxoso888
            //generateByXosome
            //generateByKetquaveso
            //generateByLotusAPI
            //generateBykqxsvnAPI
            //generateBy99luckeyAPI
            $count =0;
            $xoso = new XoSo();
            while (!$xoso->checkFullResults())
            {
                if (date('i') > $first_minus) return;
                if (date('i') > 40) return;
                echo 'run';
                Queue::pushOn("high",new generateLivexsmb("generateByMinhNgoc"));
                // Queue::pushOn("high",new generateLivexsmb("generateByMinhNgocJS"));
                // Queue::pushOn("high",new generateLivexsmb("generateByxoso888"));
                Queue::pushOn("high",new generateLivexsmb("generateByXosome"));
                Queue::pushOn("high",new generateLivexsmb("generateByKetquaveso"));
                // Queue::pushOn("high",new generateLivexsmb("generateByLotusAPI"));
                Queue::pushOn("high",new generateLivexsmb("generateBykqxsvnAPI"));
                Queue::pushOn("high",new generateLivexsmb("generateByNineVegas"));
                Queue::pushOn("high",new generateLivexsmb("generateByxoso888Pack"));
                
                sleep(2);
                // if ($count++ > 1500) return;
                
            }
            // NotifyHelpers::SendMailNotification('Finish XS');
            // \Log::info('i was @ update when' . \Carbon\Carbon::now());
            // $now = date('Y-m-d');
            // $xoso = new XoSo();
            // $rs = $xoso->getKetQua2today(1,$now);
            // var_dump($rs);
        }
	}

	public function insertDump(){
        $now = date('Y-m-d');
        $kqxs = XoSoResult::where('location_id', 1)
        ->where('date', $now)->get();
        if (count($kqxs) > 0) return;
        echo 'insert';
        $now = date('Y-m-d');
        DB::table('xoso_result')->insert([
            'location_id' =>  1,
            'DB' => '-----',
            'Giai_1' => '-----',
            'Giai_2' => '-----,-----',
            'Giai_3' => '-----,-----,-----,-----,-----,-----',
            'Giai_4' => '----,----,----,----',
            'Giai_5' => '----,----,----,----,----,----',
            'Giai_6' => '---,---,---',
            'Giai_7' => '--,--,--,--',
            'Giai_8' => 0,
            'spec_character' => '-----------------',
            'than_tai' => '--------',
            'date' => $now,
        ]);
    }
	
}


