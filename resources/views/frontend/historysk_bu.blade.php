@if(count($xosorecords)>0)<table id="table_winlose"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
<thead><tr>
<th>Hội viên</th>
<th>Đài</th>
<th>Thể loại</th>
<!--<th>Thời gian</th>-->
<th>Số cược</th>
<th>Điểm</th>
<th>Thực thu</th>
<th>Thắng/Thua</th>
<!-- <th>Số trúng thưởng</th> -->
<!-- <th>Tổng số tiền thắng</th> -->
</tr>
</thead>
<tbody>
<?php
$all_total_bet_money = 0;
$all_total_win_money = 0;
$all_total_point = 0;
?>
@foreach (GameHelpers::GetAllGame() as $key)
<?php
$haveData = false;
$total_bet_money = 0;
$total_bet_real_money = 0;

$total_win_money = 0;
$total_point = 0;
$betnumber = "";
$game_id = "";
$countrecord = 0;
$location_name = "";
?>
@foreach($xosorecords as  $stt =>   $xosorecord)
@if ($xosorecord->game_id == $key->game_code)

<?php
$location_name = $xosorecord->location;
$countrecord++;
$haveData = true;
$total_bet_money += $xosorecord->total_bet_money;

// fix tra thuong
if ($xosorecord->total_win_money > 0 && $xosorecord->game_id < 1000) {
    if (
        $xosorecord->game_id == 15 ||
        $xosorecord->game_id == 16 ||
        $xosorecord->game_id == 316 ||
        $xosorecord->game_id == 416 ||
        $xosorecord->game_id == 516 ||
        $xosorecord->game_id == 616 ||
        $xosorecord->game_id == 115 ||
        $xosorecord->game_id == 116
    ) {
        // || $xosorecord->game_id == 16
        $total_win_money += $xosorecord->total_win_money;
    } else {
        $total_win_money +=
            $xosorecord->total_win_money - $xosorecord->total_bet_money;
    }
} else {
    $total_win_money += $xosorecord->total_win_money;
}
// $total_win_money += $xosorecord->total_win_money;

$highlightbet = $xosorecord->bet_number;

$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
foreach ($arrbet as $bet) {
    if (strpos($xosorecord->win_number, $bet) !== false) {
        $highlightbet = str_replace($bet, "<b>" . $bet . "</b>", $highlightbet);
    }
}
$betnumber .= $highlightbet . ",";
if ($xosorecord->exchange_rates != 0) {
    if (
        $xosorecord->game_id == 29 ||
        $xosorecord->game_id == 329 ||
        $xosorecord->game_id == 429 ||
        $xosorecord->game_id == 529 ||
        $xosorecord->game_id == 629 ||
        $xosorecord->game_id == 9 ||
        $xosorecord->game_id == 309 ||
        $xosorecord->game_id == 409 ||
        $xosorecord->game_id == 509 ||
        $xosorecord->game_id == 609 ||
        $xosorecord->game_id == 709 ||
        $xosorecord->game_id == 10 ||
        $xosorecord->game_id == 310 ||
        $xosorecord->game_id == 410 ||
        $xosorecord->game_id == 510 ||
        $xosorecord->game_id == 610 ||
        $xosorecord->game_id == 710 ||
        $xosorecord->game_id == 11 ||
        $xosorecord->game_id == 311 ||
        $xosorecord->game_id == 411 ||
        $xosorecord->game_id == 511 ||
        $xosorecord->game_id == 611 ||
        $xosorecord->game_id == 711 ||
        $xosorecord->game_id == 21 ||
        $xosorecord->game_id == 20 ||
        $xosorecord->game_id == 19
    ) {
        $fact = 1;
        switch ($xosorecord->game_id) {
            case 29:
            case 329:
            case 429:
            case 529:
            case 629:
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                $fact = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                $fact = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                $fact = 4;
                break;
            case 21:
                $fact = 10;
                break;
            case 20:
                $fact = 8;
                break;
            case 19:
                $fact = 4;
                break;

            default:
                # code...

                break;
        }
        $countbetnumber = count(explode(",", $xosorecord->bet_number));
        $Ank =
            XoSoRecordHelpers::fact($countbetnumber) /
            XoSoRecordHelpers::fact($fact) /
            XoSoRecordHelpers::fact($countbetnumber - $fact);
        $betpoint =
            $xosorecord->total_bet_money / $xosorecord->exchange_rates / $Ank;

        $total_point += $betpoint;
    } else {
        $total_point +=
            $xosorecord->total_bet_money / $xosorecord->exchange_rates;
    }
}
?>
@endif
@endforeach

@if($haveData)

