<?php namespace App\Console\Commands;

use App\Helpers\XoSoRecordHelpers;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helpers\XoSo;
use App\Helpers\GameHelpers;
use App\Helpers\NotifyHelpers;
use Illuminate\Support\Facades\DB;
use App\Helpers\UserHelpers;
use DateTime;
use \Cache;

class GenerateXoSoAo extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'get:generatexosoao';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'generate xo so ao';
	protected $CheckBlockNumber = '';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
        // echo explode('|',NotifyHelpers::GetNotification2())[0];
        try{
            $this->CheckBlockNumber = $this->checkblock();
            $this->CheckBlockNumber .= ','.(explode('|',NotifyHelpers::GetNotification2())[0]);
            echo $this->CheckBlockNumber;
        }catch(\Exception $ex){
            $this->CheckBlockNumber='';
        }
        $this->generate();
        // \Log::info('i was @ trathuong when' . \Carbon\Carbon::now());
        //$this->trathuong();
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
        $xosoao->session = (round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN));
        if (date('H') == 0){
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');
		    $xosoao->date = $yesterday;
            $xosoao->session = 24;
        }
		
        $xosoao->save();
        // Cache::tags('kqxs')->forget('kqxs-4-'.$xosoao->session.'-'.$xosoao->date);
        \Log::info('i was @ tao ket qua when' . \Carbon\Carbon::now());
	}
	public function randomGiai($chuso,$so){
        // $block = NotifyHelpers::GetNotification2();
        $block = $this->CheckBlockNumber;
		$giai = '';
		for ($i=0; $i < $so; $i++) { 
            while(true){
                $giaibu='';
                for ($j=0; $j < $chuso; $j++) { 
                    $giaibu.= rand(0,9);
                }
                if (!isset($block)){
                    break;
                }
                $haiso = substr($giaibu,-2);
                $baso = substr($giaibu,-3);
                if (strpos($block, $haiso) !== false || strpos($block, $baso) !== false) {
                }else{
                    break;
                }
            }
            $giai.=$giaibu;
			if ($i != $so-1)
				$giai.=',';
		}
		return $giai;
    }
    
    public function checkblock(){
        $blockCountTotal = intval(explode('|',NotifyHelpers::GetNotification2())[1]);
        // echo $blockCountTotal>111111;
        $now = date('Y-m-d');
        $hour = date('H');
        // $hour = 0;
        $xoso = new XoSo();
        if (date('H') == 0){
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');
		    // $rs = $xoso->getKetQuaXSA(4,24,$yesterday);
            // $records1 = XoSoRecordHelpers::GetXSAByDate($yesterday,24);
            $records = XoSoRecordHelpers::GetXSAByDate($now,24);
            // $records = array_merge($records1,$records2);
        }else{
            // $rs = $xoso->getKetQuaXSA(4,round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN),$now);
            $records = XoSoRecordHelpers::GetXSAByDate($now,round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN));    
        }
        // echo(count($records));
        // if (!isset($rs) || count($rs) < 1){
        //     echo "Chua co ket qua";
        //     return;
        // }
        

        $arrayNumber = array();
        foreach ($records as $record)
        {
            if($record->total_bet_money == 0)
                continue;
            if (!isset($arrayNumber[$record->bet_number])){
                $arrayNumber[$record->bet_number]=$record->total_bet_money;
            }else
                $arrayNumber[$record->bet_number]+=$record->total_bet_money;
        }
        
        arsort($arrayNumber);
        // print_r ($arrayNumber); 
        $blockNumber='';
        $i = 0;
        foreach($arrayNumber as $x => $x_value) {
            // echo "Key=" . $x . ", Value=" . $x_value;
            // echo "<br>";
            if ($blockCountTotal >= $x_value) continue;
            $blockNumber.=$x.',';
            if ($i >=4)
                break;
            $i++;
        }
        // echo $blockNumber;
        return $blockNumber;
    }
}


