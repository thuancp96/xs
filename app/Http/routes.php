<?php

use App\Helpers\XoSo;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions;
use luk79\CryptoJsAes\CryptoJsAes;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}

Route::group(['prefix' => 'api','middleware' => 'cors'], function()
{

    Route::post('encrypt', 'HomeController@encrypt');
    Route::post('decrypt', 'HomeController@decrypt');

    Route::post('user/login', 'APIController@login');

    // Route::get('user/login', 'APIController@login');

    Route::get('locations', 'APIController@getListLocation');
    Route::post('games', 'APIController@getGameByLocation');

    Route::get('livegames', 'LiveCasinoController@login');
    Route::get('bbin/info', 'LiveCasinoController@info');
    Route::get('bbin/logout', 'LiveCasinoController@logout');
    Route::get('bbin/transfer', 'LiveCasinoController@transfer');
    Route::get('bbin/recall', 'LiveCasinoController@recall');

    Route::get('sabagames', 'SabaController@login');
    Route::get('sabagamesmobile', 'SabaController@loginmobile');
    Route::get('saba/info', 'SabaController@info');
    Route::get('saba/logout', 'SabaController@logout');
    Route::get('saba/transfer', 'SabaController@transfer');
    Route::get('saba/recall', 'SabaController@recall');

    Route::get('7zball', 'S7zballController@login');
    // Route::get('sabagamesmobile', 'SabaController@loginmobile');
    Route::get('7zball/info', 'S7zballController@info');
    Route::get('7zball/logout', 'S7zballController@logout');
    Route::get('7zball/transfer', 'S7zballController@transfer');
    Route::get('7zball/recall', 'S7zballController@recall');

    Route::get('minigame', 'MinigameController@login');
    // Route::get('sabagamesmobile', 'SabaController@loginmobile');
    Route::get('minigame/info', 'MinigameController@info');
    Route::get('minigame/logout', 'MinigameController@logout');
    Route::get('minigame/transfer', 'MinigameController@transfer');
    Route::get('minigame/recall', 'MinigameController@recall');
    
    // Route::post('operator/getbalance', 'SabaController@getbalance');
    // Route::post('operator/placebet', 'SabaController@placebet');
    // Route::post('operator/confirmbet', 'SabaController@confirmbet');
    // Route::post('operator/cancelbet', 'SabaController@cancelbet');
    // Route::post('operator/settle', 'SabaController@settle');
    // Route::post('operator/resettle', 'SabaController@settle');
    // Route::post('operator/unsettle', 'SabaController@unsettle');
    // Route::post('operator/placebetparlay', 'SabaController@placebetparlay');
    // Route::post('operator/confirmbetparlay', 'SabaController@confirmbetparlay');
    // Route::post('operator/placebet3rd', 'SabaController@placebet3rd');
    // Route::post('operator/confirmbet3rd', 'SabaController@confirmbet3rd');
    // Route::post('operator/placebetent', 'SabaController@placebetent');
    // Route::post('operator/settleent', 'SabaController@settleent');
    // Route::post('operator/cancelbetent', 'SabaController@cancelbetent');
    // Route::post('operator/getticketinfo', 'SabaController@getticketinfo');
    // Route::post('operator/healthcheck', 'SabaController@healthcheck');
    // Route::post('operator/adjustbalance', 'SabaController@adjustbalance');

    Route::post('operator/adjust-balance', 'APIController@adjustbalance');
    Route::post('operator/get-balance', 'APIController@getbalance');

    Route::get('test', function () {
        return response()->json(['code'=>200,'message'=>'','data' => "test"]);
    });
    
    // Route::get('kqmb', function () {
    //     $xoso = new XoSo();
    //         $rs = $xoso->getKetQua(1,date('Y-m-d'));
    //     return response()->json(['code'=>200,'message'=>'','data' => $rs]);
    // });

    Route::get('refresh-token', function () {
        try{
            $token = JWTAuth::getToken();
            $new_token = JWTAuth::refresh($token);
            return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData(['token' => $new_token]) ]);
        }catch(\Exception $ex){
            return response()->json(['code'=>4001,'message'=>$ex->getMessage(),'data'=>'']);
        }
    });

    Route::get('logout', function () {
        try{
            $token = JWTAuth::getToken();
            $status = JWTAuth::invalidate($token);
            return response()->json(['code'=>200,'message'=>'','data' => CryptoJsAes::encryptData(['invalidate' => $status])]);
        }catch(\Exception $ex){
            return response()->json(['code'=>4001,'message'=>$ex->getMessage(),'data'=>'']);
        }
    });

    // Route::middleware(['jwt.auth'])->get('users', function(Request $request) {
    //     return auth()->user();
    // });

    Route::group(['middleware' => ['jwt.auth','cors'], 'prefix' => 'v1.0'], function () {

        Route::post('store-user', 'UserController@postApiUserStore');
        Route::post('update-user', 'UserController@postUpdateUserApi');
        
        Route::get('user', function(Request $request) {

            $user = auth()->user();
            if ($user->lock == 2)
                return response()->json(['code'=>401,'message'=>'Tài khoản đã bị khoá. Vui lòng liên hệ quản lý.'],401);

            return response()->json(['code'=>200,'message'=>'',

            'data' => CryptoJsAes::encryptData(
                [
                    "id" => auth()->user()->id,
                    "role" => "admin",//auth()->user()->roleid,
                    "roleid" => auth()->user()->roleid,
                    "name"=> auth()->user()->name,
                    "credit" => auth()->user()->credit,
                    "remain" => auth()->user()->remain,
                    "lock_price" => auth()->user()->lock_price,
                    "lock" => auth()->user()->lock,
                    "rollback_money" => auth()->user()->rollback_money,
                    "bet" => auth()->user()->bet,
                    "thau" => auth()->user()->thau,
                    "latestlogin" => auth()->user()->latestlogin,
                ]
                ) ]);
        });
        Route::get('account-statistics', 'HomeController@AccountStatistics');
        Route::post('accounts-list', 'UserController@AccountsList');
        Route::post('account', 'UserController@Account');
        Route::get('create-token-bot-tele', 'UserController@createTokenTeleAPI');
        Route::get('reset-token-tele', 'UserController@ResetTokenTelegramAPI');
        Route::get('reset-otp', 'UserController@ResetOTPAPI');
        Route::get('disable-user', 'UserController@disableUserAPI');
        Route::get('odds', 'ControlPriceController@newdataAPI');
        Route::get('control-auto-price', 'ControlAutoPriceController@controlAutoPriceAPI');
        Route::get('reports', 'ReportController@reportBetAPI');
        
        // Route::post('store', 'GameController@postApiStore');
        Route::post('historyskbyday', 'HomeController@getApiHistorySkByDay');
        Route::post('historyskbyids', 'HomeController@getApiHistorySkByIds');
        Route::post('historybyday', 'HomeController@getApiHistoryByDay');
        Route::post('load-type-game', 'CustomerController@loadTypeGameAPI');
        Route::post('load-type-game-orginal', 'CustomerController@loadTypeGameOriginalAPI');
        Route::post('update-type-game', 'CustomerController@postUpdateAPICustomerTypeGameBySelf');
        Route::post('update-type-game-by-user', 'CustomerController@postUpdateAPICustomerTypeGameByUser');
        Route::post('changepass', 'AdminController@ChangePassAPI');
        Route::post('quickplay', 'HomeController@quickplaylogicApi');
        Route::post('normalplay', 'HomeController@normalplaylogicApi');
        Route::post('cancelbet', 'HomeController@destroyApi');
        Route::post('price', 'GameController@getPriceApi');
        Route::post('historyquickbet', 'HomeController@getReloadQuickplayhistoryApi');
        Route::post('historyquickbetid', 'HomeController@getReloadQuickplayhistorybyidApi');
        
        Route::get('check-users', function (Request $request) {
            try{
                $token = JWTAuth::parseToken();
                // $token = JWTAuth::setToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly94czgzODYubG9jYWwvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE1MzYyOTIxNzksImV4cCI6MTUzNjI5NTc3OSwibmJmIjoxNTM2MjkyMTc5LCJqdGkiOiJ0ajBWUnpSUGtJUVIwRGhUIn0.QqAwNylRWskwOdCreuCoCsw-KTknkCQhO_XJm2uFZyE');
                if (! $user = $token->authenticate()) {
                    return response()->json(['user_not_found'], 404);
                }
            }catch(\Exception $ex){
                return response()->json(['message' => $ex->getMessage()], 404);
            }
            
            // the token is valid and we have found the user via the sub claim
            return response()->json(compact('user'));
        });
    });
    

});

