<?php
use App\Helpers\MinigameHelpers;
?>
@if(count($xosorecords)>0)

<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
<thead>
<tr><!-- <th></th> -->
<th>Đài</th>
<!-- <th>Thể loại</th> -->
<!--<th>Thời gian</th>-->
<th>Số cược</th>
<!-- <th>Điểm</th> -->
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
$all_total_bonus = 0;
$all_total_point = 0;
?>

@foreach (LocationHelpers::getTopLocation() as $keyLocation)
<?php
$haveData = false;
$total_bet_money = 0;
$total_bet_real_money = 0;

$total_win_money = 0;
$total_point = 0;
$total_bonus = 0;
$betnumber = "";
$game_id = "";
$countrecord = 0;
$location_name = "";
?>
@foreach($xosorecords as $stt => $xosorecord)

@if ($xosorecord->locationslug == $keyLocation->slug)

<?php
// print_r($xosorecord->locationslug);
$location_name = $xosorecord->location;
$countrecord++;
$haveData = true;
if ($xosorecord->locationslug == 60)
$total_bet_money += $xosorecord->total_bet_money * 1000;
else
$total_bet_money += $xosorecord->total_bet_money;

if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
$arrBonus = explode(",", $xosorecord->bonus);
$bonus = end($arrBonus);
$total_bonus += $bonus;
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
// || $xosorecord->game_id == 16
$total_win_money += $xosorecord->total_win_money;
} else {
$total_win_money +=
$xosorecord->total_win_money - $xosorecord->total_bet_money;
}
} else {
if ($xosorecord->locationslug == 60)
$total_win_money += $xosorecord->total_win_money * 1000;
else
$total_win_money += $xosorecord->total_win_money;
// $total_win_money += $xosorecord->total_win_money;
}
// $total_win_money += $xosorecord->total_win_money;

$highlightbet = $xosorecord->bet_number;

$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
foreach ($arrbet as $bet) {
if (empty($bet)) continue;
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
if ($xosorecord->game_id > 4000 && $xosorecord->game_id < 5000)
$total_point += $xosorecord->total_bet_money;
else
$total_point +=
$xosorecord->total_bet_money / $xosorecord->exchange_rates;
}
}
?>
@endif
@endforeach

@if($haveData)

<tr><!-- <td>Chi tiết</td> --><td><button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" href="#full-width-modal-l-{{$keyLocation->slug}}" style="font-size: 1em;">{{$location_name}}</button></td><td>{{$countrecord}}</td><!-- <td class="text_center text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_point)}}</td> --><td class="text_center text-bold" @if ($total_bet_money<0)style=" color:red;" @endif>{{number_format($total_bet_money)}}</td><td class="text_center text-bold" @if ($total_win_money < 0) style="color:red; text-align: right;" @else style="text-align: right;" @endif>{{number_format($total_win_money)}} @if($total_bonus>0)<br><span style="color:black !important;">{{number_format($total_bonus)}}</span>@endif</td></tr>
<?php

$all_total_bet_money += $total_bet_money;
$all_total_win_money += ($total_win_money + $total_bonus);
$all_total_point += $total_point;
?>
@endif
@endforeach
</tbody>
<tfoot><tr><td colspan="2" class="text_right pr10">Tổng cộng</td><!-- <td class="text_center pr10 suminvoice text-bold" @if ($all_total_point<0)style=" color:red;" @endif>{{number_format($all_total_point,0)}}</td> --><td class="text_center pr10 suminvoice text-bold" @if ($all_total_bet_money<0)style=" color:red;" @endif>{{number_format($all_total_bet_money,0)}}</td><td class="text_right pr10 suminvoice text-bold" @if ($all_total_win_money < 0)style=" color:red;" @endif>{{number_format($all_total_win_money,0)}}</td></tr>
</tfoot>
</table>

@foreach (LocationHelpers::getTopLocation() as $keyLocation)
<div id="full-width-modal-l-{{$keyLocation->slug}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog modal-full">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title" style="color:white" id="exampleModalLabel">Chi tiết đài {{$keyLocation->name}}</h6>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

