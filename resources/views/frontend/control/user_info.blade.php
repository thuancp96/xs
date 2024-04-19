<?php

use App\CustomerType_Game;
use App\Helpers\LiveCasinoHelpers;
use App\Helpers\SabaHelpers;
use App\Helpers\XoSoRecordHelpers;

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
<!-- <div class="panel-heading recent-heading">
		<h6 class="panel-title">Thông tin tài khoản</h6>
</div>
<div class="panel-body">
	<div class="row">
		<div class="col-6"><i class="fa fa-user"></i> Hội viên</div>
		<div class="col-6 text_bold">{{$user->name}}</div>
	</div>
	
	<div class="row">
		<div class="col-6"><i class="glyphicon glyphicon-credit-card"></i> Hạn mức còn lại</div>
		<div class="col-6 text_bold text_red" id="total_money" data-toggle="tooltip" title="{{number_format($user->remain, 0)}} Chips">{{number_format($user->remain > 0 ? $user->remain : 0, 0)}}</div>
	</div>
	<div class="row">
		<div class="col-6 col-6"><i class="glyphicon glyphicon-credit-card"></i> Đang cược</div>
		<div class="col-6 col-6 text_bold text_red" id="total_money" data-toggle="tooltip" title="{{number_format($total, 0)}} Chips">{{number_format($total, 0)}}</div>
	</div>
	<div class="row">
		<div class="col-6 col-6"><i class="glyphicon glyphicon-th"></i> Thắng thua</div>
		<div class="col-6 col-6 text_bold {{$thangthua > 0 ? 'text_blue' : 'text_red'}}" id="total_money" data-toggle="tooltip" title="{{number_format($thangthua, 0)}} Chips">{{number_format($thangthua, 0)}}</div>
	</div>
</div> -->

