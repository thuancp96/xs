<?php

namespace App\Helpers;

use App\XoSoRecord;
use App\Helpers\XoSo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\History;
use App\Game_Number;
use App\Game;
use App\XoSoResult;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use App\Helpers\GameHelpers;
use App\Helpers\UserHelpers;
use App\Helpers\HistoryHelpers;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Facades\Log;
use App\Commands\PaymentLottery;
use App\Commands\saveFileHistoryService;
use App\Commands\UpdateBetPriceAllUser;
use App\Commands\UpdateChildEX;
use App\QuickPlayRecord;
use \Queue;
use \Cache;
use CURLFile;
use Exception;
use Excel;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Sunra\PhpSimple\HtmlDomParser;

class XoSoRecordHelpers
{
    public static function getAll($user, $location_id = 1)
    {
        $xoso_record = null;
        if ($user->roleid == 1) {
            $xoso_record = DB::table('xoso_record')
                ->orderBy('id', 'desc')->where('isDelete', false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                ->where('location.slug', '=', $location_id)
                ->get();
        } else {
            if ($user->roleid == 6) {
                $xoso_record = DB::table('xoso_record')
                    ->orderBy('id', 'desc')->where('isDelete', false)
                    ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                    ->join('location', 'games.location_id', '=', 'location.id')
                    ->join('users', 'users.id', '=', 'xoso_record.user_id')
                    ->where('users.id', $user->id)
                    ->where('location.slug', '=', $location_id)
                    ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                    ->get();
            } else {
                $xoso_record = DB::table('xoso_record')
                    ->orderBy('id', 'desc')->where('isDelete', false)
                    ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                    ->join('location', 'games.location_id', '=', 'location.id')
                    ->join('users', 'users.id', '=', 'xoso_record.user_id')
                    ->where('users.user_create', $user->id)
                    ->where('location.slug', '=', $location_id)
                    ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                    ->get();
            }
        }

        return $xoso_record;
    }

    public static function getRecordKhachByDate($user, $stDate, $endDate, $type = "all")
    {
        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        $xoso_record = [];
        $bbin_record = [];
        if (str_contains($type, "xoso") || $type == "all") {
            // $xoso_record = Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
            $xoso_record = DB::table('xoso_record')
                ->orderBy('id', 'desc')->where('isDelete', false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                ->where('users.id', $user->id)
                ->where('date', '>=', $stDate)
                ->where('date', '<=', $endDate)
                // ->where('total_win_money','<>',0)
                ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                // ->limit(8000)
                ->get();
            // $xoso_record=[];
            // });
            // Log::info(date("Y-m-d",strtotime('-1 day',strtotime($stDate))) .' 11:00:00');
            // Log::info(date("Y-m-d",strtotime($endDate)) .' 11:00:00');
        }

        // if (str_contains($type, "bbin") || $type == "all") {
        //     $bbin_record = DB::table('history_live_bet')
        //         // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
        //         ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
        //         ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
        //         ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //         ->where('username', $user->name)
        //         ->select('*', 'games.name as game')
        //         ->get();
        //     foreach ($bbin_record as $value) {
        //         array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . json_decode($value->jsoninfo)[0]->SerialID . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        //     }
        // }
        // if (str_contains($type, "saba") || $type == "all") {
        //     $saba_record = DB::table('history_saba_bet')
        //         ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
        //         ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
        //         ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //         ->where('username', $user->name)
        //         ->select('*', 'games.name as game')
        //         ->get();

        //     foreach ($saba_record as $value) {
        //         $dataResults = json_decode($value->jsoninfo);
        //         if ($value->gametype > 5000 && $value->gametype < 6000) {
        //             $serialID = "";
        //             $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         } else {
        //             if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //                 $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //             else
        //                 $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //             // $serialID =  $dataResults->leagueName .': ' .'('. $dataResults->betChoice. ')';
        //             // array_push($xoso_record, );
        //             // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. (json_decode($value->jsoninfo)->homeName) .' vs '. (json_decode($value->jsoninfo)->awayName) .'", "result": "'. $value->status .'"}')));

        //             $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }
        //     }
        // }

        if (str_contains($type, "7zball") || $type == "all") {
            $H_7zBall_record = DB::table('history_7zball_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
                ->where('username', $user->name)
                ->where('paid', 1)
                // ->whereIn('username', $arrUser[1])
                ->select('*', 'games.name as game')
                ->orderBy("history_7zball_bet.id", "desc")
                ->get();

            foreach ($H_7zBall_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bet_time = null;
                if (isset($dataResults->bet_match_current)) {
                    if ($dataResults->bet_type != "parlay") {
                        $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                        $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                    } else {
                    }
                }
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
                $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
                $record7zBall->rawBet = $dataResults;
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        if (str_contains($type, "minigame") || $type == "all") {
            $H_minigame_record = DB::table('history_minigame_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->where('username', $user->name)
                ->where('paid', 1)
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_minigame_record as $value) {
                $dataResults = json_decode($value->jsoninfo);

                $bonus = isset($value->bonus) ? $value->bonus :  "0,0,0,0,0,0,0,0,0,0";
                $recordMinigame = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
                $recordMinigame->rawBet = $dataResults;
                array_push($xoso_record, $recordMinigame);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }
        // var_dump($xoso_record);
        return $xoso_record;
    }

    public static function getRecordKhachByDatev2($user, $stDate, $endDate, $type = "all")
    {
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];
        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        $xoso_record = [];
        $bbin_record = [];
        if (str_contains($type, "xoso") || $type == "all") {
            // $xoso_record = Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
            $xoso_record = DB::table('xoso_record')
                ->orderBy('id', 'desc')->where('isDelete', false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                // ->where('users.id',$user->id)
                ->whereIn('users.id', $arrUser[0])
                ->where('date', '>=', $stDate)
                ->where('date', '<=', $endDate)
                ->where('total_win_money', '<>', 0)
                ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                ->get();
            // $xoso_record=[];
            // });
            // Log::info(date("Y-m-d",strtotime('-1 day',strtotime($stDate))) .' 11:00:00');
            // Log::info(date("Y-m-d",strtotime($endDate)) .' 11:00:00');
        }

        // if (str_contains($type, "bbin") || $type == "all") {
        //     $bbin_record = DB::table('history_live_bet')
        //         // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
        //         ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
        //         ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
        //         ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //         // ->where('username',$user->name)
        //         ->whereIn('username', $arrUser[1])
        //         ->select('*', 'games.name as game')
        //         ->get();
        //     foreach ($bbin_record as $value) {
        //         array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . json_decode($value->jsoninfo)[0]->SerialID . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        //     }
        // }

        if (str_contains($type, "7zball") || $type == "all") {
            $H_7zBall_record = DB::table('history_7zball_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('username', $arrUser[1])
                ->where("jsoninfo", "like", '%"bet_status":1%')
                ->where('paid', 1)
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_7zBall_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bet_time = null;
                if (isset($dataResults->bet_match_current)) {
                    if ($dataResults->bet_type != "parlay") {
                        $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                        $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                    } else {
                    }
                }
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
                $record7zBall->rawBet = $dataResults;
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        // if (str_contains($type, "minigame") || $type == "all") {
        //     $H_minigame_record = DB::table('history_minigame_bet')
        //         // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
        //         ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
        //         ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
        //         ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
        //         // ->where('username',$user->name)
        //         ->whereIn('username', $arrUser[1])
        //         ->where('paid', 1)
        //         ->select('*', 'games.name as game')
        //         ->get();

        //     foreach ($H_minigame_record as $value) {
        //         $dataResults = json_decode($value->jsoninfo);
        //         $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
        //         $recordMinigame = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
        //         $recordMinigame->rawBet = $dataResults;
        //         array_push($xoso_record, $recordMinigame);
        //     }
        //     // foreach ($bbin_record as $value){
        //     //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
        //     // }
        // }

        if (str_contains($type, "minigame") || $type == "all") {
            $H_minigame_record = DB::table('history_minigame_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('username', $arrUser[1])
                ->where('paid', 1)
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_minigame_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bonus = isset($value->bonus) ? $value->bonus :  "0,0,0,0,0,0,0,0,0,0";
                $recordMinigame = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
                $recordMinigame->rawBet = $dataResults;
                array_push($xoso_record, $recordMinigame);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }
        // if (str_contains($type,"saba")|| $type == "all"){
        //     $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($endDate))) .' 11:00:00')
        //         ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //         // ->where('username',$user->name)
        //         ->whereIn('username', $arrUser[1])
        //         ->select('*', 'games.name as game')
        //         ->get();

        //     foreach ($saba_record as $value){
        //         $dataResults = json_decode($value->jsoninfo);
        //         if ($value->gametype > 5000 && $value->gametype < 6000){
        //             $serialID = "";
        //             $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }else{
        //             if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //                 $serialID =  $dataResults->leagueName .': ' . $dataResults->homeName .' vs '. $dataResults->awayName . ' ('. $dataResults->betChoice. ')';
        //             else
        //                 $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') .': ' .'('. (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')'; 
        //             // $serialID =  $dataResults->leagueName .': ' .'('. $dataResults->betChoice. ')';
        //             // array_push($xoso_record, );
        //             // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. (json_decode($value->jsoninfo)->homeName) .' vs '. (json_decode($value->jsoninfo)->awayName) .'", "result": "'. $value->status .'"}')));

        //             $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }
        //     }
        // }
        // echo ' ' .count($xoso_record) .'-';
        return $xoso_record;
    }

    public static function getRecordKhachByDateHistorytv2Inprocess($user)
    {
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            $arrUser = [[$user->id], [$user->name]];
        if (count($arrUser[0]) <= 0) return [];
        $stDate = date("Y-m-d");
        $endDate = date("Y-m-d");
        $xoso_record = [];
        
        $H_7zBall_record = DB::table('history')
            ->where('history.created_at', '>=', $stDate . ' 00:00:00')
            ->where('history.created_at', '<=', $endDate . ' 23:59:59')
            ->join('users', 'users.id', '=', 'history.user_create')
            ->whereIn('users.id', $arrUser[0])
            ->where('paid',0)
            ->whereIn('is_done',[1])
            ->select('history.*', 'users.name as name')
            ->get();

        foreach ($H_7zBall_record as $value) {
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
            $string = preg_replace("/[\r\n]+/", "", $value->content);
            $content_Str = $string;
            $record7zBall = (json_decode('{' . '"id":"' . $value->id . '","game_id":' . 1 . ',"bonus":"' . $bonus . '","total_bet_money":' . (isset($value->money)?$value->money:0) . ',"com":' . 0 . ',"odds":1,"exchange_rates":1,"total_win_money":' . (isset($value->money)?$value->payoff:0) . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->created_at . '","updated_at":"' . $value->created_at . '","xien_id":0,"game":"' . 1 . '","name":"' . $value->name . '","content":"' . $content_Str . '","location":"Xổ số miền bắc","locationslug":"1", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $value;
            array_push($xoso_record, $record7zBall);
        }
        $H_7zBall_record = DB::table('history_7zball_bet')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('history_7zball_bet.paid','!=', 1)
            ->whereIn('username', $arrUser[1])
            ->select('*', 'games.name as game')
            ->get();

        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bet_time = null;
            if (isset($dataResults->bet_match_current)) {
                if ($dataResults->bet_type != "parlay") {
                    $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                    $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                } else {
                }
            }
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
            $record7zBall->rawBet = $dataResults;
            if ($dataResults == null) var_dump($value->jsoninfo);
            array_push($xoso_record, $record7zBall);
        }
        $H_minigame_record = DB::table('history_minigame_bet')
            ->where('createdate', '>=', $stDate . ' 00:00:00')
            ->where('createdate', '<=', $endDate . ' 23:59:59')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
            ->whereIn('username', $arrUser[1])
            ->where('history_minigame_bet.paid','!=', 1)
            ->select('*', 'games.name as game')
            ->get();

        foreach ($H_minigame_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
            $recordMinigame = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $recordMinigame->rawBet = $dataResults;
            $recordMinigame->contentShow = MinigameHelpers::convertGametype($recordMinigame->rawBet->choice,$recordMinigame->game_id);
            array_push($xoso_record, $recordMinigame);
        }
        
        // Desc sort
        usort($xoso_record, function ($first, $second) {
            if (isset($second) && isset($first))
                return $first->created_at < $second->created_at;
            else return true;
        });
        return $xoso_record;
    }

    public static function getRecordKhachByDateHistorytv2Cancel($user)
    {
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            $arrUser = [[$user->id], [$user->name]];
        if (count($arrUser[0]) <= 0) return [];
        $stDate = date("Y-m-d");
        $endDate = date("Y-m-d");
        $xoso_record = [];
        
        $now = date("Y-m-d");

        $staticstartThisWeek = $now;
        $staticfinishThisWeek = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstartThisWeek = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstartThisWeek = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinishThisWeek = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinishThisWeek = date('Y-m-d');
        }

        $staticstartLastWeek = date('Y-m-d', strtotime('-7 day', strtotime($staticstartThisWeek)));
        $staticfinishLastWeek = date('Y-m-d', strtotime('-7 day', strtotime($staticfinishThisWeek)));

        $H_7zBall_record = DB::table('history')
            ->where('history.created_at', '>=', $staticstartLastWeek . ' 00:00:00')
            ->where('history.created_at', '<=', $staticfinishThisWeek . ' 23:59:59')
            ->join('users', 'users.id', '=', 'history.user_create')
            ->whereIn('users.id', $arrUser[0])
            // ->where('is_done','!=',0)
            ->whereIn('is_done',[-1])
            ->select('history.*', 'users.name as name')
            ->get();

        foreach ($H_7zBall_record as $value) {
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
            $string = preg_replace("/[\r\n]+/", "", $value->content);
            $content_Str = $string;
            $record7zBall = (json_decode('{' . '"id":"' . $value->id . '","game_id":' . 1 . ',"bonus":"' . $bonus . '","total_bet_money":' . (isset($value->money)?$value->money:0) . ',"com":' . 0 . ',"odds":1,"exchange_rates":1,"total_win_money":' . (isset($value->money)?$value->payoff:0) . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->created_at . '","updated_at":"' . $value->created_at . '","xien_id":0,"game":"' . 1 . '","name":"' . $value->name . '","content":"' . $content_Str . '","location":"Xổ số miền bắc","locationslug":"1", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $value;
            array_push($xoso_record, $record7zBall);
        }
        
        
        // Desc sort
        usort($xoso_record, function ($first, $second) {
            if (isset($second) && isset($first))
                return $first->created_at < $second->created_at;
            else return true;
        });
        return $xoso_record;
    }

    public static function getRecordKhachByDateHistorytv2($user, $stDate, $endDate, $type = "all",$paid=[0,1])
    {
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];
        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        $xoso_record = [];
        $bbin_record = [];
        // if (str_contains($type,"xoso") || $type == "all"){
        //     // $xoso_record = Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
        //         $xoso_record = DB::table('xoso_record')
        //         ->orderBy('id', 'desc')->where('isDelete',false)
        //         ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        //         ->join('location', 'games.location_id', '=', 'location.id')
        //         ->join('users', 'users.id', '=', 'xoso_record.user_id')
        //         // ->where('users.id',$user->id)
        //         ->whereIn('users.id', $arrUser[0])
        //         ->where('date','>=',$stDate)
        //         ->where('date','<=',$endDate)
        //         // ->where('total_win_money','<>',0)
        //         ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname','location.name as location','location.slug as locationslug')
        //         ->get();
        //         // $xoso_record=[];
        //     // });
        //     // Log::info(date("Y-m-d",strtotime('-1 day',strtotime($stDate))) .' 11:00:00');
        //     // Log::info(date("Y-m-d",strtotime($endDate)) .' 11:00:00');
        // }

        if (str_contains($type, "xoso") || $type == "all") {
            $H_7zBall_record = DB::table('history')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                // ->where('history.created_at', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                // ->where('history.created_at', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->where('history.created_at', '>=', $stDate . ' 00:00:00')
                ->where('history.created_at', '<=', $endDate . ' 23:59:59')
                ->join('users', 'users.id', '=', 'history.user_create')
                // ->join('games', 'history.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                // ->whereIn('history.paid', $paid)
                ->whereIn('users.id', $arrUser[0])
                ->select('history.*', 'users.name as name')
                ->get();

            foreach ($H_7zBall_record as $value) {
                // $dataResults = json_decode($value->jsoninfo);
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $string = preg_replace("/[\r\n]+/", "", $value->content);
                $content_Str = $string;
                $record7zBall = (json_decode('{' . '"id":"' . $value->id . '","game_id":' . 1 . ',"bonus":"' . $bonus . '","total_bet_money":' . (isset($value->money)?$value->money:0) . ',"com":' . 0 . ',"odds":1,"exchange_rates":1,"total_win_money":' . (isset($value->money)?$value->payoff:0) . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->created_at . '","updated_at":"' . $value->created_at . '","xien_id":0,"game":"' . 1 . '","name":"' . $value->name . '","content":"' . $content_Str . '","location":"Xổ số miền bắc","locationslug":"1", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
                // $record7zBall = (json_decode('{"game_id":' . 1 . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->money . ',"com":' . 0 . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->money . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->created_at . '","updated_at":"' . $value->created_at . '","xien_id":0,"game":"' . 1 . '","name":"' . $value->name . '","content":"' . $value->content . '","location":"Xổ số miền bắc","locationslug":"1", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
                $record7zBall->rawBet = $value;
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        // if (str_contains($type, "bbin") || $type == "all") {
        //     $bbin_record = DB::table('history_live_bet')
        //         // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
        //         ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
        //         ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
        //         ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //         // ->where('username',$user->name)
        //         ->whereIn('username', $arrUser[1])
        //         ->select('*', 'games.name as game')
        //         ->get();
        //     foreach ($bbin_record as $value) {
        //         array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . json_decode($value->jsoninfo)[0]->SerialID . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        //     }
        // }

        if (str_contains($type, "7zball") || $type == "all") {
            // echo $stDate . " " . $endDate;
            $H_7zBall_record = DB::table('history_7zball_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                // ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                // ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->where('createdate', '>=', $stDate . ' 00:00:00')
                ->where('createdate', '<=', $endDate . ' 23:59:59')
                ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('history_7zball_bet.paid', $paid)
                ->whereIn('username', $arrUser[1])
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_7zBall_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bet_time = null;
                if (isset($dataResults->bet_match_current)) {
                    if ($dataResults->bet_type != "parlay") {
                        $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                        $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                    } else {
                    }
                }
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
                $record7zBall->rawBet = $dataResults;
                if ($dataResults == null) var_dump($value->jsoninfo);
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        if (str_contains($type, "minigame") || $type == "all") {
            // echo $stDate . " " . $endDate;
            $H_minigame_record = DB::table('history_minigame_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                // ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                // ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->where('createdate', '>=', $stDate . ' 00:00:00')
                ->where('createdate', '<=', $endDate . ' 23:59:59')
                ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('username', $arrUser[1])
                ->whereIn('history_minigame_bet.paid', $paid)
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_minigame_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $recordMinigame = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
                $recordMinigame->rawBet = $dataResults;
                $recordMinigame->contentShow = MinigameHelpers::convertGametype($recordMinigame->rawBet->choice,$recordMinigame->game_id);
                // if ($dataResults == null) var_dump($value->jsoninfo);
                array_push($xoso_record, $recordMinigame);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        // if (str_contains($type,"saba")|| $type == "all"){
        //     $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($endDate))) .' 11:00:00')
        //         ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //         // ->where('username',$user->name)
        //         ->whereIn('username', $arrUser[1])
        //         ->select('*', 'games.name as game')
        //         ->get();

        //     foreach ($saba_record as $value){
        //         $dataResults = json_decode($value->jsoninfo);
        //         if ($value->gametype > 5000 && $value->gametype < 6000){
        //             $serialID = "";
        //             $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }else{
        //             if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //                 $serialID =  $dataResults->leagueName .': ' . $dataResults->homeName .' vs '. $dataResults->awayName . ' ('. $dataResults->betChoice. ')';
        //             else
        //                 $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') .': ' .'('. (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')'; 
        //             // $serialID =  $dataResults->leagueName .': ' .'('. $dataResults->betChoice. ')';
        //             // array_push($xoso_record, );
        //             // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. (json_decode($value->jsoninfo)->homeName) .' vs '. (json_decode($value->jsoninfo)->awayName) .'", "result": "'. $value->status .'"}')));

        //             $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }
        //     }
        // }
        // echo ' ' .count($xoso_record) .'-';

        // Desc sort
        usort($xoso_record, function ($first, $second) {
            if (isset($second) && isset($first))
                return $first->created_at < $second->created_at;
            else return true;
        });
        return $xoso_record;
    }

    public static function converBetOnParlay($bet, $bet_data)
    {
        if ($bet_data == null) return "";
        $bet_on = "";
        $bet_type = $bet->betting_type_id;
        // $bet_data = null;
        // if (!is_string($bet->betting_data))
        //     $bet_data = ($bet->betting_data);
        // else $bet_data = json_decode($bet->betting_data);
        if (str_contains($bet_type, "ou")) {
            switch ($bet->betting_k_id) {
                case "ov":
                    $bet_on = "Tài " . $bet_data->k;
                    break;

                case "ud":
                    $bet_on = "Xỉu " . $bet_data->k;
                    break;

                default:
                    $bet_on = $bet->bet_on . " " . $bet_data->k;
                    break;
            }
        }

        if (str_contains($bet_type, "ah")) {
            switch ($bet->betting_k_id) {
                case "h":
                    $bet_on = $bet->betting_homeName . " " . $bet_data->k;
                    break;

                case "a":
                    $bet_on = $bet->betting_awayName . " " . (str_contains($bet_data->k, '+') ? str_replace('+', '-', $bet_data->k) : str_replace('-', '+', $bet_data->k));
                    break;

                default:
                    $bet_on = $bet->bet_on . " " . $bet_data->k;
                    break;
            }
        }

        if (str_contains($bet_type, "1x2")) {
            switch ($bet->betting_k_id) {
                case "h":
                    $bet_on = $bet->betting_homeName . " ";
                    break;

                case "a":
                    $bet_on = $bet->betting_awayName . " ";
                    break;

                case "d":
                    $bet_on = "Hòa" . " ";
                    break;
                default:
                    $bet_on = $bet->bet_on . " ";
                    break;
            }
        }
        return $bet_on;
    }

    public static function converScoreMatch($bet_type, $detailMatch)
    {
        // var_dump($detailMatch);
        $detailMatch = json_decode($detailMatch);
        $strScore = "";
        // return;
        // if (str_contains($bet_type, "#cr"))
        //     if (str_contains($bet_type, "_1st")) {
        //         $propertyhtcr = "ht-cr";
        //         $strScore = "Phạt góc " . (isset($detailMatch) ? str_replace("-", " vs ", $detailMatch->$propertyhtcr) : "0 vs 0") . "Hiệp 1";
        //     } else {
        //         $strScore = "Phạt góc " . (isset($detailMatch) ? str_replace("-", " vs ", $detailMatch->cr) : "0 vs 0");
        //     }

        // else {
        //     if (str_contains($bet_type, "_1st")) {
        //         $propertyhtscore = "ht-score";
        //         $strScore = isset($detailMatch) ? str_replace("-", " vs ", $detailMatch->$propertyhtscore) : "0 vs 0" . "Hiệp 1";
        //     } else {
        //         $strScore = isset($detailMatch) ? str_replace("-", " vs ", $detailMatch->score) : "0 vs 0";
        //     }
        // }

        if (isset($detailMatch) && is_object($detailMatch)) {
            if (str_contains($bet_type, "#cr")) {
                $propertyhtcr = "ht-cr";
                $strScore = isset($detailMatch) ? str_replace("-", " vs ", $detailMatch->cr) : "0 vs 0";
            }

            if (str_contains($bet_type, "#redCard")) {
                $propertyRedCard = "red-card";
                $propertyYellowCard = "yellow-card";
                $redCard = isset($detailMatch->$propertyRedCard) ? str_replace("-", " vs ", $detailMatch->$propertyRedCard) : "0 vs 0";
                $yellowCard = isset($detailMatch->$propertyYellowCard) ? str_replace("-", " vs ", $detailMatch->$propertyYellowCard) : "0 vs 0";
                $strScore = "Thẻ đỏ: " . $redCard . ", Thẻ vàng: " . $yellowCard;
            }

            if (str_contains($bet_type, "pk")) {
                $strScore = isset($detailMatch) && $detailMatch->pk != "" ? str_replace("-", " PK ", $detailMatch->pk) : "0 PK 0";
            }

            if (str_contains($bet_type, "ot")) {
                $strScore = isset($detailMatch) && $detailMatch->ot != "" ? str_replace("-", " vs ", $detailMatch->ot) : "0 vs 0";
            }

            if (!str_contains($bet_type, "#cr") && !str_contains($bet_type, "#redCard") && !str_contains($bet_type, "pk") && !str_contains($bet_type, "ot")) {
                $strScore = isset($detailMatch) ? str_replace("-", " vs ", $detailMatch->score) : "0 vs 0";
            }
        }

        return $strScore;
    }


    public static function converTimeMatch($detailMatch)
    {
        if ($detailMatch == null) return "";
        $time_match = "";
        switch ($detailMatch->period) {
            case "2h":
                $time_match = "Hiệp 2 ";
                break;
            case "1h":
                $time_match = "Hiệp 1 ";
                break;
            case "ht":
                $time_match = "Giữa hiệp ";
                break;
            default:
                $time_match = "";
                break;
        }
        if (isset($detailMatch->time))
            $time_match .= ($detailMatch->period != "ht" ? (substr($detailMatch->time, 0, 2) . "’") : "");
        return $time_match;
    }

    public static function getRecordSuperv2($user, $game_code)
    {
        // echo 'getRecordSuperv2' . $user->name .' ';
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];
        $stDate = date("Y-m-d");

        // $xoso_record = [];

        // $xoso_record = Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
        // echo "query db";
        $xoso_record = DB::table('xoso_record')
            ->where('isDelete', false)
            ->whereIn('xoso_record.user_id', $arrUser[0])
            ->where('date', $stDate)
            ->where('game_id', $game_code)
            // ->where('xoso_record.id','>', 637424)
            ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
            ->groupBy('bet_number')
            ->get();

        return $xoso_record;
    }

    public static function getRecordSuperLatestv2($user, $game_code, $latestID = 0, $latestIDTemp = 0)
    {
        // echo 'getRecordSuperv2' . $user->name .' ';
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];
        $stDate = date("Y-m-d");

        // $xoso_record = [];

        // $xoso_record = Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
        // echo "query db";
        if ($game_code == 9 || $game_code == 10 || $game_code == 11 || $game_code == 29) {
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->whereIn('xoso_record.user_id', $arrUser[0])
                ->where('date', $stDate)
                ->where('game_id', $game_code)
                ->where('xoso_record.id', '>', $latestID)
                ->where('xoso_record.id', '<', $latestIDTemp + 1)
                ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                ->groupBy('bet_number')
                ->get();

            $xoso_record_temp = [];
            foreach ($xoso_record as $record) {
                $numbers = explode(',', $record->bet_number);
                $sum = $record->sumbet;
                foreach ($numbers as $number) {
                    $sumI = 0;
                    if (!isset($xoso_record_temp[$number]->sumbet))
                        $sumI = 0;
                    else
                        $sumI = $xoso_record_temp[$number]->sumbet;
                    $xoso_record_temp[$number] = (object)['bet_number' => $number, 'sumbet' => $sumI + $sum];
                }
            }
            $xoso_record = (object)$xoso_record_temp;
        } else if ($game_code == 24) {
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->whereIn('xoso_record.user_id', $arrUser[0])
                ->where('date', $stDate)
                ->where('game_id', '>=', 31)
                ->where('game_id', '<=', 55)
                ->where('xoso_record.id', '>', $latestID)
                ->where('xoso_record.id', '<', $latestIDTemp + 1)
                ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                ->groupBy('bet_number')
                ->get();
            // print_r($xoso_record);
        } else if ($game_code == 7) {
            $hsLLive = 1;
            if (date('i') >= 15 && date('H') == 18) {
                $now = date('Y-m-d');
                $kqxs = XoSoResult::where('location_id', 1)
                    ->where('date', $now)->first();
                $hsLLive = isset($kqxs->Giai_8) && is_numeric($kqxs->Giai_8) ? $kqxs->Giai_8 : 1;
                $hsLLive = round(54 / (27 - $hsLLive), 5);
            }
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->whereIn('xoso_record.user_id', $arrUser[0])
                ->where('date', $stDate)
                ->whereIn('game_id', [7, 18])
                // ->where('game_id',18)
                ->where('xoso_record.id', '>', $latestID)
                ->where('xoso_record.id', '<', $latestIDTemp + 1)
                ->select('bet_number', DB::raw('SUM(IF(game_id = 18, total_bet_money *' . $hsLLive . ',total_bet_money)) AS sumbet'))
                ->groupBy('bet_number')
                ->get();
            // print_r($xoso_record);
        } else
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->whereIn('xoso_record.user_id', $arrUser[0])
                ->where('date', $stDate)
                ->where('game_id', $game_code)
                ->where('xoso_record.id', '>', $latestID)
                ->where('xoso_record.id', '<', $latestIDTemp + 1)
                ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                ->groupBy('bet_number')
                ->get();

        return $xoso_record;
    }

    public static function getRecordSuperByNumberv2($user, $game_code, $game_number)
    {
        // echo 'getRecordSuperv2' . $user->name .' ';
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];
        $stDate = date("Y-m-d");

        $xoso_record = [];

        // $xoso_record = Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
        // echo "query db";
        if ($game_code == 9 || $game_code == 10 || $game_code == 11)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                ->whereIn('users.id', $arrUser[0])
                ->where('date', $stDate)
                ->where('xoso_record.bet_number', 'like', '%' . $game_number . '%')
                ->where('game_id', $game_code)
                ->select(DB::raw('SUM(total_bet_money) AS sumbet'))
                ->groupBy('game_id')
                ->get();
        else
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                ->whereIn('users.id', $arrUser[0])
                ->where('date', $stDate)
                ->where('xoso_record.bet_number', 'like', '%' . $game_number . '%')
                ->where('game_id', $game_code)
                ->select(DB::raw('SUM(total_bet_money) AS sumbet'))
                ->groupBy('game_id')
                ->get();

        return $xoso_record;
    }

    public static function getRecordById($id)
    {
        return
            XoSoRecord
            // ->orderBy('id', 'desc')->where('isDelete',false)
            ::where('xoso_record.id', '=', $id)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->join('users', 'users.id', '=', 'xoso_record.user_id')
            // ->where('total_win_money','<>',0)
            ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
            ->first();
        // });

        // return $xoso_record;
    }

    public static function getRecordKhachChuaXulyByDate($user, $stDate, $endDate)
    {
        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        $xoso_record =
            // Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachChuaXulyByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
            // return 
            DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->join('users', 'users.id', '=', 'xoso_record.user_id')
            ->where('users.id', $user->id)
            ->where('date', '>=', $stDate)
            ->where('date', '<=', $endDate)
            ->where('total_win_money', '=', 0)
            ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
            ->get();
        // });

        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value) {
        //     $dataResults = json_decode($value->jsoninfo);
        //     if ($value->status != null || $value->payoff != 0) continue;
        //     if ($value->gametype > 5000 && $value->gametype < 6000) {
        //         $serialID = "";
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     } else {
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //         // $serialID =  $dataResults->leagueName .': ' .'('. $dataResults->betChoice. ')';
        //         // array_push($xoso_record, );
        //         // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. (json_decode($value->jsoninfo)->homeName) .' vs '. (json_decode($value->jsoninfo)->awayName) .'", "result": "'. $value->status .'"}')));

        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"payoff":' . $value->payoff . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $user->name . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     }
        // }
        return $xoso_record;
    }

    public static function getRecordKhachChuaXulyByDatev2($user, $stDate, $endDate, $type = "all")
    {

        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];

        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        $xoso_record = [];
        if (str_contains($type, "xoso") || $type == "all") {
            $xoso_record =
                // Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachChuaXulyByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
                // return 
                DB::table('xoso_record')
                ->orderBy('id', 'desc')->where('isDelete', false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                // ->where('users.id',$user->id)
                ->whereIn('users.id', $arrUser[0])
                // ->where('date', '>=', $stDate)
                // ->where('date', '<=', $endDate)
                ->where('total_win_money', '=', 0)
                ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                ->get();
            // echo "getRecordKhachChuaXulyByDatev2" . " " . $type;
            // });
        }
        if (str_contains($type, "7zball") || $type == "all") {
            $H_7zBall_record = DB::table('history_7zball_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                // ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                // ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                // ->where( function ($query) use($stDate,$endDate) {
                //     $query->whereBetween('createdate',[date("Y-m-d", strtotime($stDate)) . ' 11:00:00', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00'])
                //     ->whereNull('paid');
                // })
                ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('username', $arrUser[1])
                ->where('paid',0)
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_7zBall_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bet_time = null;
                if (isset($dataResults->bet_match_current)) {
                    if ($dataResults->bet_type != "parlay") {
                        $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                        $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                    } else {
                    }
                }
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
                $record7zBall->rawBet = $dataResults;
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        if (str_contains($type, "minigame") || $type == "all") {
            $H_minigame_record = DB::table('history_minigame_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('username', $arrUser[1])
                ->whereNull('paid')
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_minigame_record as $value) {
                $dataResults = json_decode($value->jsoninfo);

                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $recordMinigame = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": ""}'));
                $recordMinigame->rawBet = $dataResults;
                array_push($xoso_record, $recordMinigame);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }

        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
        //         ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($endDate))) .' 11:00:00')
        //         ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //         ->where('username',$user->name)
        //         ->select('*', 'games.name as game')
        //         ->get();

        //     foreach ($saba_record as $value){
        //         $dataResults = json_decode($value->jsoninfo);
        //         if ($value->status != null || $value->payoff != 0) continue;
        //         if ($value->gametype > 5000 && $value->gametype < 6000){
        //             $serialID = "";
        //             $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }else{
        //             if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //                 $serialID =  $dataResults->leagueName .': ' . $dataResults->homeName .' vs '. $dataResults->awayName . ' ('. $dataResults->betChoice. ')';
        //             else
        //                 $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') .': ' .'('. (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')'; 
        //             // $serialID =  $dataResults->leagueName .': ' .'('. $dataResults->betChoice. ')';
        //             // array_push($xoso_record, );
        //             // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. (json_decode($value->jsoninfo)->homeName) .' vs '. (json_decode($value->jsoninfo)->awayName) .'", "result": "'. $value->status .'"}')));

        //             $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $user->name .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //             $recordSaba->rawBet = $dataResults;
        //             array_push($xoso_record, $recordSaba);
        //         }
        //     }
        return $xoso_record;
    }

    public static function getRecordKhachCancelByDate($user, $stDate, $endDate)
    {
        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        $xoso_record =
            // Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachCancelByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
            // return 
            DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', true)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->join('users', 'users.id', '=', 'xoso_record.user_id')
            ->where('users.id', $user->id)
            ->where('date', '>=', $stDate)
            ->where('date', '<=', $endDate)
            ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
            ->get();
        // });
        return $xoso_record;
    }

    public static function getRecordKhachCancelByDatev2($user, $stDate, $endDate, $type)
    {
        $arrUser = UserHelpers::GetAllUserV3($user);
        if ($user->roleid == 6)
            // array_push($arrUser,$user->id);
            $arrUser = [[$user->id], [$user->name]];
        // echo ' ' .$user->id .'-'.count($arrUser[0]).'-';
        if (count($arrUser[0]) <= 0) return [];

        if ($stDate == null) $stDate = date("Y-m-d");
        else {
            $time = strtotime($stDate);
            $stDate = date('Y-m-d', $time);
        }
        if ($endDate == null) $endDate = date("Y-m-d");
        else {
            $time = strtotime($endDate);
            $endDate = date('Y-m-d', $time);
        }
        if (str_contains($type, "xoso") || $type == "all") {
            $xoso_record =
                // Cache::tags('XoSoRecord'.$user->id)->remember('getRecordKhachCancelByDate-'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME', 0), function () use ($user,$stDate,$endDate) {
                // return 
                DB::table('xoso_record')
                ->orderBy('id', 'desc')->where('isDelete', true)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->join('users', 'users.id', '=', 'xoso_record.user_id')
                // ->where('users.id',$user->id)
                ->whereIn('users.id', $arrUser[0])
                ->where('date', '>=', $stDate)
                ->where('date', '<=', $endDate)
                ->select('xoso_record.*', 'games.name as game', 'users.name as name', 'users.fullname as fullname', 'location.name as location', 'location.slug as locationslug')
                ->get();
            // });
        }

        if (str_contains($type, "7zball") || $type == "all") {
            $H_7zBall_record = DB::table('history_7zball_bet')
                // ->where('createdate','>=',date("Y-m-d",strtotime($stDate)) .' 11:00:00')
                // ->where('createdate','<=',date("Y-m-d",strtotime($endDate)) .' 11:00:00')
                ->where('createdate', '>=', date("Y-m-d", strtotime($stDate)) . ' 11:00:00')
                ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($endDate))) . ' 11:00:00')
                ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
                // ->where('username',$user->name)
                ->whereIn('username', $arrUser[1])
                ->where("jsoninfo", "like", '%"bet_status":2%')
                ->where('paid', 1)
                ->select('*', 'games.name as game')
                ->get();

            foreach ($H_7zBall_record as $value) {
                $dataResults = json_decode($value->jsoninfo);
                $bet_time = null;
                if (isset($dataResults->bet_match_current)) {
                    if ($dataResults->bet_type != "parlay") {
                        $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                        $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                    } else {
                    }
                }
                $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0";
                $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":1,"exchange_rates":1,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","name":"' . $value->username . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
                $record7zBall->rawBet = $dataResults;
                array_push($xoso_record, $record7zBall);
            }
            // foreach ($bbin_record as $value){
            //     array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"bonus":"0,0,0,0,0,0,0,0","total_bet_money":'. $value->betamount .',"com":'. $value->com .',"payoff":'. $value->payoff .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","name":"'. $value->username .'","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "'. json_decode($value->jsoninfo)[0]->SerialID .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
            // }
        }
        return $xoso_record;
    }

    public static function GetByDate($date, $mien = 1)
    {
        $xoso_record = null;
        switch ($mien) {
            case 1:
                $xoso_record = XoSoRecord::where('date', $date)
                    ->where('total_win_money', '=', 0)
                    ->where('game_id', '<', 100)
                    // ->where('game_id', '!=', 7)
                    ->where('isDelete', false)->get();
                break;
            case 5:
                $xoso_record = XoSoRecord::where('date', $date)
                    ->where('total_win_money', '=', 0)
                    ->where('game_id', '>=', 700)
                    ->where('game_id', '<=', 739)
                    ->where('isDelete', false)->get();
                break;
            case 21:
                $xoso_record = XoSoRecord::where('date', $date)
                    ->where('total_win_money', '=', 0)
                    ->where('game_id', '<', 400)->where('game_id', '>', 300)
                    ->where('isDelete', false)->get();
                break;
            case 22:
                $xoso_record = XoSoRecord::where('date', $date)
                    ->where('total_win_money', '=', 0)
                    ->where('game_id', '<', 500)->where('game_id', '>', 400)
                    ->where('isDelete', false)->get();
                break;
            case 31:
                $xoso_record = XoSoRecord::where('date', $date)
                    ->where('total_win_money', '=', 0)
                    ->where('game_id', '<', 600)->where('game_id', '>', 500)
                    ->where('isDelete', false)->get();
                break;
            case 32:
                $xoso_record = XoSoRecord::where('date', $date)
                    ->where('total_win_money', '=', 0)
                    ->where('game_id', '<', 700)->where('game_id', '>', 600)
                    ->where('isDelete', false)->get();
                break;
            default:
                $xoso_record = null;
        }
        return $xoso_record;
    }

    public static function GetAllByDate($date, $mien = 1)
    {
        $xoso_record = null;
        switch ($mien) {
            case 1:
                $xoso_record = XoSoRecord::where('date', $date)
                    // ->where('total_win_money','=',0)
                    ->orderBy('id', 'desc')->where('isDelete', false)
                    ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                    ->join('location', 'games.location_id', '=', 'location.id')
                    ->where('location.slug', '=', $mien)
                    ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
                    ->where('xoso_record.user_id', '=', 100)->where('isDelete', false)
                    ->where('xoso_record.game_id', '<', 100)->where('isDelete', false)->get();
                break;
            case 5:
                $xoso_record = XoSoRecord::where('date', $date)
                    // ->where('total_win_money','=',0)
                    ->where('game_id', '>=', 700)
                    ->where('game_id', '<=', 739)
                    ->where('isDelete', false)->get();
                break;
            case 21:
                $xoso_record = XoSoRecord::where('date', $date)
                    // ->where('total_win_money','=',0)
                    ->where('game_id', '<', 400)->where('game_id', '>', 300)
                    ->where('isDelete', false)->get();
                break;
            case 22:
                $xoso_record = XoSoRecord::where('date', $date)
                    // ->where('total_win_money','=',0)
                    ->where('game_id', '<', 500)->where('game_id', '>', 400)
                    ->where('isDelete', false)->get();
                break;
            case 31:
                $xoso_record = XoSoRecord::where('date', $date)
                    // ->where('total_win_money','=',0)
                    ->where('game_id', '<', 600)->where('game_id', '>', 500)
                    ->where('isDelete', false)->get();
                break;
            case 32:
                $xoso_record = XoSoRecord::where('date', $date)
                    // ->where('total_win_money','=',0)
                    ->where('game_id', '<', 700)->where('game_id', '>', 600)
                    ->where('isDelete', false)->get();
                break;
            default:
                $xoso_record = null;
        }
        return $xoso_record;
    }

    public static function GetMienNamByDate($date)
    {
        $xoso_record = null;
        $xoso_record = XoSoRecord::where('date', $date)
            ->where('total_win_money', '=', 0)
            ->where('game_id', '<', 400)->where('game_id', '>', 299)->where('isDelete', false)->get();
        return $xoso_record;
    }

    public static function GetMienTrungByDate($date)
    {
        $xoso_record = null;
        $xoso_record = XoSoRecord::where('date', $date)
            ->where('total_win_money', '=', 0)
            ->where('game_id', '<', 500)->where('game_id', '>', 399)->where('isDelete', false)->get();
        return $xoso_record;
    }

    public static function GetByDateHH($date, $userid)
    {
        $xoso_record = null;
        $xoso_record = XoSoRecord::where('date', '=', $date)
            // ->where('total_win_money','=',0)
            ->where('user_id', $userid)->where('isDelete', false)->get();
        return $xoso_record;
    }

    public static function GetXSAByDate($date, $session)
    {
        if ($session == 24) {
            $now = date('Y-m-d');
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');

            $xoso_record = null;
            $xoso_record = XoSoRecord::whereIn('date', [$now, $yesterday])->where('game_id', '>=', 100)->where('total_win_money', '=', 0)->where('isDelete', false)->get();
            return $xoso_record;
        } else {
            $xoso_record = null;
            $xoso_record = XoSoRecord::where('date', $date)
                ->where('game_id', '>=', 100)
                ->where('total_win_money', '=', 0)
                ->where('isDelete', false)
                ->where('xien_id', $session)
                ->get();
            return $xoso_record;
        }
    }

    public static function GetByID($id)
    {
        $xoso_record = null;
        $xoso_record = XoSoRecord::where('id', $id)->first();
        return $xoso_record;
    }
    public static function GetByUser($user, $location_id = 1)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('location.slug', '=', $location_id)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();
        return $xoso_record;
    }

    public static function GetCXLByUserByDate($user, $date)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('total_win_money', 0)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();

        // $bbin_record = DB::table('history_live_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value) {
        //     array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . (json_decode($value->jsoninfo)[0]->SerialID) . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        // }


        $H_7zBall_record = DB::table('history_7zball_bet')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->whereNull('paid')
            ->select('*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($xoso_record, $record7zBall);
        }
        // echo $date;
        // var_dump($H_7zBall_record);
        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value) {
        //     $dataResults = json_decode($value->jsoninfo);

        //     if ($value->gametype > 5000 && $value->gametype < 6000) {
        //         $serialID = "";
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount * 1000 . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout * 1000 . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     } else {
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount * 1000 . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout * 1000 . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     }
        // }
        $H_minigame_record = DB::table('history_minigame_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->whereNull('paid')
            ->select('*', 'games.name as game')
            ->orderBy("history_minigame_bet.id", "desc")
            ->get();
        foreach ($H_minigame_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $recordminigame = json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}');
            $recordminigame->rawBet = $dataResults;
            array_push($xoso_record, $recordminigame);
        }
        return $xoso_record;
    }

    public static function GetByUserByDate($user, $date)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('xoso_record.date', '>=', date("Y-m-d", strtotime($date)) . ' 00:00:00')
            ->where('xoso_record.date', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();

        // $bbin_record = DB::table('history_live_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value) {
        //     array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . (json_decode($value->jsoninfo)[0]->SerialID) . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        // }


        $H_7zBall_record = DB::table('history_7zball_bet')
            ->where(function ($query) use ($date) {
                $query->whereBetween('createdate', [date("Y-m-d", strtotime($date)) . ' 11:00:00', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00'])
                    ->orWhere('paid',0);
            })
            // ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
            // ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($xoso_record, $record7zBall);
        }
        // echo $date;
        // var_dump($H_7zBall_record);
        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value) {
        //     $dataResults = json_decode($value->jsoninfo);

        //     if ($value->gametype > 5000 && $value->gametype < 6000) {
        //         $serialID = "";
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount * 1000 . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout * 1000 . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     } else {
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount * 1000 . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout * 1000 . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     }
        // }
        $H_minigame_record = DB::table('history_minigame_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->orderBy("history_minigame_bet.id", "desc")
            ->get();
        foreach ($H_minigame_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $recordminigame = json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}');
            $recordminigame->rawBet = $dataResults;
            array_push($xoso_record, $recordminigame);
        }
        return $xoso_record;
    }

    public static function GetByUserByDatev2($user, $date)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('xoso_record.date', '>=', date("Y-m-d", strtotime($date)) . ' 00:00:00')
            ->where('xoso_record.date', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();

        // $bbin_record = DB::table('history_live_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value) {
        //     array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . (json_decode($value->jsoninfo)[0]->SerialID) . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        // }


        $H_7zBall_record = DB::table('history_7zball_bet')
            ->where(function ($query) use ($date) {
                $query->whereBetween('createdate', [date("Y-m-d", strtotime($date)) . ' 11:00:00', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00'])
                    ->orWhereNull('paid');
            })
            // ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
            // ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($xoso_record, $record7zBall);
        }
        // echo $date;
        // var_dump($H_7zBall_record);
        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value) {
        //     $dataResults = json_decode($value->jsoninfo);

        //     if ($value->gametype > 5000 && $value->gametype < 6000) {
        //         $serialID = "";
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount * 1000 . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout * 1000 . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     } else {
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount * 1000 . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout * 1000 . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     }
        // }
        $H_minigame_record = DB::table('history_minigame_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->orderBy("history_minigame_bet.id", "desc")
            ->get();
        foreach ($H_minigame_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $recordminigame = json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}');
            $recordminigame->rawBet = $dataResults;
            array_push($xoso_record, $recordminigame);
        }
        return $xoso_record;
    }

    public static function GetByUserByDateLimit5($user, $date)
    {
        $xoso_record = null;

        // $xoso_record = XoSoRecord::where('date',$date)
        //         // ->where('total_win_money','=',0)
        //     ->orderBy('id', 'desc')->where('isDelete',false)
        //     ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        //     ->join('location', 'games.location_id', '=', 'location.id')
        //     // ->where('location.slug','=',1)
        //     ->select('xoso_record.*', 'games.name as game','location.name as location','location.slug as locationslug')
        //     ->where('xoso_record.user_id','=',100)->where('isDelete',false)
        //         ->where('xoso_record.game_id','<',100)->where('isDelete',false)->get();

        $xoso_record = XoSoRecord::orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->where('xoso_record.date', $date)
            ->where('xoso_record.user_id', $user->id)
            // ->take(5)
            ->get();

        $merge_record = array();
        foreach ($xoso_record as $record)
        // if ($record->game_id == 7 || $record->game_id == 14 || $record->game_id == 12)
        {
            $have_merger = false;
            $data_merger = null;
            foreach ($merge_record as $record_save) {
                if (
                    $record_save->game_id == $record->game_id
                    && $record_save->exchange_rates == $record->exchange_rates
                    && $record_save->total_bet_money == $record->total_bet_money
                    && count(explode(",", $record->bet_number)) < 2
                ) {
                    // $record_save->bet_number.= (','.$record->bet_number);
                    // $data_merger = 
                    $have_merger = true;
                    $record_save->bet_number .= (',' . $record->bet_number);
                    $record_save->total_bet_money_real += $record->total_bet_money;
                    $data_merger = $record_save;
                }
            }
            if (!$have_merger) {
                array_push($merge_record, $record);
            }
        }
        $merge_record = array_slice($merge_record, 0, 5);
        foreach ($merge_record as $record_save) {
            // if ($record_save->game_id == 7 || $record_save->game_id == 14 || $record_save->game_id == 12)
            {
                $split_number = count(explode(",", $record_save->bet_number));
                $record_save->total_bet_money = $record_save->total_bet_money * $split_number;
            }
        }
        return $merge_record;
    }

    public static function GetByUserByDateLocation($user, $date, $slug)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            // ->where('games.location_id',$slug)
            ->where('xoso_record.date', $date)
            ->where('xoso_record.total_win_money', '=', 0)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();

        //     $H_7zBall_record = DB::table('history_7zball_bet')
        // // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
        // ->where('createdate','>=',date("Y-m-d",strtotime($date)) .' 11:00:00')
        // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
        //     ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
        //     ->where('username',$user->name)
        //     ->where('payoff',0)
        //     ->select('*', 'games.name as game')
        //     ->orderBy("history_7zball_bet.id","desc")
        //     ->get();
        // foreach ($H_7zBall_record as $value){
        //     $dataResults = json_decode($value->jsoninfo);
        //     $record7zBall = (json_decode('{"id":'.$value->id. ',"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"7zBall","locationslug":"70", "SerialID": "'. "" .'", "result": "'. "" .'"}'));
        //     $record7zBall->rawBet = $dataResults;
        //     array_push($xoso_record, $record7zBall);
        // }

        $saba_record = DB::table('history_saba_bet')
            // ->where('createdate',$date)
            ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 00:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 00:00:00')
            ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            // ->whereNull('status')
            ->select('*', 'games.name as game')
            ->get();

        foreach ($saba_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            if ($value->payout != 0 || $value->status != null) continue;

            if ($value->gametype > 5000 && $value->gametype < 6000) {
                $serialID = "";
                $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
                $recordSaba->rawBet = $dataResults;
                array_push($xoso_record, $recordSaba);
            } else {
                if (isset($dataResults->homeName) && $dataResults->homeName != '')
                    $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
                else
                    $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
                $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
                $recordSaba->rawBet = $dataResults;
                array_push($xoso_record, $recordSaba);
            }

            // if ($dataResults->homeName != '')
            //     $serialID =  $dataResults->leagueName .': ' . $dataResults->homeName .' vs '. $dataResults->awayName . ' ('. $dataResults->betChoice. ')';
            // else
            //     $serialID =  $dataResults->leagueName .': ' .'('. $dataResults->betChoice. ')';
            // $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
            // $recordSaba->rawBet = $dataResults;
            // array_push($xoso_record, $recordSaba);
        }
        // print_r((json_decode('{"game_id":'. $value->gametype .',"id":'. $value->refId .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}')));
        return $xoso_record;
    }

    public static function GetByUserSkByDate($user, $date)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('xoso_record.date', $date)
            // ->where('xoso_record.total_win_money','<>',0)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();
        return $xoso_record;
    }

    public static function GetByUserSkByDateLocation($user, $date, $slug)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('xoso_record.date', $date)
            // ->where('games.location_id',$slug)
            // ->where('xoso_record.total_win_money','<>',0)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();


        // $bbin_record = DB::table('history_live_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value) {
        //     array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . (json_decode($value->jsoninfo)[0]->SerialID) . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        // }

        $H_7zBall_record = DB::table('history_7zball_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')

            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->where(function ($query) use ($date) {
                $query->whereBetween('createdate', [date("Y-m-d", strtotime($date)) . ' 11:00:00', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00'])
                    ->orWhereNull('paid');
            })
            ->select('*', 'games.name as game')
            ->orderBy("history_7zball_bet.id", "desc")
            ->get();
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bet_time = null;
            if (isset($dataResults->bet_match_current)) {
                if ($dataResults->bet_type != "parlay") {
                    $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                    $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                } else {
                }
            }
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($xoso_record, $record7zBall);
        }

        $H_minigame_record = DB::table('history_minigame_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->orderBy("history_minigame_bet.id", "desc")
            ->get();
        foreach ($H_minigame_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $recordminigame = json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}');
            $recordminigame->rawBet = $dataResults;
            array_push($xoso_record, $recordminigame);
        }
        // var_dump($xoso_record);

        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date))) . ' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value) {
        //     $dataResults = json_decode($value->jsoninfo);
        //     if ($value->payout == 0 || $value->status == null) continue;
        //     if ($value->gametype > 5000 && $value->gametype < 6000) {
        //         $serialID = "";
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     } else {
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //         else
        //                 if (isset($dataResults->leagueName))
        //             $serialID =  $dataResults->leagueName . ': ' . '(' . $dataResults->betChoice . ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //         $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //         $recordSaba->rawBet = $dataResults;
        //         array_push($xoso_record, $recordSaba);
        //     }
        // }

        return $xoso_record;
    }

    public static function GetByUserByDateRange($user, $date1, $date2)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->where('xoso_record.total_win_money', '=', 0)
            ->where('xoso_record.date', '>=', $date1)
            ->where('xoso_record.date', '<=', $date2)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();

        $bbin_record = DB::table('history_live_bet')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
            ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->get();
        foreach ($bbin_record as $value) {
            array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . (json_decode($value->jsoninfo)[0]->SerialID) . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        }

        $saba_record = DB::table('history_saba_bet')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
            ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->get();

        foreach ($saba_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            if ($dataResults->homeName != '')
                $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
            else
                $serialID =  $dataResults->leagueName . ': ' . '(' . $dataResults->betChoice . ')';
            array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}')));
        }

        $H_7zBall_record = DB::table('history_7zball_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->orderBy("history_7zball_bet.id", "desc")
            ->get();
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($xoso_record, $record7zBall);
        }

        return $xoso_record;
    }

    public static function GetByUserSkByDateOneDay($user, $date1, $date2)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            // ->where('xoso_record.total_win_money','<>',0)
            ->where('xoso_record.date', '>=', $date1)
            ->where('xoso_record.date', '<=', $date2)
            ->select('xoso_record.*', 'games.name as game', 'location.name as location', 'location.slug as locationslug')
            ->get();
        // $bbin_record = DB::table('history_live_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value) {
        //     array_push($xoso_record, (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"Live Casino (BBIN)","locationslug":"50", "SerialID": "' . (json_decode($value->jsoninfo)[0]->SerialID) . '", "result": "' . json_decode($value->jsoninfo)[0]->ResultType . '"}')));
        // }

        // $saba_record = DB::table('history_saba_bet')
        //     ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
        //     ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username', $user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value) {
        //     $dataResults = json_decode($value->jsoninfo);
        //     if ($value->payout == 0 || $value->status == null) continue;
        //     if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //         $serialID =  $dataResults->leagueName . ': ' . $dataResults->homeName . ' vs ' . $dataResults->awayName . ' (' . $dataResults->betChoice . ')';
        //     else
        //         $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') . ': ' . '(' . (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //     $recordSaba = (json_decode('{"game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":1,"total_win_money":' . $value->payout . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"SABA","locationslug":"60", "SerialID": "' . $serialID . '", "result": "' . $value->status . '"}'));
        //     $recordSaba->rawBet = $dataResults;
        //     array_push($xoso_record, $recordSaba);
        //     // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}')));
        // }

        $H_7zBall_record = DB::table('history_7zball_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->orderBy("history_7zball_bet.id", "desc")
            ->get();
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bet_time = null;
            if (isset($dataResults->bet_match_current)) {
                if ($dataResults->bet_type != "parlay") {
                    $detailMatchOnBet = json_decode($dataResults->bet_match_current);
                    $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? static::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                } else {
                }
            }
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '", "betTime": "' . $bet_time . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($xoso_record, $record7zBall);
        }

        $H_minigame_record = DB::table('history_minigame_bet')
            // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00')
            ->where('createdate', '>=', date("Y-m-d", strtotime($date1)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($date2))) . ' 11:00:00')
            ->join('games', 'history_minigame_bet.gametype', '=', 'games.game_code')
            ->where('username', $user->name)
            ->select('*', 'games.name as game')
            ->orderBy("history_minigame_bet.id", "desc")
            ->get();
        foreach ($H_minigame_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $recordminigame = json_decode('{"bonus":"' . $bonus . '","game_id":' . $value->gametype . ',"total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":' . (isset($dataResults->odd) ? $dataResults->odd : 0) . ',"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"minigame","locationslug":"80", "SerialID": "' . "" . '", "result": "' . "" . '"}');
            $recordminigame->rawBet = $dataResults;
            array_push($xoso_record, $recordminigame);
        }

        return $xoso_record;
    }

    public static function GetByUserSkByDateRange($user, $date1, $date2)
    {
        ini_set('memory_limit', '-1');
        $begin = new DateTime($date1);
        $end = new DateTime($date2);
        if ($end > (new DateTime()))
            $end = new DateTime();
        $end->modify('+1 day');
        // var_dump($date1);
        // var_dump($end);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $xoso_record = [];
        foreach ($period as $dt) {
            $stDateTemp = $dt->format("Y-m-d");
            $endDateTemp = $dt->format("Y-m-d");
            if ($dt->format("Y-m-d") > date('Y-m-d')) {
                // echo 'continue';
                break;
            }
            $cacheTime = env('CACHE_TIME_SHORT', 0);
            $endTimeStamp = strtotime($endDateTemp);
            $endDateNewformat = date('Y-m-d', $endTimeStamp);
            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                $cacheTime = 1440 * 30;

            $userOneDay =
                //XoSoRecordHelpers::GetByUserSkByDateOneDay($user, $stDateTemp, $endDateTemp);
                // XoSoRecordHelpers::ReportKhachv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                Cache::remember('XoSoRecordHelpers-GetByUserSkByDateOneDay' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp) {
                    return  XoSoRecordHelpers::GetByUserSkByDateOneDay($user, $stDateTemp, $endDateTemp);
                });
            Log::info(count($userOneDay));
            $xoso_record = array_merge($xoso_record, $userOneDay);
        }

        return $xoso_record;
    }

    public static function GetByUserSkByDateRangeAPI($user, $date1, $date2)
    {
        // $xoso_record = null;
        // $xoso_record = DB::table('xoso_record')
        //     ->orderBy('id', 'desc')->where('isDelete',false)
        //     ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
        //     ->join('location', 'games.location_id', '=', 'location.id')
        //     ->where('xoso_record.user_id',$user->id)
        //     // ->where('xoso_record.total_win_money','<>',0)
        //     ->where('xoso_record.date','>=',$date1)
        //     ->where('xoso_record.date','<=',$date2)
        //     ->select('xoso_record.id','xoso_record.id','xoso_record.date','xoso_record.game_id as game_code','xoso_record.isDelete','xoso_record.total_bet_money','xoso_record.odds','xoso_record.exchange_rates','xoso_record.bet_number','xoso_record.win_number','xoso_record.created_at', 'games.name as game_name','location.name as location_name')
        //     ->get();

        // $bbin_record = DB::table('history_live_bet')
        // ->where('createdate','>=',date("Y-m-d",strtotime($date1)) .' 11:00:00')
        // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date2))) .' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username',$user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value){
        //      array_push($xoso_record, (json_decode('{"game_code":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","game":"'. $value->game .'","location":"Live Casino (BBIN)","SerialID": "'. (json_decode($value->jsoninfo)[0]->SerialID) .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
        // }

        // $saba_record = DB::table('history_saba_bet')
        // ->where('createdate','>=',date("Y-m-d",strtotime($date1)) .' 11:00:00')
        // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date2))) .' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username',$user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value){
        //     $dataResults = json_decode($value->jsoninfo);
        //     if ($value->payout == 0 || $value->status == null) continue;
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName .': ' . $dataResults->homeName .' vs '. $dataResults->awayName . ' ('. $dataResults->betChoice. ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') .': ' .'('. (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //     $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //     $recordSaba->rawBet = $dataResults;
        //     array_push($xoso_record, $recordSaba);
        //     // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}')));
        // }
        $quickplayhistory = QuickPlayRecord::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->where('date', '>=', $date1)
            ->where('date', '<=', $date2)
            ->get();

        foreach ($quickplayhistory as $bet) {
            $xoso_record = null;
            $ids = explode(',', $bet->ids);
            $xoso_record = DB::table('xoso_record')
                ->orderBy('id', 'desc')
                // ->where('isDelete',false)
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->join('location', 'games.location_id', '=', 'location.id')
                ->where('xoso_record.user_id', $user->id)
                // ->where('xoso_record.total_win_money','<>',0)
                ->where('xoso_record.date', '>=', $date1)
                ->where('xoso_record.date', '<=', $date2)
                ->whereIn('xoso_record.id', $ids)
                ->select(
                    'xoso_record.id',
                    'xoso_record.date',
                    'xoso_record.game_id',
                    'xoso_record.user_id',
                    'xoso_record.odds',
                    'xoso_record.exchange_rates',
                    'xoso_record.total_win_money',
                    'xoso_record.bet_number',
                    'xoso_record.win_number',
                    'xoso_record.isDelete',
                    'xoso_record.created_at',
                    'xoso_record.updated_at',
                    'games.name as game',
                    'location.name as location'
                )
                //,'location.slug as locationslug'
                ->get();

            foreach ($xoso_record as $record) {
                $now = date('Y-m-d'); // 


                $record->allow_cancel = 1;
                //datenow - 18h 
                $minutes_to_add = 5;

                $time = new DateTime($record->created_at);
                $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));

                $stamp = $time->format('Y-m-d H:i:s');


                $hour = date('H', $time->getTimestamp());
                $min = date('i', $time->getTimestamp());
                $sec = date('s', $time->getTimestamp());
                $subtractTime = (new DateTime())->getTimestamp() - $time->getTimestamp();
                if ($hour >= 18 || $subtractTime >= 60 * 5) {
                    $record->allow_cancel = 0;
                    $record->cancel_at = '';
                } else {
                    $record->allow_cancel = 1;
                    //$subtractTime;
                    //datenow - 18h 
                    $record->cancel_at = $stamp;
                }
            }
            $bet['choices'] = $xoso_record;
        }
        return $quickplayhistory;
    }

