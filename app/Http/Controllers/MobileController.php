<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/15/2016
 * Time: 2:49 PM
 */
namespace App\Http\Controllers;
use App\ChucNang;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class MobileController extends Controller
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
        return view('frontend_mobile.home');
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
        $name = $request->session()->get('username');
        UserHelpers::changePass($name,$request->newpass);
        $request->session()->flash('password',$request->newpass);
        return 'true';
    }
    /**
     * Hàm xử lí check pass
     * @return \Illuminate\View\View
     */
    public function postCheckPass(Request $request)
    {
        $pass = $request->session()->get('password');
        if($request->oldpass == $pass)
        {
            return 'true';
        }
        return 'false';
    }

}