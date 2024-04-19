<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\History;
use App\Http\Controllers\XosobotController;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Environment\Console;
use SevenEcks\Tableify\Tableify;

class HistoryHelpers
{
    public static function InsertHistory7zball($value, $usercreate)
    {
        try {
            $user = User::where("name", $usercreate)->first();
            $txtBet = "";
            if ($value["bet_type"] != "parlay") {
                $txtBet .= $value["bet_type_txt"] . "\n";
                $txtBet .= $value["bet_on_txt"] . " " . number_format(isset($value["bet_odd"]) ? $value["bet_odd"] : 0, 2); // ."\n";

                if (str_contains($value["bet_type"], "#my"))
                    $txtBet .=  "MY";
                else $txtBet .=  "DEC";

                if (isset($value["bet_match_current"]))
                    $txtBet .=  '(' . XoSoRecordHelpers::converScoreMatch($value["bet_type"], $value["bet_match_current"]) . ')';
                $txtBet .= "\n";
                $txtBet .= $value["m_tnHomeName"] . ' vs ' . $value["m_tnAwayName"] . "\n";
                $txtBet .= (isset($value["m_tnName"]) ? $value["m_tnName"] : "") . "\n";

                $dataResults = $value;
                $bet_time = null;
                if (isset($dataResults["bet_match_current"])) {
                    if ($dataResults["bet_type"] != "parlay") {
                        $detailMatchOnBet = json_decode($dataResults["bet_match_current"]);
                        $bet_time = 'Thời Gian Đặt Cược ' . (isset($detailMatchOnBet) ? XoSoRecordHelpers::converTimeMatch($detailMatchOnBet) : 'Hiệp 1 00:00');
                    } else {
                    }
                }
                $txtBet .= (isset($bet_time) && $bet_time  != "" ? $bet_time  : (isset($value["kickoffVN"]) ? $value["kickoffVN"] : ""));
            } else {
                $parlay = json_decode($value["parlay"]);
                $detailMatchOnBetLst = isset($value["bet_match_current"]) ? json_decode($value["bet_match_current"]) : null;
                $bet_data_lst = json_decode($value["bet_data"]);
                $bet_ons = json_decode($value["parlay_money"]);
                $countBet = 0;
                $strBet_on = "";
                foreach ($bet_ons as $bet_on) {
                    if ($bet_on->money == 0) continue;
                    $countBet++;
                    if (isset($bet_on->nameParlay))
                        $strBet_on .= ($bet_on->nameParlay . " ");
                }

                $txtBet .= $value["bet_type_txt"] . " " . $strBet_on . "\n";

                foreach ($parlay as $parlayOne) {
                    $match_id = $parlayOne->match_id;
                    $bet_type = $parlayOne->betting_type_id;
                    $detailMatchOnBet = isset($detailMatchOnBetLst) ? json_decode($detailMatchOnBetLst->$match_id) : null;

                    $txtBet .= $parlayOne->betting_type . "\n";
                    $txtBet .= $parlayOne->betting_tournament . "\n";
                    $txtBet .= $parlayOne->betting_homeName . " vs " . $parlayOne->betting_awayName . "\n";

                    $txtBetTemp = "";
                    if(isset($parlayOne->betting_odd))
                        $txtBetTemp = "@".(isset($parlayOne->betting_odd) ? $parlayOne->betting_odd : "");
                    else{
                        if(isset($parlayOne->betting_k_id)){
                            switch ($parlayOne->betting_k_id) {
                                case 'od':
                                    $txtBetTemp = "Lẻ";
                                    break;
                                case 'ev':
                                    $txtBetTemp = "Chẵn";
                                    break;
                                default:
                                    $txtBetTemp = $parlayOne->betting_k_id;
                                    break;
                            }
                        }
                    }
                    $txtBet .= XoSoRecordHelpers::converBetOnParlay($parlayOne, $bet_data_lst->$match_id) . " " . "@" . $txtBetTemp . "\n";
                    if (isset($detailMatchOnBet))
                        $txtBet .= "Thời Gian Đặt Cược" . (isset($detailMatchOnBet) ? XoSoRecordHelpers::converTimeMatch($detailMatchOnBet) : "Hiệp 1 00:00") . "\n";
                    $txtBet .= "-------------" . "\n";
                }

                foreach ($bet_ons as $bet_on) {
                    if ($bet_on->money == 0) continue;
                    $txtBet .= "Đặt Cược:" . $bet_on->nameParlay . " " . number_format($bet_on->money) . "\n";
                }
            }
            $insertId = HistoryHelpers::Nf2Tele("7zball", "Bóng đá " . " " . $txtBet, $user->id, $value["bet_money"]);
        } catch (Exception $ex) {
            Log::info($ex);
        }
        return true;
    }