    public static function GetByUserSkByIdsAPI($user, $ids)
    {
        $xoso_record = null;
        $xoso_record = DB::table('xoso_record')
            ->orderBy('id', 'desc')->where('isDelete', false)
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->join('location', 'games.location_id', '=', 'location.id')
            ->where('xoso_record.user_id', $user->id)
            ->whereIn('xoso_record.id', explode(',', $ids))
            // ->where('xoso_record.total_win_money','<>',0)
            ->select('xoso_record.id', 'xoso_record.id', 'xoso_record.date', 'xoso_record.game_id as game_code', 'xoso_record.isDelete', 'xoso_record.total_bet_money', 'xoso_record.odds', 'xoso_record.exchange_rates', 'xoso_record.bet_number', 'xoso_record.win_number', 'xoso_record.created_at', 'games.name as game_name', 'location.name as location_name')
            ->get();

        // $bbin_record = DB::table('history_live_bet')
        // ->where('createdate','>=',date("Y-m-d",strtotime($date1)) .' 11:00:00')
        // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date2))) .' 11:00:00')
        //     ->join('games', 'history_live_bet.gametype', '=', 'games.game_code')
        //     ->where('username',$user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();
        // foreach ($bbin_record as $value){
        //      array_push($xoso_record, (json_decode('{"game_code":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":0,"total_win_money":'. $value->payoff .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","game":"'. $value->game .'","location":"Live Casino (BBIN)","SerialID": "'. (json_decode($value->jsoninfo)[0]->SerialID) .'", "result": "'. json_decode($value->jsoninfo)[0]->ResultType .'"}')));
        // }

        // $saba_record = DB::table('history_saba_bet')
        // ->where('createdate','>=',date("Y-m-d",strtotime($date1)) .' 11:00:00')
        // ->where('createdate','<',date("Y-m-d",strtotime('+1 day',strtotime($date2))) .' 11:00:00')
        //     ->join('games', 'history_saba_bet.gametype', '=', 'games.game_code')
        //     ->where('username',$user->name)
        //     ->select('*', 'games.name as game')
        //     ->get();

        // foreach ($saba_record as $value){
        //     $dataResults = json_decode($value->jsoninfo);
        //     if ($value->payout == 0 || $value->status == null) continue;
        //         if (isset($dataResults->homeName) && $dataResults->homeName != '')
        //             $serialID =  $dataResults->leagueName .': ' . $dataResults->homeName .' vs '. $dataResults->awayName . ' ('. $dataResults->betChoice. ')';
        //         else
        //             $serialID =  (isset($dataResults->productName_en) ? $dataResults->productName_en : '') .': ' .'('. (isset($dataResults->gameName_en) ? $dataResults->gameName_en : '') . ')';
        //     $recordSaba = (json_decode('{"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}'));
        //     $recordSaba->rawBet = $dataResults;
        //     array_push($xoso_record, $recordSaba);
        //     // array_push($xoso_record, (json_decode('{"game_id":'. $value->gametype .',"total_bet_money":'. $value->betamount .',"com":'. $value->com .',"odds":0,"exchange_rates":1,"total_win_money":'. $value->payout .',"bet_number":"01","win_number":"","isDelete":0,"created_at":"'. $value->createdate .'","updated_at":"'. $value->createdate .'","xien_id":0,"game":"'. $value->game .'","location":"SABA","locationslug":"60", "SerialID": "'. $serialID .'", "result": "'. $value->status .'"}')));
        // }

        return $xoso_record;
    }

    public static function TotalBetTodayByNumber($gameid, $number)
    {
        // return Cache::tags('TotalBetTodayByNumber')->remember('TotalBetTodayByNumber-'.$gameid.'-'.$number, env('CACHE_TIME_BET', 0), function () use ($gameid,$number) {
        // ->whereIn('xoso_record.game_id', [9, 29])
        if ($gameid < 100) {
            if (
                $gameid == 9 || $gameid == 309 || $gameid == 409 || $gameid == 509 || $gameid == 609 || $gameid == 29
                || $gameid == 709
            )
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [9, 29])
                    ->get();
            elseif ($gameid == 7 || $gameid == 18)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->whereIn('xoso_record.game_id', [7, 18])
                    // ->orWhere('xoso_record.game_id',18)
                    ->get();
            elseif (
                $gameid == 9 || $gameid == 10 || $gameid == 11
                || $gameid == 309 || $gameid == 310 || $gameid == 311
                || $gameid == 409 || $gameid == 410 || $gameid == 411
                || $gameid == 509 || $gameid == 510 || $gameid == 511
                || $gameid == 609 || $gameid == 610 || $gameid == 611
                || $gameid == 709 || $gameid == 710 || $gameid == 711
            )
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
        } else {
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->where('xoso_record.game_id', $gameid)
                ->where('xoso_record.total_win_money', 0)
                ->get();
        }
        $totalBet = 0;
        foreach ($xoso_record as $record) {
            # code...
            $totalBet += $record->total_bet_money;
        }

