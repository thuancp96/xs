@extends('frontend.frontend-template')
@section('title','Bảng chuẩn')
@section('content')

<?php

use App\CustomerType_Game;
use App\Helpers\LiveCasinoHelpers;
use App\Helpers\SabaHelpers;
use App\Helpers\XoSoRecordHelpers;
use Illuminate\Support\Facades\Auth;

	$user = Auth::user();
	$newDate = date("Y-m-d");
	if (date('H') < 11)
	  $newDate=date("Y-m-d",strtotime('-1 day',strtotime($newDate)));
	$recordUser = XoSoRecordHelpers::GetByUserByDate($user,$newDate);

	// $recordUserBC = XoSoRecordHelpers::GetByUserByDate($user,$newDate);
	// print_r($recordUser);
	$somacuoc = count($recordUser);
	$thangthua = 0;
	$total = 0;
	// echo count($recordUser);
	foreach($recordUser as $record){
		if($record->locationslug==70 || $record->locationslug==80){
			if (isset($record->rawBet) && ($record->rawBet->paid != null || $record->rawBet->paid != 0)){
				$thangthua += $record->total_win_money;
			}else{
				$total+= $record->total_bet_money;
			}
		}else{
			if ($record->total_win_money == 0)
				$total+= $record->total_bet_money;
			else{
				if ( $record->total_win_money > 0){
					if ($record->game_id > 3000 || $record->game_id == 15 || $record->game_id == 16
					|| $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616
					||$record->game_id == 115|| $record->game_id == 116 ){
						$thangthua += $record->total_win_money;
					// || $xosorecord->game_id == 16|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21
					}else
						$thangthua += ($record->total_win_money-$record->total_bet_money);
				}else{
					// if ($record->game_id > 3000)
						// $thangthua += (0-$record->total_bet_money);
					$thangthua += $record->total_win_money;
				}
			}
		}
		if($record->locationslug==70 || $record->locationslug==80){
			$arrBonus = explode(",",$record->bonus);
			$bonus = end($arrBonus);
			if ($bonus > 0) 
				$thangthua += $bonus;
		}
		//$thangthua += $record->total_win_money;
	}

	// echo count($recordUser);
	// echo " ";
	// echo count($recordUserBC);

	// foreach($recordUser as $record){
	// 	if ($record->total_win_money == 0 && $record->game_id <= 7000)
	// 		$total+= $record->total_bet_money;

	// 	// if ( $record->total_win_money > 0){
	// 	// 	if ($record->game_id > 3000 || $record->game_id == 15 || $record->game_id == 16
	// 	// 	|| $record->game_id == 316 || $record->game_id == 416 || $record->game_id == 516 || $record->game_id == 616
	// 	// 	||$record->game_id == 115|| $record->game_id == 116 ){
	// 	// 		$thangthua += $record->total_win_money;
	// 	// 	// || $xosorecord->game_id == 16|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21
	// 	// 	}else
	// 	// 		$thangthua += ($record->total_win_money-$record->total_bet_money);
	// 	// }else{
	// 	// 	// if ($record->game_id > 3000)
	// 	// 		// $thangthua += (0-$record->total_bet_money);
	// 	// 	$thangthua += $record->total_win_money;
	// 	// }
				
	// 	//$thangthua += $record->total_win_money;
	// }
?>

<style>
	.my-account {
		margin-top: 32px;
		border-bottom: 1px solid #dadce0;
		padding-bottom: 16px;
	}
</style>
<div style="margin-left: auto;
    margin-right: auto;
	display: flex;
    align-items: center;
    justify-content: center;
	">
