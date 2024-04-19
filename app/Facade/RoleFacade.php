<?php namespace App\Facade;

/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/18/2016
 * Time: 3:09 PM
 */
use Illuminate\Support\Facades\Facade;
class RoleFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'RoleHelpers';
    }
}