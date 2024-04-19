@extends('admin.admin-template')

@section('content')

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<!-- <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Thống kê hôm nay</a></li>
		<li class=""><a href="#tab_2" data-toggle="tab">Thống kê tài khoản</a></li>
		<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true">Thông báo</a></li> -->
		<li class="active"><a href="#tab_4" data-toggle="tab" aria-expanded="true">Thống kê theo mã</a></li>
		<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Thống kê theo thể loại</a></li>
	</ul>
	<div class="tab-content">
		<?php

use App\Helpers\GameHelpers;
use App\Helpers\UserHelpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
					function build_sorter($key)
					{
						return function ($a, $b) use ($key) {
							return $a[$key] < $b[$key] ? 1 : 0;
						};
					}
		?>
	@if(Auth::user()->roleid==1 && false)
	<div class="tab-pane active" id="tab_4">
			
			<div class="row">
				<div class="col-xs-12">
					<?php

					$totalByNumber = array();
					$gameall = GameHelpers::GetAllGame(0);
                    $arrUser = UserHelpers::GetAllUserV2(Auth::user());
					// $rs[7] = DB::table('xoso_record')
				    //     ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                    //     ->orderBy('sumbet', 'desc')
                    //     ->where('isDelete',false)
                    //     ->where('date',date('Y-m-d'))
                    //     ->where('game_id', 7)
                    //     ->whereIn('user_id', $arrUser)
                    //     ->groupBy('bet_number')
                    //     ->get();
                    // $rs[12] = DB::table('xoso_record')
				    //     ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                    //     ->orderBy('sumbet', 'desc')
                    //     ->where('isDelete',false)
                    //     ->where('date',date('Y-m-d'))
                    //     ->where('game_id', 12)
                    //     ->whereIn('user_id', $arrUser)
                    //     ->groupBy('bet_number')
                    //     ->get();
                    // $rs[14] = DB::table('xoso_record')
				    //     ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                    //     ->orderBy('sumbet', 'desc')
                    //     ->where('isDelete',false)
                    //     ->where('date',date('Y-m-d'))
                    //     ->where('game_id', 14)
                    //     ->whereIn('user_id', $arrUser)
                    //     ->groupBy('bet_number')
                    //     ->get();
					?>

					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important; display: inline">
						<col width="20">
						<col width="80">
						<col width="250">
						<thead>
							<tr>
								<?php 
							    	echo ('<th>' . 'STT' . '</th>');
								    echo ('<th>' . 'Lô' . '</th>');
									echo ('<th>' . 'Đề' . '</th>');
									echo ('<th>' . 'Nhất' . '</th>');
								?>
								<!-- <th>Giá trị</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
								$TotalBetTodayByNumberARR = [];
								$TotalBetTodayByNumberARROG = [];
    							$count = 1;
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
										$bet_number = $i.$j;
										$TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-7-'.$bet_number,[0,0]);
										$totalBetByNumber = $TotalBetTodayByNumber[1];
										$totalBetByNumber1 = $TotalBetTodayByNumber[0];
										// $TotalBetTodayByNumberARR = array_merge($TotalBetTodayByNumberARR,[ $bet_number => $totalBetByNumber1]);
										array_push($TotalBetTodayByNumberARR,$totalBetByNumber1);
										array_push($TotalBetTodayByNumberARROG,$totalBetByNumber);
									}

								arsort($TotalBetTodayByNumberARROG);
								$arrLo = [];
								foreach($TotalBetTodayByNumberARROG as $x => $x_value) {
									if ($x_value > 0){
										$arrLo[] = 	'<td class="text_center">' .$x. ' <label style="color:red;">' . number_format($x_value) . '</label> <label style="color:black;">('. number_format($TotalBetTodayByNumberARR[$x]) .')</label>' . '</td>';
									// }else break;
									}
									// else{
									// 	echo ('<tr>');
									// 	echo ('<td class="text_center">' .($count++). '</td>');
									// 	echo ('<td class="text_center">' .$count. '</td>');
									// 	echo ('<td class="text_center">' . number_format(123456789000000) . '</td>');
									// 	echo ('</tr>');
									// }
								  }
								// var_dump($arrLo);
							?>

							<?php
								$TotalBetTodayByNumberARR = [];
								$TotalBetTodayByNumberARROG = [];
    							$count = 1;
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
										$bet_number = $i.$j;
										$TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-14-'.$bet_number,[0,0]);
										$totalBetByNumber = $TotalBetTodayByNumber[1];
										$totalBetByNumber1 = $TotalBetTodayByNumber[0];
										// $TotalBetTodayByNumberARR = array_merge($TotalBetTodayByNumberARR,[ $bet_number => $totalBetByNumber1]);
										array_push($TotalBetTodayByNumberARR,$totalBetByNumber1);
										array_push($TotalBetTodayByNumberARROG,$totalBetByNumber);
									}

								arsort($TotalBetTodayByNumberARROG);
								$arrDe = [];
								foreach($TotalBetTodayByNumberARROG as $x => $x_value) {
									if ($x_value > 0){
										$arrDe[] = 	'<td class="text_center">' .$x. ' <label style="color:red;">' . number_format($x_value) . '</label> <label style="color:black;">('. number_format($TotalBetTodayByNumberARR[$x]) .')</label>' . '</td>';
									// }else break;
									}
									// else{
									// 	echo ('<tr>');
									// 	echo ('<td class="text_center">' .($count++). '</td>');
									// 	echo ('<td class="text_center">' .$count. '</td>');
									// 	echo ('<td class="text_center">' . number_format(123456789000000) . '</td>');
									// 	echo ('</tr>');
									// }
								  }
							?>

							<?php
								$TotalBetTodayByNumberARR = [];
								$TotalBetTodayByNumberARROG = [];
    							$count = 1;
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
										$bet_number = $i.$j;
										$TotalBetTodayByNumber = Cache::get('TotalBetTodayByNumberThau-12-'.$bet_number,[0,0]);
										$totalBetByNumber = $TotalBetTodayByNumber[1];
										$totalBetByNumber1 = $TotalBetTodayByNumber[0];
										// $TotalBetTodayByNumberARR = array_merge($TotalBetTodayByNumberARR,[ $bet_number => $totalBetByNumber1]);
										array_push($TotalBetTodayByNumberARR,$totalBetByNumber1);
										array_push($TotalBetTodayByNumberARROG,$totalBetByNumber);
									}

								arsort($TotalBetTodayByNumberARROG);
								$arrDe = [];
								foreach($TotalBetTodayByNumberARROG as $x => $x_value) {
									if ($x_value > 0){
										$arrNhat[] = 	'<td class="text_center">' .$x. ' <label style="color:red;">' . number_format($x_value) . '</label> <label style="color:black;">('. number_format($TotalBetTodayByNumberARR[$x]) .')</label>' . '</td>';
									// }else break;
									}
									// else{
									// 	echo ('<tr>');
									// 	echo ('<td class="text_center">' .($count++). '</td>');
									// 	echo ('<td class="text_center">' .$count. '</td>');
									// 	echo ('<td class="text_center">' . number_format(123456789000000) . '</td>');
									// 	echo ('</tr>');
									// }
								  }
							?>

							<?php 
							for ($i=0; $i < 100; $i++) { 
								if (!isset($arrLo[$i]) && !isset($arrDe[$i]) && !isset($arrNhat[$i])) break;
								echo ('<tr>');
								echo ('<td class="text_center">' .($i+1). '</td>');
								echo (isset($arrLo[$i]) ? $arrLo[$i] : "<td></td>");
								echo (isset($arrDe[$i]) ? $arrDe[$i] : "<td></td>");
								echo (isset($arrNhat[$i]) ? $arrNhat[$i] : "<td></td>");
								echo ('</tr>');
							}
								
							?>
						</tbody>
						<!-- <tfoot>
						</tfoot> -->
					</table>
				</div>
			</div>
		</div>

		<div class="tab-pane" id="tab_1">
			
		<div class="row">
				<div class="col-sm-2 col-xs-12" bis_skin_checked="1">
					<input class="form-control column_filter input-daterange-datepicker-self" type="text" name="daterange" value="" readonly="readonly" style="width: 250px; ">
				</div>

				<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
					<span class="input-group-btn">
						<a style="margin-right:5px;" href="#" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter_target">Xem</a>
					</span>

				</div>
			</div>
			<br />

			<div class="row">
				<div class="col-xs-12"  id='reloadBetByCategory'>
					<?php

					

					$gameList = (new GameHelpers())->GetAllGameByParentID(0, 1);
					$totalall = 0;
					
					// $arrUser = UserHelpers::GetAllUserV2(Auth::user());
					$rs =  [];
					// DB::table('xoso_record')
				    //     ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), 'games.name as game_name')
                    //     ->orderBy('sumbet', 'desc')
                    //     ->where('isDelete',false)
                    //     ->where('date',date('Y-m-d'))
                    //     // ->where('game_id', 7)
                    //     ->whereIn('user_id', $arrUser)
					// 	->join('games', 'games.game_code', '=', 'xoso_record.game_id')
                    //     ->groupBy('game_id')
                    //     ->get();
					// print_r($rs);
					// $startDate = '01-02-2023';
					$startDate = date('d-m-Y');
					$endDate = date('d-m-Y');

					// echo $startDate . ' ' . $endDate;

					$begin = new DateTime($startDate);
					$end = new DateTime($endDate);
					if ($end > (new DateTime()))
						$end = new DateTime();
					$end->modify('+1 day');
						
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);

					
					// echo Auth::user()->id;
					$counttotalMember = 0;
					$counttotalThau = 0;
					$countwinloseAdmin = 0;
					$countwinloseMember = 0;

					$totalMember = array();
					$totalThau = array();
					$winloseAdmin = array();
					$winloseMember = array();

					foreach($period as $dt) {							
						$stDateTemp = $dt->format("d-m-Y");
						$endDateTemp = $dt->format("d-m-Y");
						if ($dt->format("Y-m-d") > date('Y-m-d')){
							// echo 'continue';
							break;
						}
						$cacheTime = env('CACHE_TIME_SHORT', 0);
						$endTimeStamp = strtotime($endDateTemp);
						$endDateNewformat = date('Y-m-d',$endTimeStamp);
						if ($endDateNewformat < date('Y-m-d'))
							$cacheTime = 1440*30;
						if ($endDateNewformat == date('Y-m-d',strtotime("yesterday")) && date('H') < 11){
							$cacheTime = env('CACHE_TIME_SHORT', 0);
						}
						$calByDate = Cache::remember('calByDate-rsv2-20230310'.$endDateNewformat, $cacheTime, function () use ($endDateNewformat,$cacheTime) {
							$childrenAdmin = UserHelpers::GetAllUserChild(Auth::user());
							$totalMemberSP = array();
							$totalThauSP = array();
							$winloseAdminSP = array();
							$winloseMemberSP = array();
							foreach($childrenAdmin as $supers){
								$arrUser = UserHelpers::GetAllUserV2($supers);
								$rs = Cache::remember('xoso_record-calByDate-rsv2-20230310'.$endDateNewformat.$supers->id, $cacheTime, function () use ($arrUser,$endDateNewformat) {
									return DB::table('xoso_record')
									->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'), DB::raw('SUM(
										IF(game_id = 15 OR game_id = 16 OR game_id = 19 OR game_id = 20 OR game_id = 21, total_win_money,IF(total_win_money > 0, total_win_money-total_bet_money,total_win_money))
										) AS sumwin', 'games.name as game_name'))
											->orderBy('sumbet', 'desc')
											->where('isDelete',false)
											->where('date',$endDateNewformat)
											// ->where('date','<=',$endDate)
											// ->whereIn('game_id', [7,12,14])
											->whereIn('user_id', $arrUser)
											->join('games', 'games.game_code', '=', 'xoso_record.game_id')
											->groupBy('game_id')
											->get();
										});
								// if (count($rs)>0)
								// 	var_dump($rs);
								foreach($rs as $record){
									$game_id = $record->game_id;
                                    if ($game_id == 9 || $game_id == 10 || $game_id == 11 ) $game_id = 2;
                                    if ($game_id == 16 || $game_id == 19 || $game_id == 20 || $game_id == 21 ) $game_id = 3;
                                    if ($game_id >= 31 && $game_id <= 55) $game_id = 24;
                                    // if ($game_id == 9 || $game_id == 10 || $game_id == 11 ) $game_id = 2;

									if (!isset($totalMemberSP[$game_id])) $totalMemberSP[$game_id] = 0;
									if (!isset($winloseMemberSP[$game_id])) $winloseMemberSP[$game_id] = 0;
									if (!isset($totalThauSP[$game_id])) $totalThauSP[$game_id] = 0;
									if (!isset($winloseAdminSP[$game_id])) $winloseAdminSP[$game_id] = 0;
									$totalMemberSP[$game_id] += $record->sumbet;
									$winloseMemberSP[$game_id] += $record->sumwin;
									$totalThauSP[$game_id] += $record->sumbet*$supers->thau/100;
									$winloseAdminSP[$game_id] += $record->sumwin*$supers->thau/100;
								}
							}
							return [$totalMemberSP,$totalThauSP,$winloseAdminSP,$winloseMemberSP];
							
						});
						// var_dump($calByDate);
						foreach($gameList as $game){
							if (!isset($totalMember[$game->game_code])) $totalMember[$game->game_code] = 0;
							if (!isset($winloseMember[$game->game_code])) $winloseMember[$game->game_code] = 0;
							if (!isset($totalThau[$game->game_code])) $totalThau[$game->game_code] = 0;
							if (!isset($winloseAdmin[$game->game_code])) $winloseAdmin[$game->game_code] = 0;

							$totalMember[$game->game_code] += (isset($calByDate[0][$game->game_code]) ? $calByDate[0][$game->game_code] : 0);
							$winloseMember[$game->game_code] += (isset($calByDate[3][$game->game_code]) ? $calByDate[3][$game->game_code] : 0);
							$totalThau[$game->game_code] += (isset($calByDate[1][$game->game_code]) ? $calByDate[1][$game->game_code] : 0);
							$winloseAdmin[$game->game_code] += (isset($calByDate[2][$game->game_code]) ? $calByDate[2][$game->game_code] : 0);

						}
					}
					
					// var_dump($totalThau);
					?>
