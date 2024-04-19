<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/15/2016
 * Time: 2:49 PM
 */
namespace App\Http\Controllers;
use App\ChucNang;
use App\Helpers\HistoryHelpers;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use App\Helpers\RoleHelpers;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Helpers\NotifyHelpers;
use luk79\CryptoJsAes\CryptoJsAes;

class AdminController extends Controller
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
        if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 2)
            return redirect(url('/users'));
        else
        if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 3)
            return redirect(url('/rp/winlose'));
        else
            return view('admin.home',['roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'thongbao'=>NotifyHelpers::GetNotification(),'thongbao1'=>NotifyHelpers::GetNotification1(),'thongbao2'=>NotifyHelpers::GetNotification2(),'thongbao3'=>NotifyHelpers::GetNotification3()]);
    }

     /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getThongketheoma(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.home2',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }

    public function getThongkehoatdong(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.activehistory',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }

    public function getBangthau(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.thau_super',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }

    public function getXuatso(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.xuatso_super',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }

    /**
     * Hàm xử lí logout
     * @return \Illuminate\View\View
     */
    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return redirect(url('/admin'));
    }

    /**
     * Hàm xử thay đổi pass
     * @return \Illuminate\View\View
     */
    public function postChangePass(Request $request)
    {
        if (Auth::user()->roleid==6 && $request->userid != Auth::user()->id )
            return "false";
        
        if (Session::get('usersecondper') == 1 && $request->userid == Auth::user()->id){
            
            UserHelpers::changePass(Session::get('usersecondname'),$request->newpass);
            return "true";
        }else
        {
            $name = $request->session()->get('username');
            $user_tmp = UserHelpers::GetUserById($request->userid);
            
            if ($request->userid == Auth::user()->id ){
                $user = Auth::user();
                $user->password = \Hash::make($request->newpass);
                $user->lastcpw = date("Y-m-d H:i:s");
                $user->save();
            }else{
                $userChild = UserHelpers::GetAllUserV2(Auth::user());
                
                if (in_array($request->userid, $userChild) || $user_tmp->usfollow == Auth::user()->name)
                    UserHelpers::changePass($user_tmp->name,$request->newpass);
                else return 'false';
            }
                
            HistoryHelpers::ActiveHistorySave(Auth::user(),$user_tmp,"thay đổi mật khẩu","");
            // if ($name == $user_tmp->name)
                // $request->session()->flash('password',$request->newpass);
            return 'true';
        }
        return 'false';
    }

    /**
     * Hàm xử thay đổi pass api
     * @return \Illuminate\View\View
     */
    public function ChangePassAPI(Request $request)
    {
        $decryptRequest = CryptoJsAes::decryptRequest($request);
        $currentUser = Auth::user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $currentUser = User::where("name",Auth::user()->usfollow)->first();
        }
        if ($currentUser->roleid==6 && $decryptRequest->userid != $currentUser->id )
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);
        if ((isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ) && $decryptRequest->userid == $currentUser->id){
            UserHelpers::changePass(Auth::user()->name,$decryptRequest->newpass);
            return response()->json(['code'=>200,'message'=>'','data' => '' ]);
        }else
        {
            $user_tmp = UserHelpers::GetUserById($decryptRequest->userid);
            
            if ($decryptRequest->userid == $currentUser->id ){
                $user = $currentUser;
                $user->password = \Hash::make($decryptRequest->newpass);
                $user->lastcpw = date("Y-m-d H:i:s");
                $user->save();
            }else{
                $userChild = UserHelpers::GetAllUserV2($currentUser);
                if (in_array($decryptRequest->userid, $userChild) || $user_tmp->usfollow == $currentUser->name)
                    UserHelpers::changePass($user_tmp->name,$decryptRequest->newpass);
                else return response()->json(['code'=>400,'message'=>'','data' => '' ]);
            }
            HistoryHelpers::ActiveHistorySave($currentUser,$user_tmp,"thay đổi mật khẩu","");
            
            return response()->json(['code'=>200,'message'=>'','data' => '' ]);
        }
        return response()->json(['code'=>400,'message'=>'','data' => '' ]);
    }
    /**
     * Hàm xử lí check pass
     * @return \Illuminate\View\View
     */
    public function postCheckPass(Request $request)
    {
        if (Session::get('usersecondper') == 1){
            $pw = User::where("id",Session::get('usersecondid'))->first()->password;
            if (\Hash::check($request->oldpass,$pw) )
            // if($request->oldpass == $pass)
            {
                return 'true';
            }
        }else{
            if (\Hash::check($request->oldpass,Auth::user()->password) )
            // if($request->oldpass == $pass)
            {
                return 'true';
            }
        }
        return 'false';
    }

    /**
     *
     * @return Response
     */
    public function getHoatdongcanhan(Request $request)
    {
        $startDatetime = strtotime($request->startdate);
        $startDatenewformat = date('Y-m-d',$startDatetime);

        $endDatetime = strtotime($request->enddate);
        $endDatenewformat = date('Y-m-d',$endDatetime);

        // return $request->startdate;
        // echo $startDatenewformat;
        // echo $endDatenewformat;
        return HistoryHelpers::GetAllActiveHistoryByUser(Auth::user(),$startDatenewformat,$endDatenewformat);
    }

    /**
     *
     * @return Response
     */
    public function getHoatdongqly(Request $request)
    {
        $startDatetime = strtotime($request->startdate);
        $startDatenewformat = date('Y-m-d',$startDatetime);

        $endDatetime = strtotime($request->enddate);
        $endDatenewformat = date('Y-m-d',$endDatetime);

        // return $startDatenewformat;
        // echo $endDatenewformat;
        return HistoryHelpers::GetTargetActiveHistory(Auth::user(),$startDatenewformat,$endDatenewformat);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getReloadBetByCategory(Request $request)
    {
        return view('admin.reloadBetByCategory',['startDate'=> $request->startDate,'endDate'=> $request->endDate]);

    }
    
    // /**
    //  *
    //  * @return Response
    //  */
    // public function getHoatdongcanhan(Request $request)
    // {
    //     if(empty( $request->session()->get('username'))){
    //         return view ('admin.login',['playgame'=>0]);
    //     }
    //     if (Auth::user()->roleid==6)
    //         return view('frontend.home',['thongbao1'=>NotifyHelpers::GetNotification1()]);
    //     else{
    //         $chucnangClass = new ChucNang();
    //         // $thongbao = 
    //         if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 2)
    //             return redirect(url('/users'));
    //         else
    //         if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 3)
    //             return redirect(url('/rp/winlose'));
    //         else
    //             return view('admin.home',['roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'thongbao'=>NotifyHelpers::GetNotification(),'thongbao1'=>NotifyHelpers::GetNotification1(),'thongbao2'=>NotifyHelpers::GetNotification2(),'thongbao3'=>NotifyHelpers::GetNotification3()]);
    //     }
    // }
}