<script>
	userName = "{!!$user->name!!}"
	userStatusLockPre = localStorage.getItem(userName+'userStatusLock')
	userStatusLockNow = {!!$user->lock!!}

	userCustomertypePre = localStorage.getItem(userName+'customertype')
	userCustomertypeNow = "{!!$user->customer_type!!}"
	// console.log(userStatusLockNow)
	localStorage.setItem(userName+'userStatusLock', userStatusLockNow)
	localStorage.setItem('current_user', userName)
	localStorage.setItem(userName+'customertype', userCustomertypeNow)
	if (userCustomertypePre != null && userCustomertypePre != userCustomertypeNow){ 
		setTimeout(()=>{
            location.reload()
        },3000);
		
	} 
	if (userStatusLockPre != userStatusLockNow && userStatusLockNow != 0){
		
		if (userStatusLockNow == 1){
			swal({
				title: "Cảnh báo tài khoản.",
				text: "Tài khoản của bạn đã ngừng đặt. Hãy liên hệ quản lý để mở.",
				type: "info",
				timer: 10000,
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Đã hiểu",
				// cancelButtonText: "Nhập lại",
				closeOnConfirm: true
			}, 
			function (dismiss) {
				if (dismiss === null) {
					// Huy();
					swal.close();
					location.reload()
					return;
				}
				if (dismiss) {
					// Action()
					location.reload()
				} else {
					// Huy();
					swal.close();
					location.reload()
					return;
				}
			}
			,function(isConfirm) {
				
			});
		}

		if (userStatusLockNow >= 2){
			swal({
				title: "Cảnh báo tài khoản.",
				text: "Tài khoản của bạn đã bị khóa. Hãy liên hệ quản lý để mở.",
				type: "info",
				timer: 10000,
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Đã hiểu",
				// cancelButtonText: "Nhập lại",
				closeOnConfirm: true
			}, 
			function (dismiss) {
				if (dismiss === null) {
					// Huy();
					swal.close();
					location.href = "/logout"
					return;
				}
				if (dismiss) {
					// Action()
					location.href = "/logout"
				} else {
					// Huy();
					swal.close();
					location.href = "/logout"
					return;
				}
			}
			,function(isConfirm) {
				
			});
		}
		// return;
		// showAlertUserStatusLock = showAlertUserStatusLock + 1
	}

	// alert(window.location.href)
	@if ($user->roleid==61)
	if (window.location.href.indexOf("bbin") != -1){
		<?php
			$a =  CustomerType_Game::where('code_type',$user->customer_type)
                ->where('game_id',3038)
                ->where('created_user',$user->id)->first();
			$balance = LiveCasinoHelpers::CheckUsrBalance($user->name);
			// if ($balance > $a->max_point_one)
			// 	LiveCasinoHelpers::Logout($user->username);
		?>
		a =  {!!$a->max_point_one!!}
        // moneyr = {!!$user->remain!!}
		balance = {!!$balance!!}
		userStatusLockPre = localStorage.getItem('popupUserBBinLock')
        if(balance > a && userStatusLockPre == 0){
			// alert("Tài khoản của bạn đã vượt quá số tiền giới hạn BBIN. Hãy rút bớt tiền ra.")
            // return "Không đủ số dư";
			localStorage.setItem('popupUserBBinLock', 1)
			swal({
				title: "Cảnh báo tài khoản.",
				text: "Tài khoản của bạn đã vượt quá số tiền giới hạn BBIN. Hãy rút tối thiểu: " + number_format(balance-a),
				type: "info",
				timer: 10000000,
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Đã hiểu",
				// cancelButtonText: "Nhập lại",
				closeOnConfirm: true
			}, 
			function (dismiss) {
				localStorage.setItem('popupUserBBinLock', 0)
				if (dismiss === null) {
					// Huy();
					swal.close();
					location.href = "/thongtintk"
					$.get("/api/bbin/logout", function(data, status) {})
					return;
				}
				if (dismiss) {
					// Action()
					$.get("/api/bbin/logout", function(data, status) {})
					location.href = "/thongtintk"
				} else {
					// Huy();
					swal.close();
					$.get("/api/bbin/logout", function(data, status) {})
					location.href = "/thongtintk"
					return;
				}
			}
			,function(isConfirm) {
				localStorage.setItem('popupUserBBinLock', 0)
				$.get("/api/bbin/logout", function(data, status) {})
				location.href = "/thongtintk"
			});
		}
	}

	if (window.location.href.indexOf("saba") != -1){
		<?php
			$a =  CustomerType_Game::where('code_type',$user->customer_type)
                ->where('game_id',4001)
                ->where('created_user',$user->id)->first();
			$balance = SabaHelpers::CheckUsrBalance($user->name);
			// if ($balance > $a->max_point_one)
			// 	LiveCasinoHelpers::Logout($user->username);
		?>
		a =  {!!$a->max_point_one!!}
        // moneyr = {!!$user->remain!!}
		balance = {!!$balance!!}
		userStatusLockPre = localStorage.getItem('popupUserSabaLock')
        if(balance > a && userStatusLockPre == 0){
			// alert("Tài khoản của bạn đã vượt quá số tiền giới hạn BBIN. Hãy rút bớt tiền ra.")
            // return "Không đủ số dư";
			localStorage.setItem('popupUserSabaLock', 1)
			swal({
				title: "Cảnh báo tài khoản.",
				text: "Tài khoản của bạn đã vượt quá số tiền giới hạn Saba. Hãy rút tối thiểu: " + number_format(balance-a),
				type: "info",
				timer: 10000000,
				showCancelButton: false,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Đã hiểu",
				// cancelButtonText: "Nhập lại",
				closeOnConfirm: true
			}, 
			function (dismiss) {
				localStorage.setItem('popupUserSabaLock', 0)
				if (dismiss === null) {
					// Huy();
					swal.close();
					// location.href = "/thongtintk"
					$.get("/api/saba/logout", function(data, status) {})
					return;
				}
				if (dismiss) {
					// Action()
					$.get("/api/saba/logout", function(data, status) {})
					// location.href = "/thongtintk"
				} else {
					// Huy();
					swal.close();
					$.get("/api/saba/logout", function(data, status) {})
					// location.href = "/thongtintk"
					return;
				}
			}
			,function(isConfirm) {
				localStorage.setItem('popupUserSabaLock', 0)
				$.get("/api/saba/logout", function(data, status) {})
				// location.href = "/thongtintk"
			});
		}
	}
	@endif

	// console.log(userStatusLock)
</script>