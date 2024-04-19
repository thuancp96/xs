<?php

namespace App\Http\Controllers;

use Crypt;
use Google2FA;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use \ParagonIE\ConstantTime\Base32;
use App\ChucNang;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Google2FAController extends Controller
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

    public function getValidate(Request $request)
    {
        $chucnangClass = new ChucNang();
        return view('admin.ggauth.ggauthvalidate',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
    }
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function enableTwoFactor(Request $request)
    {
        //generate new secret
        $secret = $this->generateSecret();
        $user = $request->user();
        if (Session::get('usersecondper') == 1){
            $user = User::where('id',Session::get('usersecondid'))->first();
        }
        // Session::put('usersecondname', $user_check[0]->name);
        // Session::put('usersecondrole2', $user_check[0]->role2);
        // Session::put('usersecondid', $user_check[0]->id);
        //get user
        

        //encrypt and then save secret
        $user->google2fa_secret_temp = Crypt::encrypt($secret);
        $user->save();

        //generate image for QR barcode
        $imageDataUri = Google2FA::getQRCodeInline(
            $request->getHttpHost(),
            $user->name,
            $secret,
            200
        );

        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            return view('admin.ggauth.ggauthenable', ['image' => $imageDataUri,
                'secret' => $secret,'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            return view('frontend.ggauth.ggauthenable', ['image' => $imageDataUri,
                'secret' => $secret]);
        }
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function confirmTwoFactor(Request $request)
    {
    	// $chucnangClass = new ChucNang();

        //generate new secret
        // $secret = $this->generateSecret();

        

        //generate image for QR barcode
        // $imageDataUri = Google2FA::getQRCodeInline(
        //     $request->getHttpHost(),
        //     $user->name,
        //     $secret,
        //     200
        // );

        $user = Auth::user();
        if (Session::get('usersecondper') == 1){
            $user = User::where('id',Session::get('usersecondid'))->first();
        }

        $secret = Crypt::decrypt($user->google2fa_secret_temp);

        if ($request->otp != NULL && Google2FA::verifyKey($secret, $request->otp))
        {
            // \Session::put('username',$request->username);
            // \Session::put('password',$request->passwd);
            // if($user_check[0]->roleid!=6)
                // return "admin";
            // else
            //get user
            $user = $request->user();

            if (Session::get('usersecondper') == 1){
                $user = User::where('id',Session::get('usersecondid'))->first();
            }
            //encrypt and then save secret
            $user->google2fa_secret = $user->google2fa_secret_temp;
            $user->save();
            return "true";
        }else{
            // Auth::logout();
            //Session::put('usersecondper', 1);
            return "otp";
        }
        
        return true;
    }

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function disableTwoFactor(Request $request)
    {
        $user = $request->user();

        if (Session::get('usersecondper') == 1){
            $user = User::where('id',Session::get('usersecondid'))->first();
        }
        //make secret column blank
        $user->google2fa_secret = null;
        $user->save();

        if (Auth::user()->roleid != 6){
            $chucnangClass = new ChucNang();
            return view('admin.ggauth.ggauthdisable',['chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin()]);
        }else{
            return view('frontend.ggauth.ggauthdisable');
        }
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
}
