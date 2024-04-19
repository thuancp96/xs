<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\History;
use App\QuickPlayRecord;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Helpers\XoSo;
use App\ChucNang;
use App\Bangso;
use App\Helpers\RoleHelpers;
use App\Helpers\BoSoHelpers;
use App\Http\Requests;
use App\Location;
use App\Helpers\LocationHelpers;
use App\Helpers\UserHelpers;
use App\Helpers\GameHelpers;
use Illuminate\Http\Request;
use App\Helpers\XoSoRecordHelpers;
use App\Helpers\NotifyHelpers;
use Sunra\PhpSimple\HtmlDomParser;
use App\Helpers\Curl;
use App\Helpers\HistoryHelpers;
use App\Helpers\SabaHelpers;
use App\XoSoResult;
use \Cache;
use Exception;
use Illuminate\Support\Facades\Input;

class QuickbetHelpers
{
    public function vn_to_str($str)
    {

        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ|₫',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            'x' => '×',
        );

        foreach ($unicode as $nonUnicode => $uni) {

            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        //    $str = str_replace(' ','_',$str);

        return $str;
    }

    public static function clean($string) {
        $unicode = array(

            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

            'd' => 'đ',

            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

            'i' => 'í|ì|ỉ|ĩ|ị',

            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',

            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'D' => 'Đ',

            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',

            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            'x' => '×|₫',
        );

        foreach ($unicode as $nonUnicode => $uni) {

            $string = preg_replace("/($uni)/i", $nonUnicode, $string);
        }
        $string = trim($string);
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
     }

    public function str_to_price($str)
    {
        // n, k, d, ng, tr, diem, trieu, ngh, ngin, nghin
        $arrprice = array('n', 'k', 'd', 'ng', 'tr', 'diem', 'trieu', 'ngh', 'ngin', 'nghin');
        $arrpricevalue = array('1', '1', '1', '1', '1000', '1', '1000', '1', '1', '1');
        $maprice = '1';
        $count = 0;
        foreach ($arrprice as $price) {
            if (strpos($str, $price) !== false) {
                $maprice = $arrpricevalue[$count];
                $str = str_replace($price, '', $str);
                break;
            }
            $count++;
        }
        preg_match_all('!\d+!', $str, $matches);
        return array($maprice, $matches[0]);
    }

    public function convert_bo_so($str)
    {
        // $bangso = Bangso::get();
        $bangso = BoSoHelpers::GetAllBangSo(0);
        for ($i = 0; $i < 10; $i++) {
            foreach ($bangso as $value) {
                # code...
                $kyhieu = explode(',', $value->kyhieu);
                $boso = $value->boso;
                $kyhieudb = '';
                if (strpos($value->kyhieu, 'tong') !== false)
                    $kyhieudb = 'tong';
                if (strpos($value->kyhieu, 'hieu') !== false)
                    $kyhieudb = 'hieu';
                if (strpos($value->kyhieu, 'dauduoi') !== false)
                    $kyhieudb = 'dauduoi';
                if (strpos($value->kyhieu, 'daudit') !== false)
                    $kyhieudb = 'daudit';
                if (strpos($value->kyhieu, 'dau') !== false)
                    $kyhieudb = 'dau';
                if (strpos($value->kyhieu, 'duoi') !== false)
                    $kyhieudb = 'duoi';
                if (strpos($value->kyhieu, 'dit') !== false)
                    $kyhieudb = 'dit';
                if (strpos($value->kyhieu, 'boso') !== false)
                    $kyhieudb = 'boso';
                if (strpos($value->kyhieu, 'cham') !== false)
                    $kyhieudb = 'cham';
                if (strpos($value->kyhieu, 'dinh') !== false)
                    $kyhieudb = 'dinh';
                if (strpos($value->kyhieu, 'co') !== false)
                    $kyhieudb = 'co';
                if (strpos($value->kyhieu, 'dan') !== false)
                    $kyhieudb = 'dan';

                    //. ' ' . $kyhieudb
                $str = str_replace($kyhieu, ' ' . $boso , $str);
                // echo $str ." ";
            }
        }
        if (
            strpos($str, "thantai") === false && strpos($str, "than tai") === false && strpos($str, "dau dac biet") === false && strpos($str, "dau dacbiet") === false
            && strpos($str, "dau nhat") === false && strpos($str, "daunhat") === false
        )
            $str = str_replace(['tong', 'hieu', 'dau', 'duoi', 'dit', 'dauduoi', 'daudit', 'boso', 'cham', 'dinh', 'co', 'dan'], ' ', $str);

        return $str;

        $str = str_replace(['tong0', 'tong 0'], '19,91,28,82,37,73,46,64,55,00', $str);
        $str = str_replace(['tong1', 'tong 1'], '01,10,29,92,38,83,47,74,56,65', $str);
        $str = str_replace(['tong2', 'tong 2'], '02,20,39,93,48,84,57,75,11,66', $str);
        $str = str_replace(['tong3', 'tong 3'], '03,30,12,21,49,94,58,85,67,76', $str);
        $str = str_replace(['tong4', 'tong 4'], '04,40,13,31,59,95,68,86,22,77', $str);
        $str = str_replace(['tong5', 'tong 5'], '05,50,14,41,23,32,69,96,78,87', $str);
        $str = str_replace(['tong6', 'tong 6'], '06,60,15,51,24,42,79,97,33,88', $str);
        $str = str_replace(['tong7', 'tong 7'], '07,70,16,61,25,52,34,43,89,98', $str);
        $str = str_replace(['tong8', 'tong 8'], '08,80,17,71,26,62,35,53,44,99', $str);
        $str = str_replace(['tong9', 'tong 9'], '09,90,18,81,27,72,36,63,45,54', $str);

        $str = str_replace(['hieu0', 'hieu 0'], '00,11,22,33,44,55,66,77,88,99', $str);
        $str = str_replace(['hieu1', 'hieu 1'], '01,10,12,21,23,32,34,43,45,54,56,65,67,76,78,87,89,98,90,09', $str);
        $str = str_replace(['hieu2', 'hieu 2'], '02,20,24,42,46,64,68,86,80,08,13,31,35,53,57,75,79,97,91,19', $str);
        $str = str_replace(['hieu3', 'hieu 3'], '03,30,36,63,69,96,92,29,25,52,58,85,81,18,41,14,74,47,07,70', $str);
        $str = str_replace(['hieu4', 'hieu 4'], '04,40,48,84,82,28,26,62,60,06,15,51,59,95,93,39,37,73,71,17', $str);
        $str = str_replace(['hieu5', 'hieu 5'], '05,50,16,61,27,72,38,83,49,94', $str);

        $str = str_replace(['dau0', 'dau 0'], '00,01,02,03,04,05,06,07,08,09', $str);
        $str = str_replace(['dau1', 'dau 1'], '10,11,12,13,14,15,16,17,18,19', $str);
        $str = str_replace(['dau2', 'dau 2'], '20,21,22,23,24,25,26,27,28,29', $str);
        $str = str_replace(['dau3', 'dau 3'], '30,31,32,33,34,35,36,37,38,39', $str);
        $str = str_replace(['dau4', 'dau 4'], '40,41,42,43,44,45,46,47,48,49', $str);
        $str = str_replace(['dau5', 'dau 5'], '50,51,52,53,54,55,56,57,58,59', $str);
        $str = str_replace(['dau6', 'dau 6'], '60,61,62,63,64,65,66,67,68,69', $str);
        $str = str_replace(['dau7', 'dau 7'], '70,71,72,73,74,75,76,77,78,79', $str);
        $str = str_replace(['dau8', 'dau 8'], '80,81,82,83,84,85,86,87,88,89', $str);
        $str = str_replace(['dau9', 'dau 9'], '90,91,92,93,94,95,96,97,98,99', $str);

        $str = str_replace(['duoi0', 'duoi 0', 'dit0', 'dit 0'], '00,10,20,30,40,50,60,70,80,90', $str);
        $str = str_replace(['duoi1', 'dit1', 'duoi 1', 'dit 1'], '01,11,21,31,41,51,61,71,81,91', $str);
        $str = str_replace(['duoi2', 'dit2', 'duoi 2', 'dit 2'], '02,12,22,32,42,52,62,72,82,92', $str);
        $str = str_replace(['duoi3', 'dit3', 'duoi 3', 'dit 3'], '03,13,23,33,43,53,63,73,83,93', $str);
        $str = str_replace(['duoi4', 'dit4', 'duoi 4', 'dit 4'], '04,14,24,34,44,54,64,74,84,94', $str);
        $str = str_replace(['duoi5', 'dit5', 'duoi 5', 'dit 5'], '05,15,25,35,45,55,65,75,85,95', $str);
        $str = str_replace(['duoi6', 'dit6', 'duoi 6', 'dit 6'], '06,16,26,36,46,56,66,76,86,96', $str);
        $str = str_replace(['duoi7', 'dit7', 'duoi 7', 'dit 7'], '07,17,27,37,47,57,67,77,87,97', $str);
        $str = str_replace(['duoi8', 'dit8', 'duoi 8', 'dit 8'], '08,18,28,38,48,58,68,78,88,98', $str);
        $str = str_replace(['duoi9', 'dit9', 'duoi 9', 'dit 9'], '09,19,29,39,49,59,69,79,89,99', $str);

        $str = str_replace(['boso00', 'bo00', 'day00'], '00,55,05,50', $str);
        $str = str_replace(['boso11', 'bo11', 'day11'], '11,66,16,61', $str);
        $str = str_replace(['boso22', 'bo22', 'day22'], '22,77,27,72', $str);
        $str = str_replace(['boso33', 'bo33', 'day33'], '33,88,38,83', $str);
        $str = str_replace(['boso44', 'bo44', 'day44'], '44,99,49,94', $str);
        $str = str_replace(['boso01', 'bo01', 'day01'], '01,10,06,60,51,15,56,65', $str);
        $str = str_replace(['boso02', 'bo02', 'day02'], '02,20,07,70,25,52,57,75', $str);
        $str = str_replace(['boso03', 'bo03', 'day03'], '03,30,08,80,35,53,58,85', $str);
        $str = str_replace(['boso04', 'bo04', 'day04'], '04,40,09,90,45,54,59,95', $str);
        $str = str_replace(['boso12', 'bo12', 'day12'], '12,21,17,71,26,62,67,76', $str);
        $str = str_replace(['boso13', 'bo13', 'day13'], '13,31,18,81,36,63,68,86', $str);
        $str = str_replace(['boso14', 'bo14', 'day14'], '14,41,19,91,46,64,69,96', $str);
        $str = str_replace(['boso15', 'bo15', 'day15'], '23,32,28,82,73,37,78,87', $str);
        $str = str_replace(['boso24', 'bo24', 'day24'], '24,42,29,92,74,47,79,97', $str);
        $str = str_replace(['boso34', 'bo34', 'day34'], '34,43,39,93,84,48,89,98', $str);

        $str = str_replace(['cham0', 'dinh0', 'co0'], '01,10,02,20,03,30,04,40,05,50,06,60,07,70,08,80,09,90,00', $str);
        $str = str_replace(['cham1', 'dinh1', 'co1'], '01,10,12,21,13,31,14,41,15,51,16,61,17,71,18,81,19,91,11', $str);
        $str = str_replace(['cham2', 'dinh2', 'co2'], '02,20,12,21,23,32,24,42,25,52,26,62,27,72,28,82,29,92,22', $str);
        $str = str_replace(['cham3', 'dinh3', 'co3'], '03,30,13,31,23,32,34,43,35,53,36,63,37,73,38,83,39,93,33', $str);
        $str = str_replace(['cham4', 'dinh4', 'co4'], '04,40,14,41,24,42,34,43,45,54,46,64,47,74,48,84,49,94,44', $str);
        $str = str_replace(['cham5', 'dinh5', 'co5'], '51,15,52,25,53,35,54,45,05,50,56,65,57,75,58,85,59,95,55', $str);
        $str = str_replace(['cham6', 'dinh6', 'co6'], '61,16,62,26,63,36,64,46,65,56,06,60,67,76,68,86,69,96,66', $str);
        $str = str_replace(['cham7', 'dinh7', 'co7'], '71,17,72,27,73,37,74,47,75,57,76,67,07,70,78,87,79,97,77', $str);
        $str = str_replace(['cham8', 'dinh8', 'co8'], '81,18,82,28,83,38,84,48,85,58,86,68,87,78,08,80,89,98,88', $str);
        $str = str_replace(['cham9', 'dinh9', 'co9'], '91,19,92,29,93,39,94,49,95,59,96,69,97,79,98,89,09,90,99', $str);

        $str = str_replace('danchia3', '00,03,06,09,12,15,18,21,24,27,30,33,36,39,42,45,48,51,54,57,60,63,66,69,72,75,78,81,84,87,90,93,96,99', $str);
        $str = str_replace('danchia3du1', '01,04,07,10,13,16,19,22,25,28,31,34,37,40,43,46,49,52,55,58,61,64,67,70,73,76,79,82,85,88,91,94,97', $str);
        $str = str_replace('danchia3du2', '02,05,08,11,14,17,20,23,26,29,32,35,38,41,44,47,50,53,56,59,62,65,68,71,74,77,80,83,86,89,92,95,98', $str);

        $str = str_replace('dan05', '00,01,02,03,04,05,10,11,12,13,14,15,20,21,22,23,24,25,30,31,32,33,34,35,40,41,42,43,44,45,50,51,52,53,54,55', $str);
        $str = str_replace('dan16', '11,12,13,14,15,16,21,22,23,24,25,26,31,32,33,34,35,36,41,42,43,44,45,46,51,52,53,54,55,56,61,62,63,64,65,66', $str);
        $str = str_replace('dan27', '22,23,24,25,26,27,32,33,34,35,36,37,42,43,44,45,46,47,52,53,54,55,56,57,62,63,64,65,66,67,72,73,74,75,76,77', $str);
        $str = str_replace('dan38', '33,34,35,36,37,38,43,44,45,46,47,48,53,54,55,56,57,58,63,64,65,66,67,68,73,74,75,76,77,78,83,84,85,86,87,88', $str);
        $str = str_replace('dan49', '44,45,46,47,48,49,54,55,56,57,58,59,64,65,66,67,68,69,74,75,76,77,78,79,84,85,86,87,88,89,94,95,96,97,98,99', $str);

        $str = str_replace(['sokeplech', 'kep l', 'keplech', 'kep lech'], '05,50,16,61,27,72,38,83,49,94', $str);
        $str = str_replace(['sokepbang', 'kep=', 'kep ='], '00,55,11,66,22,77,33,88,44,99', $str);
        $str = str_replace(['sokepam', 'kep - ', 'kep-', 'kepam', 'kep am'], '07,70,14,41,29,92,36,63,58,85', $str);
        $str = str_replace(['satkepbang', 'satkep=', 'satk=', 'sat k =', 'sat k='], '01,10,12,21,23,32,34,43,45,54,56,65,67,76,78,87,89,98', $str);
        $str = str_replace(['satkeplach', 'satkepl', 'satkl', 'sat k l', 'sat kl'], '04,06,15,17,26,28,37,39,48,51,60,62,71,73,82,84,93,95', $str);

        $str = str_replace(['chanchan', 'chan chan'], '00,22,44,66,88,02,20,04,40,06,60,08,80,24,42,26,62,28,82,46,64,48,84,68,86', $str);
        $str = str_replace(['lele', 'le le'], '11,33,55,77,99,13,31,15,51,17,71,19,91,35,53,37,73,39,93,57,75,59,95,79,97', $str);
        $str = str_replace(['chanle', 'chan le'], '01,03,05,07,09,21,23,25,27,29,41,43,45,47,49,61,63,65,67,69,81,83,85,87,89', $str);
        $str = str_replace(['lechan', 'le chan'], '10,12,14,16,18,30,32,34,36,38,50,52,54,56,58,70,72,74,76,78,90,92,94,96,98', $str);

        $str = str_replace(['nhonho', 'nho nho'], '00,11,22,33,44,01,10,02,20,03,30,04,40,12,21,13,31,14,41,23,32,24,42,34,43', $str);
        $str = str_replace(['toto', 'to to'], '55,66,77,88,99,56,65,57,75,58,85,59,95,67,76,68,86,69,96,78,87,79,97,89,98', $str);
        $str = str_replace(['nhoto', 'nho to'], '05,06,07,08,09,15,16,17,18,19,25,26,27,28,29,35,36,37,38,39,45,46,47,48,49', $str);
        $str = str_replace(['tonho', 'to nho'], '90,91,92,93,94,80,81,82,83,84,70,71,72,73,74,60,61,62,63,64,50,51,52,53,54', $str);

        return $str;
    }