    public static function InsertHistoryBet($request, $usercreate,$ids="")
    {
        $money = 0;
        $totalNow = 0;
        $betNumber = "";
        for ($i = 0; $i < count($request->choices); $i++) {
            $totalNow += intval($request->choices[$i]['total']);
            $betNumber .= $request->choices[$i]['name'] . ",";
        }
        $gameRaw = GameHelpers::GetGameByCode($request->game_code);
        $insertId = HistoryHelpers::InsertHistory($gameRaw->location, $gameRaw->name . ' ' . $betNumber, $usercreate->id, $totalNow, null, $ids);
        return true;
    }

    public static function InsertHistoryQuickBet($text, $money, $usercreate, $text_cancel = null, $ids = "")
    {
        $insertId = HistoryHelpers::InsertHistory("Miền Bắc", $text, $usercreate->id, $money, $text_cancel, $ids);
        return true;
    }

    public static function getBotTokenByType($type){
        $token = '';
        switch ($type) {
            case 'agent_member_1':
                $token = '6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q';
                break;

            case 'agent':
                $token = '5863257778:AAHOs8X9Cjsr3IcUBQy5QSO1piG4wvU5FnQ';
                break;

            case 'agent_member_2':
                $token = '6625058071:AAEgdD1qZ033OWSR8nzNk4EXXh15XG0kl5o';
                break;
            
            case 'admin_super_master':
                $token = '6241857190:AAHCxFRIcZItEvFDnk7ntXAZx978Y5wykNg';
                break;

            case 'trolymb':
                $token = '6329319864:AAEaIGUAnzIlxP2lCpMEt5w4QOS4dQPLOn0';
                break;

            case 'quanlyso':
                $token = '6498493818:AAGzOgpykLtCEWhQrsNH1GYWLdyc37O4_bo';
                break;

            case 'nhantinmb':
                $token = '6690018393:AAG8W2f_upUTJOufNBFLa81xnA1YbBHXoi8';
                break;
            default:
                # code...
                break;
        }
        return $token;
    }
    public static function UpdateHistoryQuickBet($historyID,$text, $money, $usercreate, $text_cancel = null, $ids = "")
    {
        $history = History::where('id',$historyID)->first();
        $history->money = $money;
        $history->ids = $ids;
        $history->cancel = $text_cancel;
        $history->save();

        try {
            $tokenBot_member = "6690018393:AAG8W2f_upUTJOufNBFLa81xnA1YbBHXoi8";
            $tokenBot_agent = "5863257778:AAHOs8X9Cjsr3IcUBQy5QSO1piG4wvU5FnQ";
            $tokenBot_admin_super_master = "6241857190:AAHCxFRIcZItEvFDnk7ntXAZx978Y5wykNg";
            // Log::info("send tele");
            $user = $usercreate;
            // $user = User::where("id", $userid)->first();
            $txtMessage = $user->name . " (" . "Oke Tin " . $history->id_inday . ") :" . $text . ((isset($text_cancel) && $text_cancel!="" )? " (Hủy: ". $text_cancel .") " : ""). " " . number_format($money);
            // $user = User::where("id", $user->user_create)->first();
            $count = 0;
            while ($user->roleid != 1) {
                if (isset($user->chat_id)) {
                    $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
                    if ($user->active_noti_tele == 1)
                        NotifyHelpers::sendMessage($user->chat_id, $txtMessage, $token);
                }
                $user = User::where("id", $user->user_create)->first();
                $count++;
                if ($count > 10) break;
            }
        } catch (Exception $ex) {
            Log::info($ex);
        }

        return true;
    }

    public static function sendMessageToMembersTree($user,$txtMessage){
        try {
            // Log::info("send tele");
            $count = 0;
            while ($user->roleid != 1) {
                if (isset($user->chat_id)) {
                    $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
                    if ($user->active_noti_tele == 1)
                        NotifyHelpers::sendMessage($user->chat_id, $txtMessage, $token);
                }
                $user = User::where("id", $user->user_create)->first();
                $count++;
                if ($count > 10) break;
            }
        } catch (Exception $ex) {
            Log::info($ex);
        }
    }

