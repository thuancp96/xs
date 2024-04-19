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

class XoSoResult extends Model
{
    protected $table = 'xoso_result';
    function date(){
        return $this->hasMany('date');
    }

    public function Insert($data)
    {
        DB::table('xoso_result')->insert([
                'location_id' => $data['location_id'],
                'DB' => $data['DB'],
                'Giai_1' => $data['Giai_1'],
                'Giai_2' => $data['Giai_2'],
                'Giai_3' => $data['Giai_3'],
                'Giai_4' => $data['Giai_4'],
                'Giai_5' => $data['Giai_5'],
                'Giai_6' => $data['Giai_6'],
                'Giai_7' => $data['Giai_7'],
                'Giai_8' => $data['Giai_8'],
                'date' =>  $data['date']
        ]);
    }
    public function GetResultbyDateAndLocation($date,$location)
    {
        $result = DB::table('xoso_result')->where('date', $date)->where('location_id', $location)->first();
        return $result;
    }
}