<div class="table-rep-plugin">
<div class="table-responsive">
<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important;">
<thead>
<tr>
<!-- <th></th> -->
<!-- <th>Đài</th> -->
<th>Thể loại</th>
<!--<th>Thời gian</th>-->
<th>Số cược</th>
@if($keyLocation->slug==70 || $keyLocation->slug==80)
<!-- <th>Hoa hồng</th> -->
@else
<th>Điểm</th>
@endif
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
$all_total_bonus = 0;
// print_r(GameHelpers::GetAllGame());
?>
@foreach (GameHelpers::GetAllGame() as $key)
<?php
$haveData = false;
$total_bonus = 0;
$total_bet_money = 0;
$total_bet_real_money = 0;

$total_win_money = 0;
$total_point = 0;
$total_bonus = 0;
$betnumber = "";
$game_id = "";
$countrecord = 0;
$location_name = "";
?>

@foreach($xosorecords as $stt => $xosorecord)

@if ($xosorecord->game_id == $key->game_code && $xosorecord->locationslug == $keyLocation->slug)


<?php
$location_name = $xosorecord->location;
$countrecord++;
$haveData = true;
// $total_bet_money += $xosorecord->total_bet_money;

if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
$arrBonus = explode(",", $xosorecord->bonus);
$total_bonus += end($arrBonus);
}
if ($xosorecord->locationslug == 60)
$total_bet_money += $xosorecord->total_bet_money * 1000;
else
$total_bet_money += $xosorecord->total_bet_money;

if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
$arrBonus = explode(",", $xosorecord->bonus);
$bonus = end($arrBonus);
$total_win_money += $bonus;
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
// || $xosorecord->game_id == 16
$total_win_money += $xosorecord->total_win_money;
} else {
$total_win_money +=
$xosorecord->total_win_money - $xosorecord->total_bet_money;
}
} else {
if ($xosorecord->locationslug == 60)
$total_win_money += $xosorecord->total_win_money * 1000;
else
$total_win_money += $xosorecord->total_win_money;
// $total_win_money += $xosorecord->total_win_money;
}
// $total_win_money += $xosorecord->total_win_money;

$highlightbet = $xosorecord->bet_number;

