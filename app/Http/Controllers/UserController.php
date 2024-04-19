<?php
namespace App\Http\Controllers;
use App\ChucNang;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use App\Helpers\RoleHelpers;
use App\Bet;
use App\Commands\InitDataForNewUser;
use App\Commands\UpdateCustomerTypeByUserIdService;
use App\Helpers\GameHelpers;
use App\Helpers\HistoryHelpers;
use Exception;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use luk79\CryptoJsAes\CryptoJsAes;
use \Queue;
use Illuminate\Support\Str;

class UserController extends Controller {

    public function __construct()
    {
        $this->middleware('auth',['except' => ['postApiUserStore','postStorenewguest','getbalance','adjustbalance','createTokenTeleAPI']]);
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex(Request $request)
    {

        $chucnangModel = new ChucNang();
        if (!$chucnangModel->handleUserSecond(11) )
            return "Cannot access this page! Failed!!!";
        $user = Auth::user();
        return view
        (
            'admin.user.listuser',
            [
                'user_current' => $user,
                'roles'=> RoleHelpers::getAllRole(),
                'users'=> UserHelpers::GetAllUserChild($user),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'page_title'=>'Danh sách tài khoản'
            ]
        );
    }

    public function getUserSecond(Request $request)
    {
        $chucnangModel = new ChucNang();
        if (!$chucnangModel->handleUserSecond(17) )
            return "Cannot access this page! Failed!!!";
        $user = Auth::user();
        return view
        (
            'admin.user.listusersecond',
            [
                'user_current' => $user,
                'roles'=> RoleHelpers::getAllRole(),
                'users'=> UserHelpers::GetAllUserSecondChild($user),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'page_title'=>'Danh sách tài khoản'
            ]
        );
    }
    public function getUserChild(Request $request,$userid,$search="")
    {
        $chucnangModel = new ChucNang();
        if (!$chucnangModel->handleUserSecond(11) )
            return "Cannot access this page! Failed!!!";

        // $userChild = UserHelpers::GetAllUserV2(Auth::user());
            
        //     if (in_array($userid, $userChild) || $userid == Auth::user()->id){

        //     }else
        //         return "Cannot access this page! Failed!!!";
        $user = UserHelpers::GetUserById($userid);
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent) || $userid == Auth::user()->id){

        }else
            return "Cannot access this page! Failed!!!";

