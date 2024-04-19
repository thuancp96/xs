<?php namespace App\Console\Commands;

use App\Commands\CheckUpdateExchangeRate_v3;
use App\Commands\PaymentLottery7zball;
use App\Commands\UpdateBetPriceAllUser;
use App\Commands\UpdateBetPriceAllUser_v2;
use App\Commands\UpdateCustomerTypeByUserIdService;
use App\Commands\UpdateMeFromParentEXService;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use App\Game;
use App\game_1478;
use App\Game_1533;
use App\Game_1561;
use App\Game_1650;
use App\Game_1698;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \Cache;
use \Queue;
use App\Helpers\GameHelpers;
use App\Helpers\XoSo;
use DateTime;
use App\Helpers\XoSoRecordHelpers;
use App\Game_Number;
use App\Helpers\HistoryHelpers;
use App\Helpers\LiveCasinoHelpers;
use App\Helpers\MinigameHelpers;
use App\Helpers\NotifyHelpers;
use App\Helpers\QuickbetHelpers;
use App\Helpers\SabaHelpers;
use App\Helpers\Soccer7zballHelpers;
use App\Helpers\UserHelpers;
use App\Helpers\XosobotHelpers;
use App\History;
use App\history_7zBall_bet;
use App\history_minigame_bet;
use App\Http\Controllers\XosobotController;
use App\User;
use App\XoSoRecord;
use App\XoSoResult;
use DateInterval;
use DatePeriod;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue as FacadesQueue;
use ReflectionClass;
use Telegram\Bot\Api;
use SevenEcks\Tableify\Tableify;