    public function str_to_game_code($str)
    {
        // echo $str;
        $kieucuocs = BoSoHelpers::GetAllBangSo(1);

        //  v1
        $arrde = array('de', 'dacbiet', 'dac biet', 'db');
        // -	Lô: lo
        $arrlo = array('lo');
        // -	Nhất: nhat
        $arrnhat = array('nhat');
        // -	Xiên 2: x2, xien2, xien 2
        $arrxien2 = array('x2', 'xien2', 'xien 2 ', 'xienq2');
        // -	Xiên 3: x3, xien3, xiên 3
        $arrxien3 = array('x3', 'xien3', 'xien 3 ', 'xienq3');
        // -	Xiên 4: x4, xien4, xien 4
        $arrxien4 = array('x4', 'xien4', 'xien 4 ', 'xienq4');
        $arrxien22 = array('xien');
        // -	Xiên Nháy: xn, xnhay, xiennhay, x nhay, xien nhay
        $arrxiennhay = array('xn', 'xnhay', 'xiennhay', 'x nhay', 'xien nhay');
        // -	Xiên Quay: xq, xquay, x quay, xienquay, xien quay
        $arrxienquay = array('xq', 'xienq', 'xquay', 'x quay', 'xienquay', 'xien quay');
        // $arrxienquay = array();
        // -	3 càng: 3cang, 3 cang, 3 so, de 3 cang, de 3 so
        $arr3cang = array('3cang', '3 cang', '3 so', 'de 3 cang', 'de 3 so', 'bacang');
        // -	Đề Nhất: 2cua, 2 cửa, denhat, de nhat, de va nhat, de+nhat, de+ nhat, de +nhat, de + nhat ( giữa có ký tự bỏ đi thu được đề nhất), de 2 cua, de 2cua
        $arrdenhat = array('2cua', '2 cua', 'denhat', 'de nhat', 'de 2 cua', 'de 2cua');

        //  v2
        // -	Lô: lo live
        $arrlolive = array('lo live', 'lolive');
        //3 càng nhất - 56
        $arr3cangnhat = array('3cangnhat', '3 cang nhat', '3cang nhat', '3 so nhat', 'nhat 3 cang', 'nhat 3 so', 'bacangnhat');
        //đề trượt - 15
        $arrdetruot = array('de truot', 'detruot');
        //lô trượt 1 4 8 10 - 16 19 20 21
        $arrlotruot1 = array('lo truot 1', 'lotruot 1', 'lotruot1');
        $arrlotruot4 = array('lo truot 4', 'lotruot 4', 'lotruot4');
        $arrlotruot8 = array('lo truot 8', 'lotruot 8', 'lotruot8');
        $arrlotruot10 = array('lo truot 10', 'lotruot 10', 'lotruot10');
        //đầu thần tài - 25
        $arrdauthantai = array('dau than tai', 'dauthantai', 'dau thantai');
        //đuôi thần tài - 26
        $arrduoithantai = array('duoi than tai', 'duoithantai', 'duoi thantai');
        //đầu đặc biệt - 27
        $arrdaudacbiet = array('dau dac biet', 'daudacbiet', 'dau dacbiet');
        //đầu nhất - 28
        $arrduoinhat = array('daunhat', 'dau nhat');
        //giải khác 2.1 7.4 - 31 55
        $arrgiai21 = array('giai21', 'giai2.1', 'giai 21', 'giai 2.1');
        $arrgiai22 = array('giai22', 'giai2.2', 'giai 22', 'giai 2.2');
        $arrgiai31 = array('giai31', 'giai3.1', 'giai 31', 'giai 3.1');
        $arrgiai32 = array('giai32', 'giai3.2', 'giai 32', 'giai 3.2');
        $arrgiai33 = array('giai33', 'giai3.3', 'giai 33', 'giai 3.3');
        $arrgiai34 = array('giai34', 'giai3.4', 'giai 34', 'giai 3.4');
        $arrgiai35 = array('giai35', 'giai3.5', 'giai 35', 'giai 3.5');
        $arrgiai36 = array('giai36', 'giai3.6', 'giai 36', 'giai 3.6');
        $arrgiai41 = array('giai41', 'giai4.1', 'giai 41', 'giai 4.1');
        $arrgiai42 = array('giai42', 'giai4.2', 'giai 42', 'giai 4.2');
        $arrgiai43 = array('giai43', 'giai4.3', 'giai 43', 'giai 4.3');
        $arrgiai44 = array('giai44', 'giai4.4', 'giai 44', 'giai 4.4');
        $arrgiai51 = array('giai51', 'giai5.1', 'giai 51', 'giai 5.1');
        $arrgiai52 = array('giai52', 'giai5.2', 'giai 52', 'giai 5.2');
        $arrgiai53 = array('giai53', 'giai5.3', 'giai 53', 'giai 5.3');
        $arrgiai54 = array('giai54', 'giai5.4', 'giai 54', 'giai 5.4');
        $arrgiai55 = array('giai55', 'giai5.5', 'giai 55', 'giai 5.5');
        $arrgiai56 = array('giai56', 'giai5.6', 'giai 56', 'giai 5.6');
        $arrgiai61 = array('giai61', 'giai6.1', 'giai 61', 'giai 6.1');
        $arrgiai62 = array('giai62', 'giai6.2', 'giai 62', 'giai 6.2');
        $arrgiai63 = array('giai63', 'giai6.3', 'giai 63', 'giai 6.3');
        $arrgiai71 = array('giai71', 'giai7.1', 'giai 71', 'giai 7.1');
        $arrgiai72 = array('giai72', 'giai7.2', 'giai 72', 'giai 7.2');
        $arrgiai73 = array('giai73', 'giai7.3', 'giai 73', 'giai 7.3');
        $arrgiai74 = array('giai74', 'giai7.4', 'giai 74', 'giai 7.4');
        //reset from db
        foreach ($kieucuocs as $value) {
            # code...
            $kyhieu = explode(',', $value->kyhieu);
            $boso = explode(',', $value->boso);
            // $value->boso;
            // $str = str_replace($kyhieu,$boso,$str);
            switch ($value->kyhieu) {
                case 'đề nhất':
                    $arrdenhat = $boso;
                    break;
                case '3 càng':
                    $arr3cang = $boso;
                    break;
                case 'xiên quay':
                    // $arrxienquay = $boso;
                    break;
                case 'xiên nháy':
                    $arrxiennhay = $boso;
                    break;
                case 'xiên 4':
                    // $arrxien4 = $boso;
                    break;
                case 'xiên 3':
                    // $arrxien3 = $boso;
                    break;
                case 'xiên 2':
                    // $arrxien2 = $boso;
                    break;
                case 'nhất':
                    $arrnhat = $boso;
                    break;
                case 'lô live':
                    $arrlolive = $boso;
                    break;
                case 'lô':
                    $arrlo = $boso;
                    break;
                case 'đề':
                    $arrde = $boso;
                    break;
            }
        }

        // return $str;

        // -	Đề: de, dacbiet, dac biet
        // var_dump($str);

        $arrcuoc = array(
            $arrdenhat, $arrduoinhat, $arrdaudacbiet, $arrdauthantai, $arrduoithantai, $arrlotruot10, $arrlotruot4, $arrlotruot8, $arrlotruot1, $arr3cangnhat, $arr3cang, $arrdetruot, $arrxienquay, $arrxiennhay, $arrxien2, $arrxien3, $arrxien4, $arrnhat, $arrlolive, $arrlo, $arrde
            , $arrgiai21, $arrgiai22, $arrgiai31, $arrgiai32, $arrgiai33, $arrgiai34, $arrgiai35, $arrgiai36, $arrgiai41, $arrgiai42, $arrgiai43, $arrgiai44, 
            $arrgiai51, $arrgiai52, $arrgiai53, $arrgiai54, $arrgiai55, $arrgiai56, $arrgiai61, $arrgiai62, $arrgiai63, $arrgiai71, $arrgiai72, $arrgiai73, $arrgiai74
        );
        $arrgamecode = array([12, 14], 28, 27, 25, 26, 21, 19, 20, 16, 56, 17, 15, [9, 10, 11], 29, 9, 10, 11, 12, 18, 7, 14, 31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55);
        $hascuoc = array(
            false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false,
            false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false
            ,false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false,  false,  false,  false,  false,  false,  false, false, false, false, false, false, false, false, false, false, false, false, false, false, false, false
        );
        $count = 0;
        $hasgamecode = array();
        foreach ($arrcuoc as $cuoc) {
            foreach ($cuoc as $item) {
                if (strpos($str, $item) !== false) {
                    $hascuoc[$count] = true;
                    if ($count == 0) {
                        $hasgamecode = array(array($arrgamecode[$count][0], $item));
                        array_push($hasgamecode, array($arrgamecode[$count][1], $item));
                    } else if ($count == 12) {
                        $hasgamecode = array(array($arrgamecode[$count][0], $item));
                        array_push($hasgamecode, array($arrgamecode[$count][1], $item));
                        array_push($hasgamecode, array($arrgamecode[$count][2], $item));
                    } else {
                        array_push($hasgamecode, array($arrgamecode[$count], $item));
                    }
                    $str = str_replace($item, '', $str);
                }
            }
            $count++;
        }
        // var_dump($hasgamecode);
        return $hasgamecode;
        //array($hascuoc,$str);
    }