        $userChild = null;
        if ($search != "")
            $userChild = UserHelpers::GetUserByIdKey($userid,$search);
        else
            $userChild = UserHelpers::GetAllUserChild($user);
        //Auth::user();
        //Auth::user();
        return view
        (
            'admin.user.listuser',
            [
                'user_current' => $user,
                'roles'=> RoleHelpers::getAllRole(),
                'users'=> $userChild,
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'page_title'=>'Danh sách tài khoản',
                'search' => $search
            ]
        );
    }

    public function postUpdate(Request $request, $id)
    {
        $user_create = $request->session()->get('username');
        // \Log::info("user_create " .$user_create);
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        $userSecond = User::where('id',$id)->first();
        if (in_array($id, $userChild) || $userSecond->usfollow == Auth::user()->name )
        {
            return UserHelpers::UpdateUser($request,$id,$user_create);
        }else   
            return "failed";
    }

    public function postUpdateUserApi(Request $request)
    {
        $user_create = Auth::user()->name;
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
            $user_create = $user->name;
        }
        $decryptRequest = CryptoJsAes::decryptRequest($request);
        $id = $decryptRequest->id;
        
        // \Log::info("user_create " .$user_create);
        $userChild = UserHelpers::GetAllUserV2($user);
        $userSecond = User::where('id',$id)->first();
        // return $userChild;
        // return in_array($id, $userChild) ? "true" : "false";
        if (isset($userSecond) && (in_array($id, $userChild) || $userSecond->usfollow == Auth::user()->name ))
        {
            return response()->json(['code'=>200,'message'=>'','data'=>UserHelpers::UpdateUser($decryptRequest,$id,$user_create)]);
        }else   
            return response()->json(['code'=>400,'message'=>'failed','data'=>'']);
    }

    public function postUpdateCustomertype(Request $request, $id)
    {
        $user_create = $request->session()->get('username');
        // \Log::info("user_create " .$user_create);
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        $userSecond = User::where('id',$id)->first();
        if (in_array($id, $userChild) || $userSecond->usfollow == Auth::user()->name )
        {
            // return $id;
            $userMe  = User::where('id',$id)->first();
            // $userParent = User::where('id', $userMe->user_create)->first();
    
            $userMe->customer_type = $request->customertype;
            $userMe->save();
            Queue::pushOn("high",new UpdateCustomerTypeByUserIdService($request->customertype,$userMe));
            return 'true';
            // return GameHelpers::UpdateCustomerTypeByUserId($request->customertype,$id);
        }else   
            return "failed";
    }

    public function postStoreSecond(Request $request)
    {
        $name = $request->session()->get('username');
        $user = Auth::user();
        if ($this->CheckExistUser($request->username))
            return "false";
        return UserHelpers::InsertUserSecond($request,$user->id);
        // return "true";
    }

    public function postStorenewguest(Request $request)
    {
        if ($this->CheckExistUser($request->username))
            return "false";
        // print_r($request);
        // echo $request->rollback_money;
        // return "true";
        $newuser_id = UserHelpers::InsertGuest($request,1482);
        // \Queue::pushOn("high",new InitDataForNewUser($request,$request->user_current,'CustomerType_Game'));
        // \Queue::pushOn("high",new InitDataForNewUser($request,$request->user_current,'CustomerType_Game_Original'));
        
        return $newuser_id;
        // return UserHelpers::InsertUser($request,$user->id);
        // return "true";
    }

    public function postStore(Request $request)
    {
        $name = $request->session()->get('username');
        $user = Auth::user();
        // $userChild = UserHelpers::GetAllUserV2($user);
        // return $userChild;
        // if (in_array($request->user_current, $userChild))
        // {
        // }else   
        //     return "failed";

        if ($this->CheckExistUser($request->username) || $user->roleid==6)
            return "false";
        // print_r($request);
        // echo $request->rollback_money;
        // return "true";
        $newuser_id = UserHelpers::InsertUser($request,$user->id);
        // \Queue::pushOn("high",new InitDataForNewUser($request,$request->user_current,'CustomerType_Game'));
        // \Queue::pushOn("high",new InitDataForNewUser($request,$request->user_current,'CustomerType_Game_Original'));
        
        $targetUser = UserHelpers::GetUserById($newuser_id);
        $token = Str::random(24);
        $targetUser->token_bot_tele = $token;
        $targetUser->save();
        return response()->json(['code'=>200,'message'=>'','token' => $token ]);;
        // return UserHelpers::InsertUser($request,$user->id);
        // return "true";
    }

    public function postApiUserStore(Request $request)
    {
        $name = $request->session()->get('username');
        $decrypted =  CryptoJsAes::decryptRequest($request);
        // $user = Auth::user();
        // print_r($decrypted);
        // return;
        if ($this->CheckExistUser($decrypted->username))
            return response()->json(['code'=>202,'message'=>'Tài khoản đã tồn tại','data' => '' ]);
        if (auth()->user()->roleid == 6)
            return response()->json(['code'=>202,'message'=>'Bắt buộc sử dụng tài khoản quản lý để tạo.','data' => '' ]);
        
        switch (auth()->user()->roleid) {
            case 1: //admin
                $decrypted->role = 2;
                break;
            case 2: //super
                $decrypted->role = 4;
                break;
            case 4: //master
                $decrypted->role = 5;
                break;
            case 5: //agent
                $decrypted->role = 6;
                break;
            default:
                # code...
                break;
        }
        $decrypted->lock_price = isset($request->lock_price) ? $request->lock_price : 0;
        $decrypted->lock = isset($request->lock) ? $request->lock : 0;
        $decrypted->rollback_money = isset($request->rollback_money) ? $request->rollback_money : 1;
        $decrypted->copy_data = isset($request->copy_data) ? $request->copy_data : 'non';
        $decrypted->bet = isset($request->bet) ? $request->bet : 1;
        $decrypted->thau = isset($request->thau) ? $request->thau : 0;
        // return $request;
        return response()->json(['code'=>200,'message'=>'','data' => UserHelpers::InsertUser($decrypted,auth()->user()->id) ]);
    }

    private function CheckExistUser($username)
    {
        $user_tmp = UserHelpers::GetUserByUserName($username);
        if ($user_tmp != null && count($user_tmp) > 0)
            return true;
        return false;
    }
    public function postDestroy($id)
    {
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        if (in_array($id, $userChild))
        {
        }else   
            return "failed";
        UserHelpers::DeleteUser($id);
        return "true";
    }

    public function postResetotp($id)
    {
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        if (in_array($id, $userChild))
        {
        }else   
            return "failed";

        UserHelpers::ResetOTP($id);
        
        $userParent = Auth::user();
        $userTarget = User::where('id', $id)->first();
        HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"reset otp","");

        return "true";
    }

    public function postResetTokenTelegram($id)
    {
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        $userTarget = User::where('id', $id)->first();
        if (in_array($id, $userChild) || ($userTarget->per==1 && $userTarget->usfollow == Auth::user()->name))
        {
        }else   
            return "failed";

        // UserHelpers::ResetOTP($id);
        
        $userParent = Auth::user();
        $userTarget = User::where('id', $id)->first();

        $userTarget->fullname = "";
        $userTarget->token_bot_tele = "";
        $userTarget->chat_id = null;
        $userTarget->save();
        HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"reset otp","");

        return "true";
    }

    public function postActiveNotiTele($id)
    {
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        if (in_array($id, $userChild))
        {
        }else   
            return "failed";
        $userParent = Auth::user();
        $userTarget = User::where('id', $id)->first();
        $userTarget->active_noti_tele = 0;
        $userTarget->save();
        // HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"reset otp","");
        return "true";
    }

    public function postCreateTokenTele($id)
    {
        $targetUser = UserHelpers::GetUserById($id);
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        if (in_array($id, $userChild))
        {
            $token = Str::random(24);
            
            $targetUser->token_bot_tele = $token;
            $targetUser->save();

            $userParent = Auth::user();
            $userTarget = User::where('id', $id)->first();
            HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"tạo token telegram","");
            return response()->json(['code'=>200,'message'=>'','data' => $token ]);
        }else   
            if ($targetUser->per == 1 && $targetUser->usfollow == Auth::user()->name){
                $token = Str::random(24);
            
                $targetUser->token_bot_tele = $token;
                $targetUser->save();
                return response()->json(['code'=>200,'message'=>'','data' => $token ]);
            }
            return "failed";

        return "true";
    }

    public function createTokenTeleAPI(Request $request)
    {
        $requestD = CryptoJsAes::decryptRequest($request);
        $id = $requestD->id;
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $targetUser = UserHelpers::GetUserById($id);
        $userChild = UserHelpers::GetAllUserV2($user);

        
        if (in_array($id, $userChild))
        {
            $token = Str::random(24);
            
            $targetUser->token_bot_tele = $token;
            $targetUser->save();

            $userParent = Auth::user();
            $userTarget = User::where('id', $id)->first();
            HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"tạo token telegram","");
            return response()->json(['code'=>200,'message'=>'','data' => $token ]);
        }else   
            if ($targetUser->per == 1 && $targetUser->usfollow == Auth::user()->name){
                $token = Str::random(24);
            
                $targetUser->token_bot_tele = $token;
                $targetUser->save();
                return response()->json(['code'=>200,'message'=>'','data' => $token ]);
            }
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);

            return response()->json(['code'=>200,'message'=>'','data' => '' ]);
    }

    public function ResetTokenTelegramAPI(Request $request)
    {
        $requestD = CryptoJsAes::decryptRequest($request);
        $id = $requestD->id;
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $userChild = UserHelpers::GetAllUserV2($user);
        $userTarget = User::where('id', $id)->first();
        if (in_array($id, $userChild))
        {
        }else   
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);

        $userParent = Auth::user();
        $userTarget = User::where('id', $id)->first();

        $userTarget->fullname = "";
        $userTarget->token_bot_tele = "";
        $userTarget->chat_id = null;
        $userTarget->save();
        HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"reset token telegram","");

        return response()->json(['code'=>200,'message'=>'','data' => '' ]);
    }

    public function ResetOTPAPI(Request $request)
    {
        $requestD = CryptoJsAes::decryptRequest($request);
        $id = $requestD->id;
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $userChild = UserHelpers::GetAllUserV2($user);
        $userTarget = User::where('id', $id)->first();
        if (in_array($id, $userChild))
        {
        }else   
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);

        $userParent = Auth::user();
        $userTarget = User::where('id', $id)->first();

        // $userTarget->fullname = "";
        $userTarget->google2fa_secret = "";
        $userTarget->google2fa_secret_temp = "";
        // $userTarget->chat_id = null;
        $userTarget->save();
        HistoryHelpers::ActiveHistorySave($userParent,$userTarget,"reset otp","");

        return response()->json(['code'=>200,'message'=>'','data' => '' ]);
    }

    public function disableUserAPI(Request $request)
    {
        $requestD = CryptoJsAes::decryptRequest($request);
        $id = $requestD->id;
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $userChild = UserHelpers::GetAllUserV2($user);
        if (in_array($id, $userChild))
        {
        }else   
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);
        UserHelpers::DeleteUser($id);
        return response()->json(['code'=>200,'message'=>'','data' => '' ]);
    }

    public function postLocksecond($id)
    {
        $userChild = UserHelpers::GetAllUserV2(Auth::user());
        if (in_array($id, $userChild))
        {
        }else   
            return "failed";
        UserHelpers::LockUser($id);
        return "true";
    }

    public function postCheckUser(Request $request)
    {
        $user = null;
        if($request->type == 'email')
        {
            $user = UserHelpers::GetUserByUserEmail($request->key);
        }
        else
            $user = UserHelpers::GetUserByUserName($request->key);
        if(!isset($user[0]))
        {
            return 'true';
        }
        else
        {
            return 'false';
        }
    }
    public function getRefreshData(Request $request,$userid = -1)
    {
        if ($userid ==-1)
            $userid = Auth::user()->id;
        $chucnangModel = new ChucNang();
        //$roleModel = new Role();
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        $user = UserHelpers::GetUserById($userid);
        return view
        (
            'admin.user.tableuser',
            [
                'user_current' => $user,
                'roles'=> RoleHelpers::getAllRole(),
                'users'=> UserHelpers::GetAllUserChild($user),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
            ]
        );
    }

    public function getUsercreate(Request $request)
    {
        $chucnangModel = new ChucNang();
        $user = Auth::user();
        
        return view
        (
            'admin.user.UserCreate',
            [
                'roles'=> RoleHelpers::getAllRole(),
                'users'=> UserHelpers::GetAllUser($user),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'page_title'=>'Danh sách tài khoản'
            ]
        );
    }

    public function AccountsList(Request $request){
        $requestD = CryptoJsAes::decryptRequest($request);
        // return $requestD->id;
        $user = auth()->user();
        // $user = User::where("roleid",6)->select("id","name","fullname","roleid","credit","remain","updated_at","latestlogin","thau","lock")->get();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $dataReturn = [];
        if (isset($requestD->id)){
            $userChild = UserHelpers::GetAllUserV2($user);
            if (in_array($requestD->id, $userChild))
            {
                $dataReturn = UserHelpers::GetAllUserChild4API(User::where("id",$requestD->id)->first());
            }else   
                return response()->json(['code'=>400,'message'=>'','data' => '' ]);
        }else{
            $dataReturn = UserHelpers::GetAllUserChild4API($user);
        }
        return response()->json([
            'code'=>200,
            'message'=>'',
            'data' => $dataReturn
            //UserHelpers::GetAllUserChild($user)
        ]);
    }

    public function Account(Request $request){
        $requestD = CryptoJsAes::decryptRequest($request);
        $id = $requestD->id;
        $user = auth()->user();
        // $user = User::where("roleid",6)->select("id","name","fullname","roleid","credit","remain","updated_at","latestlogin","thau","lock")->get();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $userChild = UserHelpers::GetAllUserV2($user);
        if (in_array($id, $userChild))
        {
        }else   
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);
        return response()->json([
            'code'=>200,
            'message'=>'',
            'data' => UserHelpers::GetUserById4API($id)
            //UserHelpers::GetAllUserChild($user)
        ]);
    }
}
