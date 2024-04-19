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
use App\Helpers\LiveCasinoHelpers;
use App\Helpers\GameHelpers;
use Illuminate\Support\Facades\Auth;

class LiveCasinoController extends Controller
{

    public function login(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $LiveModule = new LiveCasinoHelpers;
            return response($LiveModule->Login($user["name"]));
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => "error"]);
        }
    }

    public function logout(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            LiveCasinoHelpers::Logout($user["name"]);
            return response()->json(['code'=>200,'message'=>'','data' => ""]);
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => "error"]);
        }
    }
    
    public function info(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $LiveModule = new LiveCasinoHelpers;
            $a =  CustomerType_Game::where('code_type',$user->customer_type)
                ->where('game_id',3038)
                ->where('created_user',$user->id)->first();
            return response()->json(['code'=>200,'message'=>'','mainbalance' => $user["remain"] ,'BBINbalance' => $LiveModule->CheckUsrBalance($user["name"]), 'MaxTransfer' => $a->max_point, 'MaxBet' =>  1000000000]);//$a->max_point_one
            // return response()->json(['code'=>200,'message'=>'','mainbalance' => $user["remain"] ,'BBINbalance' => 200000000000, 'MaxTransfer' => $a->max_point, 'MaxBet' => $a->max_point_one ]);
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => "error"]);
        }
        
    }
   
    public function transfer(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $LiveModule = new LiveCasinoHelpers;
            return response()->json(['code'=>200,'message' => $LiveModule->Transfer($user["name"], $request->money, $request->mode) ]);

        }else{
            return response()->json(['code'=>200,'message'=>"error"]);
        }
        
    }
   
    public function recall(Request $request){
        $user = auth()->user();
        if(isset($user["name"])){
            $LiveModule = new LiveCasinoHelpers;
            return response()->json(['code'=>200,'message' => $LiveModule->ReCall($user["name"]) ]);

        }else{
            return response()->json(['code'=>200,'message'=>"error"]);
        }
    }
    
    
}