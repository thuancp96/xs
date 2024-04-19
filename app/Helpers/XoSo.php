<?php
namespace App\Helpers;

use App\Commands\UpdateBetPriceAllUser_v2;
use App\Commands\UpdateBetPriceAllUser_v4;
use App\Commands\UpdateChildEX;
use App\Game;
use App\Game_Number;
use App\Helpers\DateTimeZone;
use App\Location;
use App\XoSoResult;
use Carbon\Carbon;
use DateTime;
use Sunra\PhpSimple\HtmlDomParser;
use App\Helpers\Curl;
use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Queue;
use stdClass;
use App\game_1478;
use App\Game_1533;
use App\Game_1561;
use App\Game_1650;
use App\Game_1698;

class XoSo
{

    /**
     * Lấy kết quả xổ số 1
     */
    public function getKetQua($locationID,$date)
    {
        if (!is_numeric($locationID)) {
            return [];
        }
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
        {
            return [];
        }

        $result = XoSoResult::where('location_id', intval($locationID))
            ->where('date', $date)->get();
        $location = Location::find(intval($locationID));
        if (!isset($location) || !isset($location->url_api)) {
            // echo $locationID;
            return [];
        }
        else{
            if ($locationID==21 || $locationID==22 || $locationID == 31 || $locationID == 32){
                if(count($result)>0)
                {
                    return [
                        'date' => DateTime::createFromFormat('Y-m-d', $result[0]->date)->format('d-m-Y'),
                        'location' => $location->name,
                        'locationid' => $location->slug,
                        'DB' => $result[0]->DB,
                        '1' => $result[0]->Giai_1,
                        '2' => $result[0]->Giai_2,
                        '3' => $this->SplitStringToArray($result[0]->Giai_3),
                        '4' => $this->SplitStringToArray($result[0]->Giai_4),
                        '5' => $result[0]->Giai_5,
                        '6' => $this->SplitStringToArray($result[0]->Giai_6),
                        '7' => $result[0]->Giai_7,
                        '8' => $result[0]->Giai_8,
                        'than_tai' => $result[0]->than_tai
                    ];
                }else{
                    return [];
                }
            }
            
            if(count($result)>0)
            {
                return [
                    'date' => DateTime::createFromFormat('Y-m-d', $result[0]->date)->format('d-m-Y'),
                    'location' => $location->name,
                    'locationid' => $location->slug,
                    'DB' => $result[0]->DB,
                    '1' => $result[0]->Giai_1,
                    '2' => $this->SplitStringToArray($result[0]->Giai_2),
                    '3' => $this->SplitStringToArray($result[0]->Giai_3),
                    '4' => $this->SplitStringToArray($result[0]->Giai_4),
                    '5' => $this->SplitStringToArray($result[0]->Giai_5),
                    '6' => $this->SplitStringToArray($result[0]->Giai_6),
                    '7' => $this->SplitStringToArray($result[0]->Giai_7),
                    '8' => $result[0]->Giai_8,
                    'spec_character' => $result[0]->spec_character,
                    'than_tai' => $result[0]->than_tai
                ];
            }
            else
            {
                $url = $location->url_api;
                $content = file_get_contents($url);
                $flux = new \SimpleXmlElement($content);
                foreach ($flux->channel->item as $flu)
                {
                    $flu->title;
                    $title = preg_replace('/[^0-9\/]/s', '', $flu->title);
                    $title = str_replace('/', '-',$title)."-".Carbon::now()->year;
                    $time = strtotime($title);
                    $title = date('Y-m-d',$time);
                    if($title == $date)
                    {
                        $pieces = explode("\n", $flu->description);
                        $special = preg_replace('/[^0-9]/s', '', $pieces[1]);
                        $one = preg_replace('/[^0-9]/s', '', $pieces[2]);
                        $one = substr($one,1,strlen($one));
                        $two = $this->getGiai($pieces[3]);
                        $three = $this->getGiai($pieces[4]);
                        $four = $this->getGiai($pieces[5]);
                        $five = $this->getGiai($pieces[6]);
                        $six = $this->getGiai($pieces[7]);
                        $seven = $this->getGiai($pieces[8]);
                        $eight = 0;
                        if(count($pieces) == 10) //con số 10 ở đây ý nghĩa là gì, nên tạo 1 biến tên ý nghĩa rồi gán 10 cho nó thì ng xem sẽ hiểu
                        {
                            $eight= $this->getGiai($pieces[8]);
                        }
                        $data =[
                            'location_id' =>  $location->id,
                            'DB' => $special,
                            'Giai_1' => $one,
                            'Giai_2' => $two,
                            'Giai_3' => $three,
                            'Giai_4' => $four,
                            'Giai_5' => $five,
                            'Giai_6' => $six,
                            'Giai_7' => $seven,
                            'Giai_8' => $eight,
                            'date' => $title,
                        ];
                        $xosoresult = new XoSoResult();
                        $xosoresult->Insert($data);
                        $data =[
                            'location' =>  $location->name,
                            'DB' => $special,
                            '1' => $one,
                            '2' => $this->SplitStringToArray($two),
                            '3' => $this->SplitStringToArray($three),
                            '4' => $this->SplitStringToArray($four),
                            '5' => $this->SplitStringToArray($five),
                            '6' => $this->SplitStringToArray($six),
                            '7' => $this->SplitStringToArray($seven),
                            '8' => $this->SplitStringToArray($eight),
                            'date' => DateTime::createFromFormat('Y-m-d', $title)->format('d-m-Y'),
                        ];
                        return $data;
                    } 
                }
            }
        }
        return [];
    }

    public function getKetQuaToArr($locationID,$date)
    {
        if (!is_numeric($locationID)) {
            return [];
        }
        // if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
        // {
            // return [];
        // }
        $location = Location::find(intval($locationID));
        // if (!isset($location) || !isset($location->url_api)) {
            // echo $locationID;
            // return [];
        // }
        $result = XoSoResult::where('location_id', intval($locationID))
            ->where('date', $date)->get();
            
        if(count($result)>0)
        {
            return [
                'date' => DateTime::createFromFormat('Y-m-d', $result[0]->date)->format('d-m-Y'),
                'location' => $location->name,
                'locationid' => $location->slug,
                'DB' => $result[0]->DB,
                '1' => $result[0]->Giai_1,
                '2' => $this->SplitStringToArray($result[0]->Giai_2),
                '3' => $this->SplitStringToArray($result[0]->Giai_3),
                '4' => $this->SplitStringToArray($result[0]->Giai_4),
                '5' => $this->SplitStringToArray($result[0]->Giai_5),
                '6' => $this->SplitStringToArray($result[0]->Giai_6),
                '7' => $this->SplitStringToArray($result[0]->Giai_7),
                '8' => $result[0]->Giai_8,
                'spec_character' => $result[0]->spec_character,
                'than_tai' => $result[0]->than_tai
            ];
        }
        return [];
    }

