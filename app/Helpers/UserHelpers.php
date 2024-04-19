<?php

namespace App\Helpers;

use App\Commands\CheckUpdateExchangeRate;
use App\Commands\InitDataForNewUser;
use App\Commands\UpdateCustomerTypeByUserIdService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use App\History;
use App\Helpers\HistoryHelpers;
use \Cache;
use Illuminate\Support\Facades\Auth;
use \Queue;
use Illuminate\Support\Str;

class UserHelpers
{
    public  $customertype = array(
        'A' => array(
            'code' => 'A',
            'name' => 'Chuẩn A',
        ),
        'B' => array(
            'code' => 'B',
            'name' => 'Chuẩn B',
        ),
        'C' => array(
            'code' => 'C',
            'name' => 'Chuẩn C',
        ),
        'D' => array(
            'code' => 'D',
            'name' => 'Chuẩn D',
        ),
    );
    public function GetCustomertype()
    {
        return $this->customertype;
    }

    public static function GetAllUserChild($byUser, $active = 0)
    {
        // return Cache::tags('User'.$byUser->id)->remember('GetAllUserChild-'.$byUser->id.'-'.$active, env('CACHE_TIME', 0), function () use ($byUser,$active) {
            // ->select("id","name","fullname","roleid","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")
        if ($active != 2) {
            if ($byUser->name == 'admin')
                $users = User::orderBy('id', 'desc')->where('user_create', 274)->where('active', $active)->where('per', 0)->get();
            else
                $users = User::orderBy('id', 'desc')->where('user_create', $byUser->id)->where('active', $active)->where('per', 0)->get();
        } else {
            if ($byUser->name == 'admin')
                $users = User::orderBy('id', 'desc')->where('user_create', 274)->where('per', 0)->get();
            else
                $users = User::orderBy('id', 'desc')->where('user_create', $byUser->id)->where('per', 0)->get();
        }
        return $users;
        // });
    }

    public static function GetAllUserChild4API($byUser, $active = 0)
    {
        // return Cache::tags('User'.$byUser->id)->remember('GetAllUserChild-'.$byUser->id.'-'.$active, env('CACHE_TIME', 0), function () use ($byUser,$active) {
            // ->select("id","name","fullname","roleid","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")
        if ($active != 2) {
            if ($byUser->name == 'admin')
                $users = User::orderBy('id', 'desc')->where('user_create', 274)->where('active', $active)->where('per', 0)->join("role","users.roleid","=","role.id")->select("users.id","users.name","role.name as rolename","fullname","roleid","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")->get();
            else
                $users = User::orderBy('id', 'desc')->where('user_create', $byUser->id)->where('active', $active)->where('per', 0)->join("role","users.roleid","=","role.id")->select("users.id","users.name","role.name as rolename","fullname","roleid","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")->get();
        } else {
            if ($byUser->name == 'admin')
                $users = User::orderBy('id', 'desc')->where('user_create', 274)->where('per', 0)->join("role","users.roleid","=","role.id")->select("users.id","users.name","role.name as rolename","fullname","roleid","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")->get();
            else
                $users = User::orderBy('id', 'desc')->where('user_create', $byUser->id)->where('per', 0)->join("role","users.roleid","=","role.id")->select("users.id","users.name","role.name as rolename","fullname","roleid","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")->get();
        }
        return $users;
        // });
    }

    public static function GetAllUserChildv2Admin($name, $id, $active = 0)
    {
        // echo 'GetAllUserChildv2Admin';
        // return Cache::tags('User'.$byUser->id)->remember('GetAllUserChild-'.$byUser->id.'-'.$active, env('CACHE_TIME', 0), function () use ($byUser,$active) {
        if ($active != 2) {
            if ($name == 'admin')
                $users = User::orderBy('id', 'desc')->where('user_create', 274)->where('active', $active)->where('per', 0)->get();
            else
                $users = User::orderBy('id', 'desc')->where('user_create', $id)->where('active', $active)->where('per', 0)->get();
        } else {
            if ($name == 'admin')
                $users = User::orderBy('id', 'desc')->where('user_create', 274)->where('active', 0)->where('per', 0)->get();
            else
                $users = User::orderBy('id', 'desc')->where('user_create', $id)->where('active', 0)->where('per', 0)->get();
        }
        return $users;
        // });
    }

    public static function GetAllUser1()
    {
        $users = User::orderBy('id', 'desc')->get();
        return $users;
    }

    public static function GetAllUserSecondChild($byUser)
    {
        $users = User::orderBy('id', 'desc')->where('usfollow', $byUser->name)->where('active', 0)->get();
        return $users;
    }

    public static function GetRole2Name($role2)
    {
        if (isset($role2)) {
            switch ($role2) {
                case 1:
                    return "Full control";
                    break;
                case 2:
                    return "Tài khoản";
                    break;
                case 3:
                    return "Bảng biểu";
                    break;
                default:
                    # code...
                    break;
            }
        }
        return "";
    }

    public static function GetAllUserV2($user)
    {
        $child = static::GetAllUservID($user);
        // $arrUser = [];
        // 			foreach($child as $item){
        // 				array_push($arrUser,$item->id);
        // 			}
        return $child;

        $arrUser = [];
        $users = User::where('user_create', $user->id)->get(); //sp agent where('active',0)->
        foreach ($users as $userItem) {
            array_push($arrUser, $userItem->id);
            $child = UserHelpers::GetAllUserV2($userItem);
            $arrUser = array_merge($arrUser, $child);
        }
        return $arrUser;
    }

    public static function GetAllUserV3($user)
    {
        $child = static::GetAllUservID3($user);
        // $arrUser = [];
        // 			foreach($child as $item){
        // 				array_push($arrUser,$item->id);
        // 			}
        return $child;

        $arrUser = [];
        $users = User::where('user_create', $user->id)->get(); //sp agent where('active',0)->
        foreach ($users as $userItem) {
            array_push($arrUser, $userItem->id);
            $child = UserHelpers::GetAllUserV2($userItem);
            $arrUser = array_merge($arrUser, $child);
        }
        return $arrUser;
    }

