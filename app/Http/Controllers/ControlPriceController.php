<?php
namespace App\Http\Controllers;
use App\ChucNang;
use App\Location;
use App\Role;
use App\User;
use App\CustomerType_Game_Original;
use App\CustomerType_Game;
use App\Helpers\UserHelpers;
use App\Helpers\RoleHelpers;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Bet;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use \Cache;
use luk79\CryptoJsAes\CryptoJsAes;

class ControlPriceController extends Controller {

    public function __construct()
    {
        $this->middleware('auth',['except' => ['getnewdata']]);
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex(Request $request,$locationid = 1)
    {
        $chucnangModel = new ChucNang();

        if (!$chucnangModel->handleUserSecond(21) )
            return "Cannot access this page! Failed!!!";

        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'locationId'=>1,
            ]
        );
    }

    public function getMienBac(Request $request,$locationId = 1)
    {
        $chucnangModel = new ChucNang();
      

        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'locationId'=>1,
            ]
        );
    }

    public function getKeno(Request $request,$locationId = 5)
    {
        $chucnangModel = new ChucNang();
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'locationId'=>5,
            ]
        );
    }

    public function getXsAo(Request $request,$locationId = 4)
    {
        $chucnangModel = new ChucNang();
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'locationId'=>4,
            ]
        );
    }

    public function getMienNam(Request $request,$locationId = 21)
    {
        $chucnangModel = new ChucNang();
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'locationId'=>$locationId,
            ]
        );
    }

    public function getMienTrung(Request $request,$locationId = 31)
    {
        $chucnangModel = new ChucNang();
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin(),
                'locationId'=>$locationId,
            ]
        );
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getLocal(Request $request,$locationid = 1)
    {
        $chucnangModel = new ChucNang();
        $name = $request->session()->get('username');
        $usercreate = UserHelpers::GetUserByUserName($name);
        return view
        (
            'admin.control.controltable',
            [
                'users'=> $usercreate[0],
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
            ]
        );
    }

    public function postUpdate(Request $request)
    {
        if (Auth::user()->roleid != 1 && Auth::user()->roleid != 2) return "false";
        GameHelpers::Update_Game_Number($request);
        return "true";
    }

    public function postGetnewdata(Request $request)
    {
        $listnumber = array();
        $game = GameHelpers::GetGameByCode($request->game_code);
        if (Auth::user()->roleid==1)
            $customer_type =CustomerType_Game::where('game_id',$request->game_code)
                        ->where('created_user', Auth::user()->id)
                        ->where('code_type', 'A')
                        ->first();
        else
            $customer_type =CustomerType_Game_Original::where('game_id',$request->game_code)
                        ->where('created_user', Auth::user()->id)
                        ->where('code_type', 'A')
                        ->first();

        if ($request->game_code == "8" || $request->game_code == "108" || $request->game_code == "17" || $request->game_code == "56"){
            for($i=0;$i<10;$i++)
                for($j=0;$j<10;$j++)
                    for($k=0;$k<10;$k++)
            {
                $data = GameHelpers::GetGame_Number($request->game_code,$i.$j.$k);
                $exchange_rates = "";
                $a = "";
                $x = "";
                $y = "";
                if(count($data)>0) {
                    $exchange_rates = $data[0]['exchange_rates'];
                    if ($data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                        $exchange_rates = $customer_type['exchange_rates'];
                    $a = $data[0]['a'];
                    $x = $data[0]['x'];
                    $y = $data[0]['y'];
                    $total = $data[0]['total'];
                }
                else{
                    $exchange_rates =  $customer_type['exchange_rates'];
                    $a = $game['a'];
                    $x = $game['x'];
                    $y = 0;
                    $total = 0;
                }
                $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j.$k);
                $totalBetNumberThau = 0;
                // if (rand(0,99) %10 ==0)
                //     $listnumber[$i.$j.$k] = array($exchange_rates+rand(0,10),$a,$x,$total);
                // else
                    $listnumber[$i.$j.$k] = array($exchange_rates,$y,$x,$totalBetNumber,$totalBetNumberThau);
                // $listnumber[$i.$j]['a'] = $a;
                // $listnumber[$i.$j]['x'] = $x;
                // $listnumber[$i.$j]['total'] = $total;
            }
        }else{
            for($i=0;$i<10;$i++)
                for($j=0;$j<10;$j++)
            {
                $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);
                $exchange_rates = "";
                $a = "";
                $x = "";
                $y = "";
                if(count($data)>0) {
                    $exchange_rates = $data[0]['exchange_rates'];
                    if ($data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                        $exchange_rates = $customer_type['exchange_rates'];
                    $a = $data[0]['a'];
                    $x = $data[0]['x'];
                    $y = $data[0]['y'];
                    $total = $data[0]['total'];
                }
                else{
                    $exchange_rates =  $customer_type['exchange_rates'];
                    $a = $game['a'];
                    $x = $game['x'];
                    $y = 0;
                    $total = 0;
                }

                if ($game['game_code']==24){
                    $totalBetNumber = 0;
                    $totalBetNumberThau = 0;
                    // for($k=31;$k<=55;$k++){
                        // $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber($k,$i.$j);
                        // $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($k,$i.$j);
                        $total = [0,0];
                        if (Auth::user()->roleid == 1)
                            $total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j,[0,0]);
                        else
                            $total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j.'-'.Auth::user()->id,[0,0]);
                        // Cache::get('TotalBetTodayByNumberThau-'.$k.'-'.$i.$j,[0,0]);
                        $totalBetNumber = $total[0];
                        $totalBetNumberThau = $total[1];
                    // }
                }else{
                    $total = [0,0];
                    if (Auth::user()->roleid == 1)
                        $total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j,[0,0]);
                    else
                        $total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j.'-'.Auth::user()->id,[0,0]);
                    // $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($game['game_code'],$i.$j);
                    $totalBetNumber = $total[0];
                    $totalBetNumberThau = $total[1];
                }

                // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j);
                // if ($game['game_code']==24){
                //     $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber(22,$i.$j);
                //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(23,$i.$j);
                //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(25,$i.$j);
                //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(26,$i.$j);
                //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(27,$i.$j);
                //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(28,$i.$j);
                //     $totalBetNumberThau=0;
                // }else{
                    // $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($game['game_code'],$i.$j);
                    // $totalBetNumber = $total[0];
                    // $totalBetNumberThau = $total[1];
                // }
                // if (rand(0,99) %10 ==0)
                //     $listnumber[$i.$j] = array($exchange_rates+rand(0,10),$a,$x,$total);
                // else
                    $listnumber[$i.$j] = array($exchange_rates,$y,$x,$totalBetNumber,$totalBetNumberThau);
                // $listnumber[$i.$j]['a'] = $a;
                // $listnumber[$i.$j]['x'] = $x;
                // $listnumber[$i.$j]['total'] = $total;
            }
        }
        return $listnumber;
    }

    public function newdataAPI(Request $request)
    {
        $request = CryptoJsAes::decryptRequest($request);
        
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        $listnumber = array();
        if($user->roledid != 6){
            $game = GameHelpers::GetGameByCode($request->game_code);
            if ($user->roleid==1)
                $customer_type =CustomerType_Game::where('game_id',$request->game_code)
                            ->where('created_user', $user->id)
                            ->where('code_type', 'A')
                            ->first();
            else
                $customer_type =CustomerType_Game_Original::where('game_id',$request->game_code)
                            ->where('created_user', $user->id)
                            ->where('code_type', 'A')
                            ->first();
    
            if ($request->game_code == "8" || $request->game_code == "108" || $request->game_code == "17" || $request->game_code == "56"){
                for($i=0;$i<10;$i++)
                    for($j=0;$j<10;$j++)
                        for($k=0;$k<10;$k++)
                {
                    $data = GameHelpers::GetGame_Number($request->game_code,$i.$j.$k);
                    $exchange_rates = "";
                    $a = "";
                    $x = "";
                    $y = "";
                    if(count($data)>0) {
                        $exchange_rates = $data[0]['exchange_rates'];
                        if ($data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                            $exchange_rates = $customer_type['exchange_rates'];
                        $a = $data[0]['a'];
                        $x = $data[0]['x'];
                        $y = $data[0]['y'];
                        $total = $data[0]['total'];
                    }
                    else{
                        $exchange_rates =  $customer_type['exchange_rates'];
                        $a = $game['a'];
                        $x = $game['x'];
                        $y = 0;
                        $total = 0;
                    }
                    $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j.$k);
                    $totalBetNumberThau = 0;
                    // if (rand(0,99) %10 ==0)
                    //     $listnumber[$i.$j.$k] = array($exchange_rates+rand(0,10),$a,$x,$total);
                    // else
                        $listnumber[$i.$j.$k] = array($exchange_rates,$y,$x,$totalBetNumber,$totalBetNumberThau);
                    // $listnumber[$i.$j]['a'] = $a;
                    // $listnumber[$i.$j]['x'] = $x;
                    // $listnumber[$i.$j]['total'] = $total;
                }
            }else{
                for($i=0;$i<10;$i++)
                    for($j=0;$j<10;$j++)
                {
                    $data = GameHelpers::GetGame_Number($request->game_code,$i.$j);
                    $exchange_rates = "";
                    $a = "";
                    $x = "";
                    $y = "";
                    if(count($data)>0) {
                        $exchange_rates = $data[0]['exchange_rates'];
                        if ($data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                            $exchange_rates = $customer_type['exchange_rates'];
                        $a = $data[0]['a'];
                        $x = $data[0]['x'];
                        $y = $data[0]['y'];
                        $total = $data[0]['total'];
                    }
                    else{
                        $exchange_rates =  $customer_type['exchange_rates'];
                        $a = $game['a'];
                        $x = $game['x'];
                        $y = 0;
                        $total = 0;
                    }
    
                    if ($game['game_code']==24){
                        $totalBetNumber = 0;
                        $totalBetNumberThau = 0;
                        // for($k=31;$k<=55;$k++){
                            // $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber($k,$i.$j);
                            // $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($k,$i.$j);
                            $total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j,[0,0]);
                            // Cache::get('TotalBetTodayByNumberThau-'.$k.'-'.$i.$j,[0,0]);
                            $totalBetNumber = $total[0];
                            $totalBetNumberThau = $total[1];
                        // }
                    }else{
                        $total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j,[0,0]);
                        // $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($game['game_code'],$i.$j);
                        $totalBetNumber = $total[0];
                        $totalBetNumberThau = $total[1];
                    }
    
                    // $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($request->game_code,$i.$j);
                    // if ($game['game_code']==24){
                    //     $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber(22,$i.$j);
                    //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(23,$i.$j);
                    //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(25,$i.$j);
                    //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(26,$i.$j);
                    //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(27,$i.$j);
                    //     $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(28,$i.$j);
                    //     $totalBetNumberThau=0;
                    // }else{
                        // $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($game['game_code'],$i.$j);
                        // $totalBetNumber = $total[0];
                        // $totalBetNumberThau = $total[1];
                    // }
                    // if (rand(0,99) %10 ==0)
                    //     $listnumber[$i.$j] = array($exchange_rates+rand(0,10),$a,$x,$total);
                    // else
                        $listnumber[$i.$j] = array($exchange_rates,$y,$x,$totalBetNumber,$totalBetNumberThau);
                    // $listnumber[$i.$j]['a'] = $a;
                    // $listnumber[$i.$j]['x'] = $x;
                    // $listnumber[$i.$j]['total'] = $total;
                }
            }
            return response()->json(['code'=>200,'message'=>'','data' => $listnumber ]);
        }else{
            return response()->json(['code'=>200,'message'=>'','data' => [] ]);
        }
        
    }

    public function getRefreshNumber(Request $request,$gamecode,$i,$j){
        if (Auth::user()->roleid==1)
            return view
            (
                'admin.control.refresh_number_100',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'customer_type'=> CustomerType_Game::where('game_id',$gamecode)
                        ->where('created_user', Auth::user()->id)
                        ->where('code_type', 'A')
                        ->first(),
                    'i'=> $i,
                    'j'=> $j
                ]
            );
        else
            return view
            (
                'admin.control.refresh_number_100',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'customer_type'=> CustomerType_Game_Original::where('game_id',$gamecode)
                        ->where('created_user', Auth::user()->id)
                        ->where('code_type', 'A')
                        ->first(),
                    'i'=> $i,
                    'j'=> $j
                ]
            );
    }
    public function getRefreshNumber1000(Request $request,$gamecode,$t,$k,$l){
        return view
        (
            'admin.control.refresh_number_1000',
            [
                'game'=> GameHelpers::GetGameByCode($gamecode),
                'customer_type'=> CustomerType_Game_Original::where('game_id',$gamecode)
                    ->where('created_user', Auth::user()->id)
                    ->where('code_type', 'A')
                    ->first(),
                't'=> $t,
                'k'=> $k,
                'l'=>$l
            ]
        );
    }
    public function getLoadNumber(Request $request,$gamecode){
        if ($gamecode >= 31 && $gamecode == 55)
            $gamecode=24;
        if (Auth::user()->roleid==1)
            return view
            (
                'admin.control.number',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'customer_type'=> CustomerType_Game::where('game_id',$gamecode)
                        ->where('created_user', Auth::user()->id)
                        ->where('code_type', 'A')
                        ->first()

                ]
            );
        else
            return view
            (
                'admin.control.number',
                [
                    'game'=> GameHelpers::GetGameByCode($gamecode),
                    'customer_type'=> CustomerType_Game_Original::where('game_id',$gamecode)
                        ->where('created_user', Auth::user()->id)
                        ->where('code_type', 'A')
                        ->first()

                ]
            );
    }
    public function postSearchNumber(Request $request){
        return GameHelpers::GetGameByNumber($request->game_code,$request->number);
    }
}