    public function game_code_to_str($game_code)
    {
        $str = '';
        switch ($game_code) {
            case 7:
                $str = 'lô';
                break;
            case 18:
                $str = 'lô live';
                break;
            case 14:
                $str = 'đề';
                break;
            case 15:
                $str = 'đề trượt';
                break;
            case 25:
                $str = 'đầu thần tài';
                break;
            case 26:
                $str = 'đuôi thần tài';
                break;
            case 27:
                $str = 'đầu đặc biệt';
                break;
            case 28:
                $str = 'đầu nhất';
                break;
            case 16:
                $str = 'lô trượt 1';
                break;
            case 19:
                $str = 'lô trượt 4';
                break;
            case 20:
                $str = 'lô trượt 8';
                break;
            case 21:
                $str = 'lô trượt 10';
                break;
            case 12:
                $str = 'nhất';
                break;
            case 56:
                $str = '3 càng nhất';
                break;
            case 17:
                $str = '3 càng';
                break;
            case 9:
                $str = 'xiên2';
                break;
            case 10:
                $str = 'xiên3';
                break;
            case 11:
                $str = 'xiên4';
                break;
            case 31:
                $str = 'giải 2.1';
                break;
            case 32:
                $str = 'giải 2.2';
                break;
            case 33:
                $str = 'giải 3.1';
                break;
            case 34:
                $str = 'giải 3.2';
                break;
            case 35:
                $str = 'giải 3.3';
                break;
            case 36:
                $str = 'giải 3.4';
                break;
            case 37:
                $str = 'giải 3.5';
                break;
            case 38:
                $str = 'giải 3.6';
                break;
            case 39:
                $str = 'giải 4.1';
                break;
            case 40:
                $str = 'giải 4.2';
                break;
            case 41:
                $str = 'giải 4.3';
                break;
            case 42:
                $str = 'giải 4.4';
                break;
            case 43:
                $str = 'giải 5.1';
                break;
            case 44:
                $str = 'giải 5.2';
                break;
            case 45:
                $str = 'giải 5.3';
                break;
            case 46:
                $str = 'giải 5.4';
                break;
            case 47:
                $str = 'giải 5.5';
                break;
            case 48:
                $str = 'giải 5.6';
                break;
            case 49:
                $str = 'giải 6.1';
                break;
            case 50:
                $str = 'giải 6.2';
                break;
            case 51:
                $str = 'giải 6.3';
                break;
            case 52:
                $str = 'giải 7.1';
                break;
            case 53:
                $str = 'giải 7.2';
                break;
            case 54:
                $str = 'giải 7.3';
                break;
            case 55:
                $str = 'giải 7.4';
                break;
        }
        return $str;
    }

    public function _s_has_letters($string)
    {
        return preg_match('/[a-zA-Z]/', $string);
    }

    public function _s_has_numbers($string)
    {
        return preg_match('/\d/', $string);
    }

    public function initquickplay($quicktext)
    {
        $newbet = $quicktext;

        $newbet = $this->vn_to_str($newbet);
        $newbet = strtolower($newbet);

        $newbet = preg_replace('/\s\s+/', ' ', $newbet);

        // echo $newbet;
        $arrRaw = explode(" ", $newbet);
        foreach($arrRaw as &$item){
            if (str_contains($item,"&"))
                $item = "(".$item.") ";
        }
        // var_dump($arrRaw);
        
        $newbet = implode(' ', $arrRaw);
        $newbet = str_replace([') (',')  (','| |','|  |'], ' 0000 ', $newbet);
        $newbet = str_replace(['&'], ',', $newbet);
        
        $result = preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $newbet);

        preg_match_all('/\d+|[a-z]+/i', $newbet, $result);

        // print_r($result[0]);
        
        $newbet = implode(' ', $result[0]);
        $newbet = str_replace('moi con', ' x', $newbet);
        $newbet = str_replace('2 c', '2c', $newbet);
        $newbet = $newbet . ' ';
        // echo $newbet;
        $newbet = preg_replace('/\s\s+/', ' ', $newbet);
        $newbet = trim($newbet);
        $newbet = str_replace(',', ' ', $newbet);

