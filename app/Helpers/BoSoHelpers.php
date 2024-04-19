<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Bangso;
use \Cache;
class BoSoHelpers
{
    
    public function GetCustomertype(){
        return $this->customertype;
    }

    public static function GetAllBangSo($type=0){
        $bangso = Bangso::orderBy('id', 'DESC')->where('type',$type)->where('isdelete',0)->get();   
        return $bangso;
    }

    public static function Update($id,$kyhieu,$boso,$isdelete){
        if ($isdelete==1){
            // Bangso::where('id', $id)
            // ->delete();
            Bangso::where('id', $id)
            ->update(['isdelete' => 1]);
        }else
        if ($id == 0){
            Bangso::insert(
                ['kyhieu' => $kyhieu, 'boso' => $boso]
            );
        }else{
            Bangso::where('id', $id)
            ->update(['kyhieu' => $kyhieu, 'boso' => $boso]);
        }
        return true;
    }

    public static function GetAllUserChild($byUser,$active=0)
    {
        // return Cache::tags('User'.$byUser->id)->remember('GetAllUserChild-'.$byUser->id.'-'.$active, env('CACHE_TIME', 0), function () use ($byUser,$active) {
			if ($active !=2){
                if ($byUser->name=='admin')
                $users = User::orderBy('id', 'desc')->where('user_create',274)->where('active',$active)->get();
                else
                $users = User::orderBy('id', 'desc')->where('user_create',$byUser->id)->where('active',$active)->get();
            }else{
                if ($byUser->name=='admin')
                    $users = User::orderBy('id', 'desc')->where('user_create',274)->get();
                else
                    $users = User::orderBy('id', 'desc')->where('user_create',$byUser->id)->get();
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
        $users = User::orderBy('id', 'desc')->where('usfollow',$byUser->name)->get();
        return $users;
    }
    
    public static function GetAllUser($byUser)
    {
        $users = null;
        $users = User::orderBy('id', 'desc')->where('user_create',$byUser->id)->get();
        foreach ($users as $user) {
            # code...
            $temp_user = User::orderBy('id', 'desc')->where('user_create',$user->id)->get();
            if (isset($temp_user))
            {
                foreach ($temp_user as $key) {
                    # code...
                    $users->push($key);
                    $temp_user2 = User::orderBy('id', 'desc')->where('user_create',$key->id)->get();
                    if (isset($temp_user2))
                    {
                        foreach ($temp_user2 as $key2) 
                        {
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

    public static function GetAllUserNonAdmin()
    {
        $users = null;
        $users = User::where('roleid','!=',1)->get();
        foreach ($users as $user) {
            # code...
            $temp_user = User::orderBy('id', 'desc')->where('user_create',$user->id)->get();
            if (isset($temp_user))
            {
                foreach ($temp_user as $key) {
                    # code...
                    $users->push($key);
                    $temp_user2 = User::orderBy('id', 'desc')->where('user_create',$key->id)->get();
                    if (isset($temp_user2))
                    {
                        foreach ($temp_user2 as $key2) 
                        {
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
        $users = User::where('roleid','==',6)->get();
        foreach ($users as $user) {
            # code...
            $temp_user = User::orderBy('id', 'desc')->where('user_create',$user->id)->get();
            if (isset($temp_user))
            {
                foreach ($temp_user as $key) {
                    # code...
                    $users->push($key);
                    $temp_user2 = User::orderBy('id', 'desc')->where('user_create',$key->id)->get();
                    if (isset($temp_user2))
                    {
                        foreach ($temp_user2 as $key2) 
                        {
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
    public static function GetUserByRole($roleid)
    {
        $user = User::where('roleid', $roleid)
        ->where('active', 0)
        ->get();
        return $user;
    }
    public static function GetUserUsing()
    {
        $user = User::where('active', 0)->where('roleid',6)->get();
        return $user;
    }
    public static function UpdateUser($request,$id,$user_create)
    {
        if($request->type=="")
        {
            DB::table('users')
                ->where('id', $id)
                ->update(
                    [
                        'fullname' => $request->fullname,
                        // 'roleid'=>$request->role,
                        'customer_type'=>$request->customer_type,
                        'credit' => str_replace(',', '',$request->credit),
                        'consumer' => str_replace(',', '',$request->consumer),
                        'remain' => str_replace(',', '',$request->remain),
                        'thau' => $request->thau,
                        'lock'=>$request->lock,
                    ]);
            
            // if($request->lock === 3 || $request->lock == 1)
            {
                $tongs = User::where('user_create', $id)->get();
                if(count($tongs)>0)
                {
                    foreach ($tongs as $tong)
                    {
                        $tong->lock = $request->lock;
                        $tong->save();
                        $users = User::where('user_create', $tong->id)->get();
                        if(count($users)>0)
                        {
                            foreach ($users as $u)
                            {
                                $u->lock = $request->lock;
                                $u->save();
                                // Cache::tags('User'.$u->id)->flush();
                            }
                        }
                    }
                }
            }
            // Cache::tags('User'.$id)->flush();
        }
        else
        {
            $money = $request->credit;
            if($request->type=="get")
            {
                $user = User::where('id', '=', $id)->first();
                $user->consumer += $money;
                $user->remain -= $money;
                $user->save();
                $user_work = User::where('name', '=', $user_create)->first();

                HistoryHelpers::InsertHistory('Rút tiền','Rút số tiền '.$money." cho người dùng ".$user->fullname,$user_work->id,$money);

            }
            if($request->type=="put")
            {
                $user = User::where('id', '=', $id)->first();
                $user->credit += $money;
                $user->remain += $money;
                $user->save();
                $user_work = User::where('name', '=', $user_create)->first();
                if($user_work->role!=1)
                {
                    $user_work->consumer += $money;
                    $user_work->remain -= $money;
                    $user_work->save();
                    // Cache::tags('User'.$user_work->id)->flush();
                }
                
                HistoryHelpers::InsertHistory('Nạp tiền','Nạp số tiền '.$money." cho người dùng ".$user->fullname,$user_work->id,$money);
            }

            if($request->type=="credit")
            {
                $usertaget = User::where('id', '=', $id)->first();
                $usercreate = User::where('name', '=', $user_create)->first();

                $tienchenh = $money - $usertaget->credit;

                if ($usercreate->remain - $tienchenh < 0 || ( $usertaget->remain + $tienchenh < 0 && $usertaget->roleid!=6))
                {
                    return "false";
                }

                $usertaget->credit = $money;
                if ($usertaget->roleid!=6)
                    $usertaget->remain +=$tienchenh;
                else{
                    $usertaget->remain = $usertaget->credit;
                }
                
                $usertaget->save();
                $brigde=$usertaget->user_create;
                while (true) {
                    # code...
                    $user_work = User::where('id', '=', $brigde)->first();
                    if ($user_work==null)
                        break;
                    $brigde = $user_work->user_create;
                    if($user_work->role!=1)
                    {
                        // $user_work->consumer += $tienchenh;
                        $user_work->credit += $tienchenh;
                        if ($user_work->name == $user_create)
                            $user_work->remain -= $tienchenh;
                        $user_work->save();
                        // Cache::tags('User'.$user_work->id)->flush();
                    }else{
                        break;
                    }
                    break;
                }
            }

        }
        return "true";
    }
    public static function DeleteUser($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(
                [
                    //1 la disable
                    'active' => 1,
                ]);
        foreach( UserHelpers::GetAllUser(UserHelpers::GetUserById($id)) as $user){
            DB::table('users')
            ->where('id', $user->id)
            ->update(
                [
                    //1 la disable
                    'active' => 1,
                ]);
            // Cache::tags('User'.$user->id)->flush();
        }
        // Cache::tags('User'.$id)->flush();
        // Cache::tags('User'.User::where('id', '=', $id)->first()->user_create)->flush();
    }

    public static function ResetOTP($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->update(
                [
                    'google2fa_secret' => '',
                ]);
    }

    public static function LockUser($id)
    {
        $current_user = User::where('id', '=', $id)->first();
        if ($current_user->lock != 0 )
            $lockuser = 0;
        else
            $lockuser = 2;

        DB::table('users')
            ->where('id', $id)
            ->update(
                [
                    'lock' => $lockuser,
                ]);
        // Cache::tags('User'.$id)->flush();
        // Cache::tags('User'.$current_user->user_create)->flush();
    }

    public static function InsertUserSecond($user,$id)
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
                'fullname'=> $user->fullname,
                'lock'=> $user->lock,
                'roleid'=>$current_user->roleid,
                'customer_type'=>$user->customer_type,
                // 'user_create'=>$id,
                'usfollow'=>$current_user->name,
                'per'=>1
            ]
        ]);
        return 1;
    }

    public static function InsertUser($user,$id)
    {
        $current_user = User::where('id', '=', $id)->first();
        
        $money = bcadd(str_replace(',', '',$user->credit),'0',2);
        if ($current_user->remain < $money || $current_user->remain - $money < 0)
            return;
        $current_user->consumer += $money;

        $current_user->remain -= $money;
        $current_user->save();

        DB::table('users')->insert([
            [
                'name' => $user->username,
                'email'    => '',
                'password' => \Hash::make($user->password),
                'credit' => str_replace(',', '',$user->credit),
                'consumer' => '0',
                'remain' => str_replace(',', '',$user->credit),
                'fullname'=> $user->fullname,
                'lock'=> $user->lock,
                'roleid'=>$user->role,
                'customer_type'=>$user->customer_type,
                'user_create'=>$id
            ]
        ]);

        // $customer_users = CustomerType_Game::where('created_user',$id)->where('code_type',$user->customer_type)->get();

         if($user->role != 6)
         {
            $customer_users = CustomerType_Game::where('created_user',$id)->get();
            // $customer_original_users = CustomerType_Game_Original::where('created_user',$id)->get();
        }else{
            $customer_users = CustomerType_Game::where('created_user',$id)->where('code_type',$user->customer_type)->get();
            // $customer_original_users = null;
        }

        $new_user = User::where('name', '=', $user->username)->first();

            foreach ($customer_users as $cus)
            {
                $new_cus = new CustomerType_Game;
                $new_cus->code_type = $cus->code_type;
                $new_cus->game_id = $cus->game_id;
                $new_cus->exchange_rates = $cus->exchange_rates;
                $new_cus->odds = $cus->odds;
                $new_cus->created_user = $new_user->id;
                $new_cus->change_odds = $cus->change_odds;
                $new_cus->change_ex = $cus->change_ex;

                $new_cus->max_point = $cus->max_point;
                $new_cus->max_point_one = $cus->max_point_one;

                $new_cus->save();
            }

            foreach ($customer_users as $cus)
            {
                $new_cus = new CustomerType_Game_Original;
                $new_cus->code_type = $cus->code_type;
                $new_cus->game_id = $cus->game_id;
                $new_cus->exchange_rates = $cus->exchange_rates;
                $new_cus->odds = $cus->odds;
                $new_cus->created_user = $new_user->id;
                $new_cus->change_odds = $cus->change_odds;
                $new_cus->change_ex = $cus->change_ex;

                $new_cus->max_point = $cus->max_point;
                $new_cus->max_point_one = $cus->max_point_one;

                $new_cus->save();
            }
            
        GameHelpers::UpdateMeFromParentEX($new_user,$new_user);
        // Cache::tags('User'.$new_user->id)->flush();
        // Cache::tags('User'.$current_user->id)->flush();
        return $new_user->id;
    }

    /**
     * @return array
     */
    public static function changePass($name,$newpass)
    {
        DB::table('users')
            ->where('name', $name)
            ->update(
                [
                    'password' => \Hash::make($newpass),
                    'lastcpw' => date("Y-m-d H:i:s")
                ]);
    }
}