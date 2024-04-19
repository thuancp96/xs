<?php
namespace App\Helpers;

use App\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\History;
use \Cache;
class LocationHelpers
{
    public static function getTopLocation()
    {
        return DB::table('location')
            ->where('active',true)
            ->orderBy('order','asc')
            ->select('id','name','slug','url_api','time')
            ->get();
    }

    public static function getBySlug($slug)
    {
        // if(empty($slug)){
        //     return false;
        // }
        // return Cache::tags('Location')->remember('getBySlug-'.$slug, env('CACHE_TIME', 0), function () use ($slug) {
			return Location::where('slug',$slug)->select('id','name','slug','url_api','time','alias')->first();
        // });
    }

    public static function getByAlias($alias)
    {
        // if(empty($slug)){
        //     return false;
        // }
        // return Cache::tags('Location')->remember('getBySlug-'.$slug, env('CACHE_TIME', 0), function () use ($slug) {
			return Location::where('alias',$alias)->select('id','name','slug','url_api','time','alias')->first();
        // });
    }
}