class CacheClear extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cache-clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

    private function test01(){
        try{
            
			// $jobs = DB::table('jobs')->where('queue','high')->get();
			// $jobs = DB::table('jobs')->get();
			// if ( count($jobs) > 2000) return;
			// \Log::info('jobs ' . count($jobs));
			$now = date('Y-m-d');
			$maxSeconds = 3;
			$timeRun = 60/$maxSeconds - 1;
			//60/$maxSeconds;
			for($i=1; $i <= $timeRun; $i++)
			{
				// $jobs = DB::table('jobs')->where('queue','high')->get();

				// if ( count($jobs) > 100) return;
				// echo $i.' ';
				$stat1 = file('/proc/stat'); 
				sleep(1); 
				$stat2 = file('/proc/stat'); 
				$info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0])); 
				$info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0])); 
				$dif = array(); 
				$dif['user'] = $info2[0] - $info1[0]; 
				$dif['nice'] = $info2[1] - $info1[1]; 
				$dif['sys'] = $info2[2] - $info1[2]; 
				$dif['idle'] = $info2[3] - $info1[3]; 
				$total = array_sum($dif); 
				$cpu = array(); 
				foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 1);
				echo "run". date('H-i') . $cpu['idle'];
				// if ($cpu['idle'] <= 40) return;
				$gameall = GameHelpers::GetAllGame(1);
				foreach($gameall as $game)
					if ($game->game_code==14 || $game->game_code==27 ||
					$game->game_code==28 || $game->game_code==7 || $game->game_code==12 || $game->game_code==9
					|| $game->game_code==10 || $game->game_code==11 || $game->game_code==29 
				//	|| $game->game_code==114 || $game->game_code==107 || $game->game_code==112
				//	|| ($game->game_code >= 721 && $game->game_code <= 739)
				//	|| $game->game_code==709 || $game->game_code==701 || $game->game_code==710 || $game->game_code==711 
					
					// || $game->game_code==314 || $game->game_code==414 || $game->game_code==514 || $game->game_code==614
					// || $game->game_code==307 || $game->game_code==407 || $game->game_code==507 || $game->game_code==607
					// || $game->game_code==309 || $game->game_code==409 || $game->game_code==509 || $game->game_code==609
					// || $game->game_code==310 || $game->game_code==410 || $game->game_code==510 || $game->game_code==610
					// || $game->game_code==311 || $game->game_code==411 || $game->game_code==511 || $game->game_code==611
					// || $game->game_code==329 || $game->game_code==429 || $game->game_code==529 || $game->game_code==629
					// || ($game->game_code >= 31 && $game->game_code <= 55)
					|| $game->game_code == 24
					|| $game->game_code == 25 || $game->game_code == 26 || $game->game_code == 27 || $game->game_code == 28
					)
					{
						// if(intval(date('H') )==18 && intval(date('i'))>=14 && intval(date('i') )<=30){
						// 	$rs = XoSoResult::where('location_id', 1)
                		// 		->where('date', $now)->first();

						// 	if ( !isset($rs) || !isset($rs['8']) )
						// 	{
						// 		// ok
						// 	}
						// 	else if ( $game->game_code >= 31 && $game->game_code <= 32 && $rs['8']>=1 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 33 && $game->game_code <= 38 && $rs['8']>=3 )
						// 	{
						// 		//lock
						// 		continue;
						// 	}
						// 	else if ( $game->game_code >= 39 && $game->game_code <= 42 && $rs['8']>=9 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 43 && $game->game_code <= 48 && $rs['8']>=13 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 49 && $game->game_code <= 51 && $rs['8']>=19 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else if ( $game->game_code >= 52 && $game->game_code <= 55 && $rs['8']>=22 )
						// 		{
						// 			//lock
						// 			continue;
						// 		}
						// 	else
						// 		{
						// 			//ok
						// 		}
						// 	}

						if( (intval(date('H') ) ==18 && intval(date('i'))>=35) || intval(date('H') ) >18){
							//lock
							// return;
						}

						if ($game->status_cal == 0){
							echo $game->game_code .' ';
							$lastestBettime = XoSoRecordHelpers::lastestBetTime($game->game_code);
							echo ' '.$lastestBettime[0].' ';
							if ($lastestBettime[0] == null || $game->lastestBet >= $lastestBettime[0]){
								// $game->lastestBet = $lastestBettime;
								// $game->save();
								continue;
								// echo 'save status_cal -1';
								// return;
							}
							// echo "insert job " . $game->game_code .' ';
							$game->lastestBet = $lastestBettime[0];
							$game->latestIDTemp = $lastestBettime[1];
							$game->save();
							Queue::pushOn("high",new CheckUpdateExchangeRate_v3($game));
							// Queue::pushOn("medium",new AutoLockNumber($game));
							// Queue::pushOn("low",new AutoLockNumberMerge($game));
							
						}	
					}
				if ($i<$timeRun)
					sleep($maxSeconds);
			}
		}catch(\Exception $ex){
			echo $ex->getMessage().'-'.$ex->getLine();
		}

    }
	public function handle()
	{       
        XoSo::reCalculatorNumberSuper(7,"01");
        return;
        // $userSuper = User::where("id",1650)->first();
        // $xr = XoSoRecordHelpers::calThauSuperGameLatest($userSuper,14,0,801051);
        // var_dump($xr);
        // // return;
        // Game_1478::first();
        // Game_1533::first();
        // Game_1561::first();
        // Game_1698::first();
        // Game_1650::first();
        // // return;
        $gameTableId = 'App\Game_'.'1650';
        // $ref = new ReflectionClass($gameTableId);
        $ref = new $gameTableId;
        // $gameSuper = $ref::where('game_code',14)->first();
        // $gameSuper->alias = 'test';
        // $gameSuper->save();
        // var_dump($gameSuper);
        var_dump($ref);
        return;
        XoSo::setCheckLuk();
        return;
        // DB::table('history')
        //         ->where('date', date('Y-m-d'))
        //         ->update([
        //             'paid' =>  1
        //         ]);
        //         return;
        $his7z = history_7zBall_bet::get();

        foreach($his7z as $item){
            // $item->date = date('Y-m-d', strtotime($item->createdate));
            // echo $item->date;
            if ($item->bonus != null){
                $arrBonus = explode(",",$item->bonus);
                $item->com = end($arrBonus);
                echo $item->com . " ";
                $item->save();
            }
                // $item->source_bet = $item->id_inday > 0 ? 1 : 0;
                // echo $item->source_bet . " ";
                // $item->save();
        }
        return;

        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        $begin = new DateTime($staticstart);
        $end = new DateTime($staticfinish);
        if ($end > (new DateTime()))
            $end = new DateTime();
        $end->modify('+1 day');
        $count = 0;
        $i = 0;
        $interval = DateInterval::createFromDateString('1 day');

        $period = new DatePeriod($begin, $interval, $end);
        setlocale(LC_TIME, "vi_VN");
        foreach ($period as $dt) {
            
            echo strftime("%A", $dt->getTimestamp());
        }

        return;
        $datetime = new DateTime('yesterday');
            	                $yesterday = $datetime->format('Y-m-d');
                                echo $yesterday;
        $now = $yesterday;
        $xoso = new XoSo();

        $yesterday = date('Y-m-d', strtotime($now. ' -1 day'));
        echo $yesterday;

        $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();

            var_dump($kqxs);
        $kqxs_yesterday = XoSoResult::where('location_id', 1)
        ->where('date', $yesterday)->get();
        var_dump($kqxs_yesterday);

        if (count($kqxs) > 0 && $xoso->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
            echo "ok";
        }else{
            echo "false";
        }
        return;

        NotifyHelpers::sendMessage(5381486859, "test", '6690018393:AAG8W2f_upUTJOufNBFLa81xnA1YbBHXoi8');
        return;
        Cache::put('stack_action_bot_tele_confirm_terms' . 1481, false, env('CACHE_TIME_BOT', 24 * 60));
        return;
        $mess = "";
        $mess .= "<b>QUY TẮC ĐẶT CƯỢC</b>" . "\n". "\n";
        $mess .= "1.	Có thể luật của nơi bạn đang sống không cho cá cược hợp pháp. Nếu bạn vào đặt cược ở đó, Công ty chúng tôi sẽ không chịu trách nhiệm về những sự cố mà khách hàng gặp phải." . "\n". "\n";
        $mess .= '2.	Khách hàng có trách nhiệm bảo mật về tài khoản của mình. Nếu khách hàng nghi ngờ rằng dữ liệu cùa mình bị đánh cắp, nên thông báo ngay cho Đại lý cấp trên hoặc báo lỗi <a href="https://example.com">This is an example</a> trên hệ thống.' . "\n". "\n";
        $mess .= "3.	Khi đặt cược là bạn đã chấp nhận theo luật chơi, trả thưởng của phía Công ty ở đây (link đến phần quy tắc) . Chúng tôi không có trách nhiệm giải quyết thắc mắc, trả thưởng theo luật chơi bên phía các bạn đang hoặc đã từng cược." . "\n". "\n";
        $mess .= "4.	Công ty có quyền từ chối, hủy những mã đặt cược bất thường hoặc có biểu hiện gian lận: vào cược sau giờ mở thưởng, có dấu hiệu hack và chỉnh sửa, …" . "\n". "\n";
        $mess .= "5.	<i>Công ty và người chơi có thể hủy bỏ các mã đặt cược trước giờ quay thưởng. Người chơi xin lưu ý theo dõi để nhận thông báo các mã huỷ bỏ.</i>";
        $mess .= "";

        $tokenBot_agent_member = "6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q";
        $txtMessage = $mess;
        NotifyHelpers::sendMessage('5381486859', $txtMessage, $tokenBot_agent_member);

        return;
        $now = "2023-12-27";
        $xoso = new XoSo();
        // Tra thuong mien bắc
        $rs = $xoso->getKetQua(1,$now);
        $records = XoSoRecordHelpers::GetByDate($now,1);
        $xoso = new XoSo();
        XoSoRecordHelpers::trathuong($records,$rs,$now);

        return;
        XoSo::setTokenLD789();
        $data = XoSo::fetchOne789AuthDataRaw();
					foreach ($data as $key => $gameNumber) {
                        var_dump($gameNumber);
						if($gameNumber['BetType'] == 0){
							XoSo::fetchOne789AuthData($gameNumber,14,0,0);
						}
						if($gameNumber['BetType'] == 1){
							XoSo::fetchOne789AuthData($gameNumber,7,100,1);
						}
						if($gameNumber['BetType'] == 22){
							XoSo::fetchOne789AuthData($gameNumber,12,10,22);
						}
					}

                    return;
        $game_ids = [7,12,14];
        foreach ($game_ids as $game_id) {
            # code...
            for($i=0;$i<10;$i++)
            for($j=0;$j<10;$j++){
                $bet_number = $i.$j;
                // $new789 = 0;
                Cache::forget('fetchOne789DataRaw1-'.$game_id.'-'.$bet_number);
                $extend789 = Cache::get('fetchOne789DataRaw1-'.$game_id.'-'.$bet_number,0);
                echo $extend789 . " ";
                Xoso::reCalculatorNumber($game_id,$bet_number);
                // , $new789, env('CACHE_TIME_PRICE', 1*60) * 5
            }

        }
        
        XoSo::setTokenLD789();
        $data = XoSo::fetchOne789AuthDataRaw();
					foreach ($data as $key => $gameNumber) {
                        var_dump($gameNumber);
						if($gameNumber['BetType'] == 0){
							XoSo::fetchOne789AuthData($gameNumber,14,0,0);
						}
						if($gameNumber['BetType'] == 1){
							XoSo::fetchOne789AuthData($gameNumber,7,100,1);
						}
						if($gameNumber['BetType'] == 22){
							XoSo::fetchOne789AuthData($gameNumber,12,10,22);
						}
					}

                    return;
 
        return;
        // XoSoRecordHelpers::CheckXosoRecord("12,13");
        // return;
        // $currentUser = User::where('id', 274)->first();
        // $lockPrice=0;
        // $childrenUser = 
        // Cache::remember('childrenUser-'.$currentUser->id."-".$lockPrice, env('CACHE_TIME_SHORT', 0), function () use ($currentUser,$lockPrice) {
        //     return 
        //     User::where('user_create',$currentUser->id)
        //     ->where('active',0)
        //     ->where('lock_price',$lockPrice)
        //     ->where('per',0)
        //     ->where('roleid','<',6)
        //     ->orderBy('latestlogin', 'desc')
        //     ->get();
        // });
        // $lstUser = [];
        //     foreach ($childrenUser as $user){
        //         $lstUser[] = $user->id;
        //     }

        // if ($lstUser == []){
        //     echo "loi len gia lstUser". $currentUser->id . " " . $lockPrice;
        //     return;
        // }
        // echo "loi len gia lstUser". $currentUser->id . " " . $lockPrice;
        // return;

        
        // $game_id=12;
        // for($i=0;$i<10;$i++)
        //     for($j=0;$j<10;$j++){
        //         $bet_number = $i.$j;
        //         $new789 = 0;
        //         Cache::forget('fetchOne789Data-'.$game_id.'-'.$bet_number);
        //         // $extend789 = Cache::get('fetchOne789Data-'.$game_code.'-'.$bet_number,0);
        //         Xoso::reCalculatorNumber($game_id,$bet_number);
        //         // , $new789, env('CACHE_TIME_PRICE', 1*60) * 5
        //     }
            XoSo::fetchOne789Data(7,100,1);
            XoSo::fetchOne789Data(14,10,0);
            XoSo::fetchOne789Data(12,10,22);
            return;
        // XoSo::reCalculatorNumber(7,'49');
        // return;
        // XoSo::fetchOne789Data(7,100,1);
        // XoSo::fetchOne789Data(7,100,1);
        // XoSo::fetchOne789();
        return;
        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
        $channelid = "-1002038570631";
        $message = "<b>ss</b>";
        $message.= PHP_EOL;
        $message.= "ss";
        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, $message);
        return;
        echo \Hash::make("lov41ng2024").PHP_EOL;
        return;
        // Xoso::reCalculatorNumber(7,"01");return;
        // Cache::put('xacnhan_sokhoado_bot', false, env('CACHE_TIME', 12*60));
        XoSo::fetchOne789Data(14,10,0);
			// XoSo::fetchOne789Data(7,100,1);
		// XoSo::fetchOne789Data(12,10,22);
        var_dump(Cache::get('fetchOne789Data-'."14"."-"."73",[]));
        return;
        // NotifyHelpers::saveNotification2(User::where("id",1481)->first(),"Công ty xin trả lại tin “” do có dấu hiệu bất thường");
        // return;
        // (new XosobotHelpers("6625058071:AAEgdD1qZ033OWSR8nzNk4EXXh15XG0kl5o","2"))->deleteMessage("5381486859","17649");
        // $queue = Cache::get('queue_messages' . "5381486859","");
        // echo $queue;
        // (new XosobotHelpers("6625058071:AAEgdD1qZ033OWSR8nzNk4EXXh15XG0kl5o","2"))->clearMessageQueue("5381486859");
        // return;
        $ids = [61465,61434,61402,61387,61363,61369,61364];
        XoSoRecordHelpers::checkCancelBetxs($ids);
        return;
        $allUsersMember = User::where('roleid', 6)
			->where('active', 0)
			->where('lock_price', 0)
			->where('remain','>', 0)
			->orderBy('latestlogin','desc')
			->limit(1000)
			->get();
			foreach($allUsersMember as $user){
				$secs = (new DateTime())->getTimestamp() - (new DateTime($user->latestlogin))->getTimestamp();// == <seconds between the two times>
				$days = $secs / 86400;
				if ($days < 1) 
					GameHelpers::UpdateMeFromParentEX6($user,$user,14,11);
			}
            return;
        // $this->test01();
        $user = User::where("id",1550)->first();
        GameHelpers::UpdateMeFromParentEX6($user,$user,14,49);
        return;
        $xoso_record=[];
            $H_7zBall_record = DB::table('history')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                // ->where('history.created_at', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                // ->where('history.created_at', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->where('history.id', 57831)
                ->join('users', 'users.id', '=', 'history.user_create')
                // ->join('games', 'history.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->select('history.*', 'users.name as name')
                ->get();
                var_dump($H_7zBall_record);
            foreach ($H_7zBall_record as $value) {
                // $dataResults = json_decode($value->jsoninfo);
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $string = preg_replace("/[\r\n]+/", "", $value->content);
                $content_Str = $string;
                $record7zBall = (json_decode('{"game_id":' . 1 . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->money . ',"com":' . 0 . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->money . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->created_at . '","updated_at":"' . $value->created_at . '","xien_id":0,"game":"' . 1 . '","name":"' . $value->name . '","content":"' . $content_Str . '","location":"Xổ số miền bắc","locationslug":"1", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
                // $record7zBall->rawBet = $dataResults;
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
                var_dump($xoso_record);
            return;

        $user = null;
        try{
            $allUsersMember = User::where('roleid', 6)
			->where('active', 0)
			->where('lock_price', 0)
			->where('remain','>', 0)
			->orderBy('latestlogin','desc')
			->limit(1000)
			->get();
			foreach($allUsersMember as $user){
				$secs = (new DateTime())->getTimestamp() - (new DateTime($user->latestlogin))->getTimestamp();// == <seconds between the two times>
        		$days = $secs / 86400;
        		if ($days < 1) 
					GameHelpers::UpdateMeFromParentEX6($user,$user,14,68);
			}
        }catch(Exception $ex){
            var_dump($user);
            return;
        }
        return;
            

        $this->test01();
    //     var_dump(XoSoRecordHelpers::lastestBetTime(7)) ;
    //     var_dump(XoSoRecordHelpers::lastestBetTime(14)) ;
    //     FacadesQueue::pushOn("high", new UpdateMeFromParentEXService(User::where("id",1481)->first()));
    //     FacadesQueue::pushOn("high", new UpdateMeFromParentEXService(User::where("id",1550)->first()));
    return;

        $games = CustomerType_Game_Original::where('game_id',7)
                        // ->where('created_user', $userCus->id)
                        ->whereIn('created_user', [1533])
                        ->where('code_type', 'A')
                        ->get();
                        $game = XoSoRecordHelpers::array_find(1533,$games,"created_user");
        var_dump($game);

        return;
        echo XoSoRecordHelpers::getMinCategory(14);
        return;
        $user = User::where("id",1550)->first();
        HistoryHelpers::notificationTeleWinlose($user,date('Y-m-d',strtotime("-1 day")));
        HistoryHelpers::notificationTeleWinloseByDetailHistory($user,date('Y-m-d',strtotime("-1 day")));
        return;
        // echo DB::table("users")->where("id", 1481)->decrement('remain', 100000);
        // return;
        HistoryHelpers::notification2User();
        return;
        $get7z = history_minigame_bet::where("id",308)->first();
        // foreach($get7z as $his)
        XoSoRecordHelpers::PaymentLottery7zball($get7z);
        return;
        echo MinigameHelpers::convertGametype("ODD") ;
        return;
        echo (new QuickbetHelpers)->convert_bo_so("De dan02cach3 x10");
        return;
        NotifyHelpers::sendMessage('5381486859','Hãy cài đặt username của tài khoản telegram theo hướng dẫn https://www.youtube.com/watch?v=FKMTzgJ1Cww và /start để bắt đầu sử dụng.','6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q');
        return;
        Soccer7zballHelpers::GetHistoryLoop();
        return;
        $get7z = history_7zBall_bet::get();
        foreach($get7z as $his)
        XoSoRecordHelpers::PaymentLottery7zball($his);
        return;
        $get7z = history_7zBall_bet::where("id",500)->first();
        // foreach($get7z as $his)
        XoSoRecordHelpers::PaymentLottery7zball($get7z);

        $get7z = history_7zBall_bet::where("id",502)->first();
        // foreach($get7z as $his)
        XoSoRecordHelpers::PaymentLottery7zball($get7z);

        $get7z = history_7zBall_bet::where("id",499)->first();
        // foreach($get7z as $his)
        XoSoRecordHelpers::PaymentLottery7zball($get7z);

        // Queue::pushOn('low10',new PaymentLottery7zball(history_7zBall_bet::where("id",456)->first()));
        return;

        $endDate = "2023-05-24 01:02:12";
        $endTimeStamp = strtotime($endDate);
            $endDateNewformat = date('Y-m-d',$endTimeStamp);
            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday"))){
                echo date('Y-m-d', strtotime("tomorrow"));
                echo ";;";
            }
                
        return;
        $userMe  = User::where('id',1490)->first();
        $stDateTemp = '22-05-2023';
        $endDateTemp = '22-05-2023';
        $type = 'all';
        print_r(Cache::forget('XoSoRecordHelpers-ReportKhachv20230115'.$userMe->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type));
        return;
        $get7z = history_7zBall_bet::get();
        foreach($get7z as $his)
        XoSoRecordHelpers::PaymentLottery7zball($his);
        // Queue::pushOn('low10',new PaymentLottery7zball(history_7zBall_bet::where("id",456)->first()));
        return;
        // Soccer7zballHelpers::ReCall("zolak");
        // echo Soccer7zballHelpers::Login("zolak");
        echo "GetHistoryLoop";
        Soccer7zballHelpers::GetHistoryLoop();
        return;
        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
        // $channelid = "-1001667315543";
        $channelid = "@thongbaoketquaxosomienbac";
        // https://t.me/thongbaoketquaxosomienbac
        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"test using ID");
        // $xs = new XoSo();
        //     $kqxs = $xs->getKetQuaToArr(1, date('Y-m-d'));
        //     $message = "<i>Ký hiệu Đặc biệt:</i> <b>" . $kqxs['spec_character'] . "</b>\n";
        //     $message .= "<i>Đặc biệt:</i>              <b>" . $kqxs['DB'] . "</b>\n";
        //     $message .= "<i>Nhất:</i>                    <b>" . $kqxs['1'] . "</b>\n";
        //     $message .= "<i>Nhì:</i>          <b>" . $kqxs['2'][0] . "</b>       |           <b>" . $kqxs['2'][1]   . "</b>\n";
        //     $message .= "<i>Ba:</i> <b>" . $kqxs['3'][0] . "</b>     |     <b>" . $kqxs['3'][1] . "</b>     |     <b>" . $kqxs['3'][2] . "</b>\n";
        //     $message .= "       <b>" . $kqxs['3'][3] . "</b>     |     <b>" . $kqxs['3'][4] . "</b>     |     <b>" . $kqxs['3'][5] . "</b>\n";
        //     $message .= "<i>Tư:</i> <b>" . $kqxs['4'][0] . "</b>   |   <b>" . $kqxs['4'][1] . "</b>   |   <b>" . $kqxs['4'][2] . "</b>   |   <b>" . $kqxs['4'][3] . "</b>\n";
        //     $message .= "<i>Năm:</i> <b>" . $kqxs['5'][0] . "</b>    |     <b>" . $kqxs['5'][1] . "</b>    |     <b>" . $kqxs['5'][2] . "</b>\n";
        //     $message .= "           <b>" . $kqxs['5'][3] . "</b>    |     <b>" . $kqxs['5'][4] . "</b>    |     <b>" . $kqxs['5'][5] . "</b>\n";
        //     $message .= "<i>Sáu:</i>   <b>" . $kqxs['6'][0] . "</b>       |      <b>" . $kqxs['6'][1] . "</b>       |      <b>" . $kqxs['6'][2] . "</b>\n";
        //     $message .= "<i>Bảy:</i>    <b>" . $kqxs['7'][0] . "</b>     |    <b>" . $kqxs['7'][1] . "</b>     |    <b>" . $kqxs['7'][2] . "</b>     |    <b>" . $kqxs['7'][3] . "</b>\n";
        //     $message .= "<i>Thần tài:</i>                  <b>" . $kqxs['than_tai'] . "</b>\n";
        //     NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,$message);
            return;
        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        // if (Cache::get('staticstart_week_bet' . $user->id) == $staticstart )
        //     return;

        // $staticstart = date('Y-m-d',strtotime('-7 day', strtotime($staticstart))); 
        // $staticfinish = date('Y-m-d',strtotime('-7 day', strtotime($staticfinish))); 

        $keyboardHoivienthangthuatuannay =
            array(
                array(
                    //     array('text' => 'Thứ 2', 'callback_data' => 'hoivienthangthuatuannay_thu2'),
                    //     array('text' => 'Thứ 3', 'callback_data' => 'hoivienthangthuatuannay_thu3'),
                ),
                // array(
                //     array('text' => 'Thứ 4', 'callback_data' => 'hoivienthangthuatuannay_thu4'),
                //     array('text' => 'Thứ 5', 'callback_data' => 'hoivienthangthuatuannay_thu5'),
                // ),
                // array(
                //     array('text' => 'Thứ 6', 'callback_data' => 'hoivienthangthuatuannay_thu6'),
                //     array('text' => 'Thứ 7', 'callback_data' => 'hoivienthangthuatuannay_thu7'),
                // ),
                // array(
                //     array('text' => '< Back', 'callback_data' => 'hoivienthangthua_agent'),
                //     array('text' => 'CN', 'callback_data' => 'hoivienthangthuatuannay_cn'),
                // )
            );
        $begin = new DateTime($staticstart);
        $end = new DateTime($staticfinish);
        if ($end > (new DateTime()))
            $end = new DateTime();
        $end->modify('+1 day');
        $count = 0;
        $i = 0;
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        
        foreach ($period as $dt) {
            $stDateTemp = $dt->format("d-m-Y");
            $count++;
            if ($count > 2) {
                $i++;
                $count = 0;
                array_push($keyboardHoivienthangthuatuannay, []);
            }
            setlocale(LC_TIME, "vi_VN");
            array_push($keyboardHoivienthangthuatuannay[$i], array('text' => strftime("%A", $dt->getTimestamp()), 'callback_data' => 'hoivienthangthuatuannay_' . $dt->format("Y-m-d")));
        }
        array_push($keyboardHoivienthangthuatuannay, array('text' => '< Back', 'callback_data' => 'hoivienthangthua_agent'));

        var_dump($keyboardHoivienthangthuatuannay);
        return;
        $request = (object) ["money" => 1000,"type"=>"credit","id"=>1, "user_create"=>2];
        echo $request->money;
        return;
        echo QuickbetHelpers::clean('a|"bc!@£de^&$f gÔ Â   ');
        return;
        $data = [
            ['Họ và tên', 'Date', 'Phone', 'Age'],
            ['Altec Lansing', '03/22/18', '617-555-0584', '30'],
            ['Fack', '03/22/18', '508-555-0584', '24'],
            ['Seven Ecks', '03/22/18', '+1-888-555-0584', '100'],
            ['Hoàng Bách', '03/22/18', 'N/A', '33'],
            ['Jason Jasonson', '03/22/18', '978-555-0584', '34'],
            ['Waxillium Wick', '03/22/18', '978-555-0584', '34'],
            ['Ruby Reide', '03/22/18', '978-555-0584', '34'],
            ['Rex Gold', '03/22/18', '978-555-0584', '34'],
            ['Juicy Vee', '03/22/18', '978-555-0584', '34'],
        ];

        echo "Table Construction using default values on class and no method chaining:\n";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            echo $row . "\n";
        }
