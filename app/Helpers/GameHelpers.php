<?php
namespace App\Helpers;

use App\Commands\UpdateChildEX;
use App\Commands\UpdateChildEX_NonMember;
use App\Commands\UpdateMeFromParentEXService;
use App\Game_Number;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\History;
use App\Game;
use App\Location;
use App\CustomerType_Game;
use App\CustomerType_Game_Original;
use \Cache;
use Exception;
use Log;
use \Queue;
use App\game_1478;
use App\Game_1533;
use App\Game_1561;
use App\Game_1650;
use App\Game_1698;

class GameHelpers
{

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckKimKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 210 && $totalResult <= 695)
            array_push($win_bet,'Kim');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckMocKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 696 && $totalResult <= 763)
            array_push($win_bet,'Kim');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckThuyKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 764 && $totalResult <= 855)
            array_push($win_bet,'Thủy');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckKMTHoaTKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 856 && $totalResult <= 923)
            array_push($win_bet,'Thủy');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckThoKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 924 && $totalResult <= 1410)
            array_push($win_bet,'Thổ');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckTaiLeKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 811 && $totalResult%2==1 )
            array_push($win_bet,'Tài Lẻ');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckTaiChanKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 811 && $totalResult%2==0 )
            array_push($win_bet,'Tài Chẵn');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckXiuLeKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult <= 810 && $totalResult%2==1 )
            array_push($win_bet,'Xỉu Lẻ');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckXiuChanKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult <= 810 && $totalResult%2==0 )
            array_push($win_bet,'Xỉu Chẵn');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckTaiKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult >= 811 )
            array_push($win_bet,'Tài');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckXiuKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult <= 810 )
            array_push($win_bet,'Xỉu');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLeKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult % 2 == 1)
            array_push($win_bet,'Lẻ');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckChanKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $win_bet = array();
        if ($totalResult % 2 == 0)
            array_push($win_bet,'Chẵn');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckRongKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $chuc = $totalResult%100;
        $hangdonvi = $chuc%10;
        $hangchuc = ($chuc - $hangdonvi)/10;

        $win_bet = array();
        if ($hangchuc > $hangdonvi)
            array_push($win_bet,'Rồng');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckHoKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $chuc = $totalResult%100;
        $hangdonvi = $chuc%10;
        $hangchuc = ($chuc - $hangdonvi)/10;

        $win_bet = array();
        if ($hangchuc < $hangdonvi)
            array_push($win_bet,'Hổ');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckRongHoaHoKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayTotalResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $chuc = $totalResult%100;
        $hangdonvi = $chuc%10;
        $hangchuc = ($chuc - $hangdonvi)/10;

        $win_bet = array();
        if ($hangchuc == $hangdonvi)
            array_push($win_bet,'Hoa');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckTrenKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $tren = 0;
        $duoi = 0;
        foreach($totalResult as $item){
            if ($item <= 40)
                $tren++;
            else $duoi++;
        }
        

        $win_bet = array();
        if ($tren > $duoi)
            array_push($win_bet,'Trên');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckTrenHoaDuoiKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $tren = 0;
        $duoi = 0;
        foreach($totalResult as $item){
            if ($item <= 40)
                $tren++;
            else $duoi++;
        }
        

        $win_bet = array();
        if ($tren == $duoi)
            array_push($win_bet,'Hòa');
        return $win_bet;
    }

    /**
     * Tính tai keno
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDuoiKeno($bets,$result)
    {
        $totalResult = GameHelpers::BuildArrayResultKeno($result);
        if($result==null)
        {
            return null;
        }

        $tren = 0;
        $duoi = 0;
        foreach($totalResult as $item){
            if ($item <= 40)
                $tren++;
            else $duoi++;
        }
        

        $win_bet = array();
        if ($tren < $duoi)
            array_push($win_bet,'Dưới');
        return $win_bet;
    }

    /**
     * Tính đề (Giải ĐB và 3 Càng) và Nhất
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDeGiaiDBAndGiaiNhat($bets,$result,$loai)
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


    /**
     * Tính đề (Giải ĐB và 3 Càng) và Nhất
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoDe($bet,$result,$loai)
    {
        if($result==null)
        {
            return null;
        }
        $DB = null;
        if($loai=="DB")
            $DB = substr($result['DB'], -2);
        if($loai=="Giai_1")
            $DB = substr($result['1'], -2);
        if($loai=="3_Cang")
            $DB = substr($result['DB'], -3);
        if($loai=="3_Cang_nhat")
            $DB = substr($result['1'], -3);
        if($loai=="7")
            $DB = substr($result['7'], -3);
        if($loai=="8")
            $DB = substr($result['8'], -2);
        if($DB==$bet)
        {
            return $bet;
        }else
            return null;
    }

    /**
     * Tính đề (Giải ĐB và 3 Càng) và Nhất
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoDeMienNamTrung($bet,$result,$loai)
    {
        if($result==null)
        {
            return null;
        }
        $DB = null;
        if($loai=="DB")
            $DB = substr($result['DB'][0], -2);
        if($loai=="Giai_1")
            $DB = substr($result['1'][0], -2);
        if($loai=="3_Cang")
            $DB = substr($result['DB'][0], -3);
        if($loai=="3_Cang_nhat")
            $DB = substr($result['1'][0], -3);
        if($DB==$bet)
        {
            return $bet;
        }

        $DB = null;
        if($loai=="DB")
            $DB = substr($result['DB'][1], -2);
        if($loai=="Giai_1")
            $DB = substr($result['1'][1], -2);
        if($loai=="3_Cang")
            $DB = substr($result['DB'][1], -3);
        if($loai=="3_Cang_nhat")
            $DB = substr($result['1'][1], -3);
        if($DB==$bet)
        {
            return $bet;
        }

        return null;
    }

    /**
     * Tính đầu đề nhất (Giải ĐB và 3 Càng) và Nhất
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDauLoDe($bet,$result,$loai)
    {
        if($result==null)
        {
            return null;
        }
        $DB = null;
        if($loai=="DB")
            $DB = substr($result['DB'], 0, 2);
        if($loai=="Giai_1")
            $DB = substr($result['1'], 0, 2);
        // if($loai=="3_Cang")
        //     $DB = substr($result['DB'], -3);
        // if($loai=="3_Cang_nhat")
        //     $DB = substr($result['1'], -3);
        if($DB==$bet)
        {
            return $bet;
        }else
            return null;
    }

    /**
     * Tính đầu thần tài
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDauThanTai($bet,$result,$loai)
    {
        if($result==null)
        {
            return null;
        }
        $DB = null;
        if($loai=="than_tai")
            $DB = substr($result['than_tai'], 0, 2);
        
        if($DB==$bet)
        {
            return $bet;
        }else
            return null;
    }

    /**
     * Tính đầu thần tài
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDuoiThanTai($bet,$result,$loai)
    {
        if($result==null)
        {
            return null;
        }
        $DB = null;
        if($loai=="than_tai")
            $DB = substr($result['than_tai'], -2);
        
        if($DB==$bet)
        {
            return $bet;
        }else
            return null;
    }

    /**
     * Tính đề trượt
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDeTruot($bet,$result,$loai)
    {
        $DB = null;
        // if($loai=="DB")
        $DB = substr($result['DB'], -2);
        //$listbets = explode(",",$bet);
        //$listResult = GameHelpers::BuildArrayResult($result);

        if($result==null) {
            return null;
        }
        $count = 0;
        //foreach ($listbets as $bet)
        {
            //foreach ($listResult as $item)
            {
              //  $DB = substr($item, -2);
                if($bet==$DB)
                {
                    return null;
                }
            }
        }
        return $bet;
    }

    /**
     * Tính lô 2 số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLo2($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResult($result);
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
     * Tính lô live 2 số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
     public static function CheckLoLive2($bets,$kqxsdr,$result)
     {
        if($result==null)
        {
            return null;
        }
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResultLoLive($result);
        $win_bet = array();

        foreach ($listbets as $bet){
            for($i=$kqxsdr;$i<27;$i++){
                $DB = substr($listResult[$i], -2);
                if($bet==$DB)
                {
                    array_push($win_bet,$bet);
                }
            }
        }
        return $win_bet;
     }

    /**
     * Tính đề giải 7 2 số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckGiaiX($bets,$result,$giai,$stt)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResultByGiaiX($result,$giai);
        if($result==null)
        {
            return null;
        }
        $win_bet = array();
        foreach ($listbets as $bet)
        {
            // foreach ($listResult as $item)
            {
                $DB = substr($listResult[$stt-1], -2);
                if($bet==$DB)
                {
                    array_push($win_bet,$bet);
                }
            }
        }
        return $win_bet;
    }

    /**
     * Tính đề giải 7  số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDe7($bets,$result,$giai)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResultGiai7($result);
        if($result==null)
        {
            return null;
        }
        $win_bet = array();
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = $item;
                if($bet==$DB)
                {
                    array_push($win_bet,$bet);
                }
            }
        }
        return $win_bet;
    }

    /**
     * Tính đề giải 6 3 số
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckDe6($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResultGiai6($result);
        if($result==null)
        {
            return null;
        }
        $win_bet = array();
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = $item;
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
    public static function CheckLo3($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResult3($result);
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
     * Tính lô xiên nhay
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoXienNhayLive($bets,$kqxsdr,$result)
    {
        $listbets = explode(",",$bets);
        // // \log::info('listbets was @ ' . $bets);
        $win_bet=array();
        $listResult = GameHelpers::BuildArrayResult($result);
        if($result==null) {
            return null;
        }
        $count = 0;

        foreach ($listbets as $bet){
            for($i=$kqxsdr;$i<27;$i++){
                $DB = substr($listResult[$i], -2);
                if($bet==$DB)
                {
                    $count +=1;
                    array_push($win_bet,$bet);
                }
            }
        }

        // foreach ($listbets as $bet)
        // {
        //     foreach ($listResult as $item)
        //     {
        //         $DB = substr($item, -2);
        //         if($bet==$DB)
        //         {
        //             $count +=1;
        //             array_push($win_bet,$bet);
        //             // break;
        //         }
        //     }
        // }
        if($count >= 2)
            return $win_bet;
        else
            {
                // // \log::info('count was @ ' . $count);
                return null;
            }
    }

    /**
     * Tính lô xiên nhay
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoXienNhay($bets,$result)
    {
        $listbets = explode(",",$bets);
        // // \log::info('listbets was @ ' . $bets);
        $win_bet=array();
        $listResult = GameHelpers::BuildArrayResult($result);
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
                    array_push($win_bet,$bet);
                    // break;
                }
            }
        }
        if($count >= 2)
            return $win_bet;
        else
            {
                // // \log::info('count was @ ' . $count);
                return null;
            }
    }

    /**
     * Tính lô xiên live
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoXienLive($bets,$kqxsdr,$result)
    {
        $listbets = explode(",",$bets);
        // // \log::info('listbets was @ ' . $bets);
        $listResult = GameHelpers::BuildArrayResult($result);
        // print_r($listResult);
        if($result==null) {
            return null;
        }
        $count = 0;
        // echo $kqxsdr . "-";
        foreach ($listbets as $bet){
            for($i=$kqxsdr;$i<27;$i++){
                $DB = substr($listResult[$i], -2);
                if($bet==$DB)
                {
                    $count +=1;
                    // echo $DB . ".";
                    break;
                }
            }
        }

        // foreach ($listbets as $bet)
        // {
        //     foreach ($listResult as $item)
        //     {
        //         $DB = substr($item, -2);
        //         if($bet==$DB)
        //         {
        //             $count +=1;
        //             break;
        //         }
        //     }
        // }
        if(count($listbets)==$count)
            return $listbets;
        else
            {
                // // \log::info('count was @ ' . $count);
                return null;
            }
    }

    /**
     * Tính lô xiên
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoXien($bets,$result)
    {
        $listbets = explode(",",$bets);
        // // \log::info('listbets was @ ' . $bets);
        $listResult = GameHelpers::BuildArrayResult($result);
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
            {
                // // \log::info('count was @ ' . $count);
                return null;
            }
    }

    /**
     * Tính 3 càng
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function Check3Cang($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResult($result);

        if($result==null) {
            return null;
        }
        $count = 0;
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -3);
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
     * Tính lô trượt 4 8 10
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoTruot1($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResult($result);

        if($result==null) {
            return null;
        }
        $lose_bet = array();
        $trung = false;
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -2);
                // // \log::info('DB was @ ' .$bets .' - '. $DB);
                if($bet==$DB)
                {
                    if ($bet=='00') $bet=1;
                    array_push($lose_bet,0-$bet);
                    $trung = true;
                }
            }
        }
        if ($trung==false)
            return $listbets;
        else
            return $lose_bet;
    }

    /**
     * Tính lô trượt 4 8 10
     * @param $bets các số đặt cược
     * @param $result kết quả xổ số theo ngày
     * @return array|null
     */
    public static function CheckLoTruot($bets,$result)
    {
        $listbets = explode(",",$bets);
        $listResult = GameHelpers::BuildArrayResult($result);

        if($result==null) {
            return null;
        }
        $count = 0;
        foreach ($listbets as $bet)
        {
            foreach ($listResult as $item)
            {
                $DB = substr($item, -2);
                // // \log::info('DB was @ ' .$bets .' - '. $DB);
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
    public static  function BuildArrayResult($result)
    {
        $listResult = array();
        if (isset($result['locationid']) && $result['locationid']>20){
            
            $listResult = array_merge($listResult,array($result['DB']));
            $listResult = array_merge($listResult,array($result['1']));
            $listResult = array_merge($listResult,array($result['2']));
            $listResult = array_merge($listResult,$result['3']);
            $listResult = array_merge($listResult,$result['4']);
            $listResult = array_merge($listResult,array($result['5']));
            $listResult = array_merge($listResult,$result['6']);
            $listResult = array_merge($listResult,array($result['7']));
            $listResult = array_merge($listResult,array($result['8']));
        }else{
            if (isset($result['locationid']) && $result['locationid']== 5){
                $listResult = array_merge($listResult,$result['DB']);
            }else{
                $listResult = array_merge($listResult,array($result['DB']));
                $listResult = array_merge($listResult,array($result['1']));
                $listResult = array_merge($listResult,$result['2']);
                $listResult = array_merge($listResult,$result['3']);
                $listResult = array_merge($listResult,$result['4']);
                $listResult = array_merge($listResult,$result['5']);
                $listResult = array_merge($listResult,$result['6']);
                $listResult = array_merge($listResult,$result['7']);
                // $listResult = array_merge($listResult,$result['8']);
            }
        }
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public static  function BuildArrayResultForAlert($result)
    {
        $listResult = array();
        if (isset($result['locationid']) && $result['locationid']>20){
            
            $listResult = array_merge($listResult,array($result['DB']));
            $listResult = array_merge($listResult,array($result['1']));
            $listResult = array_merge($listResult,array($result['2']));
            $listResult = array_merge($listResult,$result['3']);
            $listResult = array_merge($listResult,$result['4']);
            $listResult = array_merge($listResult,array($result['5']));
            $listResult = array_merge($listResult,$result['6']);
            $listResult = array_merge($listResult,array($result['7']));
            $listResult = array_merge($listResult,array($result['8']));
        }else{
            if (isset($result['locationid']) && $result['locationid']== 5){
                $listResult = array_merge($listResult,$result['DB']);
            }else{
                $listResult = array_merge($listResult,array($result['1']));
                $listResult = array_merge($listResult,$result['2']);
                $listResult = array_merge($listResult,$result['3']);
                $listResult = array_merge($listResult,$result['4']);
                $listResult = array_merge($listResult,$result['5']);
                $listResult = array_merge($listResult,$result['6']);
                $listResult = array_merge($listResult,$result['7']);
                $listResult = array_merge($listResult,array($result['DB']));
                // $listResult = array_merge($listResult,$result['8']);
            }
        }
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public static  function BuildArrayResult3($result)
    {
        $listResult = array();
        if (isset($result['locationid']) && $result['locationid']>20){
            
            $listResult = array_merge($listResult,array($result['DB']));
            $listResult = array_merge($listResult,array($result['1']));
            $listResult = array_merge($listResult,array($result['2']));
            $listResult = array_merge($listResult,$result['3']);
            $listResult = array_merge($listResult,$result['4']);
            $listResult = array_merge($listResult,array($result['5']));
            $listResult = array_merge($listResult,$result['6']);
            $listResult = array_merge($listResult,array($result['7']));
            $listResult = array_merge($listResult,array($result['8']));
        }else{
            if (isset($result['locationid']) && $result['locationid']== 5){
                $listResult = array_merge($listResult,$result['DB']);
            }else{
                $listResult = array_merge($listResult,array($result['DB']));
                $listResult = array_merge($listResult,array($result['1']));
                $listResult = array_merge($listResult,$result['2']);
                $listResult = array_merge($listResult,$result['3']);
                $listResult = array_merge($listResult,$result['4']);
                $listResult = array_merge($listResult,$result['5']);
                $listResult = array_merge($listResult,$result['6']);
                // $listResult = array_merge($listResult,$result['7']);
                // $listResult = array_merge($listResult,$result['8']);
            }
        }
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public static  function BuildArrayResultKeno($result)
    {
        $listResult = array();
        $listResult = array_merge($listResult,$result['DB']);
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public static  function BuildArrayTotalResultKeno($result)
    {
        $totalResult = 0;
        $totalResult = $result['1'];
        return $totalResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
     public static  function BuildArrayResultLoLive($result)
     {
         $listResult = array();
         $listResult = array_merge($listResult,array($result['1']));
         $listResult = array_merge($listResult,$result['2']);
         $listResult = array_merge($listResult,$result['3']);
         $listResult = array_merge($listResult,$result['4']);
         $listResult = array_merge($listResult,$result['5']);
         $listResult = array_merge($listResult,$result['6']);
         $listResult = array_merge($listResult,$result['7']);
         $listResult = array_merge($listResult,array($result['DB']));
         return $listResult;
     }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public static  function BuildArrayResultGiai6($result)
    {
        $listResult = array();
        // $listResult = array_merge($listResult,array($result['DB']));
        // $listResult = array_merge($listResult,array($result['1']));
        // $listResult = array_merge($listResult,$result['2']);
        // $listResult = array_merge($listResult,$result['3']);
        // $listResult = array_merge($listResult,$result['4']);
        // $listResult = array_merge($listResult,$result['5']);
        $listResult = array_merge($listResult,$result['6']);
        // $listResult = array_merge($listResult,$result['7']);
        // $listResult = array_merge($listResult,$result['8']);
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
    public static  function BuildArrayResultGiai7($result)
    {
        $listResult = array();
        // $listResult = array_merge($listResult,array($result['DB']));
        // $listResult = array_merge($listResult,array($result['1']));
        // $listResult = array_merge($listResult,$result['2']);
        // $listResult = array_merge($listResult,$result['3']);
        // $listResult = array_merge($listResult,$result['4']);
        // $listResult = array_merge($listResult,$result['5']);
        // $listResult = array_merge($listResult,$result['6']);
        $listResult = array_merge($listResult,$result['7']);
        // $listResult = array_merge($listResult,$result['8']);
        return $listResult;
    }

    /**
     * Chuyển toàn bộ kết quả thành mảng
     * @param $result
     * @return array
     */
     public static  function BuildArrayResultByGiaiX($result,$giai)
     {
         $listResult = array();
         $listResult = array_merge($listResult,$result[$giai]);
         return $listResult;
     }

    public function GetGameList($locationId)
    {
        if (!is_numeric($locationId)) {
            return [];
        }
        return Game::where('active', 1)
            ->where('location_id', intval($locationId))
            ->where('parent_id',0)
            ->select('games.*')
            ->orderBy('order')
            ->get();
    }

    public function GetAgGameList($locationId=1)
    {
        // if (!is_numeric($locationId)) {
        //     return [];
        // }
        return Game::where('active', 1)
            ->where('location_id', intval($locationId))
            ->where('parent_id',0)
            ->select('games.*')
            ->orderBy('order')
            ->get();
    }

    public function GetExchange_rates($locationId)
    {

        if (!is_numeric($locationId)) {
            return [];
        }
        $games = Game::where('active', 1)
            ->where('location_id', intval($locationId))
            ->where('parent_id',0)
            ->select('games.*')
            ->orderBy('order')
            ->get();
        $result = array();
        foreach ($games as $game)
        {
            for ($i=0;$i<10;$i++)
            {
                for($j=0;$j<10;$j++)
                {
                    $numb = bcadd($i.$j,'0',2);

                }
            }
        }

        return $result;
    }
    public static function GetGame_Number($gamecode,$number)
    {
        $user = Auth::user();
        // return \Cache::remember('Game_Number'.$user->id.'-'.$gamecode.'-'.$number.'-'.date('Y-m-d'),24*60, function() use ($gamecode,$number,$user) {
        //     return Game_Number::where('code_type',$gamecode)
        //     ->where('number', $number)
        //     ->where('userid', $user->id)
        //     ->whereDate('updated_at', '=', date('Y-m-d'))
        //     ->select('game_number.*')
        //     ->get();
        // });
        return Game_Number::where('code_type',$gamecode)
        ->where('number', $number)
        ->where('userid', $user->id)
        ->whereDate('updated_at', '=', date('Y-m-d'))
        ->select('game_number.*')
        ->get();
    }

    public static function GetGame_NumberUser($gamecode,$number,$user)
    {
        // $user = Auth::user();
        // return \Cache::remember('Game_Number'.$user->id.'-'.$gamecode.'-'.$number.'-'.date('Y-m-d'),24*60, function() use ($gamecode,$number,$user) {
        //     return Game_Number::where('code_type',$gamecode)
        //     ->where('number', $number)
        //     ->where('userid', $user->id)
        //     ->whereDate('updated_at', '=', date('Y-m-d'))
        //     ->select('game_number.*')
        //     ->get();
        // });
        if ($gamecode == 18) $gamecode = 7;
        return Game_Number::where('code_type',$gamecode)
        ->where('number', $number)
        ->where('userid', $user->id)
        ->whereDate('updated_at', '=', date('Y-m-d'))
        ->select('game_number.*')
        ->get();
    }

    public static function GetGame_NumberByUser($gamecode,$number,$user_id)
    {
        // $user = Auth::user();
        // return \Cache::remember('Game_Number'.$user->id.'-'.$gamecode.'-'.$number.'-'.date('Y-m-d'),24*60, function() use ($gamecode,$number,$user) {
        //     return Game_Number::where('code_type',$gamecode)
        //     ->where('number', $number)
        //     ->where('userid', $user->id)
        //     ->whereDate('updated_at', '=', date('Y-m-d'))
        //     ->select('game_number.*')
        //     ->get();
        // });
        return Game_Number::where('code_type',$gamecode)
        ->where('number', $number)
        ->where('userid', $user_id)
        ->whereDate('updated_at', '=', date('Y-m-d'))
        ->select('game_number.*')
        ->get();
    }

    public static function GetGame_AllNumber($gamecode)
    {
        if ($gamecode == 18){
            $user = Auth::user();
            return 
            // Cache::tags('Game_Number'.$user->id)->remember('GetGame_AllNumber-'.$gamecode.'-'.$user->id, env('CACHE_TIME', 0), function () use ($gamecode,$user) {
                // return 
                Game_Number::where('code_type',7)
                // ->where('number', $number)
                ->where('userid', 274)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                ->select('game_number.*')
                ->get();
            // });

        }else{
            $user = Auth::user();
            return 
            // Cache::tags('Game_Number'.$user->id)->remember('GetGame_AllNumber-'.$gamecode.'-'.$user->id, env('CACHE_TIME', 0), function () use ($gamecode,$user) {
                // return 
                Game_Number::where('code_type',$gamecode)
                // ->where('number', $number)
                ->where('userid', $user->id)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                ->select('game_number.*')
                ->get();
            // });
            
        }
        // return \Cache::remember('Game_Number'.$user->id.'-'.$gamecode.'-'.$number.'-'.date('Y-m-d'),24*60, function() use ($gamecode,$number,$user) {
        // });
    }

    public static function InsertGame_Number($gamecode,$number,$total)
    {
        $game_number = Game_Number::where('code_type',$gamecode)
            ->where('number',$number)
            ->whereDate('updated_at', '=', date('Y-m-d'))
            ->first();
        $game = Game::where('game_code',$gamecode)
            ->first();
        $user = Auth::user();  
        if(count($game_number)>0)
        {
            $money = bcadd(str_replace(',', '',$total),'0',2);
            $total =  $game_number->total + $money;
            $game_number->total = $total;
            $game_number->userid = $user->id;
            if($total >$game_number->x)
            {
                if($game_number->a!=0)
                {
                    $y = ($total -$game_number->x)/$game_number->a;
                    $game_number->y = round($y);
                    $game_number->exchange_rates += round($y);
                }
                else
                {
                    $game_number->exchange_rates = $game->exchange_rates;
                    $game_number->y = 0;
                }
            }
            $game_number->save();
        }
        else
        {
            $game_number = new Game_Number;
            $game_number->a = $game->a;
            $game_number->x = $game->x;
            $game_number->total = bcadd(str_replace(',', '',$total),'0',2);
            $game_number->number = $number;
            $game_number->code_type = $gamecode;
            $game_number->userid = $user->id;
            //$total = bcadd(str_replace(',', '',$total),'0',2);
            if($total > $game->x)
            {
                if($game->a!=0)
                {
                    $y = ($total -$game->x)/$game->a;
                    $game_number->y = round($y);
                    $game_number->exchange_rates = $game->exchange_rates + round($y);
                }
                else{
                    $game_number->exchange_rates = $game->exchange_rates;
                    $game_number->y = 0;
                }
            }
            else
            {
                $game_number->exchange_rates = $game->exchange_rates;
                $game_number->y = 0;
            }
            $game_number->save();
        }
    }

    public static function Update_Game_Number($request)
    {
        $type = $request->type;
        $gamecode = $request->game_code;
        $number = $request->number;
        $value = $request->value;
        $changeValue = 0;
        $user = Auth::user();
        if($type=='All')
        {
            $game_number = Game_Number::where('code_type',$gamecode)
                ->where('number',$number)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                ->first();
            $game = Game::where('game_code',$gamecode)
                ->first();
            if(count($game_number)>0)
            {
                $game_number->exchange_rates = $request->exchange_rates;
                $game_number->a = $request->a;
                $game_number->x = $request->x;
                $game_number->y = 0;
                $game_number->userid = $user->id;
                $game_number->save();
            }
            else
            {
                $game_number = new Game_Number;
                $game_number->exchange_rates = $request->exchange_rates;
                $game_number->a = $request->a;
                $game_number->x = $request->x;
                $game_number->y = 0;
                $game_number->number = $number;
                $game_number->code_type = $gamecode;
                $game_number->userid = $user->id;
                $game_number->save();
            }
        }
        if($type=='exchange_rates')
        {
            $game_number = Game_Number::where('code_type',$gamecode)
                ->where('number',$number)
                ->where('userid', $user->id)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                ->first();
            $game = Game::where('game_code',$gamecode)
                ->first();
            if(count($game_number)>0)
            {
                $changeValue = $value-$game_number->exchange_rates;
                $game_number->exchange_rates = $value;
                $game_number->userid = $user->id;
                $game_number->save();
            }
            else
            {
                $game_number = new Game_Number;
                $game_number->exchange_rates = $value;
                $changeValue = $value-$game->exchange_rates;
                $game_number->a = $game->a;
                $game_number->x = $game->x;
                $game_number->y = 0;
                $game_number->number = $number;
                $game_number->code_type = $gamecode;
                $game_number->userid = $user->id;
                $game_number->save();
            }

            GameHelpers::UpdateChildEX($user,$game_number,$value,0);
        }
        if($type=='a')
        {
            $game_number = Game_Number::where('code_type',$gamecode)
            ->whereDate('updated_at', '=', date('Y-m-d'))
                ->where('number',$number)
                ->first();
            $game = Game::where('game_code',$gamecode)
                ->first();
            if(count($game_number)>0)
            {
                $game_number->a = $value;
                $game_number->userid = $user->id;
                $game_number->save();
            }
            else
            {
                $game_number = new Game_Number;
                $game_number->exchange_rates = $game->exchange_rates;;
                $game_number->a = $value;
                $game_number->x = $game->x;
                $game_number->y = 0;
                $game_number->number = $number;
                $game_number->code_type = $gamecode;
                $game_number->userid = $user->id;
                $game_number->save();
            }
        }
        if($type=='x')
        {
            $game_number = Game_Number::where('code_type',$gamecode)
            ->whereDate('updated_at', '=', date('Y-m-d'))
                ->where('number',$number)
                ->first();
            $game = Game::where('game_code',$gamecode)
                ->first();
            if(count($game_number)>0)
            {
                $game_number->x = $value;
                $game_number->userid = $user->id;
                $game_number->save();
            }
            else
            {
                $game_number = new Game_Number;
                $game_number->exchange_rates = $game->exchange_rates;
                $game_number->a = $game->a;
                $game_number->x = $value;
                $game_number->y = 0;
                $game_number->number = $number;
                $game_number->code_type = $gamecode;
                $game_number->userid = $user->id;
                $game_number->save();
            }
        }

        if($type=='y')
        {
            $game_number = Game_Number::where('code_type',$gamecode)
                ->where('number',$number)
                ->where('userid', $user->id)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                ->first();
            $game = Game::where('game_code',$gamecode)
                ->first();
            if(count($game_number)>0)
            {
                $changeValue = $value-$game_number->exchange_rates;
                $game_number->exchange_rates = $game_number->exchange_rates;
                $game_number->userid = $user->id;
                $game_number->y = $request->y;
                $value = $request->ex;
                $game_number->save();
            }
            else
            {
                $game_number = new Game_Number;
                $game_number->exchange_rates = $game->exchange_rates;
                $changeValue = $value-$game->exchange_rates;
                $value = $request->ex;
                $game_number->a = $game->a;
                $game_number->x = $game->x;
                $game_number->y = $request->y;
                $game_number->number = $number;
                $game_number->code_type = $gamecode;
                $game_number->userid = $user->id;
                $game_number->save();
            }
            HistoryHelpers::ActiveHistorySave($user,$user,"thay đổi bảng giá ". $game->name . " - mã ". $number,"");
            GameHelpers::UpdateChildEX($user,$game_number,$value+$request->y,0);
        }

        if($type=='locknumberblack')
        {
            if (Auth::user()->roleid == 2){
                //using dynamic class
                $gameTableId = 'App\Game_'.Auth::user()->id;
                $ref = new $gameTableId;
                $game = $ref::where('game_code',$gamecode)->first();
                if ($request->status == "1")
                    $game->locknumber = str_replace($number,"",$game->locknumber);
                else{
                    if (!str_contains($game->locknumber,$number))
                        $game->locknumber .= ','.$number;
                }
                    
                $game->save();
    
                HistoryHelpers::ActiveHistorySave($user,$user,"khoá số đen ". $game->name . " - mã ". $number,"");
            }
            // $game_number = Game_Number::where('code_type',$gamecode)
            //     ->where('number',$number)
            //     ->where('userid', $user->id)
            //     ->whereDate('updated_at', '=', date('Y-m-d'))
            //     ->first();
            // $game = Game::where('game_code',$gamecode)
            //     ->first();
            // if(count($game_number)>0)
            // {
            //     $changeValue = $value-$game_number->exchange_rates;
            //     $game_number->exchange_rates = $game_number->exchange_rates;
            //     $game_number->userid = $user->id;
            //     $game_number->y = $request->y;
            //     $value = $request->ex;
            //     $game_number->save();
            // }
            // else
            // {
            //     $game_number = new Game_Number;
            //     $game_number->exchange_rates = $game->exchange_rates;
            //     $changeValue = $value-$game->exchange_rates;
            //     $value = $request->ex;
            //     $game_number->a = $game->a;
            //     $game_number->x = $game->x;
            //     $game_number->y = $request->y;
            //     $game_number->number = $number;
            //     $game_number->code_type = $gamecode;
            //     $game_number->userid = $user->id;
            //     $game_number->save();
            // }

            // GameHelpers::UpdateChildEX($user,$game_number,$value+$request->y,0);
        }

        if($type=='locknumberred')
        {
            $game = Game::where('game_code',$gamecode)->first();
            if ($request->status == "1")
                $game->locknumberred = str_replace($number,"",$game->locknumberred);
            else{
                if (!str_contains($game->locknumberred,$number))
                    $game->locknumberred .= ','.$number;
            }
            HistoryHelpers::ActiveHistorySave($user,$user,"khoá số đỏ ". $game->name . " - mã ". $number,"");
            $game->save();
            // $game_number = Game_Number::where('code_type',$gamecode)
            //     ->where('number',$number)
            //     ->where('userid', $user->id)
            //     ->whereDate('updated_at', '=', date('Y-m-d'))
            //     ->first();
            // $game = Game::where('game_code',$gamecode)
            //     ->first();
            // if(count($game_number)>0)
            // {
            //     $changeValue = $value-$game_number->exchange_rates;
            //     $game_number->exchange_rates = $game_number->exchange_rates;
            //     $game_number->userid = $user->id;
            //     $game_number->y = $request->y;
            //     $value = $request->ex;
            //     $game_number->save();
            // }
            // else
            // {
            //     $game_number = new Game_Number;
            //     $game_number->exchange_rates = $game->exchange_rates;
            //     $changeValue = $value-$game->exchange_rates;
            //     $value = $request->ex;
            //     $game_number->a = $game->a;
            //     $game_number->x = $game->x;
            //     $game_number->y = $request->y;
            //     $game_number->number = $number;
            //     $game_number->code_type = $gamecode;
            //     $game_number->userid = $user->id;
            //     $game_number->save();
            // }

            // GameHelpers::UpdateChildEX($user,$game_number,$value+$request->y,0);
        }

        if($type=='unlocknumberblackred')
        {
            if (Auth::user()->roleid == 1){
                $game = Game::where('game_code',$gamecode)->first();
                if ($request->status == "1"){
                    $game->locknumber = str_replace($number,"",$game->locknumber);
                    $game->locknumberred = str_replace($number,"",$game->locknumberred);
                }
                HistoryHelpers::ActiveHistorySave($user,$user,"mở khoá số ". $game->name . " - mã ". $number,"");
                $game->save();
            }

            if (Auth::user()->roleid == 2){
                //using dynamic class
                $gameTableId = 'App\Game_'.Auth::user()->id;
                $ref = new $gameTableId;
                $game = $ref::where('game_code',$gamecode)->first();
                if ($request->status == "1"){
                    $game->locknumber = str_replace($number,"",$game->locknumber);
                    $game->locknumberred = str_replace($number,"",$game->locknumberred);
                }
                HistoryHelpers::ActiveHistorySave($user,$user,"mở khoá số ". $game->name . " - mã ". $number,"");
                $game->save();
            }

        }

        if($type=='qlocknumberblack')
        {
            if (Auth::user()->roleid == 2){
                //using dynamic class
                $gameTableId = 'App\Game_'.Auth::user()->id;
                $ref = new $gameTableId;
                $game = $ref::where('game_code',$gamecode)->first();
                if ($request->status == "1"){
                    $strNumber = explode(",", $number);
                    $game->locknumber = str_replace($strNumber,"",$game->locknumber);
                }
                    
                else{
                    $strNumber = explode(",", $number);
                    // $game->locknumber = str_replace($strNumber,"",$game->locknumber);
                    foreach($strNumber as $numberItem)
                        if (!str_contains($game->locknumber,$numberItem))
                            $game->locknumber .= ','.$numberItem;
                }
                HistoryHelpers::ActiveHistorySave($user,$user,"khoá số đen ". $game->name . " - mã ". $number,"");
                $game->save();
            }
        }

        if($type=='qlocknumberred')
        {
            $game = Game::where('game_code',$gamecode)->first();
            if ($request->status == "1"){
                $strNumber = explode(",", $number);
                $game->locknumberred = str_replace($strNumber,"",$game->locknumberred);
            }
                
            else{
                $strNumber = explode(",", $number);
                // $game->locknumberred = str_replace($strNumber,"",$game->locknumberred);
                foreach($strNumber as $numberItem)
                    if (!str_contains($game->locknumberred,$numberItem))
                        $game->locknumberred .= ','.$numberItem;
            }
            HistoryHelpers::ActiveHistorySave($user,$user,"khoá số đỏ ". $game->name . " - mã ". $number,"");
            $game->save();
        }

        if($type=='qlocknumber')
        {
            if (Auth::user()->roleid == 1){
                if ($request->status == "1"){
                    $strNumber = explode(",", $number);
                    $game->locknumber = str_replace($strNumber,"",$game->locknumber);
                    $game->locknumberred = str_replace($strNumber,"",$game->locknumberred);
                }
                else{
                    $strNumber = explode(",", $number);
                    // $game->locknumber = str_replace($strNumber,"",$game->locknumber);
                    foreach($strNumber as $numberItem){
                        if (!str_contains($game->locknumber,$numberItem))
                            $game->locknumber .= ','.$numberItem;
                        if (!str_contains($game->locknumberred,$numberItem))
                            $game->locknumberred .= ','.$numberItem;
                    }
                        
                }
                HistoryHelpers::ActiveHistorySave($user,$user,"mở khoá số ". $game->name . " - mã ". $number,"");
                $game->save();
            }

            if (Auth::user()->roleid == 2){
                //using dynamic class
                $gameTableId = 'App\Game_'.Auth::user()->id;
                $ref = new $gameTableId;
                $game = $ref::where('game_code',$gamecode)->first();
                if ($request->status == "1"){
                    $strNumber = explode(",", $number);
                    $game->locknumber = str_replace($strNumber,"",$game->locknumber);
                    $game->locknumberred = str_replace($strNumber,"",$game->locknumberred);
                }
                else{
                    $strNumber = explode(",", $number);
                    // $game->locknumber = str_replace($strNumber,"",$game->locknumber);
                    foreach($strNumber as $numberItem){
                        if (!str_contains($game->locknumber,$numberItem))
                            $game->locknumber .= ','.$numberItem;
                        if (!str_contains($game->locknumberred,$numberItem))
                            $game->locknumberred .= ','.$numberItem;
                    }
                }
                HistoryHelpers::ActiveHistorySave($user,$user,"mở khoá số ". $game->name . " - mã ". $number,"");
                $game->save();
                
            }
        }
    }

    public static function LockNumber($gamecode){
        $game = null;
        if (Auth::user()->roleid == 2){
            //using dynamic class
            $gameTableId = 'App\Game_'.Auth::user()->id;
            $ref = new $gameTableId;
            if ($gamecode == 18)
            $game = $ref::where('game_code',7)->first();
            else $game = $ref::where('game_code',$gamecode)->first();
        }

        if (Auth::user()->roleid == 1){
            //$gamecode == 9 || $gamecode == 10 || $gamecode == 11 || 
            if ($gamecode == 18)
            $game = Game::where('game_code',7)->first();
            else $game = Game::where('game_code',$gamecode)->first();
        }
        
        return $game->locknumber.','.$game->locknumberauto;
    }

    public static function LockNumberRed($gamecode){
        $game = null;
        //
        if ($gamecode == 9 || $gamecode == 10 || $gamecode == 11 || $gamecode == 18 || $gamecode == 29
        || $gamecode == 16 || $gamecode == 19 || $gamecode == 20 ||  $gamecode == 21 
        || ($gamecode >= 31 && $gamecode <= 55) )
            $game = Game::where('game_code',7)->first();
        else $game = Game::where('game_code',$gamecode)->first();
        return isset($game->locknumberred) ? $game->locknumberred : "";
    }

    public static function GetSuperFromMember($user){
        $parentUser = User::where('id',$user->user_create)->first();
        if (!isset($parentUser)) return [];
        if($parentUser->roleid == 2) return $parentUser->id;
        else return GameHelpers::GetSuperFromMember($parentUser);
    }
    public static function LockNumberUser($gamecode,$user,$test=false){
        try{
            // $currentUser = Auth::user();
            // if ($test==true){
            //     $currentUser = User::where('id',1312)->first();
            //     // echo $currentUser->id;
            //     //00|1294,||10|||20|1294,||
            // }
            //User::where('id',1181)->first();
            //Auth::user();
            $super_id = static::GetSuperFromMember($user);
            $gameTableId = 'App\Game_'.$super_id;
            $ref = new $gameTableId;
            $game = null;
            //$gamecode == 9 || $gamecode == 10 || $gamecode == 11 || 
            if ($gamecode == 18)
                $game = $ref::where('game_code',7)->first();
            else if ($gamecode >= 31 && $gamecode <= 55)
                $game = $ref::where('game_code',24)->first();
            else
             $game = $ref::where('game_code',$gamecode)->first();

            $lockNumber = $game->locknumber.','.$game->locknumberauto;
            // $lockSuper = $game->locksuper;
            // $superU = GameHelpers::GetSuperFromMember($currentUser);
            // // echo $superU;
            // $arrLockNumber = explode("||",$lockSuper); // 00|1294,||10|||20|1294,||     00|1294,

            // foreach($arrLockNumber as $lockNumberItem){
            //     $arrSuperExp = explode("-",$lockNumberItem);
            //     // return $lockNumberItem;
            //     if (count($arrSuperExp) > 1 ){
            //         $numberItem = $arrSuperExp[0];
            //         $arrSuper = $arrSuperExp[1];
            //         if ($test==true){
            //             // echo $lockNumberItem;
            //         }
            //         // return $arrSuper . ' - '.$superU . ": ". (str_contains($superU,$arrSuper)?1:0);
            //         $arrSuperIDs = explode(",",$arrSuper);
            //         foreach($arrSuperIDs as $superID){
            //             if ($superID == $superU) {
            //                 // if (strpos($arrSuper,$superU) != false) {
            //                     // return $numberItem;
            //                     $lockNumber.= (','. $numberItem);
            //                 }
            //         }
                    
            //     }
            // }
            return $lockNumber;
        }catch(\Exception $ex){
            // echo $ex->getLine();
        }
        
    }

    // public static function LockNumberAuto($gamecode){
    //     $game = null;
    //     if ($gamecode == 9 || $gamecode == 10 || $gamecode == 11 || $gamecode == 18)
    //         $game = Game::where('game_code',7)->first();
    //     else $game = Game::where('game_code',$gamecode)->first();
    //     return $game->locknumberauto;
    // }

    public static function UpdateChildEX($currentUser,$currentgame_number,$changeValue,$exchangeAdmin=0)
    {
        // // \log::info($currentUser->name . ' '.$changeValue);
        if ($currentUser->roleid == 6)
            return;

        if ($exchangeAdmin==0){
            $game_code = $currentgame_number->code_type;
            if ($currentgame_number->code_type == 18) $game_code = 7;
            $gameTarget = GameHelpers::GetGameByCode($game_code);
            $exchangeAdmin = $gameTarget->exchange_rates;
        }
        
        $childrenUser = 
        // Cache::tags('User'.$currentUser->id)->remember('user_create'.'-'.$currentUser->id, env('CACHE_TIME', 0), function () use ($currentUser) {
            // return 
            User::where('user_create',$currentUser->id)
            ->where('active',0)
            ->where('per',0)
            ->orderBy('latestlogin', 'desc')
            ->get();
        // });
        
        $changeValuebu = $changeValue;
        foreach ($childrenUser as $userCus) {
            # code...
            $changeValue = $changeValuebu;
            $game_number = 
            // Cache::tags('Game_Number'.$userCus->id)->remember('Game_Number-'.'-'.$currentgame_number->code_type.'-'.$currentgame_number->number.'-'.$userCus->id, env('CACHE_TIME', 0), function () use ($currentgame_number,$userCus) {
                // return 
                Game_Number::where('code_type',$currentgame_number->code_type)
                ->where('number',$currentgame_number->number)
                // ->whereDate('updated_at', '=', date('Y-m-d'))
                ->where('userid', $userCus->id)
                ->first();
            // });

            
            if ($userCus->roleid == 6){
                $game = 
                Cache::remember('CustomerType_Game-'.$currentgame_number->code_type.'-'.$userCus->id, env('CACHE_TIME_SHORT', 0), function () use ($currentgame_number,$userCus) {
                    return 
                    CustomerType_Game::where('game_id',$currentgame_number->code_type)
                    ->where('created_user', $userCus->id)
                    ->first();
                });

                if ($currentgame_number->code_type==14 ||
                $currentgame_number->code_type==25 ||
                $currentgame_number->code_type==26 ||
                $currentgame_number->code_type==27 ||
                $currentgame_number->code_type==28 || 
                $currentgame_number->code_type==12 || 
                ($currentgame_number->code_type>=31 &&  $currentgame_number->code_type<=55) ||
                $currentgame_number->code_type== 24
                ){
                    if ($userCus->customer_type == 'B') //A*8/7
                    // $newbet = round($newbet, 0, PHP_ROUND_HALF_UP);
                        $changeValue = round($changeValue*8/7, 0, PHP_ROUND_HALF_UP);
                    if ($userCus->customer_type == 'C') //A*9/7
                        $changeValue = round($changeValue*9/7, 0, PHP_ROUND_HALF_UP);
                    if ($userCus->customer_type == 'D') //A*95/70
                        $changeValue = round($changeValue*95/70, 0, PHP_ROUND_HALF_UP);

                    // $changeValue = round($changeValue, 0, PHP_ROUND_HALF_UP);
                }

                // old
                // if ($currentgame_number->code_type==14 ||
                // $currentgame_number->code_type==25 ||
                // $currentgame_number->code_type==26 ||
                // $currentgame_number->code_type==27 ||
                // $currentgame_number->code_type==28 || $currentgame_number->code_type==12){
                //     if ($userCus->customer_type == 'B')
                //         $changeValue+=101;
                //     if ($userCus->customer_type == 'C')
                //         $changeValue+=202;
                // }

                if ($currentgame_number->code_type==9
                || $currentgame_number->code_type==309
                || $currentgame_number->code_type==409
                || $currentgame_number->code_type==509
                || $currentgame_number->code_type==609
                || $currentgame_number->code_type==709){
                    if ($userCus->customer_type == 'D')
                        $changeValue = round($changeValue*1.6, 0, PHP_ROUND_HALF_UP);
                }

                if ($currentgame_number->code_type==10
                || $currentgame_number->code_type==310
                || $currentgame_number->code_type==410
                || $currentgame_number->code_type==510
                || $currentgame_number->code_type==610
                || $currentgame_number->code_type==710){
                    if ($userCus->customer_type == 'D')
                        $changeValue = round($changeValue*1.75, 0, PHP_ROUND_HALF_UP);
                }

                if ($currentgame_number->code_type==11
                || $currentgame_number->code_type==311
                || $currentgame_number->code_type==411
                || $currentgame_number->code_type==511
                || $currentgame_number->code_type==611
                || $currentgame_number->code_type==711){
                    if ($userCus->customer_type == 'D')
                        $changeValue = round($changeValue*2, 0, PHP_ROUND_HALF_UP);
                }

                if ($currentgame_number->code_type==16
                || $currentgame_number->code_type==316 || $currentgame_number->code_type==416 || $currentgame_number->code_type==516 || $currentgame_number->code_type==616
                || $currentgame_number->code_type==19
                || $currentgame_number->code_type==20 || $currentgame_number->code_type==21){
                    if ($userCus->customer_type == 'B')
                        $changeValue+=10000;
                    if ($userCus->customer_type == 'C')
                        $changeValue+=20000;
                    if ($userCus->customer_type == 'D')
                        $changeValue+=20000;
                }
            }else
                $game = 
                Cache::remember('CustomerType_Game-'.$currentgame_number->code_type.'-A'.'-'.$userCus->id, env('CACHE_TIME_SHORT', 0), function () use ($currentgame_number,$userCus) {
                    return 
                    CustomerType_Game_Original::where('game_id',$currentgame_number->code_type)
                    ->where('created_user', $userCus->id)
                    ->where('code_type', 'A')
                    ->first();
                });
                
            //Game::where('game_code',$currentgame_number->code_type)
                // ->first();
            if ($game == null)
                continue;
            if(count($game_number)>0)
            {
                if($game_number->exchange_rates < $game->exchange_rates)
                    $game_number->exchange_rates = $game->exchange_rates;

                $game_number->a = $currentgame_number->a;
                $game_number->x = $currentgame_number->x;
                $game_number->y = 0;
                $game_number->userid = $userCus->id;
                if($game_number->exchange_rates != $changeValue)
                {
                    $game_number->exchange_rates = $changeValue;
                    // try{
                    //     // $maxBetGame = 99999999;
                    //     // $maxBetOne = 99999999;
                    //     // $custome_type = GameHelpers::GetGameParentByCusTypeGameid($userCus->customer_type,$userCus->id,$currentgame_number->code_type);
                    //     // $maxBetGame = $custome_type->max_point; //b
                    //     // $maxBetOne = $custome_type->max_point_one;

                    //     // //a1
                    //     // $totalBettodayOne = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($currentgame_number->code_type,$currentgame_number->number,$userCus)[1];
                    //     // if ($totalBettodayOne > $maxBetGame/5)
                    //     $game_number->save();
                    // }catch(\Exception $err){
                    //     //// \log::error('error '.$err->getFile().'-'.$err->getMessage().'-'.$err->getLine());
                    //     $game_number->save();
                    // }
                    
                    $game_number->save();
                    // Cache::tags('Game_Number'.$userCus->id)->forget('Game_Number-'.'-'.$currentgame_number->code_type.'-'.$currentgame_number->number.'-'.$userCus->id);
                    // Cache::tags('Game_Number'.$userCus->id)->forget('GetGame_AllNumber-'.$currentgame_number->code_type.'-'.$userCus->id);
                    // if($userCus->roleid <= 5)
                    //     Queue::pushOn("medium",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else if($userCus->roleid == 6)
                    //         Queue::pushOn("high",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else
                        GameHelpers::UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin);
                    
                }
                
            }else
            {
                $game_number = new Game_Number;
                    // $game_number->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                $game_number->a = $currentgame_number->a;
                $game_number->x = $currentgame_number->x;
                $game_number->y = 0;
                $game_number->number = $currentgame_number->number;
                $game_number->code_type = $currentgame_number->code_type;
                $game_number->userid = $userCus->id;

                if($game->exchange_rates < $changeValue)
                {
                    // $game_number = new Game_Number;
                    $game_number->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                    // $game_number->a = $currentgame_number->a;
                    // $game_number->x = $currentgame_number->x;
                    // $game_number->y = 0;
                    // $game_number->number = $currentgame_number->number;
                    // $game_number->code_type = $currentgame_number->code_type;
                    // $game_number->userid = $userCus->id;
                    // try{
                    //     $maxBetGame = 99999999;
                    //     $maxBetOne = 99999999;
                    //     $custome_type = GameHelpers::GetGameParentByCusTypeGameid($userCus->customer_type,$userCus->id,$currentgame_number->code_type);
                    //     $maxBetGame = $custome_type->max_point; //b
                    //     $maxBetOne = $custome_type->max_point_one;

                    //     //a1
                    //     $totalBettodayOne = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($currentgame_number->code_type,$currentgame_number->number,$userCus)[1];
                    //     if ($totalBettodayOne > $maxBetGame/5)
                    //         $game_number->save();
                    // }catch(\Exception $err){
                    //     //// \log::error('error '.$err->getFile().'-'.$err->getMessage().'-'.$err->getLine());
                    //     $game_number->save();
                    // }
                    // Cache::tags('Game_Number'.$userCus->id)->forget('Game_Number-'.'-'.$currentgame_number->code_type.'-'.$currentgame_number->number.'-'.$userCus->id);
                    // Cache::tags('Game_Number'.$userCus->id)->forget('GetGame_AllNumber-'.$currentgame_number->code_type.'-'.$userCus->id);
                    $game_number->save();
                    // if($userCus->roleid <= 5)
                    //     Queue::pushOn("medium",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else 
                    // if($userCus->roleid == 6)
                    //         Queue::pushOn("high",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else
                    GameHelpers::UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin);
                }  
            }
        }     
    }

    public static function UpdateChildEXv2($currentUser,$currentgame_number,$changeValue,$exchangeAdmin=0,$lockPrice=0)
    {
        // // \log::info($currentUser->name . ' '.$changeValue);
        if ($currentUser->roleid == 6)
            return;

        if ($exchangeAdmin==0){
            $game_code = $currentgame_number->code_type;
            if ($currentgame_number->code_type == 18) $game_code = 7;
            $gameTarget = GameHelpers::GetGameByCode($game_code);
            $exchangeAdmin = $gameTarget->exchange_rates;
        }
        
        if (($currentgame_number->code_type == 7 || $currentgame_number->code_type == 14 ||$currentgame_number->code_type == 12)){
            $childrenUser = 
            // User::where('user_create',$currentUser->id)
            // ->where('active',0)
            // ->where('lock_price',$lockPrice)
            // ->where('per',0)
            // ->where('roleid','<',6)
            // ->orderBy('latestlogin', 'desc')
            // ->get();
            Cache::remember('childrenUser-'.$currentUser->id."-".$lockPrice, env('CACHE_TIME_SHORT', 0), function () use ($currentUser,$lockPrice) {
                return 
                User::where('user_create',$currentUser->id)
                ->where('active',0)
                ->where('lock_price',$lockPrice)
                ->where('per',0)
                ->where('roleid','<',6)
                ->orderBy('latestlogin', 'desc')
                ->get();
            });
        }else{
            // $lockPrice = 0;
            $childrenUser = 
            // User::where('user_create',$currentUser->id)
            // ->where('active',0)
            // ->whereIn('lock_price', [0,2])
            // ->where('per',0)
            // ->where('roleid','<',6)
            // ->orderBy('latestlogin', 'desc')
            // ->get();
            Cache::remember('childrenUser-'.$currentUser->id, env('CACHE_TIME_SHORT', 0), function () use ($currentUser,$lockPrice) {
                return 
                User::where('user_create',$currentUser->id)
                ->where('active',0)
                ->whereIn('lock_price', [0,2])
                ->where('per',0)
                ->where('roleid','<',6)
                ->orderBy('latestlogin', 'desc')
                ->get();
            });
        }
        

       
        
        $lstUser = [];
            foreach ($childrenUser as $user){
                $lstUser[] = $user->id;
            }

        if ($lstUser == []){
            Log::info("loi len gia lstUser". $currentUser->id . " " . $lockPrice);
            return;
        }

        $game_numbers =
                Game_Number::where('code_type', $currentgame_number->code_type)
                ->where('number', $currentgame_number->number)
                ->whereDate('updated_at', '=', date('Y-m-d'))
                // ->where('userid', $user->id)
                ->whereIn('userid', $lstUser)
                ->get();

        $games = 
                Cache::remember('CustomerType_Game_Original-'.$currentgame_number->code_type.'-A'.'-'.$currentUser->id. " " . $lockPrice, env('CACHE_TIME_SHORT', 0), function () use ($currentgame_number,$lstUser) {
                 return CustomerType_Game_Original::where('game_id',$currentgame_number->code_type)
                        // ->where('created_user', $userCus->id)
                        ->whereIn('created_user', $lstUser)
                        ->where('code_type', 'A')
                        ->get();
                });

        $changeValuebu = $changeValue;
        foreach ($childrenUser as $userCus) {
            if ($userCus->roleid == 6){
                continue;
            }
            // $game_number = 
            // // Cache::tags('Game_Number'.$userCus->id)->remember('Game_Number-'.'-'.$currentgame_number->code_type.'-'.$currentgame_number->number.'-'.$userCus->id, env('CACHE_TIME', 0), function () use ($currentgame_number,$userCus) {
            //     // return 
            //     Game_Number::where('code_type',$currentgame_number->code_type)
            //     ->where('number',$currentgame_number->number)
            //     // ->whereDate('updated_at', '=', date('Y-m-d'))
            //     ->where('userid', $userCus->id)
            //     ->first();
            // // });

            $game_number = XoSoRecordHelpers::array_find($userCus->id,$game_numbers);
            
            $game = XoSoRecordHelpers::array_find($userCus->id,$games,"created_user");

            if ($game == null) {
                Log::info("loi len gia game". $userCus->id . " " . $lockPrice);
                continue;
            }
            // Cache::remember('CustomerType_Game-'.$currentgame_number->code_type.'-A'.'-'.$userCus->id, env('CACHE_TIME_SHORT', 0), function () use ($currentgame_number,$userCus) {
            //     return 
            //     CustomerType_Game_Original::where('game_id',$currentgame_number->code_type)
            //     ->where('created_user', $userCus->id)
            //     ->where('code_type', 'A')
            //     ->first();
            // });
                
            //Game::where('game_code',$currentgame_number->code_type)
                // ->first();

                # code...
            if ($userCus->roleid == 2){
                $changeValue = $exchangeAdmin + ($changeValuebu-$exchangeAdmin)/$game->ratio_ex;
                $changeValue = (int)$changeValue;
                if ($changeValue > $game->max_ex && $game->max_ex != 0) $changeValue = $game->max_ex;
            }else
                $changeValue = $changeValuebu;

            if($game_number != null)
            {
                if($game_number->exchange_rates < $game->exchange_rates)
                    $game_number->exchange_rates = $game->exchange_rates;

                $game_number->a = $currentgame_number->a;
                $game_number->x = $currentgame_number->x;
                $game_number->y = 0;
                $game_number->userid = $userCus->id;
                if($game_number->exchange_rates != $changeValue)
                {
                    $game_number->exchange_rates = $changeValue;
                    // try{
                    //     // $maxBetGame = 99999999;
                    //     // $maxBetOne = 99999999;
                    //     // $custome_type = GameHelpers::GetGameParentByCusTypeGameid($userCus->customer_type,$userCus->id,$currentgame_number->code_type);
                    //     // $maxBetGame = $custome_type->max_point; //b
                    //     // $maxBetOne = $custome_type->max_point_one;

                    //     // //a1
                    //     // $totalBettodayOne = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($currentgame_number->code_type,$currentgame_number->number,$userCus)[1];
                    //     // if ($totalBettodayOne > $maxBetGame/5)
                    //     $game_number->save();
                    // }catch(\Exception $err){
                    //     //// \log::error('error '.$err->getFile().'-'.$err->getMessage().'-'.$err->getLine());
                    //     $game_number->save();
                    // }
                    $game_number->save();
                    // Cache::tags('Game_Number'.$userCus->id)->forget('Game_Number-'.'-'.$currentgame_number->code_type.'-'.$currentgame_number->number.'-'.$userCus->id);
                    // Cache::tags('Game_Number'.$userCus->id)->forget('GetGame_AllNumber-'.$currentgame_number->code_type.'-'.$userCus->id);
                    // if($userCus->roleid <= 5)
                    //     Queue::pushOn("medium",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else if($userCus->roleid == 6)
                    //         Queue::pushOn("high",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else
                    Queue::pushOn("medium",new UpdateChildEX_NonMember($userCus,$game_number,$changeValue,$exchangeAdmin,$lockPrice));
                    // GameHelpers::UpdateChildEXv2($userCus,$game_number,$changeValue,$exchangeAdmin);
                    
                }
                
            }else
            {
                $game_number = new Game_Number;
                    // $game_number->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                $game_number->a = $currentgame_number->a;
                $game_number->x = $currentgame_number->x;
                $game_number->y = 0;
                $game_number->number = $currentgame_number->number;
                $game_number->code_type = $currentgame_number->code_type;
                $game_number->userid = $userCus->id;

                if($game->exchange_rates < $changeValue)
                {
                    // $game_number = new Game_Number;
                    $game_number->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                    // $game_number->a = $currentgame_number->a;
                    // $game_number->x = $currentgame_number->x;
                    // $game_number->y = 0;
                    // $game_number->number = $currentgame_number->number;
                    // $game_number->code_type = $currentgame_number->code_type;
                    // $game_number->userid = $userCus->id;
                    // try{
                    //     $maxBetGame = 99999999;
                    //     $maxBetOne = 99999999;
                    //     $custome_type = GameHelpers::GetGameParentByCusTypeGameid($userCus->customer_type,$userCus->id,$currentgame_number->code_type);
                    //     $maxBetGame = $custome_type->max_point; //b
                    //     $maxBetOne = $custome_type->max_point_one;

                    //     //a1
                    //     $totalBettodayOne = XoSoRecordHelpers::TotalBetTodayByNumberThauByUser($currentgame_number->code_type,$currentgame_number->number,$userCus)[1];
                    //     if ($totalBettodayOne > $maxBetGame/5)
                    //         $game_number->save();
                    // }catch(\Exception $err){
                    //     //// \log::error('error '.$err->getFile().'-'.$err->getMessage().'-'.$err->getLine());
                    //     $game_number->save();
                    // }
                    // Cache::tags('Game_Number'.$userCus->id)->forget('Game_Number-'.'-'.$currentgame_number->code_type.'-'.$currentgame_number->number.'-'.$userCus->id);
                    // Cache::tags('Game_Number'.$userCus->id)->forget('GetGame_AllNumber-'.$currentgame_number->code_type.'-'.$userCus->id);
                    $game_number->save();
                    // if($userCus->roleid <= 5)
                    //     Queue::pushOn("medium",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else 
                    // if($userCus->roleid == 6)
                    //         Queue::pushOn("high",new UpdateChildEX($userCus,$game_number,$changeValue,$exchangeAdmin));
                    // else
                    Queue::pushOn("medium",new UpdateChildEX_NonMember($userCus,$game_number,$changeValue,$exchangeAdmin,$lockPrice));
                    // GameHelpers::UpdateChildEXv2($userCus,$game_number,$changeValue,$exchangeAdmin);
                }  
            }
        }     
    }

    public static function UpdateMeFromParentEX($currentUser,$usercheckparent)
    {
        if ($usercheckparent->roleid == 2)
            return 0;
        if ($currentUser->roleid == 6){
            $parentUser = User::where('id',$usercheckparent->user_create)->first();
            if ($parentUser->roleid != 2){
                return GameHelpers::UpdateMeFromParentEX($currentUser,$parentUser);
            }
            $game_number_parent = Game_Number::
            // where('code_type',$currentUser->customer_type)
                    // ->where('number',$currentgame_number->number)
                    where('userid', $parentUser->id)
                    ->whereDate('updated_at', '=', date('Y-m-d'))
                    ->get();

            $games = 
                Cache::remember('CustomerType_Game-'.$currentUser->customer_type.'-'.$currentUser->id, env('CACHE_TIME_SHORT', 0), function () use ($currentUser) {
                    return CustomerType_Game::where('code_type', $currentUser->customer_type)
                        ->where('created_user', $currentUser->id)
                        ->get();
                });

            foreach ($game_number_parent as $game_number) {
                
                $game = XoSoRecordHelpers::array_find($game_number->code_type,$games,"game_id");
                // CustomerType_Game::where('game_id',$game_number->code_type)
                //     ->where('code_type', $currentUser->customer_type)
                //     ->where('created_user', $currentUser->id)
                //     ->first();

                if ($game_number->code_type==14 ||
                $game_number->code_type==27 ||
                $game_number->code_type==28 || $game_number->code_type==12){
                    if ($currentUser->customer_type == 'B')
                        $game_number->exchange_rates+=101;
                    if ($currentUser->customer_type == 'C')
                        $game_number->exchange_rates+=202;
                    // if ($currentUser->customer_type == 'D')
                    //     $game_number->exchange_rates+=202;
                }

                if ($currentUser->customer_type == 'D')
                switch ($game_number->code_type) {
                    case 14: $game_number->exchange_rates+= 253; break;
                    case 9:  $game_number->exchange_rates+= 340; break;
                    case 10: $game_number->exchange_rates+= 390; break;
                    case 11: $game_number->exchange_rates+= 450; break;
                    case 12: $game_number->exchange_rates+= 253; break;
                    case 25: $game_number->exchange_rates+= 253; break;
                    case 26: $game_number->exchange_rates+= 253; break;
                    case 27: $game_number->exchange_rates+= 253; break;
                    case 28: $game_number->exchange_rates+= 253; break;
                    case 24: $game_number->exchange_rates+= 263; break;
                    default:
                        # code...
                        break;
                }

                if ($game_number->exchange_rates < $game->exchange_rates)
                    $game_number->exchange_rates = $game->exchange_rates;

                $changeValue = $game_number->exchange_rates;

                {
                    // if ($game_number->exchange_rates < $game->exchange_rates)
                    //     $game_number->exchange_rates = $game->exchange_rates

                    if($game->exchange_rates != $changeValue)
                    {
                        $game_numberCurrent = Game_Number::
                            where('code_type',$game_number->code_type)
                            ->where('number',$game_number->number)
                            ->where('userid', $currentUser->id)
                            ->whereDate('updated_at', '=', date('Y-m-d'))
                            ->first();
                        if (isset($game_numberCurrent)){
                            $game_numberCurrent->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                        }else{
                            $game_numberCurrent = new Game_Number;
                            $game_numberCurrent->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                            $game_numberCurrent->a = $game_number->a;
                            $game_numberCurrent->x = $game_number->x;
                            $game_numberCurrent->y = 0;
                            $game_numberCurrent->number = $game_number->number;
                            $game_numberCurrent->code_type = $game_number->code_type;
                            $game_numberCurrent->userid = $currentUser->id;
                        }
                        
                        $game_numberCurrent->save();
                        //GameHelpers::UpdateChildEX($userCus,$game_number,$changeValue);
                    }
                }
            }
            return GameHelpers::UpdateMeFromParentEX($currentUser,$parentUser);
        }else{
            $parentUser = User::where('id',$usercheckparent->user_create)->first();
            $game_number_parent = Game_Number::
            //where('code_type',$currentUser->customer_type)
                    // ->where('number',$currentgame_number->number)
                    where('userid', $parentUser->id)
                    ->whereDate('updated_at', '=', date('Y-m-d'))
                    ->get();

                    // $games = CustomerType_Game_Original::where('created_user', $currentUser->id)
                    // ->where('code_type', 'A')
                    // ->get();

                    $games = 
                Cache::remember('CustomerType_Game_Original-'.'A'.'-'.$currentUser->id, env('CACHE_TIME_SHORT', 0), function () use ($currentUser) {
                    return CustomerType_Game_Original::where('created_user', $currentUser->id)
                    ->where('code_type', 'A')
                    ->get();
                });

            foreach ($game_number_parent as $game_number) {
                $game = XoSoRecordHelpers::array_find($game_number->code_type,$games,"game_id");
                // CustomerType_Game_Original::where('game_id',$game_number->code_type)
                //     ->where('created_user', $currentUser->id)
                //     ->where('code_type', 'A')
                //     ->first();
                if ($game_number->exchange_rates < $game->exchange_rates)
                    $game_number->exchange_rates = $game->exchange_rates;
                $changeValue = $game_number->exchange_rates;

                if ($game_number->code_type==14 ||
                $game_number->code_type==25 ||
                $game_number->code_type==26 ||
                $game_number->code_type==27 ||
                $game_number->code_type==28 || $game_number->code_type==12){
                    if ($game->code_type == 'B')
                        $changeValue+=101;
                    if ($game->code_type == 'C')
                        $changeValue+=202;
                    if ($game->code_type == 'D')
                        $game_number->exchange_rates+=202;
                }

                {
                    if($game->exchange_rates != $changeValue)
                    {
                        $game_numberCurrent = new Game_Number;
                        $game_numberCurrent->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                        $game_numberCurrent->a = $game_number->a;
                        $game_numberCurrent->x = $game_number->x;
                        $game_numberCurrent->y = 0;
                        $game_numberCurrent->number = $game_number->number;
                        $game_numberCurrent->code_type = $game_number->code_type;
                        $game_numberCurrent->userid = $currentUser->id;
                        $game_numberCurrent->save();
                        //GameHelpers::UpdateChildEX($userCus,$game_number,$changeValue);
                    }
                }
            }
            return GameHelpers::UpdateMeFromParentEX($currentUser,$parentUser);
        }
        return 0;
    }

    public static function UpdateMeFromParentEX6($currentUser,$usercheckparent,$gamecode,$number)
    {
        if ($usercheckparent->roleid == 2)
            return 0;
        if ($currentUser->roleid == 6){
            $parentUser = User::where('id',$usercheckparent->user_create)->first();
            echo $usercheckparent->user_create . PHP_EOL;
            if ($parentUser->roleid != 2){
                return GameHelpers::UpdateMeFromParentEX6($currentUser,$parentUser,$gamecode,$number);
            }
            $game_number_parent = Game_Number::
                    where('code_type',$gamecode)
                    ->where('number',$number)
                    ->where('userid', $parentUser->id)
                    ->whereDate('updated_at', '=', date('Y-m-d'))
                    ->get();
            // $game = CustomerType_Game::where('game_id',$gamecode)
            //         ->where('code_type', $currentUser->customer_type)
            //         ->where('created_user', $currentUser->id)
            //         ->groupBy('game_id')
            //         ->get();

            $game = 
                Cache::remember('CustomerType_Game-'.$gamecode.'-'.$currentUser->customer_type.'-'.$currentUser->id, env('CACHE_TIME_SHORT', 0), function () use ($gamecode,$currentUser) {
                    return CustomerType_Game::where('game_id',$gamecode)
                    ->where('code_type', $currentUser->customer_type)
                    ->where('created_user', $currentUser->id)
                    ->first();
                });

            if (!isset($game)) return;
            // $game = CustomerType_Game::where('game_id',$gamecode)
            // ->where('code_type', $currentUser->customer_type)
            // ->where('created_user', $currentUser->id)
            // ->first();
            if (count($game_number_parent) < 1){
                $changeValue = $game->exchange_rates;
                echo "gia thay doi theo chuan " . $changeValue . PHP_EOL;
                {
                    // if ($game_number->exchange_rates < $game->exchange_rates)
                    //     $game_number->exchange_rates = $game->exchange_rates

                    // if($game->exchange_rates != $changeValue)
                    {
                        $game_number_user = 
                            Game_Number::where('code_type',$gamecode)
                            ->where('number',$number)
                            // ->whereDate('updated_at', '=', date('Y-m-d'))
                            ->where('userid', $currentUser->id)
                            ->first();
                            // print_r($game_number);
                        // if (isset($game_number))
                        if(count($game_number_user)>0)
                        {
                            $game_number_user->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                            $game_number_user->save();
                        }else{
                            $game_numberCurrent = new Game_Number;
                            $game_numberCurrent->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                            $game_numberCurrent->a = 1;
                            $game_numberCurrent->x = 1;
                            $game_numberCurrent->y = 0;
                            $game_numberCurrent->number = $number;
                            $game_numberCurrent->code_type = $gamecode;
                            $game_numberCurrent->userid = $currentUser->id;
                            $game_numberCurrent->save();
                        }
                    }
                }
            }else
            foreach ($game_number_parent as $game_number) {
                // $gameItem = $game[(int)$game_number->code_type];
                if ($game_number->code_type==14 ||
                $game_number->code_type==27 ||
                $game_number->code_type==28 || $game_number->code_type==12
                || ($game_number->code_type>=31 && $game_number->code_type<=55)
                || $game_number->code_type==24
                || $game_number->code_type==25 ||$game_number->code_type==26
                ){
                    if ($currentUser->customer_type == 'B')
                        $game_number->exchange_rates+=101;
                    if ($currentUser->customer_type == 'C')
                        $game_number->exchange_rates+=202;
                    // if ($currentUser->customer_type == 'D')
                    //     $game_number->exchange_rates+=202;
                }

                if ($currentUser->customer_type == 'D'){
                    switch ($game_number->code_type) {
                        case 14: $game_number->exchange_rates+= 253; break;
                        case 9:  $game_number->exchange_rates+= 340; break;
                        case 10: $game_number->exchange_rates+= 390; break;
                        case 11: $game_number->exchange_rates+= 450; break;
                        case 12: $game_number->exchange_rates+= 253; break;
                        case 25: $game_number->exchange_rates+= 253; break;
                        case 26: $game_number->exchange_rates+= 253; break;
                        case 27: $game_number->exchange_rates+= 253; break;
                        case 28: $game_number->exchange_rates+= 253; break;
                        case 24: $game_number->exchange_rates+= 263; break;
                        default:
                            # code...
                            break;
                    }
                    if (($game_number->code_type >= 31 && $game_number->code_type <= 55) || $game_number->code_type == 24){
                        $game_number->exchange_rates+= 253;
                    }
                }

                if ($game_number->exchange_rates < $game->exchange_rates)
                    $game_number->exchange_rates = $game->exchange_rates;

                $changeValue = $game_number->exchange_rates;
                echo "gia thay doi theo gamenumber " . $changeValue . PHP_EOL;
                {
                    // if ($game_number->exchange_rates < $game->exchange_rates)
                    //     $game_number->exchange_rates = $game->exchange_rates

                    // if($game_number->exchange_rates != $changeValue)
                    {
                        $game_number_user = 
                            Game_Number::where('code_type',$game_number->code_type)
                            ->where('number',$game_number->number)
                            // ->whereDate('updated_at', '=', date('Y-m-d'))
                            ->where('userid', $currentUser->id)
                            ->first();
                            // print_r($game_number);
                        // if (isset($game_number))
                        if(count($game_number_user)>0)
                        {
                            if($game_number_user->exchange_rates != $changeValue){
                                $game_number_user->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                                $game_number_user->save();
                            }
                        }else{
                            if($game->exchange_rates != $changeValue){
                                $game_numberCurrent = new Game_Number;
                                $game_numberCurrent->exchange_rates = $changeValue; //$game->exchange_rates + $changeValue;
                                $game_numberCurrent->a = $game_number->a;
                                $game_numberCurrent->x = $game_number->x;
                                $game_numberCurrent->y = 0;
                                $game_numberCurrent->number = $game_number->number;
                                $game_numberCurrent->code_type = $game_number->code_type;
                                $game_numberCurrent->userid = $currentUser->id;
                                $game_numberCurrent->save();
                            }
                        }
                    }
                }
            }
        }
        return 0;
    }

    public static function GetGameByCode($game_code)
    {
        return 
        Cache::remember('GetGameByCode-'.$game_code, env('CACHE_TIME_PRICE', 0), function () use ($game_code) {
            return 
            Game::join('location', 'games.location_id', '=', 'location.id')
            ->select('games.*','location.name as location','location.slug as locationslug')
            ->where('game_code',$game_code)->first();
        });
    }

    public static function GetAllGame($locationId=0)
    {
        if ($locationId==0)
            return Game::
            join('location', 'games.location_id', '=', 'location.id')
            ->where('games.active',1)
            ->select('games.*','location.name as location','location.slug as locationslug')
            ->orderBy('order')
                ->get();
        else
            return Game::
            join('location', 'games.location_id', '=', 'location.id')
            ->where('games.active',1)
            ->where('location_id', intval($locationId))
            ->select('games.*','location.name as location','location.slug as locationslug')
            ->orderBy('order')
                ->get();
    }

    public static function GetAllGameControlAutoPrice($locationId=0)
    {
        if ($locationId==0)
            return Game::
            join('location', 'games.location_id', '=', 'location.id')
            ->where('games.active',1)
            ->select('games.name','games.id','games.order','games.game_code','games.y','games.a2','location.name as location','location.slug as locationslug')
            ->orderBy('order')
                ->get();
        else
            return Game::
            join('location', 'games.location_id', '=', 'location.id')
            ->where('games.active',1)
            ->where('location_id', intval($locationId))
            ->select('games.name','games.id','games.order','games.game_code','games.y','games.a2','location.name as location','location.slug as locationslug')
            ->orderBy('order')
                ->get();
    }

    public static function GetGameByNumber($game_code,$number)
    {
        $game_number = Game_Number::where('code_type',$game_code)
            ->where('number',$number)
            ->whereDate('updated_at', '=', date('Y-m-d'))
            ->first();
        $game = Game::where('game_code',$game_code)
            ->first();
        if(count($game_number)>0)
        {
            return $game_number;
        }
        else
            return $game;
    }
    public function GetGameListByParentID($parentID,$userid)
    {
        if (!is_numeric($parentID)) {
            return [];
        }
        return Game::where('active', 1)
            ->where('parent_id', intval($parentID))
            ->join('customer_type_game', 'customer_type_game.game_id', '=', 'games.game_code')
            ->join('users', 'users.customer_type', '=', 'customer_type_game.code_type')
            ->where('users.id',$userid)
            ->select('games.*','customer_type_game.exchange_rates as exchange_rates')
            ->get();
    }
    public static function CheckExchange($game_code,$number,$exchange)
    {
        if ($exchange < 300)
            return false;
        return true;
        $user = Auth::user();
        $game_number = Game_Number::where('code_type',$game_code)
            ->where('number',$number)
            ->where('userid',$user->id)
            ->whereDate('updated_at', '=', date('Y-m-d'))
            ->first();
        
        $exchange = bcadd(str_replace(',', '',$exchange),'0',2);

        if(count($game_number)>0)
        {
            if($game_number->exchange_rates != $exchange)
            return false;
        }
        else
        {
            $cusgame = GameHelpers::GetOneGameByCusType($user->customer_type,$user->id,$game_code);
            if($cusgame->exchange_rates > $exchange)
            return false;

            // $game = Game::where('game_code',$game_code)
            // ->first();
            // if($game->exchange_rates > $exchange)
            //     return false;
        }
        return true;
    }

    public static function GetAllGameByCusType($custype,$user_id,$locationId=0)
    {
        if ($user_id == 274){
            if ($locationId!=0)
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)
                ->where('games.location_id', intval($locationId))
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            else 
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)    
                // ->where('games.location_id', intval($locationId))
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            return $result;
        }else{
            if ($locationId!=0)
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)
                ->where('games.location_id', intval($locationId))
                ->select('games.game_code as game_code','games.name as game_name','games.short_name as short_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            else 
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)    
                // ->where('games.location_id', intval($locationId))
                ->select('games.game_code as game_code','games.name as game_name','games.short_name as short_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            return $result;
        }
        
    }

    public static function GetAllGameByCusTypeSuper($custype,$user_id,$locationId=0)
    {
        if ($user_id == 274){
            if ($locationId!=0)
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)
                ->where('games.location_id', intval($locationId))
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            else 
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)    
                // ->where('games.location_id', intval($locationId))
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            return $result;
        }else{
            if ($locationId!=0)
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games_'.$user_id, 'games_'.$user_id.'.game_code', '=', 'customer_type_game.game_id')
                ->where('games_'.$user_id.'.active',true)
                ->where('games_'.$user_id.'.location_id', intval($locationId))
                ->select('games_'.$user_id.'.*','games_'.$user_id.'.game_code as game_code','games_'.$user_id.'.name as game_name','games_'.$user_id.'.short_name as short_name','customer_type_game.*')
                ->orderBy('games_'.$user_id.'.order','asc')
                ->get();
            else 
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games_'.$user_id.'', 'games_'.$user_id.'.game_code', '=', 'customer_type_game.game_id')
                ->where('games_'.$user_id.'.active',true)    
                // ->where('games.location_id', intval($locationId))
                ->select('games_'.$user_id.'.*','games_'.$user_id.'.game_code as game_code','games_'.$user_id.'.name as game_name','games_'.$user_id.'.short_name as short_name','customer_type_game.*')
                ->orderBy('games_'.$user_id.'.order','asc')
                ->get();
            return $result;
        }
        
    }

    public static function GetAllGameByCusTypeAPI($custype,$user_id,$locationId=0)
    {
        if ($user_id == 274){
            if ($locationId!=0)
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)
                ->where('games.location_id', intval($locationId))
                ->select('games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            else 
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)    
                // ->where('games.location_id', intval($locationId))
                ->select('games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            return $result;
        }else{
            if ($locationId!=0)
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)
                ->where('games.location_id', intval($locationId))
                ->select('games.game_code as game_code','games.name as game_name','customer_type_game.*')
                ->orderBy('games.order','asc')
                ->get();
            else 
                $result =  CustomerType_Game::where('code_type',$custype)
                ->where('created_user',$user_id)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('games.active',true)    
                // ->where('games.location_id', intval($locationId))
                ->select('games.game_code as game_code','games.name as game_name','customer_type_game.code_type as customer_type', 'customer_type_game.exchange_rates','customer_type_game.odds','customer_type_game.max_point','customer_type_game.max_point_one','customer_type_game.change_max_one')
                ->orderBy('games.order','asc')
                ->get();
            return $result;
        }
        
    }

    public static function GetOneGameByCusType($custype,$user_id,$game_code)
    {
        // echo $custype.','.$user_id.','.$game_code.'-';
        $result =  CustomerType_Game::where('code_type',$custype)
            ->where('created_user','=',$user_id)
            ->where('game_id','=',$game_code)
            ->first();
        // var_dump($result);
        return $result;
    }

    public static function GetOneGameParentByCusType($custype,$user_id,$game_code)
    {
        $result =  
        Cache::remember('GetOneGameParentByCusType-'.$user_id.'-'.$custype.'-'.$game_code, env('CACHE_TIME_SHORT', 0), function () use ($custype,$user_id,$game_code) {
            return
             CustomerType_Game_Original::where('code_type',$custype)
            ->where('created_user','=',$user_id)
            ->where('game_id','=',$game_code)
            ->first();
        });
        return $result;
    }

    public static function GetAllGameCus($user_id)
    {
        $result =  CustomerType_Game::where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
            ->where('games.active',true)
            ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.*')
            ->orderBy('games.order','asc')
            ->get();
        return $result;
    }

    public static function GetAllGameParentCus($user_id)
    {
        $result =  CustomerType_Game_Original::where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game_original.game_id')
            ->where('games.active',true)
            ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game_original.*')
            ->orderBy('games.order','asc')
            ->get();
        return $result;
    }
    
    public static function GetAllGameParentByCusType($custype,$user_id,$locationId=0)
    {
        if ($locationId == 0)
        $result =  
        // Cache::tags('CustomerType_Game_Original-'.$user_id)->remember('GetAllGameParentByCusType-'.$user_id.'-'.$custype, env('CACHE_TIME', 0), function () use ($custype,$user_id) {
            // return 
            CustomerType_Game_Original::where('code_type',$custype)
            ->where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game_original.game_id')
            ->where('games.active',true)
            ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game_original.*')
            ->orderBy('games.order','asc')
            ->get();
        else
        $result =  
        // Cache::tags('CustomerType_Game_Original-'.$user_id)->remember('GetAllGameParentByCusType-'.$user_id.'-'.$custype, env('CACHE_TIME', 0), function () use ($custype,$user_id) {
            // return 
            CustomerType_Game_Original::where('code_type',$custype)
            ->where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game_original.game_id')
            ->where('games.active',true)
            ->where('games.location_id', intval($locationId))
            ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game_original.*')
            ->orderBy('games.order','asc')
            ->get();
        // });
        
        return $result;
    }

    public static function GetAllGameParentByCusTypeAPI($custype,$user_id,$locationId=0)
    {
        if ($locationId == 0)
        $result =  
        // Cache::tags('CustomerType_Game_Original-'.$user_id)->remember('GetAllGameParentByCusType-'.$user_id.'-'.$custype, env('CACHE_TIME', 0), function () use ($custype,$user_id) {
            // return 
            CustomerType_Game_Original::where('code_type',$custype)
            ->where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game_original.game_id')
            ->where('games.active',true)
            ->select('games.game_code as game_code','games.name as game_name','customer_type_game_original.*')
            ->orderBy('games.order','asc')
            ->get();
        else
        $result =  
        // Cache::tags('CustomerType_Game_Original-'.$user_id)->remember('GetAllGameParentByCusType-'.$user_id.'-'.$custype, env('CACHE_TIME', 0), function () use ($custype,$user_id) {
            // return 
            CustomerType_Game_Original::where('code_type',$custype)
            ->where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game_original.game_id')
            ->where('games.active',true)
            ->where('games.location_id', intval($locationId))
            ->select('games.game_code as game_code','games.name as game_name','customer_type_game_original.*')
            ->orderBy('games.order','asc')
            ->get();
        // });
        
        return $result;
    }

    public static function GetGameParentByCusTypeGameid($custype,$user_id,$game_id)
    {
        $result =  
        Cache::remember('GetGameParentByCusTypeGameid-'.$user_id.'-'.$custype.'-'.$game_id, env('CACHE_TIME_BET', 0), function () use ($custype,$user_id,$game_id) {
            return 
            CustomerType_Game::where('code_type',$custype)
            ->where('created_user',$user_id)
            //->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
            //->where('games.active',true)
            ->where('game_id',$game_id)
            //->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.*')
            //->orderBy('order')
            ->first();
        });
        
        return $result;
    }

    public static function GetAllGameListByCusType($custype,$user_id,$parentid)
    {
        $result =  CustomerType_Game::where('code_type',$custype)
            ->where('created_user',$user_id)
            ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
            ->where('games.active',true)
            ->where('games.parent_id',$parentid)
            ->select('customer_type_game.*','games.game_code as game_code','games.name as name','games.a as a','games.open as open','games.close as close')
            // ->select('games.*','games.game_code as game_code','games.name as name','games.a as a','games.open as open','games.close as close','customer_type_game.*')
            ->orderBy('games.order','asc')
            ->get();
        return $result;
    }
    public static function GetGameByCusType($custype,$user_id,$id)
    {
        $result =  CustomerType_Game::where('code_type',$custype)
        ->where('created_user',$user_id)
        ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
        ->where('games.active',true)
        ->where('games.game_code',$id)
        ->select('customer_type_game.*','games.game_code as game_code','games.name as name','games.alias as alias','games.a as a','games.open as open','games.close as close')
        // ->select('games.game_code as game_code','games.name as name','games.a as a','games.open as open','games.close as close','customer_type_game.*')
        // ->orderBy('games.order','asc')
        ->first();
        // Cache::remember('GetGameByCusType-'.$user_id.'-'.$id, env('CACHE_TIME_BET', 0), function () use ($custype,$user_id,$id) {
        //     return 
        //     CustomerType_Game::where('code_type',$custype)
        //     ->where('created_user',$user_id)
        //     ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
        //     ->where('games.active',true)
        //     ->where('games.game_code',$id)
        //     ->select('customer_type_game.*','games.game_code as game_code','games.name as name','games.a as a','games.open as open','games.close as close')
        //     // ->select('games.game_code as game_code','games.name as name','games.a as a','games.open as open','games.close as close','customer_type_game.*')
        //     // ->orderBy('games.order','asc')
        //     ->first();
		// });
        
        return $result;
    }
    public function GetAllGameByParentID($parentID,$slug=1)
    {
        if (!is_numeric($parentID)) {
            return [];
        }
        return 
        // Cache::tags('Game')->remember('GetAllGameByParentID-'.$parentID.'-'.$slug, env('CACHE_TIME', 0), function () use ($parentID,$slug) {
            // return 
            Game::where('active', 1)
            ->where('parent_id', intval($parentID))
            ->where('location_id',$slug)
            ->orderBy('order')
            ->select('games.*')
            ->get();
		// });
        
    }

    public static function GetGameByGameCode($game_code)
    {
        return 
        Cache::remember('GetGameByGameCode-'.$game_code, env('CACHE_TIME_SHORT', 0), function () use ($game_code) {
            return 
            Game::where('active', 1)
            ->where('game_code', intval($game_code))
            ->select('games.*')
            ->first();
		});
        
    }

    public static function GetGameByAlias($alias)
    {
        return 
        Cache::remember('GetGameByAlias-'.$alias, env('CACHE_TIME_SHORT', 0), function () use ($alias) {
            return 
            Game::where('active', 1)
            ->where('alias', $alias)
            ->select('games.*')
            ->first();
		});
        
    }

    public function GetAllAgGameByParentID($parentID)
    {
        if (!is_numeric($parentID)) {
            return [];
        }
        return Game::where('active', 1)
            ->where('parent_id', intval($parentID))
            ->orderBy('order')
            ->select('games.*')
            ->get();
    }

    public function GetLocationGameByParentID($parentID)
    {
        if (!is_numeric($parentID)) {
            return [];
        }
        return Location::where('active', 1)
            ->where('parent_id', intval($parentID))
            ->orderBy('order')
            ->get();
    }

    public static function GetByCusTypeGameCode($game_code,$custype)
    {
        if ($game_code==18){
            $user = Auth::user();

            $result = 
            // Cache::tags('CustomerType_Game'.$user->id)->remember('GetByCusTypeGameCode-'.$game_code.'-'.$user->id.'-'.$custype, env('CACHE_TIME', 0), function () use ($game_code,$user,$custype) {
                // return 
                CustomerType_Game::where('code_type',$custype)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('customer_type_game.created_user',274)
                ->where('games.game_code',7)
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.code_type as code_type','customer_type_game.exchange_rates as exchange_rates')
                ->orderBy('games.order','asc')
                ->first();
            // });
            return $result;
        }else{
            $user = Auth::user();
            $result = 
            Cache::remember('GetByCusTypeGameCode-'.$game_code.'-'.$user->id.'-'.$custype, env('CACHE_TIME_SHORT', 0), function () use ($game_code,$user,$custype) {
                return 
                CustomerType_Game::where('code_type',$custype)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('customer_type_game.created_user',$user->id)
                ->where('games.game_code',$game_code)
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.code_type as code_type','customer_type_game.exchange_rates as exchange_rates')
                ->orderBy('games.order','asc')
                ->first();
            }); 
            return $result;
        }
        
    }

    public static function GetByCusTypeGameCodeUser($game_code,$custype,$user)
    {
        if ($game_code >= 31 && $game_code <= 55){
            $game_code_og = $game_code;
            $game_code = 24;
            $result = 
            Cache::remember('GetByCusTypeGameCode-'.$game_code.'-'.$user->id.'-'.$custype, env('CACHE_TIME_SHORT', 0), function () use ($game_code,$user,$custype) {
                return 
                CustomerType_Game::where('code_type',$custype)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('customer_type_game.created_user',$user->id)
                ->where('games.game_code',$game_code)
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.code_type as code_type','customer_type_game.exchange_rates as exchange_rates')
                ->orderBy('games.order','asc')
                ->first();
            }); 
            $game_og = Game::where("game_code",$game_code_og)->first();
            $result->game_name = $game_og->name;
            return $result;
        }
            
        if ($game_code==18){
            // $user = Auth::user();

            $result = 
            // Cache::tags('CustomerType_Game'.$user->id)->remember('GetByCusTypeGameCode-'.$game_code.'-'.$user->id.'-'.$custype, env('CACHE_TIME', 0), function () use ($game_code,$user,$custype) {
                // return 
                CustomerType_Game::where('code_type',$custype)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('customer_type_game.created_user',274)
                ->where('games.game_code',7)
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.code_type as code_type','customer_type_game.exchange_rates as exchange_rates')
                ->orderBy('games.order','asc')
                ->first();
            // });
            $result->game_name = "Lô Live";
            return $result;
        }else{
            // $user = Auth::user();
            $result = 
            Cache::remember('GetByCusTypeGameCode-'.$game_code.'-'.$user->id.'-'.$custype, env('CACHE_TIME_SHORT', 0), function () use ($game_code,$user,$custype) {
                return 
                CustomerType_Game::where('code_type',$custype)
                ->join('games', 'games.game_code', '=', 'customer_type_game.game_id')
                ->where('customer_type_game.created_user',$user->id)
                ->where('games.game_code',$game_code)
                ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game.code_type as code_type','customer_type_game.exchange_rates as exchange_rates')
                ->orderBy('games.order','asc')
                ->first();
            }); 
            return $result;
        }
        
    }

    public static function GetByCusTypeGameCodeOriginal($game_code,$custype)
    {
        $user = Auth::user();
        $result =  CustomerType_Game_Original::where('code_type',$custype)
            ->join('games', 'games.game_code', '=', 'customer_type_game_original.game_id')
            ->where('customer_type_game_original.created_user',$user->id)
            ->where('games.game_code',$game_code)
            ->select('games.*','games.game_code as game_code','games.name as game_name','customer_type_game_original.code_type as code_type','customer_type_game_original.exchange_rates as exchange_rates')
            ->orderBy('games.order','asc')
            ->first();
        return $result;
    }

    public static function UpdateGameNumberFollowCustomerType($customerType)
    {

    }

    public static function UpdateCustomerTypeGameOriginalChild($change,$user_id)
    {

    }
    public static function UpdateCustomerTypeGameOriginal($change,$user_id)
    {
        $customerType =  
        // Cache::tags('CustomerType_Game_Original'.$user_id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$user_id, env('CACHE_TIME', 0), function () use ($change,$user_id) {
            // return 
            CustomerType_Game_Original::where('code_type',$change['type'])
            ->where('game_id',$change['name'])
            ->where('created_user',$user_id)->first();
        // });
        
        $customerType->exchange_rates = $change['exchange'];
        $customerType->odds = $change['odds'];
        $customerType->max_point = $change['max_point'];
        $customerType->max_point_one = $change['max_point_one'];
        $customerType->change_odds = $change['change_odds'] === 'true'? true: false;
        $customerType->change_ex = $change['change_ex'] === 'true'? true: false;
        $customerType->change_max = $change['change_max'] === 'true'? true: false;
        // $customerType->change_max_one = $change['change_max_one'] === 'true'? true: false;
        $customerType->save();
        // Cache::tags('CustomerType_Game_Original'.$user_id)->flush();

        $customerTypeNO =  
        // Cache::tags('CustomerType_Game'.$user_id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$user_id, env('CACHE_TIME', 0), function () use ($change,$user_id) {
            // return 
            CustomerType_Game::where('code_type',$change['type'])
            ->where('game_id',$change['name'])
            ->where('created_user',$user_id)->first();
        // });
        
        
        // if ( $customerTypeNO->exchange_rates < $change['exchange'])
            $customerTypeNO->exchange_rates = $change['exchange'];
        // if ($customerTypeNO->odds > $change['odds'])
            $customerTypeNO->odds = $change['odds'];
        // if ($customerTypeNO->max_point > $change['max_point'])
            $customerTypeNO->max_point = $change['max_point'];
        // if ($customerTypeNO->max_point_one > $change['max_point_one'])
            $customerTypeNO->max_point_one = $change['max_point_one'];
        $customerTypeNO->change_odds = $change['change_odds'] === 'true'? true: false;
        $customerTypeNO->change_ex = $change['change_ex'] === 'true'? true: false;
        $customerTypeNO->change_max = $change['change_max'] === 'true'? true: false;
        // $customerTypeNO->change_max_one = $change['change_max_one'] === 'true'? true: false;
        $customerTypeNO->save();
        // Cache::tags('CustomerType_Game'.$user_id)->flush();
        
        $spargents = 
        // Cache::tags('User'.$user_id)->remember('user_create-'.$user_id, env('CACHE_TIME', 0), function () use ($user_id) {
            // return 
            User::where('user_create',$user_id)->where('active',0)->get();
        // });
        
        if(count($spargents)>0)
        {
            foreach ($spargents as $spargent)
            {
                $spa =  
                // Cache::tags('CustomerType_Game'.'-'.$argent->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$spargent->id)->first();
                // });
                
                if ($spa==null)
                    continue;
                // // \log::info("UpdateCustomerTypeGame");
                // // \log::info($change['name']);
                // // \log::info($change['odds']);
                // // \log::info($spargent->name);
                // // \log::info($spa->odds);
                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $spa->exchange_rates > $change['exchange'] )
                        $spa->exchange_rates = $change['exchange'];
                    if ( $spa->odds > $change['odds'] )
                        $spa->odds = $change['odds'];
                }
                else{
                    if ( $spa->exchange_rates < $change['exchange'] )
                        $spa->exchange_rates = $change['exchange'];
                    if ( $spa->odds < $change['odds'] )
                        $spa->odds = $change['odds'];
                }
                if ($spa->max_point > $change['max_point'])
                    $spa->max_point = $change['max_point'];
                if ($spa->max_point_one > $change['max_point_one'])
                    $spa->max_point_one = $change['max_point_one'];
                $spa->change_odds = $change['change_odds'] === 'true'? true: false;
                $spa->change_ex = $change['change_ex'] === 'true'? true: false;
                $spa->change_max = $change['change_max'] === 'true'? true: false;
                // $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $spa->save();
                // Cache::tags('CustomerType_Game'.$argent->id)->flush();

                $spaO =  
                // Cache::tags('CustomerType_Game_Original'.'-'.$argent->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game_Original::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$spargent->id)->first();
                // });
                
                if ($spaO == null || $spargent->roleid == 6)
                    continue;

                //     // \log::info("UpdateCustomerTypeGameOriginal");
                // // \log::info($change['name']);
                // // \log::info($change['odds']);
                // // \log::info($spargent->name);
                // // \log::info($spa->odds);

                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $spaO->exchange_rates > $change['exchange'] )
                        $spaO->exchange_rates = $change['exchange'];
                    if ( $spaO->odds > $change['odds'] )
                        $spaO->odds = $change['odds'];
                }
                else{
                    if ( $spaO->exchange_rates < $change['exchange'] )
                        $spaO->exchange_rates = $change['exchange'];
                    if ( $spaO->odds < $change['odds'] )
                        $spaO->odds = $change['odds'];
                }
                if ($spaO->max_point > $change['max_point'])
                    $spaO->max_point = $change['max_point'];
                if ($spaO->max_point_one > $change['max_point_one'])
                    $spaO->max_point_one = $change['max_point_one'];
                $spaO->change_odds = $change['change_odds'] === 'true'? true: false;
                $spaO->change_ex = $change['change_ex'] === 'true'? true: false;
                $spaO->change_max = $change['change_max'] === 'true'? true: false;
                // $spaO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $spaO->save();
                // Cache::tags('CustomerType_Game_Original'.$argent->id)->flush();

                // $tongs = 
                // // Cache::tags('User'.'-'.$argent->id)->remember('user_create-'.$argent->id, env('CACHE_TIME', 0), function () use ($argent) {
                //     // return 
                //     User::where('user_create',$argent->id)->where('active',0)->get();
                // // });

                $argents = 
                // Cache::tags('User'.$user_id)->remember('user_create-'.$user_id, env('CACHE_TIME', 0), function () use ($user_id) {
                    // return 
                    User::where('user_create',$spargent->id)->where('active',0)->get();
                // });
                
                if(count($argents)>0)
                {
                    foreach ($argents as $argent)
                    {
                        $a =  
                        // Cache::tags('CustomerType_Game'.'-'.$argent->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                            // return 
                            CustomerType_Game::where('code_type',$change['type'])
                            ->where('game_id',$change['name'])
                            ->where('created_user',$argent->id)->first();
                        // });
                        
                        if ($a==null)
                            continue;
                        if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                        || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                        {
                            if ( $a->exchange_rates > $change['exchange'] )
                                $a->exchange_rates = $change['exchange'];
                            if ( $a->odds > $change['odds'] )
                                $a->odds = $change['odds'];
                        }
                        else{
                            if ( $a->exchange_rates < $change['exchange'] )
                                $a->exchange_rates = $change['exchange'];
                            if ( $a->odds < $change['odds'] )
                                $a->odds = $change['odds'];
                        }
                        if ($a->max_point > $change['max_point'])
                            $a->max_point = $change['max_point'];
                        if ($a->max_point_one > $change['max_point_one'])
                            $a->max_point_one = $change['max_point_one'];
                        $a->change_odds = $change['change_odds'] === 'true'? true: false;
                        $a->change_ex = $change['change_ex'] === 'true'? true: false;
                        $a->change_max = $change['change_max'] === 'true'? true: false;
                        // $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
                        $a->save();
                        // Cache::tags('CustomerType_Game'.$argent->id)->flush();

                        $aO =  
                        // Cache::tags('CustomerType_Game_Original'.'-'.$argent->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                            // return 
                            CustomerType_Game_Original::where('code_type',$change['type'])
                            ->where('game_id',$change['name'])
                            ->where('created_user',$argent->id)->first();
                        // });
                        
                        if ($aO == null || $argent->roleid == 6)
                            continue;
                        if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                        || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                        {
                            if ( $aO->exchange_rates > $change['exchange'] )
                                $aO->exchange_rates = $change['exchange'];
                            if ( $aO->odds > $change['odds'] )
                                $aO->odds = $change['odds'];
                        }
                        else{
                            if ( $aO->exchange_rates < $change['exchange'] )
                                $aO->exchange_rates = $change['exchange'];
                            // if ( $aO->odds < $change['odds'] )
                                $aO->odds = $change['odds'];
                        }
                        if ($aO->max_point > $change['max_point'])
                            $aO->max_point = $change['max_point'];
                        if ($aO->max_point_one > $change['max_point_one'])
                            $aO->max_point_one = $change['max_point_one'];
                        $aO->change_odds = $change['change_odds'] === 'true'? true: false;
                        $aO->change_ex = $change['change_ex'] === 'true'? true: false;
                        $aO->change_max = $change['change_max'] === 'true'? true: false;
                        // $aO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                        $aO->save();
                        // Cache::tags('CustomerType_Game_Original'.$argent->id)->flush();

                        $tongs = 
                        // Cache::tags('User'.'-'.$argent->id)->remember('user_create-'.$argent->id, env('CACHE_TIME', 0), function () use ($argent) {
                            // return 
                            User::where('user_create',$argent->id)->where('active',0)->get();
                        // });
                        
                        if(count($tongs)>0)
                        {
                            foreach ($tongs as $tong) {
                                $t = 
                                // Cache::tags('CustomerType_Game'.'-'.$tong->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$tong->id, env('CACHE_TIME', 0), function () use ($change,$tong) {
                                    // return 
                                    CustomerType_Game::where('code_type', $change['type'])
                                    ->where('game_id', $change['name'])
                                    ->where('created_user', $tong->id)->first();
                                // });
                                
                                if ($t == null)
                                    continue;

                                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                                {
                                    if ( $t->exchange_rates > $change['exchange'] )
                                        $t->exchange_rates = $change['exchange'];
                                    if ( $t->odds > $change['odds'] )
                                        $t->odds = $change['odds'];
                                }
                                else{
                                    if ( $t->exchange_rates < $change['exchange'] )
                                        $t->exchange_rates = $change['exchange'];
                                    if ( $t->odds < $change['odds'] )
                                        $t->odds = $change['odds'];
                                }
                                
                                // if ( $t->exchange_rates < $change['exchange'] )
                                //     $t->exchange_rates = $change['exchange'];
                                // if ( $t->odds < $change['odds'] )
                                //     $t->odds = $change['odds'];

                                if ($t->max_point > $change['max_point'])
                                    $t->max_point = $change['max_point'];
                                if ($t->max_point_one > $change['max_point_one'])
                                    $t->max_point_one = $change['max_point_one'];
                                $t->change_odds = $change['change_odds'] === 'true'? true: false;
                                $t->change_ex = $change['change_ex'] === 'true'? true: false;
                                $t->change_max = $change['change_max'] === 'true'? true: false;
                                // $t->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                $t->save();
                                // Cache::tags('CustomerType_Game'.$tong->id)->flush();

                                $tO = 
                                // Cache::tags('CustomerType_Game_Original'.'-'.$tong->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$tong->id, env('CACHE_TIME', 0), function () use ($change,$tong) {
                                    // return 
                                    CustomerType_Game_Original::where('code_type', $change['type'])
                                    ->where('game_id', $change['name'])
                                    ->where('created_user', $tong->id)->first();
                                // });
                                
                                if ($tO == null || $tong->roleid == 6)
                                    continue;

                                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                                {
                                    if ( $tO->exchange_rates > $change['exchange'] )
                                        $tO->exchange_rates = $change['exchange'];
                                    if ( $tO->odds > $change['odds'] )
                                        $tO->odds = $change['odds'];
                                }
                                else{
                                    if ( $aO->exchange_rates < $change['exchange'] )
                                        $tO->exchange_rates = $change['exchange'];
                                    // if ( $aO->odds < $change['odds'] )
                                        $tO->odds = $change['odds'];
                                }
                                // // if ( $tO->exchange_rates < $change['exchange'] )
                                //     $tO->exchange_rates = $change['exchange'];
                                // // if ( $tO->odds < $change['odds'] )
                                //     $tO->odds = $change['odds'];
                                if ($tO->max_point > $change['max_point'])
                                    $tO->max_point = $change['max_point'];
                                if ($tO->max_point_one > $change['max_point_one'])
                                    $tO->max_point_one = $change['max_point_one'];
                                $tO->change_odds = $change['change_odds'] === 'true'? true: false;
                                $tO->change_ex = $change['change_ex'] === 'true'? true: false;
                                $tO->change_max = $change['change_max'] === 'true'? true: false;
                                // $tO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                $tO->save();
                                // Cache::tags('CustomerType_Game_Original'.$tong->id)->flush();

                                $khachs = 
                                // Cache::tags('User'.'-'.$tong->id)->remember('user_create-'.$tong->id, env('CACHE_TIME', 0), function () use ($tong) {
                                    // return 
                                    User::where('user_create',$tong->id)->where('active',0)->get();
                                // });
                                
                                if(count($khachs)>0)
                                {
                                    foreach ($khachs as $khach) {
                                        $k = 
                                        // Cache::tags('CustomerType_Game'.'-'.$khach->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$khach->id, env('CACHE_TIME', 0), function () use ($change,$khach) {
                                            // return 
                                            CustomerType_Game::where('code_type', $change['type'])
                                            ->where('game_id', $change['name'])
                                            ->where('created_user', $khach->id)->first();
                                        // });
                                        
                                        if ($k == null)
                                            continue;

                                        if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                                        || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                                        {
                                            if ( $k->exchange_rates > $change['exchange'] )
                                                $k->exchange_rates = $change['exchange'];
                                            if ( $k->odds > $change['odds'] )
                                                $k->odds = $change['odds'];
                                        }
                                        else{
                                            if ( $aO->exchange_rates < $change['exchange'] )
                                                $k->exchange_rates = $change['exchange'];
                                            // if ( $aO->odds < $change['odds'] )
                                                $k->odds = $change['odds'];
                                        }
                                        
                                        // if ( $k->exchange_rates < $change['exchange'] )
                                        //     $k->exchange_rates = $change['exchange'];
                                        // if ( $k->odds < $change['odds'] )
                                        //     $k->odds = $change['odds'];

                                        if ($k->max_point > $change['max_point'])
                                            $k->max_point = $change['max_point'];
                                        if ($k->max_point_one > $change['max_point_one'])
                                            $k->max_point_one = $change['max_point_one'];
                                        $k->change_odds = $change['change_odds'] === 'true'? true: false;
                                        $k->change_ex = $change['change_ex'] === 'true'? true: false;
                                        $k->change_max = $change['change_max'] === 'true'? true: false;
                                        // $k->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                        $k->save();
                                        // Cache::tags('CustomerType_Game'.$khach->id)->flush();

                                        // $kO = CustomerType_Game_Original::where('code_type', $change['type'])
                                        //     ->where('game_id', $change['name'])
                                        //     ->where('created_user', $khach->id)->first();

                                        // if ( $kO->exchange_rates < $change['exchange'] )
                                        //     $kO->exchange_rates = $change['exchange'];
                                        // if ( $kO->odds < $change['odds'] )
                                        //     $kO->odds = $change['odds'];
                                        // if ($kO->max_point > $change['max_point'])
                                        //     $kO->max_point = $change['max_point'];
                                        // if ($kO->max_point_one > $change['max_point_one'])
                                        //     $kO->max_point_one = $change['max_point_one'];
                                        // $kO->change_odds = $change['change_odds'] === 'true'? true: false;
                                        // $kO->change_ex = $change['change_ex'] === 'true'? true: false;
                                        // $kO->change_max = $change['change_max'] === 'true'? true: false;
                                        // $kO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                        // $kO->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function UpdateCustomerTypeGameOriginalLowp($change,$user_id)
    {
        $customerType =  
        // Cache::tags('CustomerType_Game_Original'.$user_id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$user_id, env('CACHE_TIME', 0), function () use ($change,$user_id) {
            // return 
            CustomerType_Game_Original::where('code_type',$change['type'])
            ->where('game_id',$change['name'])
            ->where('created_user',$user_id)->first();
        // });
        
        $customerType->exchange_rates = $change['exchange'];
        $customerType->odds = $change['odds'];
        $customerType->max_point = $change['max_point'];
        $customerType->max_point_one = $change['max_point_one'];
        $customerType->change_odds = $change['change_odds'] === 'true'? true: false;
        $customerType->change_ex = $change['change_ex'] === 'true'? true: false;
        $customerType->change_max = $change['change_max'] === 'true'? true: false;
        // $customerType->change_max_one = $change['change_max_one'] === 'true'? true: false;
        $customerType->save();
        // Cache::tags('CustomerType_Game_Original'.$user_id)->flush();
        return;
        $customerTypeNO =  
        // Cache::tags('CustomerType_Game'.$user_id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$user_id, env('CACHE_TIME', 0), function () use ($change,$user_id) {
            // return 
            CustomerType_Game::where('code_type',$change['type'])
            ->where('game_id',$change['name'])
            ->where('created_user',$user_id)->first();
        // });
        
        
        if ( $customerTypeNO->exchange_rates < $change['exchange'])
            $customerTypeNO->exchange_rates = $change['exchange'];
        if ($customerTypeNO->odds < $change['odds'])
            $customerTypeNO->odds = $change['odds'];
        if ($customerTypeNO->max_point > $change['max_point'])
            $customerTypeNO->max_point = $change['max_point'];
        if ($customerTypeNO->max_point_one > $change['max_point_one'])
            $customerTypeNO->max_point_one = $change['max_point_one'];
        $customerTypeNO->change_odds = $change['change_odds'] === 'true'? true: false;
        $customerTypeNO->change_ex = $change['change_ex'] === 'true'? true: false;
        $customerTypeNO->change_max = $change['change_max'] === 'true'? true: false;
        // $customerTypeNO->change_max_one = $change['change_max_one'] === 'true'? true: false;
        $customerTypeNO->save();
        // Cache::tags('CustomerType_Game'.$user_id)->flush();
        
        $spargents = 
        // Cache::tags('User'.$user_id)->remember('user_create-'.$user_id, env('CACHE_TIME', 0), function () use ($user_id) {
            // return 
            User::where('user_create',$user_id)->where('active',0)->get();
        // });
        
        if(count($spargents)>0)
        {
            foreach ($spargents as $spargent)
            {
                $a =  
                // Cache::tags('CustomerType_Game'.'-'.$argent->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$spargent->id)->first();
                // });
                
                if ($a==null)
                    continue;
                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $a->exchange_rates > $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds > $change['odds'] )
                        $a->odds = $change['odds'];
                }
                else{
                    if ( $a->exchange_rates < $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds < $change['odds'] )
                        $a->odds = $change['odds'];
                }
                if ($a->max_point > $change['max_point'])
                    $a->max_point = $change['max_point'];
                if ($a->max_point_one > $change['max_point_one'])
                    $a->max_point_one = $change['max_point_one'];
                $a->change_odds = $change['change_odds'] === 'true'? true: false;
                $a->change_ex = $change['change_ex'] === 'true'? true: false;
                $a->change_max = $change['change_max'] === 'true'? true: false;
                // $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $a->save();
                // Cache::tags('CustomerType_Game'.$argent->id)->flush();

                $aO =  
                // Cache::tags('CustomerType_Game_Original'.'-'.$argent->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game_Original::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$spargent->id)->first();
                // });
                
                if ($aO == null)
                    continue;
                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    // if ( $a->exchange_rates > $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    // if ( $a->odds > $change['odds'] )
                        $a->odds = $change['odds'];
                }
                else{
                    // if ( $aO->exchange_rates < $change['exchange'] )
                        $aO->exchange_rates = $change['exchange'];
                    // if ( $aO->odds < $change['odds'] )
                        $aO->odds = $change['odds'];
                }
                // if ($aO->max_point > $change['max_point'])
                    $aO->max_point = $change['max_point'];
                // if ($aO->max_point_one > $change['max_point_one'])
                    $aO->max_point_one = $change['max_point_one'];
                $aO->change_odds = $change['change_odds'] === 'true'? true: false;
                $aO->change_ex = $change['change_ex'] === 'true'? true: false;
                $aO->change_max = $change['change_max'] === 'true'? true: false;
                // $aO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $aO->save();
                // Cache::tags('CustomerType_Game_Original'.$argent->id)->flush();

                // $tongs = 
                // // Cache::tags('User'.'-'.$argent->id)->remember('user_create-'.$argent->id, env('CACHE_TIME', 0), function () use ($argent) {
                //     // return 
                //     User::where('user_create',$argent->id)->where('active',0)->get();
                // // });
                $argents = 
                // Cache::tags('User'.$user_id)->remember('user_create-'.$user_id, env('CACHE_TIME', 0), function () use ($user_id) {
                    // return 
                    User::where('user_create',$spargent->id)->where('active',0)->get();
                // });
                
                if(count($argents)>0)
                {
                    foreach ($argents as $argent)
                    {
                        $a =  
                        // Cache::tags('CustomerType_Game'.'-'.$argent->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                            // return 
                            CustomerType_Game::where('code_type',$change['type'])
                            ->where('game_id',$change['name'])
                            ->where('created_user',$argent->id)->first();
                        // });
                        
                        if ($a==null)
                            continue;
                        if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                        || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                        {
                            if ( $a->exchange_rates > $change['exchange'] )
                                $a->exchange_rates = $change['exchange'];
                            if ( $a->odds > $change['odds'] )
                                $a->odds = $change['odds'];
                        }
                        else{
                            if ( $a->exchange_rates < $change['exchange'] )
                                $a->exchange_rates = $change['exchange'];
                            if ( $a->odds < $change['odds'] )
                                $a->odds = $change['odds'];
                        }
                        if ($a->max_point > $change['max_point'])
                            $a->max_point = $change['max_point'];
                        if ($a->max_point_one > $change['max_point_one'])
                            $a->max_point_one = $change['max_point_one'];
                        $a->change_odds = $change['change_odds'] === 'true'? true: false;
                        $a->change_ex = $change['change_ex'] === 'true'? true: false;
                        $a->change_max = $change['change_max'] === 'true'? true: false;
                        // $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
                        $a->save();
                        // Cache::tags('CustomerType_Game'.$argent->id)->flush();

                        $aO =  
                        // Cache::tags('CustomerType_Game_Original'.'-'.$argent->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                            // return 
                            CustomerType_Game_Original::where('code_type',$change['type'])
                            ->where('game_id',$change['name'])
                            ->where('created_user',$argent->id)->first();
                        // });
                        
                        if ($aO == null)
                            continue;
                        if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                        || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                        {
                            // if ( $a->exchange_rates > $change['exchange'] )
                                $a->exchange_rates = $change['exchange'];
                            // if ( $a->odds > $change['odds'] )
                                $a->odds = $change['odds'];
                        }
                        else{
                            // if ( $aO->exchange_rates < $change['exchange'] )
                                $aO->exchange_rates = $change['exchange'];
                            // if ( $aO->odds < $change['odds'] )
                                $aO->odds = $change['odds'];
                        }
                        // if ($aO->max_point > $change['max_point'])
                            $aO->max_point = $change['max_point'];
                        // if ($aO->max_point_one > $change['max_point_one'])
                            $aO->max_point_one = $change['max_point_one'];
                        $aO->change_odds = $change['change_odds'] === 'true'? true: false;
                        $aO->change_ex = $change['change_ex'] === 'true'? true: false;
                        $aO->change_max = $change['change_max'] === 'true'? true: false;
                        // $aO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                        $aO->save();
                        // Cache::tags('CustomerType_Game_Original'.$argent->id)->flush();

                        $tongs = 
                        // Cache::tags('User'.'-'.$argent->id)->remember('user_create-'.$argent->id, env('CACHE_TIME', 0), function () use ($argent) {
                            // return 
                            User::where('user_create',$argent->id)->where('active',0)->get();
                        // });
                        
                        if(count($tongs)>0)
                        {
                            foreach ($tongs as $tong) {
                                $t = 
                                // Cache::tags('CustomerType_Game'.'-'.$tong->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$tong->id, env('CACHE_TIME', 0), function () use ($change,$tong) {
                                    // return 
                                    CustomerType_Game::where('code_type', $change['type'])
                                    ->where('game_id', $change['name'])
                                    ->where('created_user', $tong->id)->first();
                                // });
                                
                                if ($t == null)
                                    continue;
                                if ( $t->exchange_rates < $change['exchange'] )
                                    $t->exchange_rates = $change['exchange'];
                                if ( $t->odds < $change['odds'] )
                                    $t->odds = $change['odds'];
                                if ($t->max_point > $change['max_point'])
                                    $t->max_point = $change['max_point'];
                                if ($t->max_point_one > $change['max_point_one'])
                                    $t->max_point_one = $change['max_point_one'];
                                $t->change_odds = $change['change_odds'] === 'true'? true: false;
                                $t->change_ex = $change['change_ex'] === 'true'? true: false;
                                $t->change_max = $change['change_max'] === 'true'? true: false;
                                // $t->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                $t->save();
                                // Cache::tags('CustomerType_Game'.$tong->id)->flush();

                                $tO = 
                                // Cache::tags('CustomerType_Game_Original'.'-'.$tong->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$tong->id, env('CACHE_TIME', 0), function () use ($change,$tong) {
                                    // return 
                                    CustomerType_Game_Original::where('code_type', $change['type'])
                                    ->where('game_id', $change['name'])
                                    ->where('created_user', $tong->id)->first();
                                // });
                                

                                // if ( $tO->exchange_rates < $change['exchange'] )
                                    $tO->exchange_rates = $change['exchange'];
                                // if ( $tO->odds < $change['odds'] )
                                    $tO->odds = $change['odds'];
                                // if ($tO->max_point > $change['max_point'])
                                    $tO->max_point = $change['max_point'];
                                // if ($tO->max_point_one > $change['max_point_one'])
                                    $tO->max_point_one = $change['max_point_one'];
                                $tO->change_odds = $change['change_odds'] === 'true'? true: false;
                                $tO->change_ex = $change['change_ex'] === 'true'? true: false;
                                $tO->change_max = $change['change_max'] === 'true'? true: false;
                                // $tO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                $tO->save();
                                // Cache::tags('CustomerType_Game_Original'.$tong->id)->flush();

                                $khachs = 
                                // Cache::tags('User'.'-'.$tong->id)->remember('user_create-'.$tong->id, env('CACHE_TIME', 0), function () use ($tong) {
                                    // return 
                                    User::where('user_create',$tong->id)->where('active',0)->get();
                                // });
                                
                                if(count($khachs)>0)
                                {
                                    foreach ($khachs as $khach) {
                                        $k = 
                                        // Cache::tags('CustomerType_Game'.'-'.$khach->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$khach->id, env('CACHE_TIME', 0), function () use ($change,$khach) {
                                            // return 
                                            CustomerType_Game::where('code_type', $change['type'])
                                            ->where('game_id', $change['name'])
                                            ->where('created_user', $khach->id)->first();
                                        // });
                                        
                                        if ($k == null)
                                            continue;
                                        if ( $k->exchange_rates < $change['exchange'] )
                                            $k->exchange_rates = $change['exchange'];
                                        if ( $k->odds < $change['odds'] )
                                            $k->odds = $change['odds'];
                                        if ($k->max_point > $change['max_point'])
                                            $k->max_point = $change['max_point'];
                                        if ($k->max_point_one > $change['max_point_one'])
                                            $k->max_point_one = $change['max_point_one'];
                                        $k->change_odds = $change['change_odds'] === 'true'? true: false;
                                        $k->change_ex = $change['change_ex'] === 'true'? true: false;
                                        $k->change_max = $change['change_max'] === 'true'? true: false;
                                        // $k->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                        $k->save();
                                        // Cache::tags('CustomerType_Game'.$khach->id)->flush();

                                        // $kO = CustomerType_Game_Original::where('code_type', $change['type'])
                                        //     ->where('game_id', $change['name'])
                                        //     ->where('created_user', $khach->id)->first();

                                        // if ( $kO->exchange_rates < $change['exchange'] )
                                        //     $kO->exchange_rates = $change['exchange'];
                                        // if ( $kO->odds < $change['odds'] )
                                        //     $kO->odds = $change['odds'];
                                        // if ($kO->max_point > $change['max_point'])
                                        //     $kO->max_point = $change['max_point'];
                                        // if ($kO->max_point_one > $change['max_point_one'])
                                        //     $kO->max_point_one = $change['max_point_one'];
                                        // $kO->change_odds = $change['change_odds'] === 'true'? true: false;
                                        // $kO->change_ex = $change['change_ex'] === 'true'? true: false;
                                        // $kO->change_max = $change['change_max'] === 'true'? true: false;
                                        // $kO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                        // $kO->save();
                                    }
                                }
                            }
                        }
                    }
                }
            
            }
        }
    }

    public static function UpdateChildCustomerTypeGame($change,$user){
        if ($user->roleid != 6){
            $userChild = 
            // Cache::tags('User'.$user_id)->remember('user_create-'.$user_id, env('CACHE_TIME', 0), function () use ($user_id) {
                // return 
            User::where('user_create',$user->id)->where('active',0)->get();
            // });
            // // \log::info('agnt'.count($argents));
            foreach ($userChild as $userCurrent)
            {
                $a =  
                // Cache::tags('CustomerType_Game'.'-'.$argent->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$userCurrent->id)->first();
                // });
                if ($a == null)
                    continue;
                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                || $change['name']== 16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $a->exchange_rates > $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds < $change['odds'] )
                        $a->odds = $change['odds'];
                }
                else{
                    if ( $a->exchange_rates < $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds < $change['odds'] )
                        $a->odds = $change['odds'];
                }
                if ($a->max_point > $change['max_point'])
                    $a->max_point = $change['max_point'];
                if ($a->max_point_one > $change['max_point_one'])
                    $a->max_point_one = $change['max_point_one'];
                $a->change_odds = $change['change_odds'] === 'true'? true: false;
                $a->change_ex = $change['change_ex'] === 'true'? true: false;
                $a->change_max = $change['change_max'] === 'true'? true: false;
                // $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $a->save();
                // Cache::tags('CustomerType_Game'.$argent->id)->flush();

                $aO =  
                // Cache::tags('CustomerType_Game_Original'.'-'.$argent->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game_Original::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$userCurrent->id)->first();
                // });
                if ($aO == null)
                    continue;
                if($change['name'] == 15 || $change['name'] == 15 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $a->exchange_rates > $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds < $change['odds'] )
                        $a->odds = $change['odds'];
                }
                else{
                    if ( $aO->exchange_rates < $change['exchange'] )
                        $aO->exchange_rates = $change['exchange'];
                    if ( $aO->odds < $change['odds'] )
                        $aO->odds = $change['odds'];
                }
                if ($aO->max_point > $change['max_point'])
                    $aO->max_point = $change['max_point'];
                if ($aO->max_point_one > $change['max_point_one'])
                    $aO->max_point_one = $change['max_point_one'];
                $aO->change_odds = $change['change_odds'] === 'true'? true: false;
                $aO->change_ex = $change['change_ex'] === 'true'? true: false;
                $aO->change_max = $change['change_max'] === 'true'? true: false;
                // $aO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $aO->save();
                // Cache::tags('CustomerType_Game_Original'.$argent->id)->flush();

                GameHelpers::UpdateChildCustomerTypeGame($change,$userCurrent);
            }
        }else{
            $k = 
            // Cache::tags('CustomerType_Game'.'-'.$khach->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$khach->id, env('CACHE_TIME', 0), function () use ($change,$khach) {
                // return 
                CustomerType_Game::where('code_type', $change['type'])
                ->where('game_id', $change['name'])
                ->where('created_user', $user->id)->first();
            // });
            if ($k == null)
                return;
            if ( $k->exchange_rates < $change['exchange'] )
                $k->exchange_rates = $change['exchange'];
            if ( $k->odds < $change['odds'] )
                $k->odds = $change['odds'];
            if ($k->max_point > $change['max_point'])
                $k->max_point = $change['max_point'];
            if ($k->max_point_one > $change['max_point_one'])
                $k->max_point_one = $change['max_point_one'];
            $k->change_odds = $change['change_odds'] === 'true'? true: false;
            $k->change_ex = $change['change_ex'] === 'true'? true: false;
            $k->change_max = $change['change_max'] === 'true'? true: false;
            // $k->change_max_one = $change['change_max_one'] === 'true'? true: false;
            $k->save();

            // if ($user->roleid == 6)
            //     SabaHelpers::SetMemberBetSetting($user->name,$k);

            // Cache::tags('CustomerType_Game'.$khach->id)->flush();

            // $kO = CustomerType_Game_Original::where('code_type', $change['type'])
            //     ->where('game_id', $change['name'])
            //     ->where('created_user', $khach->id)->first();

            // if ( $kO->exchange_rates < $change['exchange'] )
            //     $kO->exchange_rates = $change['exchange'];
            // if ( $kO->odds < $change['odds'] )
            //     $kO->odds = $change['odds'];
            // if ($kO->max_point > $change['max_point'])
            //     $kO->max_point = $change['max_point'];
            // if ($kO->max_point_one > $change['max_point_one'])
            //     $kO->max_point_one = $change['max_point_one'];
            // $kO->change_odds = $change['change_odds'] === 'true'? true: false;
            // $kO->change_ex = $change['change_ex'] === 'true'? true: false;
            // $kO->change_max = $change['change_max'] === 'true'? true: false;
            // $kO->change_max_one = $change['change_max_one'] === 'true'? true: false;
            // $kO->save();
        }
    }

    public static function UpdateCustomerTypeByUserIdNormal($change_customertype,$userMe)
    {
        // $userMe  = User::where('id',$user_id)->first();
        $customerTypeMe =  CustomerType_Game::where('created_user',$userMe->id)->orderBy('game_id','asc')->get();
            
        $customerTypeParent =  CustomerType_Game::where('code_type',$change_customertype)
                                                ->where('created_user',$userMe->user_create)->orderBy('game_id','asc')->get();

        $count=0;
        // echo count($customerTypeMe) . ' ' . count($customerTypeParent);

        foreach ($customerTypeMe as $customerType){
            $customerTypePP = $customerTypeParent[0];
            foreach($customerTypeParent as $customerTypePr){
                if ($customerType->game_id == $customerTypePr->game_id){
                    // echo 'break ';
                    $customerTypePP = $customerTypePr;
                    break;
                }
            }
            if ($customerType->game_id != $customerTypePP->game_id){
                // echo 'break ';
                continue;
            }
            $customerType->exchange_rates = $customerTypePP->exchange_rates;
            $customerType->odds = $customerTypePP->odds;
            $customerType->max_point = $customerTypePP->max_point;
            $customerType->max_point_one = $customerTypePP->max_point_one;
            $customerType->change_odds = $customerTypePP->change_odds;
            $customerType->change_ex = $customerTypePP->change_ex;
            $customerType->change_max = $customerTypePP->change_max;
            $customerType->change_max_one = $customerTypePP->change_max_one;
            $customerType->code_type = $change_customertype;
            // $customerType->change_max_one = $change['change_max_one'] === 'true'? true: false;
            $customerType->save();
            $count++;
        }
    }

    public static function UpdateCustomerTypeByUserIdOG($change_customertype,$userMe)
    {
        // $userMe  = User::where('id',$user_id)->first();
        $customerTypeMeOG =  CustomerType_Game_Original::where('created_user',$userMe->id)->orderBy('game_id','asc')->get();
    
        $customerTypeParentOG =  CustomerType_Game_Original::where('code_type',$change_customertype)
                                                ->where('created_user',$userMe->user_create)->orderBy('game_id','asc')->get();
        $count=0;
        foreach ($customerTypeMeOG as $customerType){
            $customerTypePP = $customerTypeParentOG[0];
            foreach($customerTypeParentOG as $customerTypePr){
                if ($customerType->game_id == $customerTypePr->game_id){
                    // echo 'break ';
                    $customerTypePP = $customerTypePr;
                    break;
                }
            }
            if ($customerType->game_id != $customerTypePP->game_id){
                // echo 'break ';
                continue;
            }
            $customerType->exchange_rates = $customerTypePP->exchange_rates;
            $customerType->odds = $customerTypePP->odds;
            $customerType->max_point = $customerTypePP->max_point;
            $customerType->max_point_one = $customerTypePP->max_point_one;
            $customerType->change_odds = $customerTypePP->change_odds;
            $customerType->change_ex = $customerTypePP->change_ex;
            $customerType->change_max = $customerTypePP->change_max;
            $customerType->change_max_one = $customerTypePP->change_max_one;
            $customerType->code_type = $change_customertype;
            // $customerType->change_max_one = $change['change_max_one'] === 'true'? true: false;
            $customerType->save();
            $count++;
        }
    }

    public static function UpdateCustomerTypeByUserId($change_customertype,$userMe)
    {
        try{
            // $userMe  = User::where('id',$user_id)->first();
            // $userParent = User::where('id', $userMe->user_create)->first();
    
            static::UpdateCustomerTypeByUserIdNormal($change_customertype,$userMe);
            static::UpdateCustomerTypeByUserIdOG($change_customertype,$userMe);
            
            $userMe->customer_type = $change_customertype;
            $userMe->save();

            Queue::pushOn("high",new UpdateMeFromParentEXService($userMe,$userMe));
            // static::UpdateMeFromParentEX($userMe,$userMe);
            return 'true';
        }catch(Exception $ex){
            echo $ex->getMessage() . ' ' . $ex->getLine();
        }
        return 'failed';
    }

    public static function UpdateCustomerTypeGame($change,$user_id)
    {
        $customerType =  CustomerType_Game::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$user_id)->first();
        $customerType->exchange_rates = $change['exchange'];
        $customerType->odds = $change['odds'];
        $customerType->max_point = $change['max_point'];
        $customerType->max_point_one = $change['max_point_one'];
        $customerType->change_odds = $change['change_odds'] === 'true'? true: false;
        $customerType->change_ex = $change['change_ex'] === 'true'? true: false;
        $customerType->change_max = $change['change_max'] === 'true'? true: false;
        // $customerType->change_max_one = $change['change_max_one'] === 'true'? true: false;
        $customerType->save();

        $userCurrent = User::where('id',$user_id)->first();

        if ($user_id == 274 && $change['type'] == 'A'){
            $gameUpdate = GameHelpers::GetGameByGameCode($change['name']);
            $gameUpdate->exchange_rates = $change['exchange'];
            $gameUpdate->save();

            HistoryHelpers::ActiveHistorySave($userCurrent,$userCurrent,"thay đổi chuẩn " . $change['type'] . " - ". $gameUpdate->name,"");
        }
        // Cache::tags('CustomerType_Game'.$user_id)->flush();

        // if ($userCurrent->roleid == 6)
        //     SabaHelpers::SetMemberBetSetting($userCurrent->name,$customerType);

        GameHelpers::UpdateChildCustomerTypeGame($change,$userCurrent);

        return;

        $argents = 
        // Cache::tags('User'.$user_id)->remember('user_create-'.$user_id, env('CACHE_TIME', 0), function () use ($user_id) {
            // return 
            User::where('user_create',$user_id)->where('active',0)->get();
        // });
        if(count($argents)>0)
        {
            // // \log::info('agnt'.count($argents));
            foreach ($argents as $argent)
            {
                $a =  
                // Cache::tags('CustomerType_Game'.'-'.$argent->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$argent->id)->first();
                // });
                if ($a == null)
                    continue;
                if($change['name'] == 15 || $change['name'] == 315 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $a->exchange_rates > $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds > $change['odds'] )
                        $a->odds = $change['odds'];
                }
                else{
                    if ( $a->exchange_rates < $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds < $change['odds'] )
                        $a->odds = $change['odds'];
                }
                if ($a->max_point > $change['max_point'])
                    $a->max_point = $change['max_point'];
                if ($a->max_point_one > $change['max_point_one'])
                    $a->max_point_one = $change['max_point_one'];
                $a->change_odds = $change['change_odds'] === 'true'? true: false;
                $a->change_ex = $change['change_ex'] === 'true'? true: false;
                $a->change_max = $change['change_max'] === 'true'? true: false;
                // $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $a->save();
                // Cache::tags('CustomerType_Game'.$argent->id)->flush();

                $aO =  
                // Cache::tags('CustomerType_Game_Original'.'-'.$argent->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$argent->id, env('CACHE_TIME', 0), function () use ($change,$argent) {
                    // return 
                    CustomerType_Game_Original::where('code_type',$change['type'])
                    ->where('game_id',$change['name'])
                    ->where('created_user',$argent->id)->first();
                // });
                if ($aO == null)
                    continue;
                if($change['name'] == 15 || $change['name'] == 15 || $change['name'] == 415 || $change['name'] == 515 || $change['name'] == 615 
                || $change['name']==16 || $change['name']==316 || $change['name']==416 || $change['name']==516 || $change['name']==616)
                {
                    if ( $a->exchange_rates > $change['exchange'] )
                        $a->exchange_rates = $change['exchange'];
                    if ( $a->odds > $change['odds'] )
                        $a->odds = $change['odds'];
                }
                else{
                    if ( $aO->exchange_rates < $change['exchange'] )
                        $aO->exchange_rates = $change['exchange'];
                    if ( $aO->odds < $change['odds'] )
                        $aO->odds = $change['odds'];
                }
                if ($aO->max_point > $change['max_point'])
                    $aO->max_point = $change['max_point'];
                if ($aO->max_point_one > $change['max_point_one'])
                    $aO->max_point_one = $change['max_point_one'];
                $aO->change_odds = $change['change_odds'] === 'true'? true: false;
                $aO->change_ex = $change['change_ex'] === 'true'? true: false;
                $aO->change_max = $change['change_max'] === 'true'? true: false;
                // $aO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                $aO->save();
                // Cache::tags('CustomerType_Game_Original'.$argent->id)->flush();

                $tongs = 
                // Cache::tags('User'.'-'.$argent->id)->remember('user_create-'.$argent->id, env('CACHE_TIME', 0), function () use ($argent) {
                    // return 
                    User::where('user_create',$argent->id)->where('active',0)->get();
                // });
                if(count($tongs)>0)
                {
                    foreach ($tongs as $tong) {
                        $t = 
                        // Cache::tags('CustomerType_Game'.'-'.$tong->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$tong->id, env('CACHE_TIME', 0), function () use ($change,$tong) {
                            // return 
                            CustomerType_Game::where('code_type', $change['type'])
                            ->where('game_id', $change['name'])
                            ->where('created_user', $tong->id)->first();
                        // });
                        if ($t == null)
                            continue;
                        if ( $t->exchange_rates < $change['exchange'] )
                            $t->exchange_rates = $change['exchange'];
                        if ( $t->odds < $change['odds'] )
                            $t->odds = $change['odds'];
                        if ($t->max_point > $change['max_point'])
                            $t->max_point = $change['max_point'];
                        if ($t->max_point_one > $change['max_point_one'])
                            $t->max_point_one = $change['max_point_one'];
                        $t->change_odds = $change['change_odds'] === 'true'? true: false;
                        $t->change_ex = $change['change_ex'] === 'true'? true: false;
                        $t->change_max = $change['change_max'] === 'true'? true: false;
                        // $t->change_max_one = $change['change_max_one'] === 'true'? true: false;
                        $t->save();
                        // Cache::tags('CustomerType_Game'.$tong->id)->flush();

                        $tO = 
                        // Cache::tags('CustomerType_Game_Original'.'-'.$tong->id)->remember('CustomerType_Game_Original-'.$change['type'].'-'.$change['name'].'-'.$tong->id, env('CACHE_TIME', 0), function () use ($change,$tong) {
                            // return 
                            CustomerType_Game_Original::where('code_type', $change['type'])
                            ->where('game_id', $change['name'])
                            ->where('created_user', $tong->id)->first();
                        // });

                        if ( $tO->exchange_rates < $change['exchange'] )
                            $tO->exchange_rates = $change['exchange'];
                        if ( $tO->odds < $change['odds'] )
                            $tO->odds = $change['odds'];
                        if ($tO->max_point > $change['max_point'])
                            $tO->max_point = $change['max_point'];
                        if ($tO->max_point_one > $change['max_point_one'])
                            $tO->max_point_one = $change['max_point_one'];
                        $tO->change_odds = $change['change_odds'] === 'true'? true: false;
                        $tO->change_ex = $change['change_ex'] === 'true'? true: false;
                        $tO->change_max = $change['change_max'] === 'true'? true: false;
                        // $tO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                        $tO->save();
                        // Cache::tags('CustomerType_Game_Original'.$tong->id)->flush();

                        $khachs = 
                        // Cache::tags('User'.'-'.$tong->id)->remember('user_create-'.$tong->id, env('CACHE_TIME', 0), function () use ($tong) {
                            // return 
                            User::where('user_create',$tong->id)->where('active',0)->get();
                        // });
                        if(count($khachs)>0)
                        {
                            foreach ($khachs as $khach) {
                                $k = 
                                // Cache::tags('CustomerType_Game'.'-'.$khach->id)->remember('CustomerType_Game-'.$change['type'].'-'.$change['name'].'-'.$khach->id, env('CACHE_TIME', 0), function () use ($change,$khach) {
                                    // return 
                                    CustomerType_Game::where('code_type', $change['type'])
                                    ->where('game_id', $change['name'])
                                    ->where('created_user', $khach->id)->first();
                                // });
                                if ($k == null)
                                    continue;
                                if ( $k->exchange_rates < $change['exchange'] )
                                    $k->exchange_rates = $change['exchange'];
                                if ( $k->odds < $change['odds'] )
                                    $k->odds = $change['odds'];
                                if ($k->max_point > $change['max_point'])
                                    $k->max_point = $change['max_point'];
                                if ($k->max_point_one > $change['max_point_one'])
                                    $k->max_point_one = $change['max_point_one'];
                                $k->change_odds = $change['change_odds'] === 'true'? true: false;
                                $k->change_ex = $change['change_ex'] === 'true'? true: false;
                                $k->change_max = $change['change_max'] === 'true'? true: false;
                                // $k->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                $k->save();
                                // Cache::tags('CustomerType_Game'.$khach->id)->flush();

                                // $kO = CustomerType_Game_Original::where('code_type', $change['type'])
                                //     ->where('game_id', $change['name'])
                                //     ->where('created_user', $khach->id)->first();

                                // if ( $kO->exchange_rates < $change['exchange'] )
                                //     $kO->exchange_rates = $change['exchange'];
                                // if ( $kO->odds < $change['odds'] )
                                //     $kO->odds = $change['odds'];
                                // if ($kO->max_point > $change['max_point'])
                                //     $kO->max_point = $change['max_point'];
                                // if ($kO->max_point_one > $change['max_point_one'])
                                //     $kO->max_point_one = $change['max_point_one'];
                                // $kO->change_odds = $change['change_odds'] === 'true'? true: false;
                                // $kO->change_ex = $change['change_ex'] === 'true'? true: false;
                                // $kO->change_max = $change['change_max'] === 'true'? true: false;
                                // $kO->change_max_one = $change['change_max_one'] === 'true'? true: false;
                                // $kO->save();
                            }
                        }
                    }
                }
            }
        }
    }

    public static function UpdateCustomerTypeGameABCMAXPOINTV2($change,$user_id,$auth_id,$isUpdateCMO=true)
    {
        $customerType =  CustomerType_Game::where('game_id',$change['name'])
                    ->where('created_user',$user_id)->get();
        $user = User::where('id',$user_id)->get()->first();

        if ($user_id == 274){
            $gameUpdate = GameHelpers::GetGameByGameCode($change['name']);
            HistoryHelpers::ActiveHistorySave($user,$user,"thay đổi giới hạn cược " . $gameUpdate->name,"");
        } 

        // if ($auth_id == $user_id)
            foreach ($customerType as $customerTypeOne) {
                if ($change['name'] > 4000)
                    $customerTypeOne->odds = $change['odds'];
                $customerTypeOne->max_point_one = $change['max_point_one'];
                try{
                    $customerTypeOne->max_point = $change['max_point'];
                    $customerTypeOne->change_max_one = (int)$change['change_max_one'];
                }catch(\Exception $ex){
                    throw $ex;
                }
                $customerTypeOne->save();
                // if ($user->roleid == 6){
                //     SabaHelpers::CreateMember($user->name);
                //     SabaHelpers::SetMemberBetSetting($user->name,$customerTypeOne);
                //     \Log::info('SetMemberBetSetting UpdateCustomerTypeGameABCMAXPOINTV2');
                // }
            }

        $customerTypeOg =  CustomerType_Game_Original::where('game_id',$change['name'])
                    ->where('created_user',$user_id)->get();
        foreach ($customerTypeOg as $customerTypeOne) {
            if ($change['name'] > 4000)
                $customerTypeOne->odds = $change['odds'];
            $customerTypeOne->max_point = $change['max_point'];
            $customerTypeOne->max_point_one = $change['max_point_one'];
            try{
                $customerTypeOne->change_max_one = (int)$change['change_max_one'];
            }catch(\Exception $ex){
                throw $ex;
            }
            $customerTypeOne->save();
        } 
        GameHelpers::UpdateCustomerTypeGameABCMAXPOINTV2Child($change,$user_id,false);
    }

    public static function UpdateCustomerTypeGameABCMAXEXV2($change,$user_id,$auth_id,$isUpdateCMO=true)
    {
        $customerType =  CustomerType_Game::where('game_id',$change['name'])
                    ->where('created_user',$user_id)->get();
        $user = User::where('id',$user_id)->get()->first();

        if ($user_id == 274){
            $gameUpdate = GameHelpers::GetGameByGameCode($change['name']);
            HistoryHelpers::ActiveHistorySave($user,$user,"thay đổi giới hạn lên giá " . $gameUpdate->name,"");
        } 

        foreach ($customerType as $customerTypeOne) {
            $customerTypeOne->ratio_ex = $change['ratio_ex'];
            $customerTypeOne->max_ex = $change['max_ex'];
            $customerTypeOne->save();
        }

        $customerTypeOg =  CustomerType_Game_Original::where('game_id',$change['name'])
                    ->where('created_user',$user_id)->get();
        foreach ($customerTypeOg as $customerTypeOne) {
            $customerTypeOne->ratio_ex = $change['ratio_ex'];
            $customerTypeOne->max_ex = $change['max_ex'];
            $customerTypeOne->save();
        } 
    }

    public static function UpdateCustomerTypeGameABCMAXPOINTV2Child($change,$user_id,$isUpdateCMO=true)
    {
        // \Log::info('UpdateCustomerTypeGameABCMAXPOINTV2Child');
        // $userChild = User::where('user_create',$user_id)->get();
        $userChild = UserHelpers::GetAllUservIDByID($user_id);
        // $user = User::where('id',$user_id)->get()->first();
        // foreach($userChild as $child)
        {
            $customerType =  CustomerType_Game::where('game_id',$change['name'])
                    ->whereIn('created_user',$userChild)->get();
            foreach ($customerType as $customerTypeOne) {
                if (($change['name'] > 4000 && $change['name'] < 7000) || $change['name'] >= 8000)
                    if ($customerTypeOne->odds > $change['odds'])
                        $customerTypeOne->odds = $change['odds'];
                if ($customerTypeOne->max_point > $change['max_point'])
                    $customerTypeOne->max_point = $change['max_point'];
                if ($customerTypeOne->max_point_one > $change['max_point_one'])
                    $customerTypeOne->max_point_one = $change['max_point_one'];
                
                try{
                    if ($customerTypeOne->change_max_one > $change['change_max_one'])
                        $customerTypeOne->change_max_one = (int)$change['change_max_one'];
                }catch(\Exception $ex){
                    // throw $ex;
                }
                $customerTypeOne->save();
                // if ($child->roleid == 6){
                //     SabaHelpers::CreateMember($child->name);
                //     SabaHelpers::SetMemberBetSetting($child->name,$customerTypeOne);
                //     \Log::info('SetMemberBetSetting UpdateCustomerTypeGameABCMAXPOINTV2Child');
                // }
                    
            }

            $customerTypeOg =  CustomerType_Game_Original::where('game_id',$change['name'])
                ->whereIn('created_user',$userChild)->get();
            foreach ($customerTypeOg as $customerTypeOne) {
                // if ($change['name'] > 4000)
                //     if ($customerTypeOne->odds > $change['odds'])
                //         $customerTypeOne->odds = $change['odds'];
                if ($customerTypeOne->max_point > $change['max_point'])
                    $customerTypeOne->max_point = $change['max_point'];
                if ($customerTypeOne->max_point_one > $change['max_point_one'])
                    $customerTypeOne->max_point_one = $change['max_point_one'];
                try{
                    // if ($customerTypeOne->change_max_one > $change['change_max_one'])
                        $customerTypeOne->change_max_one = (int)$change['change_max_one'];
                }catch(\Exception $ex){
                    // throw $ex;
                }
                $customerTypeOne->save();
            }

            // GameHelpers::UpdateCustomerTypeGameABCMAXPOINTV2Child($change,$child->id,false);
        }
    }

    public static function UpdateCustomerTypeGameABCMAX($change,$user_id)
    {
        $customerType =  CustomerType_Game::where('game_id',$change['name'])
                    ->where('created_user',$user_id)->get();
                    foreach ($customerType as $customerTypeOne) {
                        # code...
                        $customerTypeOne->max_point = $change['max_point'];
                        $customerTypeOne->max_point_one = $change['max_point_one'];

                        try{
                            $customerTypeOne->change_max_one = (int)$change['change_max_one'];
                        }catch(\Exception $ex){
                            throw $ex;
                        }
                        
                        $customerTypeOne->save();
                    }
        // Cache::tags('CustomerType_Game'.$user_id)->flush();
        // $customerType->exchange_rates = $change['exchange'];
        // $customerType->odds = $change['odds'];
        
        // $customerType->change_odds = $change['change_odds'] === 'true'? true: false;
        // $customerType->change_ex = $change['change_ex'] === 'true'? true: false;
        // $customerType->change_max = $change['change_max'] === 'true'? true: false;
        // $customerType->change_max_one = $change['change_max_one'] === 'true'? true: false;
        
        // $argents = User::where('user_create',$user_id)->where('roleid','<>',6)->get();
        // if(count($argents)>0)
        // {
        //     foreach ($argents as $argent)
        //     {
        //         $a =  CustomerType_Game::where('code_type',$change['type'])
        //             ->where('game_id',$change['name'])
        //             ->where('created_user',$argent->id)->first();
        //         $a->exchange_rates = $change['exchange'];
        //         $a->odds = $change['odds'];
        //         $a->max_point = $change['max_point'];
        //         $a->max_point_one = $change['max_point_one'];
        //         $a->change_odds = $change['change_odds'] === 'true'? true: false;
        //         $a->change_ex = $change['change_ex'] === 'true'? true: false;
        //         $a->change_max = $change['change_max'] === 'true'? true: false;
        //         $a->change_max_one = $change['change_max_one'] === 'true'? true: false;
        //         $a->save();
        //         $tongs = User::where('user_create',$argent->id)->where('roleid','<>',6)->get();
        //         if(count($tongs)>0)
        //         {
        //             foreach ($tongs as $tong) {
        //                 $t = CustomerType_Game::where('code_type', $change['type'])
        //                     ->where('game_id', $change['name'])
        //                     ->where('created_user', $tong->id)->first();
        //                 $t->exchange_rates = $change['exchange'];
        //                 $t->odds = $change['odds'];
        //                 $t->max_point = $change['max_point'];
        //                 $t->max_point_one = $change['max_point_one'];
        //                 $t->change_odds = $change['change_odds'] === 'true'? true: false;
        //                 $t->change_ex = $change['change_ex'] === 'true'? true: false;
        //                 $t->change_max = $change['change_max'] === 'true'? true: false;
        //                 $t->change_max_one = $change['change_max_one'] === 'true'? true: false;
        //                 $t->save();
        //             }
        //         }
        //     }
        // }
    }

    public static function UpdateGameAXY($change,$user_id)
    {
        if ($user_id == 274){
            $customerAXYone =  Game::where('game_code',$change['name'])->first();
            $customerAXYone->a = $change['aa'];
            $customerAXYone->y = $change['yy'];
            $customerAXYone->x = $change['xx'];

            $customerAXYone->a2 = $change['aa2'];
            $customerAXYone->y2 = $change['yy2'];
            $customerAXYone->x2 = $change['xx2'];

            $customerAXYone->a3 = $change['aa3'];
            $customerAXYone->y3 = $change['yy3'];
            $customerAXYone->x3 = $change['xx3'];
            $customerAXYone->save();
            $user = User::where('id',$user_id)->get()->first();
            $gameUpdate = GameHelpers::GetGameByGameCode($change['name']);
            HistoryHelpers::ActiveHistorySave($user,$user,"thay đổi hệ số lên giá " . $gameUpdate->name,"");
        } else{
            //using dynamic class
            $gameTableId = 'App\Game_'.$user_id;
            $ref = new $gameTableId;
            $customerAXYone =  $ref::where('game_code',$change['name'])->first();
            $customerAXYone->a = $change['aa'];
            $customerAXYone->y = $change['yy'];
            $customerAXYone->x = $change['xx'];

            $customerAXYone->a2 = $change['aa2'];
            $customerAXYone->y2 = $change['yy2'];
            $customerAXYone->x2 = $change['xx2'];

            $customerAXYone->a3 = $change['aa3'];
            $customerAXYone->y3 = $change['yy3'];
            $customerAXYone->x3 = $change['xx3'];
            $customerAXYone->save();
        }
    }

    public static function DeleteGame_Number()
    {
        Game_Number::where('id','<>', 0)->delete();
    }

    public static function ChuyenDoiDai($slug){
        $daiName = '';
        if ($slug==21){

                $dayofweek = date('w');		
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Tiền Giang';
                        break;
                    case 1:
                        $daiName = 'TP. HCM';
                        break;
                    case 2:
                        $daiName = 'Bến Tre';
                        break;
                    case 3:
                        $daiName = 'Đồng Nai';
                        break;
                    case 4:
                        $daiName = 'Tây Ninh';
                        break;
                    case 5:
                        $daiName = 'Vĩnh Long';
                        break;
                    case 6:
                        $daiName = 'TP. HCM';
                        break;
                    default:
                        # code...
                        break;
                }
        }
        else

        if ($slug==22){

                $dayofweek = date('w');		
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Kiên Giang';
                        break;
                    case 1:
                        $daiName = 'Đồng Tháp';
                        break;
                    case 2:
                        $daiName = 'Vũng Tàu';
                        break;
                    case 3:
                        $daiName = 'Cần Thơ';
                        break;
                    case 4:
                        $daiName = 'An Giang';
                        break;
                    case 5:
                        $daiName = 'Bình Dương';
                        break;
                    case 6:
                        $daiName = 'Long An';
                        break;
                    default:
                        # code...
                        break;
                }
            }
            else

        if ($slug==31){

                $dayofweek = date('w');		
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Kon tum';
                        break;
                    case 1:
                        $daiName = 'Thừa T. Huế';
                        break;
                    case 2:
                        $daiName = 'Đắk Lắk';
                        break;
                    case 3:
                        $daiName = 'Đà Nẵng';
                        break;
                    case 4:
                        $daiName = 'Bình Định';
                        break;
                    case 5:
                        $daiName = 'Gia Lai';
                        break;
                    case 6:
                        $daiName = 'Đà Nẵng';
                        break;
                    default:
                        # code...
                        break;
                }
            }