    /**
     * Lấy kết quả xổ số 2
     */
     public function getKetQua2today($locationID,$date)
     {
         if (!is_numeric($locationID)) {
             return [];
         }
         if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date))
         {
             return [];
         }
 
         $result = XoSoResult::where('location_id', intval($locationID))
             ->where('date', $date)->get();
         $location = Location::find(intval($locationID));
         if (!isset($location)) {
             return [];
         }
         else{
             if(count($result)>0)
             {
                 return [
                     'date' => DateTime::createFromFormat('Y-m-d', $result[0]->date)->format('d-m-Y'),
                     'location' => $location->name,
                     'DB' => $result[0]->DB,
                     '1' => $result[0]->Giai_1,
                     '2' => $this->SplitStringToArray($result[0]->Giai_2),
                     '3' => $this->SplitStringToArray($result[0]->Giai_3),
                     '4' => $this->SplitStringToArray($result[0]->Giai_4),
                     '5' => $this->SplitStringToArray($result[0]->Giai_5),
                     '6' => $this->SplitStringToArray($result[0]->Giai_6),
                     '7' => $this->SplitStringToArray($result[0]->Giai_7),
                     '8' => $this->SplitStringToArray($result[0]->Giai_8)
                 ];
             }
             else
             {
                $curl = new Curl();
                $linkminhngoc = 'https://www.minhngoc.net.vn/xo-so-truc-tiep/mien-bac.html';
                $response = $curl->get($linkminhngoc);
                $domHtml = HtmlDomParser::str_get_html($response->body);
                $mainBody = $domHtml->find("table.bkqtinhmienbac",0);
                // var_dump($mainBody);
                $info = $mainBody->find("td.ngay",0);
                $date = $info->find("span.tngay",0)->innertext;
                $date = str_replace('Ng&agrave;y: ','',$date);
                $date = str_replace('/', '-', $date);
                $date = strtotime($date);
                $countkq = 0;
                $newformat = date('Y-m-d',$date);
                //echo $newformat ."</br>";
                $now = date('Y-m-d');
                if ($newformat != $now) return null;
                try{
                    $giaidbr = $mainBody->getElementById("td.giaidb")->children();
                    $giaidb = '';
                    //echo 'giaidb ';
                    foreach($giaidbr as $item){
                        //echo $item->innertext .",";
                        if (strlen($giaidb) > 0) $giaidb.=",";
                        if (is_numeric($item->innertext)){
                            $giaidb .= $item->innertext;
                            $countkq++;
                        }else{
                            $giaidb .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
                
                try{
                    $giai1r = $mainBody->getElementById("td.giai1")->children();
                    $giai1='';
                    //echo 'giai1 ';
                    foreach($giai1r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai1) > 0) $giai1.=",";
                        if (is_numeric($item->innertext)){
                            $giai1 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai1 .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
        
                try{
                    $giai2r = $mainBody->getElementById("td.giai2")->children();
                    $giai2='';
                    //echo 'giai2 ';
                    foreach($giai2r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item->innertext)){
                            $giai2 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
        
                try{
                    $giai3r = $mainBody->getElementById("td.giai3")->children();
                    $giai3='';
                    //echo 'giai3 ';
                    foreach($giai3r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item->innertext)){
                            $giai3 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
        
                try{
                    $giai4r = $mainBody->getElementById("td.giai4")->children();
                    $giai4='';
                    //echo 'giai4 ';
                    foreach($giai4r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item->innertext)){
                            $giai4 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai4 .= '----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
        
                try{
                    $giai5r = $mainBody->getElementById("td.giai5")->children();
                    $giai5='';
                    //echo 'giai5 ';
                    foreach($giai5r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item->innertext)){
                            $giai5 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai5 .= '----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
        
                try{
                    $giai6r = $mainBody->getElementById("td.giai6")->children();
                    $giai6='';
                    // echo 'giai6 ';
                    foreach($giai6r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item->innertext)){
                            $giai6 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
        
                try{
                    $giai7r = $mainBody->getElementById("td.giai7")->children();
                    $giai7='';
                    //echo 'giai7 ';
                    foreach($giai7r as $item){
                        //echo $item->innertext .",";
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item->innertext)){
                            $giai7 .= $item->innertext;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}

                    $data =[
                        'location_id' =>  $location->id,
                        'DB' => $giaidb,
                        'Giai_1' => $giai1,
                        'Giai_2' => $giai2,
                        'Giai_3' => $giai3,
                        'Giai_4' => $giai4,
                        'Giai_5' => $giai5,
                        'Giai_6' => $giai6,
                        'Giai_7' => $giai7,
                        'Giai_8' => $giai7,
                        'date' => $newformat,
                    ];
                    if ($countkq < 27) return null;
                    $xosoresult = new XoSoResult();
                    $xosoresult->Insert($data);
                    $data =[
                        'location' =>  $location->name,
                        'DB' => $giaidb,
                        '1' => $giai1,
                        '2' => $this->SplitStringToArray2($giai2),
                        '3' => $this->SplitStringToArray2($giai3),
                        '4' => $this->SplitStringToArray2($giai4),
                        '5' => $this->SplitStringToArray2($giai5),
                        '6' => $this->SplitStringToArray2($giai6),
                        '7' => $this->SplitStringToArray2($giai7),
                        '8' => array(),
                        'date' => $newformat,
                        //DateTime::createFromFormat('Y-m-d', $date)->format('d-m-Y'),
                    ];
                    
                    return $data;
             }
         }
         return [];
     }

    /**
     * Lấy kết quả xổ số
     */
    public function getKetQuaXSA($locationID,$session,$date)
    {
        
        $result = XoSoResult::where('location_id', intval($locationID))
        ->where('session', intval($session))
            ->where('date', $date)->get();
        $location = Location::find(intval($locationID));
        
        if(count($result)>0)
        {
            return [
                'date' => DateTime::createFromFormat('Y-m-d', $result[0]->date)->format('d-m-Y'),
                'location' => $location->name,
                'locationid' => $location->slug,
                'DB' => $result[0]->DB,
                '1' => $result[0]->Giai_1,
                '2' => $this->SplitStringToArray($result[0]->Giai_2),
                '3' => $this->SplitStringToArray($result[0]->Giai_3),
                '4' => $this->SplitStringToArray($result[0]->Giai_4),
                '5' => $this->SplitStringToArray($result[0]->Giai_5),
                '6' => $this->SplitStringToArray($result[0]->Giai_6),
                '7' => $this->SplitStringToArray($result[0]->Giai_7),
                '8' => $this->SplitStringToArray($result[0]->Giai_8)
            ];
        }
        
        return [];
    }

    /**
     * Lấy kết quả xổ số
     */
    public function getKetQuaKeno($locationID,$hour,$min,$date)
    {
        $result = XoSoResult::where('location_id', intval($locationID))
        ->where('updated_at', $date . ' '. ($hour<10?'0'.$hour:$hour) .':'.($min<10?'0'.$min:$min).':00')
        ->orderBy('id','desc')
        ->get();

        $location = Location::find(intval($locationID));
        
        if(count($result)>0)
        {
            return [
                'date' => DateTime::createFromFormat('Y-m-d', $result[0]->date)->format('d-m-Y'),
                'location' => $location->name,
                'locationid' => $location->slug,
                'DB' => $this->SplitStringToArray($result[0]->DB),
                '1' => $result[0]->Giai_1,
                '2' => $this->SplitStringToArray($result[0]->Giai_2),
                '3' => $this->SplitStringToArray($result[0]->Giai_3),
                '4' => $this->SplitStringToArray($result[0]->Giai_4),
                '5' => $this->SplitStringToArray($result[0]->Giai_5),
                '6' => $this->SplitStringToArray($result[0]->Giai_6),
                '7' => $this->SplitStringToArray($result[0]->Giai_7),
                '8' => $result[0]->Giai_8,
                'updated_at' => $result[0]->updated_at,
            ];
        }
        
        return [];
    }

    /**
     * Lấy kết quả xổ số
     */
    public function getKetQuaKenoByDay($locationID,$date)
    {
        $result = XoSoResult::where('location_id', intval($locationID))
        ->where('date', $date)->orderBy('id','desc') ->get();

        $location = Location::find(intval($locationID));
        
        if(count($result)>0)
        {
            return $result;
        }
        
        return [];
    }
    
    public function getPubDate($locationID)
    {
        $dt = new DateTime();
        return $dt->format('Y-m-d H:i:s');

        $location = Location::find(intval($locationID));
        $url = $location->url_api;
        $content = file_get_contents($url);
        $flux = new \SimpleXmlElement($content);
        $pubDate = $flux->channel->pubDate;
        $parsed = strtotime($pubDate);
        $sqldate = gmdate("Y-m-d H:i:s", $parsed);
        return $sqldate;
    }
    private function getGiai($content)
    {

        $new_content = preg_replace('/[^0-9\-]/s', '',$content);
        $new_content = str_replace('-', ',',$new_content);
        $new_content = substr($new_content,1,strlen($new_content));
        return $new_content;
    }

    /**
     * split a string to array
     * if value of array is zero or empty, this will return blank array
     * @param $string
     * @param string $separator
     * @return array
     */
    private function SplitStringToArray($string,$separator=','){
        $array = explode($separator,$string);
        $counter = count($array);
        if($counter>1){
            return $array;
        }
        if($counter==0 || ($counter==1 && empty($array[0]))){
            return [];
        }
        return [];
    }

    /**
     * split a string to array
     * if value of array is zero or empty, this will return blank array
     * @param $string
     * @param string $separator
     * @return array
     */
    private function SplitStringToArray3($string,$separator='|'){
        $array = explode($separator,$string);
        $counter = count($array);
        if($counter>1){
            return $array;
        }
        if($counter==0 || ($counter==1 && empty($array[0]))){
            return [];
        }
        return [];
    }


    /**
     * split a string to array
     * if value of array is zero or empty, this will return blank array
     * @param $string
     * @param string $separator
     * @return array
     */
     private function SplitStringToArray2($string,$separator=','){
        // if (strlen($string) > 1 && $string[strlen($string)-1] == ',')
        //     $string = substr($string,0,strlen($string)-1);
        $array = explode($separator,$string);
        $counter = count($array);
        if($counter>1){
            return $array;
        }
        if($counter==0 || ($counter==1 && empty($array[0]))){
            return [];
        }
        return [];
    }

    public function checkFullResults(){
        $now = date('Y-m-d');
        $kqxs = XoSoResult::where('location_id', 1)
        ->where('date', $now)->get();

        $datetime = new DateTime('yesterday');
        $yesterday = $datetime->format('Y-m-d');

        $kqxs_yesterday = XoSoResult::where('location_id', 1)
        ->where('date', $yesterday)->get();

        if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) )
            return true;
        return false;
    }

    public function fullKq($result,$checkTime = true)
    {
        if(count($result)>0)
        {
            $countkq = 0;

            if (is_numeric($result[0]->DB)){
                $countkq++;
            }
            if (is_numeric($result[0]->Giai_1)){
                $countkq++;
            }

            foreach($this->SplitStringToArray($result[0]->Giai_2) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_3) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_4) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_5) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_6) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            foreach($this->SplitStringToArray($result[0]->Giai_7) as $item){
                if (is_numeric($item)){
                    $countkq++;
                }
            }
            
            // if ($checkTime){
            //     if ( $countkq == 27 && date('i') >= 32 ) return true;
            //     if ( $countkq == 27 && date('H') >= 19 ) return true;
            //     else return false;
            // }else{
            //     if ( $countkq == 27 ) return true;
            // }

            if (date('H') >= 0 && date('H') <= 10){
                if ( $countkq == 27 ) return true;
            }else{
                if ( $countkq == 27 && date('i') >= 32 ) return true;
                if ( $countkq == 27 && date('H') >= 19 ) return true;
            }
            
        }
        return false;
    }

    public function insertDump(){
        echo 'insert';
        $now = date('Y-m-d');
        DB::table('xoso_result')->insert([
            'location_id' =>  1,
            'DB' => '-----',
            'Giai_1' => '-----',
            'Giai_2' => '-----,-----',
            'Giai_3' => '-----,-----,-----,-----,-----,-----',
            'Giai_4' => '----,----,----,----',
            'Giai_5' => '----,----,----,----,----,----',
            'Giai_6' => '---,---,---',
            'Giai_7' => '--,--,--,--',
            'Giai_8' => 0,
            'spec_character' => '-----------------',
            'than_tai' => '--------',
            'date' => $now,
        ]);
    }

    public function updateKQ($id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now,$specCharacter=''){
        // echo 'tbupdateKQ';
        // NotifyHelpers::SendMailNotification('Cap nhat kq '.$countkq);
        if ($specCharacter != '')
            DB::table('xoso_result')
            ->where('id', $id)
            ->update([
                'location_id' =>  1,
                'DB' => $giaidb,
                'Giai_1' => $giai1,
                'Giai_2' => $giai2,
                'Giai_3' => $giai3,
                'Giai_4' => $giai4,
                'Giai_5' => $giai5,
                'Giai_6' => $giai6,
                'Giai_7' => $giai7,
                'Giai_8' => $countkq,
                'spec_character' => $specCharacter,
                'date' => $now,
            ]);
        else
            DB::table('xoso_result')
                ->where('id', $id)
                ->update([
                    'location_id' =>  1,
                    'DB' => $giaidb,
                    'Giai_1' => $giai1,
                    'Giai_2' => $giai2,
                    'Giai_3' => $giai3,
                    'Giai_4' => $giai4,
                    'Giai_5' => $giai5,
                    'Giai_6' => $giai6,
                    'Giai_7' => $giai7,
                    'Giai_8' => $countkq,
                    'date' => $now,
                ]);

        $oldCountkq = Cache::get("updateKQ",0);
        // echo "oldCountkq " .$oldCountkq;
        if ($specCharacter!="" && $countkq == 26 && strlen($specCharacter) >= 20){
            // $oldCountkq = 25;
            $countkq = 26.5;
        }
            
        if ($oldCountkq >= $countkq) return;
        Cache::put("updateKQ",$countkq,60);
        // echo $countkq;
        // return;
        $rs = $this->getKetQua(1,$now);
        $rs_arr = GameHelpers::BuildArrayResultForAlert($rs);

        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";

        $public_channelid = "@thongbaoketquaxosomienbac";
        $public_message = "";
        //thong bao public channel
        if ($countkq ==1) $public_message = "Giải Nhất : ".$rs_arr[$countkq-1];
        if ($countkq ==2) $public_message = "Giải 2.1 : ".$rs_arr[$countkq-1];
        if ($countkq ==3) $public_message = "Giải 2.2 : ".$rs_arr[$countkq-1];
        if ($countkq ==4) $public_message = "Giải 3.1 : ".$rs_arr[$countkq-1];
        if ($countkq ==5) $public_message = "Giải 3.2 : ".$rs_arr[$countkq-1];
        if ($countkq ==6) $public_message = "Giải 3.3 : ".$rs_arr[$countkq-1];
        if ($countkq ==7) $public_message = "Giải 3.4 : ".$rs_arr[$countkq-1];
        if ($countkq ==8) $public_message = "Giải 3.5 : ".$rs_arr[$countkq-1];
        if ($countkq ==9) $public_message = "Giải 3.6 : ".$rs_arr[$countkq-1];
        if ($countkq ==10) $public_message = "Giải 4.1 : ".$rs_arr[$countkq-1];
        if ($countkq ==11) $public_message = "Giải 4.2 : ".$rs_arr[$countkq-1];
        if ($countkq ==12) $public_message = "Giải 4.3 : ".$rs_arr[$countkq-1];
        if ($countkq ==13) $public_message = "Giải 4.4 : ".$rs_arr[$countkq-1];
        if ($countkq ==14) $public_message = "Giải 5.1 : ".$rs_arr[$countkq-1];
        if ($countkq ==15) $public_message = "Giải 5.2 : ".$rs_arr[$countkq-1];
        if ($countkq ==16) $public_message = "Giải 5.3 : ".$rs_arr[$countkq-1];
        if ($countkq ==17) $public_message = "Giải 5.4 : ".$rs_arr[$countkq-1];
        if ($countkq ==18) $public_message = "Giải 5.6 : ".$rs_arr[$countkq-1];
        if ($countkq ==19) $public_message = "Giải 6.1 : ".$rs_arr[$countkq-1];
        if ($countkq ==20) $public_message = "Giải 6.2 : ".$rs_arr[$countkq-1];
        if ($countkq ==21) $public_message = "Giải 6.3 : ".$rs_arr[$countkq-1];
        if ($countkq ==22) $public_message = "Giải 6.4 : ".$rs_arr[$countkq-1];
        if ($countkq ==23) $public_message = "Giải 7.1 : ".$rs_arr[$countkq-1];
        if ($countkq ==24) $public_message = "Giải 7.2 : ".$rs_arr[$countkq-1];
        if ($countkq ==25) $public_message = "Giải 7.3 : ".$rs_arr[$countkq-1];
        if ($countkq ==26) $public_message = "Giải 7.4 : ".$rs_arr[$countkq-1];
        if ($countkq ==27) $public_message = "Giải Đặc biệt : ".$rs_arr[$countkq-1];
        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$public_channelid,$public_message);
        if ($countkq == 27){
            sleep(1);
            $xs = new XoSo();
            $kqxs = $xs->getKetQuaToArr(1, $now);
            $public_message = "<i>Xổ số Miền Bắc</i>   <b>Ngày " . date('d-m-Y', strtotime($now)) . "</b> \n";
            $public_message .= "<i>Ký hiệu Đặc biệt:</i> <b>" . $kqxs['spec_character'] . "</b>\n";
            $public_message .= "<i>Đặc biệt:</i>              <b>" . $kqxs['DB'] . "</b>\n";
            $public_message .= "<i>Nhất:</i>                    <b>" . $kqxs['1'] . "</b>\n";
            $public_message .= "<i>Nhì:</i>          <b>" . $kqxs['2'][0] . "</b>       |           <b>" . $kqxs['2'][1]   . "</b>\n";
            $public_message .= "<i>Ba:</i> <b>" . $kqxs['3'][0] . "</b>     |     <b>" . $kqxs['3'][1] . "</b>     |     <b>" . $kqxs['3'][2] . "</b>\n";
            $public_message .= "       <b>" . $kqxs['3'][3] . "</b>     |     <b>" . $kqxs['3'][4] . "</b>     |     <b>" . $kqxs['3'][5] . "</b>\n";
            $public_message .= "<i>Tư:</i> <b>" . $kqxs['4'][0] . "</b>   |   <b>" . $kqxs['4'][1] . "</b>   |   <b>" . $kqxs['4'][2] . "</b>   |   <b>" . $kqxs['4'][3] . "</b>\n";
            $public_message .= "<i>Năm:</i> <b>" . $kqxs['5'][0] . "</b>    |     <b>" . $kqxs['5'][1] . "</b>    |     <b>" . $kqxs['5'][2] . "</b>\n";
            $public_message .= "           <b>" . $kqxs['5'][3] . "</b>    |     <b>" . $kqxs['5'][4] . "</b>    |     <b>" . $kqxs['5'][5] . "</b>\n";
            $public_message .= "<i>Sáu:</i>   <b>" . $kqxs['6'][0] . "</b>       |      <b>" . $kqxs['6'][1] . "</b>       |      <b>" . $kqxs['6'][2] . "</b>\n";
            $public_message .= "<i>Bảy:</i>    <b>" . $kqxs['7'][0] . "</b>     |    <b>" . $kqxs['7'][1] . "</b>     |    <b>" . $kqxs['7'][2] . "</b>     |    <b>" . $kqxs['7'][3] . "</b>\n";
            $public_message .= "<i>Thần tài:</i>                  <b>" . $kqxs['than_tai'] . "</b>\n";
            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$public_channelid,$public_message);
            // $message .= "<i>Cập nhật:</i> <b>" . date("H:i:s", $t[1]) . substr((string)$t[0], 1, 3) . "</b>" . "\n";
        }

        $private_channelid = "-1001667315543";
        $message = "";
        
        if ($countkq != 26.5)
            $message = date("H:i:s") . " ra kết quả " . $rs_arr[$countkq-1]  . ". Còn (" . (27-$countkq) . ")" . PHP_EOL;
            // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,date("H:i:s") . " ra kết quả " . $rs_arr[$countkq-1]  . ". Còn (" . (27-$countkq) . ")" );
        if ($countkq ==1) $message .= "Khóa Nhất, 3 Càng nhất, Đầu nhất, Khóa Giải 3.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Nhất, 3 Càng nhất, Đầu nhất, Giải 2.2");
        if ($countkq ==2) $message .="Khóa Giải 3.3";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.1");
        if ($countkq ==3) $message .="Khóa Giải 3.4";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.2");
        if ($countkq ==4) $message .="Khóa Giải 3.5";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.3");
        if ($countkq ==5) $message .="Khóa Giải 3.6";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.4");
        if ($countkq ==6) $message .="Khóa Giải 4.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.5");
        if ($countkq ==7) $message .="Khóa Giải 4.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.6");
        if ($countkq ==8) $message .="Khóa Giải 4.3";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.1");
        if ($countkq ==9) $message .="Khóa Giải 4.4";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.2");
        if ($countkq ==10) $message .="Khóa Giải 5.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.3");
        if ($countkq ==11) $message .="Khóa Giải 5.2";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.4");
        if ($countkq ==12) $message .="Khóa Giải 5.3";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.1");
        if ($countkq ==13) $message .="Khóa Giải 5.4";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.2");
        if ($countkq ==14) $message .="Khóa Giải 5.5";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.3");
        if ($countkq ==15) $message .="Khóa Giải 5.6";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.4");
        if ($countkq ==16) $message .="Khóa Giải 6.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.5");
        if ($countkq ==17) $message .="Khóa Giải 6.2";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.6");
        if ($countkq ==18) $message .="Khóa Giải 6.3";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 6.1");
        if ($countkq ==19) $message .="Khóa Giải 7.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 6.2");
        if ($countkq ==20) $message .="Khóa Giải 7.2";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 6.3");
        if ($countkq ==21) $message .="Giải 7.3, Giải 7.4";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 7.1");
        // if ($countkq ==22) $message .="Khóa Giải 7.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 7.2");
        if ($countkq ==23) $message .="Khóa Lô xiên";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Lô xiên, Giải 7.3, Giải 7.4");
        if ($specCharacter!="" && $countkq == 26.5 && strlen($specCharacter) >= 20) $message .= "Khóa Đề, Đầu đặc biệt, 3 càng";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Đề, Đầu đặc biệt, 3 càng");
        if ($countkq ==25) $message .="Khóa Lô live";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Lô live");
        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$private_channelid,$message);

        if ($countkq == 27){
            $xs = new XoSo();
            $kqxs = $xs->getKetQuaToArr(1, $now);
            $message = "<i>Xổ số Miền Bắc</i>   <b>Ngày " . date('d-m-Y', strtotime($now)) . "</b> \n";
            $message .= "<i>Ký hiệu Đặc biệt:</i> <b>" . $kqxs['spec_character'] . "</b>\n";
            $message .= "<i>Đặc biệt:</i>              <b>" . $kqxs['DB'] . "</b>\n";
            $message .= "<i>Nhất:</i>                    <b>" . $kqxs['1'] . "</b>\n";
            $message .= "<i>Nhì:</i>          <b>" . $kqxs['2'][0] . "</b>       |           <b>" . $kqxs['2'][1]   . "</b>\n";
            $message .= "<i>Ba:</i> <b>" . $kqxs['3'][0] . "</b>     |     <b>" . $kqxs['3'][1] . "</b>     |     <b>" . $kqxs['3'][2] . "</b>\n";
            $message .= "       <b>" . $kqxs['3'][3] . "</b>     |     <b>" . $kqxs['3'][4] . "</b>     |     <b>" . $kqxs['3'][5] . "</b>\n";
            $message .= "<i>Tư:</i> <b>" . $kqxs['4'][0] . "</b>   |   <b>" . $kqxs['4'][1] . "</b>   |   <b>" . $kqxs['4'][2] . "</b>   |   <b>" . $kqxs['4'][3] . "</b>\n";
            $message .= "<i>Năm:</i> <b>" . $kqxs['5'][0] . "</b>    |     <b>" . $kqxs['5'][1] . "</b>    |     <b>" . $kqxs['5'][2] . "</b>\n";
            $message .= "           <b>" . $kqxs['5'][3] . "</b>    |     <b>" . $kqxs['5'][4] . "</b>    |     <b>" . $kqxs['5'][5] . "</b>\n";
            $message .= "<i>Sáu:</i>   <b>" . $kqxs['6'][0] . "</b>       |      <b>" . $kqxs['6'][1] . "</b>       |      <b>" . $kqxs['6'][2] . "</b>\n";
            $message .= "<i>Bảy:</i>    <b>" . $kqxs['7'][0] . "</b>     |    <b>" . $kqxs['7'][1] . "</b>     |    <b>" . $kqxs['7'][2] . "</b>     |    <b>" . $kqxs['7'][3] . "</b>\n";
            $message .= "<i>Thần tài:</i>                  <b>" . $kqxs['than_tai'] . "</b>\n";
            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$private_channelid,$message);
            // $message .= "<i>Cập nhật:</i> <b>" . date("H:i:s", $t[1]) . substr((string)$t[0], 1, 3) . "</b>" . "\n";
        }
        return 0;
    }

    public function testSSS(){
    }

    public function tbupdateKQ($countkq,$now,$specCharacter=''){
        // echo 'tbupdateKQ';
        $oldCountkq = Cache::get("updateKQ",0);
        // echo "oldCountkq " .$oldCountkq;
        if ($specCharacter!="" && $countkq == 26 && strlen($specCharacter) >= 20){
            // $oldCountkq = 25;
            $countkq = 26.5;
        }
            
        if ($oldCountkq >= $countkq) return;
        Cache::put("updateKQ",$countkq,60);
        // echo $countkq;
        // return;
        
        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
        $channelid = "@thongbaoketquaxoso";
        
        // NotifyHelpers::SendMailNotification('Cap nhat kq '.$countkq);
        // if ($specCharacter != '')
        //     DB::table('xoso_result')
        //     ->where('id', $id)
        //     ->update([
        //         'location_id' =>  1,
        //         'DB' => $giaidb,
        //         'Giai_1' => $giai1,
        //         'Giai_2' => $giai2,
        //         'Giai_3' => $giai3,
        //         'Giai_4' => $giai4,
        //         'Giai_5' => $giai5,
        //         'Giai_6' => $giai6,
        //         'Giai_7' => $giai7,
        //         'Giai_8' => $countkq,
        //         'spec_character' => $specCharacter,
        //         'date' => $now,
        //     ]);
        // else
        //     DB::table('xoso_result')
        //         ->where('id', $id)
        //         ->update([
        //             'location_id' =>  1,
        //             'DB' => $giaidb,
        //             'Giai_1' => $giai1,
        //             'Giai_2' => $giai2,
        //             'Giai_3' => $giai3,
        //             'Giai_4' => $giai4,
        //             'Giai_5' => $giai5,
        //             'Giai_6' => $giai6,
        //             'Giai_7' => $giai7,
        //             'Giai_8' => $countkq,
        //             'date' => $now,
        //         ]);

        $message = "";
        $rs = $this->getKetQua(1,$now);
        $rs_arr = GameHelpers::BuildArrayResultForAlert($rs);
        if ($countkq != 26.5)
            $message = date("H:i:s") . " ra kết quả " . $rs_arr[$countkq-1]  . ". Còn (" . (27-$countkq) . ")" . PHP_EOL;
            // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,date("H:i:s") . " ra kết quả " . $rs_arr[$countkq-1]  . ". Còn (" . (27-$countkq) . ")" );
        if ($countkq ==1) $message .= "Khóa Nhất, 3 Càng nhất, Đầu nhất, Giải 2.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Nhất, 3 Càng nhất, Đầu nhất, Giải 2.2");
        if ($countkq ==2) $message .="Khóa Giải 3.1";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.1");
        if ($countkq ==3) $message .="Khóa Giải 3.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.2");
        if ($countkq ==4) $message .="Khóa Giải 3.3";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.3");
        if ($countkq ==5) $message .="Khóa Giải 3.4";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.4");
        if ($countkq ==6) $message .="Khóa Giải 3.5";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.5");
        if ($countkq ==7) $message .="Khóa Giải 3.6";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 3.6");
        if ($countkq ==8) $message .="Khóa Giải 4.1";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.1");
        if ($countkq ==9) $message .="Khóa Giải 4.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.2");
        if ($countkq ==10) $message .="Khóa Giải 4.3";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.3");
        if ($countkq ==11) $message .="Khóa Giải 4.4";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 4.4");
        if ($countkq ==12) $message .="Khóa Giải 5.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.1");
        if ($countkq ==13) $message .="Khóa Giải 5.2";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.2");
        if ($countkq ==14) $message .="Khóa Giải 5.3";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.3");
        if ($countkq ==15) $message .="Khóa Giải 5.4";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.4");
        if ($countkq ==16) $message .="Khóa Giải 5.5";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.5");
        if ($countkq ==17) $message .="Khóa Giải 5.6";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 5.6");
        if ($countkq ==18) $message .="Khóa Giải 6.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 6.1");
        if ($countkq ==19) $message .="Khóa Giải 6.2";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 6.2");
        if ($countkq ==20) $message .="Khóa Giải 6.3";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 6.3");
        if ($countkq ==21) $message .="Khóa Giải 7.1";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 7.1");
        if ($countkq ==22) $message .="Khóa Giải 7.2";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Giải 7.2");
        if ($countkq ==23) $message .="Khóa Lô xiên, Giải 7.3, Giải 7.4";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Lô xiên, Giải 7.3, Giải 7.4");
        if ($specCharacter!="" && $countkq == 26.5 && strlen($specCharacter) >= 20) $message .= "Khóa Đề, Đầu đặc biệt, 3 càng";
        //  NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Đề, Đầu đặc biệt, 3 càng");
        if ($countkq ==25) $message .="Khóa Lô live";
        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid,"Khóa Lô live");
        echo $message;
        return 0;
    }

    public function insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now){
        DB::table('xoso_result')->insert([
            'location_id' =>  1,
            'DB' => $giaidb,
            'Giai_1' => $giai1,
            'Giai_2' => $giai2,
            'Giai_3' => $giai3,
            'Giai_4' => $giai4,
            'Giai_5' => $giai5,
            'Giai_6' => $giai6,
            'Giai_7' => $giai7,
            'Giai_8' => $countkq,
            'date' => $now,
        ]);
        return 0;
    }
    
    public function generateByMinhNgocJS(){
        // for($i=1;$i<=2;$i++)
            try{
                // usleep( 500000 );
                // sleep(1);   
                $now = date('Y-m-d');
                $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();

                $datetime = new DateTime('yesterday');
                $yesterday = $datetime->format('Y-m-d');

                $kqxs_yesterday = XoSoResult::where('location_id', 1)
                ->where('date', $yesterday)->get();

                // var_dump($kqxs_yesterday);
                if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    // echo 'generateByMinhNgoc'.$i;
                    echo 'generateByMinhNgocJS';
                    $curl = new Curl();
                    $linkminhngoc = 'https://www.minhngoc.net.vn/getkqxs/mien-bac.js';
                    $response = $curl->get($linkminhngoc);
                    
                    $pattern = "/(<div><div class=\"box_kqxs_mini.*?.'\);)/";
                    
                    // $parts = preg_split($pattern, $text);
                            
                    preg_match_all($pattern, $response, $matches);

                    $htmlData = ( isset($matches) && count($matches)>0 )? $matches[0] : [];
                    $htmlData = str_replace("');",'',$htmlData[0]);
                    // var_dump($htmlData);
                    // return
                    $domHtml = HtmlDomParser::str_get_html($htmlData);

                    if (!isset($domHtml))
                        return 2;
                    $mainBody = $domHtml->find("table.bkqtinhmienbac_mini",0);
                    
                    if (!isset($mainBody))
                        return 2;
                    // var_dump($mainBody);

                    // $info = $mainBody->find("td.ngay",0);
                    // if (!isset($info))
                    //     return 2;
                        
                    // $date = $info->find("span.tngay",0)->innertext;

                    // $specCharacter = '-';
                    // try{
                    //     $specCharacter = $info->find("span[id]",0)->innertext;
                    //     echo $specCharacter;
                    //     if (strlen($specCharacter) >= 60 || strlen($specCharacter) < 10) {
                    //         $specCharacter = '-';
                    //     }
                    // }catch(\Exception $ex){
                    //     $specCharacter = '-';
                    // }

                    // if (!isset($date))
                        // return 2;
                        
                    // $date = str_replace('Ng&agrave;y: ','',$date);
                    // $date = str_replace('/', '-', $date);
                    // $date = strtotime($date);
                    
                    // $newformat = date('Y-m-d',$date);
                    // //echo $newformat ."</br>";
                    // $now = date('Y-m-d');
                    // if ($newformat != $now) return 0;
                        // var_dump($mainBody);
                    $countkq = 0;
                    try{
                        $giaidbr = $mainBody->getElementById("td.giaidb")->innertext;
                        $giaidb = '-----';
                        $giaidbr = trim($giaidbr);
                        // echo 'giaidb: '.trim($giaidbr);
                        if (is_numeric($giaidbr)){
                            $giaidb = $giaidbr;
                            $countkq++;
                        }
                        echo $giaidb ." ";
                    }catch(\Exception $ex){}

                    try{
                        $giai1r = $mainBody->getElementById("td.giai1")->innertext;
                        $giai1 = '-----';
                        $giai1r = trim($giai1r);
                        // echo 'giai1: '.trim($giai1r);
                        if (is_numeric($giai1r)){
                            $giai1 = $giai1r;
                            $countkq++;
                        }
                        echo $giai1 ." ";
                    }catch(\Exception $ex){}

                    try{
                        $giai2r = $mainBody->getElementById("td.giai2")->innertext;
                        $giai2r = trim($giai2r);
                        $giai2r = str_replace(" ","",$giai2r);
                        $giai2r = explode('-',$giai2r);
                        $giai2='';
                        //echo 'giai2 ';
                        foreach($giai2r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai2) > 0) $giai2.=",";
                            if (is_numeric($item)){
                                $giai2 .= $item;
                                $countkq++;
                            }else{
                                $giai2 .= '-----';
                            }
                        }
                        echo $giai2 ." ";
                    }catch(\Exception $ex){}
                    // return;
                    try{
                        $giai3r = $mainBody->getElementById("td.giai3")->innertext;
                        $giai3='';
                        $giai3r = trim($giai3r);
                        $giai3r = str_replace(" ","",$giai3r);
                        $giai3r = explode('-',$giai3r);

                        //echo 'giai2 ';
                        foreach($giai3r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai3) > 0) $giai3.=",";
                            if (is_numeric($item)){
                                $giai3 .= $item;
                                $countkq++;
                            }else{
                                $giai3 .= '-----';
                            }
                        }
                        echo $giai3 ." ";
                    }catch(\Exception $ex){}

                    try{
                        $giai4r = $mainBody->getElementById("td.giai4")->innertext;
                        $giai4='';
                        $giai4r = trim($giai4r);
                        $giai4r = str_replace(" ","",$giai4r);
                        $giai4r = explode('-',$giai4r);

                        //echo 'giai2 ';
                        foreach($giai4r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai4) > 0) $giai4.=",";
                            if (is_numeric($item)){
                                $giai4 .= $item;
                                $countkq++;
                            }else{
                                $giai4 .= '-----';
                            }
                        }
                        echo $giai4 ." ";
                    }catch(\Exception $ex){}

                    try{
                        $giai5r = $mainBody->getElementById("td.giai5")->innertext;
                        $giai5='';
                        $giai5r = trim($giai5r);
                        $giai5r = str_replace(" ","",$giai5r);
                        $giai5r = explode('-',$giai5r);

                        //echo 'giai2 ';
                        foreach($giai5r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai5) > 0) $giai5.=",";
                            if (is_numeric($item)){
                                $giai5 .= $item;
                                $countkq++;
                            }else{
                                $giai5 .= '-----';
                            }
                        }
                        echo $giai5 ." ";
                    }catch(\Exception $ex){}

                    try{
                        $giai6r = $mainBody->getElementById("td.giai6")->innertext;
                        $giai6='';
                        $giai6r = trim($giai6r);
                        $giai6r = str_replace(" ","",$giai6r);
                        $giai6r = explode('-',$giai6r);

                        //echo 'giai2 ';
                        foreach($giai6r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai6) > 0) $giai6.=",";
                            if (is_numeric($item)){
                                $giai6 .= $item;
                                $countkq++;
                            }else{
                                $giai6 .= '-----';
                            }
                        }
                        echo $giai6 ." ";
                    }catch(\Exception $ex){}

                    try{
                        $giai7r = $mainBody->getElementById("td.giai7")->innertext;
                        $giai7='';
                        $giai7r = trim($giai7r);
                        $giai7r = str_replace(" ","",$giai7r);
                        $giai7r = explode('-',$giai7r);

                        //echo 'giai2 ';
                        foreach($giai7r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai7) > 0) $giai7.=",";
                            if (is_numeric($item)){
                                $giai7 .= $item;
                                $countkq++;
                            }else{
                                $giai7 .= '-----';
                            }
                        }
                        echo $giai7 ." ";
                    }catch(\Exception $ex){}
                    // return;
                    $specCharacter = '-';
                    // try{
                    //     $giaikhr = $mainBody->getElementByTagName("div.loai_ve")->children();
                    //     $giaikh = '';
                    //     //echo 'giaidb ';
                    //     foreach($giaikhr as $item){
                    //         // echo $item->innertext .",";
                    //         // if (strlen($giaikh) > 0) $giaikh.=",";

                    //         if (strpos($item->innertext, 'SC-') !== false) {
                    //             $giaikh = $item->innertext;
                    //             $countkq++;
                    //         }
                    //     }
                    //     // echo $giaikh;
                    // }catch(\Exception $ex){}
                    //echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs[0]->DB;
                    // print_r($kqxs[0]);
                    if (count($kqxs) < 1){
                        if (($kqxs_yesterday[0]->DB != $giaidb)){
                        echo 'insert';
                        $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                    }
                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs = $kqxs->first();
                        echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                        if ($countkq > $kqxs->Giai_8 
                        || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                        || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                            if ($kqxs_yesterday[0]->DB != $giaidb){
                            echo 'update';
                            $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                            }
                        }
                    }

                    return 0;
                }
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                NotifyHelpers::SendTelegramNotification('error generateByMinhNgoc '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                return 2;
            }
        return 0;
    }
    
    public function generateByMinhNgoc(){
        // for($i=1;$i<=2;$i++)
            try{
                // usleep( 500000 );
                // sleep(1);   
                $now = date('Y-m-d');
                $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();

                $datetime = new DateTime('yesterday');
                $yesterday = $datetime->format('Y-m-d');

                $kqxs_yesterday = XoSoResult::where('location_id', 1)
                ->where('date', $yesterday)->get();

                var_dump($kqxs_yesterday);
                if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    // echo 'generateByMinhNgoc'.$i;
                    echo 'generateByMinhNgoc';
                    $curl = new Curl();
                    $linkminhngoc = 'https://www.minhngoc.net.vn/xo-so-truc-tiep/mien-bac.html';
                    $response = $curl->get($linkminhngoc);
                    // var_dump($response);
                    $domHtml = HtmlDomParser::str_get_html($response->body);

                    if (!isset($domHtml))
                        return 2;
                    $mainBody = $domHtml->find("table.bkqtinhmienbac",0);
                    
                    if (!isset($mainBody))
                        return 2;
                    // var_dump($mainBody);

                    $info = $mainBody->find("td.ngay",0);
                    if (!isset($info))
                        return 2;
                        
                    $date = $info->find("span.tngay",0)->innertext;

                    $specCharacter = '-';
                    try{
                        $specCharacter = $info->find("span[id]",0)->innertext;
                        echo $specCharacter;
                        if (strlen($specCharacter) >= 60 || strlen($specCharacter) < 10) {
                            $specCharacter = '-';
                        }
                    }catch(\Exception $ex){
                        $specCharacter = '-';
                    }
                    
                    

                    if (!isset($date))
                        return 2;
                        
                    $date = str_replace('Ng&agrave;y: ','',$date);
                    $date = str_replace('/', '-', $date);
                    $date = strtotime($date);
                    $countkq = 0;
                    $newformat = date('Y-m-d',$date);
                    //echo $newformat ."</br>";
                    $now = date('Y-m-d');
                    if ($newformat != $now) return 0;

                    try{
                        $giaidbr = $mainBody->getElementById("td.giaidb")->children();
                        $giaidb = '';
                        //echo 'giaidb ';
                        foreach($giaidbr as $item){
                            //echo $item->innertext .",";
                            if (strlen($giaidb) > 0) $giaidb.=",";
                            if (is_numeric($item->innertext)){
                                $giaidb .= $item->innertext;
                                $countkq++;
                            }else{
                                $giaidb .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}
                    
                    try{
                        $giai1r = $mainBody->getElementById("td.giai1")->children();
                        $giai1='';
                        //echo 'giai1 ';
                        foreach($giai1r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai1) > 0) $giai1.=",";
                            if (is_numeric($item->innertext)){
                                $giai1 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai1 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai2r = $mainBody->getElementById("td.giai2")->children();
                        $giai2='';
                        //echo 'giai2 ';
                        foreach($giai2r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai2) > 0) $giai2.=",";
                            if (is_numeric($item->innertext)){
                                $giai2 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai2 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai3r = $mainBody->getElementById("td.giai3")->children();
                        $giai3='';
                        //echo 'giai3 ';
                        foreach($giai3r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai3) > 0) $giai3.=",";
                            if (is_numeric($item->innertext)){
                                $giai3 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai3 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai4r = $mainBody->getElementById("td.giai4")->children();
                        $giai4='';
                        //echo 'giai4 ';
                        foreach($giai4r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai4) > 0) $giai4.=",";
                            if (is_numeric($item->innertext)){
                                $giai4 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai4 .= '----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai5r = $mainBody->getElementById("td.giai5")->children();
                        $giai5='';
                        //echo 'giai5 ';
                        foreach($giai5r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai5) > 0) $giai5.=",";
                            if (is_numeric($item->innertext)){
                                $giai5 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai5 .= '----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai6r = $mainBody->getElementById("td.giai6")->children();
                        $giai6='';
                        // echo 'giai6 ';
                        foreach($giai6r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai6) > 0) $giai6.=",";
                            if (is_numeric($item->innertext)){
                                $giai6 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai6 .= '---';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai7r = $mainBody->getElementById("td.giai7")->children();
                        $giai7='';
                        //echo 'giai7 ';
                        foreach($giai7r as $item){
                            //echo $item->innertext .",";
                            if (strlen($giai7) > 0) $giai7.=",";
                            if (is_numeric($item->innertext)){
                                $giai7 .= $item->innertext;
                                $countkq++;
                            }else{
                                $giai7 .= '--';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    // try{
                    //     $giaikhr = $mainBody->getElementByTagName("div.loai_ve")->children();
                    //     $giaikh = '';
                    //     //echo 'giaidb ';
                    //     foreach($giaikhr as $item){
                    //         // echo $item->innertext .",";
                    //         // if (strlen($giaikh) > 0) $giaikh.=",";

                    //         if (strpos($item->innertext, 'SC-') !== false) {
                    //             $giaikh = $item->innertext;
                    //             $countkq++;
                    //         }
                    //     }
                    //     // echo $giaikh;
                    // }catch(\Exception $ex){}
                    //echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs[0]->DB;
                    // print_r($kqxs[0]);
                    if (count($kqxs) < 1){
                        if (($kqxs_yesterday[0]->DB != $giaidb)){
                        echo 'insert';
                        $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                    }
                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs = $kqxs->first();
                        echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                        if ($countkq > $kqxs->Giai_8 
                        || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                        || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                            if ($kqxs_yesterday[0]->DB != $giaidb){
                            echo 'update';
                            $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now,$specCharacter);
                            }
                        }
                    }

                    return 0;
                }
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                NotifyHelpers::SendTelegramNotification('error generateByMinhNgoc '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                return 2;
            }
        return 0;
    }

    public function generateByxoso888(){
        // for($i=1;$i<=2;$i++)
            try{
                // usleep( 500000 );
                // sleep(1);   
                $now = date('Y-m-d');
                $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();

                $datetime = new DateTime('yesterday');
                $yesterday = $datetime->format('Y-m-d');

                $kqxs_yesterday = XoSoResult::where('location_id', 1)
                ->where('date', $yesterday)->get();

                // // var_dump($kqxs);
                if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    // echo 'generateByMinhNgoc'.$i;
                    echo 'generateByxoso888';
                    $curl = new Curl();
                    $linkminhngoc = 'http://xoso888.vn/truc-tiep/mien-bac.html';
                    $response = $curl->get($linkminhngoc);
                    // var_dump($response);
                    $domHtml = HtmlDomParser::str_get_html($response->body);

                    if (!isset($domHtml))
                        return 2;
                    $mainBody = $domHtml->find("div.w3-half",0)->find("div.ketqua",0)->find("table",0)->find("tr");
                    // var_dump($mainBody);
                    $arrayKQ = array();
                    foreach($mainBody as $child){
                        // var_dump($child);
                        $text = $child->plaintext;
                        $text = trim($text);
                        $text = str_replace(['						','  ','								','		','   	 	   ',' 	  	    ','   '],' ',$text);
                        $text = str_replace('  ',' ',$text);
                        // var_dump($text);
                        $arrayKQ_temp = explode(' ',$text);
                        array_push($arrayKQ,$arrayKQ_temp);
                        // var_dump($text);
                    }
                    // return;
                    // if (!isset($mainBody))
                        // return 2;
                    // var_dump($arrayKQ);
                    $countkq = 0;
                    $specCharacter = '-';
                    // try{
                    //     $tempArr = array();
                    //     $tempArr = array_splice($arrayKQ[0],2);
                    //     $specCharacter = implode('-',$tempArr) ;
                    //     // var_dump($tempArr);
                    //     // echo $specCharacter;
                    //     if (strlen($specCharacter) >= 60 || strlen($specCharacter) < 10) {
                    //         $specCharacter = '-';
                    //     }
                    // }catch(\Exception $ex){
                    //     $specCharacter = '-';
                    // }

                    try{
                        $tempArr = array();
                        $tempArr = array_splice($arrayKQ[1],1);
                        // var_dump($tempArr);
                        $giaidb = implode('-',$tempArr) ;
                        // echo $giaidb;
                        if (is_numeric($giaidb)){
                            $countkq++;
                        }
                    }catch(\Exception $ex){
                        $giaidb = '-----';
                    }

                    try{
                        $tempArr = array();
                        $tempArr = array_splice($arrayKQ[2],1);
                        // var_dump($tempArr);
                        $giai1 = implode(',',$tempArr) ;
                        if (is_numeric($giai1)){
                            $countkq++;
                        }
                    }catch(\Exception $ex){
                        $giai1 = '-----';
                    }

                    try{
                        // var_dump($arrayKQ);
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[3],1);
                        $tempArr2 = array_splice($arrayKQ[4],0);
                        $tempArr = array_merge($tempArr2);
                        var_dump($tempArr);
                        $giai2 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai2 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[5],1);
                        $tempArr2 = array_splice($arrayKQ[6],0);
                        $tempArr3 = array_splice($arrayKQ[7],0);
                        $tempArr = array_merge($tempArr2,$tempArr3);
                        // var_dump($tempArr);
                        $giai3 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai3 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[8],1);
                        $tempArr2 = array_splice($arrayKQ[9],0);
                        $tempArr3 = array_splice($arrayKQ[10],0);
                        $tempArr = array_merge($tempArr2,$tempArr3);
                        // var_dump($tempArr);
                        $giai4 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai4 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[11],1);
                        $tempArr2 = array_splice($arrayKQ[12],0);
                        $tempArr3 = array_splice($arrayKQ[13],0);
                        $tempArr = array_merge($tempArr2,$tempArr3);
                        // var_dump($tempArr);
                        $giai5 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai5 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[14],1);
                        $tempArr2 = array_splice($arrayKQ[15],0);
                        $tempArr = array_merge($tempArr2);
                        // var_dump($tempArr);
                        $giai6 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai6 = '-----';
                    }

                    try{
                        // var_dump($arrayKQ);
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[16],1);
                        $tempArr2 = array_splice($arrayKQ[17],0);
                        $tempArr = array_merge($tempArr2);
                        // var_dump($tempArr);
                        $giai7 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai7 = '-----';
                    }

                    if (count($kqxs) < 1){
                        if (($kqxs_yesterday[0]->DB != $giaidb)){
                        echo 'insert';
                        $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                    }
                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs = $kqxs->first();
                        echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                        if ($countkq > $kqxs->Giai_8 
                        || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                        || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                            if ($kqxs_yesterday[0]->DB != $giaidb){
                            echo 'update';
                            $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                            }
                        }
                    }

                    return 0;
                }
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                NotifyHelpers::SendTelegramNotification('error generateByxoso888 '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                return 2;
            }
        return 0;
    }

    public function generateByxoso888Pack(){
        // for($i=1;$i<=2;$i++)
            try{
                // usleep( 500000 );
                // sleep(1);   
                $now = date('Y-m-d');
                $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();

                $datetime = new DateTime('yesterday');
                $yesterday = $datetime->format('Y-m-d');

                $kqxs_yesterday = XoSoResult::where('location_id', 1)
                ->where('date', $yesterday)->get();

                // // var_dump($kqxs);
                if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    // echo 'generateByMinhNgoc'.$i;
                    echo 'generateByxoso888Pack';
                    $curl = new Curl();
                    $linkminhngoc = 'http://xoso888.vn/html/kq_mienbac.tpl?t='.time();
                    $response = $curl->get($linkminhngoc);
                    // var_dump($response);
                    $domHtml = HtmlDomParser::str_get_html($response->body);

                    if (!isset($domHtml))
                        return 2;
                    $mainBody = $domHtml->find("div.ketqua",0)->find("table",0)->find("tr");
                    // var_dump($mainBody);
                    $arrayKQ = array();
                    foreach($mainBody as $child){
                        // var_dump($child);
                        $text = $child->plaintext;
                        $text = trim($text);
                        $text = str_replace(['						','  ','								','		','   	 	   ',' 	  	    ','   '],' ',$text);
                        $text = str_replace('  ',' ',$text);
                        // var_dump($text);
                        $arrayKQ_temp = explode(' ',$text);
                        array_push($arrayKQ,$arrayKQ_temp);
                        // var_dump($text);
                    }
                    // return;
                    // if (!isset($mainBody))
                        // return 2;
                    // var_dump($arrayKQ);
                    $countkq = 0;
                    $specCharacter = '-';
                    // try{
                    //     $tempArr = array();
                    //     $tempArr = array_splice($arrayKQ[0],2);
                    //     $specCharacter = implode('-',$tempArr) ;
                    //     // var_dump($tempArr);
                    //     // echo $specCharacter;
                    //     if (strlen($specCharacter) >= 60 || strlen($specCharacter) < 10) {
                    //         $specCharacter = '-';
                    //     }
                    // }catch(\Exception $ex){
                    //     $specCharacter = '-';
                    // }

                    try{
                        $tempArr = array();
                        $tempArr = array_splice($arrayKQ[1],1);
                        // var_dump($tempArr);
                        $giaidb = implode('-',$tempArr) ;
                        // echo $giaidb;
                        if (is_numeric($giaidb)){
                            $countkq++;
                        }
                    }catch(\Exception $ex){
                        $giaidb = '-----';
                    }

                    try{
                        $tempArr = array();
                        $tempArr = array_splice($arrayKQ[2],1);
                        // var_dump($tempArr);
                        $giai1 = implode(',',$tempArr) ;
                        if (is_numeric($giai1)){
                            $countkq++;
                        }
                    }catch(\Exception $ex){
                        $giai1 = '-----';
                    }

                    try{
                        // var_dump($arrayKQ);
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[3],1);
                        $tempArr2 = array_splice($arrayKQ[4],0);
                        $tempArr = array_merge($tempArr2);
                        var_dump($tempArr);
                        $giai2 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai2 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[5],1);
                        $tempArr2 = array_splice($arrayKQ[6],0);
                        $tempArr3 = array_splice($arrayKQ[7],0);
                        $tempArr = array_merge($tempArr2,$tempArr3);
                        // var_dump($tempArr);
                        $giai3 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai3 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[8],1);
                        $tempArr2 = array_splice($arrayKQ[9],0);
                        $tempArr3 = array_splice($arrayKQ[10],0);
                        $tempArr = array_merge($tempArr2,$tempArr3);
                        // var_dump($tempArr);
                        $giai4 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai4 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[11],1);
                        $tempArr2 = array_splice($arrayKQ[12],0);
                        $tempArr3 = array_splice($arrayKQ[13],0);
                        $tempArr = array_merge($tempArr2,$tempArr3);
                        // var_dump($tempArr);
                        $giai5 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai5 = '-----';
                    }

                    try{
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[14],1);
                        $tempArr2 = array_splice($arrayKQ[15],0);
                        $tempArr = array_merge($tempArr2);
                        // var_dump($tempArr);
                        $giai6 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai6 = '-----';
                    }

                    try{
                        // var_dump($arrayKQ);
                        $tempArr = array();
                        // $tempArr1 = array_splice($arrayKQ[16],1);
                        $tempArr2 = array_splice($arrayKQ[17],0);
                        $tempArr = array_merge($tempArr2);
                        // var_dump($tempArr);
                        $giai7 = implode(',',$tempArr) ;
                        foreach($tempArr as $item){
                            if (is_numeric($item)){
                                $countkq++;
                            }
                        }
                    }catch(\Exception $ex){
                        $giai7 = '-----';
                    }

                    if (count($kqxs) < 1){
                        if (($kqxs_yesterday[0]->DB != $giaidb)){
                        echo 'insert';
                        $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                    }
                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs = $kqxs->first();
                        echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                        if ($countkq > $kqxs->Giai_8 
                        || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                        || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                            if ($kqxs_yesterday[0]->DB != $giaidb){
                            echo 'update';
                            $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                            }
                        }
                    }

                    return 0;
                }
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                NotifyHelpers::SendTelegramNotification('error generateByxoso888 '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                return 2;
            }
        return 0;
    }

    public function generateByXosome(){
        // for($i=1;$i<=2;$i++)
            try{
                // usleep( 500000 );
                // sleep(1);   
                $now = date('Y-m-d');
                $kqxs = XoSoResult::where('location_id', 1)
                ->where('date', $now)->get();

                $datetime = new DateTime('yesterday');
                $yesterday = $datetime->format('Y-m-d');

                $kqxs_yesterday = XoSoResult::where('location_id', 1)
                ->where('date', $yesterday)->get();

                // // var_dump($kqxs);
                if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {

                // var_dump($kqxs);
                // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                    return 1;
                }else 
                {
                    // $kqxs = $kqxs->first();
                    // echo 'generateByXosome'.$i;
                    echo 'generateByXosome';
                    $curl = new Curl();
                    $linkminhngoc = 'https://xoso.mobi/xo-so-truc-tiep/xsmb-mien-bac.html';
                    $response = $curl->get($linkminhngoc);
                    // var_dump($response);
                    if (!isset($response))
                        return 2;
                    $domHtml = HtmlDomParser::str_get_html($response->body);

                    if (!isset($domHtml))
                        return 2;
                        
                    $mainBody = $domHtml->find("div[id=load_kq_mb_0]",0);
                    
                    if (!isset($mainBody))
                        return 2;
                    // var_dump($mainBody);

                    // $info = $mainBody->find("td.ngay",0);
                    // if (!isset($info))
                    //     return 2;
                        
                    // $date = $info->find("span.tngay",0)->innertext;

                    $specCharacter = '-';
                    try{
                        $specCharacter = $mainBody->getElementById("span.v-madb")->innertext;
                        $specCharacter = str_replace(" ","",$specCharacter);
                        echo $specCharacter;
                        if (strlen($specCharacter) >= 60 || strlen($specCharacter) <= 10) {
                            $specCharacter = '-';
                        }
                    }catch(\Exception $ex){
                        $specCharacter = '-';
                    }
                    
                    // // echo $specCharacter;

                    // if (!isset($date))
                    //     return 2;
                    // $date = str_replace('Ng&agrave;y: ','',$date);
                    // $date = str_replace('/', '-', $date);
                    // $date = strtotime($date);
                    $countkq = 0;
                    // $newformat = date('Y-m-d',$date);
                    // //echo $newformat ."</br>";
                    // $now = date('Y-m-d');
                    // if ($newformat != $now) return 0;

                    try{
                        $giaidbr = $mainBody->getElementById("span.v-gdb")->innertext;
                        $giaidb = '';
                        //echo 'giaidb ';

                        if (is_numeric($giaidbr)){
                            $giaidb = $giaidbr;
                            $countkq++;
                        }else
                            $giaidb = '-----';

                        //echo "</br>";
                    }catch(\Exception $ex){}
                    
                    try{
                        $giai1r = $mainBody->getElementById("span.v-g1")->innertext;
                        $giai1='';
                        //echo 'giai1 ';
                        if (is_numeric($giai1r)){
                            $giai1 = $giai1r;
                            $countkq++;
                        }else{
                            $giai1 .= '-----';
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai2='';

                        for($i=0;$i<2;$i++){
                            if (strlen($giai2) > 0) $giai2.=",";
                            $giai2r0 = $mainBody->getElementById("span.v-g2-".$i)->innertext;
                            if (is_numeric($giai2r0)){
                                $giai2 .= $giai2r0;
                                $countkq++;
                            }else{
                                $giai2 .= '-----';
                            }
                        }

                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai3='';

                        for($i=0;$i<6;$i++){
                            if (strlen($giai3) > 0) $giai3.=",";
                            $giai3r0 = $mainBody->getElementById("span.v-g3-".$i)->innertext;
                            if (is_numeric($giai3r0)){
                                $giai3 .= $giai3r0;
                                $countkq++;
                            }else{
                                $giai3 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai4='';

                        for($i=0;$i<4;$i++){
                            if (strlen($giai4) > 0) $giai4.=",";
                            $giai4r0 = $mainBody->getElementById("span.v-g4-".$i)->innertext;
                            if (is_numeric($giai4r0)){
                                $giai4 .= $giai4r0;
                                $countkq++;
                            }else{
                                $giai4 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai5='';

                        for($i=0;$i<6;$i++){
                            if (strlen($giai5) > 0) $giai5.=",";
                            $giai5r0 = $mainBody->getElementById("span.v-g5-".$i)->innertext;
                            if (is_numeric($giai5r0)){
                                $giai5 .= $giai5r0;
                                $countkq++;
                            }else{
                                $giai5 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    try{
                        $giai6='';

                        for($i=0;$i<3;$i++){
                            if (strlen($giai6) > 0) $giai6.=",";
                            $giai6r0 = $mainBody->getElementById("span.v-g6-".$i)->innertext;
                            if (is_numeric($giai6r0)){
                                $giai6 .= $giai6r0;
                                $countkq++;
                            }else{
                                $giai6 .= '-----';
                            }
                        }
                    }catch(\Exception $ex){}

                    try{
                        $giai7='';

                        for($i=0;$i<4;$i++){
                            if (strlen($giai7) > 0) $giai7.=",";
                            $giai7r0 = $mainBody->getElementById("span.v-g7-".$i)->innertext;
                            if (is_numeric($giai7r0)){
                                $giai7 .= $giai7r0;
                                $countkq++;
                            }else{
                                $giai7 .= '-----';
                            }
                        }
                        //echo "</br>";
                    }catch(\Exception $ex){}

                    // try{
                    //     $giaikhr = $mainBody->getElementByTagName("div.loai_ve")->children();
                    //     $giaikh = '';
                    //     //echo 'giaidb ';
                    //     foreach($giaikhr as $item){
                    //         // echo $item->innertext .",";
                    //         // if (strlen($giaikh) > 0) $giaikh.=",";

                    //         if (strpos($item->innertext, 'SC-') !== false) {
                    //             $giaikh = $item->innertext;
                    //             $countkq++;
                    //         }
                    //     }
                    //     // echo $giaikh;
                    // }catch(\Exception $ex){}

                    if (count($kqxs) < 1){
                        if (($kqxs_yesterday[0]->DB != $giaidb)){
                        echo 'insert';
                        $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                    }
                        // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                    }else{
                        $kqxs = $kqxs->first();
                        echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                        if ($countkq > $kqxs->Giai_8 
                        || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                        || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                            if ($kqxs_yesterday[0]->DB != $giaidb){
                            echo 'update';
                            $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now,$specCharacter);
                            }
                        }
                    }

                    return 0;
                }
            }catch(\Exception $ex){
                Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
                NotifyHelpers::SendTelegramNotification('error generateByXosome '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
                return 2;
            }
        return 0;
    }

    //live
    public function generateByKetquaveso(){
        $is_ok = false;
        // for($i=1;$i<=2;$i++)
        try{
            // usleep( 500000 );
            // sleep(1);
            // echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');

            $kqxs_yesterday = XoSoResult::where('location_id', 1)
            ->where('date', $yesterday)->get();

            // var_dump($kqxs);
            if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {

            // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                return 1;
            }else 
            {
                
                echo 'generateByKetquaveso';
                // $curl = new Curl();
                // https://s1.ketquaveso.mobi/ttkq/json_kqmb/
                // https://s2.ketquaveso.mobi/ttkq/json_kqmb/42ae0df5003a37a7940370b58070f4f6?t=1677489630037
                $linkminhngoc = 'https://s1.ketquaveso.mobi/ttkq/json_kqmb/42ae0df5003a37a7940370b58070f4f6?t='.time();
                // $response = $curl->get($linkminhngoc);

                $client = new Client();
                $res = $client->request('GET',$linkminhngoc);

                $kqraw = json_decode($res->getBody(), true);

                // print_r ($response->body);

                // $domHtml = HtmlDomParser::str_get_html($response->body);
                // $domHtml = $response->body;
                // $mainBody = $domHtml;
                // // $mainBody = '{"provinceCode":"MB","provinceName":"","rawData":"","tuong_thuat":false,"isRolling":1,"resultDate":1624362607878,"dau":{"0":["1","2","5","9"],"1":["7","8","8"],"2":["0"],"3":["0","2"],"4":["2","5","6","7"],"5":["2","8","8"],"6":["0","8"],"7":["3","9"],"8":["0","6"],"9":["6","6","8","9"]},"duoi":{"0":["2","3","6","8"],"1":["0"],"2":["0","3","4","5"],"3":["7"],"4":[],"5":["0","4"],"6":["4","8","9","9"],"7":["1","4"],"8":["1","1","5","5","6","9"],"9":["0","7","9"]},"lotData":{"1":["41158"],"2":["46686","84680"],"3":["65752","98202","01898","72132","77218","11699"],"4":["4601","7796","2920","3030"],"5":["6545","0718","3173","7947","7279","4242"],"6":["546","309","896"],"MaDb":["5EA","12EA","7EA","11EA","","10EA"],"7":["17","","58","60"],"DB":[""]},"loto":[]}';
                // // print_r ($mainBody);
                // // $mainBody = "1616239796;;;;;;;*-*-*;12345;";
                // $kqraw = json_decode($mainBody,true);

                // echo "get data";
                // print_r ($kqraw);
                // return;
                
                // $date = new DateTime();
                // $date->setTimestamp($kqraw[0]);
                // $newformat = $date->format('Y-m-d');

                // print_r ($kqraw['lotData']);

                $specCharacter = '-';
                try{
                    $specCharacter = '';
                    foreach($kqraw['lotData']['MaDb'] as $item){
                        if (strlen($specCharacter) > 0) $specCharacter.="-";
                        $specCharacter .= $item;
                    }
                    if (strlen($specCharacter) <= 1){
                        $specCharacter = '-';
                    }
                }catch(\Exception $ex){
                    $specCharacter = '-';
                }
                
                // echo "get data";

                $countkq = 0;
                
                // $now = date('Y-m-d');
                // print_r ($newformat);
                // print_r ($now);
                // if ($newformat != $now) return false;
                try{
                    // $giaidbr = $this->SplitStringToArray($kqraw[9],'-'); 
                    $giaidb = '';
                    
                    foreach($kqraw['lotData']['DB'] as $item){
                        if (strlen($giaidb) > 0) $giaidb.=",";
                        if (is_numeric($item)){
                            $giaidb .= $item;
                            $countkq++;
                        }else{
                            $giaidb .= '-----';
                        }
                    }
                    //echo "</br>";
                }catch(\Exception $ex){}
                
                try{
                    // $giai1r = $this->SplitStringToArray($kqraw[8],'-'); 
                    $giai1='';
                    
                    foreach($kqraw['lotData']['1'] as $item){
                        if (strlen($giai1) > 0) $giai1.=",";
                        if (is_numeric($item)){
                            $giai1 .= $item;
                            $countkq++;
                        }else{
                            $giai1 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai2='';

                    foreach($kqraw['lotData']['2'] as $item){
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item)){
                            $giai2 .= $item;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}
                    
                try{
                    $giai3='';
                    foreach($kqraw['lotData']['3'] as $item){
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item)){
                            $giai3 .= $item;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai4='';
                    foreach($kqraw['lotData']['4'] as $item){
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item)){
                            $giai4 .= $item;
                            $countkq++;
                        }else{
                            $giai4 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai5='';
                    foreach($kqraw['lotData']['5'] as $item){
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item)){
                            $giai5 .= $item;
                            $countkq++;
                        }else{
                            $giai5 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai6='';
                    foreach($kqraw['lotData']['6'] as $item){
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item)){
                            $giai6 .= $item;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai7='';
                    foreach($kqraw['lotData']['7'] as $item){
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item)){
                            $giai7 .= $item;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                }catch(\Exception $ex){}

                // try{
                //     $giaikhr = $this->SplitStringToArray($kqraw[1],'-'); 
                //     $giaikh=$giaikhr;

                //     if ($giaikhr != 'SC SC SC'){
                //         $giaikh = $giaikhr;
                //         $countkq++;
                //     }
                // }catch(\Exception $ex){}
                    
                if (count($kqxs) < 1){
                    if (($kqxs_yesterday[0]->DB != $giaidb)){
                    echo 'insert';
                    $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                }
                    // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                }else{
                    $kqxs = $kqxs->first();
                    echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                    if ($countkq > $kqxs->Giai_8 
                    || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                    || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                        if ($kqxs_yesterday[0]->DB != $giaidb){
                        echo 'update';
                        $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now,$specCharacter);
                        }
                    }
                }

                return 0;
            }
        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            NotifyHelpers::SendTelegramNotification('error generateByKequaveso '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            // echo 'stop';
            // $is_ok = false;
            return 2;
            // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
        }

        // if ($is_ok==false){
        //     $now = date('Y-m-d');
        //     $kqxs = XoSoResult::where('location_id', 1)
        //     ->where('date', $now)->get();
        //     if (count($kqxs)>0){
        //         $kqxs = $kqxs->first();
        //         DB::table('xoso_result')
        //         ->where('id', $kqxs->id)
        //         ->update([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }else{
        //         DB::table('xoso_result')->insert([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }
        // }
        return 0;
    }

    //slow
    public function generateByLotusAPI(){
        $is_ok = false;
        // for($i=1;$i<=2;$i++)
        try{
            // usleep( 500000 );
            // sleep(1);
            // echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');

            $kqxs_yesterday = XoSoResult::where('location_id', 1)
            ->where('date', $yesterday)->get();

            // var_dump($kqxs);
            // if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {

            // // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
            //     return 1;
            // }else 
            {
                
                echo 'generateByLotusAPI';
                $curl = new Curl();
                $linkminhngoc = 'https://lotto.lotusapi.com/lottery-results/public?date='.$now;

                $client = new Client();
                $res = $client->request('GET',$linkminhngoc);

                $jsonData = json_decode($res->getBody(), true);
                $kqraw = [];
                // var_dump ($jsonData);
                foreach($jsonData as $data){
                    if (isset($data['Type']) && $data['Type'] == 0){
                        $kqraw = $data;
                        break;
                    }
                }

                $specCharacter = '-';
                // try{
                //     $specCharacter = '';
                //     foreach($kqraw['lotData']['MaDb'] as $item){
                //         if (strlen($specCharacter) > 0) $specCharacter.="-";
                //         $specCharacter .= $item;
                //     }
                //     if (strlen($specCharacter) <= 1){
                //         $specCharacter = '-';
                //     }
                // }catch(\Exception $ex){
                //     $specCharacter = '-';
                // }
                
                // echo "get data";

                $countkq = 0;
                
                // $now = date('Y-m-d');
                // print_r ($newformat);
                // print_r ($now);
                // if ($newformat != $now) return false;
                try{
                    $giaidb = $kqraw['Jackpot'];
                    if (is_numeric($giaidb)){
                        $countkq++;
                    }
                }catch(\Exception $ex){}
                
                try{
                    $giai1=$kqraw['First'];
                    if (is_numeric($giai1)){
                        $countkq++;
                    }
                }catch(\Exception $ex){}

                try{
                    $giai2='';

                    foreach($kqraw['Second'] as $item){
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item)){
                            $giai2 .= $item;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}
                    
                try{
                    $giai3='';
                    foreach($kqraw['Third'] as $item){
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item)){
                            $giai3 .= $item;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai4='';
                    foreach($kqraw['Fourth'] as $item){
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item)){
                            $giai4 .= $item;
                            $countkq++;
                        }else{
                            $giai4 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai5='';
                    foreach($kqraw['Fiveth'] as $item){
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item)){
                            $giai5 .= $item;
                            $countkq++;
                        }else{
                            $giai5 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai6='';
                    foreach($kqraw['Sixth'] as $item){
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item)){
                            $giai6 .= $item;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai7='';
                    foreach($kqraw['Seventh'] as $item){
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item)){
                            $giai7 .= $item;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                }catch(\Exception $ex){}

                if (count($kqxs) < 1){
                    if (($kqxs_yesterday[0]->DB != $giaidb)){
                    echo 'insert';
                    $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                }
                    // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                }else{
                    $kqxs = $kqxs->first();
                    echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                    if ($countkq > $kqxs->Giai_8 
                    || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                    || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                        if ($kqxs_yesterday[0]->DB != $giaidb){
                        echo 'update';
                        $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                        }
                    }
                }

                return 0;
            }
        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            NotifyHelpers::SendTelegramNotification('error generateByLotusAPI '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            // echo 'stop';
            // $is_ok = false;
            return 2;
            // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
        }

        // if ($is_ok==false){
        //     $now = date('Y-m-d');
        //     $kqxs = XoSoResult::where('location_id', 1)
        //     ->where('date', $now)->get();
        //     if (count($kqxs)>0){
        //         $kqxs = $kqxs->first();
        //         DB::table('xoso_result')
        //         ->where('id', $kqxs->id)
        //         ->update([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }else{
        //         DB::table('xoso_result')->insert([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }
        // }
        return 0;
    }

    public function generateBykqxsvnAPI(){
        $is_ok = false;
        // for($i=1;$i<=2;$i++)
        try{
            // usleep( 500000 );
            // sleep(1);
            // echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');

            $kqxs_yesterday = XoSoResult::where('location_id', 1)
            ->where('date', $yesterday)->get();

            // var_dump($kqxs);
            if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {

            // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                return 1;
            }else 
            {
                
                echo 'generateBykqxsvnAPI';
                // $curl = new Curl();
                // $timestamp = time();
                // $linkminhngoc = 'https://www.kqxs.vn/realtime/mien-bac.html?t='.$timestamp;
                // $client = new Client();
                // $res = $client->request('GET',$linkminhngoc);
                // var_dump($res->getBody());
                // $jsonData = json_decode($res->getBody(), true);
                // $kqraw = [];
                // var_dump ($jsonData);
                // return;

                $curl = new Curl();
                $timestamp = time();
                $linkminhngoc = 'https://www.kqxs.vn/realtime/mien-bac.html?t='.$timestamp;
                $response = $curl->get($linkminhngoc);

                // print_r ($response->body);

                $domHtml = HtmlDomParser::str_get_html($response->body);
                $domHtml = $response->body;
                $mainBody = $domHtml;
                // $mainBody = '{"provinceCode":"MB","provinceName":"","rawData":"","tuong_thuat":false,"isRolling":1,"resultDate":1624362607878,"dau":{"0":["1","2","5","9"],"1":["7","8","8"],"2":["0"],"3":["0","2"],"4":["2","5","6","7"],"5":["2","8","8"],"6":["0","8"],"7":["3","9"],"8":["0","6"],"9":["6","6","8","9"]},"duoi":{"0":["2","3","6","8"],"1":["0"],"2":["0","3","4","5"],"3":["7"],"4":[],"5":["0","4"],"6":["4","8","9","9"],"7":["1","4"],"8":["1","1","5","5","6","9"],"9":["0","7","9"]},"lotData":{"1":["41158"],"2":["46686","84680"],"3":["65752","98202","01898","72132","77218","11699"],"4":["4601","7796","2920","3030"],"5":["6545","0718","3173","7947","7279","4242"],"6":["546","309","896"],"MaDb":["5EA","12EA","7EA","11EA","","10EA"],"7":["17","","58","60"],"DB":[""]},"loto":[]}';
                // print_r ($mainBody);
                // $mainBody = "1616239796;;;;;;;*-*-*;12345;";
                // $mainBody = '{
                //     "numbers": {
                //            "1": {
                //                "2": [
                //                    "39977"
                //                ],
                //                "3": [
                //                    "20715",
                //                    "42892"
                //                ],
                //                "4": [
                //                    "88546",
                //                    "49558",
                //                    "01604"
                //                ]
                //            }
                //        }
                //     }';
                $jsonData = json_decode($mainBody,true);
                if (!isset($jsonData['numbers'][1])) return;
                $kqraw = $jsonData['numbers'][1];
                // var_dump($jsonData);
                // return;
                // foreach($jsonData as $data){
                //     if (isset($data['Type']) && $data['Type'] == 0){
                //         $kqraw = $data;
                //         break;
                //     }
                // }

                $specCharacter = '-';
                // try{
                //     $specCharacter = '';
                //     foreach($kqraw['lotData']['MaDb'] as $item){
                //         if (strlen($specCharacter) > 0) $specCharacter.="-";
                //         $specCharacter .= $item;
                //     }
                //     if (strlen($specCharacter) <= 1){
                //         $specCharacter = '-';
                //     }
                // }catch(\Exception $ex){
                //     $specCharacter = '-';
                // }
                
                // echo "get data";

                $countkq = 0;
                // $now = date('Y-m-d');
                // print_r ($newformat);
                // print_r ($now);
                // if ($newformat != $now) return false;
                try{
                    $giaidb = "";
                    foreach($kqraw['1'] as $item){
                        if (strlen($giaidb) > 0) $giaidb.=",";
                        if (is_numeric($item)){
                            $giaidb .= $item;
                            $countkq++;
                        }else{
                            $giaidb .= '-----';
                        }
                    }
                }catch(\Exception $ex){}
                
                try{
                    $giai1= "";
                    foreach($kqraw['2'] as $item){
                        if (strlen($giai1) > 0) $giai1.=",";
                        if (is_numeric($item)){
                            $giai1 .= $item;
                            $countkq++;
                        }else{
                            $giai1 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai2='';

                    foreach($kqraw['3'] as $item){
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item)){
                            $giai2 .= $item;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}
                    
                try{
                    $giai3='';
                    foreach($kqraw['4'] as $item){
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item)){
                            $giai3 .= $item;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai4='';
                    foreach($kqraw['5'] as $item){
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item)){
                            $giai4 .= $item;
                            $countkq++;
                        }else{
                            $giai4 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai5='';
                    foreach($kqraw['6'] as $item){
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item)){
                            $giai5 .= $item;
                            $countkq++;
                        }else{
                            $giai5 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai6='';
                    foreach($kqraw['7'] as $item){
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item)){
                            $giai6 .= $item;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai7='';
                    foreach($kqraw['8'] as $item){
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item)){
                            $giai7 .= $item;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                }catch(\Exception $ex){}

                if (count($kqxs) < 1){
                    if (($kqxs_yesterday[0]->DB != $giaidb)){
                    echo 'insert';
                    $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                }
                    // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                }else{
                    $kqxs = $kqxs->first();
                    echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                    if ($countkq > $kqxs->Giai_8 
                    || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                    || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                        if ($kqxs_yesterday[0]->DB != $giaidb){
                        echo 'update';
                        $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                        }
                    }
                }

                return 0;
            }
        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            NotifyHelpers::SendTelegramNotification('error generateBykqxsvnAPI '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            // echo 'stop';
            // $is_ok = false;
            return 2;
            // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
        }

        // if ($is_ok==false){
        //     $now = date('Y-m-d');
        //     $kqxs = XoSoResult::where('location_id', 1)
        //     ->where('date', $now)->get();
        //     if (count($kqxs)>0){
        //         $kqxs = $kqxs->first();
        //         DB::table('xoso_result')
        //         ->where('id', $kqxs->id)
        //         ->update([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }else{
        //         DB::table('xoso_result')->insert([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }
        // }
        return 0;
    }

    public function generateBy99luckeyAPI(){
        $is_ok = false;
        // for($i=1;$i<=2;$i++)
        try{
            // usleep( 500000 );
            // sleep(1);
            // echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');

            $kqxs_yesterday = XoSoResult::where('location_id', 1)
            ->where('date', $yesterday)->get();

            // var_dump($kqxs);
            if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {

            // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                return 1;
            }else 
            {
                
                echo 'generateBy99luckeyAPI';
                // $curl = new Curl();
                // $timestamp = time();
                // $linkminhngoc = 'https://www.kqxs.vn/realtime/mien-bac.html?t='.$timestamp;
                // $client = new Client();
                // $res = $client->request('GET',$linkminhngoc);
                // var_dump($res->getBody());
                // $jsonData = json_decode($res->getBody(), true);
                // $kqraw = [];
                // var_dump ($jsonData);
                // return;

                // $curl = new Curl();
                $timestamp = time();
                $linkminhngoc = 'https://99luckey.com/api/kqmb';

                $client = new Client();
                $res = $client->request('GET',$linkminhngoc);

                $kqraw = json_decode($res->getBody(), true);
                // $response = $curl->get($linkminhngoc);

                // print_r ($response->body);

                // $domHtml = HtmlDomParser::str_get_html($response->body);
                // $domHtml = $response->body;
                // $mainBody = $domHtml;
                // $mainBody = '{"code":200,"message":"","data":{"date":"28-02-2023","location":"Mi\u1ec1n B\u1eafc","locationid":"1","DB":"93758","1":"96434","2":["10620","41971"],"3":["97839","24382","48220","49467","28419","70861"],"4":["7454","7809","8678","2897"],"5":["0499","1466","2069","6655","0134","2993"],"6":["915","894","598"],"7":["24","00","65","16"],"8":"0","spec_character":null,"than_tai":""}}';
                // $mainBody = '{"provinceCode":"MB","provinceName":"","rawData":"","tuong_thuat":false,"isRolling":1,"resultDate":1624362607878,"dau":{"0":["1","2","5","9"],"1":["7","8","8"],"2":["0"],"3":["0","2"],"4":["2","5","6","7"],"5":["2","8","8"],"6":["0","8"],"7":["3","9"],"8":["0","6"],"9":["6","6","8","9"]},"duoi":{"0":["2","3","6","8"],"1":["0"],"2":["0","3","4","5"],"3":["7"],"4":[],"5":["0","4"],"6":["4","8","9","9"],"7":["1","4"],"8":["1","1","5","5","6","9"],"9":["0","7","9"]},"lotData":{"1":["41158"],"2":["46686","84680"],"3":["65752","98202","01898","72132","77218","11699"],"4":["4601","7796","2920","3030"],"5":["6545","0718","3173","7947","7279","4242"],"6":["546","309","896"],"MaDb":["5EA","12EA","7EA","11EA","","10EA"],"7":["17","","58","60"],"DB":[""]},"loto":[]}';
                // print_r ($mainBody);
                // $mainBody = "1616239796;;;;;;;*-*-*;12345;";
                // $jsonData = json_decode($mainBody,true);
                $kqraw = $kqraw['data'];
                // var_dump($kqraw);
                // return;
                // return;
                // return;
                // foreach($jsonData as $data){
                //     if (isset($data['Type']) && $data['Type'] == 0){
                //         $kqraw = $data;
                //         break;
                //     }
                // }

                $specCharacter = isset($kqraw['spec_character']) ? $kqraw['spec_character'] : '-';
                // try{
                //     $specCharacter = '';
                //     foreach($kqraw['lotData']['MaDb'] as $item){
                //         if (strlen($specCharacter) > 0) $specCharacter.="-";
                //         $specCharacter .= $item;
                //     }
                //     if (strlen($specCharacter) <= 1){
                //         $specCharacter = '-';
                //     }
                // }catch(\Exception $ex){
                //     $specCharacter = '-';
                // }
                
                // echo "get data";

                $countkq = 0;
                // $now = date('Y-m-d');
                // print_r ($newformat);
                // print_r ($now);
                // if ($newformat != $now) return false;
                try{
                    $giaidb = $kqraw['DB'];
                    if (is_numeric($giaidb)){
                        $countkq++;
                    }
                    // echo $giaidb;
                    
                }catch(\Exception $ex){}
                
                try{
                    $giai1 = $kqraw['1'];
                    if (is_numeric($giai1)){
                        $countkq++;
                    }
                }catch(\Exception $ex){}

                try{
                    $giai2='';

                    foreach($kqraw['2'] as $item){
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item)){
                            $giai2 .= $item;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}
                    
                try{
                    $giai3='';
                    foreach($kqraw['3'] as $item){
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item)){
                            $giai3 .= $item;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai4='';
                    foreach($kqraw['4'] as $item){
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item)){
                            $giai4 .= $item;
                            $countkq++;
                        }else{
                            $giai4 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai5='';
                    foreach($kqraw['5'] as $item){
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item)){
                            $giai5 .= $item;
                            $countkq++;
                        }else{
                            $giai5 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai6='';
                    foreach($kqraw['6'] as $item){
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item)){
                            $giai6 .= $item;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai7='';
                    foreach($kqraw['7'] as $item){
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item)){
                            $giai7 .= $item;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                }catch(\Exception $ex){}

                if (count($kqxs) < 1){
                    if (($kqxs_yesterday[0]->DB != $giaidb)){
                    echo 'insert';
                    $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                }
                    // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                }else{
                    $kqxs = $kqxs->first();
                    echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                    if ($countkq > $kqxs->Giai_8 
                    || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                    || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                        if ($kqxs_yesterday[0]->DB != $giaidb){
                        echo 'update';
                        $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now,$specCharacter);
                        }
                    }
                }

                return 0;
            }
        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            NotifyHelpers::SendTelegramNotification('error generateBy99luckeyAPI '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            // echo 'stop';
            // $is_ok = false;
            return 2;
            // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
        }

        // if ($is_ok==false){
        //     $now = date('Y-m-d');
        //     $kqxs = XoSoResult::where('location_id', 1)
        //     ->where('date', $now)->get();
        //     if (count($kqxs)>0){
        //         $kqxs = $kqxs->first();
        //         DB::table('xoso_result')
        //         ->where('id', $kqxs->id)
        //         ->update([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }else{
        //         DB::table('xoso_result')->insert([
        //             'location_id' =>  1,
        //             'Giai_8' => 28,
        //             'date' => $now,
        //         ]);
        //         // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
        //     }
        // }
        return 0;
    }

    public function generateByNineVegas(){
        $is_ok = false;
        // for($i=1;$i<=2;$i++)
        try{
            // usleep( 500000 );
            // sleep(1);
            // echo 'generateByKqNet'.$i;
            $is_ok = true;
            $now = date('Y-m-d');
            $kqxs = XoSoResult::where('location_id', 1)
            ->where('date', $now)->get();
            
            $datetime = new DateTime('yesterday');
            $yesterday = $datetime->format('Y-m-d');

            $kqxs_yesterday = XoSoResult::where('location_id', 1)
            ->where('date', $yesterday)->get();

            // var_dump($kqxs);
            if (count($kqxs) > 0 && $this->fullKq($kqxs) && (isset($kqxs[0]->DB) && $kqxs_yesterday[0]->DB != $kqxs[0]->DB ) ) {

            // if (count($kqxs) > 0 && $this->fullKq($kqxs)){
                return 1;
            }else 
            {
                
                echo 'generateByNineVegas';
                // $curl = new Curl();
                // $timestamp = time();
                // $linkminhngoc = 'https://www.kqxs.vn/realtime/mien-bac.html?t='.$timestamp;
                // $client = new Client();
                // $res = $client->request('GET',$linkminhngoc);
                // var_dump($res->getBody());
                // $jsonData = json_decode($res->getBody(), true);
                // $kqraw = [];
                // var_dump ($jsonData);
                // return;

                // $curl = new Curl();
                $timestamp = time();
                $linkminhngoc = 'http://s1.ninevegas.com/api/kqmb';

                $client = new Client();
                $res = $client->request('GET',$linkminhngoc);

                $kqraw = json_decode($res->getBody(), true);
                // $response = $curl->get($linkminhngoc);

                // print_r ($response->body);

                // $domHtml = HtmlDomParser::str_get_html($response->body);
                // $domHtml = $response->body;
                // $mainBody = $domHtml;
                // $mainBody = '{"code":200,"message":"","data":{"date":"28-02-2023","location":"Mi\u1ec1n B\u1eafc","locationid":"1","DB":"93758","1":"96434","2":["10620","41971"],"3":["97839","24382","48220","49467","28419","70861"],"4":["7454","7809","8678","2897"],"5":["0499","1466","2069","6655","0134","2993"],"6":["915","894","598"],"7":["24","00","65","16"],"8":"0","spec_character":null,"than_tai":""}}';
                // $mainBody = '{"provinceCode":"MB","provinceName":"","rawData":"","tuong_thuat":false,"isRolling":1,"resultDate":1624362607878,"dau":{"0":["1","2","5","9"],"1":["7","8","8"],"2":["0"],"3":["0","2"],"4":["2","5","6","7"],"5":["2","8","8"],"6":["0","8"],"7":["3","9"],"8":["0","6"],"9":["6","6","8","9"]},"duoi":{"0":["2","3","6","8"],"1":["0"],"2":["0","3","4","5"],"3":["7"],"4":[],"5":["0","4"],"6":["4","8","9","9"],"7":["1","4"],"8":["1","1","5","5","6","9"],"9":["0","7","9"]},"lotData":{"1":["41158"],"2":["46686","84680"],"3":["65752","98202","01898","72132","77218","11699"],"4":["4601","7796","2920","3030"],"5":["6545","0718","3173","7947","7279","4242"],"6":["546","309","896"],"MaDb":["5EA","12EA","7EA","11EA","","10EA"],"7":["17","","58","60"],"DB":[""]},"loto":[]}';
                // print_r ($mainBody);
                // $mainBody = "1616239796;;;;;;;*-*-*;12345;";
                // $jsonData = json_decode($mainBody,true);
                $kqraw = $kqraw['data'];
                // var_dump($kqraw);
                // return;
                // return;
                // return;
                // foreach($jsonData as $data){
                //     if (isset($data['Type']) && $data['Type'] == 0){
                //         $kqraw = $data;
                //         break;
                //     }
                // }

                $specCharacter = isset($kqraw['spec_character']) ? $kqraw['spec_character'] : '-';
                // try{
                //     $specCharacter = '';
                //     foreach($kqraw['lotData']['MaDb'] as $item){
                //         if (strlen($specCharacter) > 0) $specCharacter.="-";
                //         $specCharacter .= $item;
                //     }
                //     if (strlen($specCharacter) <= 1){
                //         $specCharacter = '-';
                //     }
                // }catch(\Exception $ex){
                //     $specCharacter = '-';
                // }
                
                // echo "get data";

                $countkq = 0;
                // $now = date('Y-m-d');
                // print_r ($newformat);
                // print_r ($now);
                // if ($newformat != $now) return false;
                $giaidb = "";
                $giai1 = "";
                $giai2 = "";
                $giai3 = "";
                $giai4 = "";
                $giai5 = "";
                $giai6 = "";
                $giai7 = "";
                try{
                    $giaidb = $kqraw['DB'];
                    if (is_numeric($giaidb)){
                        $countkq++;
                    }
                    // echo $giaidb;
                    
                }catch(\Exception $ex){}
                
                try{
                    $giai1 = $kqraw['1'];
                    if (is_numeric($giai1)){
                        $countkq++;
                    }
                }catch(\Exception $ex){}

                try{
                    $giai2='';

                    foreach($kqraw['2'] as $item){
                        if (strlen($giai2) > 0) $giai2.=",";
                        if (is_numeric($item)){
                            $giai2 .= $item;
                            $countkq++;
                        }else{
                            $giai2 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}
                    
                try{
                    $giai3='';
                    foreach($kqraw['3'] as $item){
                        if (strlen($giai3) > 0) $giai3.=",";
                        if (is_numeric($item)){
                            $giai3 .= $item;
                            $countkq++;
                        }else{
                            $giai3 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai4='';
                    foreach($kqraw['4'] as $item){
                        if (strlen($giai4) > 0) $giai4.=",";
                        if (is_numeric($item)){
                            $giai4 .= $item;
                            $countkq++;
                        }else{
                            $giai4 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai5='';
                    foreach($kqraw['5'] as $item){
                        if (strlen($giai5) > 0) $giai5.=",";
                        if (is_numeric($item)){
                            $giai5 .= $item;
                            $countkq++;
                        }else{
                            $giai5 .= '-----';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai6='';
                    foreach($kqraw['6'] as $item){
                        if (strlen($giai6) > 0) $giai6.=",";
                        if (is_numeric($item)){
                            $giai6 .= $item;
                            $countkq++;
                        }else{
                            $giai6 .= '---';
                        }
                    }
                }catch(\Exception $ex){}

                try{
                    $giai7='';
                    foreach($kqraw['7'] as $item){
                        if (strlen($giai7) > 0) $giai7.=",";
                        if (is_numeric($item)){
                            $giai7 .= $item;
                            $countkq++;
                        }else{
                            $giai7 .= '--';
                        }
                    }
                }catch(\Exception $ex){}

                if (count($kqxs) < 1){
                    if (($kqxs_yesterday[0]->DB != $giaidb)){
                    echo 'insert';
                    $this->insertKQ($giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now);
                }
                    // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
                }else{
                    $kqxs = $kqxs->first();
                    
                    echo "sss " . $kqxs_yesterday[0]->DB . " " . $kqxs->DB . " " . $giaidb;

                    if ($countkq > $kqxs->Giai_8 
                    || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) 
                    || ($kqxs_yesterday[0]->DB == $kqxs->DB) ) {
                        if ($kqxs_yesterday[0]->DB != $giaidb){
                        echo 'update';
                        $this->updateKQ($kqxs->id,$giaidb,$giai1,$giai2,$giai3,$giai4,$giai5,$giai6,$giai7,$countkq,$now,$specCharacter);
                        }
                    }
                }

                return 0;
            }
        }catch(\Exception $ex){
            Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
            NotifyHelpers::SendTelegramNotification('error generateBy99luckeyAPI '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
            // echo 'stop';
            // $is_ok = false;
            return 2;
        }
        return 0;
    }

    // public function generateByKqNet(){
    //     $is_ok = false;
    //     for($i=1;$i<=2;$i++)
    //     try{
    //         sleep(1);
    //         echo 'generateByKqNet'.$i;
    //         $is_ok = true;
    //         $now = date('Y-m-d');
    //         $kqxs = XoSoResult::where('location_id', 1)
    //         ->where('date', $now)->get();
            
    //         if (count($kqxs) > 0 && $this->fullKq($kqxs)){
    //             return 1;
    //         }else {
                
    //             $curl = new Curl();
    //             $linkminhngoc = 'http://ketqua.net/kq-mb.raw';
    //             $response = $curl->get($linkminhngoc);

    //             // print_r ($response->body);

    //             $domHtml = HtmlDomParser::str_get_html($response->body);
    //             // $domHtml = $response->body;
    //             $mainBody = $domHtml;
    //             // $mainBody = '1616325521;;;;;3640-*;65114-04662-27967-17866-80267-40765;93839-56403;32393;';
    //             // print_r ($mainBody);
    //             // $mainBody = "1616239796;;;;;;;*-*-*;12345;";
    //             $kqraw = $this->SplitStringToArray($mainBody,';');
    //             // echo "get data";
    //             // print_r ($kqraw);
                
    //             $date = new DateTime();
    //             $date->setTimestamp($kqraw[0]);
    //             $newformat = $date->format('Y-m-d');

    //             $specCharacter = '-';
    //             try{
    //                 $specCharacter = $kqraw[1];
    //                 if (strlen($specCharacter) <= 1){
    //                     $specCharacter = '-';
    //                 }
    //             }catch(\Exception $ex){
    //                 $specCharacter = '-';
    //             }
                

    //             $countkq = 0;
                
    //             $now = date('Y-m-d');
    //             // print_r ($newformat);
    //             // print_r ($now);
    //             if ($newformat != $now) return false;
    //             try{
    //                 // $giaidbr = $this->SplitStringToArray($kqraw[9],'-'); 
    //                 $giaidb = '';
                    
    //                 if (is_numeric($kqraw[9])){
    //                     $giaidb .= $kqraw[9];
    //                     $countkq++;
    //                 }else{
    //                     $giaidb .= '-----';
    //                 }
                    
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}
                
    //             try{
    //                 // $giai1r = $this->SplitStringToArray($kqraw[8],'-'); 
    //                 $giai1='';
    //                 //echo 'giai1 ';
    //                 if (is_numeric($kqraw[8])){
    //                     $giai1 .= $kqraw[8];
    //                     $countkq++;
    //                 }else{
    //                     $giai1 .= '-----';
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}

    //             try{
    //                 $giai2r = $this->SplitStringToArray($kqraw[7],'-'); 
    //                 $giai2='';
    //                 //echo 'giai2 ';
    //                 foreach($giai2r as $item){
    //                     //echo $item->innertext .",";
    //                     if (strlen($giai2) > 0) $giai2.=",";
    //                     if (is_numeric($item)){
    //                         $giai2 .= $item;
    //                         $countkq++;
    //                     }else{
    //                         $giai2 .= '-----';
    //                     }
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}
                    
    //             try{
    //                 $giai3r = $this->SplitStringToArray($kqraw[6],'-'); 
    //                 $giai3='';
    //                 //echo 'giai3 ';
    //                 foreach($giai3r as $item){
    //                     //echo $item->innertext .",";
    //                     if (strlen($giai3) > 0) $giai3.=",";
    //                     if (is_numeric($item)){
    //                         $giai3 .= $item;
    //                         $countkq++;
    //                     }else{
    //                         $giai3 .= '-----';
    //                     }
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}

    //             try{
    //                 $giai4r = $this->SplitStringToArray($kqraw[5],'-'); 
    //                 $giai4='';
    //                 //echo 'giai4 ';
    //                 foreach($giai4r as $item){
    //                     //echo $item->innertext .",";
    //                     if (strlen($giai4) > 0) $giai4.=",";
    //                     if (is_numeric($item)){
    //                         $giai4 .= $item;
    //                         $countkq++;
    //                     }else{
    //                         $giai4 .= '----';
    //                     }
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}

    //             try{
    //                 $giai5r = $this->SplitStringToArray($kqraw[4],'-'); 
    //                 $giai5='';
    //                 //echo 'giai5 ';
    //                 foreach($giai5r as $item){
    //                     //echo $item->innertext .",";
    //                     if (strlen($giai5) > 0) $giai5.=",";
    //                     if (is_numeric($item)){
    //                         $giai5 .= $item;
    //                         $countkq++;
    //                     }else{
    //                         $giai5 .= '----';
    //                     }
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}

    //             try{
    //                 $giai6r = $this->SplitStringToArray($kqraw[3],'-'); 
    //                 $giai6='';
    //                 // echo 'giai6 ';
    //                 foreach($giai6r as $item){
    //                     //echo $item->innertext .",";
    //                     if (strlen($giai6) > 0) $giai6.=",";
    //                     if (is_numeric($item)){
    //                         $giai6 .= $item;
    //                         $countkq++;
    //                     }else{
    //                         $giai6 .= '---';
    //                     }
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}

    //             try{
    //                 $giai7r = $this->SplitStringToArray($kqraw[2],'-'); 
    //                 $giai7='';
    //                 //echo 'giai7 ';
    //                 foreach($giai7r as $item){
    //                     //echo $item->innertext .",";
    //                     if (strlen($giai7) > 0) $giai7.=",";
    //                     if (is_numeric($item)){
    //                         $giai7 .= $item;
    //                         $countkq++;
    //                     }else{
    //                         $giai7 .= '--';
    //                     }
    //                 }
    //                 //echo "</br>";
    //             }catch(\Exception $ex){}

    //             // try{
    //             //     $giaikhr = $this->SplitStringToArray($kqraw[1],'-'); 
    //             //     $giaikh=$giaikhr;

    //             //     if ($giaikhr != 'SC SC SC'){
    //             //         $giaikh = $giaikhr;
    //             //         $countkq++;
    //             //     }
    //             // }catch(\Exception $ex){}
                    
    //             if (count($kqxs) < 1){
    //                 echo 'insert';
    //                 DB::table('xoso_result')->insert([
    //                     'location_id' =>  1,
    //                     'DB' => $giaidb,
    //                     'Giai_1' => $giai1,
    //                     'Giai_2' => $giai2,
    //                     'Giai_3' => $giai3,
    //                     'Giai_4' => $giai4,
    //                     'Giai_5' => $giai5,
    //                     'Giai_6' => $giai6,
    //                     'Giai_7' => $giai7,
    //                     'Giai_8' => $countkq,
    //                     'spec_character' => $specCharacter,
    //                     'date' => $now,
    //                 ]);
    //                 // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
    //             }else{
    //                 $kqxs = $kqxs->first();
    //                 if ($countkq > $kqxs->Giai_8 || ( $countkq==26 && strlen($specCharacter) > strlen($kqxs->spec_character) ) ){
    //                     echo 'update';
    //                     DB::table('xoso_result')
    //                     ->where('id', $kqxs->id)
    //                     ->update([
    //                         'location_id' =>  1,
    //                         'DB' => $giaidb,
    //                         'Giai_1' => $giai1,
    //                         'Giai_2' => $giai2,
    //                         'Giai_3' => $giai3,
    //                         'Giai_4' => $giai4,
    //                         'Giai_5' => $giai5,
    //                         'Giai_6' => $giai6,
    //                         'Giai_7' => $giai7,
    //                         'Giai_8' => $countkq,
    //                         'spec_character' => $specCharacter,
    //                         'date' => $now,
    //                     ]);
    //                     // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
    //                     // NotifyHelpers::SendMailNotification('Cap nhat kq '.$countkq);
    //                 }
    //             }

    //             return 0;
    //         }
    //     }catch(\Exception $ex){
    //         Log::error('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine()); 
    //         echo 'stop';
    //         $is_ok = false;
    //         // NotifyHelpers::SendMailNotification('error '.$ex->getFile().'-'.$ex->getMessage().'-'.$ex->getLine());
    //     }
    //     if ($is_ok==false){
    //         $now = date('Y-m-d');
    //         $kqxs = XoSoResult::where('location_id', 1)
    //         ->where('date', $now)->get();
    //         if (count($kqxs)>0){
    //             $kqxs = $kqxs->first();
    //             DB::table('xoso_result')
    //             ->where('id', $kqxs->id)
    //             ->update([
    //                 'location_id' =>  1,
    //                 'Giai_8' => 28,
    //                 'date' => $now,
    //             ]);
    //             // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
    //         }else{
    //             DB::table('xoso_result')->insert([
    //                 'location_id' =>  1,
    //                 'Giai_8' => 28,
    //                 'date' => $now,
    //             ]);
    //             // Cache::tags('kqxs')->forget('kqxs-1-'.date('Y-m-d'));
    //         }
    //     }
    //     return 2;
    // }

    public static function reCalculatorNumber($game_code,$bet_number){
        // danh cho tk lockprice=0 len gia bthg + 789
        $TotalBetTodayByGame = Cache::get('TotalBetTodayByGameThau-'.$game_code, 0);
        $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0]);
        // echo $TotalBetTodayByGame . " " . $TotalBetTodayByNumber;
        // var_dump($TotalBetTodayByGame);
        // var_dump($TotalBetTodayByNumber);
        $game = GameHelpers::GetGameByGameCode($game_code);
        Queue::pushOn("high",new UpdateBetPriceAllUser_v2($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,0,0));
    }

    public static function reCalculatorNumberSuper($game_code,$bet_number){

        $users = UserHelpers::GetAllUserChildv2Admin("luk79", 274, 2);
        foreach ($users as $userSuper) {
            if ($userSuper->lock_price == 0){
                echo 'process super' . $userSuper->id . PHP_EOL;
                //get gamecode by super
                //using dynamic class
                $gameTableId = 'App\Game_'.$userSuper->id;
                // $ref = new ReflectionClass($gameTableId);
                $ref = new $gameTableId;
                $gameSuper = $ref::where('game_code',$game_code)->first();
    
                $TotalBetTodayByGameSuper = Cache::get('TotalBetTodayByGameThau-'.$game_code.'-'.$userSuper->id, 0);
                $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number.'-'.$userSuper->id, [0,0]);
                Queue::pushOn("high",new UpdateBetPriceAllUser_v4($gameSuper,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGameSuper,0,0,$userSuper));
            }
        }
        
        // // danh cho tk lockprice=0 len gia bthg + 789
        // $TotalBetTodayByGame = Cache::get('TotalBetTodayByGameThau-'.$game_code, 0);
        // $TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number, [0,0]);
        // // echo $TotalBetTodayByGame . " " . $TotalBetTodayByNumber;
        // // var_dump($TotalBetTodayByGame);
        // // var_dump($TotalBetTodayByNumber);
        // $game = GameHelpers::GetGameByGameCode($game_code);
        // Queue::pushOn("high",new UpdateBetPriceAllUser_v2($game,$game_code,$bet_number,$TotalBetTodayByNumber,$TotalBetTodayByGame,0,0));
    }

    public static function reCalculatorNumberV2($game_code,$bet_number,$price){
        // danh cho tk lockprice=2 chi theo 789
        $userAdmin = User::where('id', 274)->get();
        echo " " . count($userAdmin) ." update gia moi " . $price . PHP_EOL;
        foreach ($userAdmin as $user) {
            echo $user->id . PHP_EOL;
            // $game_number = new Game_Number();
            $game_number = new stdClass;
            $game_number->exchange_rates = $price;
            $game_number->a = 0;
            $game_number->x = 0;
            $game_number->y = 0;
            $game_number->number = $bet_number;
            $game_number->code_type = $game_code;
            $game_number->userid = $user->id;
            Queue::pushOn("high", new UpdateChildEX($user, $game_number, $game_number->exchange_rates, 0,2));
        }

    }

    public static function fetchOne789AuthDataRaw(){
        $timestamp = time();
        $linkminhngoc = 'https://lotto.lotusapi.com/odds/player?term='.date("Y-m-d").'&gameTypes=0&betTypes=0&betTypes=1&betTypes=22';
        $_OddsServerToken = Cache::get("_OddsServerToken");
        $client = new Client();
        $res = $client->request('GET',$linkminhngoc, [
            'headers' => ['content-type' => 'application/x-www-form-urlencoded', 'referer' => '8one789.net', 'origin' => '8one789.net', 'Accept-Language' => 'vi-vn', 'Authorization' => ('Bearer '.$_OddsServerToken)],
            'timeout' => 20,
            'connect_timeout' => 20
        ]);

        $kqraw = json_decode($res->getBody(), true);
        return $kqraw;
    }
    public static function fetchOne789AuthData($kqraw,$game_id,$subPrice,$betTypes=1){
            try{
                $price = $kqraw['Price'];
                $kqraw = $kqraw['Numbers'];
                if ($betTypes == 1){
                    // var_dump($kqraw);
                    $countNumberOver = 0;
                    $lockBlackNumber = Cache::get('fetchOne789DataLockBlackNumber-0',[]);
                    $strNumberOver = $lockBlackNumber;
                    // $strNumberOver = [];
                    foreach ($kqraw as $key => $number) {
                        if ($number["ExtraPrice"] >= 350){
                            $countNumberOver++;
                            $strNumberOver[]= $number["Number"];
                        }
                    }
                    
                    if ((Cache::get('xacnhan_sokhoado_bot', false) == false)){
                        if ($countNumberOver >= 6){
                            echo $countNumberOver . PHP_EOL;
                            echo implode(",",$strNumberOver);
                            $game = Game::where('game_code',7)->first();
                            $game->locknumberred = implode(",",$strNumberOver);
                            $game->save();
        
                            $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            $channelid = "-1002038570631"; //channel issues
                            // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            // $channelid = "-1001667315543";
                            if (Cache::get('locknumberred-'.$game->locknumberred,true)){
                                NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Khoá đỏ'.$game->locknumberred);
                                Cache::put('locknumberred-'.$game->locknumberred, false, env('CACHE_TIME', 12*60));
                            }
        
                        }else{
                            $game = Game::where('game_code',7)->first();
                            $game->locknumberred = null;
                            $game->save();
                        }
                    }else{
                        if ($countNumberOver >= 6){
                            echo $countNumberOver . PHP_EOL;
                            echo implode(",",$strNumberOver);
                            $game = Game::where('game_code',7)->first();
                            // $game->locknumberred = implode(",",$strNumberOver);
                            // $game->save();
        
                            $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            $channelid = "-1002038570631"; //channel issues
                            // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            // $channelid = "-1001667315543";
                            if (Cache::get('locknumberred-'.$game->locknumberred,true) && $game->locknumberred != ""){
                                NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Cảnh báo '.$game->locknumberred .' Không ghi đè.');
                                Cache::put('locknumberred-'.$game->locknumberred, false, env('CACHE_TIME', 12*60));
                            }
                        }else{
                        }
                    }
                }
                Cache::put('fetchOne789Data-'.$game_id, $kqraw, env('CACHE_TIME', 1*60));
                var_dump($kqraw);
                $lockBlackNumber = [];
                $updatedNumber = [];
                foreach ($kqraw as $key => $number) {

                    //begin len gia binh thuong
                    if ($number["ExtraPrice"] > $subPrice){
                        $old789 = Cache::get('fetchOne789Data-'.$game_id.'-'.$number["Number"], 0);
                        $new789 = $number["ExtraPrice"] - $subPrice;
                    }else{
                        $old789 = Cache::get('fetchOne789Data-'.$game_id.'-'.$number["Number"], 0);
                        $new789 = 0;
                    }
                    Cache::put('fetchOne789Data-'.$game_id.'-'.$number["Number"], $new789, env('CACHE_TIME', 1*60));
                    echo 'fetchOne789Data-'.$game_id.'-'.$number["Number"] . '-' . $new789;
                    //end

                    $old789_1 = Cache::get('fetchOne789DataRaw3-'.$game_id.'-'.$number["Number"], 0);
                    $new789_1 = $number["ExtraPrice"];
                    Cache::put('fetchOne789DataRaw3-'.$game_id.'-'.$number["Number"], $new789_1, env('CACHE_TIME', 1*60));
                    
                    echo " ".$old789_1 . "+". $new789_1 . PHP_EOL;
                    if ($old789_1 != $new789_1){
                        $updatedNumber[] = $number["Number"];
                        Xoso::reCalculatorNumberSuper($game_id,$number["Number"]);
                        Xoso::reCalculatorNumberV2($game_id,$number["Number"],$price + $new789_1);
                    }
                        
                    if ( (isset($number["Stop"]) && $number["Stop"] == true) 
                    || ($number["ExtraPrice"] > 100) ){
                        $lockBlackNumber[] = $number["Number"];
                    }
                }

                if (count($updatedNumber) > 0){
                    // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                    // $channelid = "-1002038570631"; //channel issues
                    // // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                    // // $channelid = "-1001667315543";
                    // $game = Game::where('game_code',$game_id)->first();
                    // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, '[AUTH] Cập nhật giá '.$game->name.": ".implode(",",$updatedNumber));
                }
                if ($game_id == 14 || $game_id == 12){
                    $oldLock = Cache::get('fetchOne789DataLockBlackNumber-'.$game_id,[]);
                    if ($oldLock != $lockBlackNumber){
                        $game = Game::where('game_code',$game_id)->first();
                        $game->locknumber = implode(",",$lockBlackNumber);
                        $game->save();
                        Cache::put('fetchOne789DataLockBlackNumber-'.$game_id, $lockBlackNumber, env('CACHE_TIME', 1*60));

                        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
			            $channelid = "-1002038570631"; //channel issues
                        // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                        // $channelid = "-1001667315543";
                        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Khóa đen '.$game->name .': '.$game->locknumber);
                    }
                }
                
                return $kqraw;
            }catch(\Exception $ex){
                echo $ex->getMessage();
                return null;
            }
        return null;
    }

    public static function fetchOne789Data($game_id,$subPrice,$betTypes=1){
        // for($i=1;$i<=2;$i++)
            try{
                echo 'fetchOne789 '.$betTypes. PHP_EOL;
                $timestamp = time();
                $linkminhngoc = 'https://lotto.lotusapi.com/odds/public?gameTypes=0&betTypes='.$betTypes;

                $client = new Client();
                $res = $client->request('GET',$linkminhngoc);

                $kqraw = json_decode($res->getBody(), true);
                $price = $kqraw[0]['Price'];
                $kqraw = $kqraw[0]['Numbers'];
                if ($betTypes == 1){
                    // var_dump($kqraw);
                    
                    $countNumberOver = 0;
                    $lockBlackNumber = Cache::get('fetchOne789DataLockBlackNumber-0',[]);
                    $strNumberOver = $lockBlackNumber;
                    // $strNumberOver = [];
                    foreach ($kqraw as $key => $number) {
                        if ($number["ExtraPrice"] >= 350){
                            $countNumberOver++;
                            $strNumberOver[]= $number["Number"];
                        }
                    }
                    
                    if ((Cache::get('xacnhan_sokhoado_bot', false) == false)){
                        if ($countNumberOver >= 6){
                            echo $countNumberOver . PHP_EOL;
                            echo implode(",",$strNumberOver);
                            $game = Game::where('game_code',7)->first();
                            $game->locknumberred = implode(",",$strNumberOver);
                            $game->save();
        
                            $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            $channelid = "-1002038570631"; //channel issues
                            // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            // $channelid = "-1001667315543";
                            if (Cache::get('locknumberred-'.$game->locknumberred,true)){
                                NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Khoá đỏ'.$game->locknumberred);
                                Cache::put('locknumberred-'.$game->locknumberred, false, env('CACHE_TIME', 12*60));
                            }
        
                        }else{
                            $game = Game::where('game_code',7)->first();
                            $game->locknumberred = null;
                            $game->save();
                        }
                    }else{
                        if ($countNumberOver >= 6){
                            echo $countNumberOver . PHP_EOL;
                            echo implode(",",$strNumberOver);
                            $game = Game::where('game_code',7)->first();
                            // $game->locknumberred = implode(",",$strNumberOver);
                            // $game->save();
        
                            $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            $channelid = "-1002038570631"; //channel issues
                            // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                            // $channelid = "-1001667315543";
                            if (Cache::get('locknumberred-'.$game->locknumberred,true) && $game->locknumberred != ""){
                                NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Cảnh báo '.$game->locknumberred .' Không ghi đè.');
                                Cache::put('locknumberred-'.$game->locknumberred, false, env('CACHE_TIME', 12*60));
                            }
                        }else{
                        }
                    }
                }
                Cache::put('fetchOne789Data-'.$game_id, $kqraw, env('CACHE_TIME', 1*60));
                var_dump($kqraw);
                $lockBlackNumber = [];
                $updatedNumber = [];
                foreach ($kqraw as $key => $number) {

                    //begin len gia binh thuong
                    if ($number["ExtraPrice"] > $subPrice){
                        $old789 = Cache::get('fetchOne789Data-'.$game_id.'-'.$number["Number"], 0);
                        $new789 = $number["ExtraPrice"] - $subPrice;
                    }else{
                        $old789 = Cache::get('fetchOne789Data-'.$game_id.'-'.$number["Number"], 0);
                        $new789 = 0;
                    }
                    Cache::put('fetchOne789Data-'.$game_id.'-'.$number["Number"], $new789, env('CACHE_TIME', 1*60));
                    echo 'fetchOne789Data-'.$game_id.'-'.$number["Number"] . '-' . $new789;
                    //end

                    $old789_1 = Cache::get('fetchOne789DataRaw3-'.$game_id.'-'.$number["Number"], 0);
                    $new789_1 = $number["ExtraPrice"];
                    Cache::put('fetchOne789DataRaw3-'.$game_id.'-'.$number["Number"], $new789_1, env('CACHE_TIME', 1*60));
                    
                    echo " ".$old789_1 . "+". $new789_1 . PHP_EOL;
                    if ($old789_1 != $new789_1){
                        $updatedNumber[] = $number["Number"];
                        Xoso::reCalculatorNumberSuper($game_id,$number["Number"]);
                        Xoso::reCalculatorNumberV2($game_id,$number["Number"],$price + $new789_1);
                    }
                        
                    if ( (isset($number["Stop"]) && $number["Stop"] == true) 
                    || ($number["ExtraPrice"] > 100) ){
                        $lockBlackNumber[] = $number["Number"];
                    }
                }

                if (count($updatedNumber) > 0){
                    // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                    // $channelid = "-1002038570631"; //channel issues
                    // // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                    // // $channelid = "-1001667315543";
                    // $game = Game::where('game_code',$game_id)->first();
                    // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Cập nhật giá '.$game->name.": ".implode(",",$updatedNumber));
                }
                if ($game_id == 14 || $game_id == 12){
                    $oldLock = Cache::get('fetchOne789DataLockBlackNumber-'.$game_id,[]);
                    if ($oldLock != $lockBlackNumber){
                        $game = Game::where('game_code',$game_id)->first();
                        $game->locknumber = implode(",",$lockBlackNumber);
                        $game->save();
                        Cache::put('fetchOne789DataLockBlackNumber-'.$game_id, $lockBlackNumber, env('CACHE_TIME', 1*60));

                        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
			            $channelid = "-1002038570631"; //channel issues
                        // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                        // $channelid = "-1001667315543";
                        NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Khóa đen '.$game->name .': '.$game->locknumber);
                    }
                }
                
                return $kqraw;
            }catch(\Exception $ex){
                echo $ex->getMessage();
                return null;
            }
        return null;
    }

    public static function fetchOne789($betTypes=1){
        // for($i=1;$i<=2;$i++)
            try{
                echo 'fetchOne789'. PHP_EOL;
                $timestamp = time();
                $linkminhngoc = 'https://lotto.lotusapi.com/odds/public?gameTypes=0&betTypes='.$betTypes;

                $client = new Client();
                $res = $client->request('GET',$linkminhngoc);

                $kqraw = json_decode($res->getBody(), true);
                // var_dump($kqraw);
                $kqraw = $kqraw[0]['Numbers'];
                $countNumberOver = 0;
                $lockBlackNumber = Cache::get('fetchOne789DataLockBlackNumber-0',[]);
                $strNumberOver = $lockBlackNumber;
                // $strNumberOver = [];
                foreach ($kqraw as $key => $number) {
                    if ($number["ExtraPrice"] >= 350){
                        $countNumberOver++;
                        $strNumberOver[]= $number["Number"];
                    }
                }
                
                if ((Cache::get('xacnhan_sokhoado_bot', false) == false)){
                    if ($countNumberOver >= 6){
                        echo $countNumberOver . PHP_EOL;
                        echo implode(",",$strNumberOver);
                        $game = Game::where('game_code',7)->first();
                        $game->locknumberred = implode(",",$strNumberOver);
                        $game->save();
    
                        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
			            $channelid = "-1002038570631"; //channel issues
                        // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                        // $channelid = "-1001667315543";
                        if (Cache::get('locknumberred-'.$game->locknumberred,true)){
                            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Khoá '.$game->locknumberred);
                            Cache::put('locknumberred-'.$game->locknumberred, false, env('CACHE_TIME', 12*60));
                        }
    
                    }else{
                        $game = Game::where('game_code',7)->first();
                        $game->locknumberred = null;
                        $game->save();
                    }
                }else{
                    if ($countNumberOver >= 6){
                        echo $countNumberOver . PHP_EOL;
                        echo implode(",",$strNumberOver);
                        $game = Game::where('game_code',7)->first();
                        // $game->locknumberred = implode(",",$strNumberOver);
                        // $game->save();
    
                        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
			            $channelid = "-1002038570631"; //channel issues
                        // $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
                        // $channelid = "-1001667315543";
                        if (Cache::get('locknumberred-'.$game->locknumberred,true)){
                            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Cảnh báo '.$game->locknumberred .' Không ghi đè.');
                            Cache::put('locknumberred-'.$game->locknumberred, false, env('CACHE_TIME', 12*60));
                        }
                    }else{
                    }
                }
                // var_dump($kqraw);
                return 0;
            }catch(\Exception $ex){
                return 2;
            }
        return 0;
    }

    public static function setTokenLD789()
    {
        $i = 0;
        while(true){
            try{
                if ($i>10) return false;
                $i++;
                $tokenTime = Cache::get("_OddsServerTokenTime",0);
                $otken = Cache::get("_OddsServerToken");
                echo $tokenTime . PHP_EOL;
                if ($tokenTime + 59 * 60 > time()) return true;
                echo "get new token". PHP_EOL;

                $url789 = 'https://id.lotusapi.com/auth/sign-in';

                $client = new Client();
                $res = $client->request('POST',$url789,
                ["json" => [
                        'Username' => 'k8hung',//'sank138',
                        'Password' => 'Abc12345'
                    
                ]]
                );
                
                $kqraw = json_decode($res->getBody(), true);
                $_OddsServerToken =  $kqraw['IdToken'];
        
                Cache::put("_OddsServerToken", $_OddsServerToken, 60 * 29);
                Cache::put("_OddsServerTokenTime", time(), 60 * 29);
                Cache::put("_TokenLD789Failed", 0, 60 * 29);
                return true;
            }catch(Exception $ex){
                Log::info($ex->getMessage());
                echo $ex->getMessage();
            }
        }
        if (Cache::get("_TokenLD789Failed") == 0){
            $client = new Client();
            $res = $client->request('GET','https://api.telegram.org/bot1803964036:AAEQq_u1JnlhsK3zF_VswcIrfg6oin4smIc/sendMessage?chat_id=@report7zbugs&text=token-ld789-failed', [
                'timeout' => 15,
                'connect_timeout' => 15,
                'headers' => [
                ]
                // 'proxy'=> '1.20.99.178:34781'
            ]);
        }
        Cache::put("_TokenLD789Failed", 1, 60 * 29);
        return false;
    }

    public static function getLoginLuk(){
        $value = "";
        try{
            for ($i=0; $i < 5; $i++) { 
                $linkminhngoc = 'https://loto79.net/auth/login';
                $client = new Client();
                $tokenTime = Cache::get("_OddsServerTokenTimeLuk",0);
                $token = Cache::get("_OddsServerTokenLuk");
                $value = $token;
                echo $tokenTime . PHP_EOL;
                if ($tokenTime + 55 * 60 > time()){
                    echo "ready token";
                }else{
                    $res = $client->request('POST',$linkminhngoc,
                    [
                        'curl' => [CURLOPT_SSL_VERIFYPEER => false ],
                        RequestOptions::PROXY => 'user49033:V82GTuzvUm@103.15.88.35:49033',
                        RequestOptions::VERIFY => false, # disable SSL certificate validation
                        RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
                        'headers' => [
                            'Content-type' => 'application/x-www-form-urlencoded', 
                            'Host' => 'loto79.net',
                            'Referer' => 'https://loto79.net/quickplay/1'
                        ],
                        'timeout' => 20,
                        'connect_timeout' => 15,
                        'form_params' => [
                            'do_login' => true,
                            'username' => 'kokomi11',
                            'passwd' => 'Qaz2222'
                        ]
                    ]
                    );
                    if ($res->getBody() != "true"){
                        break;
                    }
                    $cookie = $res->getHeader("Set-Cookie")[0];
                    $laravel_session = explode("; ",$cookie)[0];
                    $value = explode("=",$laravel_session)[1];
                    Cache::put("_OddsServerTokenLuk", $value, 60 * 55);
                    Cache::put("_OddsServerTokenTimeLuk", time(), 60 * 55);
                    echo "new token";
                }
                break;
            }
        }catch(Exception $ex){
            echo $ex->getMessage();
        }
        if ( $value == "" && Cache::get('getLoginLuk-status',true)){
            $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
            $channelid = "-1002038570631"; //channel issues
            NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, "Tài khoản luk login lỗi!!!");  
            Cache::put('getLoginLuk-status', false, 60 * 55);
        }else{
            Cache::put('getLoginLuk-status', true, 60 * 55);
        }
        return $value;
    }

    public static function setCheckNumberLuk($text)
    {
        $value = static::getLoginLuk();
        if ($value =="") {
            return;
        }
        $jar = CookieJar::fromArray(
            [
                'laravel_session' => $value,
                'Path'=> '/',
                'HttpOnly'=>'',
                'Expires'=>'Tue, 30 Jan 2024 13:34:06 GMT'
            ],
            'loto79.net'
        );

        for ($loop=0; $loop < 3; $loop++) { 
            try{
                $client = new Client([
                    'cookies' => $jar
                ]);
                $res = $client->request('GET','https://loto79.net/load-preview-modal?quicktext='.$text.'&ipaddress=undetected&slug=1&inputC=0',
                [
                    'curl' => [CURLOPT_SSL_VERIFYPEER => false, CURLOPT_COOKIE => true],
                    RequestOptions::PROXY => 'user49033:V82GTuzvUm@103.15.88.35:49033',
                    RequestOptions::VERIFY => false, # disable SSL certificate validation
                    RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
                    'headers' => [
                        'Content-type' => 'application/x-www-form-urlencoded', 
                        'Host' => 'loto79.net',
                        'Referer' => 'https://loto79.net/quickplay/1',
                    ],
                    'timeout' => 20,
                    'connect_timeout' => 15,
                ]
                );
                echo PHP_EOL;
                $domHtml = HtmlDomParser::str_get_html($res->getBody());
                if (!isset($domHtml))
                    echo "failed";
                else{
                    $mainBody = $domHtml->find("table > tbody",0)->children();
                    foreach ($mainBody as $key=>$noteTr) {
                        if ($key == 0) continue;
                        $td = $noteTr->children();
                            foreach ($td as $noteTd) {
                            $status = trim($noteTd->children(7)->innertext());
                            echo trim($noteTd->children(3)->innertext()) . " : " . $status  . PHP_EOL;
                            if ($status != "" && $status !== 'ok' && $status !== 'Hết hạn cược') {
                                return false;
                            }
                        }
                        break;
                    }
                }
                break;
            }catch(Exception $ex){
                echo $ex->getMessage();
            }
        }
        return true;
    }

    public static function setCheckLuk()
    {
        $bot_id = "6037941352:AAFNyhmmj_0G-xDJoZD8qUUnSTvLz4l6IqQ";
        $channelid = "-1002038570631"; //channel issues
        if (static::setCheckNumberLuk('lo+daunho+dauto+x1') == false){
            $lockDau = [];
            $lockDuoi = [];

            $lockDauStr = '';
            $lockDuoiStr = '';
            for($i=0;$i<10;$i++){
                if (static::setCheckNumberLuk('lo+dau'.$i.'+x1') == false){
                    $lockDau[] = $i;
                    // if (Cache::get('setCheckLuk-dau'.$i,true)){
                        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Cảnh báo Khóa Đầu '.$i);
                        // Cache::put('setCheckLuk-dau'.$i, false, env('CACHE_TIME', 12*60));
                    // }
                }
                sleep(1);
            }
            for($i=0;$i<10;$i++){
                if (static::setCheckNumberLuk('lo+duoi'.$i.'+x1') == false){
                    $lockDuoi[] = $i;
                    // if (Cache::get('setCheckLuk-duoi'.$i,true)){
                        // NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, 'Cảnh báo Khóa Đuôi '.$i);
                        // Cache::put('setCheckLuk-duoi'.$i, false, env('CACHE_TIME', 12*60));
                    // }
                }
                sleep(1);
            }
            $txt = "Cảnh báo Khóa";
            $lockDauStr = implode(",",$lockDau);
            $lockDuoiStr = implode(",",$lockDuoi);
            if (count($lockDau) > 0 ) $txt.= " Đầu: ".$lockDauStr;
            if (count($lockDuoi) > 0 ) $txt.= " Đuôi: ".$lockDuoiStr;

            if ( Cache::get('setCheckLuk-dau','') != $lockDauStr 
                || Cache::get('setCheckLuk-duoi','') != $lockDuoiStr ){
                NotifyHelpers::SendTelegramNotificationByChannel($bot_id,$channelid, $txt);  
                // echo $txt;
            }

            Cache::put('setCheckLuk-dau', $lockDauStr, env('CACHE_TIME', 12*60));
            Cache::put('setCheckLuk-duoi', $lockDuoiStr, env('CACHE_TIME', 12*60));
            
        }
    }
}