        $newbet = str_replace('moi cap', 'x', $newbet);
        $newbet = str_replace('dau dit', 'daudit', $newbet);
        $newbet = str_replace('dau duoi', 'dauduoi', $newbet);
        $newbet = $this->convert_bo_so($newbet);
        $newbet = str_replace(['.', ',', '+', 'va'], ' ', $newbet);
        $newbet = str_replace('x', ' x', $newbet);
        $newbet = str_replace('×', ' x', $newbet);
        $newbet = str_replace('₫', ' d', $newbet);
        $newbet = str_replace('djem', ' x', $newbet);
        $newbet = str_replace('=', ' x ', $newbet);
        $newbet = str_replace('nhan', ' x', $newbet);
        $newbet = str_replace(';', ' ', $newbet);
        $newbet = str_replace(':', ' ', $newbet);
        $newbet = str_replace('.', ' ', $newbet);
        $newbet = str_replace(' k', 'k', $newbet);

        $arrprice = array('n', 'k', 'd', 'ng', 'tr', 'diem', 'trieu', 'ngh', 'ngin', 'nghin');

        $newbet = str_replace(' n', 'n ', $newbet);
        $newbet = str_replace(' k', 'k', $newbet);
        $newbet = str_replace(' d ', 'd ', $newbet);
        $newbet = str_replace(' ng', 'ng', $newbet);
        // echo $newbet;
        // check lai
        if (strpos($newbet, 'bo trung') !== false)
            $newbet = str_replace(' tr', 'tr', $newbet);
        else
            $newbet = str_replace(' tr', 'tr', $newbet);

        $newbet = str_replace(' trieu', 'trieu', $newbet);
        $newbet = str_replace(' nghin', 'nghin', $newbet);
        $newbet = str_replace(' diem', 'diem', $newbet);
        $newbet = str_replace(' ngh', 'ngh', $newbet);
        $newbet = str_replace(' ngin', 'ngin', $newbet);
        $newbet = str_replace(' nghin', 'nghin', $newbet);

        $newbet = str_replace('de 2cua', '2cua ', $newbet);
        $newbet = str_replace('de 3 cang', '3cang ', $newbet);

        $newbet = str_replace('de', 'de ', $newbet);
        $newbet = str_replace('lo', 'lo ', $newbet);
        $newbet = str_replace('lo truot', 'lotruot', $newbet);
        $newbet = str_replace('lo  truot', 'lotruot', $newbet);
        $newbet = str_replace('truot 10', 'truot10 ', $newbet);
        $newbet = str_replace(['truot 1', 'truot  1'], 'truot1 ', $newbet);
        $newbet = str_replace('truot 4', 'truot4 ', $newbet);
        $newbet = str_replace('truot 8', 'truot8 ', $newbet);
        $newbet = str_replace(['dau than tai', 'dau thantai', 'dauthan tai'], 'dauthantai ', $newbet);
        $newbet = str_replace(['duoi than tai', 'duoi thantai', 'duoithan tai'], 'duoithantai ', $newbet);
        $newbet = str_replace(['dau dac biet', 'dau dacbiet', 'daudac biet'], 'daudacbiet ', $newbet);
        $newbet = str_replace(['dau nhat'], 'daunhat ', $newbet);
        $newbet = str_replace('lo live', 'lolive ', $newbet);
        $newbet = str_replace('lo  live', 'lolive ', $newbet);
        $newbet = str_replace('de truot', 'detruot ', $newbet);
        $newbet = str_replace('de  truot', 'detruot ', $newbet);
        $newbet = str_replace('3 cang', '3cang', $newbet);
        $newbet = str_replace('ba cang', 'bacang', $newbet);
        $newbet = str_replace('b c', 'bacang', $newbet);

        $newbet = str_replace(['giai2.1','giai 2.1','giai 2 1'], 'giai21', $newbet);
        $newbet = str_replace(['giai2.2','giai 2.2','giai 2 2'], 'giai22', $newbet);
        $newbet = str_replace(['giai3.1','giai 3.1','giai 3 1'], 'giai31', $newbet);
        $newbet = str_replace(['giai3.2','giai 3.2','giai 3 2'], 'giai32', $newbet);
        $newbet = str_replace(['giai3.3','giai 3.3','giai 3 3'], 'giai33', $newbet);
        $newbet = str_replace(['giai3.4','giai 3.4','giai 3 4'], 'giai34', $newbet);
        $newbet = str_replace(['giai3.5','giai 3.5','giai 3 5'], 'giai35', $newbet);
        $newbet = str_replace(['giai3.6','giai 3.6','giai 3 6'], 'giai36', $newbet);
        $newbet = str_replace(['giai4.1','giai 4.1','giai 4 1'], 'giai41', $newbet);
        $newbet = str_replace(['giai4.2','giai 4.2','giai 4 2'], 'giai42', $newbet);
        $newbet = str_replace(['giai4.3','giai 4.3','giai 4 3'], 'giai43', $newbet);
        $newbet = str_replace(['giai4.4','giai 4.4','giai 4 4'], 'giai44', $newbet);
        $newbet = str_replace(['giai5.1','giai 5.1','giai 5 1'], 'giai51', $newbet);
        $newbet = str_replace(['giai5.2','giai 5.2','giai 5 2'], 'giai52', $newbet);
        $newbet = str_replace(['giai5.3','giai 5.3','giai 5 3'], 'giai53', $newbet);
        $newbet = str_replace(['giai5.4','giai 5.4','giai 5 4'], 'giai54', $newbet);
        $newbet = str_replace(['giai5.5','giai 5.5','giai 5 5'], 'giai55', $newbet);
        $newbet = str_replace(['giai5.6','giai 5.6','giai 5 6'], 'giai56', $newbet);
        $newbet = str_replace(['giai6.1','giai 6.1','giai 6 1'], 'giai61', $newbet);
        $newbet = str_replace(['giai6.2','giai 6.2','giai 6 2'], 'giai62', $newbet);
        $newbet = str_replace(['giai6.3','giai 6.3','giai 6 3'], 'giai63', $newbet);
        $newbet = str_replace(['giai7.1','giai 7.1','giai 7 1'], 'giai71', $newbet);
        $newbet = str_replace(['giai7.2','giai 7.2','giai 7 2'], 'giai72', $newbet);
        $newbet = str_replace(['giai7.3','giai 7.3','giai 7 3'], 'giai73', $newbet);
        $newbet = str_replace(['giai7.4','giai 7.4','giai 7 4'], 'giai74', $newbet);


        // $newbet = str_replace(['xq', 'xquay', 'x quay', 'uay', 'xien quay'],'xienq2 xienq3 xienq4 ',$newbet);
        // $newbet = str_replace(['xq','xquay','x quay','xienquay','xien quay','xienq ','xien q','xjienq','xjien quay','xjien q','xjenquay'],'xienq2 xienq3 xienq4 ',$newbet);
        $newbet = str_replace(['xq', 'xquay', 'x quay', 'xienquay', 'xien quay', 'xienq ', 'xien q', 'xjienq', 'xjien quay', 'xjien q', 'xjenquay'], 'xienq ', $newbet);
        $newbet = str_replace(['x 2 ', 'xien 2 ', 'xi 2', 'xjien 2'], 'xien2 ', $newbet);
        $newbet = str_replace(['x 3 ', 'xien 3 ', 'xi 3', 'xjien 3'], 'xien3 ', $newbet);
        $newbet = str_replace(['x 4 ', 'xien 4 ', 'xi 4', 'xjien 4'], 'xien4 ', $newbet);

        $replaced = preg_replace('/\s\s+/', ' ', $newbet);
        if($replaced[strlen($replaced)-1] === "d" &&  $replaced[strlen($replaced)-2] === " ") {
            $replaced[strlen($replaced)-1] = " ";
            $replaced[strlen($replaced)-2] = "d";
        }
        
        $replaced = trim($replaced);
        $arrRaw = explode(" ", $replaced);
        $index0 = 0;
        $indexX = 0;
        $indexI = 0;
        // var_dump($arrRaw);

        if( ($arrRaw[$indexI] == "T" || $arrRaw[$indexI] == "t" || $arrRaw[$indexI] == "Tin" || $arrRaw[$indexI] == "tin") 
        ){
            array_shift($arrRaw);
        }

        if( (is_numeric($arrRaw[$indexI])) 
        ){
            array_shift($arrRaw);
        }
        
        while($indexI < count($arrRaw))
        {
            if ($arrRaw[$indexI] === '0000'){
                $index0 = $indexI;
            } 
            if ($arrRaw[$indexI] == 'x' && $index0 > 0){
                $indexX = $indexI;
                $arrRaw[$index0] = $arrRaw[$indexI];
                // $arrRaw[$index0] = $item1 . " " . $arrRaw[$indexX+1];
                array_splice( $arrRaw, $index0+1, 0, $arrRaw[$indexX+1] );
                $index0 = 0;
                // echo "ss";
            }

            $indexI++;
        }
        // var_dump($arrRaw);
        try {
            $strNotMatch = '';
            $bangso = BoSoHelpers::GetAllBangSo(0);
            $bangso1 = BoSoHelpers::GetAllBangSo(1);
            $arrprice = array('n', 'k', 'd', 'ng', 'tr', 'diem', 'trieu', 'ngh', 'ngin', 'nghin', 'x');
            foreach ($arrRaw as $item) {

                $strMatch = false;
                if ($this->_s_has_numbers($item) == true) {
                    $strMatch = true;
                    // echo $item;
                    continue;
                }
                if ($this->_s_has_letters($item) == true) {
                    foreach ($bangso as $value) {
                        $kyhieu = explode(',', $value->kyhieu);
                        // $boso = $value->boso;
                        if (in_array($item, $kyhieu)) {
                            $strMatch = true;
                            break;
                        }
                    }
                }

                if ($this->_s_has_letters($item) == true) {
                    foreach ($bangso1 as $value) {
                        // $kyhieu = explode(',',$value->kyhieu);
                        $boso = explode(',', $value->boso);
                        if (in_array($item, $boso)) {
                            $strMatch = true;
                            break;
                        }
                    }
                }

                if ($this->_s_has_letters($item) == true) {
                    if (in_array($item, $arrprice)) {
                        $strMatch = true;
                        // break;
                    }
                }

                if ($strMatch != true) {
                    $strNotMatch .= (' ' . $item);
                }
            }
        } catch (\Exception $ex) {
        }
        // echo $strNotMatch;