else
        if ($slug==32){

                $dayofweek = date('w');		
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Khánh Hòa';
                        break;
                    case 1:
                        $daiName = 'Phú Yên';
                        break;
                    case 2:
                        $daiName = 'Quảng Nam';
                        break;
                    case 3:
                        $daiName = 'Khánh Hòa';
                        break;
                    case 4:
                        $daiName = 'Quảng Trị';
                        break;
                    case 5:
                        $daiName = 'Ninh Thuận';
                        break;
                    case 6:
                        $daiName = 'Quảng Ngãi';
                        break;
                    default:
                        # code...
                        break;
                }
        }
        return $daiName;
    }

    public static function ChuyenDoiDaiByDate($slug,$dateStr){
        $daiName = '';
        $dayofweek = date('w',$dateStr);		
        if ($slug==21){
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Tiền Giang';
                        break;
                    case 1:
                        $daiName = 'TP. HCM';
                        break;
                    case 2:
                        $daiName = 'Bến Tre';
                        break;
                    case 3:
                        $daiName = 'Đồng Nai';
                        break;
                    case 4:
                        $daiName = 'Tây Ninh';
                        break;
                    case 5:
                        $daiName = 'Vĩnh Long';
                        break;
                    case 6:
                        $daiName = 'TP. HCM';
                        break;
                    default:
                        # code...
                        break;
                }
        }
        else

        if ($slug==22){	
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Kiên Giang';
                        break;
                    case 1:
                        $daiName = 'Đồng Tháp';
                        break;
                    case 2:
                        $daiName = 'Vũng Tàu';
                        break;
                    case 3:
                        $daiName = 'Cần Thơ';
                        break;
                    case 4:
                        $daiName = 'An Giang';
                        break;
                    case 5:
                        $daiName = 'Bình Dương';
                        break;
                    case 6:
                        $daiName = 'Long An';
                        break;
                    default:
                        # code...
                        break;
                }
            }
            else

        if ($slug==31){	
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Kon tum';
                        break;
                    case 1:
                        $daiName = 'Thừa T. Huế';
                        break;
                    case 2:
                        $daiName = 'Đắk Lắk';
                        break;
                    case 3:
                        $daiName = 'Đà Nẵng';
                        break;
                    case 4:
                        $daiName = 'Bình Định';
                        break;
                    case 5:
                        $daiName = 'Gia Lai';
                        break;
                    case 6:
                        $daiName = 'Đà Nẵng';
                        break;
                    default:
                        # code...
                        break;
                }
            }
else
        if ($slug==32){	
                $daiName = '';
                switch ($dayofweek) {
                    case 0:
                        $daiName = 'Khánh Hòa';
                        break;
                    case 1:
                        $daiName = 'Phú Yên';
                        break;
                    case 2:
                        $daiName = 'Quảng Nam';
                        break;
                    case 3:
                        $daiName = 'Khánh Hòa';
                        break;
                    case 4:
                        $daiName = 'Quảng Trị';
                        break;
                    case 5:
                        $daiName = 'Ninh Thuận';
                        break;
                    case 6:
                        $daiName = 'Quảng Ngãi';
                        break;
                    default:
                        # code...
                        break;
                }
        }
        return $daiName;
    }
    
}