<div class="panel panel-color panel-inverse" style="max-width: 500px;min-width: 350px;" id="user_info">
	<div class="panel-heading recent-heading">
		<h6 class="panel-title">Thông tin tài khoản</h6>
	</div>
	<div class="panel-body">
		<div class="row my-account">
			<div class="col-6"><i class="fa fa-user"></i> Hội viên</div>
			<div class="col-6 text_bold">{{$current_user->name}}</div>
		</div>
		<div class="row my-account">
			<div class="col-6"><i class="glyphicon glyphicon-credit-card"></i> Tín dụng</div>
			<div class="col-6 text_bold text_red" id="total_money" data-toggle="tooltip" title="{{number_format($current_user->credit, 0)}} Chips">{{number_format($current_user->credit > 0 ? $current_user->credit : 0, 0)}}</div>
		</div>
		<div class="row my-account">
			<div class="col-6"><i class="glyphicon glyphicon-credit-card"></i> Số dư</div>
			<div class="col-6 text_bold text_red" id="total_money" data-toggle="tooltip" title="{{number_format($current_user->remain, 0)}} Chips">{{number_format($current_user->remain > 0 ? $current_user->remain : 0, 0)}}</div>
		</div>

		<div class="row my-account">
			<div class="col-6 col-6"><i class="glyphicon glyphicon-credit-card"></i> Đang cược</div>
			<div class="col-6 col-6 text_bold text_red" id="total_money" data-toggle="tooltip" title="{{number_format($total, 0)}} Chips">{{number_format($total, 0)}}</div>
		</div>
		<div class="row my-account">
			<div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Thắng thua</div>
			<div class="col-6 col-6 text_bold {{$thangthua > 0 ? 'text_blue' : 'text_red'}}" id="total_money" data-toggle="tooltip" title="{{number_format($thangthua, 0)}} Chips">{{number_format($thangthua, 0)}}</div>
		</div>
		<!-- <div class="row  my-account">
			<div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Số dư Saba</div>
			<div class="col-6 col-6 text_bold text_red gamebalancesaba" id="total_money_saba" data-toggle="tooltip" title="0 Chips">{{$current_user->remain_saba}}</div>
		</div> -->
		<!-- <div class="row  my-account">
			<div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Số dư Bóng đá 7zball</div>
			<div class="col-6 col-6 text_bold text_red gamebalance" id="total_money_bbin" data-toggle="tooltip" title="0 Chips">0</div>
		</div>

		<div class="row  my-account">
			<div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Số dư Minigame</div>
			<div class="col-6 col-6 text_bold text_red gamebalance" id="total_money_minigame" data-toggle="tooltip" title="0 Chips">0</div>
		</div> -->

		<!-- <div class="row  my-account">
			<div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Số tiền chuyển vào ví BBIN</div>
			<div class="col-6 col-6">
				<input type="text" class="form-control" id="moneyTrans" onkeypress='validate(event)' name="confirmpass" style="text-align: center; font-weight: bold" autocomplete="false" placeholder="Nhập số tiền cần chuyển" value="" data-parsley-minlength="6">

				<input data-slider-tooltip="hide" id="ex1" style="width: 100%" data-slider-id='ex1Slider' type="range" data-slider-min="0" data-slider-max="41100000" data-slider-step="1" data-slider-value="0" />
				<p class="card-text" style="float:left; display:inline-block;margin: 0;">0</p>
				<p class="card-text mainbalance" style="float:right;margin: 0; "></p>
			</div>
		</div> -->
	</div>
</div>
</div>
<script>
	!function(a){var b=/iPhone/i,c=/iPod/i,d=/iPad/i,e=/(?=.*\bAndroid\b)(?=.*\bMobile\b)/i,f=/Android/i,g=/(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)/i,h=/(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)/i,i=/IEMobile/i,j=/(?=.*\bWindows\b)(?=.*\bARM\b)/i,k=/BlackBerry/i,l=/BB10/i,m=/Opera Mini/i,n=/(CriOS|Chrome)(?=.*\bMobile\b)/i,o=/(?=.*\bFirefox\b)(?=.*\bMobile\b)/i,p=new RegExp("(?:Nexus 7|BNTV250|Kindle Fire|Silk|GT-P1000)","i"),q=function(a,b){return a.test(b)},r=function(a){var r=a||navigator.userAgent,s=r.split("[FBAN");return"undefined"!=typeof s[1]&&(r=s[0]),s=r.split("Twitter"),"undefined"!=typeof s[1]&&(r=s[0]),this.apple={phone:q(b,r),ipod:q(c,r),tablet:!q(b,r)&&q(d,r),device:q(b,r)||q(c,r)||q(d,r)},this.amazon={phone:q(g,r),tablet:!q(g,r)&&q(h,r),device:q(g,r)||q(h,r)},this.android={phone:q(g,r)||q(e,r),tablet:!q(g,r)&&!q(e,r)&&(q(h,r)||q(f,r)),device:q(g,r)||q(h,r)||q(e,r)||q(f,r)},this.windows={phone:q(i,r),tablet:q(j,r),device:q(i,r)||q(j,r)},this.other={blackberry:q(k,r),blackberry10:q(l,r),opera:q(m,r),firefox:q(o,r),chrome:q(n,r),device:q(k,r)||q(l,r)||q(m,r)||q(o,r)||q(n,r)},this.seven_inch=q(p,r),this.any=this.apple.device||this.android.device||this.windows.device||this.other.device||this.seven_inch,this.phone=this.apple.phone||this.android.phone||this.windows.phone,this.tablet=this.apple.tablet||this.android.tablet||this.windows.tablet,"undefined"==typeof window?this:void 0},s=function(){var a=new r;return a.Class=r,a};"undefined"!=typeof module&&module.exports&&"undefined"==typeof window?module.exports=r:"undefined"!=typeof module&&module.exports&&"undefined"!=typeof window?module.exports=s():"function"==typeof define&&define.amd?define("isMobile",[],a.isMobile=s()):a.isMobile=s()}(this);
</script>
@endsection
