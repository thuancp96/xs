<?php namespace App\Http\Controllers;

use App\Helpers\XoSo;
use App\ChucNang;
use App\Bangso;
use App\User;
use App\QuickPlayRecord;
use App\Helpers\RoleHelpers;
use App\Helpers\BoSoHelpers;
use App\Http\Requests;
use App\Location;
use App\Helpers\LocationHelpers;
use App\Helpers\UserHelpers;
use App\Helpers\GameHelpers;
use Illuminate\Http\Request;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\NotifyHelpers;
use Illuminate\Support\Facades\Auth;
// use Session;
use DateTime;
use Sunra\PhpSimple\HtmlDomParser;
use App\Helpers\Curl;
use App\Helpers\HistoryHelpers;
use App\Helpers\QuickbetHelpers;
use App\Helpers\SabaHelpers;
use App\History;
use \Cache;
use DateInterval;
use DatePeriod;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use luk79\CryptoJsAes\CryptoJsAes;
require "CryptoJsAes.php";

class HomeController extends Controller
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
    private $quickbet;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth',['except' => ['getRule','quickplaylogicApi','postQuickplayguest','getQuickplayguest','getIndex','getKetqua','getRefreshTime','getKqsxByDay','getKqsxmnByDay','getKqsxmtByDay','encrypt','decrypt','getBridgeTele','getBridgeBothTele']]);
        $this->quickbet = new QuickbetHelpers();
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex(Request $request)
    {
        if(empty( $request->session()->get('username')) || Auth::guest()) {
            return view ('admin.login1',['playgame'=>0]);
        }
        if (Auth::user()->roleid==6)
            return view('frontend.home',['thongbao1'=>NotifyHelpers::GetNotification1()]);
        else{
            $chucnangClass = new ChucNang();
            // $thongbao = 
            if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 2)
                return redirect(url('/users'));
            else
            if (Session::get('usersecondper') == 1 && Session::get('usersecondrole2') == 3)
                return redirect(url('/rp/winlose'));
            else
                return view('admin.home',['roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'thongbao'=>NotifyHelpers::GetNotification(),'thongbao1'=>NotifyHelpers::GetNotification1(),'thongbao2'=>NotifyHelpers::GetNotification2(),'thongbao3'=>NotifyHelpers::GetNotification3()]);
        }
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getQlboso(Request $request)
    {
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        if (Auth::user()->roleid==6)
            return view ('admin.login',['playgame'=>0]);
        else{
            $chucnangClass = new ChucNang();
            $bangsos = BoSoHelpers::GetAllBangSo(0);
            $kyhieus = BoSoHelpers::GetAllBangSo(1);
            // $thongbao = 
            return view('admin.qlboso',['kyhieus'=>$kyhieus, 'bosos'=>$bangsos,'roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function postQlboso(Request $request)
    {
        
        // echo $request->kyhieu . '-'. $request->boso;
        $kyhieu = $request->kyhieu;
        $boso = $request->boso;
        $id = $request->id;
        $isdelete = $request->isdelete;

        BoSoHelpers::Update($id,$kyhieu,$boso,$isdelete);
        
        return 'true';

        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        if (Auth::user()->roleid==6)
            return view ('admin.login',['playgame'=>0]);
        else{
            
            $chucnangClass = new ChucNang();
            $bangsos = BoSoHelpers::GetAllBangSo();
            // $thongbao = 
            return view('admin.qlboso',['bosos'=>$bangsos,'roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getChangepw(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.changepw',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getKetqua(Request $request,$slug=1,$date='today')
    {
        if ($slug=='xoso') $slug=1;
        if ($slug=='xoso-mientrung') $slug=3;
        if ($slug=='xoso-miennam') $slug=2;
        if ($slug=='xoso-ao') $slug=4;
        $location = LocationHelpers::getBySlug($slug);
        // if(empty( $request->session()->get('username'))){
        //     return view ('admin.login',['playgame'=>0]);
        // }
        if ($date=='today')
        {
            $date=new DateTime();
        }else
            $date=new DateTime($date);

        if (Auth::user() == null || Auth::user()->roleid==6)
            return view('frontend.ketqua',
            [
            'location' => $location,
            'date'=> $date
            ]);
            
        else
        {
            $chucnangClass = new ChucNang();
            // $thongbao = 
            return view('admin.home',
            ['roles'=> RoleHelpers::getAllRole(),
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'thongbao'=>NotifyHelpers::GetNotification(),
            'thongbao1'=>NotifyHelpers::GetNotification1(),
            'location' => $location,
            'date'=> $date
            ]);
        }
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getThongso(Request $request)
    {
        
            // $thongbao = 
            // return view('admin.home',['roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'thongbao'=>NotifyHelpers::GetNotification()]);

            $chucnangClass = new ChucNang();
        return view('frontend.thongsotk_tab',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getBridgeTele(Request $request)
    {
        return view ('frontend/iframe_both_tele',[ 'url_target' =>  $request->url]);
    }

    public function getBridgeBothTele(Request $request)
    {
        return view ('frontend/iframe_both_tele',[ 'url_target' =>  $request->url]);
    }
    
    /**
     * Show the user's information to the user.
     *
     * @return Response
     */
    public function getThongtintk(Request $request)
    {
        
            // $thongbao = 
            // return view('admin.home',['roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'thongbao'=>NotifyHelpers::GetNotification()]);
            $current_user = User::where('id', '=', Auth::user()->id)->first();
            $chucnangClass = new ChucNang();
        return view('frontend.thongtintk',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(), 'current_user' => $current_user]);
        
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getThongsogiathap(Request $request)
    {
        
            // $thongbao = 
            // return view('admin.home',['roles'=> RoleHelpers::getAllRole(),'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),'thongbao'=>NotifyHelpers::GetNotification()]);

            $chucnangClass = new ChucNang();
        return view('frontend.thongsogiathap',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        
    }


    public function postNotify(Request $request)
    {
        NotifyHelpers::UpdateNotification($request->content);
        return "true";
    }

    public function postNotify1(Request $request)
    {
        NotifyHelpers::UpdateNotification1($request->content);
        return "true";
    }
    
    public function postNotify2(Request $request)
    {
        NotifyHelpers::UpdateNotification2($request->content);
        return "true";
    }

    public function postNotify3(Request $request)
    {
        NotifyHelpers::UpdateNotification3($request->content);
        return "true";
    }

    public function getHome(Request $request)
    {
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        return view('frontend.home');
    }
    public function getLogout()
    {
        Auth::logout();
        Session::flush();
        return redirect(url('/'));
    }
    public function getRule(Request $request)
    {
        // $location = LocationHelpers::getBySlug(1);
        // if(empty($location)){
        //     return view('frontend.404');
        // }
        // if(empty( $request->session()->get('username'))){
        //     return view ('admin.login',['playgame'=>0]);
        // }
        // $name = $request->session()->get('username');
        // $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'frontend.rule',
            [
                // 'location'=> $location[0],
                // 'user'=> $usercreate[0],
                'xosorecords'=> [],
            ]
        );
    }
    
    public function getPlay(Request $request,$slug)
    {
        // return view('frontend.busy');
        $location = LocationHelpers::getBySlug($slug);
        if(empty($location)){
            return view('frontend.404');
        }
        $game = GameHelpers::GetGameByAlias("de");
        // if ($slug==1){
        //     $now = date('Y-m-d');
        //     // echo $now;
        //     if ($now >= '2018-02-15' && $now <= '2018-02-18'){
                
        //         // return view('frontend.busy');
        //         return view
        //         (
        //             'frontend.busy',
        //             [
        //                 'location'=> $location,
        //                 'user'=> Auth::user(),
        //                 // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
        //             ]
        //         );
        //     } 
        // }
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        if ($user->lock > 0 )
            return view('frontend.home',
                [
                    'location'=> $location,
                    'user'=> $user,
                    'gameTarget' => $game
                ]    
            );
        return view
        (
            'frontend.gameplay',
            [
                'location'=> $location,
                'user'=> $user,
                'gameTarget' => $game
                // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
            ]
        );
    }

    public function getXoso(Request $request,$alias,$cate="de")
    {
        // return isset($cate) ? $cate : "";
        // return view('frontend.busy');
        $location = LocationHelpers::getByAlias($alias);
        $game = GameHelpers::GetGameByAlias($cate);
        if(empty($location)){
            return view('frontend.404');
        }
        // if ($slug==1){
        //     $now = date('Y-m-d');
        //     // echo $now;
        //     if ($now >= '2018-02-15' && $now <= '2018-02-18'){
                
        //         // return view('frontend.busy');
        //         return view
        //         (
        //             'frontend.busy',
        //             [
        //                 'location'=> $location,
        //                 'user'=> Auth::user(),
        //                 // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
        //             ]
        //         );
        //     } 
        // }
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        // if ($user->lock > 0 )
        //     return view('frontend.home',
        //         [
        //             'location'=> $location,
        //             'game' => $game,
        //             'user'=> $user
        //         ]    
        //     );

        return view
        (
            'frontend.gameplay',
            [
                'location'=> $location,
                'user'=> $user,
                'gameTarget' => $game
            ]
        );
    }

    public function quickplaylogicApi(Request $request){
        // if(empty( $request->session()->get('username'))){
        //     return response()->json(['code'=>202,'message'=>'Failed','data' => '' ]);
        // }
        // $location = LocationHelpers::getBySlug($slug);
        try{    
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            if ($user->lock == 1)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);

            $requestD = CryptoJsAes::decryptRequest($request);
            $ipaddr = "";
            try{
                $ipaddr = $requestD->ipaddress;
            }catch(\Exception $err){
            }
            // if(empty($location)){
            //     return response()->json(['code'=>202,'message'=>'Failed empty location','data' => '' ]);
            // }
            // if(empty( $request->session()->get('username'))){
            //     return response()->json(['code'=>202,'message'=>'Failed','data' => '' ]);
            // }
            if ($user->lock > 0 )
            return response()->json(['code'=>202,'message'=>'Failed user locked','data' => '' ]);
            // print_r ($request->quicktext);
            
            if (strlen($requestD->quicktext) <1 )
                $requestD->quicktext = 'de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50';
                // print_r ($request->quicktext);

            $input = isset($requestD->vaocuoc) ? $requestD->vaocuoc : '0' ;
            // Input::get('vaocuoc');
            if (isset($input) && $input=='1')
                $_iscuoc = '1';
            else
                $_iscuoc = '0';

            $_iscuoc = '1';
            $requestdata = [];

            $checkCancelBet = false;

            $requestdata = $this->quickbet->quickplaylogic(Auth::user(),$requestD->quicktext,'0',$ipaddr);
            for($i=0;$i< count($requestdata[0]);$i++){
                $req = $requestdata[0][$i];
                $status = $req['status'];
                if (str_contains($status, 'Vượt quá giới hạn chơi cho phép.')) {
                    $checkCancelBet = true;
                    break;
                }
            }
            
            if ($checkCancelBet == false){
                $requestdata = $this->quickbet->quickplaylogic(Auth::user(),$requestD->quicktext,$_iscuoc,$ipaddr);
            }else{
                for($i=0;$i< count($requestdata[0]);$i++){
                    $requestdata[0][$i]['status'] = 'Vượt quá giới hạn chơi cho phép.';
                }
            }

            return response()->json([
                'code'=>200,
                'message'=>'',
                'data' => CryptoJsAes::encryptData([
                    // 'location'=> $location,
                    // 'user'=> $user->id,
                    'bet'=> $requestdata[0],
                    'quicktext'=>$requestD->quicktext,
                    'quicktextnotmatch'=>$requestdata[1],
                    // '_isbet'=>$_iscuoc
                ])
                // ,
                // 'data1' => [
                //     // 'location'=> $location,
                //     'user'=> $user->id,
                //     'requestdata'=> $requestdata[0],
                //     'quicktext'=>$requestD->quicktext,
                //     'quicktextnotmatch'=>$requestdata[1],
                //     '_isbet'=>$_iscuoc
                // ]
            ]);
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }

        // return view
        // (
        //     'frontend.gamequickplay',
        //     [
        //         'location'=> $location,
        //         'user'=> $user,
        //         'requestdata'=> $requestdata[0],
        //         'quicktext'=>$request->quicktext,
        //         'quicktextnotmatch'=>$requestdata[1],
        //         '_iscuoc'=>$_iscuoc
        //         // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
        //     ]
        // );
    }

    public function normalplaylogicApi(Request $request){
        try{    
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.','data' => CryptoJsAes::encryptData([
                    'bet' => [],
                    'status' => 'Params are invalid!',
                    'betId' => -1,
                    'ids' =>''
                ])]);
            if ($user->lock == 1)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.','data' => CryptoJsAes::encryptData([
                    'bet' => [],
                    'status' => 'Params are invalid!',
                    'betId' => -1,
                    'ids' =>''
                ])]);

            $requestD = CryptoJsAes::decryptRequest($request);
            $ipaddr = "";
            try{
                $ipaddr = $requestD->ipaddress;
            }catch(\Exception $err){
            }

            if ($user->lock > 0 )
                return response()->json(['code'=>202,'message'=>'Failed user locked','data' => '' ]);

            
            // foreach($requestD as $bet)
            $bet = $requestD;
            $game_code = $bet->game_code;
            if ($game_code == 14 || $game_code == 7 || $game_code == 9 || $game_code == 10 || $game_code == 11 ||
            $game_code == 12 || $game_code == 17 || $game_code == 28 || $game_code == 200)
            {

            }else
                return response()->json([
                    'code'=>400,
                    'message'=>'Loại cược không hợp lệ.',
                    'data' => CryptoJsAes::encryptData([
                        'bet' => [],
                        'status' => 'Params are invalid!',
                        'betId' => -1,
                        'ids' =>''
                    ])
                    //
                ]);

            if ($game_code == 200){
                $count=[0,0,0];
                $game = [];
                $customer_type = [];
                $game[9] = GameHelpers::GetGameByCode(9);
                $customer_type[9] = GameHelpers::GetByCusTypeGameCode(9,$user->customer_type);

                $game[10] = GameHelpers::GetGameByCode(10);
                $customer_type[10] = GameHelpers::GetByCusTypeGameCode(10,$user->customer_type);

                $game[11] = GameHelpers::GetGameByCode(11);
                $customer_type[11] = GameHelpers::GetByCusTypeGameCode(11,$user->customer_type);

                $bet_numbers = $bet->bets;
                $bet_text = $bet->bet_text;
                $request = array();
                $request[9-9]['game_code'] = 9;
                $request[9-9]['game_name'] = $customer_type[9]->game_name;
                $request[9-9]['odds'] = $customer_type[9]->odds;
                $request[9-9]['ipaddr'] = $ipaddr;

                $request[10-9]['game_code'] = 10;
                $request[10-9]['game_name'] = $customer_type[10]->game_name;
                $request[10-9]['odds'] = $customer_type[10]->odds;
                $request[10-9]['ipaddr'] = $ipaddr;

                $request[11-9]['game_code'] = 11;
                $request[11-9]['game_name'] = $customer_type[11]->game_name;
                $request[11-9]['odds'] = $customer_type[11]->odds;
                $request[11-9]['ipaddr'] = $ipaddr;

                $totalBet = 0;
                foreach($bet_numbers as $bet_number){
                    $numbers = $bet_number['numbers'];
                    $point = $bet_number['point'];
    
                    foreach($numbers as $number){
                        $game_code = count(explode(',',$number)) + 7;
                        $exchange_rates = $customer_type[$game_code]['exchange_rates'];
                        $total = 0;
                        foreach(explode(',',$number) as $item){
                            $data = GameHelpers::GetGame_Number($game_code,$item);
                            if(count($data)>0) {
                                $exchange_rates = $data[0]['exchange_rates'];
                                if( $exchange_rates < $customer_type[$game_code]['exchange_rates'])
                                    $exchange_rates = $customer_type[$game_code]['exchange_rates'];
                            }
                            else
                            {
                                $exchange_rates =  $customer_type[$game_code]['exchange_rates'];
                            }
                            // print_r($data);
                            // echo $item .'-'.$exchange_rates . '  ';
                            $total += $exchange_rates;
                        }
                        
                        $exchange_rates = $total/($game_code-7);
                        $newbet = $exchange_rates;
                        $newbet_tmp = round($newbet, -1, PHP_ROUND_HALF_DOWN);
                        if ($newbet_tmp - $newbet > 0) $newbet = $newbet_tmp - 10;
                        else $newbet = $newbet_tmp;
                        $exchange_rates = $newbet;

                        $request[$game_code-9]['choices'][$count[$game_code-9]] = array();
                        $request[$game_code-9]['choices'][$count[$game_code-9]]['exchange'] = $exchange_rates;
                        $request[$game_code-9]['choices'][$count[$game_code-9]]['name'] = $number;
                        $request[$game_code-9]['choices'][$count[$game_code-9]]['point'] = $point;
                        if ($game_code == 9 || $game_code == 10 || $game_code == 11 || $game_code==29){
                            $countbetnumber = count(explode(',',$number));
                            if ($game_code==9)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                            elseif ($game_code==10)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(3)/XoSoRecordHelpers::fact($countbetnumber-3);
                            elseif ($game_code==11)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(4)/XoSoRecordHelpers::fact($countbetnumber-4);
                            elseif ($game_code==29)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
    
                            $request[$game_code-9]['choices'][$count[$game_code-9]]['total'] = $Ank * (int)($exchange_rates) * $point;
                            
                        
                        }else
                            $request[$game_code-9]['choices'][$count[$game_code-9]]['total'] = $exchange_rates * $point;
    
                        $totalBet += $request[$game_code-9]['choices'][$count[$game_code-9]]['total'];
                        $count[$game_code-9] = $count[$game_code-9]+1;
                    }
                }

                $temp = [];
                foreach($request as $item){
                    if (!isset($item['choices']) || count($item['choices']) ==0){

                    }else
                        array_push($temp,$item);
                }
                $request = $temp;
                $messageErr = "";
                $messageErrCode = "";
                $number_block = "";
                $count = 0;
                foreach($request as $item){
                    $response = XoSoRecordHelpers::InsertXosoRecord((object)$item,Auth::user(),false,true);
                    $item['status'] = $response['status']; $item['betId'] = $response['ids']; $item['status_code'] = $response['status_code'];
                    $item['number_block'] = isset($response['number_block']) ? $response['number_block'] : '';
                    $request[$count]['status'] = $response['status'] != 'ok' ? $response['status'] . ' '.$item['number_block'] : $response['status'].'';
                    $request[$count]['ids'] = $response['ids'];
                    $count++;
                    if ($item['status'] != 'ok' && !str_contains($messageErr,$item['status'])){
                        $messageErr .= $item['status'] .'';
                    }
                    if ($item['status'] != 'ok' && !str_contains($messageErrCode,$item['status_code'])){
                        $messageErrCode .= $item['status_code'] .'';
                    }
                    if ($item['number_block'] != '')
                        $number_block .= $item['number_block'] . ' ';
                        
                }
                if ($messageErr != ""){
                    $count = 0;
                    foreach($request as $item){   
                        $request[$count]['status'] = $request[$count]['status'] == 'ok' ? '' : $request[$count]['status'].'';
                        $count++;
                    }

                    $number_block = str_replace(' ','',$number_block);
                    // return array_unique(explode(',', $number_block));
                    
                    $number_block = implode(',',array_unique(explode(',', $number_block)));
                    $number_block = trim($number_block,',');

                    return response()->json([
                        'code'=>400,
                        'message'=>$messageErr,
                        'data' => CryptoJsAes::encryptData([
                            'bet' => $request,
                            'status' => $messageErr . ' ' . $number_block,// != '' ? $messageErr : ($response9['status'] != 'ok' ? $response9['status'] : '') . ''. ($response10['status'] != 'ok' ? $response10['status'] : '') .''. ($response11['status'] != 'ok' ? $response11['status'] : ''),
                            'betId' => -1,
                            'ids' => '',
                            'rejectedNumber' => [
                                'game_code' => 200,
                                'nums' => isset($number_block) && strlen($number_block)>0 ? explode(',',$number_block)  : [],
                                 'reason' => $messageErrCode
                            ]
                        ])
                    ]);
                }else
                    try{
                        $temp = [];
                        $ids = "";
                        foreach($request as $item){
                            if (!isset($item['choices']) || count($item['choices']) ==0){

                            }else
                                array_push($temp,$item);
                        }
                        $request = $temp;
                        $messageErr = "";
                        $count=0;
                        foreach($request as $item){
                            $response = XoSoRecordHelpers::InsertXosoRecord((object)$item,Auth::user(),true,true);
                            $item['status'] = $response['status']; $item['betId'] = $response['ids'];
                            $request[$count]['status'] = $response['status']; $request[$count]['ids'] .= $response['ids'].',';
                            $count++;
                            if ($item['status'] != 'ok')
                                $messageErr = 'failed';
                            else $ids .= $item['betId'] .',';
                        }

                        $record = new QuickPlayRecord;
                        $record->content = $bet_text;
                        $record->date = date('Y-m-d');
                        $record->user_id = $user->id;
                        $record->total = $totalBet;
                        $record->ids = $ids ;
                        $record->save();
        
                        // HistoryHelpers::InsertHistoryQuickBet($bet_text,$totalBet,$user);

                        return response()->json([
                            'code'=>200,
                            'message'=>"Vào cược thành công.",
                            'data' => CryptoJsAes::encryptData([
                                'bet' => $request,
                                'status' => 'ok',
                                'betId' => $record->id,
                                'ids' =>$ids,
                                'rejectedNumber' => [
                                    'game_code' => 200,
                                     'nums' => [],
                                     'reason' => ''
                                ]
                            ])
                        ]);

                    }catch(\Exception $ex){
                        return response()->json([
                            'code'=>400,
                            'message'=> 'Params are invalid!',
                            'data' => CryptoJsAes::encryptData([
                                'bet' => [],
                                'status' => 'Params are invalid!',
                                'betId' => -1,
                                'ids' =>'',
                                'rejectedNumber' => [
                                    'game_code' => 200,
                                     'nums' => [],
                                     'reason' => ''
                                ]
                            ])
                        ]);
                    }
            }else{
                $count=0;
                $game = GameHelpers::GetGameByCode($game_code);
                $customer_type = GameHelpers::GetByCusTypeGameCode($game_code,$user->customer_type);
                $bet_numbers = $bet->bets;
                $bet_text = $bet->bet_text;
                $request = array();
                $request['game_code'] = $game_code;
                $request['game_name'] = $customer_type->game_name;
                $request['odds'] = $customer_type->odds;
                $request['ipaddr'] = $ipaddr;
                $totalBet = 0;
                foreach($bet_numbers as $bet_number){
                    // print_r($bet_number);
                    $numbers = $bet_number['numbers'];
                    $point = $bet_number['point'];

                    foreach($numbers as $number){
                        // if (empty($number) || empty($point)) return response()->json([
                        //     'code'=>400,
                        //     'message'=>'Hãy nhập đủ mã cược và điểm cược!'
                        //     //
                        // ]);
                        $total = 0;
                        if ($game_code == 9 || $game_code == 10 || $game_code == 11){
                            foreach(explode(',',$number) as $item){
                                $data = GameHelpers::GetGame_Number($game_code,$item);
                                if(count($data)>0) {
                                    $exchange_rates = $data[0]['exchange_rates'];
                                    if( $exchange_rates < $customer_type['exchange_rates'])
                                        $exchange_rates = $customer_type['exchange_rates'];
                                }
                                else
                                {
                                    $exchange_rates =  $customer_type['exchange_rates'];
                                }
                                // print_r($data);
                                // echo $item .'-'.$exchange_rates . '  ';
                                $total += $exchange_rates;
                            }
                            
                            $exchange_rates = $total/($game_code-7);
                            $newbet = $exchange_rates;
                            $newbet_tmp = round($newbet, -1, PHP_ROUND_HALF_DOWN);
                            if ($newbet_tmp - $newbet > 0) $newbet = $newbet_tmp - 10;
                            else $newbet = $newbet_tmp;
                            $exchange_rates = $newbet;
                            
                            $request['choices'][$count]['exchange'] = $exchange_rates;
                            $request['choices'][$count]['name'] = $number;
                            $request['choices'][$count]['point'] = $point;
                            if ($game_code == 9 || $game_code == 10 || $game_code == 11 || $game_code==29){
                                $countbetnumber = count(explode(',',$number));
                                if ($game_code==9)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                                elseif ($game_code==10)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(3)/XoSoRecordHelpers::fact($countbetnumber-3);
                                elseif ($game_code==11)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(4)/XoSoRecordHelpers::fact($countbetnumber-4);
                                elseif ($game_code==29)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                                $request['choices'][$count]['total'] = $Ank * (int)($exchange_rates) * $point; 
                            }else
                                $request['choices'][$count]['total'] = $exchange_rates * $point;
                        }else{
                            $data = GameHelpers::GetGame_Number($game_code,$number);
                            $request['choices'][$count] = array();
                            $exchange_rates = "";
                            $a = "";
                            $x = "";
                            if(count($data)>0) {
                                $exchange_rates = $data[0]['exchange_rates'];
                                if( $exchange_rates < $customer_type['exchange_rates'])
                                    $exchange_rates = $customer_type['exchange_rates'];
                                // $a = $data[0]['a'];
                                // $x = $data[0]['x'];
                                // $total = $data[0]['total'];
                                // $request['choices'][$count]['up'] = $data[0];
                            }
                            else
                            {   
                                $exchange_rates =  $customer_type['exchange_rates'];
                                // $a = $game['a'];
                                // $x = $game['x'];
                                // $total = 0;
                            }
                            
                            $request['choices'][$count]['exchange'] = $exchange_rates;
                            $request['choices'][$count]['name'] = $number;
                            $request['choices'][$count]['point'] = $point;
                            if ($game_code == 9 || $game_code == 10 || $game_code == 11 || $game_code==29){
                                $countbetnumber = count(explode(',',$number));
                                if ($game_code==9)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                                elseif ($game_code==10)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(3)/XoSoRecordHelpers::fact($countbetnumber-3);
                                elseif ($game_code==11)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(4)/XoSoRecordHelpers::fact($countbetnumber-4);
                                elseif ($game_code==29)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                                $request['choices'][$count]['total'] = $Ank * (int)($exchange_rates) * $point; 
                            }else
                                $request['choices'][$count]['total'] = $exchange_rates * $point;
                        }

                        $totalBet += $request['choices'][$count]['total'];
                        $count++;
                    }
                }
                $response = XoSoRecordHelpers::InsertXosoRecord((object)$request,Auth::user(),true,true);
                if ($response['status'] == 'ok'){
                    try{
                        $record = new QuickPlayRecord;
                        $record->content = $bet_text;
                        $record->date = date('Y-m-d');
                        $record->user_id = $user->id;
                        $record->total = $totalBet;
                        $record->ids = $response['ids'];
                        $record->save();
        
                        HistoryHelpers::InsertHistoryQuickBet($bet_text,$totalBet,$user);

                        return response()->json([
                            'code'=>200,
                            'message'=>"Vào cược thành công.",
                            'data' => CryptoJsAes::encryptData([
                                'bet' => [$request],
                                'status' => $response['status'],
                                'ids' => $response['ids'],
                                'betId' => $record->id,
                                'rejectedNumber' => [
                                    'game_code' => $game_code,
                                     'nums' => [],
                                     'reason' => ''
                                ]
                            ])
                        ]);

                    }catch(\Exception $ex){
                        return response()->json([
                            'code'=>400,
                            'message'=> 'Params are invalid!',
                            'data' => CryptoJsAes::encryptData([
                                'bet' => [],
                                'status' => 'Params are invalid!',
                                'betId' => -1,
                                'ids' =>'',
                                'rejectedNumber' => [
                                    'game_code' => $game_code,
                                     'nums' => [],
                                     'reason' => 'reasonId'
                                ]
                            ])
                        ]);
                    }
                }else{
                    $number_block = "";
                    if (isset($response['number_block'])){
                        $number_block = $response['number_block'];
                        $number_block = str_replace(' ','',$number_block);
                        $number_block = implode(',',array_unique(explode(',', $number_block)));
                        $number_block = trim($number_block,',');
                    }
                    return response()->json([
                        'code'=>400,
                        'message'=>$response['status'],
                        'data' => CryptoJsAes::encryptData([
                            'bet' => $request,
                            'status' => $response['status'],
                            'betId' => -1,
                            'ids' =>'',
                            'rejectedNumber' => [
                                'game_code' => $game_code,
                                 'nums' => isset($number_block) && strlen($number_block)>0 ? explode(',',$number_block)  : [],
                                 'reason' => $response['status_code']
                            ]
                        ])
                    ]);
                }
            }
            
            
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=> 'Params are invalid!',
                'data' => CryptoJsAes::encryptData([
                    'bet' => [],
                    'status' => 'Params are invalid!',
                    'betId' => -1,
                    'ids' =>'',
                    'rejectedNumber' => [
                        'game_code' => 1,
                         'nums' => [],
                         'reason' => ''
                    ]
                ])
            ]);
        }
    }

    public function quickplaylogicguest($quicktext='de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50',$_iscuoc,$date) {

        // $newbet = ' đề 25,52,36,63    x50k. nhat45.56.74 1tr, lo 65-78-98 x 20d; xien4 33.44,55-66= 100ng ';
        // $newbet ='de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50';
        //init
        $arrRawBetDeRaw = $this->quickbet->initquickplay($quicktext);
        $arrRawBetDe = $arrRawBetDeRaw[0];
        // print_r ($arrRawBetDe);
        // echo nl2br('<br> ');
        $request = array();
        $multi_request=array();
        foreach($arrRawBetDe as $blockcuoc){
            $loaicuoc = 0;
            $macuoc = array();
            $gia = 0;
            $macuocbo = array();
            if (strpos( $blockcuoc[0], 'xien' ) == false && strlen($blockcuoc[0]) >6){
                // $dumparr = preg_split('#(?<=\d)(?=[a-z])#i', $blockcuoc[0]);
                preg_match('/[^\d]+/', $blockcuoc[0], $textMatch);
                // $blockcuoc[0]=$textMatch[0];
                // print_r ($textMatch);
                array_splice($blockcuoc, 1, 0, str_replace($textMatch[0],'',$blockcuoc[0]));
                $blockcuoc[0]=$textMatch[0];
            }
            // $result = ''.$blockcuoc[0].' ';
            $result = '';
            for($i=1;$i<=count($blockcuoc)-2;$i++){
                if (strpos( $blockcuoc[$i], 'bo' ) !==false
                || strpos( $blockcuoc[$i], 'bor' ) !==false
                || strpos( $blockcuoc[$i], 'botrung' ) !==false
                ){
                    break;
                }
                if (strpos( $blockcuoc[$i], 'xien' ) !==false
                || strpos( $blockcuoc[$i], 'x2' ) !==false
                || strpos( $blockcuoc[$i], 'x3' ) !==false
                || strpos( $blockcuoc[$i], 'x4' ) !==false)
                {
                    $result.= $blockcuoc[$i].' ';
                    // continue;
                }
                else{
                    $result.= str_replace(['x','=','nhan'],'',$blockcuoc[$i]).' ';
                // $result.= $blockcuoc[$i].' ';
                preg_match_all('!\d+!', $blockcuoc[$i], $matches);
                // $macuoc = array_merge($macuoc,$matches);
                foreach($matches as $item)
                    $macuoc = array_merge($macuoc,$item);
                // print_r($matches);
                }
            }
            $botrung = false;
            for(;$i<=count($blockcuoc)-2;$i++){
                // if (strpos( $blockcuoc[$i], 'bo' ) !==false
                // || strpos( $blockcuoc[$i], 'bor' ) !==false
                // ){
                //     break;
                // }
                if (strpos( $blockcuoc[$i], 'trung' ) !==false || strpos( $blockcuoc[$i], 'botrung' ) !==false){
                    $botrung = true;
                }
                if (strpos( $blockcuoc[$i], 'xien' ) !==false
                || strpos( $blockcuoc[$i], 'x2' ) !==false
                || strpos( $blockcuoc[$i], 'x3' ) !==false
                || strpos( $blockcuoc[$i], 'x4' ) !==false)
                {
                    $result.= $blockcuoc[$i].' ';
                    // continue;
                }
                else{
                    $result.= str_replace(['x','=','nhan'],'',$blockcuoc[$i]).' ';
                // $result.= $blockcuoc[$i].' ';
                preg_match_all('!\d+!', $blockcuoc[$i], $matches);
                // $macuoc = array_merge($macuoc,$matches);
                foreach($matches as $item)
                    $macuocbo = array_merge($macuocbo,$item);
                // print_r($matches);
                }
            }

            if ($blockcuoc[0]=='xien'){
                $blockcuoc[0] = 'xien'.(count($blockcuoc)-2);
            }
            $result = $blockcuoc[0].' '.$result;
            if ($botrung==true){
                $macuoc = array_unique($macuoc);
                // print_r($macuoc);
            }
            // $result.=' '.str_replace(['x','='],'',$blockcuoc[count($blockcuoc)-1]);
            $result.='giá '.str_replace(['x','=','nhan'],'',$blockcuoc[count($blockcuoc)-1]);
            // echo nl2br($result.'<br> ');
            // print_r($this->str_to_game_code($result));
            // echo nl2br('<br>');
            // print_r($macuoc);
            // echo nl2br('<br>');
            // print_r($this->str_to_price($blockcuoc[count($blockcuoc)-1]));
            $priceRaw = $this->quickbet->str_to_price($blockcuoc[count($blockcuoc)-1]);
            $type_price = $priceRaw[0];
            $value_price = $priceRaw[1];
            // echo nl2br('<br>');
            
            $nowdate = date("Y-m-d", strtotime($date));

            $xoso = new Xoso();
            
            try{
                $now = date('Y-m-d');// 
                $hour = date('H');
                $min = date('i');
                $sec = date('s');
                $yesterday = date('Y-m-d', time()-86400);
                // if ($location->slug ==1){
                $yesterday = date('Y-m-d', time()-86400);
                $datepickerXS= date('d-m-Y', time()-86400);

                if ($nowdate == $now)
                    if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('i') )<15)){
                        $rs = [];
                        // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
                            // return 
                            // $xoso->getKetQua(1,$yesterday);
                        // });
                        // $rs = xoso::getKetQua(1,$yesterday);
                    }
                    else{
                        $rs = 
                        // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
                            // return 
                            $xoso->getKetQua(1,date('Y-m-d'));
                        // });
                        $datepickerXS= date('d-m-Y');
                    }
                else if ($nowdate < $now){
                    $rs = 
                        // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
                            // return 
                            $xoso->getKetQua(1,$nowdate);
                }else{
                    $rs=[];
                }
                
            // $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.$nowdate, env('CACHE_TIME', 0), function () use ($nowdate,$xoso) {
                // return 
                // $xoso->getKetQua(1,$nowdate);
            // });
            }catch(\Exception $ex){
                $rs=[];
            }
            if (count($rs) < 1) return [];
            $is_actived = true;
            foreach($this->quickbet->str_to_game_code($result) as $gameOrignal){
                //vao cuoc
                try{
                $game = $gameOrignal[0];
                // echo $gameOrignal[1];
                $is_actived = true;
                if ($game ==0)continue;
                
                $request = array();
                $request['game_code'] = $game;
                $request['game_name'] = GameHelpers::GetGameByCode($game)->name;
                $request['is_actived'] = $is_actived;
                $count=0;
                
                if ($game != 9 && $game != 10 && $game != 11 && $game!=29)
                    foreach($macuoc as $name){
                        if (strlen($name)>2 && $game!=17){
                            for($h=0;$h<strlen($name);$h++)
                                for($k=$h+1;$k<strlen($name);$k++)
                                {
                                    $name1=$name[$h].$name[$k];
                                    $request['choices'][$count] = array();
                                    $request['choices'][$count]['name'] = $name1;
                                    $request['choices'][$count]['point'] = (float)($type_price)* (float)($value_price[0]);
                                    $request['choices'][$count]['status'] = $this->checktrathuong($request['choices'][$count]['name'],$request['game_code'],$rs);
                                    $count++;
                                    break;
                                }
                        }else
                        {
                            //get exchange odds by user + game_code + game_number
                            if (strlen($name)==1)
                                $name='0'.$name;
                            $request['choices'][$count] = array();
                            $request['choices'][$count]['name'] = $name;
                            $request['choices'][$count]['point'] = (float)($type_price)* (float)($value_price[0]);
                            $request['choices'][$count]['status'] = $this->checktrathuong($request['choices'][$count]['name'],$request['game_code'],$rs);
                            $count++;
                        }
                    }
                else{
                    $macuoc = array_unique($macuoc);
                    $str_macuoc='';
                    $exchange_rates =0;
                    foreach($macuoc as $name){
                        if (strlen($name)>2 && $game!=17){
                            for($h=0;$h<strlen($name);$h++)
                                for($k=$h+1;$k<strlen($name);$k++)
                                {
                                    $name1=$name[$h].$name[$k];
                                    $str_macuoc.=$name1.',';
                                    break;
                                }
                        }else
                        {
                            if (strlen($name)==1)
                                $name='0'.$name;
                            $str_macuoc.=$name.',';
                        }
                    }
                    if (!isset($str_macuoc) || strlen($str_macuoc)<1) continue;

                    if ($str_macuoc[strlen($str_macuoc)-1]==',')
                        $str_macuoc = substr($str_macuoc, 0, -1);
                    $arr_macuoc = explode(',', $str_macuoc);
                    if (count($arr_macuoc) < 2 ) continue;
                    if (count($arr_macuoc) < 3 && $game==10 ) continue;
                    if (count($arr_macuoc) < 4 && $game==11 ) continue;

                    if ( ($game==9 || $game==10 || $game==11) && strpos( $gameOrignal[1], 'xienq' ) ===false ) {
                        $countbetnumberorginal = count($arr_macuoc);
                        $currentpoint = 0;
                        for($slxien=0;$slxien<$countbetnumberorginal/($game-7);$slxien++){
                            $arr_macuoc_split = array();
                            for($k=0;$k<$game-7;$k++){
                                array_push($arr_macuoc_split,$arr_macuoc[$currentpoint]);
                                $currentpoint++;
                                if($currentpoint >= count($arr_macuoc)) break;
                            }
                            
                            if (count($arr_macuoc_split)!= ($game-7) ) continue;
                            $countbetnumber = count($arr_macuoc_split);
                            $str_macuoc_split = implode(',',$arr_macuoc_split);
                            if ($game==9)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                            elseif ($game==10)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(3)/XoSoRecordHelpers::fact($countbetnumber-3);
                            elseif ($game==11)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(4)/XoSoRecordHelpers::fact($countbetnumber-4);
                            elseif ($game==29)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);

                            // $point = $record->total_bet_money/$record->exchange_rates/$Ank;
                            // $record->total_win_money = $point*$record->odds*$count;
                            
                            $request['choices'][$count] = array();
                            $request['choices'][$count]['name'] = $str_macuoc_split;
                            $request['choices'][$count]['point'] = $Ank * (float)($type_price)* (float)($value_price[0]);
                            $request['choices'][$count]['status'] = $this->checktrathuong($request['choices'][$count]['name'],$request['game_code'],$rs)/$Ank;

                            $count++;
                        }

                    }else{
                        $countbetnumber = count($arr_macuoc);
                        if ($game==9)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);
                        elseif ($game==10)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(3)/XoSoRecordHelpers::fact($countbetnumber-3);
                        elseif ($game==11)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(4)/XoSoRecordHelpers::fact($countbetnumber-4);
                        elseif ($game==29)
                        $Ank = XoSoRecordHelpers::fact($countbetnumber)/XoSoRecordHelpers::fact(2)/XoSoRecordHelpers::fact($countbetnumber-2);

                        // $point = $record->total_bet_money/$record->exchange_rates/$Ank;
                        // $record->total_win_money = $point*$record->odds*$count;
                        
                        $request['choices'][$count] = array();
                        $request['choices'][$count]['name'] = $str_macuoc;
                        $request['choices'][$count]['point'] = $Ank * (float)($type_price)* (float)($value_price[0]);
                        $request['choices'][$count]['status'] = $this->checktrathuong($request['choices'][$count]['name'],$request['game_code'],$rs)/$Ank;

                        $count++;
                    }
                    
                }
                // print_r ($request);
                $status = '';
                // if ($_iscuoc=='1' && $is_actived==true)
                    // $status = XoSoRecordHelpers::InsertXosoRecord((object)$request,$user);
                
                $request['status'] = $status;
                array_push($multi_request,$request);
                }catch(\Exception $ex){
                    // throw $ex;
                    // echo $ex;
                }
            }
        }
        
        return [$multi_request,$arrRawBetDeRaw[1]];
    }

    public function getQuickplay(Request $request,$slug)
    {
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $location = LocationHelpers::getBySlug($slug);
        if(empty($location)){
            return view('frontend.404');
        }
        if ($slug==1){
            $now = date('Y-m-d');
            if ($now >= '2018-02-15' && $now <= '2018-02-18'){
                return view('frontend.busy');
            } 
        }
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        if ($user->lock > 0 )
            return view('frontend.home');

        $quickplayhistory = History::where('user_create', $user->id)->orderBy('created_at','desc')
        ->where('date', date('Y-m-d'))->where('is_done','!=', 0)->get();
        return view
        (
            'frontend.gamequickplay',
            [
                'location'=> $location,
                'user'=> $user,
                'quickplayhistory' => $quickplayhistory,
                'requestdata'=> array()
                //$this->quickplaylogic($request->quicktext)
                // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
            ]
        );
    }

    public function getQuickplayhistory(Request $request,$slug)
    {
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $location = LocationHelpers::getBySlug($slug);
        if(empty($location)){
            return view('frontend.404');
        }
        if ($slug==1){
            $now = date('Y-m-d');
            if ($now >= '2018-02-15' && $now <= '2018-02-18'){
                return view('frontend.busy');
            } 
        }
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        if ($user->lock > 0 )
            return view('frontend.home');
        
        $quickplayhistory = History::where('user_create', $user->id)
        ->where('date', date('Y-m-d'))->get();
        return view
        (
            'frontend.gamequickhistory',
            [
                'location'=> $location,
                'user'=> $user,
                'quickplayhistory'=> $quickplayhistory
            ]
        );
    }

    public function getReloadQuickplayhistory(Request $request,$slug=1)
    {
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $location = LocationHelpers::getBySlug($slug);
        if(empty($location)){
            return view('frontend.404');
        }
        
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        if ($user->lock > 0 )
            return view('frontend.home');
        
        $quickplayhistory = History::where('user_create', $user->id)->orderBy('created_at','desc')
        ->where('date', date('Y-m-d'))->where('is_done','!=', 0)->get();
        return view
        (
            'frontend.reloadgamequickhistory',
            [
                'location'=> $location,
                'user'=> $user,
                'quickplayhistory'=> $quickplayhistory
            ]
        );
    }

    public function getReloadQuickplayhistoryApi(Request $request)
    {
        
        try{
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            // if ($user->lock == 1)
            //     return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);
    
            $requestD = CryptoJsAes::decryptRequest($request);
            $start = $requestD->start;
            $end = $requestD->end;
    
            $location = LocationHelpers::getBySlug(1);
            if(empty($location)){
                return response()->json(['code'=>404,'message'=>'empty','data' => '' ]);
            }
            $startdate = date("Y-m-d", strtotime($start));
            $enddate = date("Y-m-d", strtotime($end));
    
            // 
            // where('date', date('Y-m-d'))->
            $quickplayhistory = QuickPlayRecord::where('user_id', $user->id)
                ->orderBy('created_at','desc')
                ->where('date','>=',$startdate)
                ->where('date','<=',$enddate)
                ->get();
            
            return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData($quickplayhistory) ]);   
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }

    }

    public function getReloadQuickplayhistorybyidApi(Request $request)
    {
        
        try{
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            // if ($user->lock == 1)
            //     return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);
    
            $requestD = CryptoJsAes::decryptRequest($request);
            $id = $requestD->id;
    
            $location = LocationHelpers::getBySlug(1);
            if(empty($location)){
                return response()->json(['code'=>404,'message'=>'empty','data' => '' ]);
            }
            // 
            // where('date', date('Y-m-d'))->
            $quickplayhistory = QuickPlayRecord::where('user_id', $user->id)
                ->orderBy('created_at','desc')
                ->where('id',$id)
                ->get();
            
            return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData($quickplayhistory) ]);   
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }

    }

    public function getQuickplayguest(Request $request)
    {   
        $yesterday = date('d-m-Y', time()-86400);
        return view
        (
            'frontend.gamequickplayguest',
            [
                'requestdata'=> array(),
                'date'=>$yesterday
                //$this->quickplaylogic($request->quicktext)
                // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
            ]
        );
    }

    public function getLoadPreviewModal(Request $request){
        // check vào cược
        // $now = date('Y-m-d');// 
        // $hour = date('H');
        // $min = date('i');
        // $sec = date('s');
        // if ($hour==18 && $min >=15) return;
        // if ($hour>18) return;

        // return view('frontend.time-zone');
        // echo $request->quicktext;
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $location = LocationHelpers::getBySlug($request->slug);
        $ipaddr = "";
        try{
            $ipaddr = $request->ipaddress;
        }catch(\Exception $err){
        }
        if(empty($location)){
            return view('frontend.404');
        }
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        if ($user->lock > 0 )
            return view('frontend.home');
        // print_r ($request->quicktext);
        
        if (isset($request->quicktext) || strlen($request->quicktext) <1 )
            $request->quicktext = 'de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50';
            // $request->quicktext = 'de lo 23.34,55-66x30.';
            // print_r ($request->quicktext);

        // $input = Input::get('vaocuoc');
        $input = $request->inputC;
        // echo "sss " .$request->checkbox_lowp;
        $checkbox_lowp = false;
        $checkbox_lowp = $request->checkbox_lowp == 'on' ? true : false;

        
        $requestdata = [];
        if (isset($input) && $input == "1"){
            $_iscuoc = '1';
            $requestdata = $this->quickbet->quickplaylogic(Auth::user(),$request->quicktext,'0',$ipaddr,$checkbox_lowp);
            $checkCancelBet = false;
            for($i=0;$i< count($requestdata[0]);$i++){
                $req = $requestdata[0][$i];
                $status = $req['status'];
                if (str_contains($status, 'error021')) {
                    $checkCancelBet = true;
                    break;
                }
            }
            if ($checkCancelBet){
                $_iscuoc = '0';
            }else{
                $requestdata = $this->quickbet->quickplaylogic(Auth::user(),$request->quicktext,'1',$ipaddr,$checkbox_lowp);
            }
            
        }else{
            $_iscuoc = '0';
            // return $this->quickbet->initquickplay($request->quicktext);
            $requestdata = $this->quickbet->quickplaylogic(Auth::user(),$request->quicktext,$_iscuoc,$ipaddr,$checkbox_lowp);
        }
        
        $list_tin_cuoc = [];
        $list_tin_huy = [];
        foreach($requestdata[0] as $requestCuoc){
            if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                array_push($list_tin_cuoc, $requestCuoc);
            else
                array_push($list_tin_huy, $requestCuoc);
        }
        
        $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc);
        $tin_huy = $this->quickbet->revertquickplay($list_tin_huy);

        return view
        (
            'frontend.loadPreview',
            [
                'location'=> $location,
                'user'=> $user,
                'requestdata'=> $requestdata[0],
                'quicktext'=>$request->quicktext,
                'quicktextnotmatch'=>$requestdata[1],
                '_iscuoc'=>$_iscuoc,
                'checkbox_lowp'=>$checkbox_lowp,
                'tin_cuoc' => $tin_cuoc,
                'tin_huy' => $tin_huy,
                // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
            ]
        );
    }
    public function postQuickplay(Request $request,$slug)
    {
        // check vào cược
        // $now = date('Y-m-d');// 
        // $hour = date('H');
        // $min = date('i');
        // $sec = date('s');
        // if ($hour==18 && $min >=15) return;
        // if ($hour>18) return;
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $location = LocationHelpers::getBySlug($slug);
        $ipaddr = "";
        try{
            $ipaddr = $request->ipaddress;
        }catch(\Exception $err){
        }
        if(empty($location)){
            return view('frontend.404');
        }
        if(empty( $request->session()->get('username'))){
            return view ('admin.login',['playgame'=>0]);
        }
        $user = Auth::user();
        if ($user->lock > 0 )
            return view('frontend.home');
        // print_r ($request->quicktext);
        
        if (strlen($request->quicktext) <1 )
            $request->quicktext = 'de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50';
            // print_r ($request->quicktext);

        $input = Input::get('vaocuoc');

        $checkbox_lowp = false;
        $checkbox_lowp = Input::get('checkbox_lowp') == 'on' ? true : false;

        // echo "sss " .$checkbox_lowp;
        if (isset($input))
            $_iscuoc = '1';
        else
            $_iscuoc = '0';

        $requestdata = $this->quickbet->quickplaylogic(Auth::user(),$request->quicktext,$_iscuoc,$ipaddr,$checkbox_lowp);

        $list_tin_cuoc = [];
        $list_tin_huy = [];
        foreach($requestdata[0] as $requestCuoc){
            if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok')
                array_push($list_tin_cuoc, $requestCuoc);
            else
                array_push($list_tin_huy, $requestCuoc);
        }
        $tin_cuoc = $this->quickbet->revertquickplay($list_tin_cuoc);
        $tin_huy = $this->quickbet->revertquickplay($list_tin_huy);

        return view
        (
            'frontend.gamequickplay',
            [
                'location'=> $location,
                'user'=> $user,
                'requestdata'=> $requestdata[0],
                'quicktext'=>$request->quicktext,
                'quicktextnotmatch'=>$requestdata[1],
                '_iscuoc'=>$_iscuoc,
                'checkbox_lowp'=>$checkbox_lowp,
                'tin_cuoc' => $tin_cuoc,
                'tin_huy' => $tin_huy,
                // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
            ]
        );
    }

    private function checktrathuong($bet_number,$game_id,$rs){
        if (!isset($rs) || count($rs)<1) return 0;
        if($game_id==14 || $game_id==114) //check de
            {
                $win = GameHelpers::CheckLoDe($bet_number,$rs,"DB");
                if($win != null)
                    return count(array($win));
                else
                    return 0;
            }
        if($game_id==12 || $game_id==112) //check nhat
            {
                $win = GameHelpers::CheckLoDe($bet_number,$rs,"Giai_1");
                
                if(count($win) > 0)
                    return count(array($win));
                else
                    return 0;
            }

        if($game_id==17 || $game_id==117) //check 3 cang
        {

            $win = GameHelpers::CheckLoDe($bet_number,$rs,"3_cang");
            
            if($win != null)
                return count(array($win));
            else
                return 0;
        }


        if($game_id==7 || $game_id==107) //check lo 2 so
        {
            $win = GameHelpers::CheckLo2($bet_number,$rs);
            
            if(count($win) > 0)
                return count($win);
            else
                return 0;
        }

        if($game_id==29) //check lo xien 29
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$bet_number));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) { 
                        $win = GameHelpers::CheckLoXienNhay(trim($listbets[$i]).','.trim($listbets[$j]),$rs);
                        
                        if(count($win) > 0){
                            // \Log::info('win was @ ' . implode(",",$win));
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                            }
                    }
                }
                if ($haswin == false)
                    return 0;
                else
                    return $countwin;
            }

            $haswin = true;
            if($game_id==9 || $game_id==109) //check lo xien 2
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$bet_number));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) { 
                        $win = GameHelpers::CheckLoXien(trim($listbets[$i]).','.trim($listbets[$j]),$rs);
                        
                        if(count($win) > 0){
                            // \Log::info('win was @ ' . implode(",",$win));
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                            }
                    }
                }
                if ($haswin == false)
                    return 0;
                else
                    return $countwin;
            }

            if($game_id==10 || $game_id==110) //check lo xien 3
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$bet_number));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) {
                        for ($k=$j+1; $k < count($listbets); $k++) { 
                            $win = GameHelpers::CheckLoXien(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]),$rs);
                            
                            if(count($win) > 0){
                                    // \Log::info('win was @ ' . implode(",",$win));
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                            }
                        }
                    }
                }
                if ($haswin == false)
                    return 0;
                else
                    return $countwin;
            }

            if($game_id==11 || $game_id==111) //check lo xien 4
            {
                $haswin = false;
                $countwin = 0;
                $winnumber="";
                $listbets = explode(",",str_replace(" ","",$bet_number));
                for ($i=0; $i < count($listbets); $i++) { 
                    for ($j=$i+1; $j < count($listbets); $j++) {
                        for ($k=$j+1; $k < count($listbets); $k++) { 
                            for ($l=$k+1; $l < count($listbets); $l++) { 
                                $win = GameHelpers::CheckLoXien(trim($listbets[$i]).','.trim($listbets[$j]).','.trim($listbets[$k]).','.trim($listbets[$l]),$rs);
                                // \Log::info('win was @ ' . implode(",",$win));
                                
                                if(count($win) > 0){
                                    $countwin++;
                                    $haswin = true;
                                    $winnumber.='|'.implode(",",$win);
                                }
                            }
                        }
                    }
                }
                if ($haswin == false)
                    return 0;
                else
                    return $countwin;
            }
        return 0;
    }

    public function postQuickplayguest(Request $request)
    {
        if (strlen($request->quicktext) <1 )
            $request->quicktext = 'de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50';
            // print_r ($request->quicktext);

        $input = Input::get('vaocuoc');
        $date = Input::get('date');
        // echo $date;
        if (isset($input))
            $_iscuoc = '1';
        else
            $_iscuoc = '0';
        $requestdata = $this->quickplaylogicguest($request->quicktext,$_iscuoc,$date);
        return view
        (
            'frontend.gamequickplayguest',
            [
                'requestdata'=> $requestdata[0],
                'quicktext'=>$request->quicktext,
                'quicktextnotmatch'=>$requestdata[1],
                '_iscuoc'=>$_iscuoc,
                'date'=>$date
                // 'xosorecords'=> XoSoRecordHelpers::GetByUser($user,$slug),
            ]
        );
    }

    public function getHistory(Request $request,$slug)
    {
        $user = Auth::user();
        $location = LocationHelpers::getBySlug($slug);
        // print_r($location);
        if(empty($location)){
            return view('frontend.404');
        }
        $newDate = date("Y-m-d");
        // return XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug);
        // echo SabaHelpers::converDate('2022-10-24T03:00:00.000-04:00');
        return view
        (
            'frontend.historybet',
            [
                'user'=>Auth::user(),
                'location'=> $location,
                'xosorecords'=> XoSoRecordHelpers::GetByUserByDateLocation($user,$newDate,$slug),
            ]
        );
    }

    public function getHistorySk(Request $request,$slug)
    {
        $user = Auth::user();
        $location = LocationHelpers::getBySlug($slug);
        if(empty($location)){
            return view('frontend.404');
        }
        $newDate = date("Y-m-d");
        $newDateShow = date("d-m-Y");
        if (date('H') < 11){
            $newDate=date("Y-m-d",strtotime('-1 day',strtotime($newDate)));
            $newDateShow=date("d-m-Y",strtotime('-1 day',strtotime(date("d-m-Y"))));
        }
        // return XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug);
        return view
        (
            'frontend.historybetsk',
            [
                'user'=>Auth::user(),
                'location'=> $location,
                'xosorecords'=> XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug),
                'newDate' => $newDateShow
            ]
        );
    }

    public function getInbets(Request $request)
    {
        $user = Auth::user();
        $newDate = date("Y-m-d");
        $newDateShow = date("d-m-Y");
        // if (date('H') < 11){
        //     $newDate=date("Y-m-d",strtotime('-1 day',strtotime($newDate)));
        //     $newDateShow=date("d-m-Y",strtotime('-1 day',strtotime(date("d-m-Y"))));
        // }
        // return XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug);
        $stDate= $newDate;
        $endDate= $newDate;
        $history = XoSoRecordHelpers::getRecordKhachByDateHistorytv2Inprocess(Auth::user());
        return view
        (
            'frontend.inbets',
            [
                'user'=>Auth::user(),
                'xosorecords'=> $history,
                'newDate' => $newDateShow,
                'type' => $request->type
            ]
        );
    }

    public function getCancelbets(Request $request)
    {
        $user = Auth::user();
        $newDate = date("Y-m-d");
        $newDateShow = date("d-m-Y");
        // if (date('H') < 11){
        //     $newDate=date("Y-m-d",strtotime('-1 day',strtotime($newDate)));
        //     $newDateShow=date("d-m-Y",strtotime('-1 day',strtotime(date("d-m-Y"))));
        // }
        // return XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug);
        $stDate= $newDate;
        $endDate= $newDate;
        $history = XoSoRecordHelpers::getRecordKhachByDateHistorytv2Cancel(Auth::user());
        return view
        (
            'frontend.inbets',
            [
                'user'=>Auth::user(),
                'xosorecords'=> $history,
                'newDate' => $newDateShow,
                'type' => 2
            ]
        );
    }

    // public function getReports(Request $request){
    //     $user = Auth::user();
    //     $newDate = date("Y-m-d");
    //     $newDateShow = date("d-m-Y");
    //     if (date('H') < 11){
    //         $newDate=date("Y-m-d",strtotime('-1 day',strtotime($newDate)));
    //         $newDateShow=date("d-m-Y",strtotime('-1 day',strtotime(date("d-m-Y"))));
    //     }
    //     // return XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug);
    //     //build data
    //     $today = date("Y-m-d");
    //     $rs =
    //         DB::table('xoso_record')
    //         ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
    //             IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
    //             ) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
    //         ->orderBy('sumbet', 'desc')
    //         ->where('isDelete', false)
    //         ->where('date', $today)
    //         // ->where('date','<=',$endDate)
    //         // ->whereIn('game_id', [7,12,14])
    //         ->where('user_id', $user->id)
    //         ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
    //         ->join('location', 'location.slug', '=', 'games.location_id')
    //         ->groupBy('location.slug')
    //         ->get();

    //         $from = date($today." 00:00:00");
    //         $to = date($today." 23:59:59");

    //     $r7zball =
    //         DB::table('history_7zball_bet')
    //         ->select('history_7zball_bet.gametype as game_id', DB::raw('SUM(betamount) AS sumbet'), DB::raw('SUM(payoff) AS sumwin'), 'location.name as location_name', 'location.id as location_id')
    //         ->orderBy('sumbet', 'desc')
    //         ->whereBetween('createdate', [$from, $to])
    //         // ->where('date','<=',$endDate)
    //         // ->whereIn('game_id', [7,12,14])
    //         ->where('username', $user->name)
    //         ->join('games', 'games.game_code', '=', 'history_7zball_bet.gametype')
    //         ->join('location', 'location.slug', '=', 'games.location_id')
    //         ->groupBy('location.slug')
    //         ->get();

    //     $rs = array_merge($rs,$r7zball);
    //     $data = [];//[["Đài", "Tiền cược", "Thắng thua"]];
    //     $totalSumbet = 0;
    //     $totalSumwin = 0;

    //     foreach ($rs as $record) {
    //         array_push($data, [$record->location_name, number_format($record->sumbet), number_format($record->sumwin)]);
    //         $totalSumbet += $record->sumbet;
    //         $totalSumwin += $record->sumwin;
    //     }
    //     // array_push($data, ["Tổng", number_format($totalSumbet), number_format($totalSumwin)]);
    //     $stDate= $newDate;
    //     $endDate= $newDate;
    //     $history = XoSoRecordHelpers::getRecordKhachByDateHistorytv2(Auth::user(),$stDate,$endDate,"all",[$request->type]);
    //     return view
    //     (
    //         'frontend.reports',
    //         [
    //             'user'=>Auth::user(),
    //             'reports'=> $data,
    //             'newDate' => $newDateShow,
    //             'type' => $request->type
    //         ]
    //     );
    // }

    private function find($arr,$date){
        foreach ($arr as $key => $item) {
            if ($item->date == $date)
                return $item;
        }
        return json_decode(json_encode(["ticket" => 0, "sumwin" => 0, "sumbet" => 0, "sumcom" => 0]));
;
    }

    private function getDataReports($staticstart, $staticfinish){
        $user = Auth::user();
        $rs =
            DB::table('xoso_record')
            ->select('date', 'game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
                IF(game_id = 15 OR game_id = 16, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
                ) AS sumwin'), 'games.short_name as game_name')
            ->orderBy('date', 'desc')
            ->where('isDelete', false)
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            // ->where('date','<=',$endDate)
            // ->whereIn('game_id', [7,12,14])
            ->where('user_id', $user->id)
            ->where('total_win_money', '!=', 0)
            ->join('games', 'games.game_code', '=', 'xoso_record.game_id')
            ->groupBy('date')
            ->get();

        $rshistory =
            DB::table('history')
            ->select('date',DB::raw('count(*) AS ticket'))
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            ->where('user_create', $user->id)
            ->groupBy('date')
            ->get();

        $rs7zhistory =
            DB::table('history_7zball_bet')
            ->select('date',DB::raw('count(*) AS ticket'), DB::raw('SUM(betamount) AS sumbet'),DB::raw('SUM(payoff) AS sumwin'),DB::raw('SUM(com) AS sumcom'))
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            ->where('paid',1)
            ->where('username', $user->name)
            ->groupBy('date')
            ->get();

        $rsMinigamehistory =
            DB::table('history_minigame_bet')
            ->select('date',DB::raw('count(*) AS ticket'), DB::raw('SUM(betamount) AS sumbet'),DB::raw('SUM(payoff) AS sumwin'),DB::raw('SUM(com) AS sumcom'))
            ->where('date', '>=', $staticstart)
            ->where('date', '<=', $staticfinish)
            ->where('paid',1)
            ->where('username', $user->name)
            ->groupBy('date')
            ->get();
        $dataThisWeek = [];
        $total = [];
        $totalSumbet = 0;
        $totalSumwin = 0;
        $totalTicket = 0;
        setlocale(LC_TIME, 'vi_VN');
        $interval = DateInterval::createFromDateString('1 day');
        $begin = new DateTime($staticstart);
        $end = new DateTime($staticfinish);
        $end->modify('+1 day');
        $period = new DatePeriod($begin, $interval, $end);
        foreach($period as $dt){
            $dateFound = $dt->format("Y-m-d");
            $itemHistory = $this->find($rshistory,$dateFound);
            $itemRs = $this->find($rs,$dateFound);
            $itemRs7zHistory = $this->find($rs7zhistory,$dateFound);
            $itemRsMinigameHistory = $this->find($rsMinigamehistory,$dateFound);
            // 
            if ($itemRs->sumbet+$itemRs7zHistory->sumbet+$itemRsMinigameHistory->sumbet != 0 || $itemRs->sumwin+$itemRs7zHistory->sumwin+$itemRsMinigameHistory->sumwin != 0 ){
                array_push($dataThisWeek, [strftime("%A", strtotime($dateFound)), date("d-m",strtotime($dateFound)), $itemHistory->ticket + $itemRs7zHistory->ticket+ $itemRsMinigameHistory->ticket, ($itemRs->sumbet+$itemRs7zHistory->sumbet+$itemRsMinigameHistory->sumbet), ($itemRs->sumwin+$itemRs7zHistory->sumwin+$itemRsMinigameHistory->sumwin),$dateFound,$itemRs7zHistory->sumcom+$itemRsMinigameHistory->sumcom]);
                $totalTicket += $itemHistory->ticket + $itemRs7zHistory->ticket+ $itemRsMinigameHistory->ticket;
                $totalSumbet += $itemRs->sumbet+$itemRs7zHistory->sumbet+$itemRsMinigameHistory->sumbet;
                $totalSumwin += $itemRs->sumwin+$itemRs7zHistory->sumwin+$itemRsMinigameHistory->sumwin + $itemRs7zHistory->sumcom+$itemRsMinigameHistory->sumcom;
            }
        }

        return (object)["data" => $dataThisWeek, "total" => [($totalTicket), ($totalSumbet), ($totalSumwin)]];
    }

    public function getReports(Request $request){
        $user = Auth::user();
        
        $now = date("Y-m-d");

        $staticstart = $now;
        $staticfinish = $now;

        // check the current day
        if (date('D') != 'Mon') {
            //take the last monday
            $staticstart = date('Y-m-d', strtotime('last Monday'));
        } else {
            $staticstart = date('Y-m-d');
        }
        //always next saturday

        if (date('D') != 'Sun') {
            $staticfinish = date('Y-m-d', strtotime('next Sunday'));
        } else {
            $staticfinish = date('Y-m-d');
        }

        $reportsThisWeek = $this->getDataReports($staticstart, $staticfinish);
        
        $staticstart = date('Y-m-d', strtotime('-7 day', strtotime($staticstart)));
        $staticfinish = date('Y-m-d', strtotime('-7 day', strtotime($staticfinish)));

        $reportsLastWeek = $this->getDataReports($staticstart, $staticfinish);
        // var_dump($reportsLastWeek);
        // return "";
        return view
        (
            'frontend.reports',
            [
                'user'=>Auth::user(),
                'reportsThisWeek'=> $reportsThisWeek->data,
                'totalThisWeek' => $reportsThisWeek->total,
                'reportsLastWeek'=> $reportsLastWeek->data,
                'totalLastWeek' => $reportsLastWeek->total,
                'type' => $request->type
            ]
        );
    }

    public function getReportByDay(Request $request)
    {
        $user = Auth::user();
        $day = $request->day;
        // if (date('H') < 11){
        //     $day=date("Y-m-d",strtotime('-1 day',strtotime($day)));
        // }
        // return XoSoRecordHelpers::GetByUserSkByDateLocation($user,$newDate,$slug);
        $stDate= $day;
        $endDate= $day;
        $history = XoSoRecordHelpers::getRecordKhachByDateHistorytv2(Auth::user(),$stDate,$endDate,"all",[$request->type]);

        return view
        (
            'frontend.inbets',
            [
                'user'=>Auth::user(),
                'xosorecords'=> $history,
                'day' => $day,
                'type' => $request->type
            ]
        );
    }

    public function getHistorySkByDay($start,$end)
    {
        $user = Auth::user();
        $location = LocationHelpers::getBySlug(1);
        if(empty($location)){
            return view('frontend.404');
        }
        $startdate = date("Y-m-d", strtotime($start));
        $enddate = date("Y-m-d", strtotime($end));
        return view
        (
            'frontend.historysk',
            [
                'xosorecords'=> XoSoRecordHelpers::GetByUserSkByDateRange($user,$startdate,$enddate),
            ]
        );
    }

    public function getApiHistorySkByDay(Request $request)
    {
        try{
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            // if ($user->lock == 1)
            //     return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);
    
            $requestD = CryptoJsAes::decryptRequest($request);
            $start = $requestD->start;
            $end = $requestD->end;
    
            $location = LocationHelpers::getBySlug(1);
            if(empty($location)){
                return response()->json(['code'=>404,'message'=>'empty','data' => '' ]);
            }
            $startdate = date("Y-m-d", strtotime($start));
            $enddate = date("Y-m-d", strtotime($end));
    
            return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData(XoSoRecordHelpers::GetByUserSkByDateRangeApi($user,$startdate,$enddate)) ]);   
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }
    }

    public function getApiHistorySkByIds(Request $request)
    {
        try{
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            // if ($user->lock == 1)
            //     return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);
    
            $requestD = CryptoJsAes::decryptRequest($request);
            $ids = $requestD->ids;
    
            $location = LocationHelpers::getBySlug(1);
            if(empty($location)){
                return response()->json(['code'=>404,'message'=>'empty','data' => '' ]);
            }
    
            return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData(XoSoRecordHelpers::GetByUserSkByIdsAPI($user,$ids)) ]);   
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>$ex->getMessage()
            ]);
        }
    }

    public function getHistoryByDay($start,$end)
    {
        $user = Auth::user();
        $location = LocationHelpers::getBySlug(1);
        if(empty($location)){
            return view('frontend.404');
        }
        $startdate = date("Y-m-d", strtotime($start));
        $enddate = date("Y-m-d", strtotime($end));
        return view
        (
            'frontend.history',
            [
                'xosorecords'=> XoSoRecordHelpers::GetByUserByDateRange($user,$startdate,$enddate),
            ]
        );
    }

    public function getApiHistoryByDay(Request $request)
    {
        $start = $request->start;
        $end = $request->end;

        $user = Auth::user();
        $location = LocationHelpers::getBySlug(1);
        if(empty($location)){
            return response()->json(['code'=>404,'message'=>'empty','data' => '' ]);
        }
        $startdate = date("Y-m-d", strtotime($start));
        $enddate = date("Y-m-d", strtotime($end));

        return response()->json(['code'=>200,'message'=>'','data' => XoSoRecordHelpers::GetByUserByDateRange($user,$startdate,$enddate) ]);
        
    }
    public function getKqsxByDay($date)
    {
        $xoso = new XoSo();
        $newDate = date("Y-m-d", strtotime($date));
        return view('frontend.kqsx',['rs'=>$xoso->getKetQua(1,$newDate)]);
    }

    public function getKqsxmnByDay($date,$slug)
    {
        $xoso = new XoSo();
        $newDate = date("Y-m-d", strtotime($date));
        return view('frontend.kqsxmn',['slug'=>$slug,'rs'=>$xoso->getKetQua($slug,$newDate)]);
    }

    public function getKqsxmtByDay($date,$slug)
    {
        $xoso = new XoSo();
        $newDate = date("Y-m-d", strtotime($date));
        return view('frontend.kqsxmn',['slug'=>$slug,'rs'=>$xoso->getKetQua($slug,$newDate)]);
    }

    public function getKqsxminByDay($date)
    {
        $xoso = new XoSo();
        $newDate = date("Y-m-d", strtotime($date));
        return view('frontend.kqsxmin',['rs'=>$xoso->getKetQua(1,$newDate)]);
    }

    public function getKqkenominByNow($date)
    {
        $xoso = new XoSo();
        $newDate = date("Y-m-d", strtotime($date));

        $now = date('Y-m-d'); // date('Y-m-d');
		$hour = date('H');
		$min = date('i');
		$sec = date('s');
        // $rs = xoso::getKetQuaKeno(5,$hour,$min-$min%10,$now);
        return view('frontend.kqkenomin',['rs'=>$xoso->getKetQuaKeno(5,$hour,$min-$min%10,$now)]);
    }

    public function getRefreshLogin()
    {
        return view('frontend.login');
    }
    public function getLogin()
    {
        return view('frontend.home');
    }
    public function getRefreshTime()
    {
        return view('frontend.time-zone');
    }

    public function getRefreshOpenCloseGameTimer()
    {
        return view('frontend.open-close-game-timer');
    }

    public function getRefreshBetsTop5()
    {
        return view('frontend.refresh-bets-top5');
    }

    public function getXsaodiv()
    {
        // return view('frontend.time-zone');
        return view('frontend.xsaodiv');
    }

    public function getXslive()
    {
        // return view('frontend.time-zone');
        $now = date('Y-m-d');
        $xoso = new XoSo();
        $rs = $xoso->getKetQua(1,$now);
        $listResult = GameHelpers::BuildArrayResultLoLive($rs);
        var_dump($listResult);
        // return;
    }

    
    public function postDestroy($id)
    {
        XoSoRecordHelpers::DeleteLoto($id);
        return "true";
    }

    public function destroyApi(Request $request)
    {
        try{
            
            $user = Auth::user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.']);
            if ($user->lock == 1)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị ngừng vào cược. Vui lòng liên hệ quản lý.']);
    
            $requestD = CryptoJsAes::decryptRequest($request);

            if (isset($requestD->id)){
                $id = $requestD->id;
                return response()->json([
                    'code'=>200,
                    'message'=>'',
                    'data' => CryptoJsAes::encryptData(["status"=>XoSoRecordHelpers::DeleteLoto($id)])
                    // 'data' => XoSoRecordHelpers::DeleteLoto($id)
                ]);
            }

            if (isset($requestD->ids)){
                $ids = $requestD->ids;
                $data = [];
                foreach($ids as $id){
                    array_push($data, ["id"=>$id, "status"=>XoSoRecordHelpers::DeleteLoto($id) ]);
                }
                
                return response()->json([
                    'code'=>200,
                    'message'=>'',
                    'data' => $data
                    // 'data' => XoSoRecordHelpers::DeleteLoto($id)
                ]);
            }

            if(isset($requestD->betId)){
                $betId = $requestD->betId;
                $data = [];
                $quickplayhistory = QuickPlayRecord::where('user_id', $user->id)
                                    ->where('id',$betId)
                                    ->first();
                $listBetDetail = explode(',',$quickplayhistory->ids);
                foreach($listBetDetail as $id){
                    array_push($data, ["id"=>$id, "status"=>XoSoRecordHelpers::DeleteLoto($id) ]);
                }
                
                return response()->json([
                    'code'=>200,
                    'message'=>'',
                    'data' => $data
                    // 'data' => XoSoRecordHelpers::DeleteLoto($id)
                ]);
            }
            
        }catch(Exception $ex){
            return response()->json([
                'code'=>400,
                'message'=>""
                //$ex->getMessage()
            ]);
        }
        
    }

    public function getReloadUser(Request $request){
        $current_user = User::where('id', '=', Auth::user()->id)->first();
        // print_r(Auth::user()->id);

        return view
        (
            'frontend.control.user_info',
            [
                'user'=> $current_user
            ]
        );
    }

    public function postReloadUserData(Request $request){
        $current_user = User::where('id', '=', Auth::user()->id)->first();
        // print_r(Auth::user()->id);

        $user = Auth::user();
        $newDate = date("Y-m-d");
        if (date('H') < 11)
        $newDate=date("Y-m-d",strtotime('-1 day',strtotime($newDate)));
        $recordUser = XoSoRecordHelpers::GetByUserByDate($user,$newDate);

        // $recordUserBC = XoSoRecordHelpers::GetByUserByDate($user,$newDate);
        // print_r($recordUser);
        $somacuoc = count($recordUser);
        $thangthua = 0;
        $total = 0;
        // echo count($recordUser);
        foreach($recordUser as $record){
            if($record->locationslug==70 || $record->locationslug==80){
                if (isset($record->rawBet) && ($record->rawBet->paid != null || $record->rawBet->paid != 0)){
                    $thangthua += $record->total_win_money;
                }else{
                    $total+= $record->total_bet_money;
                }
            }else{
                if ($record->total_win_money == 0)
                    $total+= $record->total_bet_money;
                else{
                    if ( $record->total_win_money > 0){
                        if ($record->game_id > 3000 || $record->game_id == 15 || $record->game_id == 16
                        || $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616
                        ||$record->game_id == 115|| $record->game_id == 116 ){
                            $thangthua += $record->total_win_money;
                        // || $xosorecord->game_id == 16|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21
                        }else
                            $thangthua += ($record->total_win_money-$record->total_bet_money);
                    }else{
                        // if ($record->game_id > 3000)
                            // $thangthua += (0-$record->total_bet_money);
                        $thangthua += $record->total_win_money;
                    }
                }
            }
            if($record->locationslug==70 || $record->locationslug==80){
                $arrBonus = explode(",",$record->bonus);
                $bonus = end($arrBonus);
                if ($bonus > 0) 
                    $thangthua += $bonus;
            }
            //$thangthua += $record->total_win_money;
        }

        return ["remain"=>number_format($user->remain > 0 ? $user->remain : 0, 0), "inbet" => number_format($total, 0), "winlose" => number_format($thangthua, 0)];
    }
    
    public function encrypt(Request $request){
        
        return json_decode(CryptoJsAes::cryptoJsAesEncrypt(env('keyii', 'd4f137fc2e43fbac74b031e843e84f6a'),$request->getContent()), true);
        
    }

    public function decrypt(Request $request){
        // return json_decode($request->getContent(), true);
        // return json_encode($request->getContent());
        return CryptoJsAes::cryptoJsAesDecrypt(env('keyii', 'd4f137fc2e43fbac74b031e843e84f6a'),$request->getContent());
        
    }

    public function AccountStatistics(){
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        return response()->json([
            'code'=>200,
            'message'=>'',
            'data' => XoSoRecordHelpers::AccountStatistics($user)
        ]);
    }
}