        // echo nl2br('<br> ');
        $arrRawBetDe = array();
        $dump = array();
        $i = 0;
        while ($i < count($arrRaw)) {
            $item = $arrRaw[$i];
            
            // if ($i>13) echo $item;
            if (count($dump) > 0 && $this->_s_has_letters($item) == true) {
                array_push($dump, $item);
                if ($item == 'nhan' || $item == 'x') {
                    array_push($dump, $arrRaw[$i + 1]);
                    array_push($arrRawBetDe, $dump);
                    // print_r ($arrRawBetDe);
                    $dump = array();
                    $i++;
                }
                // else
                // if ($item=='d'){
                //     // array_push($dump,$arrRaw[$i+1]);
                //     array_push($arrRawBetDe,$dump);
                //     // print_r ($arrRawBetDe);
                //     $dump=array();
                //     $i++;
                // }
                else {
                    if (
                        $item == 'lo' || $item == 'de' || $item == 'dacbiet' || $item == 'dac' || $item == 'biet'
                        || $item == 'nhat' || $item == 'xien'
                        || $item == 'xien2' || $item == 'xien3' || $item == 'xien4'
                        || $item == 'xienq2' || $item == 'xienq3' || $item == 'xienq4'
                        || $item == 'xienq'
                        || $item == 'x2' || $item == 'x3' || $item == 'x4'
                        || $item == 'cua' || $item == '2cua'
                        || $item == '2c'
                        || $item == 'bo' || $item == 'bor'
                        || $item == 'trung' || $item == 'botrung'
                        // || $item == 'than tai' 
                    ) {
                        //no action
                    } else {
                        array_push($arrRawBetDe, $dump);
                        $dump = array();
                    }
                }
            } else
             if (count($dump) == 0 && $this->_s_has_letters($item) == true) {
                array_push($dump, $item);
            } else {
                if (count($dump) == 0 && $this->_s_has_letters($item) == false) {
                    if (count($arrRawBetDe) > 0)
                        foreach ($arrRawBetDe[count($arrRawBetDe) - 1] as $cuoc) {
                            if ($this->_s_has_letters($cuoc) == false) {
                                break;
                            }
                            array_push($dump, $cuoc);
                        }
                    // array_push($dump,$arrRawBetDe[count($arrRawBetDe)-1][0]);
                }
                array_push($dump, $item);
            }
            $i++;
        }
        