    public static function sendMessageToMembersTreeAgent($user,$txtMessage){
        try {
            $user = User::where("id", $user->user_create)->first();
            if (isset($user->chat_id)) {
                $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
                if ($user->active_noti_tele == 1)
                    NotifyHelpers::sendMessage($user->chat_id, $txtMessage, $token);
            }
        } catch (Exception $ex) {
            Log::info($ex);
        }
    }

    public static function InsertHistory($type, $content, $userid, $money, $text_cancel = null, $ids = "",$saveHistory = true)
    {
        $tokenBot_agent_member = "6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q";
        $tokenBot_admin_super_master = "6241857190:AAHCxFRIcZItEvFDnk7ntXAZx978Y5wykNg";

        if ($saveHistory){
            $data = new History;
            $data->date = date("Y-m-d");
            $data->type = $type;
            $data->content = $content;
            $data->ids = $ids;
            $data->money = $money;
            $data->user_create = $userid;
            $data->cancel = $text_cancel;
            $data->id_inday = History::where("user_create",$userid)->where("date",date("Y-m-d"))->count() + 1;
            $data->save();

            try {
                // Log::info("send tele");
                $user = User::where("id", $userid)->first();
                $txtMessage = $user->name . " (" . date("H:i") . ") :" . $content . " " . ((isset($text_cancel) && $text_cancel!="") ? "(Hủy: ". $text_cancel .") " : "") . number_format($money);
                $user = User::where("id", $user->user_create)->first();
                $count = 0;
                while ($user->roleid != 1) {
                    if (isset($user->chat_id)) {
                        $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
                        if ($user->active_noti_tele == 1)
                            NotifyHelpers::sendMessage($user->chat_id, $txtMessage, $token);
                    }
                    $user = User::where("id", $user->user_create)->first();
                    $count++;
                    if ($count > 10) break;
                }
            } catch (Exception $ex) {
                Log::info($ex);
            }
        }
        return isset($data->id) ? $data->id : 0;
    }

    public static function Nf2Tele($type, $content, $userid, $money, $text_cancel = null)
    {
        $tokenBot_agent_member = "6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q";
        $tokenBot_agent = "6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q";
        $tokenBot_admin_super_master = "6241857190:AAHCxFRIcZItEvFDnk7ntXAZx978Y5wykNg";
        try {
            // Log::info("send tele");
            $user = User::where("id", $userid)->first();
            $txtMessage = $user->name . " (" . date("H:i") . ") :" . $content . "\n" . number_format($money);
            $user = User::where("id", $user->user_create)->first();
            $count = 0;
            while ($user->roleid != 1) {
                if (isset($user->chat_id)) {
                    $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
                    if ($user->active_noti_tele == 1)
                        NotifyHelpers::sendMessage($user->chat_id, $txtMessage, $token);
                }
                $user = User::where("id", $user->user_create)->first();
                $count++;
                if ($count > 10) break;
            }
        } catch (Exception $ex) {
            Log::info($ex);
        }
        return true;
    }

    public static function notification2User(){
        $users = User::where("roleid",6)->whereNotNull("chat_id")->get();
        // where("lock_price",1)->
        foreach($users as $user){
            try{
                static::notificationTeleWinlose($user,date("Y-m-d"));
                static::notificationTeleWinloseByDetailHistory($user,date("Y-m-d"));
            }catch(Exception $ex){
                Log::info($ex);
            }
        }
    }
    
