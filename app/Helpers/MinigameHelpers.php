<?php

namespace App\Helpers;

use App\Commands\PaymentLottery7zball;
use App\CustomerType_Game;
use DateTime;
use DateTimeZone;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Helpers\UserHelpers;
use App\history_7zBall_bet;
use App\history_minigame_bet;
use App\History_Saba_bet;
use Exception;
use GuzzleHttp\Client;
use \Queue;
use Illuminate\Support\Facades\Log;

class MinigameHelpers
{

    // info BBIN
    private static $ApiUrl = [
        // "default" => "http://soccer.test/api",
        "default" => "https://minigame.99luckey.com/api",
        "Login" => "https://minigame.99luckey.com/api"
    ];
    private static $vendor_id = "h5ycnl7k6u_";
    private static $OperatorId = "99luckeyprod_";

    private static $UpperName = "dluk79prod";
    private static $Website = "saba";
    private static $KeyB = [
        "CreateSession" => "75ZDXZ",
        "CreateMember" => "Ugct67",
        "Login" => "75ZDXZ",
        "Logout" => "9Ka2Es",
        "Transfer" => "XbVSHs0",
        "CheckTransfer" => "SB5OD",
        "TransferRecord" => "SB5OD",
        "BetRecord" => "MKaM4",
        "WagersRecordBy3" => "MKaM4",
        "CheckUsrBalance" => "PG8s7"
    ];

    private static $timeMaintain = [
        "10:40:00",
        "11:00:00"
    ];