<tr> <td><button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" href="#full-width-modal{{$key->game_code}}">Chi tiết</button></td><td>{{$location_name}}</td><td>{{$key->name}}</td><td>{{$countrecord}}</td><td class="text_center text-bold"@if ($total_point<0) style=" color:red;"@endif>{{number_format($total_point)}}</td><td class="text_center text-bold"@if ($total_bet_money<0)style=" color:red;"@endif>{{number_format($total_bet_money)}}</td><td class="text_center text-bold"@if ($total_win_money<0)style=" color:red;"@endif>{{number_format($total_win_money)}}</td></tr>
<?php

$all_total_bet_money += $total_bet_money;
$all_total_win_money += $total_win_money;
$all_total_point += $total_point;
?>
@endif
@endforeach
</tbody><tfoot>
<tr><td colspan="4" class="text_right pr10">Tổng cộng</td><td class="text_center pr10 suminvoice text-bold"@if ($all_total_point<0)style=" color:red;"@endif>{{number_format($all_total_point,0)}}</td><td class="text_center pr10 suminvoice text-bold"@if ($all_total_bet_money<0)style=" color:red;"@endif>{{number_format($all_total_bet_money,0)}}</td><td class="text_center pr10 suminvoice text-bold"@if ($all_total_win_money < 0)style=" color:red;"@endif>{{number_format($all_total_win_money,0)}}</td></tr>
</tfoot>
</table>
@else
    Không có sao kê trong thời gian này
@endif
@foreach (GameHelpers::GetAllGame() as $key)
<?php
$haveData = false;
$total_bet_money = 0;
$total_bet_accept_money = 0;
$total_win_money = 0;
$total_point = 0;
$betnumber = "";
?>

<div id="full-width-modal{{$key->game_code}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-full">
<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" style="color:white" id="exampleModalLabel">Chi tiết cược</h6>
		<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

    <div class="table-rep-plugin">
<div class="table-responsive">
<table id="table_winlose"  class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
<thead>
<tr>
@if ( $key->game_code > 1000)
 <th>STT</th> 
<!--<th>Hội viên</th>-->
<th>Đài</th>
<th>Thể loại</th>
<th>Thời gian</th>
<th>Mã ván</th>
<th>Kết quả</th>

<th>Đặt cược hợp lệ</th>
<th>Tổng cược</th>

<th>Tổng tiền thưởng</th>
<!-- <th>Số trúng thưởng</th> -->
<!-- <th>Tổng số tiền thắng</th> -->
</tr>
</thead>
<tbody>
    
@else
 <th>STT</th> 
<!--<th>Hội viên</th>-->
<th>Đài</th>
<th>Thể loại</th>
<th>Thời gian</th>
<th>Số cược</th>
<th>Giá</th>
<th>Trả thưởng</th>
<th>Điểm</th>
<th>Thực thu</th>
<th>Thắng/Thua</th>
<th>Ghi chú</th>
<!-- <th>Số trúng thưởng</th> -->
<!-- <th>Tổng số tiền thắng</th> -->
</tr>
</thead>
<tbody>
@endif

        @foreach($xosorecords as  $stt =>   $xosorecord)
@if ($xosorecord->game_id == $key->game_code)

<?php
$haveData = true;
$total_bet_money += $xosorecord->total_bet_money;

if( $xosorecord->game_id > 1000){
    $total_bet_accept_money += $xosorecord->com;
}