    public static function notificationTeleWinloseByDetailHistory($user,$today){
        // date('Y-m-d', strtotime('-1 day'))
        $history = DB::table('history')
        ->where('date', $today)
        ->where('money', '>', 0)
        ->where('user_create', $user->id)
        ->select('*')
        ->orderBy('created_at', 'asc')->get();
        if (count($history) == 0) return;
        $data = [["Tin", "Điểm"]];
        $totalSumbet = 0;
        $totalSumwin = 0;
        // var_dump($history);
        echo count($history);
        $totalSumbet = 0;
        $totalpointBet = 0;
        $totalpointWin = 0;
        foreach($history as $key=>$item){
            if (!isset($item->ids)) continue;
            // echo $item->content . PHP_EOL;
            $ids = explode(',',$item->ids);
            var_dump($ids);

            $rs =
            DB::table('xoso_record')
            ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'location.name as location_name', 'location.id as location_id', 'games.short_name as game_name')
            ->orderBy('games.order', 'desc')
            ->where('isDelete', false)
            ->where('date', $today)
            ->where('games.location_id', 1)
            ->whereIn('xoso_record.id', $ids)
            ->where('user_id', $user->id)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->join('location', 'location.slug', '=', 'games.location_id')
            ->groupBy('games.short_name')
            ->get();

            // $rs =
            // DB::table('xoso_record')
            //     // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            //     // ->orderBy('sumbet', 'desc')
            //     ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            //     ->where('isDelete', 0)
            //     // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            //     // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            //     ->where('date', $today)
            //     ->whereIn('xoso_record.id', $ids)
            //     ->where('user_id', $user->id)
            //     ->select('xoso_record.*', 'games.short_name as game',DB::raw('(
            //         IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
            //         ) AS sumwin'))
            //     ->orderBy('xoso_record.id', 'asc')
            //     // ->groupBy('game_id')
            //     ->get();
                
            
            $totalSumwinTT = 0;
            $contentTin = "";
            $countI = 0;
            foreach ($rs as $recordC) {
                $totalSumbet += $recordC->sumbet;
                $location_name = $recordC->location_name;
                $rsTheloai =
                DB::table('xoso_record')
                // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                // ->orderBy('sumbet', 'desc')
                ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
                ->where('isDelete', 0)
                // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
                // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
                ->where('date', $today)
                ->whereIn('xoso_record.id', $ids)
                ->where('xoso_record.game_id', $recordC->game_id)
                ->where('user_id', $user->id)
                ->select('xoso_record.*', 'games.short_name as game')
                ->orderBy('created_at', 'des')
                // ->groupBy('game_id')
                ->get();

                $xosobotH = new XosobotHelpers(null,null);
                $pointBet = 0;
                $pointWin = 0;
                
                
                foreach ($rsTheloai as $record) {
                    $point = $record->total_bet_money / $record->exchange_rates / $xosobotH->Cal_Ank($record->game_id, $record->bet_number);
                    $pointBet += $point;
                    $totalpointBet += $point;
                    if ($record->total_win_money > 0){
                        $pointWin += $record->total_win_money / $record->odds / $xosobotH->Cal_Ank($record->game_id, $record->bet_number);
                        $totalpointWin += $record->total_win_money / $record->odds / $xosobotH->Cal_Ank($record->game_id, $record->bet_number);
                    }
                        
                }
                // array_push($data, [$recordC->game_name, number_format($pointBet) . " (" . number_format($pointWin) . ")", number_format($recordC->sumwin)]);
                $contentTin.= $recordC->game_name. " " . number_format($pointBet) . "(".number_format($pointWin) . ") ";
                $totalSumwinTT +=$recordC->sumwin;
                $totalSumwin +=$recordC->sumwin;
                if ($countI == 0)
                    array_push($data, ["tin ".($key+1), $recordC->game_name. " " . number_format($pointBet) . "(".number_format($pointWin) . ")"]);
                else
                    array_push($data, ["", $recordC->game_name. " " . number_format($pointBet) . "(".number_format($pointWin) . ")"]);
                $countI++;
            }
            // array_push($data, ["tin ".($key+1), $contentTin]);
        }
        
        array_push($data, ["Tổng", number_format($totalpointBet)."(".number_format($totalpointWin).")" ]);

