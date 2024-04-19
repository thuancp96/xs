<?php

namespace App\Helpers;

use App\CustomerType_Game;
use DateTime;
use DateTimeZone;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Helpers\UserHelpers;
use App\History_Saba_bet;
use Exception;
use GuzzleHttp\Client;
use \Queue;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\map;

class SabaHelpers
{

    // info BBIN
    private static $ApiUrl = [
        "default" => "http://c6a8api.bw6688.com/api",
        "Login" => "http://888.psikygpp.com/app/WebService/JSON/display.php"
    ]; 
    private static $vendor_id = "h5ycnl7k6u_"; 
    private static $OperatorId = "luk02prod_"; 

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

    
    public static function CreateMember($username) {
        $client = new Client();
        $res = $client->request('POST',static::$ApiUrl["default"].'/CreateMember', [
            'headers' => ['Content-type: application/x-www-form-urlencoded'],
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
                'vendor_member_id' => static::$OperatorId.'_'.$username,
                'OperatorId' => static::$OperatorId.'_',
                'username' => static::$OperatorId.'_'.$username,
                'oddstype' => 2,
                'currency' => 51,
                'mintransfer' => 0,
                'maxtransfer' => 9999999999
            ],
        ]);
        $data = json_decode($res->getBody(), true);
        // print_r($data);
        if(!isset($data['error_code'])){ // khong dung dinh dang
            return false;
        }
        if($data['error_code'] == 0){
            return true;
        }
        return false;
    }

    public static function SetMemberBetSetting($username,$dataSetting) {
        try{
            $client = new Client();
            $betSetting = '[{"sport_type" : '.($dataSetting->game_id-4000). ', "min_bet":'.$dataSetting->odds. 
                ', "max_bet":'. $dataSetting->max_point . ', "max_bet_per_match": '. $dataSetting->max_point_one . ', "max_payout_per_match":'. $dataSetting->change_max_one .'}]';
            if ($dataSetting->game_id-4000 == 161)
                $betSetting = '[{"sport_type" : '.($dataSetting->game_id-4000). ', "min_bet":'.$dataSetting->odds. 
                    ', "max_bet":'. $dataSetting->max_point . ', "max_bet_per_ball": '. $dataSetting->max_point_one . ', "max_bet_per_match":'. $dataSetting->change_max_one .'}]';
            $res = $client->request('POST',static::$ApiUrl["default"].'/SetMemberBetSetting', [
                'timeout' => 20,
                'connect_timeout' => 15,
                'form_params' => [
                    'vendor_id' => static::$vendor_id,
                    'vendor_member_id' => static::$OperatorId.'_'.$username,
                    'bet_setting' => $betSetting
                ],
            ]);
            $data = json_decode($res->getBody(), true);
            // print_r($data);
            // Log::info($data);
            if(!isset($data['error_code'])){ // khong dung dinh dang
                return false;
            }
            if($data['error_code'] == 0){
                return true;
            }
        }catch(Exception $ex){
            Log::info($ex->getMessage());
        }
        return false;
    }
    
    public static function LogoutAndRecallAllMember() {
        try{
            $AllUserBet = DB::table('history_transfer')
                        ->select('username')
                        ->where('created_date','>',date("Y-m-d", strtotime("yesterday")) . " " . static::$timeMaintain[1])
                        ->where('status',0)
                        ->groupBy('username')
                        ->get();
            foreach ($AllUserBet as $value){
                static::Logout($value->username);
                static::Recall($value->username, false);
            }
        }
    	catch(\Exception $ex){
				// catch code
				Log::info($ex->getMessage());
		}
    }  

    public static function Login($username,$platform=1) { //1: desktop 2: mobile 3: wap
        
        if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
            return "<script>alert('Trò chơi bảo trì đến ". static::$timeMaintain[1] ."')</script>";
        }

        static::CreateMember($username); // tao tai khoan truoc khi login

        // SabaHelpers::SetMemberBetSetting($username,$customerTypeOne);
        // \Log::info('SetMemberBetSetting UpdateCustomerTypeGameABCMAXPOINTV2');
        
        $client = new Client();
        $res = $client->request('POST',static::$ApiUrl["default"].'/GetSabaUrl', [
            'timeout' => 20,
            'connect_timeout' => 15,
            'form_params' => [
                'vendor_id' => static::$vendor_id,
                'vendor_member_id' => static::$OperatorId.'_'.$username,
                'platform' => $platform
            ],
        ]);
        $data = json_decode($res->getBody(), true);
        // print_r($data['error_code']);
        if(!isset($data['error_code'])){ // khong dung dinh dang
            return false;
        }
        if($data['error_code'] == 0){
            return $data['Data'].'&lang=vn&OType=4';
        }
        return false;
    }  
    
    public static function Logout($username) {
        try{
            $client = new Client();
            $res = $client->request('POST',static::$ApiUrl["default"].'/KickUser', [
                'timeout' => 20,
                'connect_timeout' => 15,
                'form_params' => [
                    'vendor_id' => static::$vendor_id,
                    'vendor_member_id' => static::$OperatorId.'_'.$username,
                ],
            ]);
            $data = json_decode($res->getBody(), true);
            // print_r($data);
            return true;
        }
        catch(\Exception $ex){
            // catch code
            Log::info($ex->getMessage());
        }
        return false;
    } 
    
    public static function CheckUsrBalance($username) {
        return static::getbalance($username);

        // $client = new Client();
        // $res = $client->request('POST',static::$ApiUrl["default"].'/CheckUserBalance', [
        //     'timeout' => 20,
        //     'connect_timeout' => 15,
        //     'form_params' => [
        //         'vendor_id' => static::$vendor_id,
        //         'vendor_member_id' => static::$OperatorId.'_'.$username,
        //         'wallet_id' => 1
        //             // Wallet ID
        //             // 1 : Sportsbook
        //             // 5 : AG
        //     ],
        // ]);
        // $data = json_decode($res->getBody(), true);
        // print_r($data);
        // if(!isset($data['error_code'])){ // khong dung dinh dang
        //     return false;
        // }
        // if($data['error_code'] == 0){
        //     return $data['Data'][0];
        // }
        // return false;
    }  
    
    public static function ReCall($username) { // action IN or OUT
        // return true;
        // if($force){
        //     if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
        //         return "Đang trong thời gian bảo trì";
        //     }
        // }

        $user = User::where('name',$username)->first();

        // $a =  CustomerType_Game::where('code_type',$user->customer_type)
        //         ->where('game_id',4001)
        //         ->where('created_user',$user->id)->first();

        
        $user->remain += $user->remain_saba *1000;
        $user->consumer -= $user->remain_saba *1000;
        $user->remain_saba = 0;
        
        $user->save();
        return true;
        
    }  

    public static function Transfer($username, $money, $action = "IN", $force = true) { // action IN or OUT
        // return true;
        // if($force){
        //     if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
        //         return "Đang trong thời gian bảo trì";
        //     }
        // }

        $user = User::where('name',$username)->first();

        $a =  CustomerType_Game::where('code_type',$user->customer_type)
                ->where('game_id',4001)
                ->where('created_user',$user->id)->first();
        $moneyr = $user->remain;
        // $user->save();
        if($moneyr < $money && $action == "IN"){
            return "Không đủ số dư";
        }
        // $balance = static::CheckUsrBalance($username);
        $balance = $user->remain_saba;
        if ($action == "IN"){
            if ($balance + $money > $a->max_point){
                return "Vượt giới hạn chuyển ". number_format($a->max_point);
            }
        }else{
            if ($balance < $money){
                return "Vượt giới hạn chuyển ". number_format($balance);
            }
        }
        
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

        // $client = new Client();
        // $res = $client->request('POST',static::$ApiUrl["default"].'/FundTransfer', [
        //     'timeout' => 20,
        //     'connect_timeout' => 15,
        //     'form_params' => [
        //         'vendor_id' => static::$vendor_id,
        //         'vendor_member_id' => static::$OperatorId.'_'.'_'.$username,
        //         'vendor_trans_id' => $key,
        //         'amount' => $money,
        //         'currency' => 51,
        //         'direction' => $action == "IN" ? 1 : 0,
        //         'wallet_id' => 1
        //             // Wallet ID
        //             // 1 : Sportsbook
        //             // 5 : AG
        //     ],
        // ]);
        // $data = json_decode($res->getBody(), true);
        // // print_r($data['error_code']);
        // if(!isset($data['error_code'])){ // khong dung dinh dang
        //     return false;
        // }

        
        // DB::table('history_transfer')->where('id', $remitno)->update([
        //     'status' =>  $data->data->Code,
        //     'msg' =>  $data->data->Message
        // ]);


        $user = User::where('name',$username)->first();

        if($action == "IN"){
            $user->remain -= $money*1000;
            $user->consumer += $money*1000;
            $user->remain_saba += ($money);
        }else{
            $user->remain += $money*1000;
            $user->consumer -= $money*1000;
            $user->remain_saba -= ($money);
        }
        $user->save();
        return true;
        
    }  
    
    //HElP

    public static function getDateStr($format = 'Ymd', $minutesToSub = 0){
        $date = new DateTime($minutesToSub == 0 ? "now" : $minutesToSub . " minutes ago", new DateTimeZone('America/New_York') );

        return $date->format($format);
        // return "";
    }
    
    public static function converDate($date){
        try{
            $date = new DateTime($date, new DateTimeZone('GMT-4'));
            $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
            return $date->format('Y-m-d H:i:s');
        }catch(Exception $ex){
            return "";
        }
        
        return "";
    }
    
    public static function converStatus($status){
        try{
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
        }catch(Exception $ex){
            return "";
        }
        
        return "";
    }

    public static function generateRandomString($length = 10) {
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
    
    public static function CurlSaba($url){
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
    
    public static function isJson($str) {
       $json = json_decode($str);
       return $json && $str != $json;
    }

    public static function getbalance($userId){
        $userId = str_replace(static::$OperatorId.'_','',$userId);
        $user = UserHelpers::GetUserByUserName($userId);
        return $user[0]->remain_saba;
    }

    public static function placebet($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        $arr = [
            'refId' => $message['refId'] ,
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
        return [ 'refId' => $message['refId'], 'licenseeTxId' => DB::table('history_saba_bet')->insertGetId($arr) ];
    }

    public static function confirmbet($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        $totalAmount = 0;
        foreach($message['txns'] as $txns){
            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsonconfirm' =>  json_encode($txns),
            ]);
            $totalAmount += ($txns['creditAmount'] - $txns['debitAmount']) ;
        }
        $user->remain_saba += $totalAmount;
        return $totalAmount;
    }

    public static function settle($message){
        $totalAmount = 0;
        foreach($message['txns'] as $txns){
            $userId = str_replace(static::$OperatorId.'_','',$txns['userId']);
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

    public static function unsettle($message){
        $totalAmount = 0;
        foreach($message['txns'] as $txns){
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

    public static function cancelbet($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        $totalAmount = 0;
        foreach($message['txns'] as $txns){
            DB::table('history_saba_bet')->where('refId', $txns['refId'])->update([
                'jsoncancel' =>  json_encode($txns),
                'status' => 'reject',
            ]);
            $totalAmount += $txns['creditAmount'];
        }
        
        return $totalAmount;
    }

    public static function placebetparlay($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        
        $dataReturn = [];
        foreach($message['txns'] as $txns){

            $arr = [
                'refId' => $txns['refId'],
                'username' =>  $user->name,
                'gametype' =>  5001,//$message->sportType,
                'betamount' => $txns['betAmount'],
                'com' =>  0,
                'payoff' =>  0,
                'jsoninfo' =>  json_encode($message),
                'createdate' =>  static::converDate($message['betTime']),
            ];
            $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            $dR = [
                    "refId"=>$txns['refId'],
                    "licenseeTxId"=>$idSave.'__'.$txns['refId']
            ];
            array_push($dataReturn,$dR);
        }
        return $dataReturn;
    }

    public static function confirmbetparlay($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        
        $dataReturn = [];
        $totalAmount = 0;
        foreach($message['txns'] as $txns){

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

    public static function placebet3rd($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        
        $dataReturn = [];
        foreach($message['ticketList'] as $txns){

            $arr = [
                'refId' => $txns['refId'],
                'username' =>  $user->name,
                'gametype' =>  4000+$message['productId'],//$message->sportType,
                'betamount' => $txns['betAmount'],
                'com' =>  0,
                'payoff' =>  0,
                'jsoninfo' =>  json_encode($message),
                'createdate' =>  static::converDate($message['betTime']),
            ];
            $idSave = DB::table('history_saba_bet')->insertGetId($arr);

            $dR = [
                    "refId"=>$txns['refId'],
                    "licenseeTxId"=>$idSave.'__'.$txns['refId']
            ];
            array_push($dataReturn,$dR);
        }
        return $dataReturn;
    }

    public static function confirmbet3rd($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
        $user = UserHelpers::GetUserByUserName($userId)[0];
        
        $dataReturn = [];
        $totalAmount = 0;
        foreach($message['txns'] as $txns){

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

    public static function placebetent($message){
        $userId = str_replace(static::$OperatorId.'_','',$message['userId']);
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
        foreach($message['ticketList'] as $ticketList){
            $arr = [
                'refId' => $ticketList['refId'],//$message['action'].$message['userId'].$message['betTime'].$message['productId'].$message['gameId'],
                'username' =>  $user->name,
                'gametype' =>  4000+$message['productId'],//$message->sportType,
                'betamount' => $ticketList['actualStake'],
                'com' =>  0,
                'payoff' =>  0,
                'jsoninfo' =>  json_encode($message),
                'createdate' =>  static::converDate($message['betTime']),
            ];
    
            $idSave = DB::table('history_saba_bet')->insertGetId($arr);
            
            $dR = [
                    "refId"=>$ticketList['refId'],
                    "licenseeTxId"=>$idSave.'__'.$ticketList['refId']
            ];
            // $totalAmount += $ticketList['actualAmount'];
            array_push($dataReturn,$dR);
        }
        
        // DB::table('history_saba_bet')->where('id', $idSave)->update([
        //     'betamount' =>  $totalAmount,
        // ]);
        return $dataReturn;
    }

    public static function settleent($message){
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

    public static function cancelbetent($message){
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

    public static function getticketinfo($message){
        return DB::table('history_saba_bet')->where('refId', $message['refId'])->first();
    }
    
}