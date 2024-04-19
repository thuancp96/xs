<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/15/2016
 * Time: 2:49 PM
 */
namespace App\Http\Controllers;
use App\ChucNang;
use App\Commands\UpdateCustomerTypeGameABCMAXPOINTV2;
use App\Helpers\GameHelpers;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\XoSo;
use App\Location;
use App\Role;
use App\User;
use App\Helpers\UserHelpers;
use Session;
use \Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class ControlExController extends Controller
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

        if (!$chucnangClass->handleUserSecond(23) )
            return "Cannot access this page! Failed!!!";

        $user = Auth::user();
        if($user->roleid==1)
        {
            return view('admin.control_max.controlmax',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id),
                'user'=>$user,
                'type'=>'A','locationId'=>1]
            );
        }
        else
        {
            return view('admin.control_max.controlmax',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,1),
            'games_parent'=>GameHelpers::GetAllGameByCusType('A',$user->user_create,1),
                'user'=>$user,
                'type'=>'A','locationId'=>1]
            );
        }
    }

    public function getSuperChangeMaxex($id)
    {
        $chucnangClass = new ChucNang();

        // if (!$chucnangClass->handleUserSecond(23) )
        //     return "Cannot access this page! Failed!!!";

        $userid = $id;
        $user = User::where('id',$userid)->first();
        if($user->roleid==1)
        {
            return view('admin.control_ex.controlex_member',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,1),
                'user'=>$user,
                'type'=>'A','locationId'=>1]
            );
        }
        else
        {
            return view('admin.control_ex.controlex_member',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,1),
            'games_parent'=>GameHelpers::GetAllGameByCusType('A',$user->user_create,0),
                'user'=>$user,
                'type'=>'A','locationId'=>1]
            );
        }
    }

    public function getXsAo(Request $request)
    {
        $chucnangClass = new ChucNang();
        $user = Auth::user();
        if($user->roleid==1)
        {
            return view('admin.control_max.controlmax',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,4),
                'user'=>$user,
                'type'=>'A',
                'locationId'=>4]
            );
        }
        else
        {
            return view('admin.control_max.controlmax',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,4),
            'games_parent'=>GameHelpers::GetAllGameByCusType('A',$user->user_create,4),
                'user'=>$user,
                'type'=>'A','locationId'=>4]
            );
        }
    }

    public function getMienBac(Request $request)
    {
        $chucnangClass = new ChucNang();
        $user = Auth::user();
        if($user->roleid==1)
        {
            return view('admin.control_max.controlmax',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,1),
                'user'=>$user,
                'type'=>'A','locationId'=>1]
            );
        }
        else
        {
            return view('admin.control_max.controlmax',[
            'chucnangs'=>$chucnangClass->LoadMenuAndCheckLogin(),
            'games'=>GameHelpers::GetAllGameByCusType('A',$user->id,1),
            'games_parent'=>GameHelpers::GetAllGameByCusType('A',$user->user_create,1),
                'user'=>$user,
                'type'=>'A','locationId'=>1]
            );
        }
    }

    public function postStore(Request $request)
    {
        $user = Auth::user();
        if ($user->roleid == 6) return "false";
        for($i=0;$i<count($request->changes); $i++)
        {
            // GameHelpers::UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$user->id);
            Queue::pushOn("high",new UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$user->id,Auth::user()->id));
        }
        return $request;
    }

    public function postStoreByUser(Request $request)
    {
        // $user = Auth::user();
        $user = UserHelpers::GetUserById($request->userid);
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent)){
            for($i=0;$i<count($request->changes); $i++)
            {
                // GameHelpers::UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$request->userid);
                Queue::pushOn("high",new UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$request->userid,Auth::user()->id));
            }
            return $request;
        }else
            return "failed";
        return 'ok';
        // $userChild = UserHelpers::GetAllUserV2(Auth::user());
        // if (in_array($request->userid, $userChild))
        // {
        //     for($i=0;$i<count($request->changes); $i++)
        //     {
        //         GameHelpers::UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$request->userid);
        //     }
        //     return $request;
        // }else
        //     return "failed";
    }

    public function postStoreBySuperMaxex(Request $request)
    {
        // $user = Auth::user();
        $user = UserHelpers::GetUserById($request->userid);
        $userParent = UserHelpers::GetAllUserParentV2($user);
            
        if (in_array(Auth::user()->id, $userParent)){
            for($i=0;$i<count($request->changes); $i++)
            {
                GameHelpers::UpdateCustomerTypeGameABCMAXEXV2($request->changes[$i],$request->userid,Auth::user()->id);
                //Queue::pushOn("high",new UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$request->userid,Auth::user()->id));
            }
            return $request;
        }else
            return "failed";
        return 'ok';
        // $userChild = UserHelpers::GetAllUserV2(Auth::user());
        // if (in_array($request->userid, $userChild))
        // {
        //     for($i=0;$i<count($request->changes); $i++)
        //     {
        //         GameHelpers::UpdateCustomerTypeGameABCMAXPOINTV2($request->changes[$i],$request->userid);
        //     }
        // }else
        //     return "failed";
        // return 'ok';
    }
}