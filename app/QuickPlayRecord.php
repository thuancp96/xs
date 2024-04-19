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

class QuickPlayRecord extends Model
{
    protected $table = 'quick_play_record';
    function date(){
        return $this->hasMany('date');
    }

}