<?php
/**
 * Created by PhpStorm.
 * User: Satoshi
 * Date: 9/11/2016
 * Time: 8:28 PM
 */

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Game_1478 extends Model
{
    protected $table='games_1478';


    // public function findOrFail()
    // {
    //     if (App::runningInConsole()) {
    //         Log::info("DB::reconnect");
    //         DB::reconnect();
    //     }

    //     return parent::__call('findOrFail', func_get_args());
    // }
    /**
     * Tính đề (Giải ĐB và 3 Càng) và Nhất
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public function CheckDeGiaiDBAndGiaiNhat($bets,$result,$loai)
    {
        $listbets = explode(",",$bets);
        if($result==null)
        {
            return null;
        }
        $DB = null;
        if($loai=="DB")
            $DB = substr($result->DB, -2);
        if($loai=="Giai_1")
            $DB = substr($result->Giai_1, -2);
        if($loai=="3_Cang")
            $DB = substr($result->DB, -3);
        $win_bet = array();
        foreach ($listbets as $bet)
        {
            if($DB==$bet)
            {
                array_push($win_bet,$bet);
            }
        }
        return $win_bet;
    }
    public function CalculatorDeGiaiDBAndGiaiNhat($xosorecord,$bet_win)
    {

    }

    /**
     * Tính lô 2 số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public function CheckLo2($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = $this->BuildArrayResult($result);
        if($result==null)
        {
            return null;
        }
        $win_bet = array();
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -2);
                if($bet==$DB)
                {
                    array_push($win_bet,$bet);
                }
            }
        }
        return $win_bet;
    }

    /**
     * Tính lô 3 số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public function CheckLo3($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = $this->BuildArrayResult($result);
        if($result==null)
        {
            return null;
        }
        $win_bet = array();
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -3);
                if($bet==$DB)
                {
                    array_push($win_bet,$bet);
                }
            }
        }
        return $win_bet;
    }

    /**
     * Tính lô xiên
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public function CheckLoXien($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = $this->BuildArrayResult($result);
        if($result==null) {
            return null;
        }
        $count = 0;
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -2);
                if($bet==$DB)
                {
                    $count +=1;
                    break;
                }
            }
        }
        if(count($listbets)==$count)
            return $listbets;
        else
            return null;
    }

    /**
     * Tính 3 càng
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public function Check3Cang($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = $this->BuildArrayResult($result);

        if($result==null) {
            return null;
        }
        $count = 0;
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -2);
                if($bet==$DB)
                {
                    $count +=1;
                    break;
                }
            }
        }
        if(count($listbets)==$count)
            return $listbets;
        else
            return null;
    }

    /**
     * Tính lô trượt
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public function CheckLoTruot($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = $this->BuildArrayResult($result);

        if($result==null) {
            return null;
        }
        $count = 0;
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -2);
                if($bet==$DB)
                {
                    return null;
                }
            }
        }
        return $listbets;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public  function BuildArrayResult($result)
    {
        $listResult = array();
        $listResult = array_merge($listResult,explode(",",$result->DB));
        $listResult = array_merge($listResult,explode(",",$result->Giai_1));
        $listResult = array_merge($listResult,explode(",",$result->Giai_2));
        $listResult = array_merge($listResult,explode(",",$result->Giai_3));
        $listResult = array_merge($listResult,explode(",",$result->Giai_4));
        $listResult = array_merge($listResult,explode(",",$result->Giai_5));
        $listResult = array_merge($listResult,explode(",",$result->Giai_6));
        $listResult = array_merge($listResult,explode(",",$result->Giai_7));
        $listResult = array_merge($listResult,explode(",",$result->Giai_8));
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public  function BuildArrayResultDGiai7($result)
    {
        $listResult = array();
        // $listResult = array_merge($listResult,explode(",",$result->DB));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_1));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_2));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_3));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_4));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_5));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_6));
        $listResult = array_merge($listResult,explode(",",$result->Giai_7));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_8));
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public  function BuildArrayResultDGiai6($result)
    {
        $listResult = array();
        // $listResult = array_merge($listResult,explode(",",$result->DB));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_1));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_2));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_3));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_4));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_5));
        $listResult = array_merge($listResult,explode(",",$result->Giai_6));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_7));
        // $listResult = array_merge($listResult,explode(",",$result->Giai_8));
        return $listResult;
    }
}