$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
foreach ($arrbet as $bet) {
if (empty($bet)) continue;
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

<tr>
<!-- <td>Chi tiết</td> -->
<!-- <td>{{$location_name}}</td> -->
<td><button class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" href="#full-width-modal{{$key->game_code}}" style="font-size: 1em;">{{$key->name}}</button></td>
<td>{{$countrecord}}</td>
@if($keyLocation->slug == 70 || $keyLocation->slug == 80)
<!-- <td class="text_center text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_bonus)}}</td> -->
@else
<td class="text_center text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_point)}}</td>
@endif
<td class="text_center text-bold" @if ($total_bet_money<0)style=" color:red;" @endif>{{number_format($total_bet_money)}}</td>
<td class="text_center text-bold" @if ($total_win_money < 0) style="color:red; text-align: right;" @else style="text-align: right;" @endif>{{number_format($total_win_money-$total_bonus)}}
@if($total_bonus>0)<br><span style="color:black !important;">{{number_format($total_bonus)}}</span>@endif


<!-- @if($total_bonus>0) <br> ({{$total_bonus}})@endif -->
</td>
</tr>
<?php

$all_total_bet_money += $total_bet_money;
$all_total_win_money += $total_win_money;
$all_total_point += $total_point;
$all_total_bonus += $total_bonus;
?>
@endif
@endforeach
</tbody>
<tfoot>
<tr>
<td colspan="2" class="text_right pr10">Tổng cộng</td>
@if($keyLocation->slug == 70 || $keyLocation->slug == 80)
<!-- <td class="text_center pr10 suminvoice text-bold" @if ($all_total_bonus<0)style=" color:red;" @endif>{{number_format($all_total_bonus,0)}}</td> -->
@else
<td class="text_center pr10 suminvoice text-bold" @if ($all_total_point<0)style=" color:red;" @endif>{{number_format($all_total_point,0)}}</td>
@endif
<td class="text_center pr10 suminvoice text-bold" @if ($all_total_bet_money<0)style=" color:red;" @endif>{{number_format($all_total_bet_money,0)}}</td>
<td class="text_right pr10 suminvoice text-bold" @if ($all_total_win_money < 0)style=" color:red;" @endif>{{number_format($all_total_win_money,0)}}</td>
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
@else
Không có sao kê trong thời gian này
@endif

@foreach (GameHelpers::GetAllGame() as $key)
<?php
$haveData = false;
$total_bet_money = 0;
$total_bet_accept_money = 0;
$total_win_money = 0;
$total_bonus = 0;
$total_point = 0;
$betnumber = "";
// echo $key->location_id . '.';
// var_dump($key);
?>

<div id="full-width-modal{{$key->game_code}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog modal-full">
<div class="modal-content">
<div class="modal-header">
<h6 class="modal-title" style="color:white" id="exampleModalLabel">Chi tiết cược {{$key->short_name}} - {{$key->location}}</h6>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

<div class="table-rep-plugin">
<div class="table-responsive">
<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer" style="font-size: 12px !important; min-width: 800px;">
<thead>
<tr>
@if ( $key->game_code > 1000)
<th>STT</th>
<!--<th>Hội viên</th>-->
<!-- <th>Đài</th> -->
<!-- <th>Thể loại</th> -->
<th>Thời gian</th>
<th>Mã ván</th>
<th>Trạng thái</th>
@if ($key->game_code > 4000)
<th>Tỷ lệ cược</th>
<th>Tiền cược</th>

<th>Thắng thua</th>
@else
<th>Đặt cược hợp lệ</th>
<th>Tổng cược</th>

<th>Tổng tiền thưởng</th>
@endif
<!-- <th>Số trúng thưởng</th> -->
<!-- <th>Tổng số tiền thắng</th> -->
</tr>
</thead>
<tbody>

@else
<th>STT</th>
<!--<th>Hội viên</th>-->
<!-- <th>Đài</th> -->
<!-- <th>Thể loại</th> -->
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

@foreach($xosorecords as $stt => $xosorecord)
@if ($xosorecord->game_id == $key->game_code)

<?php

$haveData = true;
if ($xosorecord->locationslug == 60)
$total_bet_money += $xosorecord->total_bet_money * 1000;
else
$total_bet_money += $xosorecord->total_bet_money;
// $total_bet_money += $xosorecord->total_bet_money;

if ($xosorecord->game_id > 1000) {
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
if ($xosorecord->locationslug == 60)
$total_win_money += $xosorecord->total_win_money * 1000;
else
$total_win_money += $xosorecord->total_win_money;
// $total_win_money += $xosorecord->total_win_money;
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
@if ($xosorecord->locationslug>20 && $xosorecord->locationslug !=50 && $xosorecord->locationslug !=60 && $xosorecord->locationslug !=70 && $xosorecord->locationslug !=80)
<td>{{GameHelpers::ChuyenDoiDaiByDate($xosorecord->locationslug,strtotime($xosorecord->created_at))}}</td>
@else
<td hidden>{{$xosorecord->location}}</td>
@endif
<td hidden>{{$xosorecord->game}}</td>


<td>{{date("d-m H:i", strtotime($xosorecord->created_at))}} @if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>

@if ($key->game_code >= 7000 && $key->game_code < 8000) 
<td style="text-align: left;">
@if(isset($xosorecord->rawBet))
@if($xosorecord->rawBet->bet_type != "parlay")
@if($xosorecord->rawBet->bet_type == "outright")
<span style="color:black; font-weight:700;">Chung cuộc</span>
<br>
@endif
<span style="color:#2596be; font-weight:700;"> {{$xosorecord->rawBet->bet_type_txt}}</span>
<br>
<b>{{$xosorecord->rawBet->bet_on_txt}}</b> <b>@ {{number_format(isset($xosorecord->rawBet->bet_odd) ? $xosorecord->rawBet->bet_odd : 0, 2)}} @if(str_contains($xosorecord->rawBet->bet_type,"#my")) MY @else DEC @endif @if (isset($xosorecord->rawBet->bet_match_current)) ({{XoSoRecordHelpers::converScoreMatch($xosorecord->rawBet->bet_type,$xosorecord->rawBet->bet_match_current)}}) @endif</b>
<br>
@if($xosorecord->rawBet->bet_type != "outright")
{{$xosorecord->rawBet->m_tnHomeName}} vs {{$xosorecord->rawBet->m_tnAwayName}}
<br>
<b>{{isset($xosorecord->rawBet->m_tnName) ? $xosorecord->rawBet->m_tnName : ""}}</b>
<br>
{{isset($xosorecord->betTime) && $xosorecord->betTime != "" ? $xosorecord->betTime : (isset($xosorecord->rawBet->kickoffVN) ? $xosorecord->rawBet->kickoffVN : "")}}
<?php
if (isset($xosorecord->rawBet->bet_match_current)) {
$bet_match_current = json_decode($xosorecord->rawBet->bet_match_current);
// echo " (" . str_replace("-", " vs ", $bet_match_current->score) . ")";
}
?>

@if(isset($xosorecord->rawBet->detail))
<br>
<span style="font-weight:700;">
Kết quả: 
<?php $bet_type = $xosorecord->rawBet->bet_type; $detailMatch = json_decode($xosorecord->rawBet->detail);?>
@if(str_contains($bet_type,"#cr"))

    @if(str_contains($bet_type,"_1st"))
    <?php $propertyhtcr = "ht-cr"; ?>
    {{"Phạt góc " . (isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtcr) : "0 vs 0")}} Hiệp 1
    <!-- </div> -->
    @else
    {{"Phạt góc " . (isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->cr) : "0 vs 0")}}
    @endif

