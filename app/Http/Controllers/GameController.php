<?php
namespace App\Http\Controllers;

use App\ChucNang;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\Request;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\UserHelpers;
use App\Helpers\GameHelpers;
use App\Helpers\HistoryHelpers;
use App\Helpers\NotifyHelpers;
use App\Helpers\QuickbetHelpers;
use App\History;
use App\User;
use Illuminate\Support\Facades\Cache;
use App\XoSoResult;
use Exception;
use luk79\CryptoJsAes\CryptoJsAes;

class GameController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex()
    {


    }
    public function postStore(Request $request)
    {
        try{
            $name = $request->session()->get('username');
            $usercreate = UserHelpers::GetUserByUserName($name);
            $insertBet = XoSoRecordHelpers::InsertXosoRecord($request,$usercreate[0],true,true);
            $statusIns = $insertBet["status"];
            if ($statusIns == 'ok')
                HistoryHelpers::InsertHistoryBet($request,$usercreate[0],$insertBet["ids"]);
            return $statusIns;
        }catch(\Exception $err){
            throw $err;
            return 'error Store:' .$err->getFile().'-'. $err->getLine() .'-'. $err->getMessage();
        }
    }

    public function postApiStore(Request $request)
    {
        try{
            // $name = $request->session()->get('username');
            $usercreate=auth()->user();
            // $usercreate = UserHelpers::GetUserByUserName($name);
            return response()->json(['code'=>200,'message'=>'','data' => XoSoRecordHelpers::InsertXosoRecord($request,$usercreate) ]);

            // return ;
        }catch(\Exception $err){
            throw $err;
            return 'error Store:' .$err->getFile().'-'. $err->getLine() .'-'. $err->getMessage();
        }
    }

    public function postStorexien2(Request $request)
    {
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        $return_str = "";
        $game_code = $request->game_code;
        $request2 = $request;
        $request2->choices = $request->choices2;
        if ($request2->choices[0]['exchange'] > 0){
            switch ($game_code) {
                case 2:
                    $request2->game_code = 9;
                    break;
                case 302:
                    $request2->game_code = 309;
                        break;
                case 402:
                    $request2->game_code = 409;
                    break;
                case 502:
                    $request2->game_code = 509;
                    break;
                case 602:
                    $request2->game_code = 609;
                    break;
                case 702:
                    $request2->game_code = 709;
                    break;
                default:
                    $request2->game_code = 9;
                    break;
            }
            // $request2->game_code = 9;
            $request2->odds = $request->odds2;
            $insertBet = XoSoRecordHelpers::InsertXosoRecord($request2,$usercreate[0],true,true);
            $statusIns = $insertBet["status"];
            // $return_str.= $statusIns;
            if ($statusIns == 'ok'){
                HistoryHelpers::InsertHistoryBet($request2,$usercreate[0],$insertBet["ids"]);
                $return_str.= 'Lô xiên 2: thành công. ';
            }else{
                $return_str.= 'Lô xiên 2: '.$statusIns.' ';
            }
        }

        $requestxn = $request;
        $requestxn->choices = $request->choicesxn;
        if ($requestxn->choices[0]['exchange'] > 0){
            switch ($game_code) {
                case 2:
                    $requestxn->game_code = 29;
                    break;
                case 302:
                    $requestxn->game_code = 309;
                        break;
                case 402:
                    $requestxn->game_code = 409;
                    break;
                case 502:
                    $requestxn->game_code = 509;
                    break;
                case 602:
                    $requestxn->game_code = 609;
                    break;
                case 702:
                    $requestxn->game_code = 709;
                    break;
                default:
                    $requestxn->game_code = 9;
                    break;
            }
            // $request2->game_code = 9;
            $requestxn->odds = $request->oddsxn;
            $insertBet = XoSoRecordHelpers::InsertXosoRecord($requestxn,$usercreate[0],true,true);
            $statusIns = $insertBet["status"];
            // $statusIns = XoSoRecordHelpers::InsertXosoRecord($requestxn,$usercreate[0]);
            // $return_str.= $statusIns;
            if ($statusIns == 'ok'){
                HistoryHelpers::InsertHistoryBet($requestxn,$usercreate[0],$insertBet["ids"]);
                $return_str.= 'Lô xiên nháy: thành công. ';
            }else{
                $return_str.= 'Lô xiên nháy: '.$statusIns.' ';
            }
        }

        $request3 = $request;
        $request3->choices = $request->choices3;
        if ($request3->choices[0]['exchange'] > 0){
            
            switch ($game_code) {
                case 2:
                    $request3->game_code = 10;
                    break;
                case 302:
                    $request3->game_code = 310;
                        break;
                case 402:
                    $request3->game_code = 410;
                    break;
                case 502:
                    $request3->game_code = 510;
                    break;
                case 602:
                    $request3->game_code = 610;
                    break;
                case 702:
                    $request3->game_code = 710;
                    break;
                default:
                    $request3->game_code = 10;
                    break;
            }
            $request3->odds = $request->odds3;
            $insertBet = XoSoRecordHelpers::InsertXosoRecord($request3,$usercreate[0],true,true);
            $statusIns = $insertBet["status"];
            // $statusIns = XoSoRecordHelpers::InsertXosoRecord($request3,$usercreate[0]);
            
            if ($statusIns == 'ok'){
                HistoryHelpers::InsertHistoryBet($request3,$usercreate[0],$insertBet["ids"]);
                $return_str.= 'Lô xiên 3: thành công. ';
            }else{
                $return_str.= 'Lô xiên 3: '.$statusIns.' ';
            }
                

            // $return_str.=XoSoRecordHelpers::InsertXosoRecord($request3,$usercreate[0]);
        }
        $request4 = $request;
        $request4->choices = $request->choices4;
        if ($request4->choices[0]['exchange'] > 0){
            
            switch ($game_code) {
                case 2:
                    $request4->game_code = 11;
                    break;
                case 302:
                    $request4->game_code = 311;
                        break;
                case 402:
                    $request4->game_code = 411;
                    break;
                case 502:
                    $request4->game_code = 511;
                    break;
                case 602:
                    $request4->game_code = 611;
                    break;
                case 702:
                    $request4->game_code = 711;
                    break;
                default:
                    $request4->game_code = 11;
                    break;
            }
            $request4->odds = $request->odds4;
            $insertBet = XoSoRecordHelpers::InsertXosoRecord($request4,$usercreate[0],true,true);
            $statusIns = $insertBet["status"];
            // $return_str.=XoSoRecordHelpers::InsertXosoRecord($request4,$usercreate[0]);
            // $statusIns = XoSoRecordHelpers::InsertXosoRecord($request4,$usercreate[0]);
            // $return_str.= $statusIns;
            if ($statusIns == 'ok'){
                HistoryHelpers::InsertHistoryBet($request4,$usercreate[0],$insertBet["ids"]);
                $return_str.= 'Lô xiên 4: thành công. ';
            }else{
                $return_str.= 'Lô xiên 4: '.$statusIns.' ';
            }
        }

        $return_str = str_replace('error021','Vượt giới hạn cược.',$return_str);
        return $return_str;
    }

    public function getLoadNumber(Request $request,$gamecode){
        if($gamecode==8|| $gamecode==308 || $gamecode==408 || $gamecode==508 || $gamecode==608)
        {
            return view
            (
                'frontend.control.gameplay_1000',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'gamecode'=>$gamecode
                ]
            );
        }
        if($gamecode==17  || $gamecode==56 || $gamecode==317 || $gamecode==417 || $gamecode==517 || $gamecode==617
        || $gamecode==352 || $gamecode==452 || $gamecode==552 || $gamecode==652)
        {
            return view
            (
                'frontend.control.gameplay_1000',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'gamecode'=>$gamecode
                ]
            );
        }

        // if($gamecode==22)
        // {
        //     return view
        //     (
        //         'frontend.control.gameplay_1000',
        //         [
        //             'game'=> GameHelpers::GetGameByCode($gamecode),
        //             'gamecode'=>$gamecode
        //         ]
        //     );
        // }

        if($gamecode==700)
        {
            $gameByCode = [];
            $gameByCode['game721'] = GameHelpers::GetGameByCode(721);
            $gameByCode['game722'] = GameHelpers::GetGameByCode(722);
            $gameByCode['game723'] = GameHelpers::GetGameByCode(723);
            $gameByCode['game724'] = GameHelpers::GetGameByCode(724);
            $gameByCode['game725'] = GameHelpers::GetGameByCode(725);
            $gameByCode['game726'] = GameHelpers::GetGameByCode(726);
            $gameByCode['game727'] = GameHelpers::GetGameByCode(727);
            $gameByCode['game728'] = GameHelpers::GetGameByCode(728);
            $gameByCode['game729'] = GameHelpers::GetGameByCode(729);
            $gameByCode['game730'] = GameHelpers::GetGameByCode(730);
            $gameByCode['game731'] = GameHelpers::GetGameByCode(731);
            $gameByCode['game732'] = GameHelpers::GetGameByCode(732);
            $gameByCode['game733'] = GameHelpers::GetGameByCode(733);
            $gameByCode['game734'] = GameHelpers::GetGameByCode(734);
            $gameByCode['game735'] = GameHelpers::GetGameByCode(735);
            $gameByCode['game736'] = GameHelpers::GetGameByCode(736);
            $gameByCode['game737'] = GameHelpers::GetGameByCode(737);
            $gameByCode['game738'] = GameHelpers::GetGameByCode(738);
            $gameByCode['game739'] = GameHelpers::GetGameByCode(739);

            return view
            (
                'frontend.control.gameplay_keno',
                [
                    'gameByCode'=> $gameByCode,
                    'gamecode'=>$gamecode,

                    'gamecode721'=>721,
                    'gamecode722'=>722,
                    'gamecode723'=>723,
                    'gamecode724'=>724,
                    'gamecode725'=>725,
                    'gamecode726'=>726,
                    'gamecode727'=>727,
                    'gamecode728'=>728,
                    'gamecode729'=>729,
                    'gamecode730'=>730,
                    'gamecode731'=>731,
                    'gamecode732'=>732,
                    'gamecode733'=>733,
                    'gamecode734'=>734,
                    'gamecode735'=>735,
                    'gamecode736'=>736,
                    'gamecode737'=>737,
                    'gamecode738'=>738,
                    'gamecode739'=>739,
                ]
            );
        }

        if($gamecode==2)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(9),
                    'game3'=> GameHelpers::GetGameByCode(10),
                    'game4'=> GameHelpers::GetGameByCode(11),
                    'gamexn'=> GameHelpers::GetGameByCode(29),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>9,
                    'gamecode3'=>10,
                    'gamecode4'=>11,
                    'gamecodexn'=>29,
                ]
            );
        }

        if($gamecode==302)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(309),
                    'game3'=> GameHelpers::GetGameByCode(310),
                    'game4'=> GameHelpers::GetGameByCode(311),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>309,
                    'gamecode3'=>310,
                    'gamecode4'=>311,
                ]
            );
        }

        if($gamecode==402)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(409),
                    'game3'=> GameHelpers::GetGameByCode(410),
                    'game4'=> GameHelpers::GetGameByCode(411),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>409,
                    'gamecode3'=>410,
                    'gamecode4'=>411,
                ]
            );
        }

        if($gamecode==502)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(509),
                    'game3'=> GameHelpers::GetGameByCode(510),
                    'game4'=> GameHelpers::GetGameByCode(511),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>509,
                    'gamecode3'=>510,
                    'gamecode4'=>511,
                ]
            );
        }

        if($gamecode==602)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(609),
                    'game3'=> GameHelpers::GetGameByCode(610),
                    'game4'=> GameHelpers::GetGameByCode(611),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>609,
                    'gamecode3'=>610,
                    'gamecode4'=>611,
                ]
            );
        }

        if($gamecode==702)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(709),
                    'game3'=> GameHelpers::GetGameByCode(710),
                    'game4'=> GameHelpers::GetGameByCode(711),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>709,
                    'gamecode3'=>710,
                    'gamecode4'=>711,
                ]
            );
        }

        //XSAO
        if($gamecode==108)
        {
            return view
            (
                'frontend.control.gameplay_1000',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'gamecode'=>$gamecode
                ]
            );
        }
        if($gamecode==117)
        {
            return view
            (
                'frontend.control.gameplay_1000',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'gamecode'=>$gamecode
                ]
            );
        }

        // if($gamecode==122)
        // {
        //     return view
        //     (
        //         'frontend.control.gameplay_1000',
        //         [
        //             'game'=> GameHelpers::GetGameByCode($gamecode),
        //             'gamecode'=>$gamecode
        //         ]
        //     );
        // }

        if($gamecode==102)
        {
            return view
            (
                'frontend.control.gamexienplay_100',
                [
                    'game2'=> GameHelpers::GetGameByCode(9),
                    'game3'=> GameHelpers::GetGameByCode(10),
                    'game4'=> GameHelpers::GetGameByCode(11),
                    'gamexn'=> GameHelpers::GetGameByCode(29),
                    'gamecode'=>$gamecode,
                    'gamecode2'=>9,
                    'gamecode3'=>10,
                    'gamecode4'=>11,
                    'gamecodexn'=>29,
                ]
            );
        }
        return view
        (
            'frontend.control.gameplay_100',
            [
                'game'=> GameHelpers::GetGameByCode($gamecode),
                'gamecode'=>$gamecode
            ]
        );
        return view
        (

            'frontend.control.gameplay_100',
            [
                'game'=> GameHelpers::GetGameByCode($gamecode)
            ]
        );
    }
    public function getLoadGame($col,$game_code){
        return view
        (
            'frontend.control.gameplay_1000_Tab',
            [
                't'=>$col,
                'game'=> GameHelpers::GetGameByCode($game_code)
            ]
        );
    }
    public function getReloadUser(Request $request){
        return view
        (
            'frontend.control.user_info',
            [
                'user'=> Auth::user()
            ]
        );
    }

    public function getPriceApi(Request $request)
    {
        try{

            
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            // if ($user->lock == 1)
            //     return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);

            $requestD = CryptoJsAes::decryptRequest($request);
            $request = $requestD;
            $listnumber = array();
            
            if ($request->game_code == 2 || $request->game_code == 102){

            }else{
                if ($request->game_code == 18){
                    $dataAll = GameHelpers::GetGame_AllNumber(18);
                    $datachuan = GameHelpers::GetByCusTypeGameCode(18,$user->customer_type);
                    $game = GameHelpers::GetGameByCode(7);
                }else if ($request->game_code >= 31 && $request->game_code <= 55){
                    $dataAll = GameHelpers::GetGame_AllNumber(24);
                    $datachuan = GameHelpers::GetByCusTypeGameCode(24,$user->customer_type);
                    $game = GameHelpers::GetGameByCode(24);
                }
                else{
                    $dataAll = GameHelpers::GetGame_AllNumber($request->game_code);
                    $datachuan = GameHelpers::GetByCusTypeGameCode($request->game_code,$user->customer_type);
                    $game = GameHelpers::GetGameByCode($request->game_code);
                }
                
                if ($request->game_code == "8" || $request->game_code == "17"|| $request->game_code == "56"|| $request->game_code == "108" || $request->game_code == "117"
                || $request->game_code == "308" || $request->game_code == "317"
                || $request->game_code == "408" || $request->game_code == "417"
                || $request->game_code == "508" || $request->game_code == "517"
                || $request->game_code == "608" || $request->game_code == "617"
                || $request->game_code == "352" || $request->game_code == "452"
                || $request->game_code == "552" || $request->game_code == "652"){
                    for($i=0;$i<10;$i++)
                        for($j=0;$j<10;$j++)
                            for($k=0;$k<10;$k++)
                    {
                        // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j.$k);
                        
                        $data = [];
                        foreach($dataAll as $struct) {
                            if ($i.$j.$k == $struct->number) {
                                $data = $struct;
                                break;
                            }
                        }

                        $exchange_rates = "";
                        if(count($data)>0) {
                            // if(count($datachuan)){
                            //  $g = bcadd($game['exchange_rates'],'0',2);
                            //  $num = bcadd($datachuan['exchange_rates'],'0',2);
                            //  $chuan = bcadd($data['exchange_rates'],'0',2);
                            //  $exchange_rates =  round($chuan*$num/$g);
                            // }
                            // else
                            // {
                                
                            // }
                            $exchange_rates = $data['exchange_rates'];
                        }
                        else{
                            if(count($datachuan)>0){
                                $exchange_rates =  $datachuan['exchange_rates'];
                            }
                            else
                            {
                                $exchange_rates =  $game['exchange_rates'];
                            }
                        }

                        // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j.$k);
                        // $exchange_rates = "";
                        // $a = "";
                        // $x = "";
                        // if(count($data)>0) {
                        //     $exchange_rates = $data['exchange_rates'];
                        //     $a = $data['a'];
                        //     $x = $data['x'];
                        //     $total = $data['total'];
                        // }
                        // else{
                        //     $exchange_rates =  $game['exchange_rates'];
                        //     $a = $game['a'];
                        //     $x = $game['x'];
                        //     $total = 0;
                        // }
                        // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j.$k);
                        // if (rand(0,99) %10 ==0)
                        //     $listnumber[$i.$j.$k] = array($exchange_rates+rand(0,10),$a,$x,$total);
                        // else
                        array_push($listnumber,[
                            "number" => "".$i.$j.$k,
                            "price"  => $exchange_rates
                        ]);
                        // $listnumber[$i.$j]['a'] = $a;
                        // $listnumber[$i.$j]['x'] = $x;
                        // $listnumber[$i.$j]['total'] = $total;
                    }
                }else{
                    // $dataAll = GameHelpers::GetGame_AllNumber($request->game_code);
                    $datagoc = 21680;
                    if ($request->game_code == 18 || $request->game_code == 200 || $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11){
                        $now = date('Y-m-d');
                        $kqxs = XoSoResult::where('location_id', 1)
                        ->where('date', $now)->get();
                        if (count($kqxs) < 1)
                            $kqxsdr = 0;
                        else
                            $kqxsdr = $kqxs->first()->Giai_8;
                    }

                    if ($request->game_code == "721" || $request->game_code == "722" || $request->game_code == "723" ||
                    $request->game_code == "724" || $request->game_code == "725" || $request->game_code == "726" ||
                    $request->game_code == "727" || $request->game_code == "728" || $request->game_code == "729" ||
                    $request->game_code == "730" || $request->game_code == "731" || $request->game_code == "732" ||
                    $request->game_code == "733" || $request->game_code == "734" || $request->game_code == "735"
                    || $request->game_code == "736" || $request->game_code == "737" || $request->game_code == "738"
                    || $request->game_code == "739")
                    {
                        $i=0;$j=0;
                        // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);

                        $data = [];
                        foreach($dataAll as $struct) {
                            if ($i.$j == $struct->number) {
                                $data = $struct;
                                break;
                            }
                        }
                        
                        $exchange_rates = "";
                        if ( count($data)>0 && count($datachuan)>0){
                            $exchange_rates = $datachuan['exchange_rates'];
                            if ($data['exchange_rates'] > $datachuan['exchange_rates']){
                                $exchange_rates = $data['exchange_rates'];
                            }
                        }else
                        if(count($data)>0) {
                            $exchange_rates = $data['exchange_rates'];
                        }
                        else{
                            if(count($datachuan)>0){
                                $exchange_rates =  $datachuan['exchange_rates'];
                            }
                            else
                            {
                                $exchange_rates =  $game['exchange_rates'];
                            }
                        }

                        if ($request->game_code == 18)
                            if ($kqxsdr == 0) 
                                $exchange_rates = 803*(27-1) + ($exchange_rates-$datagoc);
                                else
                            if ($kqxsdr >= 25)
                                $exchange_rates= 0;
                            else
                                $exchange_rates = 803*(27-$kqxsdr-1) + ($exchange_rates-$datagoc);
                        
                        // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);
                        // $exchange_rates = "";
                        // $a = "";
                        // $x = "";
                        // if(count($data)>0) {
                        //     $exchange_rates = $data['exchange_rates'];
                        //     $a = $data['a'];
                        //     $x = $data['x'];
                        //     $total = $data['total'];
                        // }
                        // else{
                        //     $exchange_rates =  $game['exchange_rates'];
                        //     $a = $game['a'];
                        //     $x = $game['x'];
                        //     $total = 0;
                        // }
                        // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j);
                        // if (rand(0,99) %10 ==0)
                        //     $listnumber[$i.$j] = array($exchange_rates+rand(0,10),$a,$x,$total);
                        // else
                        array_push($listnumber,[
                            "number" => "".$i.$j,
                            "price"  => $exchange_rates
                        ]);
                        // $listnumber[$i.$j]['a'] = $a;
                        // $listnumber[$i.$j]['x'] = $x;
                        // $listnumber[$i.$j]['total'] = $total;
                    }else{
                        for($i=0;$i<10;$i++)
                            for($j=0;$j<10;$j++)
                        {
                            // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);

                            $data = [];
                            foreach($dataAll as $struct) {
                                if ($i.$j == $struct->number) {
                                    $data = $struct;
                                    break;
                                }
                            }
                            
                            $exchange_rates = "";
                            if ( count($data)>0 && count($datachuan)>0){
                                $exchange_rates = $datachuan['exchange_rates'];
                                if ($data['exchange_rates'] > $datachuan['exchange_rates']){
                                    $exchange_rates = $data['exchange_rates'];
                                }
                            }else
                            if(count($data)>0) {
                                $exchange_rates = $data['exchange_rates'];
                            }
                            else{
                                if(isset($datachuan['exchange_rates'])){
                                    $exchange_rates =  $datachuan['exchange_rates'];
                                }
                                else
                                {
                                    $exchange_rates =  $game['exchange_rates'];
                                }
                            }

                            if ($request->game_code == 18)
                                if ($kqxsdr == 0) 
                                    $exchange_rates = 830*(27-1) + ($exchange_rates-$datagoc);
                                    else
                                if ($kqxsdr >= 25)
                                    $exchange_rates= 0;
                                else
                                    $exchange_rates = 830*(27-$kqxsdr-1) + ($exchange_rates-$datagoc);
                            
                            // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);
                            // $exchange_rates = "";
                            // $a = "";
                            // $x = "";
                            // if(count($data)>0) {
                            //     $exchange_rates = $data['exchange_rates'];
                            //     $a = $data['a'];
                            //     $x = $data['x'];
                            //     $total = $data['total'];
                            // }
                            // else{
                            //     $exchange_rates =  $game['exchange_rates'];
                            //     $a = $game['a'];
                            //     $x = $game['x'];
                            //     $total = 0;
                            // }
                            // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j);
                            // if (rand(0,99) %10 ==0)
                            //     $listnumber[$i.$j] = array($exchange_rates+rand(0,10),$a,$x,$total);
                            // else
                            array_push($listnumber,[
                                "number" => "".$i.$j,
                                "price"  => $exchange_rates
                            ]);
                            // $listnumber[$i.$j]['a'] = $a;
                            // $listnumber[$i.$j]['x'] = $x;
                            // $listnumber[$i.$j]['total'] = $total;
                        }
                    }
                }
                if ($request->game_code == 18 || $request->game_code == 200 || $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11)
                    return response()->json([
                        'code'=>200,
                        'message'=>'',
                        'data' => CryptoJsAes::encryptData($listnumber)
                    ]);
                else
                    return response()->json([
                        'code'=>200,
                        'message'=>'',
                        'data' => CryptoJsAes::encryptData($listnumber)
                    ]);
            }
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }
    }
    public function postGetnewdata(Request $request)
    {
        $listnumber = array();
        $user = Auth::user();
        
        if ($request->game_code == 2 || $request->game_code == 102){

        }else{
            if ($request->game_code == 18){
                $dataAll = GameHelpers::GetGame_AllNumber(18);
                $datachuan = GameHelpers::GetByCusTypeGameCode(18,$user->customer_type);
                $game = GameHelpers::GetGameByCode(7);
            }else if ($request->game_code >= 31 && $request->game_code <= 55){
                $dataAll = GameHelpers::GetGame_AllNumber(24);
                $datachuan = GameHelpers::GetByCusTypeGameCode(24,$user->customer_type);
                $game = GameHelpers::GetGameByCode(24);
            }
            else{
                $dataAll = GameHelpers::GetGame_AllNumber($request->game_code);
                $datachuan = GameHelpers::GetByCusTypeGameCode($request->game_code,$user->customer_type);
                $game = GameHelpers::GetGameByCode($request->game_code);
            }
            
            if ($request->game_code == "8" || $request->game_code == "17"|| $request->game_code == "56"|| $request->game_code == "108" || $request->game_code == "117"
            || $request->game_code == "308" || $request->game_code == "317"
            || $request->game_code == "408" || $request->game_code == "417"
            || $request->game_code == "508" || $request->game_code == "517"
            || $request->game_code == "608" || $request->game_code == "617"
            || $request->game_code == "352" || $request->game_code == "452"
            || $request->game_code == "552" || $request->game_code == "652"){
                for($i=0;$i<10;$i++)
                    for($j=0;$j<10;$j++)
                        for($k=0;$k<10;$k++)
                {
                    // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j.$k);
                    
                    $data = [];
                    foreach($dataAll as $struct) {
                        if ($i.$j.$k == $struct->number) {
                            $data = $struct;
                            break;
                        }
                    }

                    $exchange_rates = "";
                    if(count($data)>0) {
                        // if(count($datachuan)){
                        //  $g = bcadd($game['exchange_rates'],'0',2);
                        //  $num = bcadd($datachuan['exchange_rates'],'0',2);
                        //  $chuan = bcadd($data['exchange_rates'],'0',2);
                        //  $exchange_rates =  round($chuan*$num/$g);
                        // }
                        // else
                        // {
                            
                        // }
                        $exchange_rates = $data['exchange_rates'];
                    }
                    else{
                        if(count($datachuan)>0){
                            $exchange_rates =  $datachuan['exchange_rates'];
                        }
                        else
                        {
                            $exchange_rates =  $game['exchange_rates'];
                        }
                    }

                    // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j.$k);
                    // $exchange_rates = "";
                    // $a = "";
                    // $x = "";
                    // if(count($data)>0) {
                    //     $exchange_rates = $data['exchange_rates'];
                    //     $a = $data['a'];
                    //     $x = $data['x'];
                    //     $total = $data['total'];
                    // }
                    // else{
                    //     $exchange_rates =  $game['exchange_rates'];
                    //     $a = $game['a'];
                    //     $x = $game['x'];
                    //     $total = 0;
                    // }
                    // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j.$k);
                    // if (rand(0,99) %10 ==0)
                    //     $listnumber[$i.$j.$k] = array($exchange_rates+rand(0,10),$a,$x,$total);
                    // else
                        $listnumber[$i.$j.$k] = array($exchange_rates,0,0,0);
                    // $listnumber[$i.$j]['a'] = $a;
                    // $listnumber[$i.$j]['x'] = $x;
                    // $listnumber[$i.$j]['total'] = $total;
                }
            }else{
                // $dataAll = GameHelpers::GetGame_AllNumber($request->game_code);
                $datagoc = 21680;
                if ($request->game_code == 18 || $request->game_code == 200 || $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11){
                    $now = date('Y-m-d');
                    $kqxs = XoSoResult::where('location_id', 1)
                    ->where('date', $now)->get();
                    if (count($kqxs) < 1)
                        $kqxsdr = 0;
                    else
                        $kqxsdr = $kqxs->first()->Giai_8;
                }

                if ($request->game_code == "721" || $request->game_code == "722" || $request->game_code == "723" ||
                $request->game_code == "724" || $request->game_code == "725" || $request->game_code == "726" ||
                $request->game_code == "727" || $request->game_code == "728" || $request->game_code == "729" ||
                $request->game_code == "730" || $request->game_code == "731" || $request->game_code == "732" ||
                $request->game_code == "733" || $request->game_code == "734" || $request->game_code == "735"
                || $request->game_code == "736" || $request->game_code == "737" || $request->game_code == "738"
                || $request->game_code == "739")
                {
                    $i=0;$j=0;
                    // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);

                    $data = [];
                    foreach($dataAll as $struct) {
                        if ($i.$j == $struct->number) {
                            $data = $struct;
                            break;
                        }
                    }
                    
                    $exchange_rates = "";
                    if ( count($data)>0 && count($datachuan)>0){
                        $exchange_rates = $datachuan['exchange_rates'];
                        if ($data['exchange_rates'] > $datachuan['exchange_rates']){
                            $exchange_rates = $data['exchange_rates'];
                        }
                    }else
                    if(count($data)>0) {
                        $exchange_rates = $data['exchange_rates'];
                    }
                    else{
                        if(count($datachuan)>0){
                            $exchange_rates =  $datachuan['exchange_rates'];
                        }
                        else
                        {
                            $exchange_rates =  $game['exchange_rates'];
                        }
                    }

                    if ($request->game_code == 18)
                        if ($kqxsdr == 0) 
                            $exchange_rates = 803*(27-1) + ($exchange_rates-$datagoc);
                            else
                        if ($kqxsdr >= 25)
                            $exchange_rates= 0;
                        else
                            $exchange_rates = 803*(27-$kqxsdr-1) + ($exchange_rates-$datagoc);
                    
                    // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);
                    // $exchange_rates = "";
                    // $a = "";
                    // $x = "";
                    // if(count($data)>0) {
                    //     $exchange_rates = $data['exchange_rates'];
                    //     $a = $data['a'];
                    //     $x = $data['x'];
                    //     $total = $data['total'];
                    // }
                    // else{
                    //     $exchange_rates =  $game['exchange_rates'];
                    //     $a = $game['a'];
                    //     $x = $game['x'];
                    //     $total = 0;
                    // }
                    // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j);
                    // if (rand(0,99) %10 ==0)
                    //     $listnumber[$i.$j] = array($exchange_rates+rand(0,10),$a,$x,$total);
                    // else
                        $listnumber[$i.$j] = array($exchange_rates,0,0,0);
                    // $listnumber[$i.$j]['a'] = $a;
                    // $listnumber[$i.$j]['x'] = $x;
                    // $listnumber[$i.$j]['total'] = $total;
                }else{
                    for($i=0;$i<10;$i++)
                        for($j=0;$j<10;$j++)
                    {
                        // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);

                        $data = [];
                        foreach($dataAll as $struct) {
                            if ($i.$j == $struct->number) {
                                $data = $struct;
                                break;
                            }
                        }
                        
                        $exchange_rates = "";
                        if ( count($data)>0 && count($datachuan)>0){
                            $exchange_rates = $datachuan['exchange_rates'];
                            if ($data['exchange_rates'] > $datachuan['exchange_rates']){
                                $exchange_rates = $data['exchange_rates'];
                            }
                        }else
                        if(count($data)>0) {
                            $exchange_rates = $data['exchange_rates'];
                        }
                        else{
                            if(isset($datachuan['exchange_rates'])){
                                $exchange_rates =  $datachuan['exchange_rates'];
                            }
                            else
                            {
                                $exchange_rates =  $game['exchange_rates'];
                            }
                        }

                        if ($request->game_code == 18)
                            if ($kqxsdr == 0) 
                                $exchange_rates = 830*(27-1) + ($exchange_rates-$datagoc);
                                else
                            if ($kqxsdr >= 25)
                                $exchange_rates= 0;
                            else
                                $exchange_rates = 830*(27-$kqxsdr-1) + ($exchange_rates-$datagoc);
                        
                        // $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);
                        // $exchange_rates = "";
                        // $a = "";
                        // $x = "";
                        // if(count($data)>0) {
                        //     $exchange_rates = $data['exchange_rates'];
                        //     $a = $data['a'];
                        //     $x = $data['x'];
                        //     $total = $data['total'];
                        // }
                        // else{
                        //     $exchange_rates =  $game['exchange_rates'];
                        //     $a = $game['a'];
                        //     $x = $game['x'];
                        //     $total = 0;
                        // }
                        // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j);
                        // if (rand(0,99) %10 ==0)
                        //     $listnumber[$i.$j] = array($exchange_rates+rand(0,10),$a,$x,$total);
                        // else
                            $listnumber[$i.$j] = array($exchange_rates,0,0,0);
                        // $listnumber[$i.$j]['a'] = $a;
                        // $listnumber[$i.$j]['x'] = $x;
                        // $listnumber[$i.$j]['total'] = $total;
                    }
                }
            }
            if ($request->game_code == 18 || $request->game_code == 200 || $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11)
                return array($request->game_code,$listnumber,$kqxsdr,GameHelpers::LockNumberUser($request->game_code,Auth::user()));
            else
            return array($request->game_code,$listnumber,GameHelpers::LockNumberUser($request->game_code,Auth::user()));
        }
    }

    public function getConfirmBet(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";
    
            $arrUser = UserHelpers::GetAllUserV3(Auth::user());
            // if ($request->status == 1)
            return view('admin.games.confirms',['status' => $request->status, 'bets'=> History::orderBy('created_at','asc')->where("source_bet",">",0)->whereIn('users.id', $arrUser[0])->where("date",date("Y-m-d"))->join("users","users.id","=","history.user_create")->where("is_done","!=", 0)->select("history.*", "users.name as user_name")->get() ,
            'bets_nc'=> History::orderBy('created_at','asc')->where("source_bet",">",0)->whereIn('users.id', $arrUser[0])->where("date",date("Y-m-d"))->join("users","users.id","=","history.user_create")->where("is_done", 0)->select("history.*", "users.name as user_name")->get(), 'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
            // else return view('admin.games.confirms',['status' => $request->status, 'bets'=> History::orderBy('created_at','desc')->where("id_inday",">",0)->whereIn('users.id', $arrUser[0])->where("date",date("Y-m-d"))->join("users","users.id","=","history.user_create")->where("is_done", 0)->limit(100)->select("history.*", "users.name as user_name")->get() ,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
    }

    public function getNeedConfirmBet(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(6) )
                return "Cannot access this page! Failed!!!";
    
            $arrUser = UserHelpers::GetAllUserV3(Auth::user());
            $bet = History::where("id_inday",">",0)->where('history.id',$request->id)->whereIn('users.id', $arrUser[0])->where("date",date("Y-m-d"))->join("users","users.id","=","history.user_create")->select("history.*", "users.name as user_name")->first();
            return view('admin.games.need_confirm',['status' => $request->status, 'bet'=> $bet ,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
    }

    public function postEstimateCancelBet(Request $request)
    {
        if (Auth::user()->roleid == 5 || Auth::user()->roleid == 6)
        {
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(6) )
                return "Cannot access this page! Failed!!!";
            $arrUser = [];
            if (Auth::user()->roleid == 6) $arrUser[0] = [Auth::user()->id];
            else
                $arrUser = UserHelpers::GetAllUserV3(Auth::user());
            $bet_id = $request->id;
            $bet = History::where('history.id',$request->id)->whereIn('users.id', $arrUser[0])->join("users","users.id","=","history.user_create")->select("history.*", "users.name as user_name")->first();
            $user = UserHelpers::GetUserById($bet->user_create);
            $ids = explode(",",$bet->ids);
            $moneyCancel = 0;

            foreach ($ids as $id)
                $moneyCancel += XoSoRecordHelpers::EstimateBetCancel($id,$user)[1];
            
            return ["fee" => number_format($moneyCancel)];

            $arrUser = UserHelpers::GetAllUserV3(Auth::user());
            $bet = History::where("id_inday",">",0)->where('history.id',$request->id)->whereIn('users.id', $arrUser[0])->where("date",date("Y-m-d"))->join("users","users.id","=","history.user_create")->select("history.*", "users.name as user_name")->first();
            return view('admin.games.need_confirm',['status' => $request->status, 'bet'=> $bet ,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
    }

    public function postSaveConfirmBet(Request $request)
    {
        if (Auth::user()->roleid == 5 || Auth::user()->roleid == 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(6) )
                return "Cannot access this page! Failed!!!";
    
            $arrUser = [];
            if (Auth::user()->roleid == 6) $arrUser[0] = [Auth::user()->id];
            else
                $arrUser = UserHelpers::GetAllUserV3(Auth::user());
            $bet_id = $request->id;
            $bet = History::where('history.id',$request->id)->whereIn('users.id', $arrUser[0])->join("users","users.id","=","history.user_create")->select("history.*", "users.name as user_name")->first();
            if (isset($bet)){
                if($request->type == 'transition'){
                    $bet_edit = $request->confirmTextBet;
                    if (!isset($bet_edit) || $bet_edit == "") $bet_edit = $bet->content;
                    $bet->confirmed = $bet_edit;
                    $quickbet = new QuickbetHelpers();
                    $userMember = UserHelpers::GetUserById($bet->user_create);
                    $inBet = $quickbet->quickplaylogic($userMember, $bet_edit, '0', '', false);
                    // return $inBet[0];
                    if (count($inBet[0]) == 0) {
                        $bet->transition = "Tin cược không đúng.";
                        $bet->save();
                        return [0,'Tin cược không đúng.'];
                    } else {
                        $list_tin_cuoc = [];
                        $list_tin_huy = [];
                        $notes = "";
                        foreach ($inBet[0] as $requestCuoc) {
                            if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                                array_push($list_tin_cuoc, $requestCuoc);
                            else{
                                array_push($list_tin_huy, $requestCuoc);
                                $notes .= $requestCuoc['status'] . '<br>';
                            }
                        }
    
                        $tin_cuoc = $quickbet->revertquickplay($list_tin_cuoc, "\n");
                        $tin_huy = $quickbet->revertquickplay($list_tin_huy, "\n");
                        $bet->money = $inBet[2];
                        $bet->transition = $tin_cuoc;  
                        if($tin_huy != "")   $bet->transition .= '<br>' . 'Tin hủy: '.$tin_huy;  

                        if ($notes != ""){
                            $bet->transition = 'Tin hủy: '.$bet_edit; 
                            $bet->transition .= "<br>". $notes;
                            // $bet->save(); 
                            // return [0,'Xác nhận cược không thành công!'];
                        }
                    }
                    $bet->save();
                    return [1,'Xác nhận cược thành công!'];
                }
                
                if($request->type == 'cancel'){
                    $user = UserHelpers::GetUserById($bet->user_create);
                    if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) 
                    || ($bet->created_at < date("Y-m-d")))
                        return [0,'Hủy cược không thành công! Hết giờ hủy!'];

                    $txtMessage = "";
                    if (isset($bet->is_done) && $bet->is_done == 1){
                        $ids = explode(",",$bet->ids);
                        foreach ($ids as $id)
                            XoSoRecordHelpers::DeleteLotoByUser($id, $user,false);
                        $txtMessage = "Trả lại:" . " Tin ".$bet->id_inday . "\n";
                        $txtMessage .= "" . ( (isset($bet->transition) && $bet->transition != "" && !str_contains($bet->transition, "Tin nhận:")) ? $bet->transition : ((isset($bet->confirmed) && $bet->confirmed != "") ? $bet->confirmed : $bet->content));
                        $txtMessage = str_replace("<br>","\n",$txtMessage);
                    }else{
                        $txtMessage = "Trả lại:" . " Tin ".$bet->id_inday . "\n";
                        $txtMessage .= "" . ( (isset($bet->transition) && $bet->transition != "" && !str_contains($bet->transition, "Tin nhận:") ) ? $bet->transition : ((isset($bet->confirmed) && $bet->confirmed != "") ? $bet->confirmed : $bet->content));
                        $txtMessage = str_replace("<br>","\n",$txtMessage);
                    }
                    $bet->is_done = -1;

                    $bet->type_action = Auth::user()->roleid;
                    $bet->save();
                    
                    HistoryHelpers::sendMessageToMembersTree($user,$txtMessage);
                    return [1,'Hủy cược thành công!'];
                }

                if($request->type == 'bet'){
                    if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
                        return [0,'Vào cược không thành công! Hết giờ cược!'];
                    $quickbet = new QuickbetHelpers();
                    $checkBet = false;
                    $newTransition = "";
                    //logic checkbet
                    //begin
                    $bet_edit = $request->confirmTextBet;
                    if (!isset($bet_edit) || $bet_edit == "") $bet_edit = $bet->confirmed;
                    if (!isset($bet_edit) || $bet_edit == "") $bet_edit = $bet->content;
                    $bet->confirmed = $bet_edit;
                    $userMember = UserHelpers::GetUserById($bet->user_create);
                    $inBet = $quickbet->quickplaylogic($userMember, $bet_edit, '0', '', false);
                    // return $inBet;
                    if (count($inBet[0]) == 0) {
                        return [0,'Tin cược không đúng.'];
                    } else {
                        $list_tin_cuoc = [];
                        $list_tin_huy = [];
                        $notes = "";
                        foreach ($inBet[0] as $requestCuoc) {
                            if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                                array_push($list_tin_cuoc, $requestCuoc);
                            else{
                                array_push($list_tin_huy, $requestCuoc);
                                $notes .= $requestCuoc['status'] . '<br>';
                            }
                                
                        }
    
                        $tin_cuoc = $quickbet->revertquickplay($list_tin_cuoc, "\n");
                        $tin_huy = $quickbet->revertquickplay($list_tin_huy, "\n");
    
                        $newTransition = $tin_cuoc;  
                        if($tin_huy != "")   $newTransition .= '<br>' . 'Tin hủy: '.$tin_huy;  

                        if ($notes != ""){
                            $bet->transition = 'Tin hủy: '.$bet_edit; 
                            $bet->transition .= "<br>". $notes;
                            $bet->save(); 
                            // echo $notes;
                            return [0,'Vào cược không thành công!'];
                        }
                    }
                    //end

                    if ($newTransition == $bet->transition) $checkBet = true;

                    if ($checkBet){
                        $inBet = $quickbet->quickplaylogic($userMember, $bet_edit, '1', '', false,$bet->id);
                        if (count($inBet[0]) == 0) {
                            return [0,'Tin cược không đúng.'];
                        } else {
                            $list_tin_cuoc = [];
                            $list_tin_huy = [];
                            foreach ($inBet[0] as $requestCuoc) {
                                if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                                    array_push($list_tin_cuoc, $requestCuoc);
                                else
                                    array_push($list_tin_huy, $requestCuoc);
                            }
        
                            $tin_cuoc = $quickbet->revertquickplay($list_tin_cuoc, "\n");
                            $tin_huy = $quickbet->revertquickplay($list_tin_huy, "\n");
        
                            $bet->transition = $tin_cuoc;  
                            if($tin_huy != "")   $bet->transition .= '<br>' . 'Tin hủy: '.$tin_huy;  
                            $bet->is_done = 1;
                            $bet->save();
                            return [1,'Vào cược thành công!'];
                        }
                    }else{
                        $bet->transition = $newTransition;
                        $bet->save();
                        return [2,'Vào cược không thành công! Đã cập nhật tin dịch mới!'];
                    }
                }
            }
            return [0,'error'];
            $bet = History::where('history.id',$request->id)->whereIn('users.id', $arrUser[0])->where("date",date("Y-m-d"))->join("users","users.id","=","history.user_create")->whereRaw("is_done is NULL or is_done = 0")->select("history.*", "users.name as user_name")->first();
            return view('admin.games.need_confirm',['status' => $request->status, 'bet'=> $bet ,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
    }
    
}
