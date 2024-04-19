<?php

namespace App\Http\Controllers;

use App\Commands\UpdateMeFromParentEXService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTFactory;
use JWTAuth;
use App\User;
use App\Location;
use App\Helpers\LocationHelpers;
use App\Helpers\GameHelpers;
use App\Helpers\UserHelpers;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use luk79\CryptoJsAes\CryptoJsAes;
// require "CryptoJsAes.php";
use Illuminate\Support\Facades\DB;
use \Queue;

class APIController extends Controller
{
    private static $vendor_id = "h5ycnl7k6u_"; 
    private static $OperatorId = "99luckeyprod_"; 
    
    public function __construct()
    {
        $this->middleware('auth',['except' => ['login','getbalance','adjustbalance']]);
    }

    public function login(Request $request)
    {
        try{
// $validator = Validator::make($request->all(), [
        //     'email' => 'required|string|email|max:255',
        //     'password'=> 'required'
        // ]);
        // if ($validator->fails()) {
        //     return response()->json($validator->errors());
        // }
        $decrypted =  CryptoJsAes::decryptRequest($request);
        // return response()->json(['error' => $decrypted], 401);
        // print_r(json_decode('{ "name": "Kkka4444", "password": "qaz123" }',true)['name']);
        // print_r($decrypted);
        // print_r($decrypted['name']);
        // return $decrypted;
        
        $request->name = $decrypted->name;
        $request->password = $decrypted->password;
        
        // $decrypted->name = 'bbb44';//$decrypted->name;
        // $decrypted->password = 'qaz123';//$decrypted->password;

        $credentials = ['name'=>$decrypted->name,'password'=>$decrypted->password];
        //$request->only('name', 'password');
        // var_dump($credentials);
        try {
            
            if (! $token = JWTAuth::attempt($credentials)) {
                // return response()->json(['error' => 'invalid_credentials'], 401);
                $user_check = UserHelpers::GetUserByUserName($decrypted->name);
                if ($user_check != null && count($user_check) > 0 && $user_check[0]->active ==0){
                    $user_check[0]->loginfailure+=1;
                    if ($user_check[0]->loginfailure >= 6)
                        $user_check[0]->lock=2;

                    $user_check[0]->save();
                };

                return response()->json(['code'=>401,'message'=>'Đăng nhập không thành công'],401);
            }
        } catch (JWTException $e) {
            // return response()->json(['error' => 'could_not_create_token'], 500);
            return response()->json(['code'=>500,'message'=>'Lỗi service. Đăng nhập không thành công'],500);
        }
        $user = Auth::user();
        if ($user->lock == 2)
            return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.'],401);
        if ($user->lock == 1)
            return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.'],401);

        // $payload = JWTAuth::decode($token);
        // $expiration = $payload->getExp();
        // $expiration = $payload->get('exp');
        // $expiration = $payload['exp'];
        // $expires_at = date('d M Y h:i', $payload->get('exp')); 
        $apy['exp'] = '';
        // try {
        //     // attempt to verify the credentials and create a token for the user
        //     // $token = JWTAuth::getToken();
        //     $apy = JWTAuth::getPayload($token)->toArray();
        // } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    
        //     return response()->json(['token_expired'], 500);
    
        // } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    
        //     return response()->json(['token_invalid'], 500);
    
        // } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
    
        //     return response()->json(['token_absent' => $e->getMessage()], 500);
    
        // }
        
        // Queue::pushOn("high",new UpdateMeFromParentEXService($user));
        // $user->latestlogin = date("Y-m-d H:i:s");
        // $user->save();
        $secs = (new DateTime())->getTimestamp() - (new DateTime($user->latestlogin))->getTimestamp();// == <seconds between the two times>
        $days = $secs / 86400;
        if ($days > 1) Queue::pushOn("high",new UpdateMeFromParentEXService($user));
        DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'latestlogin' => date("Y-m-d H:i:s")
                    ]);

        return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData(
            [
                'user' => [
                    "id" => $user->id,
                    "role" => "admin",//auth()->user()->roleid,
                    "roleid" => auth()->user()->roleid,
                    "name"=> $user->name,
                    "credit" => $user->credit,
                    "remain" => $user->remain,
                    "latestlogin" => $user->latestlogin,
                        ], 
                'token' => $token,
                'exp' => date('Y-m-d H:i:s',time() + 3600) 
            ]
        )]);
        // return response()->json(compact('user','token'));
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage() .'-'.$ex->getLine()
            ]);
        }
    }

    public function getListLocation(Request $request){
        return response()->json(['code'=>200,'message'=>'','data' => ['location' => LocationHelpers::getTopLocation()]]);
    }

    public function getGameByLocation(Request $request){
        $gameHelpers = new GameHelpers();
        $locationID = $request->locationID;
        return response()->json(['code'=>200,'message'=>'','data' => ['games' => $gameHelpers->GetGameList($locationID)]]);
    }

    public function postPlayXS(Request $request){
        
    }

    public function getbalance(Request $request){
        try{
            $userId = str_replace(static::$OperatorId.'_','',$request->userId);
            $user = UserHelpers::GetUserByUserName($userId);
            $balance = $user[0]->remain;

            return response()->json([
                'status' => 0,
                'userId' => $request->userId,
                'balance'=> $balance,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function adjustbalance(Request $request){
        try{
            $userId = str_replace(static::$OperatorId.'_','',$request->userId);
            $user = UserHelpers::GetUserByUserName($userId);
            $balance = $request->balance;
            if ($request->action == "OUT")
                $user[0]->remain = $user[0]->remain - $balance;
            else
                $user[0]->remain = $user[0]->remain + $balance;
            $user[0]->save();
            return response()->json([
                'status' => 0,
                'userId' => $request->userId,
                'balance'=> $user[0]->remain,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

}