@endif

@if(str_contains($bet_type,"#redCard"))
    @if(str_contains($bet_type,"_1st"))
        <?php $propertyhtRedCard = "ht-red-card"; $propertyhtYellowCard = "ht-yellow-card"; ?>
        @if(isset($detailMatch->$propertyhtRedCard)) Thẻ đỏ <span>  {{(isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtRedCard) : "0 vs 0")}}</span> @endif
        &nbsp;
        @if(isset($detailMatch->$propertyhtYellowCard)) Thẻ vàng <span> {{(isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtYellowCard) : "0 vs 0")}}</span> @endif Hiệp 1
        <!-- </div> -->
    @else
        <?php $propertyRedCard = "red-card"; $propertyYellowCard = "yellow-card"; ?>
        @if(isset($detailMatch->$propertyRedCard)) Thẻ đỏ <span> {{(isset($detailMatch->$propertyRedCard) ? str_replace("-"," vs ", $detailMatch->$propertyRedCard) : "0 vs 0")}}</span> @endif
        &nbsp;
        @if(isset($detailMatch->$propertyYellowCard)) Thẻ vàng <span>{{(isset($detailMatch->$propertyYellowCard) ? str_replace("-"," vs ", $detailMatch->$propertyYellowCard) : "0 vs 0")}}</span> @endif
    @endif
@endif

<!-- pk -->
@if(str_contains($bet_type,"pk"))
    {{isset($detailMatch) ? str_replace("-"," PK ", $detailMatch->pk) : "0 PK 0"}}
    <!-- </div> -->
@endif

<!-- pk -->
@if(str_contains($bet_type,"ot"))
    Hiệp phụ {{isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->ot) : "0 vs 0"}}
    <!-- </div> -->