return;

        echo XoSoRecordHelpers::DeleteLotoByUser(112251,User::where("id",1481)->first());
        return;
        $str = "112251,112250";
        $ids = explode(",",$str);
        if (count($ids) < 0) $ids = [$ids];
        var_dump($ids);
        return;
        // echo strtotime('now');
        // return;

        // NotifyHelpers::SendTelegramNotificationByChannel("6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ","@thongbaoketquaxoso","test");
        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
        $channelid = "@thongbaoketquaxoso";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Test 01'. PHP_EOL . 'Trả thưởng Miền Bắc hoàn thành!');
        // echo 'tbupdateKQ';
        Cache::put("updateKQ",0,60);
        (new XoSo())->tbupdateKQ(1,date("Y-m-d"),"122-122-122-122-122122-122-122-122-122-122");
        return;
        try{
            $str_bet = "giai2.1 30,50x10d";
            $quickbet = new QuickbetHelpers();
            $bet = $quickbet->quickplaylogic(User::where("id","1481")->first(), $str_bet, '0', '', false);
            var_dump($bet);
            // var_dump($quickbet->str_to_game_code($bet));
        }catch(Exception $ex){
            echo $ex->getMessage() . " " . $ex->getLine();
        }
        
        return;

       

        setlocale(LC_TIME, "vi_VN");
