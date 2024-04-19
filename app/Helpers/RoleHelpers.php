<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\History;
class RoleHelpers
{

    public static function getFunctionInRole($roleid)
    {
        $functions = DB::table('role')->where('id', $roleid)->get();
        foreach ($functions as $fc) {
            return $fc->functions;
        }
    }
    public static function getAllRole()
    {
        $roles = DB::table('role')->where('id','<>','1')->get();
        return $roles;
    }

    /**
     * @return array
     */
    public static function InsertRole($role)
    {
        DB::table('role')->insert([
            [
                'name' => $role->rolename,
                'isSuperAdmin'=>false,
                'functions'=>$role->function
            ]
        ]);
    }
    public static function UpdateRole($role,$id)
    {
        DB::table('role')
            ->where('id', $id)
            ->update(
                [
                    'name' => $role->rolename,
                    'functions'=>$role->function

                ]);
    }
    public static function DeleteRole($id)
    {
        DB::table('role')
            ->where('id', $id)
            ->delete();
    }
}