    public static function GetAllUserParentV2($user)
    {
        $arrUser = [];
        $users = User::where('id', $user->user_create)->get(); //sp agent where('active',0)->
        foreach ($users as $userItem) {
            array_push($arrUser, $userItem->id);
            $child = UserHelpers::GetAllUserParentV2($userItem);
            $arrUser = array_merge($arrUser, $child);
        }
        return $arrUser;
    }

    public static function CheckPermission($parentid, $childid)
    {

        $user = User::where('id', $childid)->first(); //sp agent where('active',0)->

        if (!isset($user)) return false;
        echo $user->user_create . " ";
        if ($user->user_create == $parentid)
            return true;
        else
            return UserHelpers::CheckPermission($parentid, $user->user_create);

        return false;
    }

    public static function GetAllUser($byUser)
    {
        // $users = null;
        $users = User::orderBy('id', 'desc')->where('active', 0)->where('user_create', $byUser->id)->where('per', 0)->get(); //sp master
        foreach ($users as $user) {
            # code...
            $temp_user = User::orderBy('id', 'desc')->where('active', 0)->where('user_create', $user->id)->where('per', 0)->get(); //agent
            if (isset($temp_user)) {
                foreach ($temp_user as $key) {
                    # code...
                    $users->push($key);
                    $temp_user2 = User::orderBy('id', 'desc')->where('active', 0)->where('user_create', $key->id)->where('per', 0)->get(); //tong
                    if (isset($temp_user2)) {
                        foreach ($temp_user2 as $key2) {
                            $users->push($key2);
                            if ($key2->roleid == 6) continue;
                            $temp_user3 = User::orderBy('id', 'desc')->where('active', 0)->where('user_create', $key2->id)->where('per', 0)->get(); //mem
                            if (isset($temp_user3)) {
                                foreach ($temp_user3 as $key3) {
                                    $users->push($key3);
                                }
                            }
                        }
                    }
                }
            }
            // array_push($users,$temp_user);
            // $users->append($temp_user);
        }
        return $users;
    }

    public static function GetAllUservID($byUser)
    {
        // $users = null;
        $users = User::where('active', 0)->where('user_create', $byUser->id)->where('per', 0)->get(); //sp master
        $dataReturn = [];
        foreach ($users as $user) {
            # code...
            array_push($dataReturn, $user->id);
            $temp_user = User::where('active', 0)->where('user_create', $user->id)->where('per', 0)->get(); //agent
            if (isset($temp_user)) {
                foreach ($temp_user as $key) {
                    # code...
                    array_push($dataReturn, $key->id);
                    // $users->push($key);
                    $temp_user2 = User::where('active', 0)->where('user_create', $key->id)->where('per', 0)->get(); //tong
                    if (isset($temp_user2)) {
                        foreach ($temp_user2 as $key2) {
                            array_push($dataReturn, $key2->id);
                            // $users->push($key2);
                            if ($key2->roleid == 6) continue;
                            $temp_user3 = User::where('active', 0)->where('user_create', $key2->id)->where('per', 0)->get(); //mem
                            if (isset($temp_user3)) {
                                foreach ($temp_user3 as $key3) {
                                    // $users->push($key3);
                                    array_push($dataReturn, $key3->id);
                                }
                            }
                        }
                    }
                }
            }
            // array_push($users,$temp_user);
            // $users->append($temp_user);
        }
        return $dataReturn;
    }

    public static function GetAllUservIDByID($byUserId)
    {
        // $users = null;
        $users = User::where('active', 0)->where('user_create', $byUserId)->where('per', 0)->get(); //sp master
        $dataReturn = [];
        foreach ($users as $user) {
            # code...
            array_push($dataReturn, $user->id);
            $temp_user = User::where('active', 0)->where('user_create', $user->id)->where('per', 0)->get(); //agent
            if (isset($temp_user)) {
                foreach ($temp_user as $key) {
                    # code...
                    array_push($dataReturn, $key->id);
                    // $users->push($key);
                    $temp_user2 = User::where('active', 0)->where('user_create', $key->id)->where('per', 0)->get(); //tong
                    if (isset($temp_user2)) {
                        foreach ($temp_user2 as $key2) {
                            array_push($dataReturn, $key2->id);
                            // $users->push($key2);
                            if ($key2->roleid == 6) continue;
                            $temp_user3 = User::where('active', 0)->where('user_create', $key2->id)->where('per', 0)->get(); //mem
                            if (isset($temp_user3)) {
                                foreach ($temp_user3 as $key3) {
                                    // $users->push($key3);
                                    array_push($dataReturn, $key3->id);
                                }
                            }
                        }
                    }
                }
            }
            // array_push($users,$temp_user);
            // $users->append($temp_user);
        }
        return $dataReturn;
    }