Route::get('test', function () {
    return view('new/adminlte_login_template');
});

Route::get('bbin', function () {
    if (auth()->user()!=null)
    return view ('frontend/livecasino');
    else return redirect("/");
});

Route::get('saba', function () {
    if (auth()->user()!=null)
        return view ('frontend/sabagames');
    else return redirect("/");
});

Route::get('7zball', function () {
    if (auth()->user()!=null)
        return view ('frontend/7zball');
    else return redirect("/");
});

Route::get('minigame', function () {
    if (auth()->user()!=null)
        return view ('frontend/minigame');
    else return redirect("/");
});

// Route::get('bridge_tele/{url}', function ($url) {
//     return view ('frontend/iframe_android_tele',[ 'url_target' =>  $url]);
// });

Route::get('adminlte', 'TestController@index');
Route::get('test1', 'TestController@index');

Route::get('themphanchoi', 'TestController@themphanchoi');
Route::get('trathuong', 'TestController@trathuong');
Route::get('hoahong', 'TestController@hoahong');
Route::get('clean', 'TestController@clean');

Route::get('xosobot', 'XosobotController@xosobot');
Route::post('xosobot', 'XosobotController@xosobot');

Route::get('xosobotasm', 'XosobotController@xosobotasm');
Route::post('xosobotasm', 'XosobotController@xosobotasm');