@endif
<!-- score -->
@if(!str_contains($bet_type,"#cr") && !str_contains($bet_type,"#redCard") && !str_contains($bet_type,"pk") && !str_contains($bet_type,"ot"))
    @if( str_contains($bet_type,"_1st"))
        <?php $propertyhtscore = "ht-score"; ?>
        {{isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtscore) : "0 vs 0"}} Hiệp 1
        <!-- </div> -->
    @else
        {{isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->score) : "0 vs 0"}}
    @endif
@endif
</span>
@endif
@endif
@else
<?php
$parlay = json_decode($xosorecord->rawBet->parlay);
$detailMatchOnBetLst = isset($xosorecord->rawBet->bet_match_current) ? json_decode($xosorecord->rawBet->bet_match_current) : null;
$parlay_match_result = isset($xosorecord->rawBet->parlay_match_result) ? json_decode($xosorecord->rawBet->parlay_match_result) : null;
$bet_data_lst = json_decode($xosorecord->rawBet->bet_data);
// var_dump($bet_data_lst);
$bet_ons = json_decode($xosorecord->rawBet->parlay_money);
$countBet = 0;
$strBet_on = "";
foreach ($bet_ons as $bet_on) {
if ($bet_on->money == 0) continue;
$countBet++;
if (isset($bet_on->nameParlay))
$strBet_on .= ($bet_on->nameParlay . " ");
// var_dump($bet_on);
// echo $bet_on->nameParlay;
}
// return;
?>
<span style="color:#2596be; font-weight:700;"> {{$xosorecord->rawBet->bet_type_txt}} {{$strBet_on}}</span>
<br>
@foreach($parlay as $parlayOne)
<?php
$match_id = $parlayOne->match_id;
$bet_type = $parlayOne->betting_type_id;
$match_result = isset($parlay_match_result->$match_id) ? $parlay_match_result->$match_id : null;
$detailMatchOnBet = isset($detailMatchOnBetLst) ? json_decode($detailMatchOnBetLst->$match_id) : null;
?>
<span>{{$parlayOne->betting_type}}</span>
<br>
<span>{{$parlayOne->betting_tournament}}</span>
<br>
<span>{{$parlayOne->betting_homeName}} vs {{$parlayOne->betting_awayName}}</span>
<br>
<span>{{XoSoRecordHelpers::converBetOnParlay($parlayOne,$bet_data_lst->$match_id)}}</span>
<span>
@if(isset($parlayOne->betting_odd))
                                    {{"@".(isset($parlayOne->betting_odd) ? $parlayOne->betting_odd : "")}}
                                @else
                                    <?php
                                        if(isset($parlayOne->betting_k_id)){
                                            switch ($parlayOne->betting_k_id) {
                                                case 'od':
                                                    echo "Lẻ";
                                                    break;
                                                case 'ev':
                                                    echo "Chẵn";
                                                    break;
                                                default:
                                                    echo $parlayOne->betting_k_id;
                                                    break;
                                            }
                                        }
                                    ?>
                                @endif

</span>
<span>({{isset($detailMatchOnBet) ? ( (str_contains($bet_type,"#cr") ? ("Phạt góc " . str_replace("-"," vs ", $detailMatchOnBet->cr)) : str_replace("-"," vs ", $detailMatchOnBet->score))) : (str_contains($bet_type,"#cr") ? "Phạt góc 0 vs 0" : "0 vs 0" )}})</span>
<br>
@if(isset($detailMatchOnBet))
<span>Thời Gian Đặt Cược {{isset($detailMatchOnBet) ? XoSoRecordHelpers::converTimeMatch($detailMatchOnBet) : "Hiệp 1 00:00"}} </span>
@endif

@if(isset($match_result))
<br>
<span style="font-weight:700;">
Kết quả: 
<?php $detailMatch = $match_result;?>
@if(str_contains($bet_type,"#cr"))

    @if(str_contains($bet_type,"_1st"))
    <?php $propertyhtcr = "ht-cr"; ?>
    {{"Phạt góc " . (isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtcr) : "0 vs 0")}} Hiệp 1
    <!-- </div> -->
    @else
    {{"Phạt góc " . (isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->cr) : "0 vs 0")}}
    @endif

