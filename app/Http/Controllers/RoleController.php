<?php
namespace App\Http\Controllers;
use App\ChucNang;
use App\Location;
use App\Role;
use App\Helpers\RoleHelpers;
use App\Helpers\UserHelpers;
use App\User;
use App\Bet;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class RoleController extends Controller {

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function getIndex()
    {
        $chucnangModel = new ChucNang();
        return view
        (
            'admin.role.listrole',
            [
                'roles'=> RoleHelpers::getAllRole(),
                'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
            ]
        );
    }
    public function getLoadTreeFunction($id,$load)
    {
        $chucnangModel = new ChucNang();
        if($load=="load")
        {
            return view
            (
                'admin.role.treefunction',
                [
                    'function'=> RoleHelpers::getFunctionInRole($id),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
                ]
            );
        }
        else
        {
            return view
            (
                'admin.role.treefunctionedit',
                [
                    'function'=> RoleHelpers::getFunctionInRole($id),
                    'chucnangs'=>$chucnangModel->LoadMenuAndCheckLogin()
                ]
            );
        }
    }
    public function getReloadRole()
    {
        return view
        (
            'admin.role.roles',
            [
                'roles'=> RoleHelpers::getAllRole(),
            ]
        );
    }

    public function postUpdate(Request $request, $id)
    {
        RoleHelpers::UpdateRole($request,$id);
        return "true";
    }
    public function postStore(Request $request)
    {
        RoleHelpers::InsertRole($request);
        return "true";
    }
    public function postDestroy($id)
    {
        $users = UserHelpers::GetUserByRole($id);
        if(count($users)>0)
            return "false";
        RoleHelpers::DeleteRole($id);
        return "true";
    }
}
