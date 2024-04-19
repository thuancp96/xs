<?php

namespace App\Http\Controllers;

use App\CustomerType_Game;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTFactory;
use JWTAuth;
use App\User;
use App\Location;
use App\Helpers\SabaHelpers;
use App\Helpers\GameHelpers;
use App\Helpers\MinigameHelpers;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Illuminate\Support\Facades\Log;

class MinigameController extends Controller
{

    public function loginmobile(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $s_7zballModule = new MinigameHelpers;
            return redirect($s_7zballModule->Login($user["name"],2));
            // return response($SabaModule->Login($user["name"]));
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => "error"]);
        }
    }

    public function login(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $s_7zballModule = new MinigameHelpers;
            return redirect($s_7zballModule->Login($user["name"],2));
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => "error"]);
        }
    }

    public function logout(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            SabaHelpers::Logout($user["name"]);
            return response()->json(['code'=>200,'message'=>'','data' => ""]);
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => "error"]);
        }
    }
    
    public function info(Request $request){

        try{
            $user = auth()->user();
        
            if(isset($user["name"])){
                MinigameHelpers::CreateMember($user["name"]); // tao tai khoan truoc khi login
    
                $limitMember = CustomerType_Game::where('game_id',8001)
                        ->where('created_user', $user->id)->first();
                // $a =  CustomerType_Game::where('code_type',$user->customer_type)
                //     ->where('game_id',4001)
                //     ->where('created_user',$user->id)->first();
                return response()->json(['code'=>200,'message'=>'','mainbalance' => $user["remain"] ,'SABAbalance' => MinigameHelpers::CheckUsrBalance($user["name"]), 'MaxTransfer' => (int)$user["remain"], 'MaxBet' => $limitMember->change_max_one ]); //$SabaModule->CheckUsrBalance($user["name"])
                // return response()->json(['code'=>200,'message'=>'','mainbalance' => $user["remain"] ,'SABAbalance' => 200000000000, 'MaxTransfer' => $a->max_point, 'MaxBet' => $a->max_point_one ]);
            }else{
                return response()->json(['code'=>200,'message'=>'','data' => "error"]);
            }
        }catch(Exception $ex){
            return response()->json(['code'=>200,'message'=>'','data' => $ex->getMessage()]);
        }
        
        
    }
   
    public function transfer(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $SabaModule = new MinigameHelpers;
            return response()->json(['code'=>200,'message' => $SabaModule->Transfer($user["name"], $request->money, $request->mode) ]);

        }else{
            return response()->json(['code'=>200,'message'=>"error"]);
        }
        
    }
   
    public function recall(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $SabaModule = new MinigameHelpers;
            return response()->json(['code'=>200,'message' => $SabaModule->ReCall($user["name"]) ]);

        }else{
            return response()->json(['code'=>200,'message'=>"error"]);
        }
    }
    
    // -------------------------------------------------
    // Operator
    // -------------------------------------------------

    /**
     * Handle an incoming request.
     *
     * @param Request  $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handleRequestGzip($request)
    {
        $newRequest = $request;
        if ($request->header('Content-Encoding') === 'gzip') {
            $zippedContent = $request->getContent();
            $request->headers->remove('Content-Encoding');
            // print_r($zippedContent);
            // echo gzdecode(base64_decode('eJzLSM3JyQcABiwCFQ=='));
            Log::info('gzip data: ' . $zippedContent);
            $content = gzdecode($zippedContent);
            // $content = gzinflate(substr($zippedContent, 10, -8));
            
            if ($content === false) {
                return $request;
            }
            
            $baseRequest = new SymfonyRequest();
            $baseRequest->initialize(
                $request->query->all(),
                $request->request->all(),
                $request->attributes->all(),
                $request->cookies->all(),
                $request->files->all(),
                $request->server->all(),
                $content
            );
            $newRequest = Request::createFromBase($baseRequest);
            // $this->app->instance(Request::class, $newRequest);
        }
        return $newRequest;
    }

    public function getbalance(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
                return response()->json([
                    'status' => -1,
                    'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                    'msg' => 'key:'.$newRequest->key
                ]);
        
            $balance = SabaHelpers::getbalance($newRequest->message['userId']);
            
            return response()->json([
                'status' => 0,
                'userId' => $newRequest->message['userId'],
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

    public function placebet(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $placebet = SabaHelpers::placebet($newRequest->message);
            
            return response()->json([
                'status' => 0,
                'refId'=> $placebet['refId'],
                'licenseeTxId' => $placebet['licenseeTxId'],
                'msg' => ''
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function confirmbet(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $confirmbet = SabaHelpers::confirmbet($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            $balance = SabaHelpers::getbalance($newRequest->message['userId']);
            
            return response()->json([
                'status' => 0,
                'balance'=> $balance - $confirmbet,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function cancelbet(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $cancelbet = SabaHelpers::cancelbet($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            $balance = SabaHelpers::getbalance($newRequest->message['userId']);
            
            return response()->json([
                'status' => 0,
                'balance'=> $balance + $cancelbet,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function settle(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $settle = SabaHelpers::settle($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function resettle(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            // $placebet = SabaHelpers::placebet($request->message['userId']);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function unsettle(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $unsettle = SabaHelpers::unsettle($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function placebetparlay(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            // $newRequest = $request;
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $placebetparlay = SabaHelpers::placebetparlay($newRequest->message);
            
            return response()->json([
                'status' => 0,
                'txns'=> $placebetparlay,
                'msg' => ''
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function confirmbetparlay(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $confirmbetparlay = SabaHelpers::confirmbetparlay($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            $balance = SabaHelpers::getbalance($newRequest->message['userId']) - $newRequest->message['debitAmount'];
            
            return response()->json([
                'status' => 0,
                'balance'=> $balance,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function placebet3rd(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $placebet3rd = SabaHelpers::placebet3rd($newRequest->message);
            
            return response()->json([
                'status' => 0,
                'txns'=> $placebet3rd,
                'msg' => ''
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function confirmbet3rd(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $confirmbet3rd = SabaHelpers::confirmbet3rd($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            $balance = SabaHelpers::getbalance($newRequest->message['userId']);
            
            return response()->json([
                'status' => 0,
                'balance'=> $balance,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function placebetent(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $placebet = SabaHelpers::placebetent($newRequest->message);
            $balance = SabaHelpers::getbalance($newRequest->message['userId']);

            return response()->json([
                'status' => 0,
                'balance'=> $balance,
                'ticketList' => $placebet,
                'msg' => ''
            ]);
        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function settleent(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $settleent = SabaHelpers::settleent($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function cancelbetent(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($request->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $cancelbetent = SabaHelpers::cancelbetent($request->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function getticketinfo(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            $getticketinfo = SabaHelpers::getticketinfo($newRequest->message);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => '',
                "ticketStatus" => $getticketinfo->status,
                "actualStake" => $getticketinfo->betamount,
                "winlostAmount" => $getticketinfo->payout,
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function healthcheck(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            // $placebet = SabaHelpers::placebet($request->message['userId']);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

    public function adjustbalance(Request $request){
        try{
            $newRequest = $this->handleRequestGzip($request);
            if ($newRequest->key != 'h5ycnl7k6u')
            return response()->json([
                'status' => -1,
                'balanceTs' => date("Y-m-d\TH:i:s.vP"),
                'msg' => ''
            ]);
        
            // $placebet = SabaHelpers::placebet($request->message['userId']);
            
            // return response()->json([
            //     'status' => 0,
            //     'refId'=> $placebet['refId'],
            //     'licenseeTxId' => $placebet['licenseeTxId'],
            //     'msg' => ''
            // ]);
            // $balance = SabaHelpers::getbalance($request->message['userId']);
            
            return response()->json([
                'status' => 0,
                'msg' => ''
            ]);

        }catch(Exception $ex){
            return response()->json([
                'status' => -1,
                'msg' => $ex->getMessage().'-'.$ex->getLine()
            ]);
        }
    }

}