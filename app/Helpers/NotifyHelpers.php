<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Notification;
use App\Notification2;
use App\User;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use \Mail;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class NotifyHelpers
{

    private static function appendMessageID2Queue($messageid,$chatid,$token){
        $queue = Cache::get('queue_messages' . $chatid,[]);
        if (!is_array($queue)) $queue = [];
        $isContrains = false;
        foreach ($queue as $key => $message) {
            if ($message[0] == $messageid) $isContrains = true;
        }
        if (!$isContrains)
        {
            $queue[] = [$messageid,$token];
            Cache::put('queue_messages' . $chatid, $queue, env('CACHE_TIME_BOT', 24 * 60));
        }
    }

    public static function sendMessage($id, $message,$token)
    {
        $telegram = new Api($token);
        $response = $telegram->getMe();
        $response = $telegram->sendMessage([
            'chat_id' => $id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
        static::appendMessageID2Queue($response->getMessageId(),$id,$token);
        return $response->getMessageId();
    }

    public static function GetNotification($id = 1)
    {
        $notify = Notification::where('id',$id)->where('typeid',1)->get()->first();
        if (isset($notify))
            return $notify->content;
        else
            return null;
    }

    public static function GetNotification1($id = 2)
    {
        $notify = Notification::where('id',$id)->where('typeid',2)->get()->first();
        if (isset($notify))
            return $notify->content;
        else
            return null;
    }

    public static function GetNotification2($id = 3)
    {
        $notify = Notification::where('id',$id)->where('typeid',3)->get()->first();
        if (isset($notify))
            return $notify->content;
        else
            return null;
    }

    public static function GetNotification3($id = 4)
    {
        $notify = Notification::where('id',$id)->where('typeid',4)->get()->first();
        if (isset($notify))
            return $notify->content;
        else
            return null;
    }

    public static function UpdateNotification($content,$id = 1)
    {
        $notify = Notification::where('id',$id)->where('typeid',1)->get()->first();
        $notify->content = $content;
        $notify->typeid = 1;
        $notify->save();
        return $notify->content;
    }

    public static function UpdateNotification1($content,$id = 2)
    {
        $notify = Notification::where('id',$id)->where('typeid',2)->get()->first();
        $notify->content = $content;
        $notify->typeid = 2;
        $notify->save();
        return $notify->content;
    }

    public static function UpdateNotification2($content,$id = 3)
    {
        $notify = Notification::where('id',$id)->where('typeid',3)->get()->first();
        $notify->content = $content;
        $notify->typeid = 3;
        $notify->save();
        return $notify->content;
    }

    public static function UpdateNotification3($content,$id = 4)
    {
        $notify = Notification::where('id',$id)->where('typeid',4)->get()->first();
        $notify->content = $content;
        $notify->typeid = 4;
        $notify->save();
        return $notify->content;
    }

    public static function SendMailNotification($content){
        // return;
        
    }

    public static function SendTelegramNotificationByChannel($bot_id,$channel,$content){
        // return;
        echo "SendTelegramNotificationByChannel";
        $bot_id = $bot_id;
        $chat_id = $channel;
        // $filename = storage_path('excel/exports')."/"."xs".$now.".xlsx";
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, );
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // curl_setopt($ch, CURLOPT_POST, 1);

        $curl = new Curl();
        $linkminhngoc = "https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=" . $chat_id . "&parse_mode=html" . "&text=".urlencode($content);
        $response = $curl->get($linkminhngoc);

        Log::info("https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=" . $chat_id . "&text=".urlencode($content)) ;
        // Create CURLFile
        // $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        // print_r($finfo);
        // $cFile = new CURLFile($filename, $finfo);
        // $cFile = new CURLFile($filename, "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", $now.".xlsx");

        // Add CURLFile to CURL request
        // curl_setopt($ch, CURLOPT_POSTFIELDS, [
        //     "document" => $cFile
        // ]);

        // Call
        // $result = curl_exec($ch);

        // Show result and close curl
        // var_dump($result);
        // curl_close($ch);
    }

    public static function SendTelegramNotification($content){
        // return;
        $bot_id = "5173551303:AAGce_EKGBYhjxPVsg58zrZNzX18uYYKzJo";
        $chat_id = "@xs_notification_channel";
        // $filename = storage_path('excel/exports')."/"."xs".$now.".xlsx";
        $curl = new Curl();
        $linkminhngoc = "https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=" . $chat_id . "&text=".$content;
        $response = $curl->get($linkminhngoc);

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot".$bot_id."/sendMessage?chat_id=" . $chat_id . "&text=".$content);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // curl_setopt($ch, CURLOPT_POST, 1);

        // Create CURLFile
        // $finfo = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $filename);
        // print_r($finfo);
        // $cFile = new CURLFile($filename, $finfo);
        // $cFile = new CURLFile($filename, "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", $now.".xlsx");

        // Add CURLFile to CURL request
        // curl_setopt($ch, CURLOPT_POSTFIELDS, [
        //     "document" => $cFile
        // ]);

        // Call
        // $result = curl_exec($ch);

        // Show result and close curl
        // var_dump($result);
        // curl_close($ch);
    }

    public static function storeNotification2($request){
        try{
            $newNotify = new Notification2();
            $newNotify->message = $request->message;
            $newNotify->type = $request->type;
            if ($request->type == 'personal')
                $newNotify->target = explode('-',$request->personal_name)[0];
            $newNotify->message = $request->message;
            $newNotify->save();            
        }catch(Exception $ex){
            return $ex->getMessage();
        }
        return "true";
    }

    public static function saveNotification2($user,$message,$message2=""){
        try{
            $newNotify = new Notification2();
            $newNotify->message = str_replace("\n","<br>",$message);
            $newNotify->message2 = $message2;
            $newNotify->type = "personal";
            $newNotify->target = $user->name;
            $newNotify->pin = 1;
            $newNotify->hidden = 1;
            $newNotify->save();
            
            try {
                $tokenBot_agent_member = "6031440319:AAENrVaJ_TOLxQ75L8e2eDjW0_A_bzTnS2Q";
                $tokenBot_admin_super_master = "6241857190:AAHCxFRIcZItEvFDnk7ntXAZx978Y5wykNg";
                // echo "send tele";
                // $user = User::where("id", $user->id)->first();
                $txtMessage = $user->name . " (" . date("H:i") . ") :" . $message;
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

        }catch(Exception $ex){
            Log::info($ex);
            return $ex->getMessage();
        }
        return "true";
    }

    public static function updateNotification22($request){
        try{
            if ($request->type == 'pin'){
                $notify = Notification2::where('id',$request->id)->first();
                $notify->pin = $request->value;
                $notify->save();
                if ($notify->type != 'personal' && $notify->pin == 1)
                    Notification2::where('id','!=',$request->id)->where('type',$notify->type)->update(['pin' => 0]);
                else
                    Notification2::where('id','!=',$request->id)->where('type',$notify->type)->where('target',$notify->target)->update(['pin' => 0]);
            }
            if ($request->type == 'hidden'){
                $notify = Notification2::where('id',$request->id)->first();
                $notify->hidden = $request->value;
                $notify->save();

                if ($notify->type != 'personal' && $notify->hidden == 1)
                    Notification2::where('id','!=',$request->id)->where('type',$notify->type)->update(['hidden' => 0]);
                else
                    Notification2::where('id','!=',$request->id)->where('type',$notify->type)->where('target',$notify->target)->update(['hidden' => 0]);

            }
        }catch(Exception $ex){
            return $ex->getMessage() . $ex->getLine();
        }
        return "true";
    }

    public static function showNotification($request){
        try{
            $startdateR = $request->startdate;
            $enddateR = $request->enddate;
            $category = $request->category;

            $time = strtotime($startdateR);
            $startdate = date('Y-m-d',$time);

            $time = strtotime($enddateR);
            $enddate = date('Y-m-d',$time);
            // echo  $startdate .  $enddate.$category;
            if ($startdate=='1970-01-01') $startdate = date('Y-m-d');
            if ($enddate=='1970-01-01') $enddate = date('Y-m-d');
            if ($category=='') $category = 'all';
            switch ($category) {
                case 'all':
                    return Notification2::whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)->orderByRaw("pin DESC, hidden ASC, created_at desc")->get();
                    break;
                case 'system':
                    return Notification2::whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)->where('type','system')->orderByRaw("pin DESC, hidden ASC, created_at desc")->get();
                    break;
                case 'generate':
                    return Notification2::whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)
                    ->where(function($query)
                    {
                        $query->orWhere('type','supers')->orWhere('type','agents')->orWhere('type','masters')->orWhere('type','members');
                    })  
                    ->orderByRaw("pin DESC, hidden ASC, created_at desc")->get();
                    break;
                case 'personal':
                    return Notification2::whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)->where('type','personal')->orderByRaw("pin DESC, hidden ASC, created_at desc")->get();
                    break;
                default:
                    # code...
                    break;
            }
            return Notification2::whereDate('created_at', '>=', $startdate)->whereDate('created_at', '<=', $enddate)->orderByRaw("pin DESC, hidden ASC, created_at desc")->get();
        }catch(Exception $ex){
            return $ex->getMessage();
        }
        return [];
    }

    public static function showNotificationByFilter($type,$target=''){
        try{
            switch ($type) {
                case 'system':
                    return Notification2::where('type',$type)->get();
                    break;
                
                case 'supers':
                    return Notification2::where('type',$type)->get();
                    break;                    

                case 'masters':
                    return Notification2::where('type',$type)->get();
                    break;

                case 'agents':
                    return Notification2::where('type',$type)->get();
                    break;

                case 'members':
                    return Notification2::where('type',$type)->get();
                    break;

                case 'personal':
                    return Notification2::where('target',$target)->get();
                    break;

                default:
                    break;
            }
            return [];
        }catch(Exception $ex){
            return [];
        }
        return [];
    }

    public static function showNotificationByUserid(){
        try{
            $group_message = '';
            $total_message = [];
            $now = date('Y-m-d');
            switch (Auth::user()->roleid) {
                case 2:
                    $group_message = 'supers';
                    break;

                case 4:
                    $group_message = 'masters';
                    break;
                
                case 5:
                    $group_message = 'agents';
                    break;

                case 6:
                    $group_message = 'members';
                    break;

                default:
                    # code...
                    break;
            }

            // $total_message = Notification2::orWhere('type','system')->orWhere('type',$group_message)->orWhere('target',Auth::user()->name)->where('hidden',1)->get();
            $date = new DateTime('-2 week');
            $date = $date->format('Y-m-d');

            $total_message = Notification2::whereRaw('(type = "system" or type = "'.$group_message. '" or target = "'. Auth::user()->name . '") and hidden = 1')->whereDate('created_at','>=',$date)->orderBy('updated_at', 'desc')->get();
            return $total_message;
        }catch(Exception $ex){
            return [];
        }
        return [];
    }

    public static function showNotificationByPin(){
        try{
            $group_message = '';
            $total_message = [];
            $now = date('Y-m-d');
            switch (Auth::user()->roleid) {
                case 2:
                    $group_message = 'supers';
                    break;

                case 4:
                    $group_message = 'masters';
                    break;
                
                case 5:
                    $group_message = 'agents';
                    break;

                case 6:
                    $group_message = 'members';
                    break;

                default:
                    # code...
                    break;
            }
            // $pin_message = Notification2::where('pin',1)->orWhere('type',$group_message)->orWhere('target',Auth::user()->name)->where('hidden',1)->get();

            $pin_message = Notification2::whereRaw('(type = "system" or type = "'.$group_message. '" or target = "'. Auth::user()->name . '") and hidden = 1 and pin = 1')->whereDate('created_at','=',$now)->orderBy('updated_at', 'desc')->first();
           
            return $pin_message;
        }catch(Exception $ex){
            return [];
        }
        return [];
    }
}