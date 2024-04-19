<?php
    $now = date_format($date,'Y-m-d'); // date('Y-m-d');
    $hour = date('H');
    $min = date('i');
    $sec = date('s');
    $yesterday = date('Y-m-d', time()-86400);

    if ($location->slug ==1){
        $yesterday = date('Y-m-d', time()-86400);
        $datepickerXS= date('d-m-Y', time()-86400);
        if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('i') )<14)){
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($location,$yesterday) {
                // return 
                xoso::getKetQua($location->slug,$yesterday);
            // });
            // $rs = xoso::getKetQua($location->slug,$yesterday);
        }
        else{
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () use ($location) {
                // return 
                xoso::getKetQua($location->slug,date('Y-m-d'));
            // });
            // $rs = xoso::getKetQua($location->slug,date('Y-m-d'));
            $datepickerXS= date('d-m-Y');
        }
    }

    //mien nam
    if ($location->slug ==21 || $location->slug ==22){
        $yesterday = date('Y-m-d', time()-86400);
        $datepickerXS= date('d-m-Y', time()-86400);
        if(intval(date('H') )<16 || (intval(date('H') )==16 && intval(date('i') )<12)){
            $rs = xoso::getKetQua($location->slug,$yesterday);
            // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($location,$yesterday) {
                // return 
                
            // });
            // $rs = xoso::getKetQua($location->slug,$yesterday);
        }
        else{
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () use ($location) {
                // return 
                xoso::getKetQua($location->slug,date('Y-m-d'));
            // });
            // $rs = xoso::getKetQua($location->slug,date('Y-m-d'));
            $datepickerXS= date('d-m-Y');
        }
    }

    //mien trung
    if ($location->slug ==31 || $location->slug ==32){
        $yesterday = date('Y-m-d', time()-86400);
        $datepickerXS= date('d-m-Y', time()-86400);
        if(intval(date('H') )<17 || (intval(date('H') )==17 && intval(date('i') )<12)){
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($location,$yesterday) {
                // return 
                xoso::getKetQua($location->slug,$yesterday);
            // });
            // $rs = xoso::getKetQua($location->slug,$yesterday);
        }
        else{
            $rs = 
            // Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () use ($location) {
                // return 
                xoso::getKetQua($location->slug,date('Y-m-d'));
            // });
            // $rs = xoso::getKetQua($location->slug,date('Y-m-d'));
            $datepickerXS= date('d-m-Y');
        }
    }
    
    if ($location->slug ==4){
        $rs = 
        // Cache::tags('kqxs')->remember('kqxs-4-'.($hour/1).'-'.$now, env('CACHE_TIME', 0), function () use ($hour,$now,$location) {
            // return 
            xoso::getKetQuaXSA($location->slug,($hour/1),$now);
        // });
        // $rs = xoso::getKetQuaXSA($location->slug,($hour/1),$now);
    }

    if ($location->slug ==5){
        // $nowKeno = date_format($date,'Y-m-d H:i:s'); // date('Y-m-d');
        $rs = 
        // Cache::tags('kqxs')->remember('kqxs-4-'.($hour/1).'-'.$now, env('CACHE_TIME', 0), function () use ($hour,$now,$location) {
            // return 
            xoso::getKetQuaKeno($location->slug,($hour/1),($min - $min%10),$now);
        // });
        // $rs = xoso::getKetQuaXSA($location->slug,($hour/1),$now);
    }

    // $gameList = GameHelpers::GetAllGameByParentID(0);
?>

@extends('frontend.frontend-template')

@section('sidebar-menu')
    @parent
    
@stop

@section('content')
    <!--  -->
 
    
    @if ($location->slug==5)
    <?php
        $rs_array = xoso::getKetQuaKenoByDay($location->slug,$now);
        // foreach($rs_array as $rs){
        //     print_r($rs);
        // }
    ?>
    <style>
        .keno_tructiep table,.keno_tructiep tr,.keno_tructiep td{border:1px inset #9d3437;border-collapse:collapse;border-spacing:0;text-align:center;font-weight:700;}
        .keno_tructiep .tblTK tbody tr:nth-child(1),.keno_tructiep .tblTK tbody tr:nth-child(2),.keno_tructiep .tblTK tbody tr:nth-child(5),.keno_tructiep .tblTK tbody tr:nth-child(6),.keno_tructiep .tblTK tbody tr:nth-child(9),.keno_tructiep .tblTK tbody tr:nth-child(10){background:#fff9d3}
    </style>

<div class="input-group col-md-4 col-xs-4">
             <span class="input-group">
                <input type="text" class="datepicker form-control" placeholder="{{$now}}"  id="home-keno-datepicker" readonly="readonly"/>
                <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_kqkeno">Xem</a>
            </span>
  
    </div>

    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="keno_tructiep">
        <div class="panel-body">
            <div id="kq">
                <table width="100%" class="tblTK tblKQ">
                    <thead>
                        <tr>
                            <td>KỲ XỔ</td>
                            <td>THỜI GIAN</td>
                            <td >Tổng</td>
                            <td >Chẵn/Lẻ</td>
                            <td >Lớn/Bé</td>
                            <td colspan="10">BỘ SỐ</td>
                            
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($rs_array as $rs)
                    <?php
                        $kq = explode(",",$rs->DB);
                        $kq1 = array_slice($kq,0,10);
                        $kq2 = array_slice($kq,10,10);
                        $time = explode(" ",$rs->updated_at . '');
                        $chan = 0;
                        $be = 0;
                        foreach($kq as $item){
                            if ($item <= 40)
                                $be++;
                            if ($item %2 == 0)
                                $chan++;
                        }
                    ?>
                        <tr>
                            <td rowspan="2">{{$rs->Giai_8}}<br></td>
                            <td rowspan="2">
                                <div>{{$time[0]}}</div>
                                <div>{{$time[1]}}</div>
                                
                            </td>
                            
                            <td rowspan="2">{{$rs->Giai_1}}<br></td>
                            <td rowspan="2">
                                <div>Chẵn {{$chan}}</div>
                                <div>Lẻ {{20-$chan}}</div>
                            </td>
                            <td rowspan="2">
                                <div>Lớn {{20-$be}}</div>
                                <div>Bé {{$be}}</div>
                            </td>

                            @foreach($kq1 as $item)
                                <td>{{$item}}</td>
                            @endforeach
                            <tr>
                            @foreach($kq2 as $item)
                                <td>{{$item}}</td>
                            @endforeach
                            </tr>

                            

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>

    @endif

    @if ($location->slug==4)
    <div class="input-group col-md-4 col-xs-4">
        
            <span class="input-group">
                <input type="text" class="datepicker form-control" placeholder="{{$now}}"  id="home-xsao-datepicker" readonly="readonly"/>
                <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_kqsxao">Xem</a>
            </span>
   
    </div>
        @if ($now != date('Y-m-d'))

            @for($i=24;$i>0;$i--)
        <?php
                $rs = xoso::getKetQuaXSA($location->slug,$i,$now);
            
        ?>

            @if (isset($rs) && count($rs)>0)
    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="panel-body">

            <div class="input-group col-md-4 col-xs-4">
                @if ($location->slug==1)
                <input type="text" class="datepicker form-control" placeholder="{{$datepickerXS}}"  id="home-datepicker" readonly="readonly"/>

                    <span class="input-group-btn">
                    <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_kqsx">Xem</a>
                    </span>
                @else
                    
                @endif
                </div>
            <div 
            @if ($i==$hour)
                id="xsaodiv"
            @else    
                id="div_kqsx"
            @endif>
                @if(count($rs)>0)
                    <h2 class="table-header">{{$rs['location']}} ngày <span class="badge badge-blue">{{$rs['date']}}</span> Kỳ {{$i}} <span class="badge badge-blue">Đặt cược {{$i-1}}:45 - {{$i}}:14</span>  <span class="badge badge-blue">Quay thưởng {{$i}}:15 - {{$i}}:25</span></h2>
                    <table class="table table-striped">
                        <tr>
                            <td>Giải đặc biệt</td>
                            <td><span class="badge badge-blue">{{$rs['DB']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhất</td>
                            <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhì</td>
                            <td>
                                @foreach($rs['2'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải ba</td>
                            <td>
                                @foreach($rs['3'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tư</td>
                            <td>
                                @foreach($rs['4'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải năm</td>
                            <td>
                                @foreach($rs['5'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải sáu</td>
                            <td>
                                @foreach($rs['6'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải bảy</td>
                            <td>
                                @foreach($rs['7'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        
                    </table>
                @else
                    Chưa có kết quả
                @endif
            </div>
        </div>
    </div>
    @endif
        @endfor

        @else

        @for($i=24;$i>0;$i--)
        <?php
            if (($hour==0 || $hour==24 )&& ($min<=45)){
                $rs = xoso::getKetQuaXSA($location->slug,$i,$yesterday);
                $hour=24;
                $now = $yesterday;
            }
            else{
                $rs = xoso::getKetQuaXSA($location->slug,$i,$now);
                // if ($hour==0) $hour++;
            }
        ?>

        @if(($min >=26 && $min <= 59) && ($hour==0) && $i==1)
            <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
                <div class="panel-body">
                    <h2 class="table-header">XS Ảo ngày <span class="badge badge-blue">{{$now}}</span> Kỳ {{$i}} <span class="badge badge-blue">Đặt cược {{$i-1}}:45 - {{$i}}:14</span>  <span class="badge badge-blue">Quay thưởng {{$i}}:15 - {{$i}}:25</span></h2>
                </div>
            </div>
        @endif

        @if(($min >=26 && $min <= 59)&& ($hour==$i) && ($hour!=24))
            <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
                <div class="panel-body">
                    <h2 class="table-header">XS Ảo ngày <span class="badge badge-blue">{{$now}}</span> Kỳ {{$i+1}} <span class="badge badge-blue">Đặt cược {{$i}}:45 - {{$i+1}}:14</span>  <span class="badge badge-blue">Quay thưởng {{$i+1}}:15 - {{$i+1}}:25</span></h2>
                </div>
            </div>
        @endif

        @if(($min >=0 && $min <= 14)&& ($hour==$i)&& ($hour!=24))
            <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
                <div class="panel-body">
                    <h2 class="table-header">XS Ảo ngày <span class="badge badge-blue">{{$now}}</span> Kỳ {{$i}} <span class="badge badge-blue">Đặt cược {{$i-1}}:45 - {{$i}}:14</span>  <span class="badge badge-blue">Quay thưởng {{$i}}:15 - {{$i}}:25</span></h2>
                </div>
            </div>
        @endif
            @if (isset($rs) && count($rs)>0)
    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="panel-body">

            <div class="input-group col-md-4 col-xs-4">
                @if ($location->slug==1)
                <input type="text" class="datepicker form-control" placeholder="{{$datepickerXS}}"  id="home-datepicker" readonly="readonly"/>

                    <span class="input-group-btn">
                    <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_kqsx">Xem</a>
                    </span>
                @else
                    
                @endif
                </div>
            <div 
            @if ($i==$hour)
                id="xsaodiv"
            @else    
                id="div_kqsx"
            @endif>
                @if(count($rs)>0)
                    <h2 class="table-header">{{$rs['location']}} ngày <span class="badge badge-blue">{{$rs['date']}}</span> Kỳ {{$i}} <span class="badge badge-blue">Đặt cược {{$i-1}}:45 - {{$i}}:14</span>  <span class="badge badge-blue">Quay thưởng {{$i}}:15 - {{$i}}:25</span></h2>
                    <table class="table table-striped">
                        <tr>
                            <td>Giải đặc biệt</td>
                            @if ($min>22 || $hour > $i)
                                <td><span class="badge badge-blue" style="background-color: red;">{{$rs['DB']}}</span></td>
                            @elseif ($min==22)
                                @if ($sec >=30)
                                    <td><span class="badge badge-blue" style="background-color: red;">{{$rs['DB']}}</span></td>
                                    @else <td><span class="badge badge-blue" style="background-color: red;">-----</span> <span class="split"> </span></td>
                                    @endif
                            @else
                                <td><span class="badge badge-blue" style="background-color: red;">-----</span> <span class="split"> </span></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Giải nhất</td>
                            @if ($min>15 || $hour > $i)
                                <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                            @elseif ($min==15)
                                @if ($sec >=30)
                                    <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                                @else <td><span class="badge badge-blue">-----</span> <span class="split"> </span></td>
                                    @endif
                            @else
                                <td><span class="badge badge-blue">-----</span> <span class="split"> </span></td>
                            @endif
                        </tr>
                        <tr>
                            <td>Giải nhì</td>
                            <td>
                                    @if ($min>16 || $hour > $i)
                                        @foreach($rs['2'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==16)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['2'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['2'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['2'] as $item)
                                            <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải ba</td>
                            <td>
                                @if ($min>17 || $hour > $i)
                                        @foreach($rs['3'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==17)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['3'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=10)
                                            <span class="badge badge-blue">{{$rs['3'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=20)
                                            <span class="badge badge-blue">{{$rs['3'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['3'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=40)
                                            <span class="badge badge-blue">{{$rs['3'][4]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=50)
                                            <span class="badge badge-blue">{{$rs['3'][5]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['3'] as $item)
                                            <span class="badge badge-blue">-----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tư</td>
                            <td>
                                @if ($min>18 || $hour > $i)
                                        @foreach($rs['4'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==18)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['4'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=15)
                                            <span class="badge badge-blue">{{$rs['4'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['4'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=45)
                                            <span class="badge badge-blue">{{$rs['4'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['4'] as $item)
                                            <span class="badge badge-blue">----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải năm</td>
                            <td>
                                @if ($min>19 || $hour > $i)
                                        @foreach($rs['5'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==19)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['5'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=10)
                                            <span class="badge badge-blue">{{$rs['5'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=20)
                                            <span class="badge badge-blue">{{$rs['5'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['5'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=40)
                                            <span class="badge badge-blue">{{$rs['5'][4]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=50)
                                            <span class="badge badge-blue">{{$rs['5'][5]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">----</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['5'] as $item)
                                            <span class="badge badge-blue">----</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải sáu</td>
                            <td>
                                @if ($min>20 || $hour > $i)
                                        @foreach($rs['6'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==20)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['6'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">---</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=20)
                                            <span class="badge badge-blue">{{$rs['6'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">---</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=40)
                                            <span class="badge badge-blue">{{$rs['6'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">---</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['6'] as $item)
                                            <span class="badge badge-blue">---</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Giải bảy</td>
                            <td>
                                @if ($min>21 || $hour > $i)
                                        @foreach($rs['7'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==21)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['7'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=15)
                                            <span class="badge badge-blue">{{$rs['7'][1]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['7'][2]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=45)
                                            <span class="badge badge-blue">{{$rs['7'][3]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                    @else
                                        @foreach($rs['7'] as $item)
                                            <span class="badge badge-blue">--</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                            </td>
                        </tr>
                        @if(count($rs['8']) > 0)
                            <tr>
                                <td>Giải tám</td>
                                <td>
                                    @if ($min>22 || $hour > $i)
                                        @foreach($rs['8'] as $item)
                                            <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                        @endforeach
                                    @elseif ($min==22)
                                        @if ($sec >=0)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>
                                        @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=15)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=30)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>
                                            @else <span class="badge badge-blue">--</span> <span class="split"> </span>
                                            @endif
                                        @if ($sec >=45)
                                            <span class="badge badge-blue">{{$rs['8'][0]}}</span> <span class="split"> </span>

                                            @endif
                                    @else
                                        @foreach($rs['8'] as $item)
                                            <span class="badge badge-blue">--</span> <span class="split"> </span>
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                        @endif
                        
                    </table>
                @else
                    Chưa có kết quả
                @endif
            </div>
        </div>
    </div>
    @endif
        @endfor
        @endif
        @elseif ($location->slug==1)
    @if (isset($rs))
    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="panel-body">

            <div class="input-group col-md-4 col-xs-4">
                @if ($location->slug==1)
                <span class="input-group">
                    <input type="text" class="datepicker form-control" placeholder="{{$datepickerXS}}"  id="home-datepicker" readonly="readonly"/>
                    <a href="javascript:void(0)" class="btn btn-danger" id="btn_view_kqsx">Xem</a>
                </span>
                @else
                    
                @endif
            </div>
            <div id="div_kqsx">
                @if(count($rs)>0)
                    <h2 class="table-header">Xổ số {{$rs['location']}} ngày <span class="badge badge-blue">{{$rs['date']}}</span></h2>
                    <table class="table table-striped">
                        <tr>
                            <td>Giải đặc biệt</td>
                            <td><span class="badge badge-blue">{{$rs['DB']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhất</td>
                            <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhì</td>
                            <td>
                                @foreach($rs['2'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải ba</td>
                            <td>
                                @foreach($rs['3'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tư</td>
                            <td>
                                @foreach($rs['4'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải năm</td>
                            <td>
                                @foreach($rs['5'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải sáu</td>
                            <td>
                                @foreach($rs['6'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải bảy</td>
                            <td>
                                @foreach($rs['7'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td><h5>Xổ số điện toán - Thần tài</h5></td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['than_tai']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                    </table>

                    <h2 class="table-header">Kết quả rút gọn <span class="badge badge-blue">{{$rs['date']}}</span></h2>
                    <div class="row" style="margin:2px;">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="col-md-2">Đầu</th>
                                        <th class="col-md-12">Lô tô</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // print_r($rs);
                                for($i=0;$i<=9;$i++){
                                    $strketqua = '';
                                    for($j=0;$j<=9;$j++){
                                        $mau = $i.$j;
                                        $count=0;
                                        foreach($rs as $ketquafull){
                                            if ($count>2 && $count<11){
                                                if (is_array($ketquafull)==false ){
                                                    $ketqua2so = substr($ketquafull,-2);
                                                    // echo $ketqua2so.' ';
                                                    if ($mau == $ketqua2so )//&& !(strpos($strketqua,$mau)!== false)
                                                        $strketqua.=$mau.'; ';
                                                }else
                                                    foreach($ketquafull as $ketquatungso){
                                                        $ketqua2so = substr($ketquatungso,-2);
                                                        if (strlen($ketquatungso)<2 )continue;
                                                        // echo $ketqua2so.' ';
                                                        if ($mau === $ketqua2so )//&& !(strpos($strketqua,$mau)!== false)
                                                            $strketqua.=$mau.'; ';
                                                    }
                                            }
                                            $count++;
                                        }
                                    }
                                    echo '<tr>
                                    <td class="col-md-2" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$i.'
                                    </td>
                                    <td class="col-md-12" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$strketqua.'
                                    </td>
                                    </tr>';
                                }
                                ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                        <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Đuôi</th>
                                        <th>Lô tô</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                for($i=0;$i<=9;$i++){
                                    $strketqua = '';
                                    for($j=0;$j<=9;$j++){
                                        $mau = $j.$i;
                                        $count=0;
                                        foreach($rs as $ketquafull){
                                            if ($count>2 && $count<11){
                                                // print_r($ketquafull);
                                                if (is_array($ketquafull)==false ){
                                                    $ketqua2so = substr($ketquafull,-2);
                                                    // echo $ketqua2so.' ';
                                                    if ($mau == $ketqua2so )//&& !(strpos($strketqua,$mau)!== false)
                                                        $strketqua.=$mau.'; ';
                                                }else
                                                foreach($ketquafull as $ketquatungso){
                                                    
                                                    $ketqua2so = substr($ketquatungso,-2);
                                                    if (strlen($ketquatungso)<2 )continue;
                                                    // echo $ketqua2so.' ';
                                                    if ($mau === $ketqua2so )//&& !(strpos($strketqua,$mau)!== false)
                                                        $strketqua.=$mau.'; ';
                                                }
                                            }
                                            $count++;
                                        }
                                    }
                                    echo '<tr>
                                    <td class="col-md-2" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$i.'
                                    </td>
                                    <td class="col-md-12" style="font-size: 14px;
                                    font-weight: 600;">
                                        '.$strketqua.'
                                    </td>
                                    </tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>

                    </div>

                @else
                    Chưa có kết quả
                @endif
            </div>
        </div>
    </div>
    <!-- @endif -->

    @elseif ($location->slug==21 || $location->slug==22 || $location->slug==31 || $location->slug==32)

    <div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
        <div class="panel-body">

            <div class="input-group col-md-4 col-xs-4">

                <input type="text" class="datepicker form-control" placeholder="{{$datepickerXS}}"  id="home-datepicker" readonly="readonly"/>

                    <span class="input-group-btn">
                    <a href="javascript:void(0)" class="btn btn-danger" id=
                    @if ($location->slug==21 || $location->slug==22) "btn_view_kqsxmn"
                    @else "btn_view_kqsxmt" @endif>Xem</a>
                    </span>

                    

                </div>
            <div id="div_kqsx">
                @if(isset($rs) && count($rs)>0)
                    <h2 class="table-header">Xổ số {{$rs['location']}} - {{GameHelpers::ChuyenDoiDaiByDate($location->slug,strtotime($rs['date']))}} ngày <span class="badge badge-blue">{{$rs['date']}}</span></h2>
                    <table class="table table-striped">
                    <tr>
                            <td>Giải đặc biệt</td>
                            <td><span class="badge badge-blue">{{$rs['DB']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhất</td>
                            <td><span class="badge badge-blue">{{$rs['1']}}</span></td>
                        </tr>
                        <tr>
                            <td>Giải nhì</td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['2']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Giải ba</td>
                            <td>
                                @foreach($rs['3'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tư</td>
                            <td>
                                @foreach($rs['4'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải năm</td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['5']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Giải sáu</td>
                            <td>
                                @foreach($rs['6'] as $item)
                                    <span class="badge badge-blue">{{$item}}</span> <span class="split"> </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>Giải bảy</td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['7']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Giải tám</td>
                            <td>
                                    <span class="badge badge-blue">{{$rs['8']}}</span> <span class="split"> </span>
                            </td>
                        </tr>
                    </table>

                    

                @else
                    Chưa có kết quả
                @endif
            </div>
        </div>
    </div>


    @endif
    <input type="hidden" id="url_kqsx" value="{{url('/kqsx-by-day')}}">
    <input type="hidden" id="url_kqsxmn" value="{{url('/kqsxmn-by-day')}}">
    <input type="hidden" id="url_kqsxmt" value="{{url('/kqsxmt-by-day')}}">
    <input type="hidden" id="url" value="{{url('/')}}">
    <input type="hidden" id="xsslug" value="{{$location->slug}}">
    
@endsection
