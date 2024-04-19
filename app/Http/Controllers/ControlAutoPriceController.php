<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/15/2016
 * Time: 2:49 PM
 */
namespace App\Http\Controllers;
use App\ChucNang;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\XoSo;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use luk79\CryptoJsAes\CryptoJsAes;

class ControlAutoPriceController extends Controller
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
        if (!$chucnangClass->handleUserSecond(24) )
            return "Cannot access this page! Failed!!!";
        $user = Auth::user();
        return view('admin.control_auto_price.controlautoprice',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGame(1),
            'locationId'=>1
                ]
        );
    }

    public function controlAutoPriceAPI(Request $request)
    {
        $user = auth()->user();
        if (isset(Auth::user()->usfollow) && Auth::user()->usfollow != "" ){
            $user = User::where("name",Auth::user()->usfollow)->first();
        }
        if ($user->roleid == 1)
            return response()->json(['code'=>200,'message'=>'',
                    'data' => [
                        "games"=>GameHelpers::GetAllGameControlAutoPrice(1)
                    ]
                    ]);
        else
            return response()->json(['code'=>400,'message'=>'','data' => '']);
    }

    public function getMienBac(Request $request)
    {
        $chucnangClass = new ChucNang();
        $user = Auth::user();
        return view('admin.control_auto_price.controlautoprice',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGame(1),
            'locationId'=>1
                ]
        );
    }

    public function getXsAo(Request $request)
    {
        $chucnangClass = new ChucNang();
        $user = Auth::user();
        return view('admin.control_auto_price.controlautoprice',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGame(4),
            'locationId'=>4
                ]
        );
    }

    public function postStore(Request $request)
    {
        $user = Auth::user();
        for($i=0;$i<count($request->changes); $i++)
        {
            GameHelpers::UpdateGameAXY($request->changes[$i],$user->id);
        }
        return $request;
    }
}