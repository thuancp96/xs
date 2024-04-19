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
use \Queue;
use Illuminate\Support\Facades\Log;

class LiveCasinoHelpers
{

    // info BBIN
    private static $ApiUrl = [
        "default" => "https://linkapi.psikygpp.com/app/WebService/JSON/display.php",
        "Login" => "http://888.psikygpp.com/app/WebService/JSON/display.php"
    ]; 
    private static $UpperName = "dluk79prod_"; 
    private static $Website = "aecasino_"; 
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

    

    //BBIN
    public static function GetHistoryLoop() { // nho phan page limit
    
        $dt = static::getDateStr();
        $timesleep = 6; // minute
        // MD5(website + KeyB + YYYYMMDD)
        $key = "12345678" . md5( static::$Website . static::$KeyB["WagersRecordBy3"].( $dt)) . "123456789";
        $request = static::CurlBBIN(static::$ApiUrl["default"] . "/WagersRecordBy3?website=" .static::$Website. "&action=ModifiedTime&uppername=" .static::$UpperName. "&date=". static::getDateStr("Y-m-d") ."&starttime=". static::getDateStr("H:i", $timesleep) .":00&endtime=". static::getDateStr("H:i", $timesleep) .":59&key=". $key);
        if($request == "error"){ // loi curl
            return false;
        }
        $data = json_decode($request);
        if(!isset($data->result)){ // khong dung dinh dang
            return false;
        }
        
        
        $arr = [];
        $Loop = $data->data;
  
        foreach ($Loop as $value){
            array_push($arr, [
                'id' => $value->WagersID ,
                'username' =>  $value->UserName,
                'gametype' =>  $value->GameType,
                'betamount' => $value->BetAmount,
                'com' =>  $value->Commissionable,
                'payoff' =>  $value->Payoff,
                'jsoninfo' =>  json_encode($Loop),
                'createdate' =>  static::converDate($value->ModifiedDate),
            ]);
        }
        DB::table('history_live_bet')->insert($arr);
        var_dump($arr);
    }  

    public static function GetHistoryALLLoop() { // nho phan page limit
    
        $dt = static::getDateStr();
        $timesleep = 6; // minute
        // MD5(website + KeyB + YYYYMMDD)
        echo static::getDateStr("H:i", 0);
        $key = "12345678" . md5( static::$Website . static::$KeyB["WagersRecordBy3"].( $dt)) . "123456789";
        // $request = static::CurlBBIN(static::$ApiUrl["default"] . "/WagersRecordBy3?website=" .static::$Website. "&action=ModifiedTime&uppername=" .static::$UpperName. "&date=". static::getDateStr("Y-m-d") ."&starttime=". static::getDateStr("H:i", $timesleep) .":00&endtime=". static::getDateStr("H:i", $timesleep) .":59&key=". $key);
        $request = static::CurlBBIN(static::$ApiUrl["default"] . "/WagersRecordBy3?website=" .static::$Website. "&action=ModifiedTime&uppername=" .static::$UpperName. "&date=". static::getDateStr("Y-m-d") ."&starttime=07:28:00&endtime=07:31:59&key=". $key);
        if($request == "error"){ // loi curl
            return false;
        }
        $data = json_decode($request);
        if(!isset($data->result)){ // khong dung dinh dang
            return false;
        }
        
        var_dump($data->data);
        $arr = [];
        $Loop = $data->data;
  
        foreach ($Loop as $value){
            array_push($arr, [
                'id' => $value->WagersID ,
                'username' =>  $value->UserName,
                'gametype' =>  $value->GameType,
                'betamount' => $value->BetAmount,
                'com' =>  $value->Commissionable,
                'payoff' =>  $value->Payoff,
                'jsoninfo' =>  json_encode($Loop),
                'createdate' =>  static::converDate($value->ModifiedDate),
            ]);
        }
        DB::table('history_live_bet')->insert($arr);
        var_dump($arr);
    }  

    public static function CreateMember($username) {
        $dt = static::getDateStr();
        $key = "123456" . md5( static::$Website . $username. static::$KeyB["CreateMember"].( $dt)) . "1234567";
        $request = static::CurlBBIN(static::$ApiUrl["default"] . "/CreateMember?website=" .static::$Website. "&uppername=" .static::$UpperName. "&username=$username&key=$key");
        if($request == "error"){ // loi curl
            return false;
        }
        $data = json_decode($request);
        if(!isset($data->data->Code)){ // khong dung dinh dang
            return false;
        }
        if($data->data->Code == 21001 || $data->data->Code == 21100){
            return true;
        }
        return false;
    }  
    
    
    public static function Login($username) {
        
        if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
            return "<script>alert('Trò chơi bảo trì đến ". static::$timeMaintain[1] ."')</script>";
        }
        $dt = static::getDateStr();
        static::CreateMember($username); // tao tai khoan truoc khi login
        // website+ username + KeyB + YYYYMMDD
        $key = "111" . md5( static::$Website . $username. static::$KeyB["Login"].( $dt)) . "12";
        $request = static::CurlLoginBBIN(static::$ApiUrl["Login"] . "/Login?ingress=2&website=" .static::$Website. "&uppername=" .static::$UpperName. "&lang=vi&username=$username&key=$key");
        
        // return static::$ApiUrl["Login"] . "/Login?website=" .static::$Website. "&uppername=" .static::$UpperName. "&lang=vi&username=$username&key=$key";
        