        // var_dump($arrRawBetDe);
        foreach($arrRawBetDe as &$item1){
            if ($item1[count($item1)-2] != "x"){
                array_splice( $item1, count($item1)-1, 0, 'x' );
            }
        }
        foreach($arrRawBetDe as &$item1){
            if ($item1[0] == 'xien') {
                $countX = count($item1) - 3;
                if($countX < 5){
                    $item = 'xien'.$countX;
                    $item1[0] = $item;
                }
            }
            if ($item1[count($item1)-2] != "x"){
                array_splice( $item1, count($item1)-1, 0, 'x' );
            }
        }
        // var_dump($arrRawBetDe);
        return [$arrRawBetDe, $strNotMatch];
    }

    public function quickplaylogic($user, $quicktext = 'de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50', $_iscuoc, $ipaddr, $checkbox_lowp = false, $historyID = 0)
    {

        // $newbet = ' đề 25,52,36,63    x50k. nhat45.56.74 1tr, lo 65-78-98 x 20d; xien4 33.44,55-66= 100ng ';
        // $newbet ='de lo 23.34,55-66x30. Lo 33 44 55 x50n xq 12.45.34nhan1tr ; 2cua 56.676-878=40k   dacbiet 15                 17 18 x 30. 44 55 x 50';
        //init
        // Log::info("quickplaylogic");
        $ids = "";
        $arrRawBetDeRaw = $this->initquickplay($quicktext);
        $arrRawBetDe = $arrRawBetDeRaw[0];
        // print_r($arrRawBetDe);
        // echo nl2br('<br> ');
        $request = array();
        $multi_request = array();
        $is_actived = false;
        // Log::info("563");
        foreach ($arrRawBetDe as $blockcuoc) {
            $loaicuoc = 0;
            $macuoc = array();
            $macuocbo = array();
            $gia = 0;
            if (
                strpos($blockcuoc[0], 'xien') === false && strlen($blockcuoc[0]) > 6 && strpos($blockcuoc[0], '3cangnhat') === false && strpos($blockcuoc[0], 'truot') === false && strpos($blockcuoc[0], 'than tai') === false
                && strpos($blockcuoc[0], 'thantai') === false
            ) {
                // $dumparr = preg_split('#(?<=\d)(?=[a-z])#i', $blockcuoc[0]);
                preg_match('/[^\d]+/', $blockcuoc[0], $textMatch);
                // $blockcuoc[0]=$textMatch[0];
                // print_r ($textMatch);
                array_splice($blockcuoc, 1, 0, str_replace($textMatch[0], '', $blockcuoc[0]));
                $blockcuoc[0] = $textMatch[0];
            }
            // print_r($blockcuoc);
            $result = '';
            // echo $result;
            // echo '---';
            // if( ($blockcuoc[0] == "T" || $blockcuoc[0] == "t" || $blockcuoc[0] == "Tin" || $blockcuoc[0] == "tin")){
            //     $blockcuoc = array_splice($blockcuoc,1);
            //     print_r($blockcuoc);
            // }
            for ($i = 1; $i <= count($blockcuoc) - 2; $i++) {
                // echo $result . "\n";
                if (
                    strpos($blockcuoc[$i], 'bo') !== false
                    || strpos($blockcuoc[$i], 'bor') !== false
                    || strpos($blockcuoc[$i], 'botrung') !== false
                ) {
                    break;
                }
                if (
                    strpos($blockcuoc[$i], 'xien') !== false
                    || strpos($blockcuoc[$i], 'xienq') !== false
                    || strpos($blockcuoc[$i], 'x2') !== false
                    || strpos($blockcuoc[$i], 'x3') !== false
                    || strpos($blockcuoc[$i], 'x4') !== false
                ) {
                    $result .= $blockcuoc[$i] . ' ';
                    // echo $blockcuoc[$i] . " ";
                    // continue;
                } else {
                    // if (is_numeric($blockcuoc[$i]) && $result == "") continue;
                        // echo $blockcuoc[$i] . " ";
                    $result .= str_replace(['x', '=', 'nhan'], '', $blockcuoc[$i]) . ' ';
                    // $result.= $blockcuoc[$i].' ';
                    preg_match_all('!\d+!', $blockcuoc[$i], $matches);
                    // $macuoc = array_merge($macuoc,$matches);
                    foreach ($matches as $item)
                        $macuoc = array_merge($macuoc, $item);
                    // print_r($matches);
                }
            }
            $botrung = false;
            // echo $i;
            // print_r($blockcuoc);
            for (; $i <= count($blockcuoc) - 2; $i++) {
                // if (strpos( $blockcuoc[$i], 'bo' ) !==false
                // || strpos( $blockcuoc[$i], 'bor' ) !==false
                // ){
                //     break;
                // }
                if (strpos($blockcuoc[$i], 'trung') !== false || strpos($blockcuoc[$i], 'botrung') !== false) {
                    $botrung = true;
                }
                if (
                    strpos($blockcuoc[$i], 'xien') !== false
                    || strpos($blockcuoc[$i], 'xienq') !== false
                    || strpos($blockcuoc[$i], 'x2') !== false
                    || strpos($blockcuoc[$i], 'x3') !== false
                    || strpos($blockcuoc[$i], 'x4') !== false
                ) {
                    $result .= $blockcuoc[$i] . ' ';
                    // continue;
                } else {
                    $result .= str_replace(['x', '=', 'nhan'], '', $blockcuoc[$i]) . ' ';
                    // $result.= $blockcuoc[$i].' ';
                    preg_match_all('!\d+!', $blockcuoc[$i], $matches);
                    // $macuoc = array_merge($macuoc,$matches);
                    foreach ($matches as $item)
                        $macuocbo = array_merge($macuocbo, $item);
                    // print_r($matches);
                }
            }
            if ($blockcuoc[0] == 'xien') {
                $blockcuoc[0] = 'xien' . (count($blockcuoc) - 2);
            }
            if ($botrung == true) {
                $macuoc = array_unique($macuoc);
                // print_r($macuoc);
            }
            // echo $result . "\n";
            $result = $blockcuoc[0] . ' ' . $result;
            // $result.=' '.str_replace(['x','='],'',$blockcuoc[count($blockcuoc)-1]);
            $result .= 'giá ' . str_replace(['x', '=', 'nhan'], '', $blockcuoc[count($blockcuoc) - 1]);
            // echo $blockcuoc[0];
            // echo $result . "\n";
            // echo $blockcuoc[0];
            // echo implode(',',$macuoc);
            // echo '----';
            // echo implode(',',$macuocbo);
            // echo '; ';

            //check bo
            // echo nl2br($result.'<br> ');
            // print_r($this->str_to_game_code($result));
            // echo nl2br('<br>');
            // print_r($macuoc);
            // echo nl2br('<br>');
            // print_r($this->str_to_price($blockcuoc[count($blockcuoc)-1]));
            $priceRaw = $this->str_to_price($blockcuoc[count($blockcuoc) - 1]);
            // print_r($priceRaw);
            $type_price = $priceRaw[0];
            $value_price = $priceRaw[1];
            // echo nl2br('<br>');
            // $user = Auth::user();

            $now = date('Y-m-d'); // 
            $hour = date('H');
            $min = date('i');
            $sec = date('s');
            $yesterday = date('Y-m-d', time() - 86400);
            // if ($location->slug ==1){
            $yesterday = date('Y-m-d', time() - 86400);
            $datepickerXS = date('d-m-Y', time() - 86400);
            $xoso = new Xoso();
            if (intval(date('H')) < 18 || (intval(date('H')) == 18 && intval(date('i')) < 14)) {
                $rs =
                    // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
                    // return 
                    $xoso->getKetQua(1, $yesterday);
                // });
                // $rs = xoso::getKetQua(1,$yesterday);
            } else {
                $rs =
                    // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
                    // return 
                    $xoso->getKetQua(1, date('Y-m-d'));
                // });
                $datepickerXS = date('d-m-Y');
            }
            // echo $result;
            // Log::info("693");
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();
            if (count($kqxs) < 1)
                $kqxsdr = 0;
            else
                $kqxsdr = $kqxs->first()->Giai_8;

            foreach ($this->str_to_game_code($result) as $gameOrignal) {
                //vao cuoc
                try {
                    $game = $gameOrignal[0];
                    // echo $game.'-';
                    //vao cuoc
                    // Request $request
                    // echo
                    $h_open = 0;
                    $h_close = 0;
                    $is_actived = true;
                    $h_game = GameHelpers::GetGameByGameCode($game);
                    // print_r ($h_game);
                    $h_close = XoSoRecordHelpers::TimeoutBet($kqxs,$game,$h_game);
                    // echo strtotime('now') .'-'.$h_close;
                    // disable bet time
                    if (strtotime('now') > $h_close) {
                        $is_actived = false;
                    }
                    if ($h_game['open'] >= date('H:i'))
                        $is_actived = false;
                    if ($game == 0) continue;
                    $customer_type = GameHelpers::GetByCusTypeGameCodeUser($game, $user->customer_type, $user);

                    $request = array();
                    $request['game_code'] = $game;
                    $request['is_actived'] = $is_actived;
                    $request['game_name'] = $customer_type->game_name;
                    $request['odds'] = $customer_type->odds;
                    $request['ipaddr'] = $ipaddr;
                    $count = 0;

                    if ($game != 9 && $game != 10 && $game != 11 && $game != 29
                        && $game != 19 && $game != 20 && $game != 21)
                        foreach ($macuoc as $name) {
                            if (strlen($name) > 2 && $game != 17 && $game != 56) {
                                for ($h = 0; $h < strlen($name); $h++)
                                    for ($k = $h + 1; $k < strlen($name); $k++) {
                                        $name1 = $name[$h] . $name[$k];
                                        if (in_array($name1, $macuocbo))
                                            continue;
                                        //get exchange odds by user + game_code + game_number
                                        $data = GameHelpers::GetGame_NumberUser($game, $name1, $user);

                                        $exchange_rates = "";
                                        $a = "";
                                        $x = "";
                                        if (count($data) > 0) {
                                            $exchange_rates = $data[0]['exchange_rates'];
                                            if ($exchange_rates < $customer_type['exchange_rates'])
                                                $exchange_rates = $customer_type['exchange_rates'];
                                            // $a = $data[0]['a'];
                                            // $x = $data[0]['x'];
                                            // $total = $data[0]['total'];
                                        } else {
                                            $exchange_rates =  $customer_type['exchange_rates'];
                                            // $a = $game['a'];
                                            // $x = $game['x'];
                                            // $total = 0;
                                        }

                                        $datagoc = 21800;
                                        if ($game == 18)
                                            if ($kqxsdr == 0)
                                                $exchange_rates = 803 * (27 - 1) + ($exchange_rates - $datagoc);
                                            else
                                            if ($kqxsdr >= 25)
                                                $exchange_rates = 0;
                                            else
                                                $exchange_rates = 803 * (27 - $kqxsdr - 1) + ($exchange_rates - $datagoc);

                                        $request['choices'][$count] = array();
                                        $request['choices'][$count]['exchange'] = $exchange_rates;
                                        $request['choices'][$count]['name'] = $name1;
                                        $request['choices'][$count]['point'] = (float)($type_price) * (float)($value_price[0]);
                                        if ($type_price == ' ')
                                            $request['choices'][$count]['total'] = $exchange_rates * (float)($value_price[0]);
                                        else
                                            $request['choices'][$count]['total'] = $exchange_rates * (float)($type_price) * (float)($value_price[0]);

                                        $count++;
                                        break;
                                    }
                            } else {
                                //get exchange odds by user + game_code + game_number
                                if (strlen($name) == 1)
                                    $name = '0' . $name;
                                if (in_array($name, $macuocbo))
                                    continue;
                                $data = GameHelpers::GetGame_NumberUser($game, $name, $user);

                                $exchange_rates = "";
                                $a = "";
                                $x = "";
                                if (count($data) > 0) {
                                    $exchange_rates = $data[0]['exchange_rates'];
                                    if ($exchange_rates < $customer_type['exchange_rates'])
                                        $exchange_rates = $customer_type['exchange_rates'];
                                    // $a = $data[0]['a'];
                                    // $x = $data[0]['x'];
                                    // $total = $data[0]['total'];
                                } else {
                                    $exchange_rates =  $customer_type['exchange_rates'];
                                    // $a = $game['a'];
                                    // $x = $game['x'];
                                    // $total = 0;
                                }

                                $datagoc = 21800;
                                if ($game == 18)
                                    if ($kqxsdr == 0)
                                        $exchange_rates = 803 * (27 - 1) + ($exchange_rates - $datagoc);
                                    else
                                    if ($kqxsdr >= 25)
                                        $exchange_rates = 0;
                                    else
                                        $exchange_rates = 803 * (27 - $kqxsdr - 1) + ($exchange_rates - $datagoc);
                                $request['choices'][$count] = array();
                                $request['choices'][$count]['exchange'] = $exchange_rates;
                                $request['choices'][$count]['name'] = $name;
                                $request['choices'][$count]['point'] = (float)($type_price) * (float)($value_price[0]);
                                if ($type_price == ' ')
                                    $request['choices'][$count]['total'] = $exchange_rates * (float)($value_price[0]);
                                else
                                    $request['choices'][$count]['total'] = $exchange_rates * (float)($type_price) * (float)($value_price[0]);

                                $count++;
                            }
                        }
                    else {
                        $macuoc = array_unique($macuoc);
                        $str_macuoc = '';
                        $exchange_rates = 0;
                        foreach ($macuoc as $name) {
                            if (strlen($name) > 2 && $game != 17 && $game != 56) {
                                for ($h = 0; $h < strlen($name); $h++)
                                    for ($k = $h + 1; $k < strlen($name); $k++) {
                                        $name1 = $name[$h] . $name[$k];
                                        if (in_array($name1, $macuocbo))
                                            continue;
                                        $str_macuoc .= $name1 . ',';
                                        //get exchange odds by user + game_code + game_number

                                        $data = GameHelpers::GetGame_NumberUser($game, $name1, $user);


                                        $a = "";
                                        $x = "";
                                        if (count($data) > 0) {

                                            if ($data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                                                $exchange_rates += $customer_type['exchange_rates'];
                                            else
                                                $exchange_rates += $data[0]['exchange_rates'];
                                            // $a = $data[0]['a'];
                                            // $x = $data[0]['x'];
                                            // $total = $data[0]['total'];
                                        } else {
                                            $exchange_rates +=  $customer_type['exchange_rates'];
                                            // $a = $game['a'];
                                            // $x = $game['x'];
                                            // $total = 0;
                                        }

                                        break;
                                    }
                            } else {
                                if (strlen($name) == 1)
                                    $name = '0' . $name;
                                if (in_array($name, $macuocbo))
                                    continue;
                                $str_macuoc .= $name . ',';

                                $data = GameHelpers::GetGame_NumberUser($game, $name, $user);


                                $a = "";
                                $x = "";
                                if (count($data) > 0) {

                                    if ($data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                                        $exchange_rates += $customer_type['exchange_rates'];
                                    else
                                        $exchange_rates += $data[0]['exchange_rates'];
                                    // $a = $data[0]['a'];
                                    // $x = $data[0]['x'];
                                    // $total = $data[0]['total'];
                                } else {
                                    $exchange_rates +=  $customer_type['exchange_rates'];
                                    // $a = $game['a'];
                                    // $x = $game['x'];
                                    // $total = 0;
                                }
                            }
                        }
                        if (!isset($str_macuoc) || strlen($str_macuoc) < 1) continue;

                        if ($str_macuoc[strlen($str_macuoc) - 1] == ',')
                            $str_macuoc = substr($str_macuoc, 0, -1);
                        $arr_macuoc = explode(',', $str_macuoc);
                        if (count($arr_macuoc) < 2) continue;
                        if (count($arr_macuoc) < 3 && $game == 10) continue;
                        if (count($arr_macuoc) < 4 && $game == 11) continue;
                        //chia xien theo cap
                        // echo $gameOrignal[1];
                        if (($game == 9 || $game == 10 || $game == 11) && strpos($gameOrignal[1], 'xienq') === false) {
                            //chia xien theo cap
                            $countbetnumberorginal = count($arr_macuoc);
                            $currentpoint = 0;

                            for ($slxien = 0; $slxien < $countbetnumberorginal / ($game - 7); $slxien++) {
                                // echo $currentpoint;
                                $arr_macuoc_split = array();
                                for ($k = 0; $k < $game - 7; $k++) {
                                    array_push($arr_macuoc_split, $arr_macuoc[$currentpoint]);
                                    $currentpoint++;
                                    if ($currentpoint >= count($arr_macuoc)) break;
                                }

                                if (count($arr_macuoc_split) != ($game - 7)) continue;

                                $countbetnumber = count($arr_macuoc_split);
                                $str_macuoc_split = implode(',', $arr_macuoc_split);
                                $Ank = 1;
                                if ($game == 9)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
                                elseif ($game == 10)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(3) / XoSoRecordHelpers::fact($countbetnumber - 3);
                                elseif ($game == 11)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(4) / XoSoRecordHelpers::fact($countbetnumber - 4);
                                elseif ($game == 29)
                                    $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);

                                // $point = $record->total_bet_money/$record->exchange_rates/$Ank;
                                // $record->total_win_money = $point*$record->odds*$count;
                                // echo (int)($exchange_rates/$countbetnumber/($game-7));
                                $request['choices'][$count] = array();
                                $request['choices'][$count]['exchange'] = (int)($exchange_rates / $countbetnumberorginal);
                                $request['choices'][$count]['name'] = $str_macuoc_split;
                                $request['choices'][$count]['point'] = $Ank * (float)($type_price) * (float)($value_price[0]);
                                if ($type_price == ' ')
                                    $request['choices'][$count]['total'] = $Ank * (int)($exchange_rates / $countbetnumberorginal) * (float)($value_price[0]);
                                else
                                    $request['choices'][$count]['total'] = $Ank * (int)($exchange_rates / $countbetnumberorginal) * (float)($type_price) * (float)($value_price[0]);

                                $count++;
                            }
                        } else {
                            $countbetnumber = count($arr_macuoc);
                            $Ank = 1;
                            if ($game == 9)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
                            elseif ($game == 10)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(3) / XoSoRecordHelpers::fact($countbetnumber - 3);
                            elseif ($game == 11 || $game == 19)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(4) / XoSoRecordHelpers::fact($countbetnumber - 4);
                            elseif ($game == 29)
                                $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);

                            // $point = $record->total_bet_money/$record->exchange_rates/$Ank;
                            // $record->total_win_money = $point*$record->odds*$count;

                            $datagoc = 21800;
                            if ($game == 18)
                                if ($kqxsdr == 0)
                                    $exchange_rates = 803 * (27 - 1) + ($exchange_rates - $datagoc);
                                else
                                if ($kqxsdr >= 25)
                                    $exchange_rates = 0;
                                else
                                    $exchange_rates = 803 * (27 - $kqxsdr - 1) + ($exchange_rates - $datagoc);
                            if(!isset($value_price[0])) continue;
                            $request['choices'][$count] = array();
                            $request['choices'][$count]['exchange'] = (int)($exchange_rates / $countbetnumber);
                            $request['choices'][$count]['name'] = $str_macuoc;
                            $request['choices'][$count]['point'] = $Ank * (float)($type_price) * (float)($value_price[0]);
                            if ($type_price == ' ')
                                $request['choices'][$count]['total'] = $Ank * (int)($exchange_rates / $countbetnumber) * (float)($value_price[0]);
                            else
                                $request['choices'][$count]['total'] = $Ank * (int)($exchange_rates / $countbetnumber) * (float)($type_price) * (float)($value_price[0]);

                            $count++;
                        }
                    }
                    // print_r ($request);
                    $status = '';
                    if(!isset($request['choices'])) continue;
                    // $exchange_rates_check = $request['choices'][0]['exchange'];

                    // $customer_type_by_user = GameHelpers::GetOneGameByCusType($user->customer_type,$request['game_code'],$user->id);
                    $customer_type_by_user = GameHelpers::GetOneGameParentByCusType($user->customer_type, $user->id, $request['game_code']);
                    // var_dump($user);
                    // echo $customer_type_by_user;
                    // $customer_type_by_user = GameHelpers::GetAllGameByCusType($user->customer_type,$user->id,0);
                    // echo count($customer_type_by_user);
                    // foreach($customer_type_by_user as $item){
                    //     // var_dump($item);
                    //     echo $item->exchange_rates .'.'.$item->game_code .'-';
                    // }
                    // echo $customer_type_by_user.'-';
                    // echo $customer_type_by_user->exchange_rates . '.'.$request['game_code'].'.'.$exchange_rates_check .'-';
                    // if ($request['game_code'] == 14)
                    //     $exchange_rates_check = 712;
                    if ($is_actived == false) {
                        $status = 'Hết hạn cược';
                    }
                    // if ($game != 18 && $customer_type_by_user->exchange_rates < $exchange_rates_check && $checkbox_lowp) {
                    //     $status = 'Giới hạn giá thấp';
                    // } else
                    {
                        if ($_iscuoc == '1' && $is_actived == true) {
                            if ($game == 7 || $game == 14 || $game == 12) {
                                $locknumber = GameHelpers::LockNumberUser($game,$user);
    
                                $requestTempLow = array();
                                $requestTempLow['game_code'] = $game;
                                $requestTempLow['is_actived'] = $is_actived;
                                $requestTempLow['game_name'] = $customer_type->game_name;
                                $requestTempLow['odds'] = $customer_type->odds;
                                $requestTempLow['ipaddr'] = $ipaddr;
                                $requestTempLow['choices'] = [];

                                $requestTemp = array();
                                $requestTemp['game_code'] = $game;
                                $requestTemp['is_actived'] = $is_actived;
                                $requestTemp['game_name'] = $customer_type->game_name;
                                $requestTemp['odds'] = $customer_type->odds;
                                $requestTemp['ipaddr'] = $ipaddr;
                                $requestTemp['choices'] = [];
    
                                $requestTemp2 = array();
                                $requestTemp2['game_code'] = $game;
                                $requestTemp2['is_actived'] = $is_actived;
                                $requestTemp2['game_name'] = $customer_type->game_name;
                                $requestTemp2['odds'] = $customer_type->odds;
                                $requestTemp2['ipaddr'] = $ipaddr;
                                $requestTemp2['choices'] = [];
    
                                $countTemp = 0;
                                $countTemp2 = 0;
                                for ($i = 0; $i < count($request['choices']); $i++){
                                    $exchange_rates_check = $request['choices'][$i]['exchange'];
                                    if ($game != 18 && $customer_type_by_user->exchange_rates < $exchange_rates_check && $checkbox_lowp) {
                                            $status = 'Giới hạn giá thấp';
                                            $requestTempLow['choices'][] = $request['choices'][$i];
                                    }else{
                                        if (str_contains($locknumber, $request['choices'][$i]['name'])) {
                                            $requestTemp['choices'][] = $request['choices'][$i];
                                        } else {
                                            $requestTemp2['choices'][] = $request['choices'][$i];
                                        }
                                    }
                                }
                                    
                                $requestTempLow['status'] = "Giới hạn giá thấp";
                                if (count($requestTempLow['choices']) > 0)
                                    array_push($multi_request, $requestTempLow);

                                $insertBet = XoSoRecordHelpers::InsertXosoRecord((object)$requestTemp, $user, true, true);
                                $ids .= ",".$insertBet["ids"];
                                $status = $insertBet['status'];
                                $requestTemp['status'] = $status;
                                // print_r($requestTemp['choices']);
                                if (count($requestTemp['choices']) > 0)
                                    array_push($multi_request, $requestTemp);
    
                                $insertBet = XoSoRecordHelpers::InsertXosoRecord((object)$requestTemp2, $user, true, true);
                                $ids .= ",".$insertBet["ids"];
                                $status = $insertBet['status'];
                                $requestTemp2['status'] = $status;
                                // print_r($requestTemp['choices']);
                                if (count($requestTemp2['choices']) > 0)
                                    array_push($multi_request, $requestTemp2);
                                continue;
                            } else {

                                $requestTempLow = array();
                                $requestTempLow['game_code'] = $game;
                                $requestTempLow['is_actived'] = $is_actived;
                                $requestTempLow['game_name'] = $customer_type->game_name;
                                $requestTempLow['odds'] = $customer_type->odds;
                                $requestTempLow['ipaddr'] = $ipaddr;
                                $requestTempLow['choices'] = [];

                                $requestTemp = array();
                                $requestTemp['game_code'] = $game;
                                $requestTemp['is_actived'] = $is_actived;
                                $requestTemp['game_name'] = $customer_type->game_name;
                                $requestTemp['odds'] = $customer_type->odds;
                                $requestTemp['ipaddr'] = $ipaddr;
                                $requestTemp['choices'] = [];

                                for ($i = 0; $i < count($request['choices']); $i++){
                                    $exchange_rates_check = $request['choices'][$i]['exchange'];
                                    if ($game != 18 && $customer_type_by_user->exchange_rates < $exchange_rates_check && $checkbox_lowp) {
                                            $status = 'Giới hạn giá thấp';
                                            $requestTempLow['choices'][] = $request['choices'][$i];
                                    }else{
                                        $requestTemp['choices'][] = $request['choices'][$i];
                                    }
                                }

                                $requestTempLow['status'] = "Giới hạn giá thấp";
                                if (count($requestTempLow['choices']) > 0)
                                    array_push($multi_request, $requestTempLow);

                                $insertBet = XoSoRecordHelpers::InsertXosoRecord((object)$requestTemp, $user, true, true);
                                $ids .= ",".$insertBet["ids"];
                                $status = $insertBet['status'];
                                $requestTemp['status'] = $status;
                                // print_r($requestTemp['choices']);
                                if (count($requestTemp['choices']) > 0)
                                    array_push($multi_request, $requestTemp);
                                // // HistoryHelpers::InsertHistoryQuickBet($quicktext,$request,$user);
                                // $insertBet = XoSoRecordHelpers::InsertXosoRecord((object)$request, $user, true, true);
                                // $ids .= ",".$insertBet["ids"];
                                // $status = $insertBet['status'];

                                continue;
                            }
    
                            // try{
                            //     $record = new QuickPlayRecord;
                            //     $record->content = $quicktext;
                            //     $record->date = date('Y-m-d');
                            //     $record->user_id = $user->id;
                            //     $record->save();
                            // }catch(\Exception $ex){
                            //     echo $ex->getMessage();
                            // }
    
    
                        } else if ($is_actived == true) {
    
                            if ($game == 7 || $game == 14 || $game == 12) {
                                $locknumber = GameHelpers::LockNumberUser($game,$user);
    
                                $requestTempLow = array();
                                $requestTempLow['game_code'] = $game;
                                $requestTempLow['is_actived'] = $is_actived;
                                $requestTempLow['game_name'] = $customer_type->game_name;
                                $requestTempLow['odds'] = $customer_type->odds;
                                $requestTempLow['ipaddr'] = $ipaddr;
                                $requestTempLow['choices'] = [];

                                $requestTemp = array();
                                $requestTemp['game_code'] = $game;
                                $requestTemp['is_actived'] = $is_actived;
                                $requestTemp['game_name'] = $customer_type->game_name;
                                $requestTemp['odds'] = $customer_type->odds;
                                $requestTemp['ipaddr'] = $ipaddr;
                                $requestTemp['choices'] = [];
    
                                $requestTemp2 = array();
                                $requestTemp2['game_code'] = $game;
                                $requestTemp2['is_actived'] = $is_actived;
                                $requestTemp2['game_name'] = $customer_type->game_name;
                                $requestTemp2['odds'] = $customer_type->odds;
                                $requestTemp2['ipaddr'] = $ipaddr;
                                $requestTemp2['choices'] = [];
    
                                for ($i = 0; $i < count($request['choices']); $i++){
                                    $exchange_rates_check = $request['choices'][$i]['exchange'];
                                    if ($game != 18 && $customer_type_by_user->exchange_rates < $exchange_rates_check && $checkbox_lowp) {
                                            $status = 'Giới hạn giá thấp';
                                            $requestTempLow['choices'][] = $request['choices'][$i];
                                    }else{
                                        if (str_contains($locknumber, $request['choices'][$i]['name'])) {
                                            $requestTemp['choices'][] = $request['choices'][$i];
                                        } else {
                                            $requestTemp2['choices'][] = $request['choices'][$i];
                                        }
                                    }
                                }
                                
                                $requestTempLow['status'] = "Giới hạn giá thấp";
                                if (count($requestTempLow['choices']) > 0)
                                    array_push($multi_request, $requestTempLow);
    
                                $status = XoSoRecordHelpers::InsertXosoRecord((object)$requestTemp, $user, false,true);
                                $requestTemp['status'] = $status["status"];
                                // print_r($requestTemp['choices']);
                                if (count($requestTemp['choices']) > 0)
                                    array_push($multi_request, $requestTemp);
    
                                $status = XoSoRecordHelpers::InsertXosoRecord((object)$requestTemp2, $user, false,true);
                                $requestTemp2['status'] = $status["status"];
                                // print_r($requestTemp['choices']);
                                if (count($requestTemp2['choices']) > 0)
                                    array_push($multi_request, $requestTemp2);
    
                                continue;
                            } else{
                                $requestTempLow = array();
                                $requestTempLow['game_code'] = $game;
                                $requestTempLow['is_actived'] = $is_actived;
                                $requestTempLow['game_name'] = $customer_type->game_name;
                                $requestTempLow['odds'] = $customer_type->odds;
                                $requestTempLow['ipaddr'] = $ipaddr;
                                $requestTempLow['choices'] = [];

                                $requestTemp = array();
                                $requestTemp['game_code'] = $game;
                                $requestTemp['is_actived'] = $is_actived;
                                $requestTemp['game_name'] = $customer_type->game_name;
                                $requestTemp['odds'] = $customer_type->odds;
                                $requestTemp['ipaddr'] = $ipaddr;
                                $requestTemp['choices'] = [];

                                $countTemp = 0;
                                $countTemp2 = 0;
                                for ($i = 0; $i < count($request['choices']); $i++){
                                    $exchange_rates_check = $request['choices'][$i]['exchange'];
                                    if ($game != 18 && $customer_type_by_user->exchange_rates < $exchange_rates_check && $checkbox_lowp) {
                                            $status = 'Giới hạn giá thấp';
                                            $requestTempLow['choices'][] = $request['choices'][$i];
                                    }else{
                                        $requestTemp['choices'][] = $request['choices'][$i];
                                    }
                                }

                                $requestTempLow['status'] = "Giới hạn giá thấp";
                                if (count($requestTempLow['choices']) > 0)
                                    array_push($multi_request, $requestTempLow);

                                $status = XoSoRecordHelpers::InsertXosoRecord((object)$requestTemp, $user, false,true);
                                $requestTemp['status'] = $status["status"];
                                // print_r($requestTemp['choices']);
                                if (count($requestTemp['choices']) > 0)
                                    array_push($multi_request, $requestTemp);
                                continue;
                                // $status = XoSoRecordHelpers::InsertXosoRecord((object)$request, $user, false,true);
                                // $status = $status["status"];
                            }
                        }
                    }
                    $request['status'] = $status;
                    // print_r($request);
                    // array_push($multi_request, $request);
                } catch (\Exception $ex) {
                    throw $ex;
                    // echo $ex;
                }
            }
        }
        // print_r($multi_request);
        // Log::info("1110");
        $totalNow = 0;
        $list_tin_cuoc = [];
        $list_tin_huy = [];
        foreach ($multi_request as $requestCuoc) {
            if ($requestCuoc['status'] == '' || $requestCuoc['status'] == 'ok') {
                array_push($list_tin_cuoc, $requestCuoc);

                for ($i = 0; $i < count($requestCuoc['choices']); $i++) {
                    $totalNow += intval($requestCuoc['choices'][$i]['total']);
                }
            } else
                array_push($list_tin_huy, $requestCuoc);
        }
        
        if ($_iscuoc == '1' && $is_actived == true) {
            // echo "ssss";
            // print_r($multi_request);
            
            // $tin_cuoc = $this->revertquickplay($list_tin_cuoc);
            $tin_huy = $this->revertquickplay($list_tin_huy);
            // echo $tin_huy;

            try {
                $record = new QuickPlayRecord();
                $record->content = $quicktext;
                $record->cancel = $tin_huy;
                $record->date = date('Y-m-d');
                $record->user_id = $user->id;
                $record->total = $totalNow;
                $record->save();

                if ($historyID == 0)
                    HistoryHelpers::InsertHistoryQuickBet($quicktext, $totalNow, $user, $tin_huy, $ids);
                else
                    HistoryHelpers::UpdateHistoryQuickBet($historyID, $quicktext, $totalNow, $user, $tin_huy, $ids);
            } catch (\Exception $ex) {
                Log::info("InsertHistoryQuickBet " . $ex->getMessage()) ;
            }
        }
        
        return [$multi_request, $arrRawBetDeRaw[1],$totalNow];
    }

    public function revertquickplay($multi_request,$character_break="")
    {
        $character_break_2nd = " ";
        $groupbygamecode = array();
        foreach ($multi_request as $request) {
            // $request['game_code'];
            // $request['name'];
            // $request['point'];
            $groupbygamecode[$request['game_code']][] = $request;
        }
        $revert2string = "";
        // var_dump($groupbygamecode);
        foreach ($groupbygamecode as $records) {
            // $game_code = $obj->key;
            // $records = $obj->value;

            $groupByCode = "";
            foreach ($records as $request) {

                $revertname = '';
                $point = 0;
                $game_code_name = $this->game_code_to_str($request['game_code']);
                $countbetnumber = 0;
                foreach ($request['choices'] as $count=>$record) {
                    $revertname .= $record['name'] . ( $count < count($request['choices']) - 1 ? ',' : '') ;
                    $point = $record['point'];
                    $countbetnumber++;

                    if ($request['game_code'] == 9 || $request['game_code'] == 10 || $request['game_code'] == 11 || $request['game_code'] == 29) {
                        if ($countbetnumber == 1)
                            $countbetnumber = count(explode(',', $record['name'])) - 1;
                        if ($request['game_code'] == 9)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
                        elseif ($request['game_code'] == 10)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(3) / XoSoRecordHelpers::fact($countbetnumber - 3);
                        elseif ($request['game_code'] == 11)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(4) / XoSoRecordHelpers::fact($countbetnumber - 4);
                        elseif ($request['game_code'] == 29)
                            $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
                            // echo $Ank . " ";
                        if ($record['name'] != '')
                            if ($groupByCode == "")
                                $groupByCode .= $game_code_name . ' ' . $record['name'] . ' x' . ($point / 1) . 'n ' . $character_break_2nd;
                            else
                                $groupByCode .= $record['name'] . ' x' . ($point / 1) . 'n ' . $character_break_2nd;
                    }
                }
                // echo $countbetnumber . " ";
                if ($request['game_code'] == 9 || $request['game_code'] == 10 || $request['game_code'] == 11 || $request['game_code'] == 29) {
                    // if ($countbetnumber == 1)
                    //     $countbetnumber = count(explode(',', $revertname)) - 1;
                    // if ($request['game_code'] == 9)
                    //     $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
                    // elseif ($request['game_code'] == 10)
                    //     $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(3) / XoSoRecordHelpers::fact($countbetnumber - 3);
                    // elseif ($request['game_code'] == 11)
                    //     $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(4) / XoSoRecordHelpers::fact($countbetnumber - 4);
                    // elseif ($request['game_code'] == 29)
                    //     $Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact(2) / XoSoRecordHelpers::fact($countbetnumber - 2);
                    //     echo $Ank . " ";
                    // if ($revertname != '')
                    //     $revert2string .= $game_code_name . ' ' . $revertname . ' x' . ($point / 1) . 'n ' . $character_break;
                } else {

                    if ($revertname != '')
                        if ($request['game_code'] == 7){
                            if ($groupByCode == "")
                                $groupByCode .= $game_code_name . ' ' . $revertname . ' x' . $point . 'd ' .$character_break_2nd;
                            else
                                $groupByCode .= $revertname . ' x' . $point . 'd ' .$character_break_2nd;
                        }
                        else{
                            if ($groupByCode == "")
                                $groupByCode .= $game_code_name . ' ' . $revertname . ' x' . $point . 'n ' .$character_break_2nd;
                            else
                                $groupByCode .=$revertname . ' x' . $point . 'n ' .$character_break_2nd;
                        }
                }
            }

            $revert2string .= $groupByCode . $character_break;
        }
        // echo $revert2string;
        return $revert2string;
    }

    public static function revertquickplayFromDB($record,$character_break="")
    {
        $revert2string = $record->game ." " . $record->bet_number . " tiền cược " . number_format($record->total_bet_money);
        return $revert2string;
    }
}
