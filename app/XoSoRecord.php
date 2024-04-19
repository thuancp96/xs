<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/11/2016
 * Time: 8:31 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\Log;

class XoSoRecord extends Model
{
    protected $table = 'xoso_record';
    function date(){
        return $this->hasMany('date');
    }

    // public function findOrFail()
    // {
    //     if (App::runningInConsole()) {
    //         Log::info("DB::reconnect");
    //         FacadesDB::reconnect();
    //     }

    //     return parent::__call('findOrFail', func_get_args());
    // }
}