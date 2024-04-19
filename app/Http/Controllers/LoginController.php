<?php

/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/21/2016
 * Time: 10:58 AM
 */

namespace App\Http\Controllers;

use App\Commands\UpdateMeFromParentEXService;
use App\Helpers\UserHelpers;
use App\Http\Requests\UserRequest;
use App\Location;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Session;
use Crypt;
use Google2FA;
use \Queue;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function getLogin()
    {
        return view('admin.login', ['playgame' => 1]);
    }

    public function swapping($user, $ipadr)
    {
        $new_sessid   = \Session::getId(); //get new session_id after user sign in
        try {
            $last_session = \Session::getHandler()->read($user->last_sessid); // retrive last session

            if ($last_session) {
                if (\Session::getHandler()->destroy($user->last_sessid)) {
                    // session was destroyed
                }
            }
        } catch (\Exception $e) {
        }
        $user->last_sessid = $new_sessid;
        $user->latestlogin = date("Y-m-d H:i:s");
        $user->latestipadr = $ipadr;
        $user->loginfailure = 0;
        $user->save();
    }

    public function getToken($token = "6vFDLoDZJYKWWkJtxBIu0pj2t8Wl8cVPg6hcqgYSWWIP2FZHzmulbZiB0B5w", $router = "xoso")
    {
        $user = UserHelpers::GetUserByToken($token);
        $user_check = $user;
        // $auth = array(
        //     'name'=>'bbb4444',
        //     'password'=>'qaz123',
        // );
        // var_dump($user) ;
        Auth::login($user);
        // return $user;

        // return Auth::user();
        // if(Auth::attempt($auth))
        {
            // var_dump(Auth::user());
            $this->swapping(Auth::user(), "");
            // return redirect(url('/xoso/mienbac'));
            \Session::put('username', $user->name);
            // \Session::put('password',"qaz123");
            if (Auth::user()->roleid != 6){
                if (Auth::user()->per == 1) {
                    $user_follow = UserHelpers::GetUserByUserName(Auth::user()->usfollow)[0];
                    $user_follow->usfollow = Auth::user()->name;
                    $user_follow->per = 1;
                    $user_follow->name = Auth::user()->name . ' TKP';
                    Auth::login($user_follow);
                    Session::put('usersecondper', 1);
                    Session::put('usersecondname', $user_check->name);
                    Session::put('usersecondrole2', $user_check->role2);
                    Session::put('usersecondid', $user_check->id);
                }
                return redirect(url('/admin'));
            }
            else {
                // UpdateMeFromParentEXService
                if ($user->lock_price == 0)
                    Queue::pushOn("high", new UpdateMeFromParentEXService(Auth::user()));
                if ($router == "xoso")
                    return redirect(url("/xoso/mienbac/de"));
                if ($router == "7zball")
                    return redirect(url("/7zball"));
                return redirect(url("/"));
            }
        }
        return "false";
    }
    /**
     *
     */
    public function postLogin(Request $request)
    {
        try {
            $auth = array(
                'name' => $request->username,
                'password' => $request->passwd,
            );
            $user_check = UserHelpers::GetUserByUserName($request->username);
            if ($user_check != null && count($user_check) > 0 && $user_check[0]->active == 0) {
                // \Log::info('host was @ ' . $_SERVER['HTTP_HOST']);
                if (strpos($_SERVER['HTTP_HOST'], 'ag') !== false) {
                    if ($user_check[0]->roleid == 6)
                        return "false";
                }

                // if (strpos($_SERVER['HTTP_HOST'], 'ag') === false) {
                //     if ($user_check[0]->roleid != 6)
                //         return "false";
                // }

                if ($user_check[0]->lock == 3 || $user_check[0]->lock == 2) {
                    return "lock";
                } else {
                    if (Auth::attempt($auth)) {
                        $this->swapping(Auth::user(), $request->ipadr);

                        if (Auth::user()->per == 1) {
                            return "false";
                            $user_follow = UserHelpers::GetUserByUserName(Auth::user()->usfollow)[0];
                            $user_follow->usfollow = Auth::user()->name;
                            $user_follow->per = 1;
                            $user_follow->name = Auth::user()->name . ' TKP';
                            Auth::login($user_follow);
                            Session::put('usersecondper', 1);
                            Session::put('usersecondname', $user_check[0]->name);
                            Session::put('usersecondrole2', $user_check[0]->role2);
                            Session::put('usersecondid', $user_check[0]->id);
                        }

                        try {
                            if (Auth::user()->google2fa_secret != NULL && Auth::user()->per != 1) {
                                $secret = Crypt::decrypt(Auth::user()->google2fa_secret);

                                if ($request->otp != NULL && Google2FA::verifyKey($secret, $request->otp)) {
                                    \Session::put('username', $request->username);
                                    \Session::put('password', $request->passwd);
                                    if ($user_check[0]->roleid != 6)
                                        return "admin";
                                    else {
                                        // UpdateMeFromParentEXService
                                        if ($user_check[0]->lock_price == 0)
                                            Queue::pushOn("high", new UpdateMeFromParentEXService(Auth::user()));
                                        return "true";
                                    }
                                } else {
                                    // Auth::logout();
                                    //Session::put('usersecondper', 1);
                                    return "otp";
                                }
                            } else {
                                \Session::put('username', $request->username);
                                \Session::put('password', $request->passwd);
                                if ($user_check[0]->roleid != 6)
                                    return "admin";
                                else {
                                    // UpdateMeFromParentEXService
                                    if ($user_check[0]->lock_price == 0)
                                        Queue::pushOn("high", new UpdateMeFromParentEXService(Auth::user()));
                                    return "true";
                                }
                            }
                        } catch (\Exception $err) {
                            return "otp";
                        }
                    } else {
                        if (isset($user_check[0]->google2fa_srcret) && strlen($user_check[0]->google2fa_srcret) > 5) {
                        } else {
                            $user_check[0]->loginfailure += 1;
                        }

                        if ($user_check[0]->loginfailure >= 6) {
                            $user_check[0]->lock = 2;
                        }
                        $user_check[0]->latestipadr = $request->ipadr;
                        $user_check[0]->save();
                        return "false";
                    }
                }
            } else {
                return "false";
            }
        } catch (\Exception $err) {
            dd($err->getMessage());
            return $err->getMessage() . ' ' . $err->getLine() . ' ' . $err->getFile();
        }
    }
}
