<?php

namespace App\Http\Controllers;

use Crypt;
use Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;
use App\ChucNang;
use App\Helpers\NotifyHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Notification;
use App\Notification2;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new authentication controller instance.
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
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
        
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";
    
            return view('admin.ggauth.ggauthhome',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            return view('frontend.ggauth.ggauthhome');
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getCreate(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";
    
            return view('admin.notification.create',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getList(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";
    
            return view('admin.notification.list',['notifications'=> NotifyHelpers::showNotification($request) ,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getListBet(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";
    
                $stDate= date("Y-m-d");
                $endDate= date("Y-m-d");
                $history = XoSoRecordHelpers::getRecordKhachByDateHistorytv2(Auth::user(),$stDate,$endDate,$type="all");
                // return $history;
                return view('admin.notification.list-bets',['notifications'=> $history ,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getFilterBets(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            
            $startdateR = $request->startdate;
            $enddateR = $request->enddate;
            $category = $request->category;

            $time = strtotime($startdateR);
            $stDate = date('Y-m-d',$time);

            $time = strtotime($enddateR);
            $endDate = date('Y-m-d',$time);

            $history = XoSoRecordHelpers::getRecordKhachByDateHistorytv2(Auth::user(),$stDate,$endDate,$category);

            return view('admin.notification.list_filter_bets',['notifications'=> $history]);
            // return "<div></div>";
        }else{
            $chucnangClass = new ChucNang();
            // return "<div></div>";
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }

    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getFilter(Request $request)
    {
        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            
            return view('admin.notification.list_filter',['notifications'=> NotifyHelpers::showNotification($request)]);
            // return "<div></div>";
        }else{
            $chucnangClass = new ChucNang();
            // return "<div></div>";
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }

    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function postStore(Request $request)
    {
        if (Auth::user()->roleid == 1){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(201) )
                return "Cannot access this page! Failed!!!";
            return NotifyHelpers::storeNotification2($request);
        }else{
            $chucnangClass = new ChucNang();
            return "Cannot access this page! Failed!!!";
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function postUpdate(Request $request)
    {
        if (Auth::user()->roleid == 1){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(201) )
                return "Cannot access this page! Failed!!!";
            return NotifyHelpers::updateNotification22($request);
        }else{
            $chucnangClass = new ChucNang();
            return "Cannot access this page! Failed!!!";
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function postUpdateRead(Request $request)
    {
        $notify = Notification2::where('id',$request->id)->first();
        if ($notify->target == Auth::user()->name){
            $notify->pin = 0;
            $notify->save();
        }
        return "ok";
    }

    /**
     * Generate a secret key in Base32 format
     *
     * @return string
     */
    private function generateSecret()
    {
        $randomBytes = random_bytes(10);

        return Base32::encodeUpper($randomBytes) ;
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getMember(Request $request)
    {
        if (Auth::user()->roleid == 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";

            $total_message = NotifyHelpers::showNotificationByUserid();
            return view('frontend.notification_member',['notifications' => $total_message]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getMemberDetail(Request $request)
    {
        if (Auth::user()->roleid == 6){
            $chucnangClass = new ChucNang();
            if (!$chucnangClass->handleUserSecond(41) )
                return "Cannot access this page! Failed!!!";
            // return $request->id;
            $message = Notification2::where("id",$request->id)->first();
            // var_dump($message);
            // return $message;
            return view('frontend.notification_member_detail',['message' => $message]);
        }else{
            $chucnangClass = new ChucNang();
            return view('admin.404',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
        
    }
}