echo strftime("%A (%d-%m)",strtotime("2023-03-29")) . "\n";
echo strftime("%A (%d-%m)") ;
return;
        $telegram = new Api('6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q');
        $response = $telegram->getMe();
        $response = $telegram->deleteMessage([
            'chat_id' => 5381486859,
            'message_id' => 1846,
        ]);
        return;
        $now = date('Y-m-d');
                        $kqxs = XoSoResult::where('location_id', 1)
                        ->where('date', $now)->first();
                        $hsLLive = isset($kqxs->Giai_8) && is_numeric($kqxs->Giai_8) ? $kqxs->Giai_8 : 1;
                        $hsLLive = round(27 / (27 - $hsLLive),5);
        echo $hsLLive . " ";
        // echo \Hash::make("lov41ng2021242123");
        // $xoso = (new XoSo())->generateByMinhNgocJS();
        // var_dump()
        // echo $xoso;
        return ;

        
        echo mb_strlen("Thông tin tài khoản") . " ";
        echo strlen("Thông tin tài khoản");
        return;
        $testNull = CustomerType_Game_Original::where('game_id',77)
                    ->where('created_user', 274)
                    ->where('code_type', 'A')
                    ->first();
                    echo isset($testNull) ? 1 : 0;
        return;
        var_dump(Cache::get('TotalBetTodayByGameThau-'.'7'));
        