// fix tra thuong
if ($xosorecord->total_win_money > 0 && $xosorecord->game_id < 1000) {
    if (
        $xosorecord->game_id == 15 ||
        $xosorecord->game_id == 16 ||
        $xosorecord->game_id == 316 ||
        $xosorecord->game_id == 416 ||
        $xosorecord->game_id == 516 ||
        $xosorecord->game_id == 616 ||
        $xosorecord->game_id == 115 ||
        $xosorecord->game_id == 116
    ) {
        $total_win_money += $xosorecord->total_win_money;
        // || $xosorecord->game_id == 16|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21
    } else {
        $total_win_money +=
            $xosorecord->total_win_money - $xosorecord->total_bet_money;
    }
} else {
    $total_win_money += $xosorecord->total_win_money;
}
$betnumber .= $xosorecord->bet_number . ",";
if ($xosorecord->exchange_rates != 0) {
    if (
        $xosorecord->game_id == 29 ||
        $xosorecord->game_id == 329 ||
        $xosorecord->game_id == 429 ||
        $xosorecord->game_id == 529 ||
        $xosorecord->game_id == 629 ||
        $xosorecord->game_id == 9 ||
        $xosorecord->game_id == 309 ||
        $xosorecord->game_id == 409 ||
        $xosorecord->game_id == 509 ||
        $xosorecord->game_id == 609 ||
        $xosorecord->game_id == 709 ||
        $xosorecord->game_id == 10 ||
        $xosorecord->game_id == 310 ||
        $xosorecord->game_id == 410 ||
        $xosorecord->game_id == 510 ||
        $xosorecord->game_id == 610 ||
        $xosorecord->game_id == 710 ||
        $xosorecord->game_id == 11 ||
        $xosorecord->game_id == 311 ||
        $xosorecord->game_id == 411 ||
        $xosorecord->game_id == 511 ||
        $xosorecord->game_id == 611 ||
        $xosorecord->game_id == 711 ||
        $xosorecord->game_id == 21 ||
        $xosorecord->game_id == 20 ||
        $xosorecord->game_id == 19
    ) {
        $fact = 1;
        switch ($xosorecord->game_id) {
            case 29:
            case 329:
            case 429:
            case 529:
            case 629:
            case 9:
            case 309:
            case 409:
            case 509:
            case 609:
            case 709:
                $fact = 2;
                break;
            case 10:
            case 310:
            case 410:
            case 510:
            case 610:
            case 710:
                $fact = 3;
                break;
            case 11:
            case 311:
            case 411:
            case 511:
            case 611:
            case 711:
                $fact = 4;
                break;
            case 21:
                $fact = 10;
                break;
            case 20:
                $fact = 8;
                break;
            case 19:
                $fact = 4;
                break;

            default:
                # code...

                break;
        }
        $countbetnumber = count(explode(",", $xosorecord->bet_number));
        $Ank =
            XoSoRecordHelpers::fact($countbetnumber) /
            XoSoRecordHelpers::fact($fact) /
            XoSoRecordHelpers::fact($countbetnumber - $fact);
        $betpoint =
            $xosorecord->total_bet_money / $xosorecord->exchange_rates / $Ank;

        $total_point += $betpoint;
    } else {
        $total_point +=
            $xosorecord->total_bet_money / $xosorecord->exchange_rates;
    }
}
?>

<tr>
 <td> {{ $stt+1 }}</td> 
<!--<td>$xosorecord->name</td>-->
@if ($xosorecord->locationslug>20)
    <td>{{GameHelpers::ChuyenDoiDaiByDate($xosorecord->locationslug,strtotime($xosorecord->created_at))}}</td>
@else
    <td>{{$xosorecord->location}}</td>
@endif
<td>{{$xosorecord->game}}</td>


<td>{{date("d-m-Y H:i:s", strtotime($xosorecord->created_at))}} @if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>
@if ( $key->game_code > 1000)
    <td>{{$xosorecord->SerialID}}</td>
    <td>{{$xosorecord->result}}</td>
@endif
@if ( $key->game_code < 1000)
<td>


<?php

$highlightbet = $xosorecord->bet_number;

$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
foreach ($arrbet as $bet) {
    if (strpos($xosorecord->win_number, $bet) !== false) {
        $highlightbet = str_replace($bet, "<b>" . $bet . "</b>", $highlightbet);
    }
}
echo $highlightbet;
if (
    $xosorecord->game_id == 18 ||
    $xosorecord->game_id == 9 ||
    $xosorecord->game_id == 10 ||
    $xosorecord->game_id == 11 ||
    $xosorecord->game_id == 29
) {
    echo "(" . (27 - $xosorecord->xien_id) . ")";
}
if (
    $xosorecord->game_id >= 100 &&
    $xosorecord->game_id <= 200 &&
    isset($xosorecord->xien_id) &&
    $xosorecord->xien_id <= 24
) {
    echo "(Kỳ " . $xosorecord->xien_id . ")";
}
?></td>
@endif
<!--<td>{{$xosorecord->bet_number}}</td>-->
@if ( $key->game_code < 1000)
<td class="text_center text-bold">{{number_format($xosorecord->exchange_rates,0)}}</td>

<td class="text_center text-bold">{{number_format($xosorecord->odds,0)}}</td>


<td class="text_center">
@if ( $key->game_code < 1000)

