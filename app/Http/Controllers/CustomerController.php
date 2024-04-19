<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/15/2016
 * Time: 2:49 PM
 */
namespace App\Http\Controllers;
use App\ChucNang;
use App\Commands\UpdateCustomerTypeGame;
use App\Commands\UpdateCustomerTypeGameOriginal;
use App\Helpers\GameHelpers;
use App\Helpers\HistoryHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\XoSo;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use Exception;
use Session;
use \Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use luk79\CryptoJsAes\CryptoJsAes;

class CustomerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
    */
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex(Request $request)
    {
        $chucnangClass = new ChucNang();
        if (!$chucnangClass->handleUserSecond(22) )
            return "Cannot access this page! Failed!!!";
        return view('admin.customer_type.customertype_tab',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'locationId'=>1]);
    }

    public function getNormal(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.customer_type.customertype',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'locationId'=>1]);
    }

    public function getNormalMienBac(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.customer_type.customertype',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'locationId'=>1]);
    }

    public function getNormalXsAo(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.customer_type.customertype',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'locationId'=>4]);
    }

    public function getLoadTypeGame(Request $request,$code)
    {
        $user = Auth::user();
        if($user->roleid==1)
        {
            return view('admin.customer_type.type_game_vertical',[
                'games'=>GameHelpers::GetAllGameByCusType($code,$user->id,0),
                'user'=>$user,
                'type'=>$code
            ]);
        }
        else
        {
            return view('admin.customer_type.type_game_vertical',[
                'games'=>GameHelpers::GetAllGameByCusType($code,$user->id),
                'games_parent'=>GameHelpers::GetAllGameParentByCusType($code,$user->id),
                'user'=>$user,
                'type'=>$code
            ]);
        }

    }

    public function getLoadTypeGameOriginal(Request $request,$code)
    {
        $user = Auth::user();
        if($user->roleid==1)
        {
            return view('admin.customer_type.type_game_original',[
                'games'=>GameHelpers::GetAllGameParentByCusType($code,$user->id),
                'user'=>$user,
                'type'=>$code
            ]);
        }
        else
        {
            return view('admin.customer_type.type_game_original',[
                'games'=>GameHelpers::GetAllGameParentByCusType($code,$user->id),
                'games_parent'=>GameHelpers::GetAllGameParentByCusType($code,$user->id),
                'user'=>$user,
                'type'=>$code
            ]);
        }

    }

    public function getOriginal(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.customer_type.customertypeoriginal',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }

    public function getLoadTypeGameByUser(Request $request,$code,$userid)
    {
        $currentuser = UserHelpers::GetUserById($userid);
        $user = Auth::user();
        // if($user->roleid==1)
        // {
        //     return view('admin.customer_type.type_game',[
        //         'games'=>GameHelpers::GetAllGameByCusType($code,$userid),
        //         'user'=>$currentuser,
        //         'type'=>$code
        //     ]);
        // }
        // else
        if($currentuser->roleid != 6)
        {
            return view('admin.customer_type.type_game_vertical',[
                'games'=>GameHelpers::GetAllGameParentByCusType($code,$userid),
                'games_parent'=>GameHelpers::GetAllGameByCusType($code,$currentuser->user_create),
                'user'=>$currentuser,
                'type'=>$code
            ]);
        }else{
            return view('admin.customer_type.type_game_vertical',[
                'games'=>GameHelpers::GetAllGameByCusType($code,$userid),
                'games_parent'=>GameHelpers::GetAllGameByCusType($code,$currentuser->user_create),
                'user'=>$currentuser,
                'type'=>$code
            ]);
        }
    }

    public function getLoadTypeGameLowpByUser(Request $request,$code,$userid)
    {
        
        $user = Auth::user();
        if ($user->lock == 2)
            return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
        // if ($user->lock == 1)
        //     return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);

        $currentuser = UserHelpers::GetUserById($userid);

        // if($user->roleid==1)
        // {
        //     return view('admin.customer_type.type_game',[
        //         'games'=>GameHelpers::GetAllGameByCusType($code,$userid),
        //         'user'=>$currentuser,
        //         'type'=>$code
        //     ]);
        // }
        // else
        if($currentuser->roleid != 6)
        {
            return view('admin.customer_type.type_game_lowp',[
                'games'=>GameHelpers::GetAllGameParentByCusType($code,$userid),
                'games_parent'=>GameHelpers::GetAllGameByCusType($code,$currentuser->user_create),
                'user'=>$currentuser,
                'type'=>$code
            ]);
        }else{
            return view('admin.customer_type.type_game_lowp_vertical',[
                'games'=>GameHelpers::GetAllGameParentByCusType($code,$userid),
                'games_parent'=>GameHelpers::GetAllGameByCusType($code,$currentuser->user_create),
                'user'=>$currentuser,
                'type'=>$code
            ]);
        }
    }

    public function loadTypeGameByUserAPI(Request $request)
    {
        try{
            $requestD = CryptoJsAes::decryptRequest($request);
        
            $user = auth()->user();
            if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
                $user = User::where("name",Auth::user()->usfollow)->first();
            }
            // return $user->name;
            $code = $user->customer_type;
            $code = $code != '' ? $code : 'A';
            if ($user->roleid == 6){
                return response()->json(['code'=>200,'message'=>'',
                'data' => [
                    "type".$code =>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI($code,$user->id,1))
                ]
                ]);
            }else{
                return response()->json(['code'=>200,'message'=>'',
                'data' => [
                    "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('A',$user->id,1)),
                    "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('B',$user->id,1)),
                    "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('C',$user->id,1)),
                    "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('D',$user->id,1))
                ]
                ]);
            }
            
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }
    }

    public function loadTypeGameAPI(Request $request)
    {
        $requestD = CryptoJsAes::decryptRequest($request);
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        // if($user->roleid==1)
        // {
        //     return response()->json(['code'=>200,'message'=>'',
        //         'data' => [
        //             "game"=>[
        //                 "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('A',$user->id,1)),
        //                 "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('B',$user->id,1)),
        //                 "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('C',$user->id,1)),
        //                 "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('D',$user->id,1))
        //             ]
        //         ]
        //         ]);
        // }
        // else
        {
            return response()->json(['code'=>200,'message'=>'',
                'data' => [
                    "game"=>[
                        "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('A',$user->id,$requestD->location)),
                        "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('B',$user->id,$requestD->location)),
                        "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('C',$user->id,$requestD->location)),
                        "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameByCusTypeAPI('D',$user->id,$requestD->location))
                    ],
                    'games_parent'=>[
                        "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('A',$user->user_create,$requestD->location)),
                        "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('B',$user->user_create,$requestD->location)),
                        "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('C',$user->user_create,$requestD->location)),
                        "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('D',$user->user_create,$requestD->location))
                    ]
                ]
                ]);
        }
    }

    public function loadTypeGameOriginalAPI(Request $request)
    {
        $requestD = CryptoJsAes::decryptRequest($request);
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        // if($user->roleid==1)
        // {
        //     return response()->json(['code'=>200,'message'=>'',
        //         'data' => [
        //             "games"=>[
        //                 "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('A',$user->id,1)),
        //                 "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('B',$user->id,1)),
        //                 "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('C',$user->id,1)),
        //                 "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('D',$user->id,1))
        //             ]
        //         ]
        //         ]);
        // }
        // else
        {
            return response()->json(['code'=>200,'message'=>'',
                'data' => [
                    "games"=>[
                        "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('A',$user->id,$requestD->location)),
                        "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('B',$user->id,$requestD->location)),
                        "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('C',$user->id,$requestD->location)),
                        "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('D',$user->id,$requestD->location))
                    ],
                    "games_parent"=>[
                        "typeA"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('A',$user->user_create,$requestD->location)),
                        "typeB"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('B',$user->user_create,$requestD->location)),
                        "typeC"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('C',$user->user_create,$requestD->location)),
                        "typeD"=>CryptoJsAes::encryptData(GameHelpers::GetAllGameParentByCusTypeAPI('D',$user->user_create,$requestD->location))
                    ]
                ]
                ]);
        }
    }

    public function postStore(Request $request)
    {
        $user = Auth::user();
        if ($user->roleid == 6) return "false";
        for($i=0;$i<count($request->changes); $i++)
        {
            // GameHelpers::UpdateCustomerTypeGame($request->changes[$i],$user->id);
            Queue::pushOn("high",new UpdateCustomerTypeGame($request->changes[$i],$user->id));
        }
        return $request;
    }

    public function postStorelowp(Request $request)
    {
        $user = Auth::user();
        for($i=0;$i<count($request->changes); $i++)
        {
            GameHelpers::UpdateCustomerTypeGameOriginalLowp($request->changes[$i],$user->id);
        }
        return $request;
    }

    public function postStoreByUser(Request $request)
    {
        // $user = Auth::user();

        $user = UserHelpers::GetUserById($request->userid);
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent)){
            for($i=0;$i<count($request->changes); $i++)
            {
                $user = UserHelpers::GetUserById($request->userid);
                if ($user->roleid != 6){
                    // GameHelpers::UpdateCustomerTypeGameOriginal($request->changes[$i],$request->userid);
                    Queue::pushOn("high",new UpdateCustomerTypeGameOriginal($request->changes[$i],$request->userid));
                    //Get custom type user
                    // $ct = GameHelpers::GetAllGameByCusType($code,$request->userid);
                }
                else
                    // GameHelpers::UpdateCustomerTypeGame($request->changes[$i],$request->userid);
                    Queue::pushOn("high",new UpdateCustomerTypeGame($request->changes[$i],$request->userid));
            }

            $userMain = Auth::user();
            $userTarget = User::where('id', $request->userid)->first();
            $userParent = User::where('id', $userTarget->user_create)->first();
            
            HistoryHelpers::ActiveHistorySave($userMain,$userTarget,"thay đổi thông số ","");
            if ($userMain->id != $userParent->id)
                HistoryHelpers::ActiveHistorySave($userMain,$userParent,"thay đổi thông số " . XoSoRecordHelpers::GetRoleName($userTarget->roleid) . " " . $userTarget->name ." của","");

            return $request;
        }else
            return "false";
        return 'ok';

        // $userChild = UserHelpers::GetAllUserV2(Auth::user());
        // if (in_array($request->userid, $userChild))
        // {
        //     for($i=0;$i<count($request->changes); $i++)
        //     {
        //         $user = UserHelpers::GetUserById($request->userid);
        //         if ($user->roleid != 6){
        //             GameHelpers::UpdateCustomerTypeGameOriginal($request->changes[$i],$request->userid);
        //             //Get custom type user
        //             // $ct = GameHelpers::GetAllGameByCusType($code,$request->userid);
        //         }
        //         else
        //             GameHelpers::UpdateCustomerTypeGame($request->changes[$i],$request->userid);
        //     }

        //     $userMain = Auth::user();
        //     $userTarget = User::where('id', $request->userid)->first();
        //     $userParent = User::where('id', $userTarget->user_create)->first();
            
        //     HistoryHelpers::ActiveHistorySave($userMain,$userTarget,"thay đổi thông số ","");
        //     if ($userMain->id != $userParent->id)
        //         HistoryHelpers::ActiveHistorySave($userMain,$userParent,"thay đổi thông số " . XoSoRecordHelpers::GetRoleName($userTarget->roleid) . " " . $userTarget->name ." của","");

        //     return $request;
        // }else
        //     return "false";
    }

    public function postUpdateAPICustomerTypeGameBySelf(Request $request)
    {
        $user = Auth::user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $user_create = Auth::user()->name;
        $decryptRequest = CryptoJsAes::decryptRequest($request);
        if ($user->roleid == 6) return "false";
        for($i=0;$i<count($decryptRequest->changes); $i++)
        {
            // GameHelpers::UpdateCustomerTypeGame($request->changes[$i],$user->id);
            Queue::pushOn("high",new UpdateCustomerTypeGame($decryptRequest->changes[$i],$user->id));
        }
        return response()->json(['code'=>200,'message'=>'','data'=>'']);
    }

    public function postUpdateAPICustomerTypeGameByUser(Request $request)
    {
        // $user = Auth::user();
        // $user_create = Auth::user()->name;
        $decryptRequest = CryptoJsAes::decryptRequest($request);
        $user = UserHelpers::GetUserById($decryptRequest->userid);
        $userParent = UserHelpers::GetAllUserParentV2($user);

        $user_create = Auth::user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user_create = User::where("name",Auth::user()->usfollow)->first();
        }
        if (in_array($user_create->id, $userParent)){
            for($i=0;$i<count($decryptRequest->changes); $i++)
            {
                $user = UserHelpers::GetUserById($decryptRequest->userid);
                if ($user->roleid != 6){
                    // GameHelpers::UpdateCustomerTypeGameOriginal($request->changes[$i],$request->userid);
                    Queue::pushOn("high",new UpdateCustomerTypeGameOriginal($decryptRequest->changes[$i],$decryptRequest->userid));
                    //Get custom type user
                    // $ct = GameHelpers::GetAllGameByCusType($code,$request->userid);
                }
                else
                    // GameHelpers::UpdateCustomerTypeGame($request->changes[$i],$request->userid);
                    Queue::pushOn("high",new UpdateCustomerTypeGame($decryptRequest->changes[$i],$decryptRequest->userid));
            }

            $userMain = $user_create;
            $userTarget = User::where('id', $decryptRequest->userid)->first();
            $userParent = User::where('id', $userTarget->user_create)->first();
            
            HistoryHelpers::ActiveHistorySave($userMain,$userTarget,"thay đổi thông số ","");
            if ($userMain->id != $userParent->id)
                HistoryHelpers::ActiveHistorySave($userMain,$userParent,"thay đổi thông số " . XoSoRecordHelpers::GetRoleName($userTarget->roleid) . " " . $userTarget->name ." của","");

            return response()->json(['code'=>200,'message'=>'','data'=>'']);
        }else
            return response()->json(['code'=>404,'message'=>'failed permission','data'=>'']);
        return response()->json(['code'=>200,'message'=>'','data'=>'']);
    }
}