//         // $telegram = new Api('6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q');
//         // // print_r($telegram);
//         // $response = $telegram->getMe();
//         // // $response = Telegram::getMe();

//         // $response = $telegram->deleteMessage([
//         //     'chat_id' => '5381486859',
//         //     'message_id' => 543
//         // ]);
//         // return;
//         $botId = $response->getId();
//         $firstName = $response->getFirstName();
//         $username = $response->getUsername();
//         echo $username;
//         //5381486859

//         $telegram = new Api('6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q');
//         $response = $telegram->getMe();

//         // $reply_markup = $telegram->replyKeyboardMarkup($keyboard);
//         $message ='11';
//         $keyboradsValue = array(
//             array("button 1","button 2"),
//             array("button 3","button 4"),
//          );
//          $replyMarkup = array(
//            'keyboard' => $keyboradsValue,
//            'force_reply' => true,
//            'selective' => true
//          );
//          $keyboard = 
//                     array(
//                         // ["Boy", "Girl", "Other"]
//                         array(
//                             array('text' => 'Boy'),
//                             array('text' => 'TGirl'),
//                         ),
//                     );
//          $keyboard = ['keyboard' => $keyboard, 'one_time_keyboard' => true, 'input_field_placeholder'=>"Boy or Girl?", 'selective'=>true];
//          $reply_markup = $telegram->replyKeyboardMarkup($keyboard);