Route::get('trolymb', 'XosobotController@xosobottrolymb');
Route::post('trolymb', 'XosobotController@xosobottrolymb');

Route::get('luckeybot', 'XosobotController@luckeybot');
Route::post('luckeybot', 'XosobotController@luckeybot');

Route::get('xosobotagent', 'XosobotController@xosobot_agent');
Route::post('xosobotagent', 'XosobotController@xosobot_agent');

Route::get('quanlyso', 'XosobotController@xosobotquanlyso');
Route::post('quanlyso', 'XosobotController@xosobotquanlyso');

Route::get('nhantinmb', 'XosobotController@xosobotnhantinmb');
Route::post('nhantinmb', 'XosobotController@xosobotnhantinmb');


Route::get('/2fa/enable', 'Google2FAController@enableTwoFactor');
Route::get('/2fa/disable', 'Google2FAController@disableTwoFactor');
Route::post('/2fa/confirmTwoFactor', 'Google2FAController@confirmTwoFactor');
// Route::get('/2fa/validate', 'Google2FAController@getValidate');
// Route::post('/2fa/validate', ['uses' => 'Auth\AuthController@postValidateToken']);
Route::get('huongdannhaptinnhanh', function() {
    return view('frontend/huongdancuocnhanh');
});

Route::get('/issues/create', 'IssueController@create');
Route::post('/issues', 'IssueController@store');

Route::controllers([
    '/admin'=>'AdminController',
    '/users'=>'UserController',
    '/games'=>'GameController',
    '/rp'=>'ReportController',
    '/auth'=>'LoginController',
    '/ggauth'=>'Google2FAController',
    '/role'=>'RoleController',
    '/control-price'=>'ControlPriceController',
    '/customer-type'=>'CustomerController',
    '/control-max'=>'ControlMaxController',
    '/control-ex'=>'ControlExController',
    '/control-auto-price'=>'ControlAutoPriceController',
    '/mb'=>'MobileController',
    '/notification'=>'NotificationController',
    '/'=>'HomeController',
]);