@endif

@if(str_contains($bet_type,"#redCard"))
    @if(str_contains($bet_type,"_1st"))
        <?php $propertyhtRedCard = "ht-red-card"; $propertyhtYellowCard = "ht-yellow-card"; ?>
        @if(isset($detailMatch->$propertyhtRedCard)) Thẻ đỏ <span>  {{(isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtRedCard) : "0 vs 0")}}</span> @endif
        &nbsp;
        @if(isset($detailMatch->$propertyhtYellowCard)) Thẻ vàng <span> {{(isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtYellowCard) : "0 vs 0")}}</span> @endif Hiệp 1
        <!-- </div> -->
    @else
        <?php $propertyRedCard = "red-card"; $propertyYellowCard = "yellow-card"; ?>
        @if(isset($detailMatch->$propertyRedCard)) Thẻ đỏ <span> {{(isset($detailMatch->$propertyRedCard) ? str_replace("-"," vs ", $detailMatch->$propertyRedCard) : "0 vs 0")}}</span> @endif
        &nbsp;
        @if(isset($detailMatch->$propertyYellowCard)) Thẻ vàng <span>{{(isset($detailMatch->$propertyYellowCard) ? str_replace("-"," vs ", $detailMatch->$propertyYellowCard) : "0 vs 0")}}</span> @endif
    @endif
@endif

<!-- pk -->
@if(str_contains($bet_type,"pk"))
    {{isset($detailMatch) ? str_replace("-"," PK ", $detailMatch->pk) : "0 PK 0"}}
    <!-- </div> -->
@endif

@if(str_contains($bet_type,"ot"))
    Hiệp phụ {{isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->ot) : "0 vs 0"}}
    <!-- </div> -->
@endif

<!-- score -->
@if(!str_contains($bet_type,"#cr") && !str_contains($bet_type,"#redCard") && !str_contains($bet_type,"pk"))
    @if( str_contains($bet_type,"_1st"))
        <?php $propertyhtscore = "ht-score"; ?>
        {{isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->$propertyhtscore) : "0 vs 0"}} Hiệp 1
        <!-- </div> -->
    @else
        {{isset($detailMatch) ? str_replace("-"," vs ", $detailMatch->score) : "0 vs 0"}}
    @endif
@endif
</span>
@endif

<br>
<span>-----</span>
<br>
@endforeach

@foreach ($bet_ons as $bet_on)
<?php if ($bet_on->money == 0) continue; ?>
<span class="flex items-center space-x-[5px] whitespace-nowrap"><span>Đặt Cược: {{$bet_on->nameParlay}} @if(isset($bet_on->w_parlay)) (<span style="color:red;">{{$bet_on->ank}}</span>)(<span style="color:green;">{{$bet_on->w_parlay}}</span>) @endif</span>
<span class="flex text-sm text-primary">{{number_format($bet_on->money)}}</span>
</span>
<br>
@endforeach
@endif
@endif
</td>
<td>
@if(isset($xosorecord->rawBet))
{{$xosorecord->rawBet->result_name}}
@endif
</td>
@endif

@if ($key->game_code >= 8000 && $key->game_code < 9000) 
<td>{{isset($xosorecord->rawBet->game_result_id) ? ("Mã ván " . $xosorecord->rawBet->game_result_id) : ""}} <br> {{MinigameHelpers::convertGametype($xosorecord->rawBet->choice,$xosorecord->game_id) }}</td>
<td>{{$xosorecord->rawBet->resultTxt}}</td>
@endif

@if ( $key->game_code > 1000 && $key->game_code < 4000) <td>{{$xosorecord->SerialID}}</td>
<td>{{$xosorecord->result}}</td>
@endif
@if ( $key->game_code < 1000) <td>


<?php

$highlightbet = $xosorecord->bet_number;