//         $response = $telegram->sendMessage([
//             'chat_id' => '5381486859', 
//             // 'text' => 'Hello guy', 
//             'reply_markup' => $reply_markup,
//             'text' => "Hi! My name is Professor Bot. I will hold a conversation with you. "
//  ."Send /cancel to stop talking to me.\n\n"
// ."Are you a boy or a girl?",
//             'parse_mode' => 'HTML'
//         ]);
$gameList = (new GameHelpers())->GetAllGameByParentID(1);
                    $startDate = date('d-m-Y');
					$endDate = date('d-m-Y');

					// echo $startDate . ' ' . $endDate;

					$begin = new DateTime($startDate);
					$end = new DateTime($endDate);
					if ($end > (new DateTime()))
						$end = new DateTime();
					$end->modify('+1 day');
						
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);

					
					// echo Auth::user()->id;
					$counttotalMember = 0;
					$counttotalThau = 0;
					$countwinloseAdmin = 0;
					$countwinloseMember = 0;

					$totalMember = array();
					$totalThau = array();
					$winloseAdmin = array();
					$winloseMember = array();

					foreach($period as $dt) {							
						$stDateTemp = $dt->format("d-m-Y");
						$endDateTemp = $dt->format("d-m-Y");
						if ($dt->format("Y-m-d") > date('Y-m-d')){
							// echo 'continue';
							break;
						}
						$cacheTime = env('CACHE_TIME_SHORT', 0);
						$endTimeStamp = strtotime($endDateTemp);
						$endDateNewformat = date('Y-m-d',$endTimeStamp);
						if ($endDateNewformat < date('Y-m-d'))
							$cacheTime = 1440*30;
						if ($endDateNewformat == date('Y-m-d',strtotime("yesterday")) && date('H') < 11){
							$cacheTime = env('CACHE_TIME_SHORT', 0);
						}
                        Cache::forget('calByDate-rsv2-20230310'.$endDateNewformat);
						$calByDate = Cache::remember('calByDate-rsv2-20230310'.$endDateNewformat, $cacheTime, function () use ($endDateNewformat,$cacheTime) {
							$childrenAdmin = UserHelpers::GetAllUserChild(User::where('id',274)->first());
							$totalMemberSP = array();
							$totalThauSP = array();
							$winloseAdminSP = array();
							$winloseMemberSP = array();
							foreach($childrenAdmin as $supers){
								$arrUser = UserHelpers::GetAllUserV2($supers);
                                Cache::forget('xoso_record-calByDate-rsv2-20230310'.$endDateNewformat.$supers->id);
								$rs = Cache::remember('xoso_record-calByDate-rsv2-20230310'.$endDateNewformat.$supers->id, $cacheTime, function () use ($arrUser,$endDateNewformat) {
									return DB::table('xoso_record')
									->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
										IF(game_id = 15 OR game_id = 16 OR game_id = 19 OR game_id = 20 OR game_id = 21, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
										) AS sumwin', 'games.name as game_name'))
											->orderBy('sumbet', 'desc')
											->where('isDelete',false)
											->where('date',$endDateNewformat)
											// ->where('date','<=',$endDate)
											// ->whereIn('game_id', [7,12,14])
											->whereIn('user_id', $arrUser)
											->join('games', 'games.game_code', '=', 'xoso_record.game_id')
											->groupBy('game_id')
											->get();
										});
								// if (count($rs)>0)
									// var_dump($rs);
								foreach($rs as $record){
									$game_id = $record->game_id;
                                    if ($game_id == 9 || $game_id == 10 || $game_id == 11 ) $game_id = 2;
                                    if ($game_id == 16 || $game_id == 19 || $game_id == 20 || $game_id == 21 ) $game_id = 3;
                                    if ($game_id >= 31 && $game_id <= 55) $game_id = 24;
                                    // if ($game_id == 9 || $game_id == 10 || $game_id == 11 ) $game_id = 2;

									if (!isset($totalMemberSP[$game_id])) $totalMemberSP[$game_id] = 0;
									if (!isset($winloseMemberSP[$game_id])) $winloseMemberSP[$game_id] = 0;
									if (!isset($totalThauSP[$game_id])) $totalThauSP[$game_id] = 0;
									if (!isset($winloseAdminSP[$game_id])) $winloseAdminSP[$game_id] = 0;
									$totalMemberSP[$game_id] += $record->sumbet;
									$winloseMemberSP[$game_id] += $record->sumwin;
									$totalThauSP[$game_id] += $record->sumbet*$supers->thau/100;
									$winloseAdminSP[$game_id] += $record->sumwin*$supers->thau/100;
								}
							}
							return [$totalMemberSP,$totalThauSP,$winloseAdminSP,$winloseMemberSP];
							
						});
						var_dump($calByDate);
						foreach($gameList as $game){
							if (!isset($totalMember[$game->game_code])) $totalMember[$game->game_code] = 0;
							if (!isset($winloseMember[$game->game_code])) $winloseMember[$game->game_code] = 0;
							if (!isset($totalThau[$game->game_code])) $totalThau[$game->game_code] = 0;
							if (!isset($winloseAdmin[$game->game_code])) $winloseAdmin[$game->game_code] = 0;

							$totalMember[$game->game_code] += (isset($calByDate[0][$game->game_code]) ? $calByDate[0][$game->game_code] : 0);
							$winloseMember[$game->game_code] += (isset($calByDate[3][$game->game_code]) ? $calByDate[3][$game->game_code] : 0);
							$totalThau[$game->game_code] += (isset($calByDate[1][$game->game_code]) ? $calByDate[1][$game->game_code] : 0);
							$winloseAdmin[$game->game_code] += (isset($calByDate[2][$game->game_code]) ? $calByDate[2][$game->game_code] : 0);

						}
                        var_dump($totalMember);
					}

        return;

        $userMe  = User::where('id',1490)->first();
        $stDateTemp = '22-05-2023';
        $endDateTemp = '22-05-2023';
        $type = 'all';
        print_r(Cache::forget('XoSoRecordHelpers-ReportKhachv20230115'.$userMe->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type));
        // 
        // print_r(XoSoRecordHelpers::getRecordKhachByDatev2($userMe,$stDateTemp,$endDateTemp,$type));
        $cacheTime = 1440*30;
        $userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachv20230115'.$userMe->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type, $cacheTime, function () use ($userMe,$stDateTemp,$endDateTemp,$type) {
            return  XoSoRecordHelpers::ReportKhachv2($userMe, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
        });
        print_r($userReportTemp);
        return;
        
        LiveCasinoHelpers::GetHistoryALLLoop();
        return;
        $userMe  = User::where('id',15262)->first();
        GameHelpers::UpdateMeFromParentEX($userMe,$userMe);
        // Queue::pushOn("high",new UpdateMeFromParentEXService($userMe));
        return;
        print_r($this->test01());
        return;
        echo 'ss';
        $userMe  = User::where('id',15262)->first();
            // $userParent = User::where('id', $userMe->user_create)->first();
    
            // $userMe->customer_type = $request->customertype;
            // $userMe->save();
            // Queue::pushOn("high",new UpdateCustomerTypeByUserIdService('C',$userMe));
        // GameHelpers::UpdateCustomerTypeByUserId($this->change_customertype,$this->userMe);
        GameHelpers::UpdateCustomerTypeByUserId('C',$userMe);
        sleep(5);
        GameHelpers::UpdateCustomerTypeByUserId('A',$userMe);
        return;
        // $now = date('Y-m-d');// 
        // if ('2023-01-21' <= $now && $now <= '2023-01-24')
        //     echo $now;
        // return;
        $users = UserHelpers::GetAllUserV2ByKey4Report(274,'admin');
        // $search = '';
        // $users = User::
        // whereRaw('active = 0 and (users.name like "%'.$search.'%" or role.name like "%'.$search.'%")')
        // where('active',0)->where('user_create',$userid)
        // ->join('role', 'users.roleid', '=', 'role.id')
        // select('*')
            // ->get();
        print_r($users);
        echo count($users) .'-';
        return;
        $begin = new DateTime('01-12-2022');
        $end = new DateTime('17-01-2023');
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $type = "";
        foreach($period as $dt) {
            $stDateTemp = $dt->format("d-m-Y");
            $endDateTemp = $dt->format("d-m-Y");
            if ($dt->format("Y-m-d") > date('Y-m-d')){
                // echo 'continue';
                break;
            }
            echo $stDateTemp .'\n';
            foreach($users as $user){
                $cacheTime = env('CACHE_TIME_SHORT', 0);
                $endTimeStamp = strtotime($endDateTemp);
                $endDateNewformat = date('Y-m-d',$endTimeStamp);
                echo $user->id." ";
                if ($endDateNewformat < date('Y-m-d'))
                    $cacheTime = 1440*30;
                // echo $cacheTime.' '.$stDateTemp.' '.$endDateTemp.'-';
                // Cache::forget('XoSoRecordHelpers-ReportKhachv20230115'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type);

                $userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachv20230115'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type, $cacheTime, function () use ($user,$stDateTemp,$endDateTemp,$type) {
                    return  XoSoRecordHelpers::ReportKhachv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                });
            }
        }
            
        return;
        $game_code = 29;
        $TotalBetTodayByGameArr = XoSoRecordHelpers::calThauAdminGameLatest($game_code,0,95079111);
        print_r($TotalBetTodayByGameArr);
        return;

        echo LiveCasinoHelpers::Transfer('satomember',100000,"IN");
        return;
        echo LiveCasinoHelpers::CheckUsrBalance('MenberT0');
        return;
        $user = User::where('id',15262)->first();
        $secs = (new DateTime())->getTimestamp() - (new DateTime($user->latestlogin))->getTimestamp();// == <seconds between the two times>
        $days = $secs / 86400;
        echo ($days) ;
        // echo (new DateTime())->getTimestamp();
        // echo (new DateTime($user->latestlogin))->getTimestamp();

        return ;
        $begin = new DateTime('2010-05-01');
        $end = new DateTime('2010-06-10');
        
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        
        foreach ($period as $dt) {
            echo $dt->format("l Y-m-d H:i:s\n");
        }

        return;

        XoSoRecordHelpers::DeleteLoto(25226);
        return;
        
        print_r(GameHelpers::GetGame_NumberByUser(14,'45',17287)[0]);
        return;
        XoSoRecordHelpers::clearBet(14);
        return;
        $record = XoSoRecord::where('id',1175704)->first();
        XoSoRecordHelpers::PaymentLottery($record);

        return;
        $arrName =  [(object)["name" => "Lô Xiên"], (object)["name" => "Xiên2"], (object)["name" => "Xiên3"], (object)["name" => "Xiên4"], (object)["name" => "Lô live"], (object)["name" => "Xiên Nháy"]];
        $game_record_by_min = DB::table('games')
                ->where('location_id', 1)
                ->where('active', 1)
                // ->where('close', '18:'. $current)
                ->whereNotIn('id', [9,10,11,68,18,2]) // xiên + lô live
                ->get();
                
                XoSoRecordHelpers::ReportMessageTelegramByNamev2($game_record_by_min, "-720602361");

        // $arrName =  [(object)["name" => "Lô"], (object)["name" => "Đề"]];
        XoSoRecordHelpers::ReportMessageTelegramByNamev2($arrName);
        return;
        $userid = 15262;
        $game_code_temp = 7;
        $bet_number = '19';
        $now = date('Y-m-d');// 
        $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-'.$userid.'-'.$game_code_temp.'-'.$bet_number.'-'.$now,0) ;
        echo $totalBettodayOne.' ';
        if ($totalBettodayOne > 200000)
            Cache::put('TotalPointBetTodayByNumberUser-'.$userid.'-'.$game_code_temp.'-'.$bet_number.'-'.$now, ($totalBettodayOne-50000), env('CACHE_TIME', 24*60));
        echo $totalBettodayOne;
        return;
        print_r(XoSoRecordHelpers::lastestBetTime(7));
        return;
        $game = Game::join('location', 'games.location_id', '=', 'location.id')
        ->select('games.*','location.name as location','location.slug as locationslug')
        ->where('game_code',14)->first();

			$game_code = $game->game_code;
			if ($game_code >= 31 && $game_code <= 55)
				$game_code = 24;
			
			$TotalBetTodayByGameArr = XoSoRecordHelpers::calThauAdminGameLatest($game_code,$game->latestID);
			$TotalBetTodayByGame = $TotalBetTodayByGameArr['total9'] + Cache::get('TotalBetTodayByGameThau-'.$game_code, 0);
			//XoSoRecordHelpers::TotalBetTodayByGameThau($game_code);
			Cache::put('TotalBetTodayByGameThau-'.$game_code, $TotalBetTodayByGame, env('CACHE_TIME', 24*60));

			$TotalBetTodayByGameOrg = $TotalBetTodayByGameArr['total8'] + Cache::get('TotalBetTodayByGameOrg-'.$game_code, 0);;
			Cache::put('TotalBetTodayByGameOrg-'.$game_code, $TotalBetTodayByGameOrg, env('CACHE_TIME', 24*60));

			$totalBetAll = $TotalBetTodayByGameOrg;
			if ($totalBetAll==0){
			// 	$game->totalbet = $totalBetAll;
			// 	// continue;
				$game->status_cal = 0;
				$game->save();
				echo 'save status_cal -1';
				return;
			}
			
			if (isset($game->totalbet) && intval($game->totalbet) == $totalBetAll){
				$game->status_cal = 0;
				$game->save();
				echo 'save status_cal -1';
				return;
			} 
			$game->totalbet = $totalBetAll;
			$game->save();
			// $arrbetnumber=array();
			// $arrbetnumber1=array();
			$arrbetnumber = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
        	$arrbetnumber1 = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];

			$savetotalbetnumber='';

			$game->locknumberauto = "";
			// $game->locksuper = "";

			// $TotalBetTodayByNumberAll = DB::table('xoso_record')
			// 	        ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
            //             ->orderBy('sumbet', 'desc')
            //             ->where('isDelete',false)
            //             ->where('date',date('Y-m-d'))
            //             ->where('game_id', $game_code)
            //             ->groupBy('bet_number')
            //             ->get();

			$customerType = Cache::remember('CustomerType_Game-'.$game_code.'-A'.'-'.'274', env('CACHE_TIME_SHORT', 0), function () use ($game_code) {
				return 
				CustomerType_Game::where('game_id',$game_code)
					->where('created_user',274)
					->where('code_type','A')
					->first();
				});

			for($i=0;$i<10;$i++)
				for($j=0;$j<10;$j++){
					$bet_number = $i.$j;
					if ($game_code>=721 && $game_code<=739){
						if ($bet_number != '00') break;
					}

					$TotalBetTodayByNumberPre = Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0]);
					$TotalBetTodayByNumber =  [$TotalBetTodayByGameArr['totalNumber'][$i*10+$j] + $TotalBetTodayByNumberPre[0],$TotalBetTodayByGameArr['totalNumberThau'][$i*10+$j]+ $TotalBetTodayByNumberPre[1]];
					Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));
					
					$totalBetByNumber = $TotalBetTodayByNumber[1];
					$totalBetByNumber1 = $TotalBetTodayByNumber[0];

					$arrbetnumber[$i*10+$j]= $totalBetByNumber;
					$arrbetnumber1[$i*10+$j]= $totalBetByNumber1;

					if ($totalBetByNumber<=0){	
						continue;
					}
					if (isset($game->totalbet) && intval($game->totalbet) == $totalBetByNumber1){
						continue;
					} 
					$min=1;
					$max=1;
					// if ($min==0 && $max==0){
					// 	continue;
					// }

					// $customerType =  CustomerType_Game::where('game_id',$game_code)
					// 	->where('created_user',274)
					// 	->where('code_type','A')
					// 	->first();
					if ($totalBetByNumber-$min > $customerType->change_max_one){
						$game->locknumberauto .= ','.$bet_number;
					}
					// $lockSuperNumber = XoSoRecordHelpers::checkLockSuper($game_code,$bet_number);
					// $game->locksuper .= $lockSuperNumber . "||";
					
					Queue::pushOn("high",new UpdateBetPriceAllUser_v2($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,$min,$max));

				}

			$savetotalbetnumber = implode("|",$arrbetnumber);
			$savetotalbetnumber1 = implode("|",$arrbetnumber1);

			$game->totalbetnumber = $savetotalbetnumber;
			$game->totalbetnumber1 = $savetotalbetnumber1;

			$game->status_cal = 0;
			$game->save();
			echo 'save status_cal -1';
            return;


        $game_code = 14;
        $TotalBetTodayByGameArr = XoSoRecordHelpers::calThauAdminGame($game_code);
        print_r($TotalBetTodayByGameArr);
        return;
        for($i=0;$i<10;$i++)
				for($j=0;$j<10;$j++){
					$bet_number = $i.$j;
					if ($game_code>=721 && $game_code<=739){
						if ($bet_number != '00') break;
					}
					$TotalBetTodayByNumber =  [$TotalBetTodayByGameArr['totalNumber'][$i*10+$j],$TotalBetTodayByGameArr['totalNumberThau'][$i*10+$j]];
					// Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));
                    // echo 'TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number;
                    print_r($TotalBetTodayByNumber);
                    // echo 'TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number;
                    // print_r(Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number,[0,0]));
                    break;
				}
        return;

        $games = GameHelpers::GetAllGame(1);
        foreach($games as $game){
            $game_code = $game->game_code;
            Cache::put('TotalBetTodayByNumberThau-'.$game_code, 0, env('CACHE_TIME', 24*60));
            for($i=0;$i<10;$i++)
                    for($j=0;$j<10;$j++){
                        $bet_number = $i.$j;
                        if ($game_code>=721 && $game_code<=739){
                            if ($bet_number != '00') break;
                        }
                        // $TotalBetTodayByNumber =  [$TotalBetTodayByGameArr['totalNumber'][$i*10+$j],$TotalBetTodayByGameArr['totalNumberThau'][$i*10+$j]];
                        Cache::put('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0], env('CACHE_TIME', 24*60));
                        // echo 'TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number;
                        // print_r($TotalBetTodayByNumber);
                        // echo $bet_number;
                        // print_r(Cache::get('TotalBetTodayByNumberThau-14-'.$bet_number,[0,0]));
                        // break;
                    }
        }
        
        return;

        // $stat1 = file('/proc/stat'); 
        // sleep(1); 
        // $stat2 = file('/proc/stat'); 
        // $info1 = explode(" ", preg_replace("!cpu +!", "", $stat1[0])); 
        // $info2 = explode(" ", preg_replace("!cpu +!", "", $stat2[0])); 
        // $dif = array(); 
        // $dif['user'] = $info2[0] - $info1[0]; 
        // $dif['nice'] = $info2[1] - $info1[1]; 
        // $dif['sys'] = $info2[2] - $info1[2]; 
        // $dif['idle'] = $info2[3] - $info1[3]; 
        // $total = array_sum($dif); 
        // $cpu = array(); 
        // foreach($dif as $x=>$y) $cpu[$x] = round($y / $total * 100, 1);
        // print_r($cpu);
        // // $load = sys_getloadavg();
        // // echo $load[0];
        // return;
        $allUsersMember = User::where('roleid', 6)
			->where('active', 0)
			->where('remain','>', 0)
			->orderBy('latestlogin','desc')
			->limit(10)
			->get();
			foreach($allUsersMember as $user){
				GameHelpers::UpdateMeFromParentEX6($user,$user,7,'00');
			}
            return;
        Queue::pushOn("high",new UpdateMeFromParentEXService("satomember"));
        return;
        $ccc = CustomerType_Game::
        where('code_type', 'A')
        ->where('created_user', 274)
        ->groupBy('game_id')
        ->get();
        print_r($ccc);
        return;
        $lastestBettime = XoSoRecordHelpers::lastestBetTime(14);
        $game = GameHelpers::GetGameByCode(14);
        echo $lastestBettime .' '.$game->lastestBet;

        if ($game->lastestBet >= $lastestBettime){
				// $game->status_cal = 0;
				// $game->save();
				echo 'save status_cal -1';
				// return;
		}else{
            $game->lastestBet = $lastestBettime;
            $game->save();
            echo "calculator";
        }
         

        return;
        $a =  CustomerType_Game::where('game_id',4001)
                    ->where('created_user',1280)->first();
        SabaHelpers::SetMemberBetSetting('luk00111',$a);
        // print_r(SabaHelpers::CreateMember('luk00111_1'));
        // print_r(SabaHelpers::Logout('luk00111'));
        // print_r(SabaHelpers::CheckUsrBalance('luk00111_1'));
        
        return;
        LiveCasinoHelpers::Logout('kkka9999');
        LiveCasinoHelpers::Logout('luk00111');
        return ;
        // $TotalBetTodayByNumber = XoSoRecordHelpers::TotalBetTodayByNumberThau(7,'25');
        // Cache::put('TotalBetTodayByNumberThau-'.'7'.'-'.'25', $TotalBetTodayByNumber, env('CACHE_TIME', 24*60));
        // print_r($TotalBetTodayByNumber);
        $TotalBetTodayByGame = Cache::get('TotalBetTodayByGameThau-'.'7',[0,0]);
        print_r($TotalBetTodayByGame);
        $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-'.'7'.'-'.'36',[0,0]);
        print_r($TotalBetTodayByNumber);
        return;
        echo date("Y-m-d",strtotime('-1 day',strtotime('2022-09-09'))) .' 11:00:00';
        return;
        $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-'.'14'.'-'.'00',[0,0]);
        print_r($TotalBetTodayByNumber);
        return
        // $datetime = new DateTime('2020-06-03 16:38:57');
        // $now = date('Y-m-d');
        $now = date('2020-06-03');
        $hour = 16;

        $minus = 38;

        $xoso = new XoSo();

        $rs = $xoso->getKetQuaKeno(5,($hour/1),($minus - $minus%10),$now);
        $result = $rs;
        // $records = XoSoRecordHelpers::GetByDate($now,5);
        // $record = $records[0];

        // $win = GameHelpers::CheckDuoiKeno('00',$rs);

        $totalResult = GameHelpers::BuildArrayResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $tren = 0;
        $duoi = 0;
        foreach($totalResult as $item){
            if ($item <= 40)
                $tren++;
            else $duoi++;
        }
        

        $win_bet = array();
        if ($tren < $duoi)
            echo 'duoi';
            echo $tren.' '.$duoi;
        print_r($totalResult);
        // print_r($win);
        return;

        // $this->trathuong($records,$rs,$now);
		// $xoso = new XoSo();
		// $datetime = new DateTime('2020-05-14');
		// $rs = $xoso->getKetQuaXSA(4,14,$datetime);
		// // $records1 = XoSoRecordHelpers::GetXSAByDate($yesterday,24);
		// $records = XoSoRecordHelpers::GetXSAByDate('2020-05-14',14);

		// // print_r(count($records));

		// $this->trathuong($rs,$records);
		
		// return;


		// $now = date('2020-05-16');
		// $xoso = new XoSo();
		// $rs = $xoso->getKetQua(21,$now);
		// $win = GameHelpers::CheckLoTruot1('42',$rs);
		// // $win = GameHelpers::CheckGiaiX('66',$rs,'3',2);
		// print_r($win);
		// return;

		// $number1= '-1';
		// echo intval($number1);
		
		// $redis = Cache::getRedis();
		// $keys = $redis->keys(Cache::getPrefix() . "xs8386");
		// foreach($keys as $key) { Cache::forget($key); }
		// \Log::info('i was @ ' . \Carbon\Carbon::now());

		// Cache::tags('TotalBetTodayByGame')->flush();
		// Cache::tags('TotalBetTodayByNumber')->forget('TotalBetTodayByNumber-'.$request->game_code.'-'.$record->bet_number);
		// Cache::tags('TotalBetTodayByGame')->put('TotalBetTodayByGame-14',100,14);
		// Cache::tags('TotalBetTodayByGame')->forget('TotalBetTodayByGame-14');
		// echo Cache::tags('TotalBetTodayByGame')->get('TotalBetTodayByGame-14');
	}

	public function trathuong($rs,$records){
		// $now = date('Y-m-d');
        // $hour = date('H');
        // $hour = 0;
        // $xoso = new XoSo();
        // if (date('H') == 0){
        //     $datetime = new DateTime('yesterday');
        //     $yesterday = $datetime->format('Y-m-d');
		//     $rs = $xoso->getKetQuaXSA(4,24,$yesterday);
        //     // $records1 = XoSoRecordHelpers::GetXSAByDate($yesterday,24);
        //     $records = XoSoRecordHelpers::GetXSAByDate($now,24);
        //     // $records = array_merge($records1,$records2);
        // }else{
        //     $rs = $xoso->getKetQuaXSA(4,round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN),$now);
        //     // print_r($rs);
        //     $records = XoSoRecordHelpers::GetXSAByDate($now,round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN));    
        // }
        // echo(count($records));
        if (!isset($rs) || count($rs) < 1){
            echo "Chua co ket qua";
            return;
        }
        foreach ($records as $record)
        {
            if($record->total_bet_money == 0)
				continue;
			
			echo($record['id'].'-');
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
}