    public static function GetAllUservID3($byUser)
    {
        // $users = null;
        //where('active', 0)->
        $users = User::where('user_create', $byUser->id)->where('per', 0)->get(); //sp master
        $dataReturn = [];
        $dataReturnName = [];
        foreach ($users as $user) {
            # code...
            if ($user->roleid == 6) {
                array_push($dataReturn, $user->id);
                array_push($dataReturnName, $user->name);
            }
            $temp_user = User::where('user_create', $user->id)->where('per', 0)->get(); //agent
            if (isset($temp_user)) {
                foreach ($temp_user as $key) {
                    # code...
                    if ($key->roleid == 6) {
                        array_push($dataReturn, $key->id);
                        array_push($dataReturnName, $key->name);
                    }
                    // $users->push($key);
                    $temp_user2 = User::where('user_create', $key->id)->where('per', 0)->get(); //tong
                    if (isset($temp_user2)) {
                        foreach ($temp_user2 as $key2) {
                            if ($key2->roleid == 6) {
                                array_push($dataReturn, $key2->id);
                                array_push($dataReturnName, $key2->name);
                            }
                            // $users->push($key2);
                            if ($key2->roleid == 6) continue;
                            $temp_user3 = User::where('user_create', $key2->id)->where('per', 0)->get(); //mem
                            if (isset($temp_user3)) {
                                foreach ($temp_user3 as $key3) {
                                    // $users->push($key3);
                                    if ($key3->roleid == 6) {
                                        array_push($dataReturn, $key3->id);
                                        array_push($dataReturnName, $key3->name);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // array_push($users,$temp_user);
            // $users->append($temp_user);
        }
        return [$dataReturn, $dataReturnName];
    }

    public static function GetAllUserAvailable($byUser)
    {
        $users = null;
        $users = User::orderBy('id', 'desc')->where('user_create', $byUser->id)->where('active', 0)->get();
        return $users;
    }

    public static function GetAllUserNonAdmin()
    {
        $users = null;
        $users = User::where('roleid', '!=', 1)->get();
        foreach ($users as $user) {
            # code...
            $temp_user = User::orderBy('id', 'desc')->where('user_create', $user->id)->get();
            if (isset($temp_user)) {
                foreach ($temp_user as $key) {
                    # code...
                    $users->push($key);
                    $temp_user2 = User::orderBy('id', 'desc')->where('user_create', $key->id)->get();
                    if (isset($temp_user2)) {
                        foreach ($temp_user2 as $key2) {
                            $users->push($key2);
                        }
                    }
                }
            }
            // array_push($users,$temp_user);
            // $users->append($temp_user);
        }
        return $users;
    }

    public static function GetAllUserKhach()
    {
        $users = null;
        $users = User::where('roleid', '==', 6)->get();
        foreach ($users as $user) {
            # code...
            $temp_user = User::orderBy('id', 'desc')->where('user_create', $user->id)->get();
            if (isset($temp_user)) {
                foreach ($temp_user as $key) {
                    # code...
                    $users->push($key);
                    $temp_user2 = User::orderBy('id', 'desc')->where('user_create', $key->id)->get();
                    if (isset($temp_user2)) {
                        foreach ($temp_user2 as $key2) {
                            $users->push($key2);
                        }
                    }
                }
            }
            // array_push($users,$temp_user);
            // $users->append($temp_user);
        }
        return $users;
    }

    public static function GetUserByUserName($username)
    {
        $user = User::where('name', $username)->get();
        return $user;
    }

    public static function GetUserByFullName($fullname)
    {
        $user = User::where('fullname', $fullname)->first();
        return $user;
    }

    public static function GetUserByFullNameRoleIds($fullname,$roleIds)
    {
        $user = User::where('fullname', $fullname)->whereIn('roleid',$roleIds)->first();
        return $user;
    }

    public static function GetUserByUserEmail($email)
    {
        $user = User::where('email', $email)->get();
        return $user;
    }

    public static function GetUserById($id)
    {
        $user = User::where('id', $id)->first();
        return $user;
    }

    public static function GetUserById4API($id)
    {
        $user = User::where('id', $id)->select("id","name","fullname","roleid","customer_type","credit","remain","rollback_money","updated_at","latestlogin","thau","lock","lock_price","lock_tele")->first();
        return $user;
    }

    public static function GetUserByToken($token)
    {
        $user = User::where('auth_token', $token)->first();
        return $user;
    }

    public static function buildBreadCrumbsUser($user, $cate = 0)
    {
        $listBreadCrumb = [];
        $breadCrumb = [
            'name' => $user->name . '(' . XoSoRecordHelpers::GetRoleShortName($user->roleid)  . ')',
            'url' => $cate == 0 ? '/users/user-child/' . $user->id : $user->id,
        ];
        array_push($listBreadCrumb, $breadCrumb);
        $userP = User::where('id', $user->user_create)->first();
        if ($user->id == Auth::user()->id) return $listBreadCrumb;
        if (isset($userP)) {
            $listBreadCrumb = array_merge($listBreadCrumb, static::buildBreadCrumbsUser($userP, $cate));
        }

        return $listBreadCrumb;
    }

    public static function GetAllUserV2ByKey($userid, $search)
    {
        $arrUser = [];

        // ->join('customer_type_game', 'customer_type_game.game_id', '=', 'games.game_code')
        // ->join('users', 'users.customer_type', '=', 'customer_type_game.code_type')
        // ->where('users.id',$userid)
        // ->select('games.*','customer_type_game.exchange_rates as exchange_rates')
        // ->get();
        $search = strtolower($search);
        $users = [];
        if (Auth::user()->roleid == 1) {
            $users = User::whereRaw('active = 0 and (users.name like "%' . $search . '%" or role.name like "%' . $search . '%")')
                // where('active',0)->where('user_create',$userid)
                ->where('per', 0)
                ->where('roleid', '<>', 1)
                ->join('role', 'users.roleid', '=', 'role.id')
                ->select('users.*', 'role.name as roleName')
                ->get(); //sp agent
            // foreach ($users as $userItem) {
            // if (str_contains(strtolower($userItem->name),$search) || str_contains(strtolower($userItem->roleName),$search))
            // array_push($arrUser,$userItem);
            // $child = UserHelpers::GetAllUserV2ByKey($userItem->id,$search);
            // $arrUser = array_merge($arrUser,$child);
            // }
            $arrUser = $users;
        } else {
            $users = User::where('active', 0)->where('user_create', $userid)
                ->join('role', 'users.roleid', '=', 'role.id')
                ->select('users.*', 'role.name as roleName')
                ->get(); //sp agent
            foreach ($users as $userItem) {
                if (str_contains(strtolower($userItem->name), $search) || str_contains(strtolower($userItem->roleName), $search))
                    array_push($arrUser, $userItem);
                $child = UserHelpers::GetAllUserV2ByKey($userItem->id, $search);
                $arrUser = array_merge($arrUser, $child);
            }
        }

        return $arrUser;
    }

    public static function GetAllUserV2ByKey4Report($userid, $search)
    {
        $arrUser = [];

        // ->join('customer_type_game', 'customer_type_game.game_id', '=', 'games.game_code')
        // ->join('users', 'users.customer_type', '=', 'customer_type_game.code_type')
        // ->where('users.id',$userid)
        // ->select('games.*','customer_type_game.exchange_rates as exchange_rates')
        // ->get();
        $search = strtolower($search);
        $users = [];
        if (Auth::user()->roleid == 1) {
            $users = User::whereRaw('active = 0 and (users.name like "%' . $search . '%" or role.name like "%' . $search . '%")')
                ->where('per', 0)
                ->where('roleid', '<>', 1)
                // where('active',0)->where('user_create',$userid)
                ->join('role', 'users.roleid', '=', 'role.id')
                ->select('users.*', 'role.name as roleName')
                ->get(); //sp agent
            // foreach ($users as $userItem) {
            // if (str_contains(strtolower($userItem->name),$search) || str_contains(strtolower($userItem->roleName),$search))
            // array_push($arrUser,$userItem);
            // $child = UserHelpers::GetAllUserV2ByKey($userItem->id,$search);
            // $arrUser = array_merge($arrUser,$child);
            // }
            $arrUser = $users;
        } else {
            $users = User::where('active', 0)->where('user_create', $userid)
                ->join('role', 'users.roleid', '=', 'role.id')
                ->select('users.*', 'role.name as roleName')
                ->get(); //sp agent
            foreach ($users as $userItem) {
                if (str_contains(strtolower($userItem->name), $search) || str_contains(strtolower($userItem->roleName), $search))
                    array_push($arrUser, $userItem);
                $child = UserHelpers::GetAllUserV2ByKey($userItem->id, $search);
                $arrUser = array_merge($arrUser, $child);
            }
        }

        return $arrUser;
    }

    public static function GetUserByIdKey($id, $search)
    {
        // $user = User::where('id', $id)->first();
        return UserHelpers::GetAllUserV2ByKey($id, $search);
    }

    public static function GetUserByRole($roleid)
    {
        $user = User::where('roleid', $roleid)
            ->where('active', 0)
            ->whereRaw('credit != remain')
            ->get();
        return $user;
    }
    public static function GetUserUsing()
    {
        $user = User::where('active', 0)->where('roleid', 6)->get();
        return $user;
    }

    public static function LockNumberChild($parent, $statusLockC)
    {
        $childs = User::where('user_create', $parent->id)->get();
        foreach ($childs as $child) {
            if ($child->lock < $statusLockC) {
                $child->lock = $statusLockC;
                $child->save();
            }
            UserHelpers::LockNumberChild($child, $statusLockC);
        }
    }

    public static function LockPriceChild($parent, $statusLockC)
    {
        $childs = User::where('user_create', $parent->id)->get();
        foreach ($childs as $child) {
            if ($child->lock_price != $statusLockC) {
                $child->lock_price = $statusLockC;
                $child->save();
            }
            UserHelpers::LockPriceChild($child, $statusLockC);
        }
    }

    public static function UpdateUser($request, $id, $user_create)
    {
        // $request->type = isset($request->type) ? $request->type : "";
        if (isset($request->type) && $request->type == "api"){
            $actionType = "";
            $user_target = User::where('id', $id)->first();
            $actionCheck = false;
            if (isset($request->customer_type) &&  $request->customer_type != $user_target->customer_type && $user_target->roleid == 6) {
                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "chuẩn";
                DB::table('users')
                    ->where('id', $id)
                    ->update(
                        [
                            'customer_type' => $request->customer_type,
                        ]
                    );

                Queue::pushOn("high",new UpdateCustomerTypeByUserIdService($request->customer_type,$user_target));
                $user_createGet = User::where('name', '=', $user_create)->first();
                HistoryHelpers::ActiveHistorySave($user_createGet, $user_target, $actionType, "");
            }

            if ($request->lock != $user_target->lock) {
                $lockName = "";
                switch ($request->lock) {
                    case 0:
                        $lockName = "Mở";
                        break;

                    case 1:
                        $lockName = "Ngừng đặt";
                        break;

                    case 2:
                        $lockName = "Đóng";
                        break;

                    case 3:
                        $lockName = "Đóng/Ngừng đặt";
                        break;

                    default:
                        break;
                }
                $actionType .= ($actionType != "" ? ", " : "") . $lockName . " tài khoản";
                DB::table('users')
                ->where('id', $id)
                ->update(
                    [
                        'lock' => $request->lock,
                    ]
                );
                UserHelpers::LockNumberChild($user_target, $request->lock);
                $user_createGet = User::where('name', '=', $user_create)->first();
                HistoryHelpers::ActiveHistorySave($user_createGet, $user_target, $actionType, "");
            }

            $money = $request->credit;
            $usercreate = User::where('id', '=', $user_target->user_create)->first();

            if ($request->credit != $user_target->credit){
                $tienchenh = $money - $user_target->credit;
                if ($usercreate->remain - $tienchenh < 0 || ($user_target->remain + $tienchenh < 0)) {
                    return "false";
                }
                $user_target->credit = $money;
                $user_target->remain += $tienchenh;
                $user_target->save();
                {
                    $user_work = $usercreate;
                    if ($user_work->role != 1) {
                        $user_work->remain -= $tienchenh;
                        $user_work->save();
                    }
                }
                HistoryHelpers::ActiveHistorySave($user_work, $user_target, "thay đổi tín dụng(f1)", $tienchenh);
            }
            if ($request->lock_price != $user_target->lock_price){
                UserHelpers::LockPriceChild($user_target, $request->lock_price);
            }
            return true;
        }
        if ($request->type == "customer_type") {
            $actionType = "";
            $user_target = User::where('id', $id)->first();
            $actionCheck = false;

            if (isset($request->customer_type) &&  $request->customer_type != $user_target->customer_type && $user_target->roleid == 6) {
                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "chuẩn";
                $actionCheck = true;
            }

            if ($actionCheck) {
                DB::table('users')
                    ->where('id', $id)
                    ->update(
                        [
                            // 'fullname' => $request->fullname,
                            // 'roleid'=>$request->role,
                            'customer_type' => $request->customer_type,
                            // 'credit' => str_replace(',', '', $request->credit),
                            // 'consumer' => str_replace(',', '', $request->consumer),
                            // 'remain' => str_replace(',', '', $request->remain),
                            // 'thau' => $request->thau,
                            // 'lock' => $request->lock,
                        ]
                    );

                // UserHelpers::LockNumberChild($user_target, $request->lock);
                $user_createGet = User::where('name', '=', $user_create)->first();
                HistoryHelpers::ActiveHistorySave($user_createGet, $user_target, $actionType, "");
            }

            return;
        }
        if ($request->type == "") {
            $actionType = "";
            $user_target = User::where('id', $id)->first();
            $actionCheck = false;

            if (isset($request->fullname) && $request->fullname != $user_target->fullname) {

                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "tên đầy đủ";
                $actionCheck = true;
            }

            if (isset($request->customer_type) &&  $request->customer_type != $user_target->customer_type && $user_target->roleid == 6) {
                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "chuẩn";
                $actionCheck = true;
            }

            if (isset($request->credit) && $request->credit != $user_target->credit) {
                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "tín dụng";
                $actionCheck = true;
            }

            if (isset($request->remain) && $request->remain != $user_target->remain) {
                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "tín dụng còn lại";
                $actionCheck = true;
            }

            if (isset($request->thau) && $request->thau != $user_target->thau) {
                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . "thầu";
                $actionCheck = true;
            }

            if (isset($request->lock) && $request->lock != $user_target->lock) {
                $lockName = "";
                switch ($request->lock) {
                    case 0:
                        $lockName = "Mở";
                        break;

                    case 1:
                        $lockName = "Ngừng đặt";
                        break;

                    case 2:
                        $lockName = "Đóng";
                        break;

                    case 3:
                        $lockName = "Đóng/Ngừng đặt";
                        break;

                    default:
                        break;
                }

                $actionType .= ($actionType != "" ? ", " : "thay đổi ") . $lockName . " tài khoản";
                $actionCheck = true;
            }

            if ($actionCheck == true) {
                $actionType = "thay đổi thông tin tài khoản";
            }

            DB::table('users')
                ->where('id', $id)
                ->update(
                    [
                        'fullname' => $request->fullname,
                        // 'roleid'=>$request->role,
                        'customer_type' => $request->customer_type,
                        'credit' => str_replace(',', '', $request->credit),
                        'consumer' => isset($request->consumer) ? str_replace(',', '', $request->consumer) : 0,
                        'remain' => str_replace(',', '', $request->remain),
                        'thau' => $request->thau,
                        'lock' => $request->lock,
                        'lock_price' => $request->lock_price,
                    ]
                );

            UserHelpers::LockNumberChild($user_target, $request->lock);
            UserHelpers::LockPriceChild($user_target, $request->lock_price);
            $user_createGet = User::where('name', '=', $user_create)->first();
            HistoryHelpers::ActiveHistorySave($user_createGet, $user_target, $actionType, "");
            // Cache::tags('User'.$id)->flush();
        } else if ($request->type == "lock") {
            $actionType = "";
            $user_target = User::where('id', $id)->first();
            $actionCheck = false;

            if ($request->lock != $user_target->lock) {

                $lockName = "";
                switch ($request->lock) {
                    case 0:
                        $lockName = "Mở";
                        break;

                    case 1:
                        $lockName = "Ngừng đặt";
                        break;

                    case 2:
                        $lockName = "Đóng";
                        break;

                    case 3:
                        $lockName = "Đóng/Ngừng đặt";
                        break;

                    default:
                        break;
                }

                $actionType .= ($actionType != "" ? ", " : "") . $lockName . " tài khoản";
                $actionCheck = true;
            }

            DB::table('users')
                ->where('id', $id)
                ->update(
                    [
                        // 'fullname' => $request->fullname,
                        // 'roleid'=>$request->role,
                        // 'customer_type'=>$request->customer_type,
                        // 'credit' => str_replace(',', '',$request->credit),
                        // 'consumer' => str_replace(',', '',$request->consumer),
                        // 'remain' => str_replace(',', '',$request->remain),
                        // 'thau' => $request->thau,
                        'lock' => $request->lock,
                    ]
                );

            UserHelpers::LockNumberChild($user_target, $request->lock);
            $user_createGet = User::where('name', '=', $user_create)->first();
            HistoryHelpers::ActiveHistorySave($user_createGet, $user_target, $actionType, "");
            // Cache::tags('User'.$id)->flush();
        } else if ($request->type == "lock2") {
            $actionType = "";
            $user_target = User::where('id', $id)->first();
            $actionCheck = false;

            if ($request->lock2 != $user_target->lock2) {

                $lockName = "";
                switch ($request->lock) {
                    case 1:
                        $lockName = "Full control";
                        break;

                    case 2:
                        $lockName = "Tài khoản";
                        break;

                    case 3:
                        $lockName = "Bảng biểu";
                        break;

                    default:
                        break;
                }

                $actionType .= ($actionType != "" ? ", " : "") . $lockName . " tài khoản phụ";
                $actionCheck = true;
            }

            DB::table('users')
                ->where('id', $id)
                ->update(
                    [
                        // 'fullname' => $request->fullname,
                        // 'roleid'=>$request->role,
                        // 'customer_type'=>$request->customer_type,
                        // 'credit' => str_replace(',', '',$request->credit),
                        // 'consumer' => str_replace(',', '',$request->consumer),
                        // 'remain' => str_replace(',', '',$request->remain),
                        // 'thau' => $request->thau,
                        'role2' => $request->lock2,
                    ]
                );

            UserHelpers::LockNumberChild($user_target, $request->lock);
            $user_createGet = User::where('name', '=', $user_create)->first();
            HistoryHelpers::ActiveHistorySave($user_createGet, $user_target, $actionType, "");
            // Cache::tags('User'.$id)->flush();
        } else {
            $money = $request->credit;
            if ($request->type == "get") {
                $user = User::where('id', '=', $id)->first();
                $user->consumer += $money;
                $user->remain -= $money;
                $user->save();
                $user_work = User::where('name', '=', $user_create)->first();

                // HistoryHelpers::InsertHistory('Rút tiền','Rút số tiền '.$money." cho người dùng ".$user->fullname,$user_work->id,$money);

                HistoryHelpers::ActiveHistorySave($user_work, $user, "rút tín dụng", $money);
            }
            if ($request->type == "put") {
                $user = User::where('id', '=', $id)->first();
                $user->credit += $money;
                $user->remain += $money;
                $user->save();
                $user_work = User::where('name', '=', $user_create)->first();
                if ($user_work->role != 1) {
                    $user_work->consumer += $money;
                    $user_work->remain -= $money;
                    $user_work->save();
                    // Cache::tags('User'.$user_work->id)->flush();
                }

                // HistoryHelpers::InsertHistory('Nạp tiền','Nạp số tiền '.$money." cho người dùng ".$user->fullname,$user_work->id,$money);

                HistoryHelpers::ActiveHistorySave($user_work, $user, "nạp tín dụng", $money);
            }

            if ($request->type == "credit") {
                $usertaget = User::where('id', '=', $id)->first();
                $usercreate = User::where('id', '=', $usertaget->user_create)->first();

                $tienchenh = $money - $usertaget->credit;

                if ($usercreate->remain - $tienchenh < 0 || ($usertaget->remain + $tienchenh < 0)) {
                    return "false";
                }

                $usertaget->credit = $money;
                // if ($usertaget->roleid!=6)
                //     $usertaget->remain +=$tienchenh;
                // else{
                //     $usertaget->remain = $usertaget->credit;
                // }
                $usertaget->remain += $tienchenh;
                $usertaget->save();
                // $brigde=$usertaget->user_create;
                // while (true) 
                // {
                //     # code...
                //     $user_work = User::where('id', '=', $brigde)->first();
                //     if ($user_work==null)
                //         break;
                //     $brigde = $user_work->user_create;
                //     if($user_work->role!=1)
                //     {
                //         // $user_work->consumer += $tienchenh;
                //         $user_work->credit += $tienchenh;
                //         if ($user_work->name == $user_create)
                //             $user_work->remain -= $tienchenh;
                //         $user_work->save();
                //         // Cache::tags('User'.$user_work->id)->flush();
                //     }else{
                //         break;
                //     }
                //     break;
                // }

                //chinh tin dun 05072022
                // while (true) 
                {
                    # code...
                    $user_work = $usercreate; //User::where('id', '=', $brigde)->first();
                    // if ($user_work==null)
                    // break;
                    // $brigde = $user_work->user_create;
                    if ($user_work->role != 1) {
                        // $user_work->consumer += $tienchenh;
                        // $user_work->credit += $tienchenh;
                        // if ($user_work->name == $user_create)
                        $user_work->remain -= $tienchenh;
                        $user_work->save();
                        // Cache::tags('User'.$user_work->id)->flush();
                    }
                    // else{
                    //     break;
                    // }
                    // break;
                }

                HistoryHelpers::ActiveHistorySave($user_work, $usertaget, "thay đổi tín dụng", $tienchenh);
            }
        }
        return "true";
    }
    public static function DeleteUser($id)
    {
        $userDel = User::where('id', $id)->first();
        // ->update(
        //     [
        //         //1 la disable
        //         'active' => 1,
        //     ]);
        $userDel->active = 1;
        $userDel->save();
        // ->update(
        //     [
        //         //1 la disable
        //         'active' => 1,
        //         'remain' =>
        //     ]);

        if ($userDel->per != 1) {
            $userParent = User::where('id', $userDel->user_create)->first();
            $userParent->remain += $userDel->credit;
            $userParent->save();

            HistoryHelpers::ActiveHistorySave($userParent, $userDel, "xóa tài khoản", "");

            foreach (UserHelpers::GetAllUser(UserHelpers::GetUserById($id)) as $user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(
                        [
                            //1 la disable
                            'active' => 1,
                            'remain' => 0,
                            'credit' => 0,
                        ]
                    );
                // Cache::tags('User'.$user->id)->flush();
            }
            // Cache::tags('User'.$id)->flush();
            // Cache::tags('User'.User::where('id', '=', $id)->first()->user_create)->flush();
        } else {
            $userParent = User::where('name', $userDel->usfollow)->first();
            HistoryHelpers::ActiveHistorySave($userParent, $userDel, "xóa tài khoản phụ", "");
        }
    }

    public static function ResetOTP($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(
                [
                    'google2fa_secret' => '',
                ]
            );
    }

    public static function LockUser($id)
    {
        $current_user = User::where('id', '=', $id)->first();
        if ($current_user->lock != 0)
            $lockuser = 0;
        else
            $lockuser = 2;

        DB::table('users')
            ->where('id', $id)
            ->update(
                [
                    'lock' => $lockuser,
                ]
            );
        UserHelpers::LockNumberChild($current_user, $lockuser);
        // Cache::tags('User'.$id)->flush();
        // Cache::tags('User'.$current_user->user_create)->flush();
    }

    public static function InsertUserSecond($user, $id)
    {
        $current_user = User::where('id', '=', $id)->first();

        DB::table('users')->insert([
            [
                'name' => $user->username,
                'email'    => '',
                'password' => \Hash::make($user->password),
                'credit' => '0',
                'consumer' => '0',
                'remain' => '0',
                'fullname' => $user->fullname,
                'lock' => $user->lock,
                'roleid' => $current_user->roleid,
                'role2' => $user->role2,
                'customer_type' => $user->customer_type,
                // 'user_create'=>$id,
                'usfollow' => $current_user->name,
                'per' => 1
            ]
        ]);
        return 1;
    }

    public static function InsertUser($user, $id)
    {
        if ($user->role != 6){
            // $user->password = Str::random(24);
            $user->password = $user->username;
        }
        $current_user = User::where('id', '=', $id)->first();

        $money = str_replace(',', '', $user->credit);
        //bcadd(str_replace(',', '', $user->credit), '0', 2);
        if ($current_user->remain < $money || $current_user->remain - $money < 0)
            return "Hết tín dụng.";
        $current_user->consumer += $money;

        $current_user->remain -= $money;
        $current_user->save();

        // $customer_users = CustomerType_Game::where('created_user',$id)->where('code_type',$user->customer_type)->get();
        // return $user;
        $lockPrice = $current_user->lock_price;
        if ($current_user->roleid == 1 && isset($user->lock_price)){
            $lockPrice = $user->lock_price;
        }
        if ($user->copy_data == 'non') {
            DB::table('users')->insert([
                [
                    'name' => $user->username,
                    'email'    => '',
                    'password' => \Hash::make($user->password),
                    'credit' => str_replace(',', '', $user->credit),
                    'consumer' => '0',
                    'remain' => str_replace(',', '', $user->credit),
                    'fullname' => isset($user->fullname) ? $user->fullname : "",
                    'lock' => $user->lock,
                    'lock_price' => $lockPrice,
                    'rollback_money' => $user->rollback_money,
                    'roleid' => $user->role,
                    'customer_type' => $user->customer_type,
                    'user_create' => $id,
                    'bet' => isset($user->bet) ? $user->bet : '',
                    'thau' => $user->thau,

                ]
            ]);

            // Queue::pushOn("high",new InitDataForNewUser($user,$id,'CustomerType_Game'));
            // Queue::pushOn("high",new InitDataForNewUser($user,$id,'CustomerType_Game_Original'));

            // if($user->role != 6)
            // {
            //    $customer_users = CustomerType_Game::where('created_user',$id)->get();
            // //    $customer_users = CustomerType_Game_Original::where('created_user',$id)->get();
            // //    customer_original_users
            // }else{
            //    $customer_users = CustomerType_Game::where('created_user',$id)->where('code_type',$user->customer_type)->get();
            //    // $customer_original_users = null;
            // }

            // $new_user = User::where('name', '=', $user->username)->first();

            // foreach ($customer_users as $cus)
            // {
            //     $new_cus = new CustomerType_Game;
            //     $new_cus->code_type = $cus->code_type;
            //     $new_cus->game_id = $cus->game_id;
            //     $new_cus->exchange_rates = $cus->exchange_rates;
            //     $new_cus->odds = $cus->odds;
            //     $new_cus->created_user = $new_user->id;
            //     $new_cus->change_odds = $cus->change_odds;
            //     $new_cus->change_ex = $cus->change_ex;
            //     $new_cus->change_max_one = $cus->change_max_one;

            //     $new_cus->max_point = $cus->max_point;
            //     $new_cus->max_point_one = $cus->max_point_one;

            //     $new_cus->save();

            //     $new_cusOg = new CustomerType_Game_Original;
            //     $new_cusOg->code_type = $cus->code_type;
            //     $new_cusOg->game_id = $cus->game_id;
            //     $new_cusOg->exchange_rates = $cus->exchange_rates;
            //     $new_cusOg->odds = $cus->odds;
            //     $new_cusOg->created_user = $new_user->id;
            //     $new_cusOg->change_odds = $cus->change_odds;
            //     $new_cusOg->change_ex = $cus->change_ex;
            //     $new_cusOg->change_max_one = $cus->change_max_one;
            //     $new_cusOg->max_point = $cus->max_point;
            //     $new_cusOg->max_point_one = $cus->max_point_one;

            //     $new_cusOg->save();
            // }

        } else {
            // print_r($user->copy_data);
            $current_user_copy = User::where('id', '=', $user->copy_data)->first();

            DB::table('users')->insert([
                [
                    'name' => $user->username,
                    'email'    => '',
                    'password' => \Hash::make($user->password),
                    'credit' => str_replace(',', '', $user->credit),
                    'consumer' => '0',
                    'remain' => str_replace(',', '', $user->credit),
                    'fullname' => $user->fullname,
                    'lock' => $user->lock,
                    'lock_price' => $lockPrice,
                    'rollback_money' => $user->rollback_money,
                    'roleid' => $user->role,
                    'customer_type' => $current_user_copy->customer_type,
                    'user_create' => $id,
                    'bet' => isset($user->bet) ? $user->bet : '',
                    'thau' => $user->thau,
                ]
            ]);

            // if($user->role != 6)
            // {
            //    $customer_users = CustomerType_Game::where('created_user',$user->copy_data)->get();
            //    // $customer_original_users = CustomerType_Game_Original::where('created_user',$id)->get();
            // }else{
            //    $customer_users = CustomerType_Game::where('created_user',$user->copy_data)->where('code_type',$current_user_copy->customer_type)->get();
            //    // $customer_original_users = null;
            // }



            // foreach ($customer_users as $cus)
            // {
            //     $new_cus = new CustomerType_Game;
            //     $new_cus->code_type = $cus->code_type;
            //     $new_cus->game_id = $cus->game_id;
            //     $new_cus->exchange_rates = $cus->exchange_rates;
            //     $new_cus->odds = $cus->odds;
            //     $new_cus->created_user = $new_user->id;
            //     $new_cus->change_odds = $cus->change_odds;
            //     $new_cus->change_ex = $cus->change_ex;

            //     $new_cus->max_point = $cus->max_point;
            //     $new_cus->max_point_one = $cus->max_point_one;

            //     $new_cus->save();

            //     $new_cusOg = new CustomerType_Game_Original;
            //     $new_cusOg->code_type = $cus->code_type;
            //     $new_cusOg->game_id = $cus->game_id;
            //     $new_cusOg->exchange_rates = $cus->exchange_rates;
            //     $new_cusOg->odds = $cus->odds;
            //     $new_cusOg->created_user = $new_user->id;
            //     $new_cusOg->change_odds = $cus->change_odds;
            //     $new_cusOg->change_ex = $cus->change_ex;

            //     $new_cusOg->max_point = $cus->max_point;
            //     $new_cusOg->max_point_one = $cus->max_point_one;

            //     $new_cusOg->save();
            // }
        }


        // GameHelpers::UpdateMeFromParentEX($new_user,$new_user);
        // Cache::tags('User'.$new_user->id)->flush();
        // Cache::tags('User'.$current_user->id)->flush();
        $new_user = User::where('name', '=', $user->username)->first(); //($username,$userrole,$customer_type,$copy_data,$parent_id,$tableTarget)
        \Queue::pushOn("high", new InitDataForNewUser($user->username, $user->role, $user->customer_type, $user->copy_data, $id, 'CustomerType_Game'));
        \Queue::pushOn("high", new InitDataForNewUser($user->username, $user->role, $user->customer_type, $user->copy_data, $id, 'CustomerType_Game_Original'));
        // \Queue::pushOn("high",new CheckUpdateExchangeRate());
        return $new_user->id;
    }

    public static function InsertGuest($user, $id)
    {
        $current_user = User::where('id', '=', $id)->first();

        // $money = bcadd(str_replace(',', '',50000000),'0',2);
        // if ($current_user->remain < $money || $current_user->remain - $money < 0)
        //     return;
        // $current_user->consumer += $money;

        // $current_user->remain -= $money;
        // $current_user->save();

        // $customer_users = CustomerType_Game::where('created_user',$id)->where('code_type',$user->customer_type)->get();
        // \Log::info($user);
        // return $user->username . '1';
        // if ($user->copy_data == 'non')
        {

            DB::table('users')->insert([
                [
                    'name' => $user->username,
                    'email'    => '',
                    'password' => \Hash::make($user->password),
                    'credit' => 50000000,
                    'consumer' => '0',
                    'remain' => 50000000,
                    'fullname' => $user->fullname,
                    'lock' => 0,
                    'rollback_money' => 1,
                    'roleid' => 6,
                    'customer_type' => 'A',
                    'user_create' => $id,
                    'bet' => '',
                    'thau' => 0,

                ]
            ]);

            // Queue::pushOn("high",new InitDataForNewUser($user,$id,'CustomerType_Game'));
            // Queue::pushOn("high",new InitDataForNewUser($user,$id,'CustomerType_Game_Original'));

            // if($user->role != 6)
            // {
            //    $customer_users = CustomerType_Game::where('created_user',$id)->get();
            // //    $customer_users = CustomerType_Game_Original::where('created_user',$id)->get();
            // //    customer_original_users
            // }else{
            //    $customer_users = CustomerType_Game::where('created_user',$id)->where('code_type',$user->customer_type)->get();
            //    // $customer_original_users = null;
            // }

            // $new_user = User::where('name', '=', $user->username)->first();

            // foreach ($customer_users as $cus)
            // {
            //     $new_cus = new CustomerType_Game;
            //     $new_cus->code_type = $cus->code_type;
            //     $new_cus->game_id = $cus->game_id;
            //     $new_cus->exchange_rates = $cus->exchange_rates;
            //     $new_cus->odds = $cus->odds;
            //     $new_cus->created_user = $new_user->id;
            //     $new_cus->change_odds = $cus->change_odds;
            //     $new_cus->change_ex = $cus->change_ex;
            //     $new_cus->change_max_one = $cus->change_max_one;

            //     $new_cus->max_point = $cus->max_point;
            //     $new_cus->max_point_one = $cus->max_point_one;

            //     $new_cus->save();

            //     $new_cusOg = new CustomerType_Game_Original;
            //     $new_cusOg->code_type = $cus->code_type;
            //     $new_cusOg->game_id = $cus->game_id;
            //     $new_cusOg->exchange_rates = $cus->exchange_rates;
            //     $new_cusOg->odds = $cus->odds;
            //     $new_cusOg->created_user = $new_user->id;
            //     $new_cusOg->change_odds = $cus->change_odds;
            //     $new_cusOg->change_ex = $cus->change_ex;
            //     $new_cusOg->change_max_one = $cus->change_max_one;
            //     $new_cusOg->max_point = $cus->max_point;
            //     $new_cusOg->max_point_one = $cus->max_point_one;

            //     $new_cusOg->save();
            // }

        }
        // else
        {
            // // print_r($user->copy_data);
            // $current_user_copy = User::where('id', '=', $user->copy_data)->first();

            // DB::table('users')->insert([
            //     [
            //         'name' => $user->username,
            //         'email'    => '',
            //         'password' => \Hash::make($user->password),
            //         'credit' => str_replace(',', '',$user->credit),
            //         'consumer' => '0',
            //         'remain' => str_replace(',', '',$user->credit),
            //         'fullname'=> $user->fullname,
            //         'lock'=> $user->lock,
            //         'rollback_money' => $user->rollback_money,
            //         'roleid'=>$user->role,
            //         'customer_type'=>$current_user_copy->customer_type,
            //         'user_create'=>$id,
            //         'bet'=> isset($user->bet)?$user->bet:'',
            //         'thau'=> $user->thau_edit,
            //     ]
            // ]);

            // if($user->role != 6)
            // {
            //    $customer_users = CustomerType_Game::where('created_user',$user->copy_data)->get();
            //    // $customer_original_users = CustomerType_Game_Original::where('created_user',$id)->get();
            // }else{
            //    $customer_users = CustomerType_Game::where('created_user',$user->copy_data)->where('code_type',$current_user_copy->customer_type)->get();
            //    // $customer_original_users = null;
            // }



            // foreach ($customer_users as $cus)
            // {
            //     $new_cus = new CustomerType_Game;
            //     $new_cus->code_type = $cus->code_type;
            //     $new_cus->game_id = $cus->game_id;
            //     $new_cus->exchange_rates = $cus->exchange_rates;
            //     $new_cus->odds = $cus->odds;
            //     $new_cus->created_user = $new_user->id;
            //     $new_cus->change_odds = $cus->change_odds;
            //     $new_cus->change_ex = $cus->change_ex;

            //     $new_cus->max_point = $cus->max_point;
            //     $new_cus->max_point_one = $cus->max_point_one;

            //     $new_cus->save();

            //     $new_cusOg = new CustomerType_Game_Original;
            //     $new_cusOg->code_type = $cus->code_type;
            //     $new_cusOg->game_id = $cus->game_id;
            //     $new_cusOg->exchange_rates = $cus->exchange_rates;
            //     $new_cusOg->odds = $cus->odds;
            //     $new_cusOg->created_user = $new_user->id;
            //     $new_cusOg->change_odds = $cus->change_odds;
            //     $new_cusOg->change_ex = $cus->change_ex;

            //     $new_cusOg->max_point = $cus->max_point;
            //     $new_cusOg->max_point_one = $cus->max_point_one;

            //     $new_cusOg->save();
            // }
        }


        // GameHelpers::UpdateMeFromParentEX($new_user,$new_user);
        // Cache::tags('User'.$new_user->id)->flush();
        // Cache::tags('User'.$current_user->id)->flush();
        $new_user = User::where('name', '=', $user->username)->first(); //($username,$userrole,$customer_type,$copy_data,$parent_id,$tableTarget)
        \Queue::pushOn("high", new InitDataForNewUser($user->username, 6, 'A', 'non', $id, 'CustomerType_Game'));
        \Queue::pushOn("high", new InitDataForNewUser($user->username, 6, 'A', 'non', $id, 'CustomerType_Game_Original'));
        // \Queue::pushOn("high",new CheckUpdateExchangeRate());
        return $new_user->id;
    }

    /**
     * @return array
     */
    public static function changePass($name, $newpass)
    {
        DB::table('users')
            ->where('name', $name)
            ->update(
                [
                    'password' => \Hash::make($newpass),
                    'lastcpw' => date("Y-m-d H:i:s")
                ]
            );
    }
}