    public static function CreateMember($username)
    {
        $limitMember = CustomerType_Game::where('game_id',8001)
                    ->where('created_user', User::where("name",$username)->first()->id)->first();

        $client = new Client();
        $res = $client->request('POST', static::$ApiUrl["default"] . '/CreateMember', [
            'headers' => ['Content-type: application/x-www-form-urlencoded'],
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
                'vendor_member_id' => static::$OperatorId . '_' . $username,
                'OperatorId' => static::$OperatorId . '_',
                'username' => static::$OperatorId . '_' . $username,
                'max_bet' => $limitMember->max_point,
                'max_match' => $limitMember->max_point_one,
                'max_win' => $limitMember->max_one,
                // 'maxtransfer' => 9999999999
            ],
        ]);
        // var_dump($res);
        $data = json_decode($res->getBody(), true);
        // print_r($data);
        if (!isset($data['error_code'])) { // khong dung dinh dang
            return false;
        }
        if ($data['error_code'] == 0) {
            return true;
        }
        return false;
    }

    public static function SetMemberBetSetting($username, $dataSetting)
    {
        try {
            $client = new Client();
            $betSetting = '[{"sport_type" : ' . ($dataSetting->game_id - 4000) . ', "min_bet":' . $dataSetting->odds .
                ', "max_bet":' . $dataSetting->max_point . ', "max_bet_per_match": ' . $dataSetting->max_point_one . ', "max_payout_per_match":' . $dataSetting->change_max_one . '}]';
            if ($dataSetting->game_id - 4000 == 161)
                $betSetting = '[{"sport_type" : ' . ($dataSetting->game_id - 4000) . ', "min_bet":' . $dataSetting->odds .
                    ', "max_bet":' . $dataSetting->max_point . ', "max_bet_per_ball": ' . $dataSetting->max_point_one . ', "max_bet_per_match":' . $dataSetting->change_max_one . '}]';
            $res = $client->request('POST', static::$ApiUrl["default"] . '/SetMemberBetSetting', [
                'timeout' => 20,
                'connect_timeout' => 15,
                'form_params' => [
                    'vendor_id' => static::$vendor_id,
                    'vendor_member_id' => static::$OperatorId . '_' . $username,
                    'bet_setting' => $betSetting
                ],
            ]);
            $data = json_decode($res->getBody(), true);
            // print_r($data);
            // Log::info($data);
            if (!isset($data['error_code'])) { // khong dung dinh dang
                return false;
            }
            if ($data['error_code'] == 0) {
                return true;
            }
        } catch (Exception $ex) {
            Log::info($ex->getMessage());
        }
        return false;
    }

    public static function LogoutAndRecallAllMember()
    {
        try {
            $AllUserBet = DB::table('history_transfer')
                ->select('username')
                ->where('created_date', '>', date("Y-m-d", strtotime("yesterday")) . " " . static::$timeMaintain[1])
                ->where('status', 0)
                ->groupBy('username')
                ->get();
            foreach ($AllUserBet as $value) {
                static::Logout($value->username);
                static::Recall($value->username, false);
            }
        } catch (\Exception $ex) {
            // catch code
            Log::info($ex->getMessage());
        }
    }

    public static function Login($username, $platform = 1)
    { //1: desktop 2: mobile 3: wap

        // if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
        //     return "<script>alert('Trò chơi bảo trì đến ". static::$timeMaintain[1] ."')</script>";
        // }

        static::CreateMember($username); // tao tai khoan truoc khi login
        $vendor_member = User::where("name",$username)->first();
        $limitMember = CustomerType_Game::where('game_id',8001)
                    ->where('created_user', $vendor_member->id)->first();

        // SabaHelpers::SetMemberBetSetting($username,$customerTypeOne);
        // \Log::info('SetMemberBetSetting UpdateCustomerTypeGameABCMAXPOINTV2');

        $client = new Client();
        $res = $client->request('POST', static::$ApiUrl["default"] . '/GetMinigameUrl', [
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
                'vendor_member_id' => static::$OperatorId . '_' . $username,
                'platform' => $platform,
                'max_bet' => $limitMember->max_point,
                'max_match' => $limitMember->max_point_one,
                'max_win' => $limitMember->change_max_one,
                'vendor_member_status' => $vendor_member->lock
            ],
        ]);
        $data = json_decode($res->getBody(), true);
        // var_dump($data);
        // print_r($data['error_code']);
        // if(!isset($data['error_code'])){ // khong dung dinh dang
        //     return false;
        // }
        return $data['url'];
    }

    public static function Logout($username)
    {
        try {
            $client = new Client();
            $res = $client->request('POST', static::$ApiUrl["default"] . '/KickUser', [
                'timeout' => 20,
                'connect_timeout' => 15,
                'form_params' => [
                    'vendor_id' => static::$vendor_id,
                    'vendor_member_id' => static::$OperatorId . '_' . $username,
                ],
            ]);
            $data = json_decode($res->getBody(), true);
            // print_r($data);
            return true;
        } catch (\Exception $ex) {
            // catch code
            Log::info($ex->getMessage());
        }
        return false;
    }

    public static function CheckUsrBalance($username)
    {
        // return static::getbalance($username);

        $client = new Client();
        $res = $client->request('POST',static::$ApiUrl["default"].'/CheckUsrBalance', [
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
                'vendor_member_id' => static::$OperatorId.'_'.$username,
                // 'wallet_id' => 1
                    // Wallet ID
                    // 1 : Sportsbook
                    // 5 : AG
            ],
        ]);
        $data = json_decode($res->getBody(), true);
        // print_r($data);
        // if(!isset($data['error_code'])){ // khong dung dinh dang
        //     return false;
        // }
        // if($data['error_code'] == 0){
            return $data;
        // }
        // return false;
    }

    public static function ReCall($username)
    { // action IN or OUT
        // return true;
        // if($force){
        //     if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
        //         return "Đang trong thời gian bảo trì";
        //     }
        // }

        $user = User::where('name', $username)->first();

        // $a =  CustomerType_Game::where('code_type',$user->customer_type)
        //         ->where('game_id',4001)
        //         ->where('created_user',$user->id)->first();

        $userBalance = (int)(static::CheckUsrBalance($username));
        static::Transfer($username,$userBalance,"OUT");
        $user->remain += $userBalance;
        $user->save();
        return true;
    }

    public static function Transfer($username, $money, $action = "IN", $force = true)
    { // action IN or OUT
        // return true;
        // if($force){
        //     if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
        //         return "Đang trong thời gian bảo trì";
        //     }
        // }

        $user = User::where('name', $username)->first();

        // $a =  CustomerType_Game::where('code_type', $user->customer_type)
        //     ->where('game_id', 4001)
        //     ->where('created_user', $user->id)->first();
        // $moneyr = $user->remain;
        // // $user->save();
        // if ($moneyr < $money && $action == "IN") {
        //     return "Không đủ số dư";
        // }
        // $balance = static::CheckUsrBalance($username);
        // // $balance = $user->remain_saba;
        // if ($action == "IN") {
        //     if ($balance + $money > $a->max_point) {
        //         return "Vượt giới hạn chuyển " . number_format($a->max_point);
        //     }
        // } else {
        //     if ($balance < $money) {
        //         return "Vượt giới hạn chuyển " . number_format($balance);
        //     }
        // }

        $dt = static::getDateStr();

        // static::CreateMember($username); // tao tai khoan truoc khi login
        // website+ username + KeyB + YYYYMMDD


        $remitno = DB::table('history_transfer')->insertGetId([
            'username' =>  $username,
            'amount' =>  $money,
            'transfer_type' => $action == "IN" ? 1 : 2,
            'status' =>  0,
            'msg' =>  'Saba',
            'created_date' =>  date('Y-m-d H:i:s'),
        ]);

        // $key = "1111" . md5( static::$Website . $username. $remitno ) . "1111";

        $client = new Client();
        $res = $client->request('POST',static::$ApiUrl["default"].'/Transfer', [
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
                'vendor_member_id' => static::$OperatorId.'_'.$username,
                'money' => $money,
                'mode' => $action,
            ],
        ]);
        // $data = json_decode($res->getBody(), true);
        // // print_r($data['error_code']);
        // if(!isset($data['error_code'])){ // khong dung dinh dang
        //     return false;
        // }


        // DB::table('history_transfer')->where('id', $remitno)->update([
        //     'status' =>  $data->data->Code,
        //     'msg' =>  $data->data->Message
        // ]);


        $user = User::where('name', $username)->first();

        if ($action == "IN") {
            $user->remain -= $money;
            $user->consumer += $money;
            $user->remain_saba += ($money);
        } else {
            $user->remain += $money;
            $user->consumer -= $money;
            $user->remain_saba -= ($money);
        }
        $user->save();
        return true;
    }

    //HElP

    public static function getDateStr($format = 'Ymd', $minutesToSub = 0)
    {
        $date = new DateTime($minutesToSub == 0 ? "now" : $minutesToSub . " minutes ago", new DateTimeZone('America/New_York'));

        return $date->format($format);
        // return "";
    }

    public static function converDate($date)
    {
        try {
            $date = new DateTime($date, new DateTimeZone('GMT+7'));
            $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
            return $date->format('Y-m-d H:i:s');
        } catch (Exception $ex) {
            return "";
        }

        return "";
    }

    public static function converStatus($status)
    {
        try {
            switch ($status) {
                case 'won':
                    return 'Thắng';
                    break;
                case 'lose':
                    return 'Thua';
                    break;
                case 'half won':
                    return 'Thắng nửa';
                    break;
                case 'half lose':
                    return 'Thua nửa';
                    break;

                default:
                    return '';
                    break;
            }
        } catch (Exception $ex) {
            return "";
        }

        return "";
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
    // public static function CurlLoginBBIN($url){
    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    //     curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    //     // curl_setopt($ch, CURLOPT_HEADER, true);
    //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //     $headers = array();
    //     $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    //     $headers[] = 'Accept-Language: vi,en;q=0.9,en-GB;q=0.8,en-US;q=0.7';
    //     $headers[] = 'Cache-Control: no-cache';
    //     $headers[] = 'Pragma: no-cache';
    //     $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
    //     $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
    //     $headers[] = 'Sec-Fetch-Dest: iframe';
    //     $headers[] = 'Sec-Fetch-Mode: navigate';
    //     $headers[] = 'Sec-Fetch-Site: same-site';
    //     $headers[] = 'Upgrade-Insecure-Requests: 1';
    //     $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.81 Safari/537.36 Edg/104.0.1293.54';
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    //     $result = curl_exec($ch);
    //     if (curl_errno($ch)) {
    //         return "<center>Đã xảy ra lỗi | -1001</center>";
    //     }
    //     if (static::isJson($result)) {
    //         return "<center>Đã xảy ra lỗi | -1002</center>";
    //     }
    //     return $result;
    // }

    public static function CurlSaba($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
        $headers[] = 'Accept-Language: vi,en;q=0.9,en-GB;q=0.8,en-US;q=0.7';
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Pragma: no-cache';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Sec-Ch-Ua-Platform: \"Windows\"';
        $headers[] = 'Sec-Fetch-Dest: iframe';
        $headers[] = 'Sec-Fetch-Mode: navigate';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Upgrade-Insecure-Requests: 1';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.81 Safari/537.36 Edg/104.0.1293.54';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        print_r($result);
        if (curl_errno($ch)) {
            return "error";
        }
        curl_close($ch);
        return $result;
    }

    public static function isJson($str)
    {
        $json = json_decode($str);
        return $json && $str != $json;
    }

    public static function getbalance($userId)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $userId);
        $user = UserHelpers::GetUserByUserName($userId);
        return $user[0]->remain_saba;
    }

    public static function placebet($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        $arr = [
            'refId' => $message['refId'],
            'username' =>  $user->name,
            'gametype' =>  4000 + $message['sportType'],
            'betamount' => $message['actualAmount'],
            'com' =>  0,
            'payoff' =>  0,
            'jsoninfo' =>  json_encode($message),
            'createdate' =>  static::converDate($message['updateTime']),
        ];
        $user->remain_saba -= $message['actualAmount'];
        $user->save();
        return ['refId' => $message['refId'], 'licenseeTxId' => DB::table('history_saba_bet')->insertGetId($arr)];
    }

    public static function confirmbet($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        $totalAmount = 0;
        foreach ($message['txns'] as $txns) {
            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsonconfirm' =>  json_encode($txns),
            ]);
            $totalAmount += ($txns['creditAmount'] - $txns['debitAmount']);
        }
        $user->remain_saba += $totalAmount;
        return $totalAmount;
    }

    public static function settle($message)
    {
        $totalAmount = 0;
        foreach ($message['txns'] as $txns) {
            $userId = str_replace(static::$OperatorId . '_', '', $txns['userId']);
            $user = UserHelpers::GetUserByUserName($userId)[0];
            $totalAmount = 0;
            $recordSABA = History_Saba_bet::where('refId', $txns['refId'])->first();
            $recordSABA->jsonsettle = json_encode($txns);
            $recordSABA->status = $txns['status'];
            // $recordSABA->payout = $txns['payout'];// - $recordSABA->betamount;
            $recordSABA->payout = $txns['payout'] - $recordSABA->betamount;

            // $totalAmount += ($txns['creditAmount'] - $txns['debitAmount']) ;
            // if ($totalAmount == 0 && $txns['status'] == 'lose')

            $recordSABA->save();

            $user->remain_saba += $txns['payout'];
            $user->save();
        }
        return $totalAmount;
    }

    public static function unsettle($message)
    {
        $totalAmount = 0;
        foreach ($message['txns'] as $txns) {
            // $userId = str_replace(static::$OperatorId.'_','',$txns['userId']);
            // $user = UserHelpers::GetUserByUserName($userId)[0];
            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsonsettle' =>  null,
                'status' => 'reject',
                'payout' => null,
            ]);
            $totalAmount += $txns['creditAmount'];
        }

        return $totalAmount;
    }

    public static function cancelbet($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        $totalAmount = 0;
        foreach ($message['txns'] as $txns) {
            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsoncancel' =>  json_encode($txns),
                'status' => 'reject',
            ]);
            $totalAmount += $txns['creditAmount'];
        }

        return $totalAmount;
    }

    public static function placebetparlay($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];

        $dataReturn = [];
        foreach ($message['txns'] as $txns) {

            $arr = [
                'refId' => $txns['refId'],
                'username' =>  $user->name,
                'gametype' =>  5001, //$message->sportType,
                'betamount' => $txns['betAmount'],
                'com' =>  0,
                'payoff' =>  0,
                'jsoninfo' =>  json_encode($message),
                'createdate' =>  static::converDate($message['betTime']),
            ];
            $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            $dR = [
                "refId" => $txns['refId'],
                "licenseeTxId" => $idSave . '__' . $txns['refId']
            ];
            array_push($dataReturn, $dR);
        }
        return $dataReturn;
    }

    public static function confirmbetparlay($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];

        $dataReturn = [];
        $totalAmount = 0;
        foreach ($message['txns'] as $txns) {

            // $arr = [
            //     'refId' => $txns['refId'],
            //     'username' =>  $user->name,
            //     'gametype' =>  5002,//$message->sportType,
            //     // 'betamount' => $message['totalBetAmount'],
            //     'com' =>  0,
            //     'payoff' =>  0,
            //     'jsoninfo' =>  json_encode($message),
            //     'createdate' =>  static::converDate($message['updateTime']),
            // ];
            // $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsonconfirm' =>  json_encode($message),
            ]);

            // $dR = [
            //         "refId"=>$txns['refId'],
            //         "licenseeTxId"=>$idSave.'__'.$txns['refId']
            // ];
            // $totalAmount += $txns['actualAmount'];
            // array_push($dataReturn,$dR);
        }

        // DB::table('history_saba_bet')->where('id', $idSave)->update([
        //     'betamount' =>  $totalAmount,
        // ]);

        return true;
    }

    public static function placebet3rd($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];

        $dataReturn = [];
        foreach ($message['ticketList'] as $txns) {

            $arr = [
                'refId' => $txns['refId'],
                'username' =>  $user->name,
                'gametype' =>  4000 + $message['productId'], //$message->sportType,
                'betamount' => $txns['betAmount'],
                'com' =>  0,
                'payoff' =>  0,
                'jsoninfo' =>  json_encode($message),
                'createdate' =>  static::converDate($message['betTime']),
            ];
            $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            $dR = [
                "refId" => $txns['refId'],
                "licenseeTxId" => $idSave . '__' . $txns['refId']
            ];
            array_push($dataReturn, $dR);
        }
        return $dataReturn;
    }

    public static function confirmbet3rd($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];

        $dataReturn = [];
        $totalAmount = 0;
        foreach ($message['txns'] as $txns) {

            // $arr = [
            //     'refId' => $txns['refId'],
            //     'username' =>  $user->name,
            //     'gametype' =>  5002,//$message->sportType,
            //     // 'betamount' => $message['totalBetAmount'],
            //     'com' =>  0,
            //     'payoff' =>  0,
            //     'jsoninfo' =>  json_encode($message),
            //     'createdate' =>  static::converDate($message['updateTime']),
            // ];
            // $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsonconfirm' =>  json_encode($message),
            ]);

            // $dR = [
            //         "refId"=>$txns['refId'],
            //         "licenseeTxId"=>$idSave.'__'.$txns['refId']
            // ];
            // $totalAmount += $txns['actualAmount'];
            // array_push($dataReturn,$dR);
        }

        // DB::table('history_saba_bet')->where('id', $idSave)->update([
        //     'betamount' =>  $totalAmount,
        // ]);

        return true;
    }

    public static function placebetent($message)
    {
        $userId = str_replace(static::$OperatorId . '_', '', $message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];

        // $arr = [
        //     'refId' => $message['action'].$message['userId'].$message['betTime'].$message['productId'].$message['gameId'],
        //     'username' =>  $user->name,
        //     'gametype' =>  90005,//$message->sportType,
        //     'betamount' => $message['debitAmount'],
        //     'com' =>  0,
        //     'payoff' =>  0,
        //     'jsoninfo' =>  json_encode($message),
        //     'createdate' =>  static::converDate($message['betTime']),
        // ];

        // $idSave = DB::table('history_saba_bet')->insertGetId($arr);
        $dataReturn = [];
        $totalAmount = 0;
        foreach ($message['ticketList'] as $ticketList) {
            $arr = [
                'refId' => $ticketList['refId'], //$message['action'].$message['userId'].$message['betTime'].$message['productId'].$message['gameId'],
                'username' =>  $user->name,
                'gametype' =>  4000 + $message['productId'], //$message->sportType,
                'betamount' => $ticketList['actualStake'],
                'com' =>  0,
                'payoff' =>  0,
                'jsoninfo' =>  json_encode($message),
                'createdate' =>  static::converDate($message['betTime']),
            ];

            $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            $dR = [
                "refId" => $ticketList['refId'],
                "licenseeTxId" => $idSave . '__' . $ticketList['refId']
            ];
            // $totalAmount += $ticketList['actualAmount'];
            array_push($dataReturn, $dR);
        }

        // DB::table('history_saba_bet')->where('id', $idSave)->update([
        //     'betamount' =>  $totalAmount,
        // ]);
        return $dataReturn;
    }

    public static function settleent($message)
    {
        $totalAmount = 0;
        // foreach($message['txns'] as $txns)
        {
            // $userId = str_replace(static::$OperatorId.'_','',$txns['userId']);
            // $user = UserHelpers::GetUserByUserName($userId)[0];
            DB::table('history_saba_bet')->where('refId', $message['refId'])->update([
                'jsonsettle' =>  json_encode($message),
                'status' => $message['status'],
                'payout' => $message['winlostAmount'],
            ]);
            $totalAmount += $message['winlostAmount'];
        }

        return $totalAmount;
    }

    public static function cancelbetent($message)
    {
        $totalAmount = 0;
        // foreach($message['txns'] as $txns)
        {
            // $userId = str_replace(static::$OperatorId.'_','',$txns['userId']);
            // $user = UserHelpers::GetUserByUserName($userId)[0];
            DB::table('history_saba_bet')->where('refId', $message['refId'])->update([
                'jsoncancel' =>  json_encode($message),
                // 'status' => $message['status'],
                // 'payout' => $message['winlostAmount'],
            ]);
            $totalAmount += $message['winlostAmount'];
        }

        return $totalAmount;
    }

    public static function getticketinfo($message)
    {
        return DB::table('history_saba_bet')->where('refId', $message['refId'])->first();
    }

    public static function GetHistoryLoop()
    { // nho phan page limit
        // Log::info("GetHistoryLoop");
        $client = new Client();
        $res = $client->request('POST', static::$ApiUrl["default"] . '/WagersRecord', [
            'headers' => ['Content-type: application/x-www-form-urlencoded'],
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
            ],
        ]);

        $data = json_decode($res->getBody(), true);

        $arr = [];
        $Loop = $data;
        
        foreach ($Loop as $value) {
            try {
                // var_dump($value);
                // echo $value["bet_id"];
                // echo str_replace(static::$OperatorId."_","",$value["userName"]);
                // echo static::converGametype($value["bet_type"]);
                // echo $value["bet_money"];
                // echo $value["com"];
                // echo $value["pay_money"];
                // echo json_encode($value);
                // echo $value["created_at"];

                $history = history_minigame_bet::updateOrCreate(
                    [
                        'id' => $value["id"],
                        'username' =>  str_replace(static::$OperatorId."_","",$value["userName"])
                    ],
                    [
                        'gametype' =>  8000 + $value["game_id"],
                        'betamount' => $value["bet_money"],
                        // 'com' =>  0,
                        'payoff' =>  isset($value["pay_money"]) ? $value["pay_money"] : 0,
                        'paid' => $value["paid"] != null ? $value["paid"] : 0,
                        'jsoninfo' =>  json_encode($value),
                        'date' =>  date("Y-m-d", strtotime($value["created_at"])),
                        'createdate' =>  $value["created_at"]
                    ]
                );

                if ($value["paid"] == 1)
                    // Queue::pushOn('low10',new PaymentLottery7zball(history_minigame_bet::where("id",$value["id"])->first()));
                    XoSoRecordHelpers::PaymentLottery7zball(history_minigame_bet::where("id",$value["id"])->first());
                // else
                    // HistoryHelpers::InsertHistory7zball($value,str_replace(static::$OperatorId."_","",$value["userName"]));
                // var_dump($history);
                // array_push($arr, [
                //     'id' => $value["bet_id"],
                //     'username' =>  $value["userName"],
                //     'gametype' =>  static::converGametype($value["bet_type"]),
                //     'betamount' => $value["bet_money"],
                //     'com' =>  $value["com"],
                //     'payoff' =>  $value["pay_money"],
                //     'jsoninfo' =>  json_encode($value),
                //     'createdate' =>  $value["created_at"],
                //     //static::converDate($value->created_at),
                // ]);
            } catch (Exception $ex) {
                echo ($ex->getMessage() . " " . $ex->getFile() . " " . $ex->getLine());
            }
            // break;
        }
        // DB::table('history_7zball_bet')->insert($arr);
        // static::insertOrUpdate($arr,"history_7zball_bet");
        // var_dump($arr);
    }

    public static function insertOrUpdate(array $rows, $table)
    {
        // $table = DB::getTablePrefix().with(new self)->getTable();


        $first = reset($rows);

        $columns = implode(
            ',',
            array_map(function ($value) {
                return "$value";
            }, array_keys($first))
        );

        $values = implode(
            ',',
            array_map(function ($row) {
                return '(' . implode(
                    ',',
                    array_map(function ($value) {
                        return '"' . str_replace('"', '""', $value) . '"';
                    }, $row)
                ) . ')';
            }, $rows)
        );

        $updates = implode(
            ',',
        array_map(function ($value) {
                return "$value = VALUES($value)";
            }, array_keys($first))
        );

        $sql = "INSERT INTO {$table}({$columns}) VALUES {$values} ON DUPLICATE KEY UPDATE {$updates}";

        return DB::statement($sql);
    }

    public static function convertGametype($betting_choice,$game_code)
    {
        if ($game_code == 8001) return static::convertBigSmall($betting_choice);
        if ($game_code == 8002) return static::convertDragonTiger($betting_choice);
        if ($game_code == 8003) return static::convertBetXocdia($betting_choice);
        if ($game_code == 8004) return static::convertBetBaucua($betting_choice);
        if ($game_code == 8005) return static::convertBetRoulette($betting_choice);
        if ($game_code == 8006) return static::convertBetBaccarat($betting_choice);
        return "";
    }

    public static function convertBetBaccarat($betting_choice)
    {
        $betting_game_name = "Baccarat";
        $betting_odd = 2.0;
        switch ($betting_choice) {
            case "PLAYER":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Người chơi";
                $betting_odd = 2.0;
                break;
            case "BANKER":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Nhà cái";
                $betting_odd = 1.95;
                break;
            case "TIE":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Hoà";
                $betting_odd = 9.0;
                break;
            case "NUM_3":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "3";
                $betting_odd = 12.0;
                break;
            case "NORMAL_BIG":
                $betting_type_txt = "Số lá bài";
                $betting_name_txt = "Tài";
                $betting_odd = 1.54;
                break;
            case "NORMAL_SMALL":
                $betting_type_txt = "Số là bài";
                $betting_name_txt = "Xỉu";
                $betting_odd = 2.5;
                break;
            case "TOTAL_BIG":
                $betting_type_txt = "Tổng điểm player và banker";
                $betting_name_txt = "Tài";
                $betting_odd = 1.95;
                break;
            case "TOTAL_SMALL":
                $betting_type_txt = "Tổng điểm player và banker";
                $betting_name_txt = "Xỉu";
                $betting_odd = 2.25;
                break;
            case "TOTAL_EVEN":
                $betting_type_txt = "Tổng điểm player và banker";
                $betting_name_txt = "Chẵn";
                $betting_odd = 1.95;
                break;
            case "TOTAL_ODD":
                $betting_type_txt = "Tổng điểm player và banker";
                $betting_name_txt = "Lẻ";
                $betting_odd = 1.95;
                break;

            default:
                $betting_type_txt = "Kết quả";
                $betting_name_txt = $betting_choice;
                $betting_odd = 2.0;
                break;
        }
        return $betting_type_txt . " - " . $betting_name_txt;
    }

    public static function convertBetRoulette($betting_choice)
    {
        $betting_game_name = "Roulette";
        $betting_odd = 2.0;
        switch ($betting_choice) {
            case "NUM_0":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "0";
                $betting_odd = 12.0;
                break;
            case "NUM_1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "1";
                $betting_odd = 12.0;
                break;
            case "NUM_2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2";
                $betting_odd = 12.0;
                break;
            case "NUM_3":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "3";
                $betting_odd = 12.0;
                break;
            case "NUM_4":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "4";
                $betting_odd = 12.0;
                break;
            case "NUM_5":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "5";
                $betting_odd = 12.0;
                break;
            case "NUM_6":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "6";
                $betting_odd = 12.0;
                break;
            case "NUM_7":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "7";
                $betting_odd = 12.0;
                break;
            case "NUM_8":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "8";
                $betting_odd = 12.0;
                break;
            case "NUM_9":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "9";
                $betting_odd = 12.0;
                break;
            case "NUM_X_10":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "10";
                $betting_odd = 12.0;
                break;
            case "NUM_X_11":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "11";
                $betting_odd = 12.0;
                break;
            case "NUM_X_12":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "12";
                $betting_odd = 12.0;
                break;
            case "_3_6_9_12":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "3,6,9,12";
                $betting_odd = 3.0;
                break;
            case "_2_5_8_11":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2,5,8,11";
                $betting_odd = 3.0;
                break;
            case "_1_4_7_10":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "1,4,7,10";
                $betting_odd = 3.0;
                break;
            case "_1_TO_6":
            case "SMALL":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "1~6";
                $betting_odd = 2.0;
                break;
            case "_1_TO_4":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "1~4";
                $betting_odd = 2.0;
                break;
            case "_7_TO_12":
            case "BIG":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "7~12";
                $betting_odd = 2.0;
                break;
            case "_5_TO_8":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2~8";
                $betting_odd = 2.0;
                break;
            case "_9_TO_12":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "9~12";
                $betting_odd = 2.0;
                break;
            case "EVEN":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Chẵn";
                $betting_odd = 2.0;
                break;
            case "ODD":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Lẻ";
                $betting_odd = 2.0;
                break;
            case "RED":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Đỏ";
                $betting_odd = 2.0;
                break;
            case "BLACK":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Đen";
                $betting_odd = 2.0;
                break;

            default:
                $betting_type_txt = "Kết quả";
                $betting_name_txt = $betting_choice;
                $betting_odd = 2.0;
                break;
        }
        return $betting_type_txt . " - " . $betting_name_txt;
    }

    public static function convertBetBaucua($betting_choice)
    {
        $betting_game_name = "Bầu cua";
        switch ($betting_choice) {
            case "GOURD_X1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Bầu";
                $betting_odd = 2.0;
                break;
            case "FISH_X1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Cá";
                $betting_odd = 2.0;
                break;
            case "DEER_X1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Hươu";
                $betting_odd = 2.0;
                break;
            case "CRAB_X1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Cua";
                $betting_odd = 2.0;
                break;
            case "CHICKEN_X1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Gà";
                $betting_odd = 2.0;
                break;
            case "SHRIMP_X1":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Tôm";
                $betting_odd = 2.0;
                break;
            case "GOURD_X2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Bầu";
                $betting_odd = 9.0;
                break;
            case "FISH_X2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Cá";
                $betting_odd = 9.0;
                break;
            case "DEER_X2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Hươu";
                $betting_odd = 9.0;
                break;
            case "CRAB_X2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Cua";
                $betting_odd = 9.0;
                break;
            case "CHICKEN_X2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Gà";
                $betting_odd = 9.0;
                break;
            case "SHRIMP_X2":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Tôm";
                $betting_odd = 9.0;
                break;
            case "ANY_X3":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Mọi cược ba";
                $betting_odd = 31.0;
                break;
            default:
                $betting_type_txt = $betting_choice;
                break;
        }

        return $betting_type_txt . " - " . $betting_name_txt;
    }

    public static function convertBetXocdia($betting_choice)
    {
        $betting_game_name = "Xóc đĩa";
        $betting_type_txt = "";
        $betting_name_txt = "";
        switch ($betting_choice) {
            case "WHITE_X4":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "4 Trắng";
                break;
            case "WHITE_X3":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "3 Trắng";
                break;
            case "DOUBLE_WHITE":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Trắng";
                break;
            case "DOUBLE_RED":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "2 Đỏ";
                break;
            case "RED_X4":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "4 Đỏ";
                break;
            case "RED_X3":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "3 Đỏ";
                break;
            case "FIRST_RED":
                $betting_type_txt = "Nút đầu tiên đỏ/trắng";
                $betting_name_txt = "Đỏ";
                $betting_odd = 1.95;
                break;
            case "FIRST_WHITE":
                $betting_type_txt = "Nút đầu tiên đỏ/trắng";
                $betting_name_txt = "Trắng";
                $betting_odd = 1.95;
                break;
            case "SECOND_RED":
                $betting_type_txt = "Nút thứ 2 đỏ/trắng";
                $betting_name_txt = "Đỏ";
                $betting_odd = 1.95;
                break;
            case "SECOND_WHITE":
                $betting_type_txt = "Nút thứ 2 đỏ/trắng";
                $betting_name_txt = "Trắng";
                $betting_odd = 1.95;
                break;
            case "THREE_RED":
                $betting_type_txt = "Nút thứ 3 đỏ/trắng";
                $betting_name_txt = "Đỏ";
                $betting_odd = 1.95;
                break;
            case "THREE_WHITE":
                $betting_type_txt = "Nút thứ 3 đỏ/trắng";
                $betting_name_txt = "Trắng";
                $betting_odd = 1.95;
                break;
            case "FOUR_WHITE":
                $betting_type_txt = "Nút cuối cùng đỏ/trắng";
                $betting_name_txt = "Trắng";
                $betting_odd = 1.95;
                break;
            case "FOUR_RED":
                $betting_type_txt = "Nút cuối cùng đỏ/trắng";
                $betting_name_txt = "Đỏ";
                $betting_odd = 1.95;
                break;
            case "ODD":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Lẻ";
                break;
            case "EVEN":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Chẵn";
                break;
            default:
                $betting_name_txt = $betting_choice;
                break;
        }

        return $betting_type_txt . " - " . $betting_name_txt;
    }
    public static function convertBigSmall($betting_choice)
    {
        $betting_type_txt = "";
        $betting_name_txt = "";
        
        switch ($betting_choice) {
            case "ODD":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Lẻ";
                break;
            case "EVEN":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Chẵn";
                break;
            case "BIG":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Tài";
                break;
            case "SMALL":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Xỉu";
                break;
            case "NUM_1":
                $betting_type_txt = "Cược số";
                $betting_name_txt = "Số (1)";
                break;
            case "NUM_2":
                $betting_type_txt = "Cược số";
                $betting_name_txt = "Số (2)";
                break;
            case "NUM_3":
                $betting_type_txt = "Cược số";
                $betting_name_txt = "Số (3)";
                break;
            case "NUM_4":
                $betting_type_txt = "Cược số";
                $betting_name_txt = "Số (4)";
                break;
            case "NUM_5":
                $betting_type_txt = "Cược số";
                $betting_name_txt = "Số (5)";
                break;
            case "NUM_6":
                $betting_type_txt = "Cược số";
                $betting_name_txt = "Số (6)";
                break;
            case "TOTAL_4":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (4)";
                break;
            case "TOTAL_5":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (5)";
                break;
            case "TOTAL_6":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (6)";
                break;
            case "TOTAL_7":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (7)";
                break;
            case "TOTAL_8":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (8)";
                break;
            case "TOTAL_9":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (9)";
                break;
            case "TOTAL_10":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (10)";
                break;
            case "TOTAL_11":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (11)";
                break;
            case "TOTAL_12":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (12)";
                break;
            case "TOTAL_13":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (13)";
                break;
            case "TOTAL_14":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (14)";
                break;
            case "TOTAL_15":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (15)";
                break;
            case "TOTAL_16":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (16)";
                break;
            case "TOTAL_17":
                $betting_type_txt = "Tổng riêng";
                $betting_name_txt = "Tổng (17)";
                break;
            default:
                $betting_type_txt = $betting_choice;
                break;
        }
        return $betting_type_txt . " - " . $betting_name_txt;
    }
    
    public static function convertDragonTiger($betting_choice)
    {
        $betting_game_name = "Rồng Hổ";
        $betting_type_txt = "";
        $betting_name_txt = "";
        switch ($betting_choice) {
            case "DRAGON_SINGLE":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Rồng";
                break;
            case "DRAW":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Hoà";
                break;
            case "TIGER_SINGLE":
                $betting_type_txt = "Kết quả";
                $betting_name_txt = "Hổ";
                break;
            case "DRAGON_BIG":
                $betting_type_txt = "Rồng";
                $betting_name_txt = "Tài";
                break;
            case "DRAGON_SMALL":
                $betting_type_txt = "Rồng";
                $betting_name_txt = "Xỉu";
                break;
            case "DRAGON_RED":
                $betting_type_txt = "Rồng";
                $betting_name_txt = "Đỏ";
                break;
            case "DRAGON_BLACK":
                $betting_type_txt = "Rồng";
                $betting_name_txt = "Đen";
                break;
            case "DRAGON_EVEN":
                $betting_type_txt = "Rồng";
                $betting_name_txt = "Chẵn";
                break;
            case "DRAGON_ODD":
                $betting_type_txt = "Rồng";
                $betting_name_txt = "Lẻ";
                break;

            case "TIGER_BIG":
                $betting_type_txt = "Hổ";
                $betting_name_txt = "Tài";
                break;
            case "TIGER_SMALL":
                $betting_type_txt = "Hổ";
                $betting_name_txt = "Xỉu";
                break;
            case "TIGER_RED":
                $betting_type_txt = "Hổ";
                $betting_name_txt = "Đỏ";
                break;
            case "TIGER_BLACK":
                $betting_type_txt = "Hổ";
                $betting_name_txt = "Đen";
                break;
            case "TIGER_EVEN":
                $betting_type_txt = "Hổ";
                $betting_name_txt = "Chẵn";
                break;
            case "TIGER_ODD":
                $betting_type_txt = "Hổ";
                $betting_name_txt = "Lẻ";
                break;

            default:
                $betting_type_txt = $betting_choice;
                break;
        }
        return $betting_type_txt . " - " . $betting_name_txt;
    }
}
