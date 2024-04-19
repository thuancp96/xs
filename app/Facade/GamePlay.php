<?php namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class GamePlay extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'gamePlay';
    }
}