        // static::LogoutAndRecallAllMember();
        return $request;
    }  
    
    public static function LogoutAndRecallAllMember() {
        try{
            $AllUserBet = DB::table('history_transfer')
                        ->select('username')
                        ->where('created_date','>',date("Y-m-d", strtotime("yesterday")) . " " . static::$timeMaintain[1])
                        ->where('status','<>',0)
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
    
    public static function Logout($username) {
        try{
            $dt = static::getDateStr();
            $key = "55555" . md5( static::$Website . $username. static::$KeyB["Logout"].( $dt)) . "4444";
            $request = static::CurlBBIN(static::$ApiUrl["default"] . "/Logout?&website=" .static::$Website. "&username=$username&key=$key");
            Log::info($request);
        }
        catch(\Exception $ex){
            // catch code
            Log::info($ex->getMessage());
    }
    } 
    
    public static function CheckUsrBalance($username) {
        try{
            $dt = static::getDateStr();
            // website+ username + KeyB + YYYYMMDD
            $key = "11111111" . md5( static::$Website . $username. static::$KeyB["CheckUsrBalance"].( $dt)) . "12345";
            $request = static::CurlBBIN(static::$ApiUrl["default"] . "/CheckUsrBalance?&website=" .static::$Website. "&uppername=" .static::$UpperName. "&lang=vi&username=$username&key=$key");
            if($request == "error"){ // loi curl
                return 0;
            }
            $data = json_decode($request);
            if(!is_array($data->data)){ // khong dung dinh dang
                return 0;
            }
            return $data->data[0]->Balance;
        }catch(\Exception $ex){
            Log::info($ex->getMessage());
            echo $ex->getMessage();
        }
        return -1;
    }  
    
    public static function Transfer($username, $money, $action = "IN", $force = true) { // action IN or OUT
        if($force){
            if (time() >= strtotime(static::$timeMaintain[0]) && time() <= strtotime(static::$timeMaintain[1])) {
                return "Đang trong thời gian bảo trì";
            }
        }

        $user = User::where('name',$username)->first();

        $a =  CustomerType_Game::where('code_type',$user->customer_type)
                ->where('game_id',3038)
                ->where('created_user',$user->id)->first();
        $moneyr = $user->remain;
        // $user->save();
        if($moneyr < $money && $action == "IN"){
            return "Không đủ số dư";
        }
        $balance = static::CheckUsrBalance($username);
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
        static::CreateMember($username); // tao tai khoan truoc khi login
        // website+ username + KeyB + YYYYMMDD
       
        
        $remitno = DB::table('history_transfer')->insertGetId([
            'username' =>  $username,
            'amount' =>  $money,
            'transfer_type' => $action == "IN" ? 1 : 2,
            'status' =>  0,
            'msg' =>  '',
            'created_date' =>  date('Y-m-d H:i:s'),
        ]);
        
        $key = "1111" . md5( static::$Website . $username. $remitno . static::$KeyB["Transfer"].( $dt)) . "1111";
        $request = static::CurlBBIN(static::$ApiUrl["default"] . "/Transfer?&website=" .static::$Website. "&uppername=" .static::$UpperName. "&remitno=$remitno&remit=$money&action=$action&username=$username&key=$key");
        if($request == "error"){ // loi curl
            return "Lỗi không xác định -1001";
        }
        $data = json_decode($request);
        
        
        if(!isset($data->data->Code)){ // khong dung dinh dang
            return $request;
            return "Lỗi không xác định -1002";
        }
        

        
        DB::table('history_transfer')->where('id', $remitno)->update([
            'status' =>  $data->data->Code,
            'msg' =>  $data->data->Message
        ]);


        switch ($data->data->Code) {
            case 11100:
            case 10008:
                $user = User::where('name',$username)->first();

                if($action == "IN"){
                    $user->remain -= $money;
                    $user->consumer += $money;
                }else{
                    $user->remain += $money;
                    $user->consumer -= $money;
                }
                $user->save();
                return true;
                break;
            case 10002:
                return "Không đủ số dư BBIN";
                break;
            case 10008:
                return "Số tiền không hợp lệ, giá trị phải lớn hơn 0";
                break;
            default:
                return "Bbin đang bảo trì! Xin hay quay lại sau ít phút. (". $data->data->Code.")";
                break;
        }
        
    }  
    public static function Recall($username, $force = true) { 
        $balance = static::CheckUsrBalance($username);
        return static::Transfer($username, $balance, "OUT", $force);
    }  
    
    //HElP

    public static function getDateStr($format = 'Ymd', $minutesToSub = 0){
        $date = new DateTime($minutesToSub == 0 ? "now" : $minutesToSub . " minutes ago", new DateTimeZone('GMT-4') );

        return $date->format($format);
        // return "";
    }
    
    public static function converDate($date){
        $date = new DateTime($date, new DateTimeZone('GMT-4'));

        $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
        return $date->format('Y-m-d H:i:s');
        // return "";
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
    public static function CurlLoginBBIN($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        // curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
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
        if (curl_errno($ch)) {
            return "<center>Đã xảy ra lỗi | -1001</center>";
        }
        if (static::isJson($result)) {
            return "<center>Đã xảy ra lỗi | -1002</center>";
        }
        return $result;
    }
    
    public static function CurlBBIN($url){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        
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
}