@if ($xosorecord->exchange_rates != 0)
@if($xosorecord->game_id==29 || $xosorecord->game_id==329 || $xosorecord->game_id==429 || $xosorecord->game_id==529 || $xosorecord->game_id==629 || $xosorecord->game_id==9 || $xosorecord->game_id==309 ||$xosorecord->game_id==409 ||$xosorecord->game_id==509||$xosorecord->game_id==609||$xosorecord->game_id==709 || $xosorecord->game_id==10 || $xosorecord->game_id==310 ||$xosorecord->game_id==410 ||$xosorecord->game_id==510||$xosorecord->game_id==610||$xosorecord->game_id==710 || $xosorecord->game_id==11 || $xosorecord->game_id==311 ||$xosorecord->game_id==411 ||$xosorecord->game_id==511||$xosorecord->game_id==611||$xosorecord->game_id==711
                                            || $xosorecord->game_id==21 || $xosorecord->game_id==20 || $xosorecord->game_id==19)
                                                <?php
                                                $fact = 1;
                                                switch ($xosorecord->game_id) {
                                                    case 29:
                                                    case 329:
                                                    case 429:
                                                    case 529:
                                                    case 629:
                                                    case 9:
                                                    case 309:
                                                    case 409:
                                                    case 509:
                                                    case 609:
                                                    case 709:
                                                        $fact = 2;
                                                        break;
                                                    case 10:
                                                    case 310:
                                                    case 410:
                                                    case 510:
                                                    case 610:
                                                    case 710:
                                                        $fact = 3;
                                                        break;
                                                    case 11:
                                                    case 311:
                                                    case 411:
                                                    case 511:
                                                    case 611:
                                                    case 711:
                                                        $fact = 4;
                                                        break;
                                                    case 21:
                                                        $fact = 10;
                                                        break;
                                                    case 20:
                                                        $fact = 8;
                                                        break;
                                                    case 19:
                                                        $fact = 4;
                                                        break;

                                                    default:
                                                        # code...

                                                        break;
                                                }
                                                $countbetnumber = count(
                                                    explode(
                                                        ",",
                                                        $xosorecord->bet_number
                                                    )
                                                );
                                                $Ank =
                                                    XoSoRecordHelpers::fact(
                                                        $countbetnumber
                                                    ) /
                                                    XoSoRecordHelpers::fact(
                                                        $fact
                                                    ) /
                                                    XoSoRecordHelpers::fact(
                                                        $countbetnumber - $fact
                                                    );
                                                $betpoint =
                                                    $xosorecord->total_bet_money /
                                                    $xosorecord->exchange_rates /
                                                    $Ank;
                                                ?>
                                                {{number_format($betpoint,0)}}
                                            @else
{{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}
@endif

<?php
// $total_point += $xosorecord->total_bet_money/$xosorecord->exchange_rates;
?>
@else
0
@endif
</td>
<!-- <td class="text_center text-bold">{{number_format($xosorecord->exchange_rates,0)}}</td> -->
@endif
@endif
@if ( $key->game_code > 1000)
    
    <td>{{number_format($xosorecord->com, 0)}}</td>
    
        

@endif
<td class=" text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td>

<?php
$win_money = $xosorecord->total_win_money;
// fix tra thuong
if ($win_money > 0 &&  $key->game_code < 1000) {
    if (
        $xosorecord->game_id == 15  ||
        $xosorecord->game_id == 16  ||
        $xosorecord->game_id == 316 ||
        $xosorecord->game_id == 416 ||
        $xosorecord->game_id == 516 ||
        $xosorecord->game_id == 616 ||
        $xosorecord->game_id == 115 ||
        $xosorecord->game_id == 116
    ) {
        //|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21  || $xosorecord->game_id == 16
    } else {
        $win_money -= $xosorecord->total_bet_money;
    }
}
?>

    @if($win_money>0)
        <td class="text_center text_bold">
            {{number_format($win_money,0)}}
        @elseif ($win_money<0)
        <td class="text_center text_bold" style=" color:red;">
            {{number_format($win_money,0)}}
        @elseif ($win_money==0)
        <td class="text_center text_bold">
            @if ( $key->game_code < 1000)
            Chưa xử lý
            @else
            0
            @endif
        @endif
</td>
@if ( $key->game_code < 1000)

<td>{{isset($xosorecord->ipaddr1)?$xosorecord->ipaddr1:"" }}</td>
<!-- <td>{{$xosorecord->total_win_money}}</td> -->
<!-- <td>{{number_format($xosorecord->total_win_money,0)}}</td> -->
</tr>
@endif
@endif
@endforeach
</tbody>

<tfoot>
<tr>
@if ( $key->game_code < 1000)

<td colspan="7" class="text_right pr10">Tổng cộng</td><td class="text_center pr10 suminvoice">{{number_format($total_point,0)}}</td>
<td class="text_center pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
<td class="text_center pr10 suminvoice"

@else

<td colspan="6" class="text_right pr10">Tổng cộng</td>
<td class="text_center pr10 suminvoice">{{number_format($total_bet_accept_money,0)}}</td>

<td class="text_center pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
<td class="text_center pr10 suminvoice"

@endif

@if ($total_win_money < 0)
style=" color:red;"
@endif
>{{number_format($total_win_money,0)}}</td>

</tr>
</tfoot>
</table>
</div>
</div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default waves-effect" data-bs-dismiss="modal">Đóng</button>
        <!-- <button type="button" class="btn btn-primary waves-effect waves-light">Save changes</button> -->
    </div>
</div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
@endforeach