        return $totalBet;
        // });
    }

    public static function TotalBetTodayByNumberThau($gameid, $number)
    {
        // return Cache::tags('TotalBetTodayByNumber')->remember('TotalBetTodayByNumber-'.$gameid.'-'.$number, env('CACHE_TIME_BET', 0), function () use ($gameid,$number) {
        // ->whereIn('xoso_record.game_id', [9, 29])
        if ($gameid < 100) {
            if ($gameid == 9 || $gameid == 309 || $gameid == 409 || $gameid == 509 || $gameid == 609 || $gameid == 709 || $gameid == 29)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [9, 29])
                    ->get();
            elseif ($gameid == 7 || $gameid == 18)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->whereIn('xoso_record.game_id', [7, 18])
                    // ->orWhere('xoso_record.game_id',18)
                    ->get();

            elseif (($gameid >= 31 && $gameid <= 55) || $gameid == 24)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->whereIn('xoso_record.game_id', [31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            elseif (
                $gameid == 9 || $gameid == 10 || $gameid == 11
                || $gameid == 309 || $gameid == 310 || $gameid == 311
                || $gameid == 409 || $gameid == 410 || $gameid == 411
                || $gameid == 509 || $gameid == 510 || $gameid == 511
                || $gameid == 609 || $gameid == 610 || $gameid == 611
                || $gameid == 709 || $gameid == 710 || $gameid == 711
            )
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
        } else

            if ($gameid >= 700) {
            if ($gameid == 709 || $gameid == 710 || $gameid == 711)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
        } else
            
            if ($gameid >= 300 && $gameid <= 600) {
            if ($gameid == 309 || $gameid == 329)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [309, 329])
                    ->get();
            elseif ($gameid == 409 || $gameid == 429)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [409, 429])
                    ->get();

            elseif ($gameid == 509 || $gameid == 529)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [509, 529])
                    ->get();

            elseif ($gameid == 609 || $gameid == 629)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [609, 629])
                    ->get();

            elseif (
                $gameid == 10 || $gameid == 11
                || $gameid == 310 || $gameid == 311
                || $gameid == 410 || $gameid == 411
                || $gameid == 510 || $gameid == 511
                || $gameid == 610 || $gameid == 611
            )
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
        } else {
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->where('xoso_record.game_id', $gameid)
                ->where('xoso_record.total_win_money', 0)
                ->get();
        }
        $totalBet = 0;
        $totalBetThau = 0;
        $userid = 0;
        // $min = 99999999;
        // $max = -1;
        switch ($gameid) {
            case 29:
                # code...
                $factnumber = 2;
                break;
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                # code...
                $factnumber = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                # code...
                $factnumber = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                # code...
                $factnumber = 4;
                break;
            default:
                # code...
                $factnumber = 1;
                break;
        }

        foreach ($xoso_record as $record) {
            # code...
            $userid = $record->user_id;
            $thau = XoSoRecordHelpers::GetThau($userid);
            // $tienThau = $record->total_bet_money * $thau;
            // $totalBetThau += $tienThau;
            // $totalBet+= $record->total_bet_money;

            if (
                $gameid == 29 || $gameid == 329 || $gameid == 429 || $gameid == 529  || $gameid == 629 || $gameid == 9 || $gameid == 10 || $gameid == 11
                || $gameid == 309 || $gameid == 310 || $gameid == 311
                || $gameid == 409 || $gameid == 410 || $gameid == 411
                || $gameid == 509 || $gameid == 510 || $gameid == 511
                || $gameid == 609 || $gameid == 610 || $gameid == 611
                || $gameid == 709 || $gameid == 710 || $gameid == 711
            ) {
                $soa = $factnumber;
                $n = count(explode(',', $record->bet_number));
                // $countbetnumber = count( explode(',',$record->bet_number) );
                $ank = XoSoRecordHelpers::fact($n) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($n - $factnumber);
                if ($soa == $n) {
                    $tienThau = $record->total_bet_money * $thau;
                    $totalBetThau += $tienThau;
                    $totalBet += $record->total_bet_money;
                }
                if ($soa < $n) {
                    $filterank = XoSoRecordHelpers::fact($n - 1) / (XoSoRecordHelpers::fact($soa - 1) * XoSoRecordHelpers::fact($n - $soa));
                    $tienThau = $record->total_bet_money * $thau * $filterank / $ank;
                    $totalBetThau += $tienThau;
                    $totalBet += $record->total_bet_money * $filterank / $ank;
                }

                // $totalBet += ($record->total_bet_money/$record->exchange_rates/$ank);
            } else {
                $tienThau = $record->total_bet_money * $thau;
                $totalBetThau += $tienThau;
                $totalBet += $record->total_bet_money;
            }
            // $totalBet += ($record->total_bet_money/$record->exchange_rates);

            // if ($tienThau > 0 && $min > $tienThau )
            //     $min = $tienThau;
            // if ($tienThau > 0 && $max < $tienThau )
            //     $max = $tienThau;
        }

        return [$totalBet, $totalBetThau];
        // });
    }

    public static function TotalBetTodayByNumberThauByUser($gameid, $number, $user)
    {
        // return Cache::tags('TotalBetTodayByNumber')->remember('TotalBetTodayByNumber-'.$gameid.'-'.$number, env('CACHE_TIME_BET', 0), function () use ($gameid,$number) {
        // ->whereIn('xoso_record.game_id', [9, 29])
        // $allUserChild = UserHelpers::GetAllUserV2($user);
        $arrUser = UserHelpers::GetAllUserV2($user);
        // foreach($allUserChild as $item)
        // if($item->roleid == 6){
        // array_push($arrUser,$item->id);
        // }
        // $arrUser = [1128,1148];
        // \Log::info($arrUser);
        // echo($user->id);
        // print_r($arrUser);
        if ($gameid < 100) {
            if ($gameid == 9 || $gameid == 309 || $gameid == 409 || $gameid == 509 || $gameid == 609 || $gameid == 709 || $gameid == 29)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [9, 29])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
            elseif ($gameid == 7 || $gameid == 18)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->whereIn('xoso_record.game_id', [7, 18])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    // ->orWhere('xoso_record.game_id',18)
                    ->get();

            elseif (($gameid >= 31 && $gameid <= 55) || $gameid == 24)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->whereIn('xoso_record.game_id', [31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            elseif (
                $gameid == 9 || $gameid == 10 || $gameid == 11
                || $gameid == 309 || $gameid == 310 || $gameid == 311
                || $gameid == 409 || $gameid == 410 || $gameid == 411
                || $gameid == 509 || $gameid == 510 || $gameid == 511
                || $gameid == 609 || $gameid == 610 || $gameid == 611
                || $gameid == 709 || $gameid == 710 || $gameid == 711
            )
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
        } else

            if ($gameid >= 700) {
            if ($gameid == 709 || $gameid == 710 || $gameid == 711)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
        } else
            
            if ($gameid >= 300 && $gameid <= 600) {
            if ($gameid == 309 || $gameid == 329)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [309, 329])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
            elseif ($gameid == 409 || $gameid == 429)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [409, 429])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();

            elseif ($gameid == 509 || $gameid == 529)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [509, 529])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();

            elseif ($gameid == 609 || $gameid == 629)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->whereIn('xoso_record.game_id', [609, 629])
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();

            elseif (
                $gameid == 10 || $gameid == 11
                || $gameid == 310 || $gameid == 311
                || $gameid == 410 || $gameid == 411
                || $gameid == 510 || $gameid == 511
                || $gameid == 610 || $gameid == 611
            )
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                    ->where('xoso_record.game_id', $gameid)
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.bet_number', $number)
                    ->where('xoso_record.game_id', $gameid)
                    ->whereIn('xoso_record.user_id', $arrUser)
                    ->get();
        } else {
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->where('xoso_record.game_id', $gameid)
                ->where('xoso_record.total_win_money', 0)
                ->whereIn('xoso_record.user_id', $arrUser)
                ->get();
        }
        $totalBet = 0;
        $totalBetThau = 0;
        $userid = 0;
        // $min = 99999999;
        // $max = -1;
        switch ($gameid) {
            case 29:
                # code...
                $factnumber = 2;
                break;
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                # code...
                $factnumber = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                # code...
                $factnumber = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                # code...
                $factnumber = 4;
                break;
            default:
                # code...
                $factnumber = 1;
                break;
        }

        foreach ($xoso_record as $record) {
            # code...
            $userid = $record->user_id;
            $thau = XoSoRecordHelpers::GetThau($userid);
            // $tienThau = $record->total_bet_money * $thau;
            // $totalBetThau += $tienThau;
            // $totalBet+= $record->total_bet_money;

            if (
                $gameid == 29 || $gameid == 329 || $gameid == 429 || $gameid == 529  || $gameid == 629 || $gameid == 9 || $gameid == 10 || $gameid == 11
                || $gameid == 309 || $gameid == 310 || $gameid == 311
                || $gameid == 409 || $gameid == 410 || $gameid == 411
                || $gameid == 509 || $gameid == 510 || $gameid == 511
                || $gameid == 609 || $gameid == 610 || $gameid == 611
                || $gameid == 709 || $gameid == 710 || $gameid == 711
            ) {
                $soa = $factnumber;
                $n = count(explode(',', $record->bet_number));
                // $countbetnumber = count( explode(',',$record->bet_number) );
                $ank = XoSoRecordHelpers::fact($n) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($n - $factnumber);
                if ($soa == $n) {
                    $tienThau = $record->total_bet_money * $thau;
                    $totalBetThau += $tienThau;
                    $totalBet += $record->total_bet_money;
                }
                if ($soa < $n) {
                    $filterank = XoSoRecordHelpers::fact($n - 1) / (XoSoRecordHelpers::fact($soa - 1) * XoSoRecordHelpers::fact($n - $soa));
                    $tienThau = $record->total_bet_money * $thau * $filterank / $ank;
                    $totalBetThau += $tienThau;
                    $totalBet += $record->total_bet_money * $filterank / $ank;
                }

                // $totalBet += ($record->total_bet_money/$record->exchange_rates/$ank);
            } else {
                $tienThau = $record->total_bet_money * $thau;
                $totalBetThau += $tienThau;
                $totalBet += $record->total_bet_money;
            }
            // $totalBet += ($record->total_bet_money/$record->exchange_rates);

            // if ($tienThau > 0 && $min > $tienThau )
            //     $min = $tienThau;
            // if ($tienThau > 0 && $max < $tienThau )
            //     $max = $tienThau;
        }
        // \Log::info($gameid .'-' .$number.'-'.$user->name.'-'.count($arrUser).'-'.count($xoso_record).'-'.$totalBetThau);
        return [$totalBet, $totalBetThau];
        // });
    }

    public static function GetThau($userid)
    {
        if ($userid == 0) return 0;
        $thau_khach = -1;
        $thau_tong = -1;
        $thau_ag = -1;
        $thau_spag = -1;
        try {
            $user_khach = User::where('id', '=', $userid)->first();
            $thau_khach = $user_khach->thau;

            $user_tong = User::where('id', '=', $user_khach->user_create)->first();
            $thau_tong = $user_tong->thau;

            $user_ag = User::where('id', '=', $user_tong->user_create)->first();
            $thau_ag = $user_ag->thau;

            $user_spag = User::where('id', '=', $user_ag->user_create)->first();
            $thau_spag = $user_spag->thau;

            return ($thau_spag + $thau_ag + $thau_khach + $thau_tong) / 100;
        } catch (\Exception $ex) {
            Log::info($user_khach->id . ' ' . $thau_khach . ' ' . $thau_tong . ' ' . $thau_ag);
            return 0;
        }
    }
    public static function TotalPointBetTodayByNumber($gameid, $number)
    {
        // $xoso_record = DB::table('xoso_record')
        //     ->where('isDelete',false)
        //     ->where('user_id',Auth::user()->id)
        //     ->where('xoso_record.date',date("Y-m-d"))
        //     ->where('xoso_record.bet_number',$number)
        //     ->where('xoso_record.game_id',$gameid)
        //     ->get();
        if ($gameid == 9 || $gameid == 309 || $gameid == 409 || $gameid == 509 || $gameid == 609 || $gameid == 709 || $gameid == 29)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                ->whereIn('xoso_record.game_id', [9, 29])
                ->get();
        elseif ($gameid == 7 || $gameid == 18)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->whereIn('xoso_record.game_id', [7, 18])
                // ->orWhere('xoso_record.game_id',18)
                ->get();
        elseif (
            $gameid == 9 || $gameid == 10 || $gameid == 11
            || $gameid == 309 || $gameid == 310 || $gameid == 311
            || $gameid == 409 || $gameid == 410 || $gameid == 411
            || $gameid == 509 || $gameid == 510 || $gameid == 511
            || $gameid == 609 || $gameid == 610 || $gameid == 611
            || $gameid == 709 || $gameid == 710 || $gameid == 711
        )
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                ->where('xoso_record.game_id', $gameid)
                ->get();
        else
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->where('xoso_record.game_id', $gameid)
                ->get();

        $totalBet = 0;

        switch ($gameid) {
            case 29:
                # code...
                $factnumber = 2;
                break;
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                # code...
                $factnumber = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                # code...
                $factnumber = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                # code...
                $factnumber = 4;
                break;
            default:
                # code...
                $factnumber = 1;
                break;
        }

        foreach ($xoso_record as $record) {
            # code...
            if ($record->exchange_rates != 0) {
                if (
                    $gameid == 29 || $gameid == 329 || $gameid == 429 || $gameid == 529  || $gameid == 629 || $gameid == 9 || $gameid == 10 || $gameid == 11
                    || $gameid == 309 || $gameid == 310 || $gameid == 311
                    || $gameid == 409 || $gameid == 410 || $gameid == 411
                    || $gameid == 509 || $gameid == 510 || $gameid == 511
                    || $gameid == 609 || $gameid == 610 || $gameid == 611
                    || $gameid == 709 || $gameid == 710 || $gameid == 711
                ) {
                    $countbetnumber = count(explode(',', $record->bet_number));
                    $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
                    $totalBet += ($record->total_bet_money / $record->exchange_rates / $ank);
                } else
                    $totalBet += ($record->total_bet_money / $record->exchange_rates);
            }
        }
        return $totalBet;
    }

    public static function TotalPointBetTodayByNumberUser($gameid, $number)
    {
        // $xoso_record = DB::table('xoso_record')
        //     ->where('isDelete',false)
        //     ->where('user_id',Auth::user()->id)
        //     ->where('xoso_record.date',date("Y-m-d"))
        //     ->where('xoso_record.bet_number',$number)
        //     ->where('xoso_record.game_id',$gameid)
        //     ->get();
        $userid = Auth::user()->id;
        if ($gameid == 9 || $gameid == 309 || $gameid == 409 || $gameid == 509 || $gameid == 609 || $gameid == 709 || $gameid == 29)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                ->whereIn('xoso_record.game_id', [9, 29])
                ->get();
        elseif ($gameid == 7 || $gameid == 18)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->whereIn('xoso_record.game_id', [7, 18])
                // ->orWhere('xoso_record.game_id',18)
                ->get();
        elseif (
            $gameid == 9 || $gameid == 10 || $gameid == 11
            || $gameid == 309 || $gameid == 310 || $gameid == 311
            || $gameid == 409 || $gameid == 410 || $gameid == 411
            || $gameid == 509 || $gameid == 510 || $gameid == 511
            || $gameid == 609 || $gameid == 610 || $gameid == 611
            || $gameid == 709 || $gameid == 710 || $gameid == 711
        )
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', 'like', '%' . $number . '%')
                ->where('xoso_record.game_id', $gameid)
                ->get();
        else
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.bet_number', $number)
                ->where('xoso_record.game_id', $gameid)
                ->get();

        $totalBet = 0;

        switch ($gameid) {
            case 29:
                # code...
                $factnumber = 2;
                break;
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                # code...
                $factnumber = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                # code...
                $factnumber = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                # code...
                $factnumber = 4;
                break;
            default:
                # code...
                $factnumber = 1;
                break;
        }

        foreach ($xoso_record as $record) {
            # code...
            if ($record->exchange_rates != 0) {
                if (
                    $gameid == 29 || $gameid == 329 || $gameid == 429 || $gameid == 529  || $gameid == 629 || $gameid == 9 || $gameid == 10 || $gameid == 11
                    || $gameid == 309 || $gameid == 310 || $gameid == 311
                    || $gameid == 409 || $gameid == 410 || $gameid == 411
                    || $gameid == 509 || $gameid == 510 || $gameid == 511
                    || $gameid == 609 || $gameid == 610 || $gameid == 611
                    || $gameid == 709 || $gameid == 710 || $gameid == 711
                ) {
                    $countbetnumber = count(explode(',', $record->bet_number));
                    $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
                    $totalBet += ($record->total_bet_money / $record->exchange_rates / $ank);
                } else
                    $totalBet += ($record->total_bet_money / $record->exchange_rates);
            }
        }
        return $totalBet;
    }

    public static function TotalPointBetTodayByUser($gameid)
    {
        // $xoso_record = DB::table('xoso_record')
        //     ->where('isDelete',false)
        //     ->where('user_id',Auth::user()->id)
        //     ->where('xoso_record.date',date("Y-m-d"))
        //     ->where('xoso_record.bet_number',$number)
        //     ->where('xoso_record.game_id',$gameid)
        //     ->get();
        $userid = Auth::user()->id;
        if ($gameid == 9 || $gameid == 309 || $gameid == 409 || $gameid == 509 || $gameid == 609 || $gameid == 709 || $gameid == 29)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                // ->where('xoso_record.bet_number','like','%'.$number.'%')
                ->whereIn('xoso_record.game_id', [9, 29])
                ->get();
        elseif ($gameid == 7 || $gameid == 18)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                // ->where('xoso_record.bet_number',$number)
                ->whereIn('xoso_record.game_id', [7, 18])
                // ->orWhere('xoso_record.game_id',18)
                ->get();
        elseif (
            $gameid == 9 || $gameid == 10 || $gameid == 11
            || $gameid == 309 || $gameid == 310 || $gameid == 311
            || $gameid == 409 || $gameid == 410 || $gameid == 411
            || $gameid == 509 || $gameid == 510 || $gameid == 511
            || $gameid == 609 || $gameid == 610 || $gameid == 611
            || $gameid == 709 || $gameid == 710 || $gameid == 711
        )
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                // ->where('xoso_record.bet_number','like','%'.$number.'%')
                ->where('xoso_record.game_id', $gameid)
                ->get();
        else
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('user_id', $userid)
                ->where('xoso_record.date', date("Y-m-d"))
                // ->where('xoso_record.bet_number',$number)
                ->where('xoso_record.game_id', $gameid)
                ->get();

        $totalBet = 0;

        switch ($gameid) {
            case 29:
                # code...
                $factnumber = 2;
                break;
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                # code...
                $factnumber = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                # code...
                $factnumber = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                # code...
                $factnumber = 4;
                break;
            default:
                # code...
                $factnumber = 1;
                break;
        }

        foreach ($xoso_record as $record) {
            # code...
            if ($record->exchange_rates != 0) {
                if (
                    $gameid == 29 || $gameid == 329 || $gameid == 429 || $gameid == 529  || $gameid == 629 || $gameid == 9 || $gameid == 10 || $gameid == 11
                    || $gameid == 309 || $gameid == 310 || $gameid == 311
                    || $gameid == 409 || $gameid == 410 || $gameid == 411
                    || $gameid == 509 || $gameid == 510 || $gameid == 511
                    || $gameid == 609 || $gameid == 610 || $gameid == 611
                    || $gameid == 709 || $gameid == 710 || $gameid == 711
                ) {
                    $countbetnumber = count(explode(',', $record->bet_number));
                    $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
                    $totalBet += ($record->total_bet_money / $record->exchange_rates / $ank);
                } else
                    $totalBet += ($record->total_bet_money / $record->exchange_rates);
            }
        }
        return $totalBet;
    }

    public static function TotalBetTodayByGame($gameid)
    {
        // return Cache::tags('TotalBetTodayByGame')->remember('TotalBetTodayByGame-'.$gameid, env('CACHE_TIME_BET', 0), function () use ($gameid) {
        if ($gameid == 9 || $gameid == 29)
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->whereIn('xoso_record.game_id', [9, 29])
                // ->orWhere('xoso_record.game_id', 29)
                ->get();
        else
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.game_id', $gameid)
                ->get();
        $totalBet = 0;
        foreach ($xoso_record as $record) {
            # code...
            $totalBet += $record->total_bet_money;
        }
        return $totalBet;
        // });
    }

    public static function TotalBetTodayByGameByUser($gameid, $userId = 274)
    {
        // $userId = 1136;
        // return Cache::tags('TotalBetTodayByGame')->remember('TotalBetTodayByGame-'.$gameid, env('CACHE_TIME_BET', 0), function () use ($gameid) {
        try {
            $user = User::where("id", $userId)->first();
            if ($user->roleid == 6) {
                if ($gameid == 9 || $gameid == 29)
                    $xoso_record = DB::table('xoso_record')
                        ->where('isDelete', false)
                        ->where('xoso_record.date', date("Y-m-d")) //date("Y-m-d"))
                        // ->whereIn('xoso_record.game_id', [9,29])
                        ->where('xoso_record.game_id', $gameid)
                        ->where('user_id', $userId)
                        ->get();
                else
                    $xoso_record = DB::table('xoso_record')
                        ->where('isDelete', false)
                        ->where('xoso_record.date', date("Y-m-d")) //date("Y-m-d"))
                        ->where('xoso_record.game_id', $gameid)
                        ->where('user_id', $userId)
                        ->get();
                $totalBet = 0;
                foreach ($xoso_record as $record) {
                    # code...
                    $totalBet += $record->total_bet_money;
                }
                return $totalBet;
            } else {
                $totalBet = 0;
                $userChild = User::where("user_create", $userId)->get();
                foreach ($userChild as $user) {
                    $totalBet += XoSoRecordHelpers::TotalBetTodayByGameByUser($gameid, $user->id);
                }
                // \Log::info($userId . ': '.$totalBet  .' '.$gameid. ' - ');
                return $totalBet;
            }
        } catch (\Exception $ex) {
            // \Log::info($ex->getMessage());// . ': '.$userId  .' '.$gameid. ' - ');
            echo $ex->getMessage() . ': ' . $userId  . ' ' . $gameid . ' - ';
        }
        return 0;
        // });
    }

    public static function lastestBetTime($gameid)
    {
        try {
            if ($gameid == 24)
                $item = XoSoRecord::orderBy('id', 'desc')->where('game_id', '>=', 31)->where('game_id', '<=', 55)->limit(1)->first();
            else if ($gameid == 7)
                $item = XoSoRecord::orderBy('id', 'desc')->where('game_id', 7)->OrWhere('game_id', 18)->limit(1)->first();
            else
                $item = XoSoRecord::orderBy('id', 'desc')->where('game_id', $gameid)->limit(1)->first();
            return [$item->created_at, $item->id];
        } catch (Exception $ex) {
        }
        return [null, 0];
    }

    public static function calThauAdminGameLatest($game_code, $latestID = 0, $latestIDTemp = 0)
    {
        // echo 'calThauAdminGame';
        $total9 = 0;
        $total8 = 0;
        $totalRecords = [];
        $totalNumber = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $totalNumberThau = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        try {
            $users = UserHelpers::GetAllUserChildv2Admin("luk79", 274, 2);
            // thêm hệ số lô live
            foreach ($users as $user) {
                $userReport = static::getRecordSuperLatestv2($user, $game_code, $latestID, $latestIDTemp);
                // Cache::remember('XoSoRecordHelpers-ReportKhachv2'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
                //     return  
                // });

                // print_r($userReport);
                array_push($totalRecords, $userReport);
                // break;

                if (count($userReport) > 0) {

                    foreach ($userReport as $key => $item) {
                        $total9 += $item->sumbet  / 100 * $user->thau;
                        $total8 += $item->sumbet;
                        $totalNumberThau[(int)$item->bet_number] += $item->sumbet  / 100 * $user->thau;
                        $totalNumber[(int)$item->bet_number] += $item->sumbet;
                    }
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage() . '-' . $ex->getFile() . $ex->getLine();
        }
        // echo $total8;
        return ['total8' => $total8, 'total9' => $total9, 'totalRecords' => $totalRecords, 'totalNumber' => $totalNumber, 'totalNumberThau' => $totalNumberThau];
    }

    public static function calThauSuperGameLatest($userSuper,$game_code, $latestID = 0, $latestIDTemp = 0)
    {
        // echo 'calThauAdminGame';
        $total9 = 0;
        $total8 = 0;
        $totalRecords = [];
        $totalNumber = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $totalNumberThau = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        try {
            echo 'GetAllUserChildv2Admin';
            $users = UserHelpers::GetAllUserChildv2Admin("luk79", $userSuper->id, 2);
            // thêm hệ số lô live
            foreach ($users as $user) {
                echo 'getRecordSuperLatestv2';
                $userReport = static::getRecordSuperLatestv2($user, $game_code, $latestID, $latestIDTemp);
                // Cache::remember('XoSoRecordHelpers-ReportKhachv2'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
                //     return  
                // });

                // print_r($userReport);
                array_push($totalRecords, $userReport);
                // break;

                if (count($userReport) > 0) {
                    echo 'cal thau';
                    $thau = isset($user->thau) ? $user->thau : 0;
                    foreach ($userReport as $key => $item) {
                        $total9 += $item->sumbet  / 100 * $thau;
                        $total8 += $item->sumbet;
                        $totalNumberThau[(int)$item->bet_number] += $item->sumbet  / 100 * $thau;
                        $totalNumber[(int)$item->bet_number] += $item->sumbet;
                    }
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage() . '-' . $ex->getFile() . $ex->getLine();
        }
        // echo $total8;
        return ['total8' => $total8, 'total9' => $total9, 'totalRecords' => $totalRecords, 'totalNumber' => $totalNumber, 'totalNumberThau' => $totalNumberThau];
    }

    public static function calThauAdminGame($game_code)
    {
        // echo 'calThauAdminGame';
        $total9 = 0;
        $total8 = 0;
        $totalRecords = [];
        $totalNumber = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $totalNumberThau = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        try {
            $users = UserHelpers::GetAllUserChildv2Admin("luk79", 274, 2);

            foreach ($users as $user) {
                $userReport = static::getRecordSuperv2($user, $game_code);
                // Cache::remember('XoSoRecordHelpers-ReportKhachv2'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
                //     return  
                // });

                // print_r($userReport);
                array_push($totalRecords, $userReport);
                // break;

                if (count($userReport) > 0) {
                    $thau = isset($user->thau) ? $user->thau : 0;
                    foreach ($userReport as $key => $item) {
                        $total9 += $item->sumbet  / 100 * $thau;
                        $total8 += $item->sumbet;
                        $totalNumberThau[(int)$item->bet_number] += $item->sumbet  / 100 * $thau;
                        $totalNumber[(int)$item->bet_number] += $item->sumbet;
                    }
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage() . '-' . $ex->getLine();
        }
        // echo $total8;
        return ['total8' => $total8, 'total9' => $total9, 'totalRecords' => $totalRecords, 'totalNumber' => $totalNumber, 'totalNumberThau' => $totalNumberThau];
    }

    public static function calThauAdminGameNumber($game_code, $game_number)
    {
        // echo 'calThauAdminGame';
        $total9 = 0;
        $total8 = 0;
        try {
            $users = UserHelpers::GetAllUserChildv2Admin("luk79", 274, 2);

            foreach ($users as $user) {
                $userReport = static::getRecordSuperByNumberv2($user, $game_code, $game_number);
                // Cache::remember('XoSoRecordHelpers-ReportKhachv2'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
                //     return  
                // });

                // print_r($userReport);
                // break;

                if (count($userReport) > 0) {
                    // echo $user->name . ":".$userReport[0]->sumbet."  ";
                    $total9 += $userReport[0]->sumbet  / 100 * $user->thau;
                    $total8 += $userReport[0]->sumbet;
                }
            }
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
        // echo $total8;
        return $total9;
    }

    public static function TotalBetTodayByGameThau($gameid)
    {
        // return Cache::tags('TotalBetTodayByGame')->remember('TotalBetTodayByGame-'.$gameid, env('CACHE_TIME_BET', 0), function () use ($gameid) {
        if ($gameid < 100) {
            if ($gameid == 9 || $gameid == 29)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->whereIn('xoso_record.game_id', [9, 29])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            elseif (($gameid >= 31 && $gameid <= 55) || $gameid == 24)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->whereIn('xoso_record.game_id', [31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
        } else
            if ($gameid >= 300 && $gameid <= 600) {
            if ($gameid == 309 || $gameid == 329)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->whereIn('xoso_record.game_id', [309, 329])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            else
                if ($gameid == 409 || $gameid == 429)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->whereIn('xoso_record.game_id', [409, 429])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            else
                if ($gameid == 509 || $gameid == 529)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->whereIn('xoso_record.game_id', [509, 529])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            else
                if ($gameid == 609 || $gameid == 629)
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->whereIn('xoso_record.game_id', [609, 629])
                    // ->orWhere('xoso_record.game_id', 29)
                    ->get();
            else
                $xoso_record = DB::table('xoso_record')
                    ->where('isDelete', false)
                    ->where('xoso_record.date', date("Y-m-d"))
                    ->where('xoso_record.game_id', $gameid)
                    ->get();
        } else {
            $xoso_record = DB::table('xoso_record')
                ->where('isDelete', false)
                ->where('xoso_record.date', date("Y-m-d"))
                ->where('xoso_record.game_id', $gameid)
                ->where('xoso_record.total_win_money', 0)
                ->get();
        }
        $totalBet = 0;
        $totalBetThau = 0;
        $userid = 0;
        foreach ($xoso_record as $record) {
            # code...
            $userid = $record->user_id;
            $thau = XoSoRecordHelpers::GetThau($userid);
            $totalBetThau += ($record->total_bet_money * $thau);
            $totalBet += $record->total_bet_money;
        }
        return [$totalBet, $totalBetThau];
        // });
    }

    public static function TimeoutBet($rs, $game_code, $h_game)
    {
        try {
            if ($game_code < 100) {
                if (intval(date('H')) == 18 && intval(date('i')) >= 14 && intval(date('i')) < 30)
                    if (!isset($rs) || !isset($rs['8']))
                        $h_close = strtotime($h_game['close']);
                    else {
                        if (($h_game['game_code'] == 9 || $h_game['game_code'] == 10 || $h_game['game_code'] == 11 || $h_game['game_code'] == 29 || $h_game['game_code'] == 2) && $rs['8'] >= 23)
                            $h_close = strtotime('08:00');
                        elseif (($h_game['game_code'] == 12 || $h_game['game_code'] == 56 || $h_game['game_code'] == 28) && $rs['8'] >= 1)
                            $h_close = strtotime('08:00');
                        elseif (($h_game['game_code'] == 14 || $h_game['game_code'] == 27 || $h_game['game_code'] == 17) && strlen($rs['spec_character']) >= 5)
                            $h_close = strtotime('08:00');
                        elseif ($h_game['game_code'] == 18 && $rs['8'] >= 25)
                            $h_close = strtotime('08:00');
                        elseif (($h_game['game_code'] >= 31 && $h_game['game_code'] <= 55)) {
                            // if ($h_game['game_code'] == 32 && $rs['8'] >= 1)
                            //     $h_close = strtotime('08:00');
                            // elseif ($h_game['game_code'] == 33 && $rs['8'] >= 2)
                            //     $h_close = strtotime('08:00');
                            // else
                            if ($h_game['game_code'] == 34 && $rs['8'] >= 1)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 35 && $rs['8'] >= 2)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 36 && $rs['8'] >= 3)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 37 && $rs['8'] >= 4)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 38 && $rs['8'] >= 5)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 39 && $rs['8'] >= 6)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 40 && $rs['8'] >= 7)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 41 && $rs['8'] >= 8)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 42 && $rs['8'] >= 9)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 43 && $rs['8'] >= 10)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 44 && $rs['8'] >= 11)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 45 && $rs['8'] >= 12)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 46 && $rs['8'] >= 13)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 47 && $rs['8'] >= 14)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 48 && $rs['8'] >= 15)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 49 && $rs['8'] >= 16)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 50 && $rs['8'] >= 17)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 51 && $rs['8'] >= 18)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 52 && $rs['8'] >= 19)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 53 && $rs['8'] >= 20)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 54 && $rs['8'] >= 21)
                                $h_close = strtotime('08:00');
                            elseif ($h_game['game_code'] == 55 && $rs['8'] >= 21)
                                $h_close = strtotime('08:00');
                            else
                                $h_close = strtotime($h_game['close']);
                        } else
                            $h_close = strtotime($h_game['close']);
                    }
                else
                    $h_close = strtotime($h_game['close']);
                // Log::info($h_close);
                return $h_close;
            }
        } catch (\Exception $ex) {
            Log::info("h_close" . $ex->getMessage());
            return strtotime("00:00");
        }
        return strtotime("00:00");
    }

    public static function CheckXosoRecord($locknumberRed)
    {
        // ->where("id", 64682)
        $historys = History::where("created_at", ">", date("Y-m-d 16:30:00"))->get();

        $notificationLst = [];
        $recordCancelLst = [];
        $locknumberRedBU = $locknumberRed;
        echo count($historys). PHP_EOL;
        foreach ($historys as $history) {
            $ids = explode(',', $history->ids);
            $records = XoSoRecord::
            join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->select('xoso_record.*', 'games.name as game')
            ->whereIn('xoso_record.id', $ids)
            // ->whereIn('user_id',[1550,1481])
            ->get();
            //1550 Zzz113a 1551 Zzz113b
            $recordsGroup = array();
            foreach ($records as $element) {
                $recordsGroup[$element->game_id][] = $element;
                // echo $element->game_id." ";
            }
            // var_dump($recordsGroup);
            // echo count($recordsGroup).PHP_EOL;
            foreach ($recordsGroup as $key => $records) {
                echo count($records) ."." . $key;
                // $locknumberRed = GameHelpers::LockNumberRed($key);
                $locknumberRed = $locknumberRedBU;
                $locknumberRed = trim($locknumberRed);
                $countRedNumberAg = count(explode(",", $locknumberRed));
                
                if ($countRedNumberAg > 0 && $locknumberRed != ""
                    && ($key == 7 ||$key == 9 || $key == 10 || $key == 11 || $key == 18
                    || $key == 16 || $key == 19 || $key == 20 ||  $key == 21 
                    || ($key >= 31 && $key <= 55))) {
                    $returnBet = false;
                    $perNormalRed = (100 - $countRedNumberAg) / $countRedNumberAg;
                    $countRedNumber = 0;
                    $countNormalNumber = 0;
                    $countRedNumberMoney = 0;
                    $countNormalNumberMoney = 0;
                    foreach ($records as $record) {
                        if (
                            $record->game_id != 29 && $record->game_id != 9 && $record->game_id != 10 && $record->game_id != 11
                            && $record->game_id != 309 && $record->game_id != 310 && $record->game_id != 311
                            && $record->game_id != 409 && $record->game_id != 410 && $record->game_id != 411
                            && $record->game_id != 509 && $record->game_id != 510 && $record->game_id != 511
                            && $record->game_id != 609 && $record->game_id != 610 && $record->game_id != 611
                            && $record->game_id != 709 && $record->game_id != 710 && $record->game_id != 711
                            && $record->game_id != 109 && $record->game_id != 110 && $record->game_id != 111
                        ) {
                            
                            if ($record->game_id == 19 || $record->game_id == 20 || $record->game_id == 21) {
                                $arrbetnumber = explode(',', $record->bet_number);
                                $countRed = 0;
                                $countNormal = 0;
                                foreach ($arrbetnumber as $number) {
                                    if (str_contains($locknumberRed, $number)) {
                                        $countRed++;
                                    } else {
                                        $countNormal++;
                                    }
                                }
                                if ($countRed > 0) {
                                    $locknumberRed = "";
                                } else {
                                    //hủy
                                    $returnBet = true;
                                    $record->total_win_money = -1;
                                    $record->ipaddr = "Trả lại: " . date('Y-m-d H:i');
                                    $record->save();
                                    $recordCancelLst[$record->user_id][] = QuickbetHelpers::revertquickplayFromDB($record);
                                    if (isset($notificationLst[$record->user_id][$record->game_id]))
                                        $notificationLst[$record->user_id][$record->game_id]++;
                                    else{
                                        $notificationLst[$record->user_id][$record->game_id] = 1;
                                    }
                                    $user = UserHelpers::GetUserById($record->user_id); //moneyback
                                    $user->remain += $record->total_bet_money;
                                    $user->consumer -= $record->total_bet_money;
                                    $user->save();

                                }
                            } else if ($record->game_id == 16) {
                                if (str_contains($locknumberRed, $record->bet_number)) {
                                    $countRedNumber++;
                                } else {
                                    $countNormalNumber++;
                                }
                            } else {
                                //check lô đề 1 số, lô trượt
                                if (str_contains($locknumberRed, $record->bet_number)) {
                                    $countRedNumber++;
                                    $countRedNumberMoney += intval($record->total_bet_money);
                                } else {
                                    $countNormalNumber++;
                                    $countNormalNumberMoney += intval($record->total_bet_money);
                                    // echo "total normal".$record->bet_number.PHP_EOL;
                                }
                            }
                        } else if (
                            $record->game_id == 29 || $record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11
                            || $record->game_id == 309 || $record->game_id == 310 || $record->game_id == 311
                            || $record->game_id == 409 || $record->game_id == 410 || $record->game_id == 411
                            || $record->game_id == 509 || $record->game_id == 510 || $record->game_id == 511
                            || $record->game_id == 609 || $record->game_id == 610 || $record->game_id == 611
                            || $record->game_id == 709 || $record->game_id == 710 || $record->game_id == 711
                        ) {
                            //check xiên
                            $arrbetnumber = explode(',', $record->bet_number);
                            foreach ($arrbetnumber as $number) {
                                if (str_contains($locknumberRed, $number)) {
                                    $countRedNumber++;
                                } else {
                                    $countNormalNumber++;
                                }
                            }
                            if ($countRedNumberAg >= 49 && $countNormalNumber > 0) $locknumberRed = "";
                        }
                    }

                    if ($countRedNumber > 0 || $returnBet) {
                        $countRedNumberAg = explode(",", $locknumberRed);

                        if (
                            $key != 29 && $key != 9 && $key != 10 && $key != 11
                            && $key != 309 && $key != 310 && $key != 311
                            && $key != 409 && $key != 410 && $key != 411
                            && $key != 509 && $key != 510 && $key != 511
                            && $key != 609 && $key != 610 && $key != 611
                            && $key != 709 && $key != 710 && $key != 711
                            && $key != 109 && $key != 110 && $key != 111
                        ) {
                            if ($key == 19 || $key == 20 || $key == 21) {
                            } else if ($key == 16) {
                                echo $countRedNumber . " " . $perNormalRed . " " . $countNormalNumber;
                                if ($countRedNumber * $perNormalRed > $countNormalNumber) {
                                    $locknumberRed = "";
                                } else {
                                    $returnBet = true;
                                }
                            } else {
                                $xNormal2Red = $countNormalNumber / $countRedNumber;
                                $checkNormalRed = $countRedNumberMoney * $xNormal2Red < $countNormalNumberMoney;
                                $checkNormalRed = $countRedNumberMoney * $perNormalRed <= $countNormalNumberMoney;
                                Log::info("lo de:".$countRedNumberMoney * $perNormalRed  . " " . $countNormalNumberMoney);
                                if ($checkNormalRed) {
                                    $locknumberRed = "";
                                } else {
                                    $returnBet = true;
                                }
                            }
                        } else if (
                            $key == 29 || $key == 9 || $key == 10 || $key == 11
                            || $key == 309 || $key == 310 || $key == 311
                            || $key == 409 || $key == 410 || $key == 411
                            || $key == 509 || $key == 510 || $key == 511
                            || $key == 609 || $key == 610 || $key == 611
                            || $key == 709 || $key == 710 || $key == 711
                        ) {
                            //check xiên ở dưới phần vào cược
                        }
                    }

                    if ($locknumberRed == "") continue;

                    // if ($key == 16) {
                    //     //
                    //     Log::info("check lo truot 1");
                    //     if ($countRedNumber * $perNormalRed > $countNormalNumber) {
                    //         $locknumberRed = "";
                    //     }else{
                    //         $returnBet = true;
                    //     }

                    foreach ($records as $record) {
                        if (
                            $record->game_id != 29 && $record->game_id != 9 && $record->game_id != 10 && $record->game_id != 11
                            && $record->game_id != 309 && $record->game_id != 310 && $record->game_id != 311
                            && $record->game_id != 409 && $record->game_id != 410 && $record->game_id != 411
                            && $record->game_id != 509 && $record->game_id != 510 && $record->game_id != 511
                            && $record->game_id != 609 && $record->game_id != 610 && $record->game_id != 611
                            && $record->game_id != 709 && $record->game_id != 710 && $record->game_id != 711
                            && $record->game_id != 109 && $record->game_id != 110 && $record->game_id != 111
                        ) {
                            if ($returnBet) {
                                if ($record->game_id == 19 || $record->game_id == 20 || $record->game_id == 21) {
                                }else{
                                    $insertBet = false;
                                    $record->total_win_money = -1;
                                    $record->ipaddr = "Trả lại: " . date('Y-m-d H:i');
                                    $record->save();

                                    $recordCancelLst[$record->user_id][] = QuickbetHelpers::revertquickplayFromDB($record);
                                    if (isset($notificationLst[$record->user_id][$record->game_id]))
                                        $notificationLst[$record->user_id][$record->game_id]++;
                                    else{
                                        $notificationLst[$record->user_id][$record->game_id] = 1;
                                    }
                                    $user = UserHelpers::GetUserById($record->user_id); //moneyback
                                    $user->remain += $record->total_bet_money;
                                    $user->consumer -= $record->total_bet_money;
                                    $user->save();
                                }
                            }
                        } else if (
                            $record->game_id == 29 || $record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11
                            || $record->game_id == 309 || $record->game_id == 310 || $record->game_id == 311
                            || $record->game_id == 409 || $record->game_id == 410 || $record->game_id == 411
                            || $record->game_id == 509 || $record->game_id == 510 || $record->game_id == 511
                            || $record->game_id == 609 || $record->game_id == 610 || $record->game_id == 611
                            || $record->game_id == 709 || $record->game_id == 710 || $record->game_id == 711
                        ) {
                            $arrbetnumber = explode(',', $record->bet_number);
                            $countRedNumberBet = 0;
                            echo "check xien";
                            foreach ($arrbetnumber as $number) {
                                if ($countRedNumberAg > 49) {
                                    if (str_contains($locknumberRed, $number)) {
                                        $countRedNumberBet++;
                                    }
                                } else {
                                    if (str_contains($locknumberRed, $number)) {
                                        $insertBet = false;
                                        $record->total_win_money = -1;
                                        $record->ipaddr = "Trả lại: " . date('Y-m-d H:i');
                                        $record->save();

                                        $recordCancelLst[$record->user_id][] = QuickbetHelpers::revertquickplayFromDB($record);
                                        if (isset($notificationLst[$record->user_id][$record->game_id]))
                                            $notificationLst[$record->user_id][$record->game_id]++;
                                        else{
                                            $notificationLst[$record->user_id][$record->game_id] = 1;
                                        }

                                        $user = UserHelpers::GetUserById($record->user_id); //moneyback
                                        $user->remain += $record->total_bet_money;
                                        $user->consumer -= $record->total_bet_money;
                                        $user->save();

                                    }
                                }
                            }

                            if ($countRedNumberBet == count($arrbetnumber)) {
                                $record->total_win_money = -1;
                                $record->ipaddr = "Trả lại: " . date('Y-m-d H:i');
                                $record->save();
                                $recordCancelLst[$record->user_id][] = QuickbetHelpers::revertquickplayFromDB($record);
                                if (isset($notificationLst[$record->user_id][$record->game_id]))
                                    $notificationLst[$record->user_id][$record->game_id]++;
                                else{
                                    $notificationLst[$record->user_id][$record->game_id] = 1;
                                }

                                $user = UserHelpers::GetUserById($record->user_id); //moneyback
                                $user->remain += $record->total_bet_money;
                                $user->consumer -= $record->total_bet_money;
                                $user->save();

                            }
                        }
                    }
                }
            }
            // var_dump($notificationLst[$history->user_create]);
        }

        $totalBetCancel = 0;
        foreach($notificationLst as $userid=>$flUser){
            // if (isset($notificationLst[$history->user_create]) && $notificationLst[$history->user_create] != "")
            $message = "";
            $message1 = "";
            $countTotal = 0;
            foreach ($flUser as $key => $game_count) {
                # code...
                $message1 .= GameHelpers::GetGameByCode($key)->name .": ".$game_count." mã" ."\n";
                $countTotal+=$game_count;
            }
            $totalBetCancel+= $countTotal;
            $message = "Công ty hoàn trả tổng ".$countTotal." mã cược:"."\n";
            $message .= $message1 . "Thời gian: " . date("H:i:s") . "\n" . "Xin quý khách lưu ý !";
            // $message = urlencode($message);
            NotifyHelpers::saveNotification2(User::where("id",$userid)->first() ,$message,implode("<br>",$recordCancelLst[$userid]));
        }
        return $totalBetCancel;
    }

    public static function InsertXosoRecord($request, $user, $isSave = true, $responseids = false)
    {
        $now = date('Y-m-d'); // 
        if ('2024-02-08' < $now && $now < '2024-02-13')
            if ($responseids) {
                return ['status' => 'Hết giờ vào cược', 'ids' => '', 'status_code' => 'timeout'];
            } else
                return "Hết giờ vào cược";

        if ($user->lock != 0)
            if ($responseids) {
                return ['status' => 'Tài khoản ngừng đặt cược', 'ids' => '', 'status_code' => 'cancel_bet'];
            } else
                return "Tài khoản ngừng đặt cược";
        // $hour = date('H');
        // $min = date('i');
        // $sec = date('s');
        $yesterday = date('Y-m-d', time() - 86400);
        // if ($location->slug ==1){
        // $yesterday = date('Y-m-d', time()-86400);
        // $datepickerXS= date('d-m-Y', time()-86400);
        $xoso = new Xoso();
        if (intval(date('H')) < 18 || (intval(date('H')) == 18 && intval(date('i')) < 14)) {
            $rs =
                // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
                // return 
                $xoso->getKetQua(1, $yesterday);
            // });
            // $rs = xoso::getKetQua(1,$yesterday);
        } else {
            $rs =
                // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
                // return 
                $xoso->getKetQua(1, date('Y-m-d'));
            // });
            // $datepickerXS= date('d-m-Y');
        }

        // $h_open=0;
        $h_close = 0;
        // $is_actived = true;
        $h_game = GameHelpers::GetGameByGameCode($request->game_code);
        $locknumber = GameHelpers::LockNumberUser($request->game_code,$user);
        $locknumberRed = GameHelpers::LockNumberRed($request->game_code);
        $isMultiNumber = 0;
        // if($user->id != 1550 
        // && $user->id != 1481
        // ) $locknumberRed = "";
        $locknumberRed = trim($locknumberRed);
        // print_r ($h_game);
        try {
            $h_close = static::TimeoutBet($rs, $request->game_code, $h_game);
            if (strtotime('now') > $h_close) {
                // $is_actived = false;
                if ($responseids) {
                    return ['status' => 'Hết giờ vào cược', 'ids' => '', 'status_code' => 'timeout'];
                } else
                    return "Hết giờ vào cược";
            }
        } catch (\Exception $ex) {
        }

        $flag = true;
        $totalNow = 0;
        $tiendetruot = 0;
        $ipaddr = "";
        try {
            // $ipaddr = $request->ipaddr;
        } catch (\Exception $err) {
        }

        if ($request->game_code == 18 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11) {
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();
            if (count($kqxs) < 1)
                $kqxsdr = 0;
            else
                $kqxsdr = $kqxs->first()->Giai_8;
        }

        $lstLocknumber = "Khóa cược mã: ";
        $lstLockrednumber = "Khóa đỏ: ";
        $lstMaxbet = "maxbet: ";
        $lstMaxbetTong = "maxbetTong: ";
        $maxBetGame = 99999999;
        $maxBetOne = 99999999;
        try {
            $custome_type = GameHelpers::GetGameParentByCusTypeGameid($user->customer_type, $user->id, $request->game_code);
            $maxBetGame = $custome_type->max_point;
            $maxBetOne = $custome_type->max_point_one;
        } catch (\Exception $err) {
            Log::error('error ' . $err->getFile() . '-' . $err->getMessage() . '-' . $err->getLine());
        }

        $gamecur = GameHelpers::GetGameByCusType($user->customer_type, $user->id, $request->game_code);
        $odds = $gamecur['odds'];
        if ($request->game_code >= 31 and $request->game_code <= 55) {
            $gamecur24 = GameHelpers::GetGameByCusType($user->customer_type, $user->id, 24);
            $odds = $gamecur24['odds'];
            $maxBetGame = $gamecur24['max_point'];
            $maxBetOne = $gamecur24['max_point_one'];
        }
        // $gameid = $request->game_code;
        // $totalByNumber = array();
        // $gamesss = GameHelpers::GetGameByCode($gameid);
        // if ($gameid==29 || $gameid==329 || $gameid==429 || $gameid==529  || $gameid==629 || $gameid==9|| $gameid==10|| $gameid==11){

        //comment remove totalbet from db 20220904
        // if (isset($gamesss->totalbetnumber1) && strlen($gamesss->totalbetnumber1>1) ){
        //     $temp = explode("|",$gamesss->totalbetnumber1);
        //     $temp1 = array();

        //     for($i=0;$i<10;$i++)
        //     for($j=0;$j<10;$j++){ 
        //         $totalByNumberdb = $temp[$i*10+$j];

        //         $temp1[$i.''.$j]['key'] = $i.''.$j;
        //         $temp1[$i.''.$j]['value'] = $totalByNumberdb;
        //     }
        //     // asort($temp1);
        //     $totalByNumber[$request->game_code] = $temp1;
        // }

        // }

        //bắt đầu check khóa đỏ
        $countRedNumberAg = count(explode(",", $locknumberRed));
        Log::info($request->game_code . " " .$countRedNumberAg);
        if (true && $countRedNumberAg > 0 && $locknumberRed != ""
        && ($request->game_code == 7 ||$request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11 || $request->game_code == 18
        || $request->game_code == 16 || $request->game_code == 19 || $request->game_code == 20 ||  $request->game_code == 21 
        || ($request->game_code >= 31 && $request->game_code <= 55))) {
            Log::info(explode(",",$locknumberRed));
            $perNormalRed = (100 - $countRedNumberAg) / $countRedNumberAg;
            $countRedNumber = 0;
            $countNormalNumber = 0;
            $countRedNumberMoney = 0;
            $countNormalNumberMoney = 0;
            for ($i = 0; $i < count($request->choices); $i++) {
                if (
                    $request->game_code != 29 && $request->game_code != 9 && $request->game_code != 10 && $request->game_code != 11
                    && $request->game_code != 309 && $request->game_code != 310 && $request->game_code != 311
                    && $request->game_code != 409 && $request->game_code != 410 && $request->game_code != 411
                    && $request->game_code != 509 && $request->game_code != 510 && $request->game_code != 511
                    && $request->game_code != 609 && $request->game_code != 610 && $request->game_code != 611
                    && $request->game_code != 709 && $request->game_code != 710 && $request->game_code != 711
                    && $request->game_code != 109 && $request->game_code != 110 && $request->game_code != 111
                ) {
                    
                    if ($request->game_code == 19 || $request->game_code == 20 || $request->game_code == 21) {
                        
                        $arrbetnumber = explode(',', $request->choices[$i]['name']);
                        $countRed = 0;
                        $countNormal = 0;
                        foreach ($arrbetnumber as $number) {
                            $isMultiNumber++;
                            if (str_contains($locknumberRed, $number)) {
                                $countRed++;
                            } else {
                                $countNormal++;
                            }
                        }
                        Log::info($countRed . " xu ly lo truot ".$request->choices[$i]['name']);
                        if ($countRed > 0) {
                            $locknumberRed = "";
                        } else {
                            $locknumberRed = $request->choices[$i]['name'];
                            if ($responseids) {
                                return ['status' => 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!', 'ids' => '', 'number_block' => "", 'status_code' => 'overbet'];
                            } else {
                                return 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!';
                            }
                        }
                    } else if ($request->game_code == 16) {
                        if (str_contains($locknumberRed, $request->choices[$i]['name'])) {
                            $countRedNumber++;
                        } else {
                            $countNormalNumber++;
                        }
                    } else {
                        //check lô đề 1 số
                        $isMultiNumber++;
                        Log::info($request->game_code);
                        if (str_contains($locknumberRed, $request->choices[$i]['name'])) {
                            $countRedNumber++;
                            $countRedNumberMoney += intval($request->choices[$i]['total']);
                        } else {
                            $countNormalNumber++;
                            $countNormalNumberMoney += intval($request->choices[$i]['total']);
                        }
                    }
                } else if (
                    $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11
                    || $request->game_code == 309 || $request->game_code == 310 || $request->game_code == 311
                    || $request->game_code == 409 || $request->game_code == 410 || $request->game_code == 411
                    || $request->game_code == 509 || $request->game_code == 510 || $request->game_code == 511
                    || $request->game_code == 609 || $request->game_code == 610 || $request->game_code == 611
                    || $request->game_code == 709 || $request->game_code == 710 || $request->game_code == 711
                ) {
                    //check xiên
                    Log::info("XIEN " . $request->game_code);
                    $arrbetnumber = explode(',', $request->choices[$i]['name']);
                    foreach ($arrbetnumber as $number) {
                        $isMultiNumber++;
                        if (str_contains($locknumberRed, $number)) {
                            $countRedNumber++;
                        } else {
                            $countNormalNumber++;
                        }
                    }
                    Log::info($countRedNumber . " " . $countNormalNumber);
                    if ($countRedNumberAg >= 49 && $countNormalNumber > 0) $locknumberRed = "";
                }
            }

            Log::info($countRedNumber);
            if ($countRedNumber == 0 && $request->game_code == 16) {
                if ($responseids) {
                    return ['status' => 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!', 'ids' => '', 'number_block' => "", 'status_code' => 'overbet'];
                } else {
                    return 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!';
                }
            }

            if ($countRedNumber > 0) {

                $countRedNumberAg = explode(",", $locknumberRed);

                if (
                    $request->game_code != 29 && $request->game_code != 9 && $request->game_code != 10 && $request->game_code != 11
                    && $request->game_code != 309 && $request->game_code != 310 && $request->game_code != 311
                    && $request->game_code != 409 && $request->game_code != 410 && $request->game_code != 411
                    && $request->game_code != 509 && $request->game_code != 510 && $request->game_code != 511
                    && $request->game_code != 609 && $request->game_code != 610 && $request->game_code != 611
                    && $request->game_code != 709 && $request->game_code != 710 && $request->game_code != 711
                    && $request->game_code != 109 && $request->game_code != 110 && $request->game_code != 111
                ) {
                    if ($request->game_code == 19 || $request->game_code == 20 || $request->game_code == 21) {
                        //
                    } else if ($request->game_code == 16) {
                        //
                        Log::info("check lo truot 1");
                        if ($countRedNumber * $perNormalRed > $countNormalNumber) {
                            $locknumberRed = "";
                        } else {
                            if ($responseids) {
                                return ['status' => 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!', 'ids' => '', 'number_block' => "", 'status_code' => 'overbet'];
                            } else {
                                return 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!';
                            }
                        }
                    } else {
                        // lo: 01,02,03,04,05,06,10,11,12,13,14,44,55,66,77,88,99,80 x100d 
                        $xNormal2Red = $countNormalNumber / $countRedNumber;
                        Log::info("Check red " . $countRedNumber . " " . $countNormalNumber . " " . $countRedNumberMoney . " " . $countNormalNumberMoney);
                        Log::info($perNormalRed);
                        $checkNormalRed = $countRedNumberMoney * $perNormalRed <= $countNormalNumberMoney;
                        Log::info($countRedNumberMoney * $perNormalRed  . " " . $countNormalNumberMoney);
                        if ($checkNormalRed) {
                            $locknumberRed = "";
                        }
                    }
                } else if (
                    $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11
                    || $request->game_code == 309 || $request->game_code == 310 || $request->game_code == 311
                    || $request->game_code == 409 || $request->game_code == 410 || $request->game_code == 411
                    || $request->game_code == 509 || $request->game_code == 510 || $request->game_code == 511
                    || $request->game_code == 609 || $request->game_code == 610 || $request->game_code == 611
                    || $request->game_code == 709 || $request->game_code == 710 || $request->game_code == 711
                ) {
                    //check xiên ở dưới phần vào cược
                    if (($request->game_code == 9 || $request->game_code == 29) && $isMultiNumber > 2 && $countRedNumber >= 2){
                        if ($responseids) {
                            return ['status' => 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!', 'ids' => '', 'number_block' => "", 'status_code' => 'overbet'];
                        } else {
                            return 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!';
                        }
                    }

                    if ($request->game_code == 10 && $isMultiNumber > 3 && $countRedNumber >= 3){
                        if ($responseids) {
                            return ['status' => 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!', 'ids' => '', 'number_block' => "", 'status_code' => 'overbet'];
                        } else {
                            return 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!';
                        }
                    }

                    if ($request->game_code == 11 && $isMultiNumber > 4 && $countRedNumber >= 4){
                        if ($responseids) {
                            return ['status' => 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!', 'ids' => '', 'number_block' => "", 'status_code' => 'overbet'];
                        } else {
                            return 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!';
                        }
                    }
                }
            }
        }

        // kết thúc check khóa đỏ
        // -------------------------------------------------------------------------------


        //Vao cuoc
        $totalBettodayOne = array();
        for ($i = 0; $i < count($request->choices); $i++) {
            // if($request->game_code != "9" && $request->game_code != "10"&& $request->game_code != "11")
            {
                if ($request->choices[$i]['exchange'] < 300 && $request->game_code != 18)
                    return "error001";
                if ($request->game_code != 18 && !GameHelpers::CheckExchange($request->game_code, $request->choices[$i]['name'], $request->choices[$i]['exchange'])) {
                    $flag = false;
                }
                $totalNow += intval($request->choices[$i]['total']);
            }

            if (
                $request->game_code != 29 && $request->game_code != 9 && $request->game_code != 10 && $request->game_code != 11
                && $request->game_code != 309 && $request->game_code != 310 && $request->game_code != 311
                && $request->game_code != 409 && $request->game_code != 410 && $request->game_code != 411
                && $request->game_code != 509 && $request->game_code != 510 && $request->game_code != 511
                && $request->game_code != 609 && $request->game_code != 610 && $request->game_code != 611
                && $request->game_code != 709 && $request->game_code != 710 && $request->game_code != 711
                && $request->game_code != 109 && $request->game_code != 110 && $request->game_code != 111
            ) {


                if ($request->game_code != 19 && $request->game_code != 20 && $request->game_code != 21)
                    if (
                        !is_numeric($request->choices[$i]['name'])
                        || (($request->game_code != 17 && $request->game_code != 56) && strlen($request->choices[$i]['name']) > 2)
                    )
                        if ($responseids)
                            return ['status' => "Sai định dạng mã cược.", 'ids' => '', 'status_code' => 'param_invalid'];
                        else
                            return "Sai định dạng mã cược.";

                if (
                    ($request->game_code == 19 && count(explode(",", $request->choices[$i]["name"])) < 4)
                    || ($request->game_code == 20 && count(explode(",", $request->choices[$i]["name"])) < 8)
                    || ($request->game_code == 21 && count(explode(",", $request->choices[$i]["name"])) < 10)
                )
                    if ($responseids)
                        return ['status' => "Sai định dạng mã cược.", 'ids' => '', 'status_code' => 'param_invalid'];
                    else
                        return "Sai định dạng mã cược.";

                $game_code_temp = $request->game_code;
                if ($game_code_temp == 9 || $game_code_temp == 309 || $game_code_temp == 409 || $game_code_temp == 509 || $game_code_temp == 609 || $game_code_temp == 709 || $game_code_temp == 29)
                    $game_code_temp = 9;
                elseif ($game_code_temp == 7 || $game_code_temp == 18)
                    $game_code_temp = 7;

                if (!isset($totalBettodayOne[$request->choices[$i]['name']]))
                    $totalBettodayOne[$request->choices[$i]['name']] = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $game_code_temp . '-' . $request->choices[$i]['name'] . '-' . $now, 0) + intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'];
                else
                    $totalBettodayOne[$request->choices[$i]['name']] += intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'];


                // $totalBettodayOne = XoSoRecordHelpers::TotalPointBetTodayByNumberUser($request->game_code,$request->choices[$i]['name']) + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange'];

                // $totalBettodayOne = XoSoRecordHelpers::TotalPointBetTodayByNumberUser($request->game_code,$request->choices[$i]['name']) + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange'];

                // $totalBettodayOne = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($request->game_code,$request->choices[$i]['name'],$user)[1] + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange'];

                // $totalBettoday = XoSoRecordHelpers::TotalPointBetTodayByUser($request->game_code) + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange'];
                $insertBet = true;
                if (($totalBettodayOne[$request->choices[$i]['name']] > $maxBetGame && ($request->game_code != 2 || $request->game_code != 102)
                    || $maxBetOne < intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'])) {
                    if (!str_contains($lstMaxbet, $request->choices[$i]['name']))
                        $lstMaxbet .= $request->choices[$i]['name'] . ", ";
                    $insertBet = false;
                    // continue;
                }
                if (str_contains($locknumberRed, $request->choices[$i]['name']) && $request->game_code != 16) {
                    $insertBet = false;
                    // if ($responseids) {
                    //     return ['status' => 'Vượt quá giới hạn chơi cho phép.', 'ids' => '', 'status_code' => 'overbet'];
                    // } else
                    //     // return "Vượt quá giới hạn chơi cho phép."; //"error021";
                    //     return "error021";
                    $lstLockrednumber .= $request->choices[$i]['name'] . ',';
                }

                if (str_contains($locknumber, $request->choices[$i]['name'])) {
                    $insertBet = false;
                    $lstLocknumber .= $request->choices[$i]['name'] . ',';
                    // return " Khóa cược mã " . $request->choices[$i]['name'];
                }
                // if($insertBet)
                //     Cache::put('TotalPointBetTodayByNumberUser-'.$user->id.'-'.$game_code_temp.'-'.$request->choices[$i]['name'].'-'.$now, $totalBettodayOne, env('CACHE_TIME', 24*60));
                // if ($totalBettoday > $maxBetGame && ($request->game_code != 2 || $request->game_code != 102)){
                //     $lstMaxbet.=$request->choices[$i]['name'].", ";
                //     // continue;
                // }

            } else if (
                $request->game_code == 29 || $request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11
                || $request->game_code == 309 || $request->game_code == 310 || $request->game_code == 311
                || $request->game_code == 409 || $request->game_code == 410 || $request->game_code == 411
                || $request->game_code == 509 || $request->game_code == 510 || $request->game_code == 511
                || $request->game_code == 609 || $request->game_code == 610 || $request->game_code == 611
                || $request->game_code == 709 || $request->game_code == 710 || $request->game_code == 711
            ) {
                $arrbetnumber = explode(',', $request->choices[$i]['name']);
                $countbetnumber = count($arrbetnumber);
                switch ($request->game_code) {
                    case 29:
                        # code...
                        $factnumber = 2;
                        break;
                    case 9:
                    case 309:
                    case 409:
                    case 509:
                    case 609:
                    case 709:
                        # code...
                        $factnumber = 2;
                        break;
                    case 10:
                    case 310:
                    case 410:
                    case 510:
                    case 610:
                    case 710:
                        # code...
                        $factnumber = 3;
                        break;
                    case 11:
                    case 311:
                    case 411:
                    case 511:
                    case 611:
                    case 711:
                        # code...
                        $factnumber = 4;
                        break;
                    default:
                        # code...
                        $factnumber = 1;
                        break;
                }
                $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);

                if ($responseids) {
                    if ($countbetnumber < $factnumber)
                        return ['status' => "Bạn phải chọn đủ mã cược", 'ids' => '', 'status_code' => 'param_invalid'];
                    // return ['status'=>"Bạn phải chọn ".$factnumber." mã cược",'ids'=>'', 'status_code'=>'param_invalid'];
                }

                $countRedNumberBet = 0;
                foreach ($arrbetnumber as $number) {

                    $game_code_temp = $request->game_code;
                    if ($game_code_temp == 9 || $game_code_temp == 309 || $game_code_temp == 409 || $game_code_temp == 509 || $game_code_temp == 609 || $game_code_temp == 709 || $game_code_temp == 29)
                        $game_code_temp = 9;
                    elseif ($game_code_temp == 7 || $game_code_temp == 18)
                        $game_code_temp = 7;

                    if (!is_numeric($number))
                        if ($responseids)
                            return ['status' => "Sai định dạng mã cược", 'ids' => '', 'status_code' => 'param_invalid'];
                        else
                            return "Sai định dạng mã cược";
                    if (!isset($totalBettodayOne[$number]))
                        $totalBettodayOne[$number] = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $game_code_temp . '-' . $number . '-' . $now, 0) + intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'] / $ank;
                    else $totalBettodayOne[$number] += intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'] / $ank;

                    $insertBet = true;
                    // $totalBettodayOne = XoSoRecordHelpers::TotalPointBetTodayByNumberUser($request->game_code,$number) + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange']/$ank;
                    // $totalBettodayOne = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($request->game_code,$number,$user)[1] + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange']/$ank;

                    if (
                        $totalBettodayOne[$number] > $maxBetGame
                        || $maxBetOne < intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'] / $ank
                    ) {
                        if (!str_contains($lstMaxbet, $number))
                            $lstMaxbet .= $number . ", ";
                        $insertBet = false;
                        // continue;
                    }
                    if ($countRedNumberAg > 49) {
                        if (str_contains($locknumberRed, $number)) {
                            $countRedNumberBet++;
                        }
                    } else {
                        if (str_contains($locknumberRed, $number)) {
                            $insertBet = false;
                            // if ($responseids) {
                            //     return ['status' => 'Vượt quá giới hạn chơi cho phép', 'ids' => '', 'status_code' => 'overbet'];
                            // } else
                            //     // return "Vượt quá giới hạn chơi cho phép"; //"error021";
                            //     return "error021";
                            $lstLockrednumber .= $number . ',';
                        }
                    }

                    if (str_contains($locknumber, $number)) {
                        $lstLocknumber .= $number . ',';
                        $insertBet = false;
                        // return " Khóa cược mã " . $number;
                        // return " Khóa cược mã " . $locknumber;
                    }
                    // if($insertBet)
                    //     Cache::put('TotalPointBetTodayByNumberUser-'.$user->id.'-'.$game_code_temp.'-'.$number.'-'.$now, $totalBettodayOne, env('CACHE_TIME', 24*60));
                }

                if ($countRedNumberBet == count($arrbetnumber)) {
                    // if ($responseids) {
                    //     return ['status' => 'Vượt quá giới hạn chơi cho phép', 'ids' => '', 'status_code' => 'overbet'];
                    // } else
                    //     // return "Vượt quá giới hạn chơi cho phép"; //"error021";
                    //     return "error021";
                    $lstLockrednumber .= $number . ',';
                }
            }

            if (intval($request->choices[$i]['total']) == 0) {
                // Log::info('exception was @ ' . $record);
                // Log::error('error total_bet_money=0 '.$record->user_id.'-'.$record->bet_number.'-'.$record->game_id);
                return 'error ' . $request->choices[$i]['name'];
            }

            // if ($request->game_code>=31 && $request->game_code <=55)
            //     $customerType =  CustomerType_Game::where('game_id',24)
            //         ->where('created_user',274)
            //         ->where('code_type','A')
            //         ->first();
            // else
            //     $customerType =  CustomerType_Game::where('game_id',$request->game_code)
            //         ->where('created_user',274)
            //         ->where('code_type','A')
            //         ->first();

            if ($request->game_code >= 31 && $request->game_code <= 55)
                $customerType = Cache::remember('CustomerType_Game-' . '24' . '-A' . '-' . '274', env('CACHE_TIME_SHORT', 0), function () {
                    return
                        CustomerType_Game::where('game_id', 24)
                        ->where('created_user', 274)
                        ->where('code_type', 'A')
                        ->first();
                });
            else
                $customerType = Cache::remember('CustomerType_Game-' . $request->game_code . '-A' . '-' . '274', env('CACHE_TIME_SHORT', 0), function () use ($request) {
                    return
                        CustomerType_Game::where('game_id', $request->game_code)
                        ->where('created_user', 274)
                        ->where('code_type', 'A')
                        ->first();
                });

            try {
                $totalMaxMember = $customerType->change_max_one;
                $totalMaxMember = (int)$totalMaxMember * 1000;
                if ($totalMaxMember < 1000) {
                    $totalMaxMember = 100000;
                }
            } catch (\Exception $ex) {
                // return 'error '.$ex->getMessage();
                $totalMaxMember = 200000 * 1000;
            }

            //comment remove totalbet from db 20220904
            // try{
            //     if (isset($gamesss->totalbetnumber1)){
            //         if ($gameid==29 || $gameid==329 || $gameid==429 || $gameid==529  || $gameid==629 || $gameid==9|| $gameid==10|| $gameid==11
            //         || $gameid==309|| $gameid==310|| $gameid==311
            //         || $gameid==409|| $gameid==410|| $gameid==411
            //         || $gameid==509|| $gameid==510|| $gameid==511
            //         || $gameid==609|| $gameid==610|| $gameid==611
            //         || $gameid==709|| $gameid==710|| $gameid==711){
            //             $countNumber = 0;
            //             $lstMaxbettemp = '';
            //             $arrbetnumber = explode(',',$request->choices[$i]['name']);
            //             foreach ($arrbetnumber as $number) {
            //                 // $totalBettoday = $totalByNumber[$request->game_code][$i.''.$j]['value'] + intval($request->choices[$i]['total'])/$request->choices[$i]['exchange']/$ank;
            //                 // $currentTotalMember = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$number) + intval($request->choices[$i]['total'])/$ank;
            //                 $currentTotalMember = $totalByNumber[$request->game_code][$number]['value'] + intval($request->choices[$i]['total'])/$ank;
            //                 // return $currentTotalMember.'over';
            //                 if ($currentTotalMember > $totalMaxMember){
            //                     $lstMaxbettemp.=$number.", ";
            //                     // continue;
            //                     $countNumber++;
            //                 }
            //             }
            //             if ($gameid==29 || $gameid==329 || $gameid==429 || $gameid==529  || $gameid==629 && $countNumber > 0 ){
            //                 $lstMaxbetTong.=$lstMaxbettemp;
            //             }
            //             if ($gameid==9 && $gameid==309 && $gameid==409 && $gameid==509 && $gameid==609 && $gameid==709 && $countNumber > 1 ){
            //                 $lstMaxbetTong.=$lstMaxbettemp;
            //             } 
            //             if ($gameid==10 && $gameid==310 && $gameid=410 && $gameid=510 && $gameid==610 && $gameid==710 && $countNumber > 1 ){
            //                 $lstMaxbetTong.=$lstMaxbettemp;
            //             } 
            //             if ($gameid==11 && $gameid==311 && $gameid==411 && $gameid==511 && $gameid==611 && $gameid==711 && $countNumber > 2 ){
            //                 $lstMaxbetTong.=$lstMaxbettemp;
            //             } 
            //         }else{


            //             // $currentTotalMember = $totalByNumber[$request->game_code][$number]['value'] + intval($request->choices[$i]['total'])/$ank;
            //             // $currentTotalMember = XoSoRecordHelpers::TotalBetTodayByNumber($gameid,$request->choices[$i]['name']) + intval($request->choices[$i]['total']);
            //             $currentTotalMember = $totalByNumber[$request->game_code][$request->choices[$i]['name']]['value'] + intval($request->choices[$i]['total']);
            //             // $lstMaxbet.=$totalMaxMember.", ";
            //             if ($currentTotalMember > $totalMaxMember){
            //                 $lstMaxbetTong.=$request->choices[$i]['name'].", ";
            //             }
            //         }
            //     }
            // }catch(\Exception $ex){
            //     // return 'error '.$ex->getMessage();
            // }

        }
        if ($responseids) {
            // echo ($totalBettodayOne['31']);
            if (!$flag && $request->game_code != 18) {
                return ['status' => 'Lỗi giá cược', 'ids' => '', 'status_code' => 'exchangerates_invalid'];
            }

            if ($totalNow > $user->remain) {
                return ['status' => 'Tài khoản không đủ tiền thực hiện cược', 'ids' => '', 'status_code' => 'overloadmoney'];
            }
            // $lstLocknumber = "Khóa cược mã: ";
            if ($lstLocknumber != "Khóa cược mã: ")
                return ['status' => 'Vượt giới hạn cược', 'ids' => '', 'number_block' => str_replace('Khóa cược mã: ', '', $lstLocknumber), 'status_code' => 'overbet'];

            if ($lstLockrednumber != "Khóa đỏ: ")
                
                return ['status' => ($isMultiNumber > 1 ? 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!' : 'Số bạn nhập không hợp lệ. Xin hãy nhập số khác!'), 'ids' => '', 'number_block' => str_replace('Khóa cược mã: ', '', $lstLocknumber), 'status_code' => 'overbet'];
                
            if ($lstMaxbet != "maxbet: ")
                return ['status' => 'Vượt giới hạn cược /1 cược', 'ids' => '', 'number_block' => str_replace('maxbet: ', '', $lstMaxbet), 'status_code' => 'overbet'];
            if ($lstMaxbetTong != "maxbetTong: ")
                return ['status' => 'Vượt giới hạn cược tối đa', 'ids' => '', 'number_block' => str_replace('maxbetTong: ', '', $lstMaxbetTong), 'status_code' => 'overbet'];

            if ($isSave == false)
                return ['status' => 'ok', 'ids' => '', 'status_code' => 'ok'];
        } else {
            if (!$flag && $request->game_code != 18) {
                return "exchange";
            }

            if ($totalNow > $user->remain) {
                return "overloadmoney";
            }
            // $lstLocknumber = "Khóa cược mã: ";
            if ($lstLocknumber != "Khóa cược mã: ")
                return $lstLocknumber;
            if ($lstLockrednumber != "Khóa đỏ: ")
                return ($isMultiNumber > 1 ? 'Dãy số bạn nhập không hợp lệ. Xin hãy nhập số khác!' : 'Số bạn nhập không hợp lệ. Xin hãy nhập số khác!');
            if ($lstMaxbet != "maxbet: ")
                return $lstMaxbet;
            if ($lstMaxbetTong != "maxbetTong: ")
                return $lstMaxbetTong;

            if ($isSave == false)
                return 'ok';
        }


        $ids = []; {
            try {
                $xien_id = 0;
                $tiendetruot = 0;

                // $game_ = GameHelpers::GetGameByCode($request->game_code);
                for ($i = 0; $i < count($request->choices); $i++) {
                    // if ($request->game_code < 100 && strtotime('now') > strtotime($game_->close)) {
                    //     // do something
                    //     return "overtime";
                    // }

                    $record = new XoSoRecord;
                    $record->date = date('Y-m-d');
                    $record->total_bet_money = intval($request->choices[$i]['total']);
                    $record->bet_money_per_number = 0;
                    $record->win_money_per_number = 0;
                    $record->odds = $request->odds;
                    $record->odds = $odds;
                    $record->exchange_rates = $request->choices[$i]['exchange'];
                    $record->total_win_money = 0;
                    $record->bet_number = $request->choices[$i]['name'];
                    $record->user_id = $user->id;
                    $record->game_id = $request->game_code;
                    $record->win_number = '';
                    $record->isDelete = false;
                    $record->ipaddr = $ipaddr;
                    // if ($record->total_bet_money==0)
                    // {
                    //     // Log::info('exception was @ ' . $record);
                    //     Log::error('error total_bet_money=0 '.$record->user_id.'-'.$record->bet_number.'-'.$record->game_id);
                    //     continue;
                    // }
                    if (
                        $request->game_code == 29  || $request->game_code == 9   || $request->game_code == 10 || $request->game_code == 11
                        || $request->game_code == 109 || $request->game_code == 110 || $request->game_code == 111
                        || $request->game_code == 309 || $request->game_code == 310 || $request->game_code == 311
                        || $request->game_code == 409 || $request->game_code == 410 || $request->game_code == 411
                        || $request->game_code == 509 || $request->game_code == 510 || $request->game_code == 511
                        || $request->game_code == 609 || $request->game_code == 610 || $request->game_code == 611
                        || $request->game_code == 709 || $request->game_code == 710 || $request->game_code == 711
                    ) {
                        $arrbetnumber = explode(',', $request->choices[$i]['name']);
                        $countbetnumber = count($arrbetnumber);
                        switch ($request->game_code) {
                            case 29:
                                # code...
                                $factnumber = 2;
                                break;
                            case 9:
                            case 309:
                            case 409:
                            case 509:
                            case 609:
                            case 709:
                                # code...
                                $factnumber = 2;
                                break;
                            case 10:
                            case 310:
                            case 410:
                            case 510:
                            case 610:
                            case 710:
                                # code...
                                $factnumber = 3;
                                break;
                            case 11:
                            case 311:
                            case 411:
                            case 511:
                            case 611:
                            case 711:
                                # code...
                                $factnumber = 4;
                                break;
                            default:
                                # code...
                                $factnumber = 1;
                                break;
                        }
                        $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);

                        foreach ($arrbetnumber as $number) {

                            $game_code_temp = $request->game_code;
                            if ($game_code_temp == 9 || $game_code_temp == 309 || $game_code_temp == 409 || $game_code_temp == 509 || $game_code_temp == 609 || $game_code_temp == 709 || $game_code_temp == 29)
                                $game_code_temp = 9;
                            elseif ($game_code_temp == 7 || $game_code_temp == 18)
                                $game_code_temp = 7;

                            $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $game_code_temp . '-' . $number . '-' . $now, 0) + intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'];
                            Cache::put('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $game_code_temp . '-' . $number . '-' . $now, $totalBettodayOne, env('CACHE_TIME', 24 * 60));
                        }

                        if ($request->game_code == 9 || $request->game_code == 10 || $request->game_code == 11) {
                            $record->xien_id = $kqxsdr;
                            $record->save();
                        }
                        // if($xien_id==0)
                        // {
                        // $record->save();
                        // $xien_id = $record->id;
                        // }
                        // $record->xien_id = $xien_id;
                        // $record->save();
                    } else {
                        $game_code_temp = $request->game_code;
                        if ($game_code_temp == 7 || $game_code_temp == 18)
                            $game_code_temp = 7;

                        $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $game_code_temp . '-' . $request->choices[$i]['name'] . '-' . $now, 0) + intval($request->choices[$i]['total']) / $request->choices[$i]['exchange'];
                        Cache::put('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $game_code_temp . '-' . $request->choices[$i]['name'] . '-' . $now, $totalBettodayOne, env('CACHE_TIME', 24 * 60));

                        if ($request->game_code == 18) {
                            $record->xien_id = $kqxsdr + 1 + 1;
                            $record->save();
                        } else {
                            // $record->save();
                            // $record->xien_id = $record->id;
                            // $record->save();
                        }
                    }
                    if ($request->game_code >= 100 && $request->game_code < 200) {
                        if (date('i') >= 35)
                            $record->xien_id = (round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN)) + 1;
                        if (date('i') <= 15)
                            $record->xien_id = (round(date('H') / 1, 0, PHP_ROUND_HALF_DOWN));
                        if (date('H') == 0) {
                            // $datetime = new DateTime('yesterday');
                            // $yesterday = $datetime->format('Y-m-d');
                            // $xosoao->date = $yesterday;
                            $record->xien_id = 24;
                        }
                    }

                    // if ($request->game_code>=700 && $request->game_code<800){
                    //     $phut = date('i');
                    //     $record->xien_id = $phut - $phut%10;
                    // }

                    $record->save();
                    // $user = User::where('id',$user->id)
                    //         ->first();
                    $money = bcadd(str_replace(',', '', intval($request->choices[$i]['total'])), '0', 2);
                    $user->consumer += $money;
                    // if ($request->game_code == 15){
                    //     if ($tiendetruot < $money)
                    //         $tiendetruot = $money;
                    // }else
                    $user->remain -= $money;
                    $user->save();
                    // $user_work = User::where('name', '=', $user->name)->first();
                    //$insertId = HistoryHelpers::InsertHistory('Đánh cược','Đánh cược '.$money." của người dùng ".$user->fullname,$user_work->id,$money);
                    $insertId = $record->id;
                    array_push($ids, $insertId);
                    // $location_id =  GameHelpers::GetGameByCode($request->game_code)->location_id;
                    // XoSoRecordHelpers::saveFileHistory(18,2,3,4,5,6);
                    if ($request->game_code < 100) {
                        // XoSoRecordHelpers::saveFileHistory($request->game_code, $user->name, $insertId, $record->bet_number, $money, $record->xien_id);
                        Queue::pushOn('low09', new saveFileHistoryService($request->game_code, $user->name, $insertId, $record->bet_number, $money, $record->xien_id, false));
                    }

                    // GameHelpers::InsertGame_Number($request->game_code,$request->choices[$i]['name'],intval($request->choices[$i]['total']));
                    try {
                        if ($request->game_code != 18)
                            // XoSoRecordHelpers::PaymentLottery($record);
                            Queue::pushOn('low10', new PaymentLottery($record));

                        // if ($request->game_code != 22 && $request->game_code != 23 && $request->game_code != 24 && $request->game_code != 25
                        // && $request->game_code != 26 && $request->game_code != 27 && $request->game_code != 28)
                        // XoSoRecordHelpers::UpdateBetPriceAllUser($request->game_code,$request->choices[$i]['name'],$money);
                        // Queue::push(new UpdateBetPriceAllUser($request->game_code,$request->choices[$i]['name'],$money));
                        // Queue::pushOn(date('i')%5,new UpdateBetPriceAllUser($request->game_code,$request->choices[$i]['name'],$money));
                    } catch (\Exception $err) {
                        // return 'error';
                        Log::error('error ' . $err->getFile() . '-' . $err->getMessage() . '-' . $err->getLine());
                    }
                    // Cache::tags('XoSoRecord'.$user->id)->flush();
                    // Cache::tags('TotalBetTodayByNumber')->flush();
                    //forget('TotalBetTodayByNumber-'.$request->game_code.'-'.$record->bet_number);
                    // Cache::tags('TotalBetTodayByGame')->flush();
                    //forget('TotalBetTodayByGame-'.$request->game_code);
                }
            } catch (\Exception $err) {
                Log::error('error ' . $err->getFile() . '-' . $err->getMessage() . '-' . $err->getLine());
                return 'error ' . $err->getMessage() . '-' . $err->getLine();
            }
        }
        // if ($tiendetruot!=0){
        //     $user->remain -= $tiendetruot;
        //     $user->save();
        // }
        if ($responseids)
            return ['status' => 'ok', 'ids' => implode(',', $ids), 'status_code' => 'ok'];
        else
            return 'ok';
        // return "overtime";
    }
    public static function saveFileHistory($gameType, $customerName, $id, $betNumber, $TotalBet, $idXien, $isHuy = false)
    {
        try {

            $now = date('Ymd');
            $h = date('h');
            $nowTime = date('d-m-Y h:i:s');
            $game = GameHelpers::GetGameByCode($gameType);
            // $filename = public_path('gamehis')."/".$customerName."_".$game->name . "_".$now.'_'.$h.".csv";
            $filename = public_path('gamehis') . "/" . $game->name . "_" . $now . ".csv";

            $writeTitle = file_exists($filename) ? false : true;
            $more = ($gameType == 18 || $gameType == 9 || $gameType == 10 || $gameType == 11 || $gameType == 29) ? "(" . (27 - $idXien) . ")" : "";

            $arr = [($writeTitle ? "1" : count(file($filename))), $id, $game->name, $customerName, $betNumber . $more, number_format($TotalBet), $nowTime];
            if ($isHuy) {
                array_push($arr, "Huỷ Cược");
            }
            // save
            $f = fopen($filename, 'a');
            flock($f, LOCK_EX);

            if ($writeTitle) {
                fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
                fputcsv($f, ["STT", "Mã Cược", "Loại", "Người dùng", "Số cược", "Tiền cược", "Thời gian", "Huỷ"]);
            }
            fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($f, $arr);
            flock($f, LOCK_UN);
            fclose($f);
        } catch (Exception $ex) {
            // echo $ex->getMessage();
        }
    }

    public static function getMinCategory($game_code)
    {
        // $TotalBetTodayByNumberARR = [];
        // $TotalBetTodayByNumberARROG = [];
        $min = PHP_INT_MAX;
        // $max = PHP_INT_MIN;
        $count = 1;
        for ($i = 0; $i < 10; $i++)
            for ($j = 0; $j < 10; $j++) {
                $bet_number = $i . $j;
                $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-' . $game_code . '-' . $bet_number, [0, 0]);
                $totalBetByNumber = $TotalBetTodayByNumber[1];
                // $totalBetByNumber1 = $TotalBetTodayByNumber[0];
                if ($min > $totalBetByNumber) $min = $totalBetByNumber;
            }
        return $min;
    }

    public static function getMinCategorySuper($game_code,$userSuper)
    {
        // $TotalBetTodayByNumberARR = [];
        // $TotalBetTodayByNumberARROG = [];
        $min = PHP_INT_MAX;
        // $max = PHP_INT_MIN;
        $count = 1;
        for ($i = 0; $i < 10; $i++)
            for ($j = 0; $j < 10; $j++) {
                $bet_number = $i . $j;
                $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-' . $game_code . '-' . $bet_number.'-'.$userSuper->id, [0, 0]);
                $totalBetByNumber = $TotalBetTodayByNumber[1];
                // $totalBetByNumber1 = $TotalBetTodayByNumber[0];
                if ($min > $totalBetByNumber) $min = $totalBetByNumber;
            }
        return $min;
    }

    static function array_find($needle, $haystack, $property = "userid")
    {
        foreach ($haystack as $item) {
            if ($item->$property == $needle) {
                return $item;
                break;
            }
        }
        return null;
    }

    public static function UpdateBetPriceAllUser($gameTarget, $game_code, $bet_number, $TotalBetTodayByNumber, $TotalBetTodayByGame, $minBetBetByNumber, $maxBetBetByNumber)
    {
        try {
            echo 'UpdateBetPriceAllUser ';
            $totalBetByNumber = $TotalBetTodayByNumber[1];
            if ($game_code == 9 || $game_code == 29) $totalBetByNumber *= 2;
            $totalBetAll = $TotalBetTodayByGame[1];
            if ($game_code == 18) $game_code = 7;
            $y = $gameTarget->y;
            $a2 = $gameTarget->a2;
            $newbet = 0;
            // if (($game_code >= 721 && $game_code <= 739))
            //     $newbet = $gameTarget->exchange_rates + ($maxBetBetByNumber - $minBetBetByNumber)/10000000 * $y;
            // else
            //     $newbet = $gameTarget->exchange_rates + ($totalBetByNumber - $minBetBetByNumber)/($totalBetAll - 100 * $minBetBetByNumber) * $y;

            // $maxBet = $gameTarget->exchange_rates + ($maxBetBetByNumber - $minBetBetByNumber) / $z;

            $maxBet = 9999999;

            if (
                $game_code == 14 || $game_code == 9
                || $game_code == 10 || $game_code == 11
                || $game_code == 12 || $game_code == 27 || $game_code == 28
                || $game_code == 309 || $game_code == 310 || $game_code == 311
                || $game_code == 409 || $game_code == 410 || $game_code == 411
                || $game_code == 509 || $game_code == 510 || $game_code == 511
                || $game_code == 609 || $game_code == 610 || $game_code == 611
                || $game_code == 709 || $game_code == 710 || $game_code == 711
                || $game_code == 701
                || ($game_code >= 721 && $game_code <= 739)
                || ($game_code >= 31 && $game_code <= 55)
                ||  $game_code == 24
            ) {
                if ($maxBet > 1000)
                    $maxBet = 1000;
            }

            if ($game_code == 7) {
                if ($maxBet > 28000)
                    $maxBet = 28000;
            }

            if ($game_code == 29) {
                if ($maxBet > 1500)
                    $maxBet = 1500;
            }

            if ($game_code >= 721 && $game_code <= 739) {
            } else {

                if ($game_code == 14 || $game_code == 12)
                    $newbet = $gameTarget->exchange_rates + ($totalBetByNumber - static::getMinCategory($game_code)) / $y * $a2;
                else
                    $newbet = $gameTarget->exchange_rates + $totalBetByNumber / $y * $a2;
                // giá con đó = giá mua gốc + giá thêm ở bảng thao tác + (tổng $ thầu admin/ con đó )/ trị số lên


                echo ("bet_number:" . $bet_number . " exchange_rates:" . $gameTarget->exchange_rates . " totalBetAll:" . $totalBetAll . " totalBetByNumber:" . $totalBetByNumber . " y:" . $y . " a:" . $a2);

                // Log::info("newbet:".$newbet);
            }

            //extend price with 789
            if ($game_code == 14 || $game_code == 7 || $game_code == 12){
                $extend789 = Cache::get('fetchOne789Data-'.$game_code.'-'.$bet_number,0);
                $newbet += $extend789;
            }

            if ($newbet > $maxBet) {
                $newbet = $maxBet;
            }
            if (
                $game_code == 14 || $game_code == 25 || $game_code == 26 ||
                $game_code == 27 ||
                $game_code == 28 || $game_code == 12 || $game_code == 114 || $game_code == 112
                || ($game_code >= 721 && $game_code <= 739)
                || ($game_code >= 31 && $game_code <= 55)
                || $game_code == 24
                || $game_code == 25 || $game_code == 26 || $game_code == 27 || $game_code == 28
            ) {
                $newbet = (int)$newbet; //round($newbet, 0, PHP_ROUND_HALF_UP);
            } else if (
                $game_code == 7 || $game_code == 10 || $game_code == 9 || $game_code == 29
                || $game_code == 107
                || $game_code == 307 || $game_code == 310 || $game_code == 309
                || $game_code == 407 || $game_code == 410 || $game_code == 409
                || $game_code == 507 || $game_code == 510 || $game_code == 509
                || $game_code == 607 || $game_code == 610 || $game_code == 609
                || $game_code == 710 || $game_code == 709
                || $game_code == 701
            ) {
                $newbet_tmp = round($newbet, -1, PHP_ROUND_HALF_DOWN);
                if ($newbet_tmp - $newbet > 0) $newbet = $newbet_tmp - 10;
                else $newbet = $newbet_tmp;
            } else if (
                $game_code == 11 || $game_code == 311 || $game_code == 411 || $game_code == 511 || $game_code == 611
                || $game_code == 711
            ) {
                $newbet = round($newbet, -1, PHP_ROUND_HALF_UP);
                if ($newbet % 5 > 0 && $newbet % 5 < 3) {
                    $newbet = $newbet - ($newbet % 5);
                } else if ($newbet % 5 > 2 && $newbet % 5 < 5) {
                    $newbet = $newbet + 5 - ($newbet % 5);
                }
            }
            $newbet = round($newbet, 0, PHP_ROUND_HALF_UP);

            // Log::info("newbet ".$newbet);
            echo "newbet " . $newbet;
            $userAdmin = User::where('id', 274)->get();
            $game = $gameTarget; //GameHelpers::GetGameByCode($game_code);
            $lstUser = [];
            foreach ($userAdmin as $user) {
                $lstUser[] = $user->id;
            }

            $game_numbers =
                Game_Number::where('code_type', $game_code)
                ->where('number', $bet_number)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                // ->where('userid', $user->id)
                ->whereIn('userid', $lstUser)
                ->get();

            foreach ($userAdmin as $user) {
                $game_number = static::array_find($user->id, $game_numbers);
                // $game_number =
                //     Game_Number::where('code_type', $game_code)
                //     ->where('number', $bet_number)
                //     ->whereDate('updated_at', '=', date('Y-m-d'))
                //     ->where('userid', $user->id)
                //     ->get();

                if ($game_number != null) {
                    // $game_number = $game_number->first();
                    //$changeValue = $value-$game_number->exchange_rates;
                    if ($game_number->exchange_rates + $game_number->y < $game->exchange_rates)
                        $game_number->exchange_rates = $game->exchange_rates;

                    if ($game_number->exchange_rates != $newbet) {
                        $game_number->exchange_rates = $newbet;
                        $game_number->userid = $user->id;
                        $game_number->save();

                        Queue::pushOn("high", new UpdateChildEX($user, $game_number, $game_number->exchange_rates + $game_number->y, $gameTarget->exchange_rates,0));

                        // GameHelpers::UpdateChildEXv2($user,$game_number,$game_number->exchange_rates + $game_number->y,$gameTarget->exchange_rates);
                    }
                } else {
                    if ($game->exchange_rates != $newbet) {
                        $game_number = new Game_Number;
                        $game_number->exchange_rates = $newbet;
                        $game_number->a = $game->a;
                        $game_number->x = $game->x;
                        $game_number->y = 0;
                        $game_number->number = $bet_number;
                        $game_number->code_type = $game_code;
                        $game_number->userid = $user->id;
                        $game_number->save();

                        Queue::pushOn("high", new UpdateChildEX($user, $game_number, $game_number->exchange_rates, $gameTarget->exchange_rates,0));
                        // GameHelpers::UpdateChildEXv2($user,$game_number,$game_number->exchange_rates,$gameTarget->exchange_rates);
                    }
                }
            }
        } catch (\Exception $ex) {
            Log::error('error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            return 'error PaymentLottery:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function UpdateBetPriceAllUser_v4($gameTarget, $game_code, $bet_number, $TotalBetTodayByNumber, $TotalBetTodayByGame, $minBetBetByNumber, $maxBetBetByNumber,$userSuper)
    {
        try {
            echo 'UpdateBetPriceAllUser ';
            $totalBetByNumber = $TotalBetTodayByNumber[1];
            if ($game_code == 9 || $game_code == 29) $totalBetByNumber *= 2;
            $totalBetAll = $TotalBetTodayByGame[1];
            if ($game_code == 18) $game_code = 7;
            $y = $gameTarget->y;
            $a2 = $gameTarget->a2;
            $newbet = 0;
            // if (($game_code >= 721 && $game_code <= 739))
            //     $newbet = $gameTarget->exchange_rates + ($maxBetBetByNumber - $minBetBetByNumber)/10000000 * $y;
            // else
            //     $newbet = $gameTarget->exchange_rates + ($totalBetByNumber - $minBetBetByNumber)/($totalBetAll - 100 * $minBetBetByNumber) * $y;

            // $maxBet = $gameTarget->exchange_rates + ($maxBetBetByNumber - $minBetBetByNumber) / $z;

            $maxBet = 9999999;

            if (
                $game_code == 14 || $game_code == 9
                || $game_code == 10 || $game_code == 11
                || $game_code == 12 || $game_code == 27 || $game_code == 28
                || $game_code == 309 || $game_code == 310 || $game_code == 311
                || $game_code == 409 || $game_code == 410 || $game_code == 411
                || $game_code == 509 || $game_code == 510 || $game_code == 511
                || $game_code == 609 || $game_code == 610 || $game_code == 611
                || $game_code == 709 || $game_code == 710 || $game_code == 711
                || $game_code == 701
                || ($game_code >= 721 && $game_code <= 739)
                || ($game_code >= 31 && $game_code <= 55)
                ||  $game_code == 24
            ) {
                if ($maxBet > 1000)
                    $maxBet = 1000;
            }

            if ($game_code == 7) {
                if ($maxBet > 28000)
                    $maxBet = 28000;
            }

            if ($game_code == 29) {
                if ($maxBet > 1500)
                    $maxBet = 1500;
            }

            if ($game_code >= 721 && $game_code <= 739) {
            } else {

                if ($game_code == 14 || $game_code == 12)
                    $newbet = $gameTarget->exchange_rates + ($totalBetByNumber - static::getMinCategorySuper($game_code,$userSuper)) / $y * $a2;
                else
                    $newbet = $gameTarget->exchange_rates + $totalBetByNumber / $y * $a2;
                // giá con đó = giá mua gốc + giá thêm ở bảng thao tác + (tổng $ thầu admin/ con đó )/ trị số lên


                echo ("bet_number:" . $bet_number . " exchange_rates:" . $gameTarget->exchange_rates . " totalBetAll:" . $totalBetAll . " totalBetByNumber:" . $totalBetByNumber . " y:" . $y . " a:" . $a2);

                // Log::info("newbet:".$newbet);
            }

            //extend price with 789
            if ($game_code == 14 || $game_code == 7 || $game_code == 12){
                $extend789 = Cache::get('fetchOne789Data-'.$game_code.'-'.$bet_number,0);
                $newbet += $extend789;
            }

            if ($newbet > $maxBet) {
                $newbet = $maxBet;
            }
            if (
                $game_code == 14 || $game_code == 25 || $game_code == 26 ||
                $game_code == 27 ||
                $game_code == 28 || $game_code == 12 || $game_code == 114 || $game_code == 112
                || ($game_code >= 721 && $game_code <= 739)
                || ($game_code >= 31 && $game_code <= 55)
                || $game_code == 24
                || $game_code == 25 || $game_code == 26 || $game_code == 27 || $game_code == 28
            ) {
                $newbet = (int)$newbet; //round($newbet, 0, PHP_ROUND_HALF_UP);
            } else if (
                $game_code == 7 || $game_code == 10 || $game_code == 9 || $game_code == 29
                || $game_code == 107
                || $game_code == 307 || $game_code == 310 || $game_code == 309
                || $game_code == 407 || $game_code == 410 || $game_code == 409
                || $game_code == 507 || $game_code == 510 || $game_code == 509
                || $game_code == 607 || $game_code == 610 || $game_code == 609
                || $game_code == 710 || $game_code == 709
                || $game_code == 701
            ) {
                $newbet_tmp = round($newbet, -1, PHP_ROUND_HALF_DOWN);
                if ($newbet_tmp - $newbet > 0) $newbet = $newbet_tmp - 10;
                else $newbet = $newbet_tmp;
            } else if (
                $game_code == 11 || $game_code == 311 || $game_code == 411 || $game_code == 511 || $game_code == 611
                || $game_code == 711
            ) {
                $newbet = round($newbet, -1, PHP_ROUND_HALF_UP);
                if ($newbet % 5 > 0 && $newbet % 5 < 3) {
                    $newbet = $newbet - ($newbet % 5);
                } else if ($newbet % 5 > 2 && $newbet % 5 < 5) {
                    $newbet = $newbet + 5 - ($newbet % 5);
                }
            }
            $newbet = round($newbet, 0, PHP_ROUND_HALF_UP);

            // Log::info("newbet ".$newbet);
            echo "newbet " . $newbet;
            $userAdmin = User::where('id', $userSuper->id)->get();
            $game = $gameTarget; //GameHelpers::GetGameByCode($game_code);
            $lstUser = [];
            foreach ($userAdmin as $user) {
                $lstUser[] = $user->id;
            }

            $game_numbers =
                Game_Number::where('code_type', $game_code)
                ->where('number', $bet_number)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                // ->where('userid', $user->id)
                ->whereIn('userid', $lstUser)
                ->get();

            foreach ($userAdmin as $user) {
                $game_number = static::array_find($user->id, $game_numbers);
                // $game_number =
                //     Game_Number::where('code_type', $game_code)
                //     ->where('number', $bet_number)
                //     ->whereDate('updated_at', '=', date('Y-m-d'))
                //     ->where('userid', $user->id)
                //     ->get();

                if ($game_number != null) {
                    // $game_number = $game_number->first();
                    //$changeValue = $value-$game_number->exchange_rates;
                    if ($game_number->exchange_rates + $game_number->y < $game->exchange_rates)
                        $game_number->exchange_rates = $game->exchange_rates;

                    if ($game_number->exchange_rates != $newbet) {
                        $game_number->exchange_rates = $newbet;
                        $game_number->userid = $user->id;
                        $game_number->save();

                        Queue::pushOn("high", new UpdateChildEX($user, $game_number, $game_number->exchange_rates + $game_number->y, $gameTarget->exchange_rates,0));

                        // GameHelpers::UpdateChildEXv2($user,$game_number,$game_number->exchange_rates + $game_number->y,$gameTarget->exchange_rates);
                    }
                } else {
                    if ($game->exchange_rates != $newbet) {
                        $game_number = new Game_Number;
                        $game_number->exchange_rates = $newbet;
                        $game_number->a = $game->a;
                        $game_number->x = $game->x;
                        $game_number->y = 0;
                        $game_number->number = $bet_number;
                        $game_number->code_type = $game_code;
                        $game_number->userid = $user->id;
                        $game_number->save();

                        Queue::pushOn("high", new UpdateChildEX($user, $game_number, $game_number->exchange_rates, $gameTarget->exchange_rates,0));
                        // GameHelpers::UpdateChildEXv2($user,$game_number,$game_number->exchange_rates,$gameTarget->exchange_rates);
                    }
                }
            }
        } catch (\Exception $ex) {
            Log::error('error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            return 'error PaymentLottery:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function UpdateBetPriceAllUserBackup($gameTarget, $game_code, $bet_number, $TotalBetTodayByNumber, $TotalBetTodayByGame, $minBetBetByNumber, $maxBetBetByNumber)
    {
        try {
            $totalBetByNumber = $TotalBetTodayByNumber[1];
            // $minBetBetByNumber = $TotalBetTodayByNumber[2];
            // $maxBetBetByNumber = $TotalBetTodayByNumber[3];

            $totalBetAll = $TotalBetTodayByGame[1];
            // //admin
            if ($game_code == 18) $game_code = 7;
            // $gameTarget = GameHelpers::GetGameByCode($game_code);

            $m = $gameTarget->a;
            $h = $gameTarget->a2;
            $y = $gameTarget->y;
            $z = $gameTarget->x;

            // if ($game_code < 100){
            //     $pointtime1 = '00:00';
            //     $pointtime2 = '18:00';
            //     $pointtime3 = '18:15';
            //     $pointtime4 = '23:59';

            //     $nowtime = date('H:i');
            //     if ( $pointtime1 <= $nowtime && $nowtime < $pointtime2 ){
            //         $m = $gameTarget->a;
            //         $h = $gameTarget->x;
            //         $y = $gameTarget->y;
            //     }else if ( $pointtime2 <= $nowtime && $nowtime < $pointtime3 ){
            //         $m = $gameTarget->a2;
            //         $x = $gameTarget->x2;
            //         $y = $gameTarget->y2;
            //     }else if ( $pointtime3 <= $nowtime && $nowtime < $pointtime4 ){
            //         $a = $gameTarget->a3;
            //         $x = $gameTarget->x3;
            //         $y = $gameTarget->y3;
            //     }
            // }

            // if ($x==0) return;

            // $totalBetByNumber = XoSoRecordHelpers::TotalBetTodayByNumber($game_code,$bet_number);
            // $totalBetAll = XoSoRecordHelpers::TotalBetTodayByGame($game_code);
            // $triso1 = 400;
            // $triso2 = 100;
            // if ($totalBetByNumber <= ($totalBetAll/$triso1))
            //     return;

            // if ($totalBetByNumber <= ($gameTarget->a))
            //     return;

            // $newbet = $gameTarget->exchange_rates + ($totalBetByNumber/($totalBetAll/$triso2))*$y;
            // $newbet = $gameTarget->exchange_rates + (($totalBetByNumber/($totalBetAll/$triso2))-1)*$y;
            if (($game_code >= 721 && $game_code <= 739))
                $newbet = $gameTarget->exchange_rates + ($maxBetBetByNumber - $minBetBetByNumber) / 10000000 * $y;
            else
                $newbet = $gameTarget->exchange_rates + ($totalBetByNumber - $minBetBetByNumber) / ($totalBetAll - 100 * $minBetBetByNumber) * $y;

            $maxBet = $gameTarget->exchange_rates + ($maxBetBetByNumber - $minBetBetByNumber) / $z;

            if (
                $game_code == 14 || $game_code == 9
                || $game_code == 10 || $game_code == 11
                || $game_code == 12 || $game_code == 27 || $game_code == 28
                || $game_code == 309 || $game_code == 310 || $game_code == 311
                || $game_code == 409 || $game_code == 410 || $game_code == 411
                || $game_code == 509 || $game_code == 510 || $game_code == 511
                || $game_code == 609 || $game_code == 610 || $game_code == 611
                || $game_code == 709 || $game_code == 710 || $game_code == 711
                || $game_code == 701
                || ($game_code >= 721 && $game_code <= 739)
                || ($game_code >= 31 && $game_code <= 55)
                ||  $game_code == 24
            ) {
                if ($maxBet > 1000)
                    $maxBet = 1000;
            }

            if ($game_code == 7) {
                if ($maxBet > 28000)
                    $maxBet = 28000;
            }

            if ($game_code == 29) {
                if ($maxBet > 1500)
                    $maxBet = 1500;
            }

            if ($game_code >= 721 && $game_code <= 739) {
            } else {
                if (($totalBetByNumber - $minBetBetByNumber) <= $m) {
                    //If ( (ta – na) < (aa-100na)*0,008 )
                    if (($totalBetByNumber - $minBetBetByNumber) < (($totalBetAll - 100 * $minBetBetByNumber) / 200)) {
                        // giá mua ko đổi 
                        // return;
                        $newbet = $gameTarget->exchange_rates;
                    } else {
                        // Giá mua a = giá mua ad + ( ((ta – na)*(ta-na))/ (aa-100na) ) * $y
                        $newbet = $gameTarget->exchange_rates + ((($totalBetByNumber - $minBetBetByNumber)
                            * ($totalBetByNumber - $minBetBetByNumber)) / (($totalBetAll - 100 * $minBetBetByNumber) * ($totalBetAll - 100 * $minBetBetByNumber))) * $y;
                    }
                } else {
                    // Giá mua a = giá mua ad + ( ((ta – na)*(ta-na))/ (aa-100na) ) * $y + (ta-m-na)/ (ta-na) *$h
                    $newbet = $gameTarget->exchange_rates + ((($totalBetByNumber - $minBetBetByNumber)
                        * ($totalBetByNumber - $minBetBetByNumber)) / (($totalBetAll - 100 * $minBetBetByNumber) * ($totalBetAll - 100 * $minBetBetByNumber))) * $y
                        + ($totalBetByNumber - $minBetBetByNumber - $m) / ($totalBetByNumber - $minBetBetByNumber) * $h;
                }
            }

            // if ($newbet - $minBetBetByNumber <= $m){

            // }else if ( ( ($totalBetByNumber - $minBetBetByNumber) / ($totalBetAll - 100 * $minBetBetByNumber) * $y + ($newbet - $m - $minBetBetByNumber)/($newbet - $minBetBetByNumber) * $h ) <= 
            //    (($maxBetBetByNumber - $minBetBetByNumber)/$z + ($newbet - $m - $minBetBetByNumber)/($newbet - $minBetBetByNumber) * $h)){
            //     $newbet = ( ($totalBetByNumber - $minBetBetByNumber) / ($totalBetAll - 100 * $minBetBetByNumber) * $y + ($newbet - $m - $minBetBetByNumber)/($newbet - $minBetBetByNumber) * $h );
            // }else{
            //     $newbet = (($maxBetBetByNumber - $minBetBetByNumber)/$z + ($newbet - $m - $minBetBetByNumber)/($newbet - $minBetBetByNumber) * $h);
            // }

            // Log::info($minBetBetByNumber.' '.$maxBetBetByNumber.' '.$newbet.' '.$maxBet);

            if ($newbet > $maxBet) {
                $newbet = $maxBet;
            }
            if (
                $game_code == 14 || $game_code == 25 || $game_code == 26 ||
                $game_code == 27 ||
                $game_code == 28 || $game_code == 12 || $game_code == 114 || $game_code == 112
                || ($game_code >= 721 && $game_code <= 739)
                || ($game_code >= 31 && $game_code <= 55)
                ||  $game_code == 24
            ) {
                $newbet = round($newbet, 0, PHP_ROUND_HALF_UP);
            } else if (
                $game_code == 7 || $game_code == 10 || $game_code == 9 || $game_code == 29
                || $game_code == 107
                || $game_code == 307 || $game_code == 310 || $game_code == 309
                || $game_code == 407 || $game_code == 410 || $game_code == 409
                || $game_code == 507 || $game_code == 510 || $game_code == 509
                || $game_code == 607 || $game_code == 610 || $game_code == 609
                || $game_code == 710 || $game_code == 709
                || $game_code == 701
            ) {
                $newbet = round($newbet, -1, PHP_ROUND_HALF_DOWN);
            } else if (
                $game_code == 11 || $game_code == 311 || $game_code == 411 || $game_code == 511 || $game_code == 611
                || $game_code == 711
            ) {
                $newbet = round($newbet, -1, PHP_ROUND_HALF_UP);
                if ($newbet % 5 > 0 && $newbet % 5 < 3) {
                    $newbet = $newbet - ($newbet % 5);
                } else if ($newbet % 5 > 2 && $newbet % 5 < 5) {
                    $newbet = $newbet + 5 - ($newbet % 5);
                }
            }
            $newbet = round($newbet, 0, PHP_ROUND_HALF_UP);
            // Log::info('newexchange '.$game_code.' - '.$newbet.'-totalbetnumber:'.$totalBetByNumber.'-totalbet:'.$totalBetAll);

            //old
            //$totalBet = XoSoRecordHelpers::TotalBetTodayByNumber($game_code,$bet_number);
            // Cache::tags('TotalBetTodayByNumber')->forget('TotalBetTodayByNumber-'.$game_code.'-'.$bet_number);
            // if ($totalBet < $gameTarget->a)
            //     return;

            // $totalBet-=$value;
            // if ($totalBet == $gameTarget->a ){
            //     $newbet = $totalBet + $value;
            // }
            // else{
            //     if ($totalBet == 0)
            //         $newbet = $totalBet + $value - $gameTarget->a;
            //     else
            //     $newbet =  ($totalBet-$gameTarget->a)%$gameTarget->x + $value;
            // }

            // if ($newbet < $gameTarget->x)
            //     return;
            // //$totalBet = XoSoRecordHelpers::TotalBetTodayByNumber($game_code,$bet_number);

            // $incre = round($newbet/$gameTarget->x)*$gameTarget->y;
            // if ($incre < 0)
            //return;
            $userAdmin = User::where('id', 274)->get();
            foreach ($userAdmin as $user) {
                # code...
                $game_number =
                    // Cache::tags('Game_Number'.$user->id)->remember('Game_Number-'.$game_code.'-'.$bet_number.'-'.$user->id, env('CACHE_TIME', 0), function () use ($game_code,$bet_number,$user) {
                    // return 
                    Game_Number::where('code_type', $game_code)
                    ->where('number', $bet_number)
                    ->whereDate('updated_at', '=', date('Y-m-d'))
                    ->where('userid', $user->id)
                    ->get();
                // });


                $game = GameHelpers::GetGameByCode($game_code);

                if (count($game_number) > 0) {
                    $game_number = $game_number->first();
                    //$changeValue = $value-$game_number->exchange_rates;
                    if ($game_number->exchange_rates + $game_number->y < $game->exchange_rates)
                        $game_number->exchange_rates = $game->exchange_rates;

                    if ($game_number->exchange_rates != $newbet) {
                        $game_number->exchange_rates = $newbet;
                        $game_number->userid = $user->id;
                        $game_number->save();
                        // Cache::tags('Game_Number'.$user->id)->forget('Game_Number-'.$game_code.'-'.$bet_number.'-'.$user->id);
                        // Cache::tags('Game_Number'.$user->id)->forget('GetGame_AllNumber-'.$game_code.'-'.$user->id);
                        GameHelpers::UpdateChildEX($user, $game_number, $game_number->exchange_rates + $game_number->y, $gameTarget->exchange_rates);
                    }
                } else {
                    if ($game->exchange_rates != $newbet) {
                        $game_number = new Game_Number;
                        $game_number->exchange_rates = $newbet;
                        // $changeValue = $value-$game->exchange_rates;
                        $game_number->a = $game->a;
                        $game_number->x = $game->x;
                        $game_number->y = 0;
                        $game_number->number = $bet_number;
                        $game_number->code_type = $game_code;
                        $game_number->userid = $user->id;
                        $game_number->save();
                        // Cache::tags('Game_Number'.$user->id)->forget('Game_Number-'.$game_code.'-'.$bet_number.'-'.$user->id);
                        // Cache::tags('Game_Number'.$user->id)->forget('GetGame_AllNumber-'.$game_code.'-'.$user->id);
                        GameHelpers::UpdateChildEX($user, $game_number, $game_number->exchange_rates, $gameTarget->exchange_rates);
                    }
                }
            }


            // $totalBet = XoSoRecordHelpers::TotalBetTodayByNumber($game_code,$bet_number);
            // $totalBet-=$newmoney;
            // $newbet = $newmoney + ($totalBet%$gameTarget->a);

            // $totalBet = XoSoRecordHelpers::TotalBetTodayByNumber($game_code,$bet_number);
            // $gameTarget = Game::where('game_code',$game_code)->first();
            // $incre = (($newbet-$gameTarget->a)/$gameTarget->x)*$gameTarget->y;

            // if ($newbet >= $gameTarget->a)
            // {
            //     $userAll = UserHelpers::GetAllUserNonAdmin();
            //     foreach ($userAll as $user) {
            //         # code...
            //         $game_number = Game_Number::where('code_type',$game_code)
            //         ->where('number',$bet_number)
            //         ->where('userid', $user->id)
            //         ->first();

            //         if ($user->roleid == 6){
            //             $game = CustomerType_Game::where('code_type',$game_code)
            //                 ->where('created_user', $user->id)
            //                 ->first();
            //         }else
            //             $game = Game::where('game_code',$game_code)->first();

            //         if(count($game_number)>0)
            //         {
            //             $game_number->exchange_rates += $incre;
            //             $game_number->userid = $user->id;
            //             $game_number->save();
            //         }
            //         else
            //         {
            //             if (count($game)>0)
            //             {
            //                 $game_number = new Game_Number;
            //                 $game_number->exchange_rates = $game->exchange_rates + $incre;
            //                 //$changeValue = $value-$game->exchange_rates;
            //                 $game_number->a = $game->a;
            //                 $game_number->x = $game->x;
            //                 $game_number->y = 0;
            //                 $game_number->number = $bet_number;
            //                 $game_number->code_type = $game_code;
            //                 $game_number->userid = $user->id;
            //                 $game_number->save();
            //             }
            //         }
            //     }
            //     // $gameTarget->z = $totalBet - $gameTarget->a;
            //     // $gameTarget->save();
            // }
        } catch (\Exception $ex) {
            Log::error('error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            return 'error PaymentLottery:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function hoahong($id)
    {

        $record = XoSoRecordHelpers::GetByID($id);
        XoSoRecordHelpers::PaymentLottery($record);
        return;

        // $datetime = date('Y-m-d', time());
        // // $datetime = new DateTime('tomorrow');
        // $datetime ='2018-04-16';

        // foreach (XoSoRecordHelpers::GetByDateHH($datetime,538) as $record) {
        //     # code...
        //     // if (!isset($record->bonus)||strlen($record->bonus)<1)
        //         XoSoRecordHelpers::PaymentLottery($record);
        //     // $record = XoSoRecordHelpers::GetByID(3163);
        //     // $record = XoSoRecordHelpers::GetByID(3165);

        // }
    }

    public static function PaymentLotteryBU($record)
    {
        try {

            // $record = XoSoRecordHelpers::getRecordById($record);
            // print_r($record);

            $game_id = $record->game_id;
            $game_number = $record->bet_number;

            if ($game_id >= 31 && $game_id == 55)
                $game_id = 24;
            // if($game_id==14 || $game_id== 7||$game_id== 8|| $game_id== 12 || $game_id== 9|| $game_id== 15 || $game_id== 16 || $game_id== 17)
            {

                $bonustong = true;
                $bonusag = true;
                $bonussuper = true;
                $point = $record->total_bet_money / $record->exchange_rates;

                //Tính bonus cho Tổng
                $userPlay = User::where('id', $record->user_id)->first();
                $userTong = User::where('id', $userPlay->user_create)->first();
                // $userPlayPriceOne = $record->exchange_rates;
                // $userPlayOdds = $record->odds;



                $userPlayOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userPlay->id)
                    ->where('game_id', $game_id)
                    ->first();

                //gia mua member khi tong nang b2
                $giamember_b2 = $userPlayOne->exchange_rates;

                $userPlayPriceOne = $userPlayOne->exchange_rates;
                $userPlayOdds = $userPlayOne->odds;


                $userTongOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userTong->id)
                    ->where('game_id', $game_id)
                    ->first();
                $userGetMoneyTongPriceOne = $userTongOne->exchange_rates;
                $userGetMoneyTongOdds = $userTongOne->odds;


                //gia tong/ gia tong khi ag nang b1
                $giatong_b1 = $userTongOne->exchange_rates;

                //Tính bonus cho Đại lý
                $userDaily = User::where('id', $userTong->user_create)->first();

                $userDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userDaily->id)
                    ->first();
                $userDailyPriceOne = $userDailyOne->exchange_rates;
                $userDailyOdds = $userDailyOne->odds;

                //gia ag c1
                $giaag_c1 = $userDailyOne->exchange_rates;

                //Tính bonus cho super agent
                $userSpDaily = User::where('id', $userDaily->user_create)->first();

                $userSpDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSpDaily->id)
                    ->first();
                $userSpDailyPriceOne = $userSpDailyOne->exchange_rates;
                $userSpDailyOdds = $userSpDailyOne->odds;

                //gia ag c1
                $giaspag_c1 = $userSpDailyOne->exchange_rates;

                //Tính bonus cho Super
                $userSuper = User::where('id', $userDaily->user_create)->first();

                $userSuperOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSuper->id)
                    ->first();
                $userSuperPriceOne = $userSuperOne->exchange_rates;
                $userSuperOdds = $userSuperOne->odds;

                //gia mua admin a

                $giamuaadmin = GameHelpers::GetGame_NumberByUser($game_id, $game_number, $record->user_id);

                $giamuaadmin = count($giamuaadmin) > 0 ? $giamuaadmin[0]->exchange_rates : 0;
                if ($giamuaadmin <= 0) $giamuaadmin = $userSuperPriceOne;

                $record->bonus = '';

                //hh cua tong
                if ($giamuaadmin > $giatong_b1)
                    $record->bonus .= ((($giamember_b2 - $giamuaadmin) > 0 ? $giamember_b2 - $giamuaadmin : 0) * $point);
                else
                    $record->bonus .= ($giamember_b2 - $giatong_b1) * $point;
                // if ($userPlayPriceOne < $userGetMoneyTongPriceOne)
                //     $record->bonus .= '0';
                // else
                //     $record->bonus .= (($userPlayPriceOne - $userGetMoneyTongPriceOne)*$point);

                //hh cua dai ly
                if ($giaag_c1 < $giamuaadmin)
                    $record->bonus .= ',' . ((($giatong_b1 - $giamuaadmin) > 0 ? $giatong_b1 - $giamuaadmin : 0) * $point);
                else
                    $record->bonus .= ',' . ($giatong_b1 - $giaag_c1) * $point;

                //hh cua dai ly
                if ($giaspag_c1 < $giamuaadmin)
                    $record->bonus .= ',' . ((($giaag_c1 - $giamuaadmin) > 0 ? $giaag_c1 - $giamuaadmin : 0) * $point);
                else
                    $record->bonus .= ',' . ($giaag_c1 - $giaspag_c1) * $point;
                // if ($userGetMoneyTongPriceOne < $userDailyPriceOne)
                //     $record->bonus .= ',0';
                // else
                //     $record->bonus .= ','.(($userGetMoneyTongPriceOne - $userDailyPriceOne)*$point);

                $record->bonus .= ',' . (($userGetMoneyTongOdds - $userPlayOdds) * $point) . ',' . (($userDailyOdds - $userGetMoneyTongOdds) * $point) . ',' . (($userSpDailyOdds - $userDailyOdds) * $point);

                //hh cua admin
                if ($userDailyPriceOne < $userSuperPriceOne)
                    $record->bonus .= ',0,0';
                else
                    $record->bonus .= ',' . (($userDailyPriceOne - $userSuperPriceOne) * $point) . ',0';

                // print_r($record);
                $record->save();
                // print_r($record);
                // Cache::tags('XoSoRecord'.$userPlay->id)->flush();
                // Cache::tags('XoSoRecord'.$userTong->id)->flush();
                // Cache::tags('XoSoRecord'.$userDaily->id)->flush();
                // Cache::tags('XoSoRecord'.$userSuper->id)->flush();

            }
        } catch (\Exception $ex) {
            Log::error('error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            return 'error PaymentLottery:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function PaymentLottery($record)
    {
        try {
            echo "PaymentLottery ";
            // $record = XoSoRecordHelpers::getRecordById($record);
            // print_r($record);

            $game_id = $record->game_id;
            $game_number = $record->bet_number;

            if ($game_id >= 31 && $game_id == 55)
                $game_id = 24;
            // if($game_id==14 || $game_id== 7||$game_id== 8|| $game_id== 12 || $game_id== 9|| $game_id== 15 || $game_id== 16 || $game_id== 17)
            {

                // $game_numberUpdated = 
                // // Cache::tags('Game_Number'.$user->id)->remember('Game_Number-'.$game_code.'-'.$bet_number.'-'.$user->id, env('CACHE_TIME', 0), function () use ($game_code,$bet_number,$user) {
                //     // return 
                //     Game_Number::where('code_type',$game_id)
                //     ->where('number',$game_number)
                //     ->whereDate('updated_at', '=', date('Y-m-d'))
                //     ->where('userid', 274)
                //     ->get();

                //A>= mem2 

                $bonustong = true;
                $bonusag = true;
                $bonussuper = true;
                $point = $record->total_bet_money / $record->exchange_rates;

                //Tính bonus cho Tổng
                $userPlay = User::where('id', $record->user_id)->first();
                $userTong = User::where('id', $userPlay->user_create)->first();

                // $userPlayPriceOne = $record->exchange_rates;
                // $userPlayOdds = $record->odds;



                $userPlayOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userPlay->id)
                    ->where('game_id', $game_id)
                    ->first();

                //gia mua member khi tong nang b2
                $giamember_b2 = $userPlayOne->exchange_rates;

                $userPlayPriceOne = $userPlayOne->exchange_rates;
                $userPlayOdds = $userPlayOne->odds;


                $userTongOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userTong->id)
                    ->where('game_id', $game_id)
                    ->first();
                $userGetMoneyTongPriceOne = $userTongOne->exchange_rates;
                $userGetMoneyTongOdds = $userTongOne->odds;


                //gia tong/ gia tong khi ag nang b1
                $giatong_b1 = $userTongOne->exchange_rates;

                //Tính bonus cho Đại lý
                $userDaily = null;
                if ($userTong->roleid == 4)
                    $userDaily = $userTong;
                else
                    $userDaily = User::where('id', $userTong->user_create)->first();

                $userDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userDaily->id)
                    ->first();
                $userDailyPriceOne = $userDailyOne->exchange_rates;
                $userDailyOdds = $userDailyOne->odds;

                //gia ag c1
                $giaag_c1 = $userDailyOne->exchange_rates;

                //Tính bonus cho super agent
                $userSpDaily = User::where('id', $userDaily->user_create)->first();

                $userSpDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSpDaily->id)
                    ->first();
                $userSpDailyPriceOne = $userSpDailyOne->exchange_rates;
                $userSpDailyOdds = $userSpDailyOne->odds;

                //gia ag c1
                $giaspag_c1 = $userSpDailyOne->exchange_rates;

                //Tính bonus cho Super
                $userSuper = User::where('id', $userSpDaily->user_create)->first();

                $userSuperOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSuper->id)
                    ->first();
                $userSuperPriceOne = $userSuperOne->exchange_rates;
                $userSuperOdds = $userSuperOne->odds;

                //gia mua admin a

                $giamuaadmin = GameHelpers::GetGame_NumberByUser($game_id, $game_number, 274);

                $giamuaadmin = count($giamuaadmin) > 0 ? $giamuaadmin[0]->exchange_rates : 0;
                if ($giamuaadmin <= 0) $giamuaadmin = $userSuperPriceOne;
                else {
                    switch ($userPlay->customer_type) {
                        case 'B':
                            $giamuaadmin += 101;
                            break;
                        case 'C':
                            $giamuaadmin += 202;
                            break;
                        case 'D':
                            $giamuaadmin += 253;
                            break;

                        default:
                            # code...
                            break;
                    }
                }


                // \Log::info('giamuaadmin '. $giamuaadmin .'---'.'giamember_b2 '. $giamember_b2 .'---'.'giatong_b1 '. $giatong_b1 .'---'.'giaag_c1 '. $giaag_c1 .'---'.'giaspag_c1 '. $giaspag_c1 .'---'.'userSuperPriceOne '. $userSuperPriceOne .'---'.$record->id);
                echo 'giamuaadmin ' . $giamuaadmin . '---' . 'giamember_b2 ' . $giamember_b2 . '---' . 'giatong_b1 ' . $giatong_b1 . '---' . 'giaag_c1 ' . $giaag_c1 . '---' . 'giaspag_c1 ' . $giaspag_c1 . '---' . 'userSuperPriceOne ' . $userSuperPriceOne . '---' . $record->id;
                // echo 'giamuaadmin '. $giamuaadmin .'---';
                // echo 'giamember_b2 '. $giamember_b2 .'---';
                // echo 'giatong_b1 '. $giatong_b1 .'---';
                // echo 'giaag_c1 '. $giaag_c1 .'---';
                // echo 'giaspag_c1 '. $giaspag_c1 .'---';
                // echo 'userSuperPriceOne '. $userSuperPriceOne .'---';
                //echo 'giamuaadmin '. $giamuaadmin .'---';
                $record->bonus = '0,0,0,0,0,0,0,0';

                $hh2 = "0,0,0";
                // giamuaadmin 708---giamember_b2 800---giatong_b1 750---giaag_c1 720---giaspag_c1 710---userSuperPriceOne 705
                if ($giamuaadmin >= $giamember_b2)
                    $hh2 = "0,0,0";
                // $record->bonus = '0,0,0,0,0,0,0,0';


                if ($giatong_b1 <= $giamuaadmin && $giamuaadmin < $giamember_b2) {
                    // Hoa hồng 2 của tổng = điểm đánh* (mem2-to2)
                    // Hoa hồng 2 của ag = điểm đánh* (to2-A)
                    // Còn lại = 0	
                    $hh2_tong = ($giamember_b2 - $giatong_b1) * $point;
                    //$hh2_ag =  ($giatong_b1 - $giamuaadmin)*$point;
                    $hh2 = $hh2_tong . ',0,0';
                    // $record->bonus = $hh2_tong.',0,0,0,0,0,0,0';
                }

                if ($giaag_c1 <= $giamuaadmin && $giamuaadmin < $giatong_b1) { //Nếu ag2< = A < to2 thì
                    // Hoa hồng 2 của tổng = điểm đánh* (mem2-to2)
                    // Hoa hồng 2 của ag = điểm đánh* (to2-A)
                    // Còn lại = 0	
                    $hh2_tong = ($giamember_b2 - $giatong_b1) * $point;
                    $hh2_ag =  ($giatong_b1 - $giamuaadmin) * $point;
                    $hh2 = $hh2_tong . ',' . $hh2_ag . ',0';
                    // $record->bonus = $hh2_tong.','. $hh2_ag . ',0,0,0,0,0,0';
                }

                // Nếu sp2<= A < ag2 thì
                if ($giaspag_c1 <= $giamuaadmin && $giamuaadmin < $giaag_c1) {
                    // Hoa hồng 2 của tổng = điểm đánh* (mem2-to2)
                    // Hoa hồng 2 của ag = điểm đánh* (to2-ag2)
                    // Hoa hồng của supperagent = điểm đánh * (ag2 – A)	
                    // Còn lại = 0
                    $hh2_tong = ($giamember_b2 - $giatong_b1) * $point;
                    $hh2_ag =  ($giatong_b1 - $giaag_c1) * $point;
                    $hh2_spag =  ($giaag_c1 - $giamuaadmin) * $point;
                    $hh2 = $hh2_tong . ',' . $hh2_ag . ',' . $hh2_spag;
                    // $record->bonus = $hh2_tong.','. $hh2_ag . ',' . $hh2_spag .',0,0,0,0,0';
                }

                // Nếu  sp1<= A < =sp2 thì
                if ($userSuperPriceOne <= $giamuaadmin && $giamuaadmin <= $giaspag_c1) {
                    // Hoa hồng 2 của tổng = điểm đánh* (mem2-to2)
                    // Hoa hồng 2 của ag = điểm đánh* (to2-ag2)
                    // Hoa hồng của supperagent = điểm đánh * (ag2 – A)	
                    // Còn lại = 0
                    $hh2_tong = ($giamember_b2 - $giatong_b1) * $point;
                    $hh2_ag =  ($giatong_b1 - $giaag_c1) * $point;
                    $hh2_spag =  ($giaag_c1 - $giaspag_c1) * $point;
                    $hh2 = $hh2_tong . ',' . $hh2_ag . ',' . $hh2_spag;
                    // $record->bonus = $hh2_tong.','. $hh2_ag . ',' . $hh2_spag .',0,0,0,0,0';
                }

                // hh1 chenh lech tra thuong

                // \Log::info('tra thuong member_b2 '. $userPlayOdds .'---'.'giatong_b1 '. $userGetMoneyTongOdds .'---'.'giaag_c1 '. $userDailyOdds .'---'.'giaspag_c1 '. $userSpDailyOdds .'---'.'userSuperPriceOne '. $userSuperPriceOne .'---'.$record->id);

                $hh1_tong = $point * ($userGetMoneyTongOdds - $userPlayOdds);
                $hh1_tong = $hh1_tong > 0 ? $hh1_tong : 0; //hh1 agent
                $hh1_ag = $point * ($userDailyOdds - $userGetMoneyTongOdds);
                $hh1_ag = $hh1_ag > 0 ? $hh1_ag : 0; //hh1 master
                $hh1_spag = $point * ($userSpDailyOdds - $userDailyOdds);
                $hh1_spag = $hh1_spag > 0 ? $hh1_spag : 0; //hh1 super

                $hh1 = $hh1_tong . "," . $hh1_ag . "," . $hh1_spag;

                $record->bonus = $hh2 . "," . $hh1 . "," . "0,0";

                // //hh cua tong
                // if ($giamuaadmin > $giatong_b1)
                //     $record->bonus .= ((($giamember_b2 - $giamuaadmin) > 0 ? $giamember_b2 - $giamuaadmin : 0)*$point);
                // else
                //     $record->bonus .= ($giamember_b2 - $giatong_b1)*$point;
                // // if ($userPlayPriceOne < $userGetMoneyTongPriceOne)
                // //     $record->bonus .= '0';
                // // else
                // //     $record->bonus .= (($userPlayPriceOne - $userGetMoneyTongPriceOne)*$point);

                // //hh cua dai ly
                // if ($giaag_c1 < $giamuaadmin)
                //     $record->bonus .= ','.((($giatong_b1 - $giamuaadmin) > 0 ? $giatong_b1 - $giamuaadmin : 0)*$point);
                // else
                //     $record->bonus .= ','.($giatong_b1 - $giaag_c1)*$point;

                //     //hh cua dai ly
                // if ($giaspag_c1 < $giamuaadmin)
                //     $record->bonus .= ','.((($giaag_c1 - $giamuaadmin) > 0 ? $giaag_c1 - $giamuaadmin : 0)*$point);
                // else
                //     $record->bonus .= ','.($giaag_c1 - $giaspag_c1)*$point;
                // // if ($userGetMoneyTongPriceOne < $userDailyPriceOne)
                // //     $record->bonus .= ',0';
                // // else
                // //     $record->bonus .= ','.(($userGetMoneyTongPriceOne - $userDailyPriceOne)*$point);

                // $record->bonus .= ','.(($userGetMoneyTongOdds - $userPlayOdds)*$point).','.(($userDailyOdds - $userGetMoneyTongOdds)*$point).','.(($userSpDailyOdds - $userDailyOdds)*$point);

                // //hh cua admin
                // if ($userDailyPriceOne < $userSuperPriceOne)
                //     $record->bonus .= ',0,0';
                // else
                //     $record->bonus .= ','.(($userDailyPriceOne - $userSuperPriceOne)*$point).',0';

                // // print_r($record);
                $record->save();
                // print_r($record);
                // Cache::tags('XoSoRecord'.$userPlay->id)->flush();
                // Cache::tags('XoSoRecord'.$userTong->id)->flush();
                // Cache::tags('XoSoRecord'.$userDaily->id)->flush();
                // Cache::tags('XoSoRecord'.$userSuper->id)->flush();

            }
        } catch (\Exception $ex) {
            Log::error('error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            echo 'error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine();
            return 'error PaymentLottery:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function PaymentLotteryMinigame($record)
    {
        try {
            echo "PaymentLotteryMinigame ";
            // $record = XoSoRecordHelpers::getRecordById($record);
            // print_r($record);

            $game_id = $record->gametype; {
                $bonustong = true;
                $bonusag = true;
                $bonussuper = true;

                //Tính bonus cho Tổng
                $userPlay = User::where('name', $record->username)->first();
                $userTong = User::where('id', $userPlay->user_create)->first();

                $userPlayOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userPlay->id)
                    ->where('game_id', $game_id)
                    ->first();
                $hhMember = $userPlayOne->odds;

                $userTongOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userTong->id)
                    ->where('game_id', $game_id)
                    ->first();
                $hhAgent = $userTongOne->odds;

                //Tính bonus cho Đại lý
                $userDaily = null;
                if ($userTong->roleid == 4)
                    $userDaily = $userTong;
                else
                    $userDaily = User::where('id', $userTong->user_create)->first();

                $userDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userDaily->id)
                    ->first();

                $hhMaster = $userDailyOne->odds;

                //Tính bonus cho super agent
                $userSpDaily = User::where('id', $userDaily->user_create)->first();

                $userSpDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSpDaily->id)
                    ->first();

                $hhSuper = $userSpDailyOne->odds;
                //Tính bonus cho Super
                $userSuper = User::where('id', $userSpDaily->user_create)->first();

                $userSuperOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSuper->id)
                    ->first();
                $hhAdmin = $userSuperOne->odds;

                $betamount = abs($record->payoff);
                $hh1Super = ($hhSuper - $hhMaster) / 100 * $betamount / 100;
                if ($userTong->roleid == 4) {
                    $hh1Master = ($hhMaster - $hhMember) / 100 * $betamount / 100;
                    $hh1Agent = 0;
                } else {
                    $hh1Master = ($hhMaster - $hhAgent) / 100 * $betamount / 100;
                    $hh1Agent = ($hhAgent - $hhMember) / 100 * $betamount / 100;
                }

                $hh1Memberr = $hhMember / 100 * $betamount / 100;

                echo "hh: " . $hhSuper . " " . $hhMaster . " " . $hhAgent . " " . $hhMember . PHP_EOL;
                echo "hh: " . $hh1Super . " " . $hh1Master . " " . $hh1Agent . " " . $hh1Memberr . " " . $betamount;

                //gia mua admin a
                $record->bonus = '0,0,0,' . (int)$hh1Agent . ',' . (int)$hh1Master . ',' . (int)$hh1Super . ',0,0,0,' . (int)$hh1Memberr;

                $userPlay->remain += (int)$hh1Memberr;
                $userPlay->save();
                $record->com = (int)$hh1Memberr;
                $record->save();
            }
        } catch (\Exception $ex) {
            Log::error('PaymentLotteryMinigame error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            echo 'PaymentLotteryMinigame error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine();
            return 'error PaymentLotteryMinigame:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function PaymentLottery7zball($record)
    {
        try {
            echo "PaymentLottery7zball ";
            // $record = XoSoRecordHelpers::getRecordById($record);
            // print_r($record);

            $game_id = $record->gametype; {
                $bonustong = true;
                $bonusag = true;
                $bonussuper = true;

                //Tính bonus cho Tổng
                $userPlay = User::where('name', $record->username)->first();
                $userTong = User::where('id', $userPlay->user_create)->first();

                $userPlayOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userPlay->id)
                    ->where('game_id', $game_id)
                    ->first();
                $hhMember = $userPlayOne->odds;

                $userTongOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('created_user', $userTong->id)
                    ->where('game_id', $game_id)
                    ->first();
                $hhAgent = $userTongOne->odds;

                //Tính bonus cho Đại lý
                $userDaily = null;
                if ($userTong->roleid == 4)
                    $userDaily = $userTong;
                else
                    $userDaily = User::where('id', $userTong->user_create)->first();

                $userDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userDaily->id)
                    ->first();

                $hhMaster = $userDailyOne->odds;

                //Tính bonus cho super agent
                $userSpDaily = User::where('id', $userDaily->user_create)->first();

                $userSpDailyOne = CustomerType_Game_Original::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSpDaily->id)
                    ->first();

                $hhSuper = $userSpDailyOne->odds;
                //Tính bonus cho Super
                $userSuper = User::where('id', $userSpDaily->user_create)->first();

                $userSuperOne = CustomerType_Game::where('code_type', $userPlay->customer_type)
                    ->where('game_id', $game_id)
                    ->where('created_user', $userSuper->id)
                    ->first();
                $hhAdmin = $userSuperOne->odds;

                $betamount = abs($record->payoff);
                $hh1Super = ($hhSuper - $hhMaster) / 100 * $betamount / 100;
                if ($userTong->roleid == 4) {
                    $hh1Master = ($hhMaster - $hhMember) / 100 * $betamount / 100;
                    $hh1Agent = 0;
                } else {
                    $hh1Master = ($hhMaster - $hhAgent) / 100 * $betamount / 100;
                    $hh1Agent = ($hhAgent - $hhMember) / 100 * $betamount / 100;
                }

                $hh1Memberr = $hhMember / 100 * $betamount / 100;

                echo "hh: " . $hhSuper . " " . $hhMaster . " " . $hhAgent . " " . $hhMember . PHP_EOL;
                echo "hh: " . $hh1Super . " " . $hh1Master . " " . $hh1Agent . " " . $hh1Memberr . " " . $betamount;

                $money_last_hh = 0;
                if (isset($record->bonus)) {
                    try {
                        $hhMem = explode(",", $record->bonus);
                        $money_last_hh = end($hhMem);
                    } catch (Exception $ex) {
                    }
                }
                //gia mua admin a
                $record->bonus = '0,0,0,' . (int)$hh1Agent . ',' . (int)$hh1Master . ',' . (int)$hh1Super . ',0,0,0,' . (int)$hh1Memberr;

                $userPlay->remain = ($userPlay->remain - $money_last_hh + (int)$hh1Memberr);
                $userPlay->save();

                $record->com = (int)$hh1Memberr;
                $record->save();
            }
        } catch (\Exception $ex) {
            Log::error('PaymentLottery7zball error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine());
            echo 'PaymentLottery7zball error ' . $ex->getFile() . '-' . $ex->getMessage() . '-' . $ex->getLine();
            return 'error PaymentLottery7zball:' . $ex->getFile() . '-' . $ex->getLine() . '-' . $ex->getMessage();
        }
    }

    public static function UpdateWinLose($win, $recordid, $game_id)
    {
        $record = XoSoRecord::where('id', $recordid)->first();
        $t = "";
        $count = count($win);
        $winnumber = array_unique($win);
        try {
            foreach ($winnumber as $n) {
                $t .= $n . ",";
            }
        } catch (\Exception $e) {
            // Log::info('exception was @ ' . count($winnumber));
            // $t=$winnumber[0];
        }
        $record->win_number = rtrim($t, ",");
        // if($game_id==14 || $game_id==18 || $game_id== 7||$game_id== 8|| $game_id== 12 || $game_id== 9|| $game_id== 15 || $game_id== 16 || $game_id== 17
        // || $game_id== 19 || $game_id== 20 || $game_id== 21 || $game_id== 22 || $game_id== 23 || $game_id== 24 || $game_id== 25 || $game_id== 26 || $game_id== 27
        // || $game_id== 28 || $game_id >= 100)
        {
            $point = $record->total_bet_money / $record->exchange_rates;

            if ($game_id >= 721 && $game_id <= 739) {
                $record->total_win_money += $point * $record->odds * $count - 1000 * $point + $record->total_bet_money;
            } else
                $record->total_win_money += $point * $record->odds * $count;

            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0) {
                $user->remain += $record->total_win_money;
                if (
                    $game_id == 15 || $game_id == 16
                    || $game_id == 316 || $game_id == 416 || $game_id == 516 || $game_id == 616
                    || $game_id == 115 || $game_id == 116
                    || $game_id == 15 || $game_id == 315 || $game_id == 415 || $game_id == 515 || $game_id == 615
                ) {
                    $user->remain += $record->total_bet_money;
                }
            }
            $user->save();

            $record->save();
        }
        return $record->total_win_money;
    }

    public static function fact($x)
    {
        if ($x <= 0) {
            return 1;
        }
        return $x * XoSoRecordHelpers::fact($x - 1);
    }

    public static function UpdateWinLoseXien($win, $recordid, $game_id, $winnumber = "")
    {
        $record = XoSoRecord::where('id', $recordid)->first();
        $t = "";
        $count = $win;
        // $winnumber = array_unique($win);
        // foreach ($winnumber as $n)
        // {
        //     $t .= $n.",";
        // }
        $record->win_number = $winnumber;
        if ($game_id == 19 || $game_id == 119) {
            $countbetnumber = count(explode(',', $record->bet_number));
            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(4) / XoSoRecordHelpers::fact($countbetnumber - 4);
            $point = $record->total_bet_money / $record->exchange_rates / $Ank;
            $record->total_win_money = $point * $record->odds * $count;
            //$point*$record->odds*$count;
            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0)
                $user->remain += $record->total_win_money;
            $user->save();
            $record->save();
        }
        if ($game_id == 20 || $game_id == 120) {
            $countbetnumber = count(explode(',', $record->bet_number));
            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(8) / XoSoRecordHelpers::fact($countbetnumber - 8);
            $point = $record->total_bet_money / $record->exchange_rates / $Ank;
            $record->total_win_money = $point * $record->odds * $count;
            //$point*$record->odds*$count;
            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0)
                $user->remain += $record->total_win_money;
            $user->save();
            $record->save();
        }
        if ($game_id == 21 || $game_id == 121) {
            $countbetnumber = count(explode(',', $record->bet_number));
            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(10) / XoSoRecordHelpers::fact($countbetnumber - 10);
            $point = $record->total_bet_money / $record->exchange_rates / $Ank;
            $record->total_win_money = $point * $record->odds * $count;
            //$point*$record->odds*$count;
            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0)
                $user->remain += $record->total_win_money;
            $user->save();
            $record->save();
        }
        if ($game_id == 11 || $game_id == 111 || $game_id == 311 || $game_id == 411 || $game_id == 511 || $game_id == 611 || $game_id == 711) {
            $countbetnumber = count(explode(',', $record->bet_number));
            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(4) / XoSoRecordHelpers::fact($countbetnumber - 4);
            $point = $record->total_bet_money / $record->exchange_rates / $Ank;
            $record->total_win_money = $point * $record->odds * $count;
            //$point*$record->odds*$count;
            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0)
                $user->remain += $record->total_win_money;
            $user->save();
            $record->save();
        }
        if ($game_id == 10 || $game_id == 310 || $game_id == 410 || $game_id == 510 || $game_id == 610 || $game_id == 710 || $game_id == 110) {
            $countbetnumber = count(explode(',', $record->bet_number));
            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(3) / XoSoRecordHelpers::fact($countbetnumber - 3);
            $point = $record->total_bet_money / $record->exchange_rates / $Ank;
            $record->total_win_money = $point * $record->odds * $count;
            //$point*$record->odds*$count;
            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0)
                $user->remain += $record->total_win_money;
            $user->save();
            $record->save();
        }
        if ($game_id == 29 || $game_id == 9 || $game_id == 309 || $game_id == 409 || $game_id == 509 || $game_id == 609 || $game_id == 709 || $game_id == 109) {
            $countbetnumber = count(explode(',', $record->bet_number));
            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
            $point = $record->total_bet_money / $record->exchange_rates / $Ank;
            $record->total_win_money = $point * $record->odds * $count;
            //$point*$record->odds*$count;
            $user = User::where('id', $record->user_id)->first();
            if ($record->total_win_money > 0)
                $user->remain += $record->total_win_money;
            $user->save();
            $record->save();
        }
        return $record->total_win_money;
    }

    public static function clearBet($game_code)
    {
        $game = GameHelpers::GetGameByCode($game_code);
        $game->totalbet = null;
        $game->locknumber = null;
        $game->locknumberred = null;
        $game->locknumberauto = null;
        $game->locksuper = null;
        $game->totalbetnumber = null;
        $game->totalbetnumber1 = null;
        $game->latestID = 0;

        $datetime = new DateTime('yesterday');
        $yesterday = $datetime->format('Y-m-d');

        $game->lastestBet = $yesterday;
        $game->save();
        $game_code = $game->game_code;
        // echo $game_code .' '. Cache::get('TotalBetTodayByNumberThau-'.$game_code) .' ';
        Cache::put('TotalBetTodayByNumberThau-' . $game_code, 0, env('CACHE_TIME', 24 * 60));
        Cache::put('TotalBetTodayByGameOrg-' . $game_code, 0, env('CACHE_TIME', 24 * 60));
        for ($i = 0; $i < 10; $i++)
            for ($j = 0; $j < 10; $j++) {
                $bet_number = $i . $j;
                if ($game_code >= 721 && $game_code <= 739) {
                    if ($bet_number != '00') break;
                }
                Cache::put('TotalBetTodayByNumberThau-' . $game_code . '-' . $bet_number, [0, 0], env('CACHE_TIME', 24 * 60));
            }
    }

    public static function DeleteLoto($id)
    {
        try {
            $record = XoSoRecord::where('id', $id)->where('user_id', Auth::user()->id)->where('isDelete', 0)->where('total_win_money', 0)->first();
            //
            if (isset($record)) {
                $now = date('Y-m-d');
                $game_ = GameHelpers::GetGameByCode($record->game_id);
                $isSubMoney = false;
                $username = User::where('id', $record->user_id)->first()->name;

                XoSoRecordHelpers::saveFileHistory($record->game_id, $username, $record->id, $record->bet_number, $record->total_bet_money, $record->xien_id, true);
                // echo strtotime($record->created_at)+(60*5);
                // echo " .  ";
                // echo strtotime(Carbon::now());
                if (
                    $record->game_id < 100
                    // && (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())
                    //     || strtotime($record->created_at) + (60 * 5) > strtotime($game_->close)
                    // )

                ) {
                    if (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())) {
                        $isSubMoney = true;
                    }
                    if (strtotime($record->created_at) + (60 * 5) > strtotime($game_->close)  || date("H") >= 18)
                        return "failed";
                }

                if ($record->game_id < 100 && intval(date('H')) >= 18)
                    return "failed";
                // if (count($record) > 0)
                {
                    if ($isSubMoney) {
                        $ank = 1;
                        if (
                            $record->game_id == 29 || $record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11 || $record->game_id == 19
                            || $record->game_id == 309 || $record->game_id == 310 || $record->game_id == 311
                            || $record->game_id == 409 || $record->game_id == 410 || $record->game_id == 411
                            || $record->game_id == 509 || $record->game_id == 510 || $record->game_id == 511
                            || $record->game_id == 609 || $record->game_id == 610 || $record->game_id == 611
                            || $record->game_id == 709 || $record->game_id == 710 || $record->game_id == 711
                        ) {
                            $arrbetnumber = explode(',', $record->bet_number);
                            $countbetnumber = count($arrbetnumber);
                            switch ($record->game_id) {
                                case 29:
                                    # code...
                                    $factnumber = 2;
                                    break;
                                case 9:
                                case 309:
                                case 409:
                                case 509:
                                case 609:
                                case 709:
                                    # code...
                                    $factnumber = 2;
                                    break;
                                case 10:
                                case 310:
                                case 410:
                                case 510:
                                case 610:
                                case 710:
                                    # code...
                                    $factnumber = 3;
                                    break;
                                case 11:
                                case 19:
                                case 311:
                                case 411:
                                case 511:
                                case 611:
                                case 711:
                                    # code...
                                    $factnumber = 4;
                                    break;
                                default:
                                    # code...
                                    $factnumber = 1;
                                    break;
                            }
                            $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
                        }
                        // $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                        $y3 = 0;
                        if ($record->game_id >= 31 and $record->game_id <= 55)
                            $y3 = GameHelpers::GetGameByGameCode(24)->y3;
                        else {
                            switch ($record->game_id) {
                                case 17:
                                case 29:
                                case 56:
                                case 15:
                                    $y3 = GameHelpers::GetGameByGameCode(14)->y3;
                                    break;
                                case 19:
                                case 20:
                                case 21:
                                    $y3 = GameHelpers::GetGameByGameCode(9)->y3;
                                    break;
                                default:
                                    $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                                    break;
                            }
                        }

                        $record->isDelete = 0;
                        $record->total_win_money = 0 - $record->total_bet_money / $record->exchange_rates * $y3; ///$ank
                        $record->ipaddr = date('H-i-s') . ": Hủy cược";
                        $record->save();

                        $user = UserHelpers::GetUserById(Auth::user()->id); //
                        $user->remain += ($record->total_bet_money - $record->total_win_money);
                        $user->consumer -= ($record->total_bet_money - $record->total_win_money);
                        $user->save();
                    } else {
                        $record->isDelete = 1;
                        $record->save();

                        $user = UserHelpers::GetUserById(Auth::user()->id); //
                        $user->remain += $record->total_bet_money;
                        $user->consumer -= $record->total_bet_money;
                        $user->save();
                    }
                    // Cache::tags('XoSoRecord'.Auth::user()->id)->flush();
                }
                static::clearBet($record->game_id);
                if ($record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11) {
                    $arrbetnumber = explode(',', $record->bet_number);
                    $countbetnumber = count($arrbetnumber);
                    switch ($record->game_id) {
                        case 29:
                            # code...
                            $factnumber = 2;
                            break;
                        case 9:
                        case 309:
                        case 409:
                        case 509:
                        case 609:
                        case 709:
                            # code...
                            $factnumber = 2;
                            break;
                        case 10:
                        case 310:
                        case 410:
                        case 510:
                        case 610:
                        case 710:
                            # code...
                            $factnumber = 3;
                            break;
                        case 11:
                        case 311:
                        case 411:
                        case 511:
                        case 611:
                        case 711:
                            # code...
                            $factnumber = 4;
                            break;
                        default:
                            # code...
                            $factnumber = 1;
                            break;
                    }
                    $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);

                    foreach ($arrbetnumber as $number) {

                        $game_code_temp = $record->game_code;
                        if ($game_code_temp == 9 || $game_code_temp == 309 || $game_code_temp == 409 || $game_code_temp == 509 || $game_code_temp == 609 || $game_code_temp == 709 || $game_code_temp == 29)
                            $game_code_temp = 9;
                        elseif ($game_code_temp == 7 || $game_code_temp == 18)
                            $game_code_temp = 7;

                        // echo Cache::get('TotalPointBetTodayByNumberUser-'.$user->id.'-'.$record->game_id.'-'.$record->bet_number.'-'.$now,0);
                        $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $number . '-' . $now, 0) - intval($record->total_bet_money) / $record->exchange_rates / $ank;
                        // echo '  '.$number.'-'.$totalBettodayOne.'  ';
                        Cache::put('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $number . '-' . $now, $totalBettodayOne, env('CACHE_TIME', 24 * 60));
                    }
                } else {
                    // echo Cache::get('TotalPointBetTodayByNumberUser-'.$user->id.'-'.$record->game_id.'-'.$record->bet_number.'-'.$now,0);
                    $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $record->bet_number . '-' . $now, 0) - $record->total_bet_money / $record->exchange_rates;
                    // echo $totalBettodayOne;
                    Cache::put('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $record->bet_number . '-' . $now, $totalBettodayOne, env('CACHE_TIME', 24 * 60));
                }

                HistoryHelpers::InsertHistory("Miền Bắc", "Huỷ " . $game_->name . ' ' . $record->bet_number, $record->user_id, $record->total_bet_money);
                return "ok";
            } else {
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . $ex->getLine();
        }

        return "failed";
    }

    public static function DeleteLotoByUser($id, $user,$saveHistory = true)
    {
        try {
            $record = XoSoRecord::where('id', $id)->where('user_id', $user->id)->where('isDelete', 0)->where('total_win_money', 0)->first();
            //
            if (isset($record)) {
                $now = date('Y-m-d');
                $game_ = GameHelpers::GetGameByCode($record->game_id);
                $isSubMoney = false;
                $username = User::where('id', $record->user_id)->first()->name;

                XoSoRecordHelpers::saveFileHistory($record->game_id, $username, $record->id, $record->bet_number, $record->total_bet_money, $record->xien_id, true);
                // echo strtotime($record->created_at)+(60*5);
                // echo " .  ";
                // echo strtotime(Carbon::now());
                if (
                    $record->game_id < 100
                    // && (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())
                    //     || strtotime($record->created_at) + (60 * 5) > strtotime($game_->close)
                    // )

                ) {
                    if (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())) {
                        $isSubMoney = true;
                    }
                    if (strtotime($record->created_at) + (60 * 5) > strtotime($game_->close))
                        return "failed";
                }

                if ($record->game_id < 100 && intval(date('H')) >= 18 && intval(date('i') >= 5))
                    return "failed";
                // if (count($record) > 0)
                {
                    if ($isSubMoney) {
                        $ank = 1;
                        if (
                            $record->game_id == 29 || $record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11
                            || $record->game_id == 309 || $record->game_id == 310 || $record->game_id == 311
                            || $record->game_id == 409 || $record->game_id == 410 || $record->game_id == 411
                            || $record->game_id == 509 || $record->game_id == 510 || $record->game_id == 511
                            || $record->game_id == 609 || $record->game_id == 610 || $record->game_id == 611
                            || $record->game_id == 709 || $record->game_id == 710 || $record->game_id == 711
                        ) {
                            $arrbetnumber = explode(',', $record->bet_number);
                            $countbetnumber = count($arrbetnumber);
                            switch ($record->game_id) {
                                case 29:
                                    # code...
                                    $factnumber = 2;
                                    break;
                                case 9:
                                case 309:
                                case 409:
                                case 509:
                                case 609:
                                case 709:
                                    # code...
                                    $factnumber = 2;
                                    break;
                                case 10:
                                case 310:
                                case 410:
                                case 510:
                                case 610:
                                case 710:
                                    # code...
                                    $factnumber = 3;
                                    break;
                                case 11:
                                case 311:
                                case 411:
                                case 511:
                                case 611:
                                case 711:
                                    # code...
                                    $factnumber = 4;
                                    break;
                                default:
                                    # code...
                                    $factnumber = 1;
                                    break;
                            }
                            $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
                        }
                        $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                        $record->isDelete = 0;
                        $record->total_win_money = 0 - $record->total_bet_money / $record->exchange_rates / $ank * $y3;
                        $record->ipaddr = date('H-i-s') . ": Hủy cược";
                        $record->save();

                        $user = UserHelpers::GetUserById($user->id); //
                        $user->remain += ($record->total_bet_money - $record->total_win_money);
                        $user->consumer -= ($record->total_bet_money - $record->total_win_money);
                        $user->save();
                    } else {
                        $record->isDelete = 1;
                        $record->save();

                        $user = UserHelpers::GetUserById($user->id); //
                        $user->remain += $record->total_bet_money;
                        $user->consumer -= $record->total_bet_money;
                        $user->save();
                    }
                    // Cache::tags('XoSoRecord'.Auth::user()->id)->flush();
                }
                static::clearBet($record->game_id);
                if ($record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11) {
                    $arrbetnumber = explode(',', $record->bet_number);
                    $countbetnumber = count($arrbetnumber);
                    switch ($record->game_id) {
                        case 29:
                            # code...
                            $factnumber = 2;
                            break;
                        case 9:
                        case 309:
                        case 409:
                        case 509:
                        case 609:
                        case 709:
                            # code...
                            $factnumber = 2;
                            break;
                        case 10:
                        case 310:
                        case 410:
                        case 510:
                        case 610:
                        case 710:
                            # code...
                            $factnumber = 3;
                            break;
                        case 11:
                        case 311:
                        case 411:
                        case 511:
                        case 611:
                        case 711:
                            # code...
                            $factnumber = 4;
                            break;
                        default:
                            # code...
                            $factnumber = 1;
                            break;
                    }
                    $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);

                    foreach ($arrbetnumber as $number) {

                        $game_code_temp = $record->game_code;
                        if ($game_code_temp == 9 || $game_code_temp == 309 || $game_code_temp == 409 || $game_code_temp == 509 || $game_code_temp == 609 || $game_code_temp == 709 || $game_code_temp == 29)
                            $game_code_temp = 9;
                        elseif ($game_code_temp == 7 || $game_code_temp == 18)
                            $game_code_temp = 7;

                        // echo Cache::get('TotalPointBetTodayByNumberUser-'.$user->id.'-'.$record->game_id.'-'.$record->bet_number.'-'.$now,0);
                        $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $number . '-' . $now, 0) - intval($record->total_bet_money) / $record->exchange_rates / $ank;
                        // echo '  '.$number.'-'.$totalBettodayOne.'  ';
                        Cache::put('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $number . '-' . $now, $totalBettodayOne, env('CACHE_TIME', 24 * 60));
                    }
                } else {
                    // echo Cache::get('TotalPointBetTodayByNumberUser-'.$user->id.'-'.$record->game_id.'-'.$record->bet_number.'-'.$now,0);
                    $totalBettodayOne = Cache::get('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $record->bet_number . '-' . $now, 0) - $record->total_bet_money / $record->exchange_rates;
                    // echo $totalBettodayOne;
                    Cache::put('TotalPointBetTodayByNumberUser-' . $user->id . '-' . $record->game_id . '-' . $record->bet_number . '-' . $now, $totalBettodayOne, env('CACHE_TIME', 24 * 60));
                }

                if ($saveHistory) HistoryHelpers::InsertHistory("Miền Bắc", "Huỷ " . $game_->name . ' ' . $record->bet_number, $record->user_id, $record->total_bet_money,null,"",$saveHistory);
                return "ok";
            } else {
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . $ex->getLine();
        }

        return "failed";
    }

    public static function EstimateBetCancel($id, $user)
    {
        $moneyCancel = 0;
        try {
            $record = XoSoRecord::where('id', $id)->where('user_id', $user->id)->where('isDelete', 0)->where('total_win_money', 0)->first();
            //
            if (isset($record)) {
                $game_ = GameHelpers::GetGameByCode($record->game_id);
                $isSubMoney = false;
                if (
                    $record->game_id < 100
                ) {
                    if (strtotime($record->created_at) + (60 * 5) < strtotime(Carbon::now())) {
                        $isSubMoney = true;
                    }
                    if (strtotime($record->created_at) + (60 * 5) > strtotime($game_->close))
                        return [0,$moneyCancel];
                }
                if ($record->game_id < 100 && intval(date('H')) >= 18 && intval(date('i') >= 5))
                    return [0,$moneyCancel];
                {
                    if ($isSubMoney) {
                        $ank = 1;
                        if (
                            $record->game_id == 29 || $record->game_id == 9 || $record->game_id == 10 || $record->game_id == 11
                            || $record->game_id == 309 || $record->game_id == 310 || $record->game_id == 311
                            || $record->game_id == 409 || $record->game_id == 410 || $record->game_id == 411
                            || $record->game_id == 509 || $record->game_id == 510 || $record->game_id == 511
                            || $record->game_id == 609 || $record->game_id == 610 || $record->game_id == 611
                            || $record->game_id == 709 || $record->game_id == 710 || $record->game_id == 711
                        ) {
                            $arrbetnumber = explode(',', $record->bet_number);
                            $countbetnumber = count($arrbetnumber);
                            switch ($record->game_id) {
                                case 29:
                                    # code...
                                    $factnumber = 2;
                                    break;
                                case 9:
                                case 309:
                                case 409:
                                case 509:
                                case 609:
                                case 709:
                                    # code...
                                    $factnumber = 2;
                                    break;
                                case 10:
                                case 310:
                                case 410:
                                case 510:
                                case 610:
                                case 710:
                                    # code...
                                    $factnumber = 3;
                                    break;
                                case 11:
                                case 311:
                                case 411:
                                case 511:
                                case 611:
                                case 711:
                                    # code...
                                    $factnumber = 4;
                                    break;
                                default:
                                    # code...
                                    $factnumber = 1;
                                    break;
                            }
                            $ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($factnumber) / XoSoRecordHelpers::fact($countbetnumber - $factnumber);
                        }
                        $y3 = GameHelpers::GetGameByGameCode($record->game_id)->y3;
                        $moneyCancel = $record->total_bet_money / $record->exchange_rates / $ank * $y3;
                    } else {
                    }
                }
            } else {
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . $ex->getLine();
        }

        return [1,$moneyCancel];
    }

    public static function ReportKhach($user, $stDate, $endDate, $type = "all")
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $com = 0;
        $recordUser = Cache::remember('1XoSoRecordHelpers-getRecordKhachByDate' . $user->id . '-' . $stDate . '-' . $endDate . '-' . $type, env('CACHE_TIME_SHORT', 0), function () use ($user, $stDate, $endDate, $type) {
            return XoSoRecordHelpers::getRecordKhachByDate($user, $stDate, $endDate, $type);
        });
        // $recordUser = XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type);
        foreach ($recordUser as $record) {
            # code...
            if ($record->exchange_rates > 0)
                // $donhang+=$record->total_bet_money/$record->exchange_rates;
                $donhang++;

            if ($record->locationslug == 60)
                $tiencuoc += $record->total_bet_money * 1000;
            else
                $tiencuoc += $record->total_bet_money;

            // $tiencuoc += $record->total_bet_money;
            //fix tra thuong
            if ($record->total_win_money > 0 && $record->game_id < 3000) {
                if (
                    $record->game_id == 15 || $record->game_id == 16 ||
                    $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                ) {
                    $winlose += $record->total_win_money;
                } else
                    $winlose += ($record->total_win_money - $record->total_bet_money);
            } else {
                if ($record->locationslug == 60)
                    $winlose += $record->total_win_money * 1000;
                else
                    $winlose += $record->total_win_money;
            }
            // $winlose += $record->total_win_money;
            $com += $record->game_id > 3000 ? $record->com : 0;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) > 2)
                    $bonus3 += intval($arrbonus[2]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus3 += intval($arrbonus[2])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus4 += intval($arrbonus[3])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                    $bonus4 += intval($arrbonus[3]);
                }

                if (count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus5 += intval($arrbonus[4]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus6 += intval($arrbonus[5]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                    $bonus5 += intval($arrbonus[4]);
                    $bonus6 += intval($arrbonus[5]);
                }

                if (count($arrbonus) >= 8 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus7 += intval($arrbonus[6]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus8 += intval($arrbonus[7]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6, $bonus7, $bonus8, $com);
    }

    public static function ReportKhachv21($user, $stDate, $endDate, $type = "all")
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $com = 0;
        // $recordUser = Cache::remember('1XoSoRecordHelpers-getRecordKhachByDate'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
        //                 return XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type);
        //             });
        $recordUser = XoSoRecordHelpers::getRecordKhachByDatev2($user, $stDate, $endDate, $type);
        foreach ($recordUser as $record) {
            # code...
            if ($record->exchange_rates > 0)
                // $donhang+=$record->total_bet_money/$record->exchange_rates;
                $donhang++;

            if ($record->locationslug == 60)
                $tiencuoc += $record->total_bet_money * 1000;
            else
                $tiencuoc += $record->total_bet_money;

            // $tiencuoc += $record->total_bet_money;
            //fix tra thuong
            if ($record->total_win_money > 0 && $record->game_id < 3000) {
                if (
                    $record->game_id == 15 || $record->game_id == 16 ||
                    $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                ) {
                    $winlose += $record->total_win_money;
                } else
                    $winlose += ($record->total_win_money - $record->total_bet_money);
            } else {
                if ($record->locationslug == 60)
                    $winlose += $record->total_win_money * 1000;
                else
                    $winlose += $record->total_win_money;
            }
            // $winlose += $record->total_win_money;
            $com += $record->game_id > 3000 ? $record->com : 0;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) > 2)
                    $bonus3 += intval($arrbonus[2]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus3 += intval($arrbonus[2])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus4 += intval($arrbonus[3])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                    $bonus4 += intval($arrbonus[3]);
                }

                if (count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus5 += intval($arrbonus[4]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus6 += intval($arrbonus[5]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                    $bonus5 += intval($arrbonus[4]);
                    $bonus6 += intval($arrbonus[5]);
                }

                if (count($arrbonus) >= 8 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus7 += intval($arrbonus[6]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus8 += intval($arrbonus[7]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6, $bonus7, $bonus8, $com);
    }

    public static function ReportKhachv2($user, $stDate, $endDate, $type = "all")
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $bonus9 = 0;
        $bonus10 = 0;
        $com = 0;
        // $recordUser = Cache::remember('1XoSoRecordHelpers-getRecordKhachByDate'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate,$type) {
        //                 return XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type);
        //             });
        $recordUser = XoSoRecordHelpers::getRecordKhachByDatev2($user, $stDate, $endDate, $type);
        foreach ($recordUser as $record) {
            # code...
            // if ($record->exchange_rates > 0 && $record->game_id < 1000)
            // $donhang+=$record->total_bet_money/$record->exchange_rates;
            $donhang++;

            if ($record->locationslug == 60)
                $tiencuoc += $record->total_bet_money * 1000;
            else
                $tiencuoc += $record->total_bet_money;

            // $tiencuoc += $record->total_bet_money;
            //fix tra thuong
            if ($record->total_win_money > 0 && $record->game_id < 3000) {
                if (
                    $record->game_id == 15 || $record->game_id == 16 ||
                    $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                ) {
                    $winlose += $record->total_win_money;
                } else
                    $winlose += ($record->total_win_money - $record->total_bet_money);
            } else {
                if ($record->locationslug == 60)
                    $winlose += $record->total_win_money * 1000;
                else
                    $winlose += $record->total_win_money;
            }
            // $winlose += $record->total_win_money;
            $com += $record->game_id > 3000 ? $record->com : 0;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                // echo $record->bonus . "<br> ".  PHP_EOL;
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) > 2)
                    $bonus3 += intval($arrbonus[2]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus3 += intval($arrbonus[2])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus4 += intval($arrbonus[3])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                    $bonus4 += intval($arrbonus[3]);
                }

                if ((count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0)) {
                    // $bonus5 += intval($arrbonus[4]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus6 += intval($arrbonus[5]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                    $bonus5 += intval($arrbonus[4]);
                    $bonus6 += intval($arrbonus[5]);
                }

                if (count($arrbonus) >= 8 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus7 += intval($arrbonus[6]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus8 += intval($arrbonus[7]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
                if (count($arrbonus) >= 10 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus9 += intval($arrbonus[8]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus10 += intval($arrbonus[9]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2,   $bonus3,  $bonus4,  $bonus5,   $bonus6,  $bonus7,  $bonus8,  $bonus9, $bonus10, $com);
        //                         hh1-agent hh1-master hh1-super hh2-agent hh2-master hh2-super hh1-admin hh2-admin 0        hh1-member
    } //                                                 3       4           5       6           7           8       9           10        11    12        

    public static function ReportTong($user, $stDate, $endDate, $type = "all")
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);

        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $com = 0;

        foreach ($userChild as $userC) {
            $userReport = XoSoRecordHelpers::ReportKhach($userC, $stDate, $endDate, $type);
            $donhang += $userReport[0];
            // $tiencuoc+=$userReport[1];
            // $winlose+=$userReport[2];
            $tiencuoc += ($userReport[1] - $userReport[3] - $userReport[6]); //
            $winlose += ($userReport[2] + $userReport[3] + $userReport[6]); //+$tongReport[5]);
            $bonus1 += $userReport[3];
            $bonus2 += $userReport[4];
            $bonus3 += $userReport[5];
            $bonus4 += $userReport[6];
            $bonus5 += $userReport[7];
            $bonus6 += $userReport[8];
            $bonus7 += $userReport[9];
            $bonus8 += $userReport[10];
            $com += $userReport[11];
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6, $bonus7, $bonus8, $com);
    }

    public static function ReportSpAg($user, $stDate, $endDate, $type = "all")
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $com = 0;
        foreach ($userChild as $userC) {
            $tongReport = XoSoRecordHelpers::ReportAg($userC, $stDate, $endDate, $type);
            $donhang += $tongReport[0];
            $tiencuoc += ($tongReport[1] - $tongReport[5] - $tongReport[8]); //-$tongReport[5]
            $winlose += ($tongReport[2] + $tongReport[5] + $tongReport[8]); //+$tongReport[5]);
            // $bonus1 = ;
            $bonus1 += $tongReport[3];
            $bonus2 += $tongReport[4];
            $bonus3 += $tongReport[5];
            $bonus4 += $tongReport[6];
            $bonus5 += $tongReport[7];
            $bonus6 += $tongReport[8];
            $bonus7 += $tongReport[9];
            $bonus8 += $tongReport[10];
            $com += $tongReport[11];
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6, $bonus7, $bonus8, $com);
    }

    public static function ReportAg($user, $stDate, $endDate, $type = "all")
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $com = 0;
        foreach ($userChild as $userC) {
            if ($userC->roleid == 5) {
                $tongReport = XoSoRecordHelpers::ReportTong($userC, $stDate, $endDate, $type);
                $donhang += $tongReport[0];
                $tiencuoc += ($tongReport[1] - $tongReport[4] - $tongReport[7]); //-$tongReport[5]
                $winlose += ($tongReport[2] + $tongReport[4] + $tongReport[7]); //+$tongReport[5]);
                // $bonus1 = ;
                $bonus1 += $tongReport[3];
                $bonus2 += $tongReport[4];
                $bonus3 += $tongReport[5];
                $bonus4 += $tongReport[6];
                $bonus5 += $tongReport[7];
                $bonus6 += $tongReport[8];
                $bonus7 += $tongReport[9];
                $bonus8 += $tongReport[10];
                $com += $tongReport[11];
            }
            if ($userC->roleid == 6) {
                $userReport = XoSoRecordHelpers::ReportKhach($userC, $stDate, $endDate, $type);
                $donhang += $userReport[0];
                // $tiencuoc+=$userReport[1];
                // $winlose+=$userReport[2];
                $tiencuoc += ($userReport[1]); //-$tongReport[5]
                $winlose += ($userReport[2]); //+$tongReport[5]);
                $bonus1 += $userReport[3];
                $bonus2 += $userReport[4];
                $bonus3 += $userReport[5];
                $bonus4 += $userReport[6];
                $bonus5 += $userReport[7];
                $bonus6 += $userReport[8];
                $bonus7 += $userReport[9];
                $bonus8 += $userReport[10];
                $com += $userReport[11];
            }
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6, $bonus7, $bonus8, $com);
    }

    public static function ReportKhachCXL($user, $stDate, $endDate)
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $com = 0;
        // $recordUser = XoSoRecordHelpers::getRecordKhachChuaXulyByDate($user,$stDate,$endDate);

        $recordUser = Cache::remember('1XoSoRecordHelpers-getRecordKhachChuaXulyByDate' . $user->id . '-' . $stDate . '-' . $endDate, env('CACHE_TIME_SHORT', 0), function () use ($user, $stDate, $endDate) {
            return XoSoRecordHelpers::getRecordKhachChuaXulyByDate($user, $stDate, $endDate);
        });

        foreach ($recordUser as $record) {
            # code...
            if ($record->exchange_rates > 0)
                // $donhang+=$record->total_bet_money/$record->exchange_rates;
                $donhang++;
            $tiencuoc += $record->total_bet_money;
            $winlose += $record->total_win_money;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus3 += intval($arrbonus[2]) / ($record->total_bet_money / $record->exchange_rates) * ($record->total_win_money / $record->odds);
                    $bonus4 += intval($arrbonus[3]) / ($record->total_bet_money / $record->exchange_rates) * ($record->total_win_money / $record->odds);
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                }

                if (count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus5 += intval($arrbonus[4]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus6 += intval($arrbonus[5]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportKhachCXLv2($user, $stDate, $endDate, $type)
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $bonus9 = 0;
        $bonus10 = 0;
        $com = 0;
        // $recordUser = XoSoRecordHelpers::getRecordKhachChuaXulyByDate($user,$stDate,$endDate);

        $recordUser = XoSoRecordHelpers::getRecordKhachChuaXulyByDatev2($user, $stDate, $endDate, $type);
        // Cache::remember('XoSoRecordHelpers-getRecordKhachChuaXulyByDatev2'.$user->id.'-'.$stDate.'-'.$endDate, env('CACHE_TIME_SHORT', 0), function () use ($user,$stDate,$endDate) {
        //     return XoSoRecordHelpers::getRecordKhachChuaXulyByDatev2($user,$stDate,$endDate);
        // });

        foreach ($recordUser as $record) {
            # code...
            // if ($record->exchange_rates > 0 && $record->game_id < 1000)
            // $donhang+=$record->total_bet_money/$record->exchange_rates;
            $donhang++;

            if ($record->locationslug == 60)
                $tiencuoc += $record->total_bet_money * 1000;
            else
                $tiencuoc += $record->total_bet_money;

            // $tiencuoc += $record->total_bet_money;
            //fix tra thuong
            if ($record->total_win_money > 0 && $record->game_id < 3000) {
                if (
                    $record->game_id == 15 || $record->game_id == 16 ||
                    $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                ) {
                    $winlose += $record->total_win_money;
                } else
                    $winlose += ($record->total_win_money - $record->total_bet_money);
            } else {
                if ($record->locationslug == 60)
                    $winlose += $record->total_win_money * 1000;
                else
                    $winlose += $record->total_win_money;
            }
            // $winlose += $record->total_win_money;
            $com += $record->game_id > 3000 ? $record->com : 0;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                // echo $record->bonus . "<br> ".  PHP_EOL;
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) > 2)
                    $bonus3 += intval($arrbonus[2]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus3 += intval($arrbonus[2])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus4 += intval($arrbonus[3])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                    $bonus4 += intval($arrbonus[3]);
                }

                if ((count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0)) {
                    // $bonus5 += intval($arrbonus[4]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus6 += intval($arrbonus[5]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                    $bonus5 += intval($arrbonus[4]);
                    $bonus6 += intval($arrbonus[5]);
                }

                if (count($arrbonus) >= 8 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus7 += intval($arrbonus[6]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus8 += intval($arrbonus[7]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
                if (count($arrbonus) >= 10 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus9 += intval($arrbonus[8]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus10 += intval($arrbonus[9]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }

        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2,   $bonus3,  $bonus4,  $bonus5,   $bonus6,  $bonus7,  $bonus8,  $bonus9, $bonus10, $com);
        //                         hh1-agent hh1-master hh1-super hh2-agent hh2-master hh2-super hh1-admin hh2-admin 0        hh1-member
    } //                                                 3       4           5       6           7           8       9           10        11    12      

    public static function ReportTongCXL($user, $stDate, $endDate)
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        foreach ($userChild as $userC) {
            $userReport = XoSoRecordHelpers::ReportKhachCXL($userC, $stDate, $endDate);
            $donhang += $userReport[0];
            $tiencuoc += ($userReport[1] - $userReport[3] - $userReport[5]); //-$tongReport[5]
            $winlose += ($userReport[2] + $userReport[3] + $userReport[5]); //+$tongReport[5]);
            $bonus1 += $userReport[3];
            $bonus2 += $userReport[4];
            $bonus3 += $userReport[5];
            $bonus4 += $userReport[6];
            $bonus5 += $userReport[7];
            $bonus6 += $userReport[8];
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportAgCXL($user, $stDate, $endDate)
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        foreach ($userChild as $userC) {
            if ($userC->roleid == 5) {
                $tongReport = XoSoRecordHelpers::ReportTongCXL($userC, $stDate, $endDate);
                $donhang += $tongReport[0];
                $tiencuoc += ($tongReport[1] - $tongReport[4] - $tongReport[6]); //-$tongReport[5]
                $winlose += ($tongReport[2] + $tongReport[4] + $tongReport[6]); //+$tongReport[5]);
                // $bonus1 = ;
                $bonus2 += $tongReport[7];
                $bonus4 += $tongReport[8];
            }
            if ($userC->roleid == 6) {
                $userReport = XoSoRecordHelpers::ReportKhachCXL($userC, $stDate, $endDate);
                $donhang += $userReport[0];
                $tiencuoc += ($userReport[1] - $userReport[3] - $userReport[5]); //-$tongReport[5]
                $winlose += ($userReport[2] + $userReport[3] + $userReport[5]); //+$tongReport[5]);
                $bonus1 += $userReport[3];
                $bonus2 += $userReport[4];
                $bonus3 += $userReport[5];
                $bonus4 += $userReport[6];
                $bonus5 += $userReport[7];
                $bonus6 += $userReport[8];
            }
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportSpAgCXL($user, $stDate, $endDate)
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        foreach ($userChild as $userC) {
            $tongReport = XoSoRecordHelpers::ReportAgCXL($userC, $stDate, $endDate);
            $donhang += $tongReport[0];
            $tiencuoc += ($tongReport[1] - $tongReport[4] - $tongReport[6]); //-$tongReport[5]
            $winlose += ($tongReport[2] + $tongReport[4] + $tongReport[6]); //+$tongReport[5]);
            // $bonus1 = ;
            $bonus2 += $tongReport[7];
            $bonus4 += $tongReport[8];
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportKhachCancel($user, $stDate, $endDate)
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $recordUser = XoSoRecordHelpers::getRecordKhachCancelByDate($user, $stDate, $endDate);

        foreach ($recordUser as $record) {
            # code...
            if ($record->exchange_rates > 0)
                // $donhang+=$record->total_bet_money/$record->exchange_rates;
                $donhang++;
            $tiencuoc += $record->total_bet_money;
            $winlose += $record->total_win_money;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus3 += intval($arrbonus[2]) / ($record->total_bet_money / $record->exchange_rates) * ($record->total_win_money / $record->odds);
                    $bonus4 += intval($arrbonus[3]) / ($record->total_bet_money / $record->exchange_rates) * ($record->total_win_money / $record->odds);
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                }

                if (count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus5 += intval($arrbonus[4]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus6 += intval($arrbonus[5]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportKhachCancelv2($user, $stDate, $endDate, $type)
    {
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        $bonus7 = 0;
        $bonus8 = 0;
        $bonus9 = 0;
        $bonus10 = 0;
        $com = 0;
        $recordUser = XoSoRecordHelpers::getRecordKhachCancelByDatev2($user, $stDate, $endDate, $type);

        foreach ($recordUser as $record) {
            # code...
            // if ($record->exchange_rates > 0 && $record->game_id < 1000)
            // $donhang+=$record->total_bet_money/$record->exchange_rates;
            $donhang++;

            if ($record->locationslug == 60)
                $tiencuoc += $record->total_bet_money * 1000;
            else
                $tiencuoc += $record->total_bet_money;

            // $tiencuoc += $record->total_bet_money;
            //fix tra thuong
            if ($record->total_win_money > 0 && $record->game_id < 3000) {
                if (
                    $record->game_id == 15 || $record->game_id == 16 ||
                    $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                ) {
                    $winlose += $record->total_win_money;
                } else
                    $winlose += ($record->total_win_money - $record->total_bet_money);
            } else {
                if ($record->locationslug == 60)
                    $winlose += $record->total_win_money * 1000;
                else
                    $winlose += $record->total_win_money;
            }
            // $winlose += $record->total_win_money;
            $com += $record->game_id > 3000 ? $record->com : 0;
            if ($record->bonus != null && strlen($record->bonus) > 0) {
                // echo $record->bonus . "<br> ".  PHP_EOL;
                $arrbonus = explode(',', $record->bonus);
                $bonus1 += intval($arrbonus[0]);
                if (count($arrbonus) > 1)
                    $bonus2 += intval($arrbonus[1]);

                if (count($arrbonus) > 2)
                    $bonus3 += intval($arrbonus[2]);

                if (count($arrbonus) >= 4 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    // $bonus3 += intval($arrbonus[2])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus4 += intval($arrbonus[3])/($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus3+= $bonus3;
                    // $bonus4+= $bonus4;
                    $bonus4 += intval($arrbonus[3]);
                }

                if ((count($arrbonus) >= 6 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0)) {
                    // $bonus5 += intval($arrbonus[4]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus6 += intval($arrbonus[5]);//($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                    $bonus5 += intval($arrbonus[4]);
                    $bonus6 += intval($arrbonus[5]);
                }

                if (count($arrbonus) >= 8 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus7 += intval($arrbonus[6]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus8 += intval($arrbonus[7]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
                if (count($arrbonus) >= 10 && $record->exchange_rates > 0 && $record->odds > 0 && $record->total_bet_money > 0) {
                    $bonus9 += intval($arrbonus[8]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    $bonus10 += intval($arrbonus[9]); //($record->total_bet_money/$record->exchange_rates) * ($record->total_win_money/$record->odds) ;
                    // $bonus5+= $bonus5;
                    // $bonus6+= $bonus6;
                }
            }
        }

        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2,   $bonus3,  $bonus4,  $bonus5,   $bonus6,  $bonus7,  $bonus8,  $bonus9, $bonus10, $com);
        //                         hh1-agent hh1-master hh1-super hh2-agent hh2-master hh2-super hh1-admin hh2-admin 0        hh1-member
    } //          

    public static function ReportTongCancel($user, $stDate, $endDate)
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        foreach ($userChild as $userC) {

            $userReport = XoSoRecordHelpers::ReportKhachCancel($userC, $stDate, $endDate);
            $donhang += $userReport[0];
            $tiencuoc += ($userReport[1] - $userReport[3] - $userReport[5]); //-$tongReport[5]
            $winlose += ($userReport[2] + $userReport[3] + $userReport[5]); //+$tongReport[5]);
            $bonus1 += $userReport[3];
            $bonus2 += $userReport[4];
            $bonus3 += $userReport[5];
            $bonus4 += $userReport[6];
            $bonus5 += $userReport[7];
            $bonus6 += $userReport[8];
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportAgCancel($user, $stDate, $endDate)
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        foreach ($userChild as $userC) {
            if ($userC->roleid == 5) {
                $tongReport = XoSoRecordHelpers::ReportTongCancel($userC, $stDate, $endDate);
                $donhang += $tongReport[0];
                $tiencuoc += ($tongReport[1] - $tongReport[4] - $tongReport[6]); //-$tongReport[5]
                $winlose += ($tongReport[2] + $tongReport[4] + $tongReport[6]); //+$tongReport[5]);
                // $bonus1 = ;
                $bonus2 += $tongReport[7];
                $bonus4 += $tongReport[8];
            }
            if ($userC->roleid == 6) {
                $userReport = XoSoRecordHelpers::ReportKhachCancel($userC, $stDate, $endDate);
                $donhang += $userReport[0];
                $tiencuoc += ($userReport[1] - $userReport[3] - $userReport[5]); //-$tongReport[5]
                $winlose += ($userReport[2] + $userReport[3] + $userReport[5]); //+$tongReport[5]);
                $bonus1 += $userReport[3];
                $bonus2 += $userReport[4];
                $bonus3 += $userReport[5];
                $bonus4 += $userReport[6];
                $bonus5 += $userReport[7];
                $bonus6 += $userReport[8];
            }
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportSpAgCancel($user, $stDate, $endDate)
    {
        $userChild = UserHelpers::GetAllUserChild($user, 2);
        $donhang = 0;
        $tiencuoc = 0;
        $winlose = 0;
        $bonus1 = 0;
        $bonus2 = 0;
        $bonus3 = 0;
        $bonus4 = 0;
        $bonus5 = 0;
        $bonus6 = 0;
        foreach ($userChild as $userC) {
            $tongReport = XoSoRecordHelpers::ReportAgCancel($userC, $stDate, $endDate);
            $donhang += $tongReport[0];
            $tiencuoc += ($tongReport[1] - $tongReport[4] - $tongReport[6]); //-$tongReport[5]
            $winlose += ($tongReport[2] + $tongReport[4] + $tongReport[6]); //+$tongReport[5]);
            // $bonus1 = ;
            $bonus2 += $tongReport[7];
            $bonus4 += $tongReport[8];
        }
        return array($donhang, $tiencuoc, $winlose, $bonus1, $bonus2, $bonus3, $bonus4, $bonus5, $bonus6);
    }

    public static function ReportTelegram()
    {
        return;
        $now = date('Ymd_His');
        try {
            Excel::create('xs' . $now, function ($excel) {
                // Our first sheet
                $excel->sheet('First sheet', function ($sheet) {
                    $now = date('Y-m-d');
                    $records = DB::table('xoso_record')
                        ->select('users.name as member', 'xoso_record.id', 'xoso_record.total_bet_money', 'xoso_record.created_at', 'xoso_record.bet_number', 'games.name as game', 'location.name as location')
                        ->join('users', 'users.id', '=', 'xoso_record.user_id')
                        ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                        ->join('location', 'games.location_id', '=', 'location.slug')
                        ->where('xoso_record.date', $now)
                        ->where('xoso_record.isDelete', 0)
                        ->get();
                    $index = 2;
                    $sheet->row(1, array(
                        "Tên KH", "ID", "Loại", "Miền", "Mã cược", "Tổng tiền", "Ngày cược"
                    ));
                    // echo count($records);
                    foreach ($records as $record) {
                        // print_r($record);
                        // return;
                        $sheet->row($index, array(
                            $record->member, $record->id, $record->game, $record->location, $record->bet_number, $record->total_bet_money, $record->created_at
                        ));
                        $index++;
                    }
                    // Freeze first row
                    // $sheet->freezeFirstRow();
                });

                // Our second sheet
                //$excel->sheet('Second sheet', function($sheet) {

                //});
            })->store('xlsx', storage_path('excel/exports'));
            //return;
            // Use this token to access the HTTP API:
            // 5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo
            // api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/sendMessage?chat_id=@luk79_channel&text=test
            // https://api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/deleteMessage?chat_id=@luk79_channel&message_id=1
            // https://api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/deleteMessage?chat_id=@luk79_channel&message_id=377
            $bot_id = "5437696782:AAE-QPL4uMUHzuRkNHHpZrTpTT8CRp26Ebo";
            $chat_id = "@luk79_channel";
            $filename = storage_path('excel/exports') . "/" . "xs" . $now . ".xlsx";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $bot_id . "/sendDocument?chat_id=" . $chat_id . "&text=testtt");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            // Create CURLFile
            // $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
            // print_r($finfo);
            // $cFile = new CURLFile($filename, $finfo);
            $cFile = new CURLFile($filename, "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", $now . ".xlsx");

            // Add CURLFile to CURL request
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                "document" => $cFile
            ]);

            // Call
            $result = curl_exec($ch);

            // Show result and close curl
            var_dump($result);
            curl_close($ch);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return true;
    }
    public static function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function ReportTest()
    {

        $now = date('Ymd');
        Excel::create('test_xs' . $now, function ($excel) {
            // Our first sheet
            $excel->sheet('First sheet', function ($sheet) {
                $now = date('Y-m-d');

                $index = 2;
                $sheet->row(1, array("Tên KH", "ID", "Loại", "Miền", "Mã cược", "Tổng tiền", "Ngày cược"));
                for ($x = 0; $x <= 100000; $x++) {
                    $sheet->row($index, array($index, $index, $index, $index, $index, $index, $index));
                    $index++;
                }

                // Freeze first row
                // $sheet->freezeFirstRow();
            });

            // Our second sheet
            //$excel->sheet('Second sheet', function($sheet) {

            //});
        })->store('csv', storage_path('excel/exports'));
    }

    public static function ReportMessageTelegramByName($arrname, $chat_id = "@baoliveluk79")
    {
        return;
        try {

            $domain = "https://luk79.net";
            $text = "BÁO CÁO CƯỢC\n";
            $now = date('Ymd');
            $path = public_path('gamehis') . "/";

            $check = false;
            foreach ($arrname as $record) {
                $name = $record->name;
                $filename = public_path('gamehis') . "/" .  $name . "_" . $now . ".csv";

                if (file_exists($filename)) {
                    $check = true;
                    $filePath = $filename;
                    $copyName = str_replace(' ', '_', $name) . "_" . $now . "_" . XoSoRecordHelpers::generateRandomString(10) . ".csv"; // copy file mới tránh scan link
                    $copypath = $path . "copy/" . $copyName;
                    $fileCopy = copy($filePath, $copypath);
                    $fileContent = file($copypath, FILE_IGNORE_NEW_LINES);
                    $totalBetValue = 0;
                    $totalCancelValue = 0;
                    $val = 0;
                    if (($handle = fopen($filePath, "r")) !== FALSE) {

                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                            $val++;
                            if ($val == 1) {
                                continue;
                            }
                            $num = count($data);
                            // $row++;
                            // var_dump($data[4]);
                            if (isset($data[7])) {
                                $totalCancelValue += (int)str_replace(",", "", $data[5]);
                            } else {
                                $totalBetValue += (int)str_replace(",", "", $data[5]);
                            }
                        }
                        fclose($handle);
                    }

                    $text .= "- Loại: " . strtoupper($name) . "\n";
                    $text .= "- Thời gian: " . date('h:i:s d-m-Y') . "\n";
                    $text .= "- Tổng Cược: " . number_format($totalBetValue - $totalCancelValue) . "\n";
                    $text .= "- Tên File: " . $copyName .  ' (' . (count($fileContent)) . " Mã" . ')' . "\n";
                    $text .= "- Link File: " . $domain . "/gamehis/copy/" . urlencode($copyName) . "\n";
                    $text .= "- Hash File: " . md5_file($copypath) . "\n---------------\n";



                    // Show result and close curl
                    // 			var_dump($result);

                }
            }

            if ($check) {
                // echo 'run bot';
                $bot_id = "5437696782:AAE-QPL4uMUHzuRkNHHpZrTpTT8CRp26Ebo";
                // 			$chat_id = "-720602361";
                // 			$chat_id = "@exportluk79";
                // $bot_id = "5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo";
                // $chat_id = "@luk79_channel1";
                $ch = curl_init();
                // echo "https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=" . $chat_id . "&text=".urlencode ($text);
                // curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=" . $chat_id . "&text=".urlencode ($text));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // curl_setopt($ch, CURLOPT_POST, 1);

                // // Call
                // $result = curl_exec($ch);
                // echo $result;
                // curl_close($ch);

                $curl = new Curl();
                $linkminhngoc = "https://api.telegram.org/bot" . $bot_id . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($text);
                $response = $curl->get($linkminhngoc);

                // print_r ($response->body);

                $domHtml = HtmlDomParser::str_get_html($response->body);
                $domHtml = $response->body;
                $mainBody = $domHtml;
                // $mainBody = '{"provinceCode":"MB","provinceName":"","rawData":"","tuong_thuat":false,"isRolling":1,"resultDate":1624362607878,"dau":{"0":["1","2","5","9"],"1":["7","8","8"],"2":["0"],"3":["0","2"],"4":["2","5","6","7"],"5":["2","8","8"],"6":["0","8"],"7":["3","9"],"8":["0","6"],"9":["6","6","8","9"]},"duoi":{"0":["2","3","6","8"],"1":["0"],"2":["0","3","4","5"],"3":["7"],"4":[],"5":["0","4"],"6":["4","8","9","9"],"7":["1","4"],"8":["1","1","5","5","6","9"],"9":["0","7","9"]},"lotData":{"1":["41158"],"2":["46686","84680"],"3":["65752","98202","01898","72132","77218","11699"],"4":["4601","7796","2920","3030"],"5":["6545","0718","3173","7947","7279","4242"],"6":["546","309","896"],"MaDb":["5EA","12EA","7EA","11EA","","10EA"],"7":["17","","58","60"],"DB":[""]},"loto":[]}';
                // print_r ($mainBody);
                // $mainBody = "1616239796;;;;;;;*-*-*;12345;";
                $kqraw = json_decode($mainBody, true);

                // print_r($kqraw);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return true;
    }

    public static function ReportMessageTelegramByNamev2($arrname, $chat_id = "@baoliveluk79")
    {
        try {

            $domain = "https://luk79.net";
            $text = "BÁO CÁO CƯỢC\n";
            $now = date('Ymd');
            $path = public_path('gamehis') . "/";

            $files = scandir($path);

            $check = false;
            print_r($files);
            //merge files to one
            foreach ($arrname as $record) {
                $name = $record->name;
                $filenameM = public_path('gamehis') . "/" . $name . "_" . $now . "m.csv";

                $f = null;
                // fputcsv($f, $arr);
                // flock($f, LOCK_UN);
                // fclose($f);
                $totalBetValue = 0;
                $totalCancelValue = 0;

                foreach ($files as $value) {
                    if (str_contains($value, $now) && str_contains($value, $name) && !str_contains($value, 'm.csv')) {
                        // echo $value;
                        // break;
                        $filename = $path . $value;
                        if (file_exists($filename)) {
                            $check = true;
                            if ($f == null) {
                                $f = fopen($filenameM, 'a');
                                flock($f, LOCK_EX);

                                fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
                                fputcsv($f, ["STT", "Mã Cược", "Loại", "Người dùng", "Số cược", "Tiền cược", "Thời gian", "Huỷ"]);

                                fprintf($f, chr(0xEF) . chr(0xBB) . chr(0xBF));
                            }
                            $filePath = $filename;
                            $val = 0;
                            if (($handle = fopen($filePath, "r")) !== FALSE) {
                                while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                                    $val++;
                                    if ($val == 1) {
                                        continue;
                                    }
                                    $num = count($data);
                                    // $row++;
                                    // var_dump($data[4]);
                                    fputcsv($f, $data);
                                    if (isset($data[7])) {
                                        $totalCancelValue += (int)str_replace(",", "", $data[5]);
                                    } else {
                                        $totalBetValue += (int)str_replace(",", "", $data[5]);
                                    }
                                }
                                fclose($handle);
                            }
                        }
                    }
                }
                // echo $name.'-'.$check.'  ';
                if ($f != null) {
                    flock($f, LOCK_UN);
                    fclose($f);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage() . ' ' . $ex->getLine();
        }
    }

    public static function ReportMessageTelegramToday()
    {
        try {
            $domain = "https://luk79.net";
            $text = "------- BÁO CÁO CƯỢC " . date('d-m-Y h:i:s') . " -------\n\n";
            $now = date('Ymd');
            $path = public_path('gamehis') . "/";
            $files = scandir($path);


            foreach ($files as $value) {
                if (strpos($value, $now) !== false) {
                    preg_match('/^(.*?)_/', $value, $typecuoc);
                    $filePath = $path . $value;
                    $copyName = substr($value, 0, -4) . "_" . XoSoRecordHelpers::generateRandomString(10) . ".csv"; // copy file mới tránh scan link
                    $copypath = $path . "copy/" . $copyName;
                    $fileCopy = copy($filePath, $copypath);
                    $fileContent = file($copypath, FILE_IGNORE_NEW_LINES);
                    $totalBetValue = 0;
                    $totalCancelValue = 0;
                    $val = 0;
                    if (($handle = fopen($filePath, "r")) !== FALSE) {

                        while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
                            $val++;
                            if ($val == 1) {
                                continue;
                            }
                            $num = count($data);
                            // $row++;
                            var_dump($data[5]);
                            if (isset($data[7])) {
                                $totalCancelValue += (int)str_replace(",", "", $data[5]);
                            } else {
                                $totalBetValue += (int)str_replace(",", "", $data[5]);
                            }
                        }
                        fclose($handle);
                    }

                    $text .= "Loại: " . strtoupper((isset($typecuoc[1]) ? $typecuoc[1] : "")) . "\n";
                    $text .= "Tổng Cược: " . number_format($totalBetValue - $totalCancelValue) . "\n";
                    $text .= "Tên File: " . $copyName .  ' (' . (count($fileContent)) . " Mã" . ')' . "\n";
                    $text .= "Link File: " . $domain . "/gamehis/copy/" . urlencode($copyName) . "\n";
                    $text .= "Hash File: " . md5_file($copypath)  . "\n\n---------------\n\n";
                }
            }
            var_dump($text);
            // return "1";
            $nowTime = date('d-m-Y h:i:s');

            $bot_id = "5437696782:AAE-QPL4uMUHzuRkNHHpZrTpTT8CRp26Ebo";
            // 			$chat_id = "-720602361";
            $chat_id = "@exportluk79";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $bot_id . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($text));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            // Call
            $result = curl_exec($ch);

            // Show result and close curl
            var_dump($result);
            curl_close($ch);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return true;
    }
    public static function ReportMessageTelegram($text)
    {
        $now = date('Ymd_His');
        try {
            // Use this token to access the HTTP API:
            // 5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo
            // api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/sendMessage?chat_id=@luk79_channel&text=test
            // https://api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/deleteMessage?chat_id=@luk79_channel&message_id=1
            // https://api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/deleteMessage?chat_id=@luk79_channel&message_id=377
            $bot_id = "5437696782:AAE-QPL4uMUHzuRkNHHpZrTpTT8CRp26Ebo";
            $chat_id = "@luk79_channel";
            $filename = storage_path('excel/exports') . "/" . "xs" . $now . ".xlsx";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $bot_id . "/sendMessage?chat_id=" . $chat_id . "&text=" . $text);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

            // Call
            $result = curl_exec($ch);

            // Show result and close curl
            var_dump($result);
            curl_close($ch);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return true;
    }

    public static function DelallTelegram()
    {

        try {

            // Use this token to access the HTTP API:
            // 5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo
            // api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/sendMessage?chat_id=@luk79_channel&text=test
            // https://api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/deleteMessage?chat_id=@luk79_channel&message_id=1
            // https://api.telegram.org/bot5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo/deleteMessage?chat_id=@luk79_channel&message_id=377
            $bot_id = "5437696782:AAE-QPL4uMUHzuRkNHHpZrTpTT8CRp26Ebo";
            $chat_id = "@luk79_channel";
            for ($i = 377; $i <= 500; $i++) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $bot_id . "/deleteMessage?chat_id=" . $chat_id . "&message_id=" . $i);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                // Call
                $result = curl_exec($ch);
                // Show result and close curl
                var_dump($result);
                curl_close($ch);
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return true;
    }

    public static function GetRoleName($roleid)
    {
        $user_role_name = "";
        switch ($roleid) {
            case 1:
                $user_role_name = "Admin";
                break;
            case 2:
                $user_role_name = "Super";
                break;

            case 4:
                $user_role_name = "Master";
                break;

            case 5:
                $user_role_name = "Agent";
                break;

            case 6:
                $user_role_name = "Member";
                break;
            default:
                # code...
                break;
        }
        return $user_role_name;
    }

    public static function GetRoleShortName($roleid)
    {
        $user_role_name = "";
        switch ($roleid) {
            case 1:
                $user_role_name = "A";
                break;
            case 2:
                $user_role_name = "S";
                break;

            case 4:
                $user_role_name = "M";
                break;

            case 5:
                $user_role_name = "A";
                break;

            case 6:
                $user_role_name = "M";
                break;
            default:
                # code...
                break;
        }
        return $user_role_name;
    }

    public static function checkLockSuper($game_code, $bet_number)
    {
        // get super 
        $lockSuper = "";
        $superU =
            User::where('user_create', 274)
            ->where('active', 0)
            ->where('per', 0)
            ->get();
        foreach ($superU as $super) {

            $totalBet = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($game_code, $bet_number, $super);
            // echo $totalBet[1] . " ";
            $customerTypeSuper =  CustomerType_Game::where('game_id', $game_code)->where('code_type', 'A')
                ->where('created_user', $super->id)->first();

            // if $customerTypeSuper->change_max_one
            // echo $customerTypeSuper->change_max_one . " ";
            if ($customerTypeSuper->change_max_one < $totalBet[0]) {
                $lockSuper .= $super->id . ',';
            }
        }
        return $bet_number . '-' . $lockSuper;
        // count total bet with game code and bet number
        // compare and lock if have
    }

    public static function checkCancelBetxs($history_ids)
    {
        $history = History::whereIn("id", $history_ids)->get();
        foreach ($history as $item) {
            $record_ids = $item->ids;
            $arrIds = explode(",", $record_ids);
            $records = XoSoRecord::whereIn("id", $arrIds)->get();
            $game_codes = [7, 9, 10, 11, 16, 18, 19, 20, 21];
            foreach ($records as $record) {
                if (in_array($record->game_id, $game_codes)) {
                    $record->total_win_money = -1;
                    $record->ipaddr = "Có dấu hiệu bất thường!";
                    $record->save();
                }
            }
        }
    }

    public static function scanCheatNumber()
    {
        $xoso = new XoSo();
        $now = date('Y-m-d');
        //$now = "2023-09-07";
        $rs = $xoso->getKetQua(1, $now);
        // var_dump($rs);
        $countColEmpty = 0;
        for ($i = 0; $i <= 9; $i++) {
            $strketqua = '';
            for ($j = 0; $j <= 9; $j++) {
                $mau = $i . $j;
                $count = 0;
                foreach ($rs as $ketquafull) {
                    if ($count > 4 && $count < 11) {
                        if (is_array($ketquafull) == false) {
                            $ketqua2so = substr($ketquafull, -2);
                            // echo $ketqua2so.' ';
                            if ($mau == $ketqua2so) //&& !(strpos($strketqua,$mau)!== false)
                                $strketqua .= $mau . '; ';
                        } else
                            foreach ($ketquafull as $ketquatungso) {
                                $ketqua2so = substr($ketquatungso, -2);
                                if (strlen($ketquatungso) < 2) continue;
                                // echo $ketqua2so.' ';
                                if ($mau === $ketqua2so) //&& !(strpos($strketqua,$mau)!== false)
                                    $strketqua .= $mau . '; ';
                            }
                    }
                    $count++;
                }
            }
            if (empty($strketqua)) $countColEmpty++;
            // echo $i.' '.$strketqua.PHP_EOL;
        }

        for ($i = 0; $i <= 9; $i++) {
            $strketqua = '';
            for ($j = 0; $j <= 9; $j++) {
                $mau = $j . $i;
                $count = 0;
                foreach ($rs as $ketquafull) {
                    if ($count > 4 && $count < 11) {
                        // print_r($ketquafull);
                        if (count($ketquafull) == 1) {
                            $ketqua2so = substr($ketquafull, -2);
                            // echo $ketqua2so.' ';
                            if ($mau == $ketqua2so) // && !(strpos($strketqua,$mau)!== false))
                                $strketqua .= $mau . '; ';
                        } else
                            foreach ($ketquafull as $ketquatungso) {
                                $ketqua2so = substr($ketquatungso, -2);
                                // echo $ketqua2so.' ';
                                if ($mau == $ketqua2so) // && !(strpos($strketqua,$mau)!== false))
                                    $strketqua .= $mau . '; ';
                            }
                    }
                    $count++;
                }
            }
            if (empty($strketqua)) $countColEmpty++;
        }

        echo $countColEmpty . "" . PHP_EOL;
        if ($countColEmpty >= 3) return true;
        return false;
    }

    public static function trathuong($records,$rs,$now){
        try{
            $totalBetMoney = 0;
            $totalWinMoney = 0;
            foreach ($records as $record)
            {
                if ($record['game_id'] >= 700 && $record['game_id'] < 800 ){
                    $now = date('Y-m-d');
                    $hour = date('H');
                    $minus = date('i');

                    $minus = $minus - $minus%10;
                    
                    $time = strtotime($record['created_at']);

                    // $newformat = date('Y-m-d',$time);
                    $datetimeRecord = date('Y-m-d',$time);
                    $hRecord = date('H',$time);
                    $mRecord = date('i',$time);
                    $mRecord = $mRecord - $mRecord%10;

                    // echo $datetimeRecord .' '.$hRecord.' '.$mRecord;

                    // echo $now .' '.$hour.' '.$minus;
                    if ($hour - 1 == $hRecord && $mRecord == 50 && $minus == 0)
                    {
                        //ok
                    }else if ($hour == $hRecord && $minus - 10 == $mRecord)
                    {
                        //ok
                    }else
                        continue;

                    $record->xien_id = intval(str_replace('#','',$rs['8']));
                //  echo 'ok';       
                //  return;   
                }
                if($record->total_bet_money == 0)
                    continue;

                $totalBetMoney+=$record->total_bet_money;

                if($record['game_id']==721) //check tai
                {
    
                    $win = GameHelpers::CheckTaiKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==722) //check xiu
                {
    
                    $win = GameHelpers::CheckXiuKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==723) //check le
                {
    
                    $win = GameHelpers::CheckLeKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==724) //check chan
                {
    
                    $win = GameHelpers::CheckChanKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==725) //check rong
                {
    
                    $win = GameHelpers::CheckRongKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==726) //check hoa
                {
    
                    $win = GameHelpers::CheckRongHoaHoKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==727) //check ho
                {
    
                    $win = GameHelpers::CheckHoKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==728) //check tren be
                {
    
                    $win = GameHelpers::CheckTrenKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==729) //check giua
                {
    
                    $win = GameHelpers::CheckTrenHoaDuoiKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==730) //check duoi lon
                {
    
                    $win = GameHelpers::CheckDuoiKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==731) //check tai le
                {
    
                    $win = GameHelpers::CheckTaiLeKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==732) //check xiu le
                {
    
                    $win = GameHelpers::CheckXiuLeKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==733) //check tai chan
                {
    
                    $win = GameHelpers::CheckTaiChanKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==734) //check xiu chan
                {
    
                    $win = GameHelpers::CheckXiuChanKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==735) //check kim
                {
    
                    $win = GameHelpers::CheckKimKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==736) //check moc
                {
    
                    $win = GameHelpers::CheckMocKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==737) //check thuy
                {
    
                    $win = GameHelpers::CheckThuyKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==738) //check hoa
                {
    
                    $win = GameHelpers::CheckKMTHoaTKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==739) //check tho
                {
    
                    $win = GameHelpers::CheckThoKeno($record['bet_number'],$rs);
                    if($win != null)
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }

                if($record['game_id']==22 || $record['game_id']==122) //check de 6
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
                    $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0 - $record->total_bet_money;
                }
    
                // if($record['game_id']==23 || $record['game_id']==123) //check de 7
                // {
    
                //     $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7');
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
    
                if($record['game_id']==25) //check dau than tai
                {
    
                    $win = GameHelpers::CheckDauThanTai($record['bet_number'],$rs,'than_tai');
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
    
                if($record['game_id']==26) //check duoi than tai
                {
    
                    $win = GameHelpers::CheckDuoiThanTai($record['bet_number'],$rs,'than_tai');
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
    
                if($record['game_id']==27) //check dau de
                {
    
                    $win = GameHelpers::CheckDauLoDe($record['bet_number'],$rs,'DB');
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
    
                if($record['game_id']==28) //check dau nhat
                {
    
                    $win = GameHelpers::CheckDauLoDe($record['bet_number'],$rs,'Giai_1');
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
    
                if($record['game_id']==31) //check de 2.1
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'2',1);
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
    
                if($record['game_id']==32) //check de 2.2
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'2',2);
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
    
                if($record['game_id']==33) //check de 3.1
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',1);
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
    
                if($record['game_id']==34) //check de 3.2
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',2);
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
    
                if($record['game_id']==35) //check de 3.3
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',3);
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
    
                if($record['game_id']==36) //check de 3.4
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',4);
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
    
                if($record['game_id']==37) //check de 3.5
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',5);
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
    
                if($record['game_id']==38) //check de 3.6
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'3',6);
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
    
                if($record['game_id']==39) //check de 4.1
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',1);
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
    
                if($record['game_id']==40) //check de 4.2
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',2);
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
    
                if($record['game_id']==41) //check de 4.3
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',3);
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
    
                if($record['game_id']==42) //check de 4.4
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'4',4);
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
    
                if($record['game_id']==43) //check de 5.1
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',1);
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
    
                if($record['game_id']==44) //check de 5.2
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',2);
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
    
                if($record['game_id']==45) //check de 5.3
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',3);
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
    
                if($record['game_id']==46) //check de 5.4
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',4);
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
    
                if($record['game_id']==47) //check de 5.5
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',5);
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
    
                if($record['game_id']==48) //check de 5.6
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'5',6);
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
    
                if($record['game_id']==49) //check de 6.1
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'6',1);
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
    
                if($record['game_id']==50) //check de 6.2
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'6',2);
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
    
                if($record['game_id']==51) //check de 6.3
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'6',3);
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
    
                if($record['game_id']==52) //check de 7.1
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',1);
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
    
                if($record['game_id']==53) //check de 7.2
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',2);
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
    
                if($record['game_id']==54) //check de 7.3
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',3);
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
    
                if($record['game_id']==55) //check de 7.4
                {
    
                    $win = GameHelpers::CheckGiaiX($record['bet_number'],$rs,'7',4);
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
    
                if($record['game_id']==14 || $record['game_id']==114
                || $record['game_id']==314 || $record['game_id']==414 || $record['game_id']==514 || $record['game_id']==614 ) //check de
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
    
                if($record['game_id']==12 || $record['game_id']==112) //check nhat
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
                        $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose(array($win),$record['id'],$record['game_id']);
                     else
                        $record->total_win_money = 0-$record->total_bet_money;
                }
    
                if($record['game_id']==17 || $record['game_id']==117
                || $record['game_id']==317 || $record['game_id']==417 || $record['game_id']==517 || $record['game_id']==617 ) //check 3 cang
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
    
                if($record['game_id']==56) //check 3 cang nhat
                {
    
                    $win = GameHelpers::CheckLoDe($record['bet_number'],$rs,"3_Cang_nhat");
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
    
                if($record['game_id']==7 || $record['game_id']==107
                || $record['game_id']==307 || $record['game_id']==407 || $record['game_id']==507 || $record['game_id']==607
                || $record['game_id']==701
                ) //check lo 2 so
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
    
                if($record['game_id']==18) //check lo live 2 so
                {
                    $win = GameHelpers::CheckLoLive2($record['bet_number'],$record['xien_id'],$rs);
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
                
                if($record['game_id']==8 || $record['game_id']==108
                || $record['game_id']==308 || $record['game_id']==408 || $record['game_id']==508 || $record['game_id']==608) //check lo 3 so
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
    
                if($record['game_id']==2 || $record['game_id']==102) //check lo xien
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
                        $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                    else
                        $record->total_win_money = 0-$record->total_bet_money;
                }
    
                $haswin = true;
                if($record['game_id']==29) //check lo xien 29
                {
                    $haswin = false;
                    $countwin = 0;
                    $winnumber="";
                    $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                    for ($i=0; $i < count($listbets); $i++) { 
                        for ($j=$i+1; $j < count($listbets); $j++) { 
                            $win = GameHelpers::CheckLoXienNhayLive(trim($listbets[$i]).','.trim($listbets[$j]),$record['xien_id'],$rs);
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

                $haswin = true;
                if($record['game_id']==329 || $record['game_id']==429 || $record['game_id']==529 || $record['game_id']==629) //check lo xien 29
                {
                    $haswin = false;
                    $countwin = 0;
                    $winnumber="";
                    $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                    for ($i=0; $i < count($listbets); $i++) { 
                        for ($j=$i+1; $j < count($listbets); $j++) { 
                            $win = GameHelpers::CheckLoXienNhay(trim($listbets[$i]).','.trim($listbets[$j]),$rs);
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
    
                $haswin = true;
                if($record['game_id']==9 ) //check lo xien live 2
                {
                    $haswin = false;
                    $countwin = 0;
                    $winnumber="";
                    $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                    for ($i=0; $i < count($listbets); $i++) { 
                        for ($j=$i+1; $j < count($listbets); $j++) { 
                            $win = GameHelpers::CheckLoXienLive(trim($listbets[$i]).','.trim($listbets[$j]),$record['xien_id'],$rs);
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

                $haswin = true;
                if($record['game_id']==109 || $record['game_id']==709
                || $record['game_id']==309 || $record['game_id']==409 || $record['game_id']==509 || $record['game_id']==609) //check lo xien 2
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
    
                if($record['game_id']==10) //check lo xien 3
                {
                    $haswin = false;
                    $countwin = 0;
                    $winnumber="";
                    $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                    for ($i=0; $i < count($listbets); $i++) { 
                        for ($j=$i+1; $j < count($listbets); $j++) {
                            for ($k=$j+1; $k < count($listbets); $k++) { 
                                $win = GameHelpers::CheckLoXienLive(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]),$record['xien_id'],$rs);
                                
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

                if($record['game_id']==110 || $record['game_id']==710
                || $record['game_id']==310 || $record['game_id']==410 || $record['game_id']==510 || $record['game_id']==610) //check lo xien 3
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
    
                if($record['game_id']==11) //check lo xien 4
                {
                    $haswin = false;
                    $countwin = 0;
                    $winnumber="";
                    $listbets = explode(",",str_replace(" ","",$record['bet_number']));
                    for ($i=0; $i < count($listbets); $i++) { 
                        for ($j=$i+1; $j < count($listbets); $j++) {
                            for ($k=$j+1; $k < count($listbets); $k++) { 
                                for ($l=$k+1; $l < count($listbets); $l++) { 
                                    $win = GameHelpers::CheckLoXienLive(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]).','.trim($listbets[$l]),$record['xien_id'],$rs);
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

                if($record['game_id']==111 || $record['game_id']==711
                || $record['game_id']==311 || $record['game_id']==411 || $record['game_id']==511 || $record['game_id']==611) //check lo xien 4
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
    
                if($record['game_id']==16 || $record['game_id']==116
                || $record['game_id']==316 || $record['game_id']==416 || $record['game_id']==516 || $record['game_id']==616) //check lo truot
                {
                    $win = GameHelpers::CheckLoTruot1($record['bet_number'],$rs);
                    try{
                        // try code
                        //XoSoRecordHelpers::PaymentLottery($record);
                    } 
                    catch(\Exception $e){
                        // catch code
                    }
                    print_r($win);
                    if(count($win) == 1 && $win[0] >= 0)
                        $totalWinMoney+=XoSoRecordHelpers::UpdateWinLose($win,$record['id'],$record['game_id']);
                    else
                        if($win[0] < 0)
                            $record->total_win_money = 0-($record->total_bet_money*count($win) - ($record->total_bet_money/$record->exchange_rates)*$record->odds);
                }
    
                if($record['game_id']==19 || $record['game_id']==119) //check lo truot 4
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
                        $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
                    
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
    
                if($record['game_id']==20 || $record['game_id']==120) //check lo truot
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
                        $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
                }
    
                if($record['game_id']==21 || $record['game_id']==121) //check lo truot
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
                        $totalWinMoney+=XoSoRecordHelpers::UpdateWinLoseXien($countwin,$record['id'],$record['game_id'],$winnumber);
                }
    
                if($record['game_id']==15 || $record['game_id']==115
                || $record['game_id']==315 || $record['game_id']==415 || $record['game_id']==515 || $record['game_id']==615) //check de truot
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

                if($record['game_id']==352 || $record['game_id']==452 || $record['game_id']==552 || $record['game_id']==652) //check de 7
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

            if($record['game_id']==353 || $record['game_id']==453 || $record['game_id']==553 || $record['game_id']==653) //check de 8
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

                // if ($record->total_win_money > 0)
                //     $totalWinMoney+=$record->total_win_money;
                $record->save();    
            }
            }catch(\Exception $ex){
                // catch code
                // print($e);
                // NotifyHelpers::SendMailNotification('Tra thuong loi '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                echo 'Tra thuong loi '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine();
                NotifyHelpers::SendTelegramNotification('Tra thuong loi '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            }
            DB::table('history')
                ->where('date', date('Y-m-d'))
                ->update([
                    'paid' =>  1
                ]);
            // NotifyHelpers::SendTelegramNotification('Tra thuong hoan thanh!');
            // $this->comment("Lay ket qua ngay ".$now);
            //if ($totalBetMoney > 0 )
            // NotifyHelpers::SendTelegramNotification('Thong ke ngay '.$now.' : '.number_format($totalBetMoney).'-'.number_format($totalWinMoney));
    }

    public static function AccountStatistics($user){
        $newDate = date("Y-m-d");
        $newDateShow = date("d-m-Y");

        $newDate = date("Y-m-d");
        if (date('H') < 11)
            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));

        $userChild = UserHelpers::GetAllUser($user);

        $counttkactive = [0, 0, 0, 0, 0, 0, 0];
        $counttkkhoa = [0, 0, 0, 0, 0, 0, 0];
        $counttkngungdat = [0, 0, 0, 0, 0, 0, 0];

        $counttiendadung = 0;
        $countsuper = 0;
        $countagent = 0;
        $countmaster = 0;
        $countmember = 0;


        foreach ($userChild as $child) {
            if ($child->lock == 0 && $child->active == 0)
                $counttkactive[$child->roleid]++;
            if ($child->lock == 1 && $child->active == 0)
                $counttkngungdat[$child->roleid]++;
            if ($child->lock == 2 && $child->active == 0)
                $counttkkhoa[$child->roleid]++;

            if ($child->user_create == $user->id)
                $counttiendadung += $child->credit;

            switch ($child->roleid) {
                case 2:
                    $countsuper++;
                    break;

                case 4:
                    $countmaster++;
                    break;

                case 5:
                    $countagent++;
                    break;

                case 6:
                    $countmember++;
                    break;

                default:
                    # code...
                    break;
            }
        }

        $rs = [];
        // $arrUser = [];
        // foreach($userChild as $item){
        // 	array_push($arrUser,$item->id);
        // }
        // $arrUser = Cache::remember('UserHelpers-GetAllUserV2'.$user->id, env('CACHE_TIME_SHORT', 0), function () use ($user) {
        // 	return UserHelpers::GetAllUserV2($user);
        // });
        $arrUser = UserHelpers::GetAllUserV2($user);
        if (date('H') < 11) {

            $newDate = date("Y-m-d", strtotime('-1 day', strtotime($newDate)));
            $newDateShow = date("d-m-Y", strtotime('-1 day', strtotime(date("d-m-Y"))));
        }

        $rs = Cache::remember('Homepage' . $user->id . '-' . $newDate, env('CACHE_TIME_SHORT', 0), function () use ($newDate, $arrUser) {
            return DB::table('xoso_record')
                // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                // ->orderBy('sumbet', 'desc')
                ->where('isDelete', 0)
                // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                ->where('date', '>=', $newDate)
                ->where('date', '<=', date("Y-m-d", strtotime('+1 day', strtotime($newDate))))
                // ->where('game_id', 7)
                ->whereIn('user_id', $arrUser)
                // ->groupBy('game_id')
                ->get();
        });

        $H_7zBall_record = DB::table('history_7zball_bet')
            ->where('createdate', '>=', date("Y-m-d", strtotime($newDate)) . ' 11:00:00')
            ->where('createdate', '<', date("Y-m-d", strtotime('+1 day', strtotime($newDate))) . ' 11:00:00')
            ->join('games', 'history_7zball_bet.gametype', '=', 'games.game_code')
            ->join('users', 'users.name', '=', 'history_7zball_bet.username')
            ->whereIn('users.id', $arrUser)
            ->select('*', 'users.*', 'games.name as game')
            ->get();
        // echo date("Y-m-d",strtotime('+1 day',strtotime($date))) .' 11:00:00';
        foreach ($H_7zBall_record as $value) {
            $dataResults = json_decode($value->jsoninfo);
            $bonus = isset($value->bonus) ? $value->bonus : "0,0,0,0,0,0,0,0,0,0";
            $record7zBall = (json_decode('{"game_id":' . $value->gametype . ',"bonus":"' . $bonus . '","total_bet_money":' . $value->betamount . ',"com":' . $value->com . ',"odds":0,"exchange_rates":0,"total_win_money":' . $value->payoff . ',"bet_number":"01","win_number":"","isDelete":0,"created_at":"' . $value->createdate . '","updated_at":"' . $value->createdate . '","xien_id":0,"game":"' . $value->game . '","location":"7zBall","locationslug":"70", "SerialID": "' . "" . '", "result": "' . "" . '"}'));
            $record7zBall->rawBet = $dataResults;
            array_push($rs, $record7zBall);
        }

        $totalCXL = 0;
        $totalWinLose = 0;
        $totalBet = 0;
        foreach ($rs as $record) {
            if ($record->total_win_money == 0) {
                if (!(isset($record->locationslug) && $record->locationslug == 70 && $record->rawBet->paid == 1)) {
                    $totalCXL += $record->total_bet_money;
                }
            } else {
                if ($record->total_win_money > 0 && $record->game_id < 100) {
                    if (
                        $record->game_id == 15 || $record->game_id == 16 ||
                        $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616 || $record->game_id == 115 || $record->game_id == 116
                    ) {
                        $totalWinLose += ($record->total_win_money);
                    } else
                        $totalWinLose += ($record->total_win_money - $record->total_bet_money);
                } else {
                    $totalWinLose += ($record->total_win_money);
                }

                $bonus = explode(',', $record->bonus);
                $totalWinLose += array_sum($bonus);
            }
            $totalBet += $record->total_bet_money;
        }
        
        return ["credit" => number_format($user->credit),
                "credit_used" => number_format($counttiendadung),
                "account_created" => number_format($countmember),
                "total_money_inprocess" => number_format($totalCXL, 0),
                "total_money_results" => number_format($totalWinLose, 0)];
    }

}