        $mess = "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>" . "\n";
        $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
        $tokenBot_super_trolymb = $token;
        $chatid = $user->chat_id; //"5381486859";//
        NotifyHelpers::sendMessage($chatid, $mess, $tokenBot_super_trolymb);
    }

    public static function notificationTeleWinlose($user,$today){
        $rs =
        DB::table('xoso_record')
        ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
            IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
            ) AS sumwin'), 'location.name as location_name', 'location.id as location_id', 'games.short_name as game_name')
        ->orderBy('games.order', 'desc')
        ->where('isDelete', false)
        ->where('date', $today)
        ->where('games.location_id', 1)
        // ->where('date','<=',$endDate)
        // ->whereIn('game_id', [7,12,14])
        ->where('user_id', $user->id)
        ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
        ->join('location', 'location.slug', '=', 'games.location_id')
        ->groupBy('games.short_name')
        ->get();

        if (count($rs) == 0) return;
        $data = [["Loại", "Điểm", "Thắng thua"]];
        $totalSumbet = 0;
        $totalSumwin = 0;

        foreach ($rs as $recordC) {
            
            $totalSumbet += $recordC->sumbet;
            $totalSumwin += $recordC->sumwin;
            $location_name = $recordC->location_name;
            
            $rsTheloai =
            DB::table('xoso_record')
            // ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
            // ->orderBy('sumbet', 'desc')
            ->join('games', 'xoso_record.game_id', '=', 'games.game_code')
            ->where('isDelete', 0)
            // ->where('created_at','>=',date("Y-m-d",strtotime($newDate)) .' 00:00:00')
            // ->where('created_at','<',date("Y-m-d",strtotime('+1 day',strtotime($newDate))) .' 24:00:00')
            ->where('date', $today)
            ->where('xoso_record.game_id', $recordC->game_id)
            ->where('user_id', $user->id)
            ->select('xoso_record.*', 'games.short_name as game')
            ->orderBy('created_at', 'des')
            // ->groupBy('game_id')
            ->get();

            $xosobotH = new XosobotHelpers(null,null);
            $pointBet = 0;
            $pointWin = 0;
            foreach ($rsTheloai as $record) {
                $point = $record->total_bet_money / $record->exchange_rates / $xosobotH->Cal_Ank($record->game_id, $record->bet_number);
                $pointBet += $point;
                if ($record->total_win_money > 0)
                    $pointWin += $record->total_win_money / $record->odds / $xosobotH->Cal_Ank($record->game_id, $record->bet_number);
            }
            array_push($data, [$recordC->game_name, number_format($pointBet) . " (" . number_format($pointWin) . ")", number_format($recordC->sumwin)]);
        }
        array_push($data, ["Tổng $", number_format($totalSumbet), number_format($totalSumwin)]);
        $mess = "<pre>";
        $table = Tableify::new($data);
        $table = $table->seperator("")->belowHeaderCharacter("*")->headerCharacter("*")->right()->make();
        $table_data = $table->toArray();
        foreach ($table_data as $row) {
            $mess .= $row . "\n";
        }
        $mess .= "</pre>" . "\n";
        $token = HistoryHelpers::getBotTokenByType($user->bot_tele_type);
        $tokenBot_super_trolymb = $token;
        $chatid = $user->chat_id; //"5381486859";//
        NotifyHelpers::sendMessage($chatid, $mess, $tokenBot_super_trolymb);
    }

    public static function GetHistory($user, $now)
    {
        return History::where('user_create', $user->id)->where('date', $now)->where('is_done', 1)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function ActiveHistorySave($user_create, $user_target, $actionType, $valueC)
    {
        try {
            $user_create_role_name = XoSoRecordHelpers::GetRoleName($user_create->roleid);
            $user_target_role_name = XoSoRecordHelpers::GetRoleName($user_target->roleid);

            $content = "";
            if (Session::get('usersecondper') == 1) {
                $content = $user_create_role_name . " " . $user_create->name . " (Tài khoản phụ-" . Session::get('usersecondname') . ") " . $actionType . " " . ($user_target_role_name != $user_create_role_name ? $user_target_role_name . " " . $user_target->name : "");
            } else {
                $content = $user_create_role_name . " " . $user_create->name . " " . $actionType . " " . ($user_target_role_name != $user_create_role_name ? $user_target_role_name . " " . $user_target->name : "");
            }
            DB::table('active_history')->insert([
                [
                    'date' => date('Y-m-d'),
                    'type'    => $actionType,
                    'content' => $content,
                    'user_create' => $user_create->id,
                    'user_target' => $user_target->id,
                    'value' => $valueC,
                    'created_at' => new DateTime(),
                    'updated_at' => new DateTime()
                ]
            ]);
        } catch (Exception $ex) {
        }
        return true;
    }

    public static function GetSeflActiveHistory($user_create, $startDate, $endDate)
    {
        try {
            return DB::table('active_history')->where("user_create", $user_create->id)
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->get();
        } catch (Exception $ex) {
        }
        return [];
    }

    public static function GetTargetActiveHistory($user_target, $startDate, $endDate)
    {
        try {
            return DB::table('active_history')->where("user_target", $user_target->id)
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->get();
        } catch (Exception $ex) {
        }
        return [];
    }

    public static function GetAllActiveHistoryByUser($user, $startDate, $endDate)
    {
        try {
            return DB::table('active_history')
            ->where(function($query) use ($user)
            {
                $query->where("user_create", $user->id)
                        ->orWhere("user_target", $user->id);
            })
                ->where('date', '>=', $startDate)
                ->where('date', '<=', $endDate)
                ->get();
        } catch (Exception $ex) {
            throw $ex;
        }
        return [];
        
    }
}