<div class="table-rep-plugin" id="div_history" bis_skin_checked="1">
                <div class="table-responsive" style="" bis_skin_checked="1">
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
						<col width="10%">
						<col width="22%">
						<col width="22%">
						<col width="22%">
						<col width="22%">
						<thead>
							<tr>
								<th>MB</th>
							</tr>
							<tr>
								<th>Loại</th>
								<th>Tổng thầu Admin</th>
								<th>Thắng thua Admin</th>
								<th>Tổng cược member</th>
								<th>Thắng thua member</th>
							</tr>
						</thead>
						<tbody>
							@foreach($gameList as $game)
							<?php
								if ( !isset($totalThau[$game->game_code]) || $totalThau[$game->game_code] == 0) continue;

								$counttotalMember += $totalMember[$game->game_code];
								$counttotalThau += $totalThau[$game->game_code];
								$countwinloseAdmin += $winloseAdmin[$game->game_code];
								$countwinloseMember += $winloseMember[$game->game_code];
							?>
								<tr>
									<td class="text_center">{{$game->name}}</td>
									<td class="text_center"><label style="color:black;">{{number_format($totalThau[$game->game_code],0)}}</label></td>
									<td class="text_center"><label style="color:@if(0-$winloseAdmin[$game->game_code]>0) green; @else red; @endif;">{{number_format(0-$winloseAdmin[$game->game_code],0)}}</label></td>
									<td class="text_center"><label style="color:black;">{{number_format($totalMember[$game->game_code],0)}}</label></td>
									<td class="text_center"><label style="color:@if($winloseMember[$game->game_code]>0) green; @else red; @endif">{{number_format($winloseMember[$game->game_code],0)}}</label></td>
								</tr>

							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td class="text_center pr10">Tổng</td>
								<td class="text_center pr10">{{number_format($counttotalThau,0)}}</td>
								<td class="text_center pr10">{{number_format(0-$countwinloseAdmin,0)}}</td>
								<td class="text_center pr10">{{number_format($counttotalMember,0)}}</td>
								<td class="text_center pr10">{{number_format($countwinloseMember,0)}}</td>
							</tr>
						</tfoot>
					</table>
				</div></div>
				</div>

				

			</div>
			
		</div>
		<script>
	$(document).ready(function() {
		//Initialize Select2 Elements
		$('.js-notification-category-single').select2({
			minimumResultsForSearch: Infinity,
			dropdownCssClass:'notification-category-height',
			width: "100%"
		});

		jQuery('#date-range').datepicker({
			toggleActive: true,
			format: "dd-mm-yyyy",
			todayHighlight: true,
			language: "vi",
		});

		//Date range picker
		$('.input-daterange-datepicker-self').daterangepicker({
			buttonClasses: ['btn', 'btn-sm'],
			applyClass: 'btn-default',
			cancelClass: 'btn-white',
			minDate: moment().subtract(62, 'days'),
			// maxDate: today,
			locale: {
				format: "DD-MM-YYYY",
				language: "vi",
				separator: " / ",
				applyLabel: "Tiếp",
				cancelLabel: "Hủy",
				fromLabel: "From",
				toLabel: "To",
				"customRangeLabel": "Tùy chọn",
			},
			ranges: {
				'Hôm nay': [moment(), moment()],
				'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Tuần này': [moment().startOf('week'), moment().endOf('week')],
				'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
				// 'Cách đây 7 ngày': [moment().subtract(6, 'days'), moment()],
				// 'Cách đây 30 ngày': [moment().subtract(29, 'days'), moment()],
				'Tháng này': [moment().startOf('month'), moment().endOf('month')],
				'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
			// startDate: today,
			// endDate: today
		}, function(start, end, label) {
			//alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			// $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
		});

		$('.input-daterange-datepicker-target').daterangepicker({
			buttonClasses: ['btn', 'btn-sm'],
			applyClass: 'btn-default',
			cancelClass: 'btn-white',
			minDate: moment().subtract(62, 'days'),
			// maxDate: today,
			locale: {
				format: "DD-MM-YYYY",
				language: "vi",
				separator: " / ",
				applyLabel: "Tiếp",
				cancelLabel: "Hủy",
				fromLabel: "From",
				toLabel: "To",
				"customRangeLabel": "Tùy chọn",
			},
			ranges: {
				'Hôm nay': [moment(), moment()],
				'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Tuần này': [moment().startOf('week'), moment().endOf('week')],
				'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
				// 'Cách đây 7 ngày': [moment().subtract(6, 'days'), moment()],
				// 'Cách đây 30 ngày': [moment().subtract(29, 'days'), moment()],
				'Tháng này': [moment().startOf('month'), moment().endOf('month')],
				'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
			}
			// startDate: today,
			// endDate: today
		}, function(start, end, label) {
			//alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			// $('#btn_view_by_filter').attr('href',$('#url').val()+ '/'+start.format('YYYY-MM-DD')+'/'+end.format('YYYY-MM-DD'));
		});
	});

	$("#btn_view_by_filter_target").click(function() {
		// $('#mySelect2').trigger('select2:select');
		console.log($("#select_notification_category").val())
		var range = $('.input-daterange-datepicker-self').val().split('/');
		var startdate = range[0];
		var enddate = range[1];
		console.log(startdate + " - " + enddate)
		// window.location.href = "/notification/list?startdate="+startdate+"&enddate="+enddate+"&category="+$("#select_notification_category").val();
		$.ajax({
			url: "/admin/reload-bet-by-category",
			method: 'GET',
			dataType: 'json',
			data: {
				startDate: startdate,
				endDate: enddate,
				// _token: $_token,
			},
			complete: function(data) {
				// console.log(data.responseText)
				$("#reloadBetByCategory").html(data.responseText);
			}
		});

	})

	</script>
		@else
		<div class="tab-pane active" id="tab_4">
			
			<div class="row">
				<div class="col-xs-12">
					<?php

					$totalByNumber = array();
					$gameall = GameHelpers::GetAllGame(0);
                    $arrUser = UserHelpers::GetAllUserV2(Auth::user());
					$rs[7] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money/exchange_rates) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 7)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();
                    $rs[12] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money/exchange_rates) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 12)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();
                    $rs[14] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money/exchange_rates) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 14)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();
					?>

					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important; display: inline">
						<col width="20">
						<col width="80">
						<thead>
							<tr>
								<?php 
							    	echo ('<th>' . 'STT' . '</th>');
								    echo ('<th>' . 'Lô' . '</th>');
									echo ('<th>' . 'Đề' . '</th>');
									echo ('<th>' . 'Nhất' . '</th>');
								?>
								<!-- <th>Giá trị</th> -->
							</tr>
						</thead>
						<tbody>
                            
							<?php
								$arrLo = [];
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    									$k = 0;
										if (isset($rs[7][$i * 10 + $j])) {
											$arrLo[] = '<td class="text_center"><label style="color:black;">' .$rs[7][$i * 10 + $j]->bet_number . '</label><br><label style="color:red;">'. number_format($rs[7][$i * 10 + $j]->sumbet) . 'đ</label>'. '</td>';
										}
								}

								$arrDe = [];
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    									$k = 0;
										if (isset($rs[14][$i * 10 + $j])) {
											$arrDe[] = '<td class="text_center"><label style="color:black;">' .$rs[14][$i * 10 + $j]->bet_number . '</label><br><label style="color:red;">'. number_format($rs[14][$i * 10 + $j]->sumbet) . 'đ</label>'. '</td>';
										}
								}

								$arrNhat = [];
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    									$k = 0;
										if (isset($rs[12][$i * 10 + $j])) {
											$arrNhat[] = '<td class="text_center"><label style="color:black;">' .$rs[12][$i * 10 + $j]->bet_number . '</label><br><label style="color:red;">'. number_format($rs[12][$i * 10 + $j]->sumbet) . 'đ</label>'. '</td>';
										}
								}

								for ($i=0; $i < 100; $i++) { 
									if (!isset($arrLo[$i]) && !isset($arrDe[$i]) && !isset($arrNhat[$i]) ) break;
									echo ('<tr>');
									echo ('<td>'.($i+1).'</td>');
									echo (isset($arrLo[$i]) ? $arrLo[$i] : '<td class="text_center"></td>');
									echo (isset($arrDe[$i]) ? $arrDe[$i] : '<td class="text_center"></td>');
									echo (isset($arrNhat[$i]) ? $arrNhat[$i] : '<td class="text_center"></td>');
									echo ('</tr>');
								}
							?>

						</tbody>
						<tfoot>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		

		<div class="tab-pane" id="tab_1">
			
			<div class="row">
				<div class="col-xs-12">
					<?php

					$gameList = (new GameHelpers())->GetAllGameByParentID(0, 1);
					$totalall = 0;
					
					$arrUser = UserHelpers::GetAllUserV2(Auth::user());
					$rs = DB::table('xoso_record')
				        ->select('game_id', DB::raw('SUM(total_bet_money) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        // ->where('game_id', 7)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('game_id')
                        ->get();
					// print_r($rs);
					?>

					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
						<col width="20">
						<col width="80">
						<thead>
							<tr>
								<th>MB</th>
							</tr>
							<tr>
								<th>Mã</th>
								<th>Tổng</th>
							</tr>
						</thead>
						<tbody>
							@foreach($gameList as $game)
							<?php
							$gamechilderList = (new GameHelpers())->GetAllGameByParentID($game['game_code'], 1);
							$totalgiaikhac = 0;
							?>

							@if (count($gamechilderList)>0)
							@foreach($gamechilderList as $children)
							<?php
								$total = 0;
								foreach ($rs as $key => $value) {
									if ($value->game_id == $children['game_code']){
										$total = $value->sumbet;
										break;
									}
										
								}
							// $total = XoSoRecordHelpers::TotalBetTodayByGameByUser($children['game_code'],Auth::user()->id);
							$totalall += $total;
							?>
							@if ( !($children['game_code']>=31 && $children['game_code']<=55 ) ) <tr>
								<td class="text_center">{{$children['name']}}</td>
								<td class="text_center">{{number_format($total,0)}}</td>
								</tr>
								@else
								<?php
								$totalgiaikhac += $total;
								?>
								@endif
								@endforeach

								@if ( $game['game_code']==24 )
								<tr>
									<td class="text_center">{{$game['name']}}</td>
									<td class="text_center">{{number_format($totalgiaikhac,0)}}</td>
								</tr>
								@endif
								@else
								<?php
								$total = 0;
								foreach ($rs as $key => $value) {
									if ($value->game_id == $game['game_code']){
										$total = $value->sumbet;
										break;
									}
										
								}
								// $total = XoSoRecordHelpers::TotalBetTodayByGameByUser($game['game_code'],Auth::user()->id);
								$totalall += $total;
								?>
								@if ( $game['game_code']!=24 )
								<tr>
									<td class="text_center">{{$game['name']}}</td>
									<td class="text_center">{{number_format($total,0)}}</td>
								</tr>
								@endif
								@endif

								@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td class="text_center pr10">Tổng</td>
								<td class="text_center pr10">{{number_format($totalall,0)}}</td>
							</tr>
						</tfoot>
					</table>
				</div>

				<div class="col-xs-6 hidden">
					<?php
					$gameList = (new GameHelpers())->GetAllGameByParentID(0, 4);
					$totalall = 0;
					?>

					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover hidden" style="font-size: 12px !important;">
						<col width="20">
						<col width="80">
						<thead>
							<tr>
								<th>XSAO</th>
							</tr>
							<tr>
								<th>Mã</th>
								<th>Tổng</th>
							</tr>
						</thead>
						<tbody>
							@foreach($gameList as $game)
								<?php
								$gamechilderList = (new GameHelpers())->GetAllGameByParentID($game['game_code'], 4);
								$totalgiaikhac = 0;
								?>

								@if (count($gamechilderList)>0)
									@foreach($gamechilderList as $children)
										<?php
										$total = XoSoRecordHelpers::TotalBetTodayByGameByUser($children['game_code'],Auth::user()->id);
										$totalall += $total;
										?>
										@if ( !($children['game_code']>=31 && $children['game_code']<=55 ) ) <tr>
											<td class="text_center">{{$children['name']}}</td>
											<td class="text_center">{{number_format($total,0)}}</td>
											</tr>
										@else
											<?php
											$totalgiaikhac += $total;
											?>
										@endif
										
									@endforeach

									@if ( $game['game_code']==24 )
										<tr>
											<td class="text_center">{{$game['name']}}</td>
											<td class="text_center">{{number_format($totalgiaikhac,0)}}</td>
										</tr>
									@endif
								@else
									<?php
									$total = XoSoRecordHelpers::TotalBetTodayByGameByUser($game['game_code'],Auth::user()->id);
									$totalall += $total;
									?>
									@if ( $game['game_code']!=24 )
										<tr>
											<td class="text_center">{{$game['name']}}</td>
											<td class="text_center">{{number_format($total,0)}}</td>
										</tr>
									@endif
								@endif

							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td class="text_center pr10">Tổng</td>
								<td class="text_center pr10">{{number_format($totalall,0)}}</td>
							</tr>
						</tfoot>
					</table>
				</div>

			</div>
			
		</div>
		@endif
	</div>
	<!-- /.tab-content -->
</div>


@endsection