$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
foreach ($arrbet as $bet) {
if (empty($bet)) continue;
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
@if ( $key->game_code < 1000) <td class="text_center text-bold">{{number_format($xosorecord->exchange_rates,0)}}</td>

<td class="text_center text-bold">{{number_format($xosorecord->odds,0)}}</td>


<td class="text_center">
@if ( $key->game_code < 1000) @if ($xosorecord->exchange_rates != 0)
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

@if ($key->game_code > 4000)
@if ($key->game_code > 5000 && $key->game_code < 6000) 
<td>{{number_format($xosorecord->rawBet->txns[0]->detail[0]->odds, 2)}}</td>
@else
@if ($key->game_code >= 7000 && $key->game_code < 8000) 
@if(isset($xosorecord->rawBet))
<td @if($xosorecord->rawBet->bet_odd <= 0) style="color:red;" @endif>{{number_format(isset($xosorecord->rawBet->bet_odd) ? $xosorecord->rawBet->bet_odd : 0, 2)}}
@if(str_contains($xosorecord->rawBet->bet_type,"#my"))
<br>
MY
@else
<br>
DEC
@endif
</td>
@else
<td></td>
@endif

@else
@if ($key->game_code > 8000) 
<td>{{number_format(isset($xosorecord->rawBet->odd) ? $xosorecord->rawBet->odd : 0, 2)}}</td>
@else
<td>{{number_format(isset($xosorecord->rawBet->odds) ? $xosorecord->rawBet->odds : 0, 2)}}</td>
@endif
@endif
@endif
@else
<td>{{number_format($xosorecord->com, 0)}}</td>
@endif
@endif

@if ($xosorecord->locationslug == 60)
<td class=" text-bold">{{number_format($xosorecord->total_bet_money*1000,0)}}</td>
@else
<td class=" text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td>
@endif


<?php
$win_money = $xosorecord->total_win_money;
if ($xosorecord->locationslug == 60)
$win_money = $xosorecord->total_win_money * 1000;
else
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
<td class="text_center text_bold" style="text-align: right;">{{number_format($win_money,0)}}
@elseif ($win_money<0) <td class="text_center text_bold" style=" color:red;text-align: right;">{{number_format($win_money,0)}}
@elseif ($win_money==0)
<td class="text_center text_bold" style="text-align: right;">
@if ( $key->game_code < 1000) Chưa xử lý @else 0 @endif @endif @if($xosorecord->locationslug==70 || $xosorecord->locationslug==80)

<?php
$arrBonus = explode(",", $xosorecord->bonus);
$bonus = end($arrBonus);
// var_dump($bonus);
if ($bonus > 0) echo '<br><span style="color:black !important;">' . number_format($bonus) . '</span>';
$total_bonus += $bonus;
?>

@endif
</td>
@if ( $key->game_code < 1000) <td>{{isset($xosorecord->ipaddr)?$xosorecord->ipaddr:"" }}</td>
<!-- <td>{{$xosorecord->total_win_money}}</td> -->
<!-- <td>{{number_format($xosorecord->total_win_money,0)}}</td> -->
</tr>
@endif
@endif
@endforeach
</tbody>

<tfoot>
<tr>
@if ( $key->game_code < 1000) <td colspan="5" class="text_right pr10">Tổng cộng</td>
<td class="text_center pr10 suminvoice">{{number_format($total_point,0)}}</td>
<td class="text_center pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
<td class="text_center pr10 suminvoice" @else <td colspan="4" class="text_right pr10">Tổng cộng</td>
<td class="text_center pr10 suminvoice">{{number_format($total_bet_accept_money,0)}}</td>

<td class="text_center pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
<td class="text_right pr10 suminvoice" @endif @if ($total_win_money < 0) style="color:red; text-align: right;" @else style="text-align: right;" @endif>{{number_format($total_win_money,0)}} @if($total_bonus>0)<br><span style="color:black !important;">{{number_format($total_bonus)}}</span>@endif</td>

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