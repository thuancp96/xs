<?php
namespace App\Http\Controllers;
use App\ChucNang;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use App\Helpers\RoleHelpers;
use App\Bet;
use App\XoSoRecord;
use App\Helpers\XoSoRecordHelpers;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use \Cache;
use DateInterval;
use DatePeriod;
use DateTime;
use luk79\CryptoJsAes\CryptoJsAes;

class ReportController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $chucnangModel = new ChucNang();
        $user = Auth::user();
        return view
        (
            'admin.report.historybetdetail',
            [
                'xosorecords'=> XoSoRecordHelpers::getAll($user),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
            ]
        );

    }
    public function getHistory(Request $request)
    {
        $chucnangModel = new ChucNang();
        $user = Auth::user();
        return view
        (
            'admin.report.historybetdetail',
            [
                'xosorecords'=> XoSoRecordHelpers::getAll($user),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
            ]
        );
    }
    public function getWinlose(Request $request)
    {
        $stDate = $request->stdate;
        $endDate = $request->enddate;
        $searchKey=$request->searchkey;
        $type = $request->type != "" ? $request->type : "all";

        if ($stDate==null){
            $stDate=date("d-m-Y");
            if (date('H') < 11)
                $stDate=date("d-m-Y",strtotime('-1 day',strtotime($stDate)));
        }
        else {
            $time = strtotime($stDate);
            $stDate = date('d-m-Y',$time);
        }
        if ($endDate==null){
            $endDate=date("d-m-Y");
            if (date('H') < 11)
                $endDate=date("d-m-Y",strtotime('-1 day',strtotime($endDate)));
        }
        else {
            $time = strtotime($endDate);
            $endDate = date('d-m-Y',$time);
        }
        $chucnangModel = new ChucNang();
        
        if (!$chucnangModel->handleUserSecond(31) )
            return "Cannot access this page! Failed!!!";

        $user = Auth::user();
        $userChild = null;
        if ($searchKey == ""){
            $userChild = UserHelpers::GetAllUserChild($user,2);
        }else
            $userChild = UserHelpers::GetAllUserV2ByKey4Report($user->id,$searchKey);
            // $userChild = UserHelpers::GetAllUserV2ByKey($user->id,$searchKey);
            //UserHelpers::GetAllUserV2ByKey4Report($user->id,$searchKey);
        // return $searchKey;
        if($user->roleid==1)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'roles'=> RoleHelpers::getAllRole(),
                    'userTarget'=>$user,
                    'users'=> $userChild,
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'input_search' => $searchKey,
                    'type' => $type,
                    'type_page' => "winlose"
                ]
            );
        }
        else
        if($user->roleid==2 || $user->roleid==4 || $user->roleid==5)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'roles'=> RoleHelpers::getAllRole(),
                    'userTarget'=>$user,
                    'users'=> $userChild,//UserHelpers::GetAllUserChild($user),//array($user),//UserHelpers::GetAllUserChild($user),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'input_search' => $searchKey,
                    'type' => $type,
                    'type_page' => "winlose"
                ]
            );
        }
        else
        {
            // $newDate = date("Y-m-d");
            return view
            (
                'admin.report.winlosedetail',
                [
                    'user'=>$user,
                    'xosorecords'=> XoSoRecordHelpers::getAll($user),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'type' => $type
                ]
            );
        }
    }

    public function getBettoday(Request $request)
    {
        $stDate = $request->stdate;
        $endDate = $request->enddate;
        $searchKey=$request->searchkey;
        $type = $request->type != "" ? $request->type : "all";

        if ($stDate==null){
            $stDate=date("d-m-Y");
            if (date('H') < 11)
                $stDate=date("d-m-Y",strtotime('-1 day',strtotime($stDate)));
        }
        else {
            $time = strtotime($stDate);
            $stDate = date('d-m-Y',$time);
        }
        if ($endDate==null){
            $endDate=date("d-m-Y");
            if (date('H') < 11)
                $endDate=date("d-m-Y",strtotime('-1 day',strtotime($endDate)));
        }
        else {
            $time = strtotime($endDate);
            $endDate = date('d-m-Y',$time);
        }

        $chucnangModel = new ChucNang();
        
        if (!$chucnangModel->handleUserSecond(32) )
            return "Cannot access this page! Failed!!!";

        $user = Auth::user();
        $userChild = null;
        if ($searchKey == ""){
            $userChild = UserHelpers::GetAllUserChild($user,2);
        }else
            $userChild = UserHelpers::GetAllUserV2ByKey4Report($user->id,$searchKey);

        if($user->roleid==1)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'roles'=> RoleHelpers::getAllRole(),
                    'userTarget'=>$user,
                    'users'=> $userChild,
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'input_search' => $searchKey,
                    'type' => $type,
                    'type_page' => "cxl"
                ]
            );
        }
        else
        if($user->roleid==2 || $user->roleid==4 || $user->roleid==5)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'roles'=> RoleHelpers::getAllRole(),
                    'userTarget'=>$user,
                    'users'=> $userChild,//UserHelpers::GetAllUserChild($user),//array($user),//UserHelpers::GetAllUserChild($user),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'input_search' => $searchKey,
                    'type' => $type,
                    'type_page' => "cxl"
                ]
            );
        }
        else
        {
            // $newDate = date("Y-m-d");
            return view
            (
                'admin.report.bettodaydetail',
                [
                    'user'=>$user,
                    'xosorecords'=> XoSoRecordHelpers::getAll($user),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'type' => $type
                ]
            );
        }
    }

    public function getBettodayDetail(Request $request)
    {
        $stDate = $request->stdate;
        $endDate = $request->enddate;
        $searchKey=$request->searchkey;
        $type = $request->type != "" ? $request->type : "all";
        $user_id = $request->user;

        $chucnangModel = new ChucNang();
        if (!$chucnangModel->handleUserSecond(32) )
        return "Cannot access this page! Failed!!!";

        $user = UserHelpers::GetUserById($user_id);
        
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent) || $user_id == Auth::user()->id){

        }else
            return "Cannot access this page! Failed!!!";

        // $user = UserHelpers::GetUserById($user_id);
        if($user->roleid==1 || $user->roleid==2 || $user->roleid==4 || $user->roleid==5)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'userTarget'=>$user,
                    'roles'=> RoleHelpers::getAllRole(),
                    'users'=> UserHelpers::GetAllUserChild($user,2),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'type' => $type,
                    'type_page' => "cxl"
                ]
            );
        }
        else
        {
            // $newDate = date("d-m-Y");
            $cacheTime = env('CACHE_TIME_SHORT', 0);
            $endTimeStamp = strtotime($endDate);
            $endDateNewformat = date('Y-m-d',$endTimeStamp);
            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                $cacheTime = 1440*10;
                // echo $cacheTime;
                // Cache::forget('XoSoRecordHelpers-ReportKhachv20230115'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type);
            // if ($endDateNewformat == '2023-05-24')
            //     Cache::forget('XoSoRecordHelpers-RecordKhachChuaXulyv20230531-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type);
            $xsrecords = 
            // XoSoRecordHelpers::getRecordKhachChuaXulyByDatev2($user,$stDate,$endDate,$type);
            Cache::remember('XoSoRecordHelpers-RecordKhachChuaXulyv20240209-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, $cacheTime, function () use ($user,$stDate,$endDate,$type) {
                return  XoSoRecordHelpers::getRecordKhachChuaXulyByDatev2($user,$stDate,$endDate,$type);
            });
            // echo $type . " " . count($xsrecords);
            return view
            (
                'admin.report.winlosedetail',
                [
                    'user'=>$user,
                    'xosorecords'=> $xsrecords,
                    // XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type),
                    // 'xosorecords'=> XoSoRecordHelpers::GetByUserByDate($user,$newDate),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'type' => $type,
                    'type_page' => "cxl"
                ]
            );


            // $newDate = date("d-m-Y");
            // return view
            // (
            //     'admin.report.bettodaydetail',
            //     [
            //         'user'=>$user,
            //         'xosorecords'=> XoSoRecordHelpers::getRecordKhachChuaXulyByDate($user,$stDate,$endDate),
            //         // 'xosorecords'=> XoSoRecordHelpers::GetByUserByDate($user,$newDate),
            //         'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
            //         'stDate' => $stDate,
            //         'endDate' => $endDate
            //     ]
            // );
        }

    }

    public function getBetcancel(Request $request)
    {
        $stDate = $request->stdate;
        $endDate = $request->enddate;
        $searchKey=$request->searchkey;
        $type = $request->type != "" ? $request->type : "all";

        if ($stDate==null){
            $stDate=date("d-m-Y");
            if (date('H') < 11)
                $stDate=date("d-m-Y",strtotime('-1 day',strtotime($stDate)));
        }
        else {
            $time = strtotime($stDate);
            $stDate = date('d-m-Y',$time);
        }
        if ($endDate==null){
            $endDate=date("d-m-Y");
            if (date('H') < 11)
                $endDate=date("d-m-Y",strtotime('-1 day',strtotime($endDate)));
        }
        else {
            $time = strtotime($endDate);
            $endDate = date('d-m-Y',$time);
        }
        $chucnangModel = new ChucNang();
        if (!$chucnangModel->handleUserSecond(33) )
        return "Cannot access this page! Failed!!!";

        $user = Auth::user();
        $userChild = null;
        if ($searchKey == ""){
            $userChild = UserHelpers::GetAllUserChild($user,2);
        }else
            $userChild = UserHelpers::GetAllUserV2ByKey4Report($user->id,$searchKey);
            // $userChild = UserHelpers::GetAllUserV2ByKey($user->id,$searchKey);
            //UserHelpers::GetAllUserV2ByKey4Report($user->id,$searchKey);
        // return $searchKey;
        if($user->roleid==1)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'roles'=> RoleHelpers::getAllRole(),
                    'userTarget'=>$user,
                    'users'=> $userChild,
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'input_search' => $searchKey,
                    'type' => $type,
                    'type_page' => "cancel"
                ]
            );
        }
        else
        if($user->roleid==2 || $user->roleid==4 || $user->roleid==5)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'roles'=> RoleHelpers::getAllRole(),
                    'userTarget'=>$user,
                    'users'=> $userChild,//UserHelpers::GetAllUserChild($user),//array($user),//UserHelpers::GetAllUserChild($user),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'input_search' => $searchKey,
                    'type' => $type,
                    'type_page' => "cancel"
                ]
            );
        }
        else
        {
            // $newDate = date("Y-m-d");
            return view
            (
                'admin.report.betcanceldetail',
                [
                    'user'=>$user,
                    'xosorecords'=> XoSoRecordHelpers::getAll($user),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
                ]
            );
        }
    }

    public function getBetcancelDetail(Request $request)
    {
        $stDate = $request->stdate;
        $endDate = $request->enddate;
        $searchKey=$request->searchkey;
        $type = $request->type != "" ? $request->type : "all";
        $user_id = $request->user;

        $chucnangModel = new ChucNang();

        if (!$chucnangModel->handleUserSecond(33) )
        return "Cannot access this page! Failed!!!";

        $user = UserHelpers::GetUserById($user_id);
        
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent) || $user_id == Auth::user()->id){

        }else
            return "Cannot access this page! Failed!!!";

        // $user = UserHelpers::GetUserById($user_id);
        if($user->roleid==1 || $user->roleid==2 || $user->roleid==4 || $user->roleid==5)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'userTarget'=>$user,
                    'roles'=> RoleHelpers::getAllRole(),
                    'users'=> UserHelpers::GetAllUserChild($user,2),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'type' => $type,
                    'type_page' => "cancel"
                ]
            );
        }
        else
        {
            // $newDate = date("d-m-Y");
            $cacheTime = env('CACHE_TIME_SHORT', 0);
            $endTimeStamp = strtotime($endDate);
            $endDateNewformat = date('Y-m-d',$endTimeStamp);
            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                $cacheTime = 1440*10;
                // echo $cacheTime;
                // Cache::forget('XoSoRecordHelpers-ReportKhachv20230115'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type);
            if ($endDateNewformat == '2023-05-24')
                Cache::forget('XoSoRecordHelpers-RecordKhachCancelv202305291-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type);
            $xsrecords = 
            // XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type);
            Cache::remember('XoSoRecordHelpers-RecordKhachCancelv202305291-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, $cacheTime, function () use ($user,$stDate,$endDate,$type) {
                return  XoSoRecordHelpers::getRecordKhachCancelByDatev2($user,$stDate,$endDate,$type);
            });
            return view
            (
                'admin.report.winlosedetail',
                [
                    'user'=>$user,
                    'xosorecords'=> $xsrecords,
                    // XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type),
                    // 'xosorecords'=> XoSoRecordHelpers::GetByUserByDate($user,$newDate),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'type' => $type,
                    'type_page' => "cancel"
                ]
            );

        }

    }

    public function getWinloseDetail(Request $request)
    {
        $stDate = $request->stdate;
        $endDate = $request->enddate;
        $searchKey=$request->searchkey;
        $type = $request->type != "" ? $request->type : "all";
        $user_id = $request->user;

        $chucnangModel = new ChucNang();
        if (!$chucnangModel->handleUserSecond(31) )
        return "Cannot access this page! Failed!!!";

        $user = UserHelpers::GetUserById($user_id);
        
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent) || $user_id == Auth::user()->id){

        }else
            return "Cannot access this page! Failed!!!";

        // if (Auth::user()->roleid >= $user->roleid) return "failed";
        if($user->roleid==1 || $user->roleid==2 || $user->roleid==4 || $user->roleid==5)
        {
            return view
            (
                'admin.report.winlosedetail_admin',
                [
                    'userTarget'=>$user,
                    'roles'=> RoleHelpers::getAllRole(),
                    'users'=> UserHelpers::GetAllUserChild($user,2),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'type' => $type,
                    'type_page' => "winlose"
                ]
            );
        }
        else
        {
            // $newDate = date("d-m-Y");
            $cacheTime = env('CACHE_TIME_SHORT', 0);
            $endTimeStamp = strtotime($endDate);
            $endDateNewformat = date('Y-m-d',$endTimeStamp);
            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                $cacheTime = 1440*10;
                // echo $cacheTime;
                // Cache::forget('XoSoRecordHelpers-ReportKhachv20230115'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type);
            // if ($endDateNewformat == '2023-05-24')
            //     Cache::forget('XoSoRecordHelpers-ReportKhachv20230529-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type);
            $xsrecords = 
            // XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type);
            Cache::remember('XoSoRecordHelpers-ReportKhachv20240209-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, $cacheTime, function () use ($user,$stDate,$endDate,$type) {
                return  XoSoRecordHelpers::getRecordKhachByDatev2($user,$stDate,$endDate,$type);
            });
            return view
            (
                'admin.report.winlosedetail',
                [
                    'user'=>$user,
                    'xosorecords'=> $xsrecords,
                    // XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type),
                    // 'xosorecords'=> XoSoRecordHelpers::GetByUserByDate($user,$newDate),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                    'stDate' => $stDate,
                    'endDate' => $endDate,
                    'type' => $type,
                    'type_page' => "winlose"
                ]
            );
        }

    }

    public function reportBetAPI(Request $request)
    {
        
        $adminShow = true;
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }

        $requestD = CryptoJsAes::decryptRequest($request);
        $type_page = $requestD->page;
        $type = $requestD->type;
        $stDate = $requestD->stDate;
        $endDate = $requestD->endDate;

        $userChild = UserHelpers::GetAllUserV2($user);
        if (in_array($requestD->user_id, $userChild))
        {
        }else   
            return response()->json(['code'=>400,'message'=>'','data' => '' ]);

        if(isset($requestD->user_id)){
            $user = User::where("id",$requestD->user_id)->first();
        }
        $userTarget = $user;

        if ($userTarget->roleid == 6){
            // $newDate = date("d-m-Y");
            $cacheTime = env('CACHE_TIME_SHORT', 0);
            $endTimeStamp = strtotime($endDate);
            $endDateNewformat = date('Y-m-d',$endTimeStamp);
            if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                $cacheTime = 1440*10;
            
            if ($type_page == 'winlose')
                    $xsrecords = Cache::remember('XoSoRecordHelpers-ReportKhachv20240209-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, $cacheTime, function () use ($user,$stDate,$endDate,$type) {
                        return  XoSoRecordHelpers::getRecordKhachByDatev2($user,$stDate,$endDate,$type);
                    });

                if ($type_page == 'cxl')
                    $xsrecords = 
                    // XoSoRecordHelpers::getRecordKhachChuaXulyByDatev2($user,$stDate,$endDate,$type);
                    Cache::remember('XoSoRecordHelpers-RecordKhachChuaXulyv20240209-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, $cacheTime, function () use ($user,$stDate,$endDate,$type) {
                        return  XoSoRecordHelpers::getRecordKhachChuaXulyByDatev2($user,$stDate,$endDate,$type);
                    });

                if ($type_page == 'cancel')
                    $xsrecords = 
                    // XoSoRecordHelpers::getRecordKhachByDate($user,$stDate,$endDate,$type);
                    Cache::remember('XoSoRecordHelpers-RecordKhachCancelv202305291-detail'.$user->id.'-'.$stDate.'-'.$endDate.'-'.$type, $cacheTime, function () use ($user,$stDate,$endDate,$type) {
                        return  XoSoRecordHelpers::getRecordKhachCancelByDatev2($user,$stDate,$endDate,$type);
                    });
            
            return response()->json(['code'=>200,'message'=>'','data' => $xsrecords ]);
        }else{
            $userChild = null;
            $searchKey = "";
            if ($searchKey == ""){
                $userChild = UserHelpers::GetAllUserChild($user,2);
            }else
                $userChild = UserHelpers::GetAllUserV2ByKey4Report($user->id,$searchKey);
            $users = $userChild;            
            
            $total1 = 0;
            $total2 = 0;
            $total3 = 0;
            $total4 = 0;
            $total5 = 0;
            $total6 = 0;
            $total7 = 0;
            $total8 = 0;
            $total9 = 0;
            $stt = 0;
    
            $begin = new DateTime($stDate);
            $end = new DateTime($endDate);
            if ($end > (new DateTime()))
                $end = new DateTime();
            $end->modify('+1 day');
    
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            $returnData = [];
            foreach($users as $user){
                $userData = [];
                $userReport = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                foreach($period as $dt){
                    $stDateTemp = $dt->format("d-m-Y");
                    $endDateTemp = $dt->format("d-m-Y");
                    if ($dt->format("Y-m-d") > date('Y-m-d')) {
                        // echo 'continue';
                        break;
                    }
                    $cacheTime = env('CACHE_TIME_SHORT_SK', 0);
                    $endTimeStamp = strtotime($endDateTemp);
                    $endDateNewformat = date('Y-m-d', $endTimeStamp);
                    if ($endDateNewformat < date('Y-m-d', strtotime("yesterday")))
                        $cacheTime = 1440 * 30;
                    // echo $cacheTime.' '.$stDateTemp.' '.$endDateTemp.'-';
                    // if ($endDateNewformat == '2023-05-24')
                    // $userReportTemp = Cache::forget('XoSoRecordHelpers-ReportKhachv20230529'.$user->id.'-'.$stDateTemp.'-'.$endDateTemp.'-'.$type);
                    $userReportTemp = [];
    
                    if ($type_page == 'winlose')
                        $userReportTemp =
                            // XoSoRecordHelpers::ReportKhachv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                            Cache::remember('XoSoRecordHelpers-ReportKhachv20240325' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp . '-' . $type, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp, $type) {
                                return  XoSoRecordHelpers::ReportKhachv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                            });
    
                    if ($type_page == 'cxl')
                        $userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachCXLv20240325' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp . '-' . $type, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp, $type) {
                            return  XoSoRecordHelpers::ReportKhachCXLv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                        });
    
                    if ($type_page == 'cancel')
                        $userReportTemp = Cache::remember('XoSoRecordHelpers-ReportKhachCancelv20240325' . $user->id . '-' . $stDateTemp . '-' . $endDateTemp . '-' . $type, $cacheTime, function () use ($user, $stDateTemp, $endDateTemp, $type) {
                            return  XoSoRecordHelpers::ReportKhachCancelv2($user, $stDateTemp, $endDateTemp, isset($type) ? $type : "all");
                        });
                    // $userReport = XoSoRecordHelpers::ReportKhachv2($user, $stDate, $endDate, isset($type) ? $type : "all");
    
                    for ($i = 0; $i <= 13; $i++) {
                        $userReport[$i] += $userReportTemp[$i];
                    }
                }
    
                $urlClick = "";
                if ($type_page == 'winlose')
                    $urlClick = url('/rp/winlose-detail?user=' . $user->id . '&stdate=' . $stDate . '&enddate=' . $endDate . '&type=' . $type);
                if ($type_page == 'cxl')
                    $urlClick = url('/rp/bettoday-detail?user=' . $user->id . '&stdate=' . $stDate . '&enddate=' . $endDate . '&type=' . $type);
    
                if ($type_page == 'cancel')
                    $urlClick = url('/rp/betcancel-detail?user=' . $user->id . '&stdate=' . $stDate . '&enddate=' . $endDate . '&type=' . $type);
    
                if ($user->roleid == 2) {
                    $userReport[1] = ($userReport[1]); //-$userReport[5]-$userReport[8]);//-$tongReport[5]
                    $userReport[2] = ($userReport[2] + $userReport[5] + $userReport[8]); //+$tongReport[5]);
                }
                if ($user->roleid <= 4) {
                    $userReport[1] = ($userReport[1]); //-$userReport[4]-$userReport[7]);//-$tongReport[5]
                    $userReport[2] = ($userReport[2] + $userReport[4] + $userReport[7]); //+$tongReport[5]);
                }
                if ($user->roleid <= 5) {
                    $userReport[1] = ($userReport[1]); //-$userReport[3]-$userReport[6]);//
                    $userReport[2] = ($userReport[2] + $userReport[3] + $userReport[6]); //+$tongReport[5]);
                }
                if ($user->roleid <= 6) {
                    $userReport[1] = ($userReport[1]); //-$userReport[12]);//
                    $userReport[2] = ($userReport[2] + $userReport[12]); //+$tongReport[5]);
                }
    
                if ($userReport[0] !=0){
                    $stt++;
                    $userData["name"] = $user->name;
                    $userData["user_id"] = $user->id;
                    $userData["info"] = $user->fullname ."/". XoSoRecordHelpers::GetRoleName($user->roleid);
                    $userData["donhang"] = number_format($userReport[0]);
                    $userData["tiencuoc"] = number_format($userReport[1]);
                    $userData["thangthua"] = number_format($userReport[2]);
                    // $user->name
                    // $urlClick
                    // $user->fullname / XoSoRecordHelpers::GetRoleName($user->roleid)
                    // number_format($userReport[0])
                    // number_format($userReport[1])
                    // number_format($userReport[2])
    
                    if ($user->roleid == 6){
                        if ($userTarget->roleid == 4 && $user->user_create == $userTarget->id){
                            $userData["hh1"] = number_format($userReport[7]);
                            $userData["hh2"] = number_format($userReport[4]);
                            $userData["thangthuacty"] = number_format(0 - ($userReport[2] + $userReport[4] + $userReport[7]));
                            // number_format($userReport[7])
                            // number_format($userReport[4])
                            // number_format(0 - ($userReport[2] + $userReport[4] + $userReport[7]))
                        } else{
                            $userData["hh1"] = number_format($userReport[6]);
                            $userData["hh2"] = number_format($userReport[3]);
                            $userData["thangthuacty"] = number_format(0 - ($userReport[2] + $userReport[3] + $userReport[6]));
                            // number_format($userReport[6])
                            // number_format($userReport[3])
                            // number_format(0 - ($userReport[2] + $userReport[3] + $userReport[6]))
                        }
                    }elseif($user->roleid == 5){
                        $userData["hh1"] = number_format($userReport[7]);
                        $userData["hh2"] = number_format($userReport[4]);
                        $userData["thangthuacty"] = number_format(0 - ($userReport[2] + $userReport[4] + $userReport[7]));
                        // number_format($userReport[7])
                        // number_format($userReport[4])
                        // number_format(0 - ($userReport[2] + $userReport[4] + $userReport[7]))
                    }elseif ($user->roleid == 4){
                        $userData["hh1"] = number_format($userReport[8]);
                        $userData["hh2"] = number_format($userReport[5]);
                        $userData["thangthuacty"] = number_format(0 - ($userReport[2] + $userReport[5] + $userReport[8]));
                        // number_format($userReport[8])
                        // number_format($userReport[5])
                        // number_format(0 - ($userReport[2] + $userReport[5] + $userReport[8]))
                    }elseif ($user->roleid == 2){
                        $total9 += (0 - ($userReport[2] + $userReport[9] + $userReport[10]))  / 100 * $user->thau;
                        if($adminShow){
                            // number_format($userReport[10])   
                            // number_format($userReport[9])
                            // number_format($userReport[9]+$userReport[10])
                        }
                        $userData["thangthuacty"] = number_format(0 - ($userReport[2] + $userReport[9] + $userReport[10]));
                    }else{
                        // 0
                        // 0
                        // number_format(0 - $userReport[2])
                    }
    
                    if(isset($users) && $users[0]->roleid == 2){
                        $userData["thangthuactythau"] = number_format((0 - ($userReport[2] + $userReport[9] + $userReport[10]))  / 100 * $user->thau);
                        $userData["thau"] = $user->thau;
                        // number_format((0 - ($userReport[2] + $userReport[9] + $userReport[10]))  / 100 * $user->thau)
                        // $user->thau %
                    }
                    $returnData[] = $userData;
                }
            }
            return response()->json(['code'=>200,'message'=>'','data' => $returnData ]);
        }
        return [];
    }

    public function update(Request $request, $id)
    {

    }
    public function store(Request $request)
    {

    }
    public function destroy($id)
    {

    }
}
