@extends('admin.admin-template')
@section('content')
<style>
	.dataTables_filter,
	.dataTables_info {
		display: none;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="portlet">
			<!-- /primary heading -->
			<div class="portlet-heading">
				<h3 class="portlet-title text-dark text-uppercase">
					@if ($type_page == 'winlose')
					Thắng thua - Member
					@endif

					@if ($type_page == 'cxl')
					Bảng cược chưa xử lý - Member
					@endif

					@if ($type_page == 'cancel')
					Bảng huỷ cược - Member
					@endif

				</h3>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<?php
use App\Helpers\MinigameHelpers;
$user_current = Auth::user();
?>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">
			<div class="row">
				@if($user_current->id != $user->id)
				<!-- <div class="row"> -->
				<div class="col-sm-2">
					<a onclick="window.history.back();" class="btn btn-default btn-md waves-effect waves-light"><i class="md md-keyboard-return"></i>Trở lại</a>
				</div>
				<!-- </div> -->
				@endif
				<div class="col-sm-6 hidden">
					<div class="form-group contact-search m-b-30">
						<input type="text" id="input_search_history" class="form-control" placeholder="Tìm kiếm người đánh, loại đặt cược,số tiền... " style="height: 30px !important;">
						<button type="button" class="btn btn-white"><i class="fa fa-search"></i></button>
					</div> <!-- form-group -->
				</div>
				<div class="col-sm-3 hidden">
					<div class="form-group contact-search m-b-30">
						<input type="text" class="form-control column_filter hidden" value="{{date("d/m/Y")}}" id="datepicker-ngaydatcuoc" style="height: 30px !important;">
						<button type="button" class="btn btn-white"><i class="fa fa-calendar"></i></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="card-box">

			<div class="row">
				<div class="table-rep-plugin">
					<div class="table-responsive">
						<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">
							<thead>
								<tr>
									<!-- <th>#</th> -->
									<th>Hội viên</th>
									<th>Đài</th>
									<!-- <th>Thể loại</th> -->
									<!--<th>Thời gian</th>-->
									<th>Mã cược</th>
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
								$all_total_bonus = 0;
								$all_total_point = 0;
								$inlocation = [];
								?>
								@foreach (LocationHelpers::getTopLocation() as $keyLocation)
								<?php
								$haveData = false;
								$total_bet_money = 0;
								$total_bet_accept_money = 0;
								$total_win_money = 0;
								$total_bonus = 0;
								$total_point = 0;
								$betnumber = "";
								$game_id = "";
								$countrecord = 0;
								$location_name = '';
								// $groupByLocation = $xosorecords->groupBy('locationslug');
								// echo count($xosorecords);
								?>
								@if(isset($xosorecords))
								@foreach($xosorecords as $xosorecord)
								@if ($xosorecord->locationslug == $keyLocation->slug)

								<?php
								$countrecord++;
								$haveData = true;
								$location_name = $xosorecord->location;

								if ($xosorecord->game_id > 3000) {
									$total_bet_accept_money += $xosorecord->com;
								}
								if ($xosorecord->locationslug == 60)
									$total_bet_money += $xosorecord->total_bet_money * 1000;
								else
									$total_bet_money += $xosorecord->total_bet_money;
								// $total_bet_money += $xosorecord->total_bet_money;
								if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
									$arrBonus = explode(",", $xosorecord->bonus);
									$bonus = end($arrBonus);
									// $total_win_money += $bonus;
									$total_bonus+=$bonus;
								}
								// fix tra thuong
								if ($xosorecord->total_win_money > 0 && $xosorecord->game_id < 1000) {
									if (
										$xosorecord->game_id == 15 || $xosorecord->game_id == 16
										|| $xosorecord->game_id == 316 || $xosorecord->game_id == 416 || $xosorecord->game_id == 516 || $xosorecord->game_id == 616
										|| $xosorecord->game_id == 115 || $xosorecord->game_id == 116
									) { //
										$total_win_money += $xosorecord->total_win_money;
									} else
										$total_win_money += ($xosorecord->total_win_money - $xosorecord->total_bet_money);
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
									if (!empty($bet))
										if (strpos($xosorecord->win_number, $bet) !== false) {
											$highlightbet = str_replace($bet, '<b>' . $bet . '</b>', $highlightbet);
										}
								}
								$betnumber .= $highlightbet . ',';
								if ($xosorecord->exchange_rates != 0)
									if (
										$xosorecord->game_id == 29 || $xosorecord->game_id == 329 || $xosorecord->game_id == 429 || $xosorecord->game_id == 529 || $xosorecord->game_id == 629 || $xosorecord->game_id == 9 || $xosorecord->game_id == 309 || $xosorecord->game_id == 409 || $xosorecord->game_id == 509 || $xosorecord->game_id == 609 || $xosorecord->game_id == 709 || $xosorecord->game_id == 10 || $xosorecord->game_id == 310 || $xosorecord->game_id == 410 || $xosorecord->game_id == 510 || $xosorecord->game_id == 610 || $xosorecord->game_id == 710 || $xosorecord->game_id == 11 || $xosorecord->game_id == 311 || $xosorecord->game_id == 411 || $xosorecord->game_id == 511 || $xosorecord->game_id == 611 || $xosorecord->game_id == 711
										|| $xosorecord->game_id == 21 || $xosorecord->game_id == 20 || $xosorecord->game_id == 19
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
										$countbetnumber = count(explode(',', $xosorecord->bet_number));
										$Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($fact) / XoSoRecordHelpers::fact($countbetnumber - $fact);
										$betpoint = $xosorecord->total_bet_money / $xosorecord->exchange_rates / $Ank;

										$total_point += $betpoint;
									} else
										$total_point += $xosorecord->total_bet_money / $xosorecord->exchange_rates;
								?>
								@endif
								@endforeach
								@endif
								@if($haveData)
								<tr>
									<!-- <td>#</td> -->
									<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal-location-{{$keyLocation->slug}}">{{$user->name}}</button></td>
									<td>{{$location_name}}</td>
									<!--<td></td>-->
									<td>
										<!--php-->

										{{$countrecord}}

									</td>
									<td class="text_right text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_point)}}</td>
									<td class="text_right text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($total_bet_money)}}</td>
									<td class="text_right text-bold" @if ($total_win_money<0) style=" color:red;" @endif>{{number_format($total_win_money)}}
									@if($total_bonus>0)
										<br>
										<span style="color:black !important;">{{number_format($total_bonus)}}</span>
									@endif
									</td>

								</tr>
								<?php
								$all_total_bet_money += $total_bet_money;
								$all_total_win_money += ($total_win_money + $total_bonus);
								$all_total_bonus += $total_bonus;
								$all_total_point += $total_point;
								array_push($inlocation, $location_name);
								?>
								@endif
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3" class="text_right pr10">Tổng cộng</td>
									<td class="text_right pr10 suminvoice text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($all_total_point,0)}}</td>
									<td class="text_right pr10 suminvoice text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($all_total_bet_money,0)}}</td>
									<td class="text_right pr10 suminvoice text-bold" @if ($all_total_win_money < 0) style=" color:red;" @endif>{{number_format($all_total_win_money,0)}}</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			<input type="hidden" id="url" value="{{url('/rp/winlose-detail')}}">
			<input type="hidden" id="token" value="{{ csrf_token() }}">

			<?php
			$ingame = [];
			?>

			@foreach (LocationHelpers::getTopLocation() as $keyLocation)
			<?php
			if (in_array($keyLocation->name, $inlocation)) {
			} else continue;
			?>
			<div id="full-width-modal-location-{{$keyLocation->slug}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-full">
					<div class="modal-content">
						<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="full-width-modalLabel">Chi tiết cược {{$keyLocation->name}}</h4>
						</div>
						<div class="modal-body">
							<div class="table-rep-plugin">
								<div class="table-responsive">
									<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">
										<thead>
											<tr>
												<!-- <th>#</th> -->
												<th>Hội viên</th>
												<!-- <th>Đài</th> -->
												<th>Thể loại</th>
												<!--<th>Thời gian</th>-->
												<th>Mã cược</th>
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
											?>
											@foreach (GameHelpers::GetAllGame($keyLocation->slug) as $key)
											<?php
											$haveData = false;
											$total_bet_money = 0;
											$total_bonus = 0;
											$total_bet_accept_money = 0;
											$total_win_money = 0;
											$total_point = 0;
											$betnumber = "";
											$game_id = "";
											$countrecord = 0;
											$location_name = '';
											?>
											@foreach($xosorecords as $xosorecord)
											@if ($xosorecord->game_id == $key->game_code && $xosorecord->locationslug == $keyLocation->slug)

											<?php
											$countrecord++;
											$haveData = true;
											$location_name = $xosorecord->location;

											if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
												$arrBonus = explode(",", $xosorecord->bonus);
												$total_bonus += end($arrBonus);
											}

											if ($xosorecord->game_id > 3000) {
												$total_bet_accept_money += $xosorecord->com;
											}
											if ($xosorecord->locationslug == 60)
												$total_bet_money += $xosorecord->total_bet_money * 1000;
											else
												$total_bet_money += $xosorecord->total_bet_money;
											// $total_bet_money += $xosorecord->total_bet_money;

											if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
												$arrBonus = explode(",", $xosorecord->bonus);
												$bonus = end($arrBonus);
												// $total_win_money += $bonus;
												$total_point+= $bonus;
											}

											// fix tra thuong
											if ($xosorecord->total_win_money > 0 && $xosorecord->game_id < 1000) {
												if (
													$xosorecord->game_id == 15 || $xosorecord->game_id == 16
													|| $xosorecord->game_id == 316 || $xosorecord->game_id == 416 || $xosorecord->game_id == 516 || $xosorecord->game_id == 616
													|| $xosorecord->game_id == 115 || $xosorecord->game_id == 116
												) { //
													$total_win_money += $xosorecord->total_win_money;
												} else
													$total_win_money += ($xosorecord->total_win_money - $xosorecord->total_bet_money);
											} else {
												if ($xosorecord->locationslug == 60)
													$total_win_money += $xosorecord->total_win_money * 1000;
												else
													$total_win_money += $xosorecord->total_win_money;
											}
											// $total_win_money += $xosorecord->total_win_money;
											// $total_win_money += $xosorecord->total_win_money;


											$highlightbet = $xosorecord->bet_number;

											$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
											foreach ($arrbet as $bet) {
												if (!empty($bet))
													if (strpos($xosorecord->win_number, $bet) !== false) {
														$highlightbet = str_replace($bet, '<b>' . $bet . '</b>', $highlightbet);
													}
											}
											$betnumber .= $highlightbet . ',';
											if ($xosorecord->exchange_rates != 0)
												if (
													$xosorecord->game_id == 29 || $xosorecord->game_id == 329 || $xosorecord->game_id == 429 || $xosorecord->game_id == 529 || $xosorecord->game_id == 629 || $xosorecord->game_id == 9 || $xosorecord->game_id == 309 || $xosorecord->game_id == 409 || $xosorecord->game_id == 509 || $xosorecord->game_id == 609 || $xosorecord->game_id == 709 || $xosorecord->game_id == 10 || $xosorecord->game_id == 310 || $xosorecord->game_id == 410 || $xosorecord->game_id == 510 || $xosorecord->game_id == 610 || $xosorecord->game_id == 710 || $xosorecord->game_id == 11 || $xosorecord->game_id == 311 || $xosorecord->game_id == 411 || $xosorecord->game_id == 511 || $xosorecord->game_id == 611 || $xosorecord->game_id == 711
													|| $xosorecord->game_id == 21 || $xosorecord->game_id == 20 || $xosorecord->game_id == 19
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
													$countbetnumber = count(explode(',', $xosorecord->bet_number));
													$Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($fact) / XoSoRecordHelpers::fact($countbetnumber - $fact);
													$betpoint = $xosorecord->total_bet_money / $xosorecord->exchange_rates / $Ank;

													$total_point += $betpoint;
												} else
													$total_point += $xosorecord->total_bet_money / $xosorecord->exchange_rates;
											?>
											@endif
											@endforeach

											@if($haveData)

											<tr>
												<!-- <td>#</td> -->
												<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal-game-{{$key->game_code}}">{{$user->name}}</button></td>
												<!-- <td>{{$location_name}}</td> -->
												<td>{{$key->name}}</td>
												<!--<td></td>-->
												<td>
													<!--php-->

													{{$countrecord}}

												</td>
												@if($keyLocation->slug == 70 || $keyLocation->slug == 80)
												<!-- <td class="text_center text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_bonus)}}</td> -->
												@else
												<td class="text_right text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_point)}}</td>
												@endif
												<td class="text_right text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($total_bet_money)}}</td>
												<td class="text_right text-bold" @if ($total_win_money<0) style=" color:red;" @endif>{{number_format($total_win_money)}}
												@if($total_bonus>0)
														<br>
														<span style="color:black !important;">{{number_format($total_bonus)}}</span>
													@endif
											</td>

											</tr>
											<?php
											array_push($ingame, $key->game_code);
											$all_total_bet_money += $total_bet_money;
											$all_total_win_money += $total_win_money+$total_bonus;
											$all_total_point += $total_point;
											?>
											@endif
											@endforeach
										</tbody>
										<tfoot>
											<tr>
												<td colspan="3" class="text_right pr10">Tổng cộng</td>
												@if($keyLocation->slug == 70 || $keyLocation->slug == 80)
												@else
												<td class="text_right pr10 suminvoice text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($all_total_point,0)}}</td>
												@endif
												<td class="text_right pr10 suminvoice text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($all_total_bet_money,0)}}</td>
												<td class="text_right pr10 suminvoice text-bold" @if ($all_total_win_money < 0) style=" color:red;" @endif>{{number_format($all_total_win_money,0)}}
													
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endforeach

			@foreach (GameHelpers::GetAllGame() as $key)
			<?php
			$haveData = false;
			$total_bet_money = 0;
			$total_bet_accept_money = 0;
			$total_win_money = 0;
			$total_point = 0;
			$total_bonus = 0;
			$betnumber = "";
			$location_name = '';
			if (in_array($key->game_code, $ingame)) {
			} else continue;
			?>
			<div id="full-width-modal-game-{{$key->game_code}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-full">
					<div class="modal-content">
						<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="full-width-modalLabel">Chi tiết cược {{$key->name}} - {{$key->location}}</h4>
						</div>
						<div class="modal-body">
							<div class="table-rep-plugin">
								<div class="table-responsive">
									<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">@if($key->game_code < 3000) <thead>
											<tr>
												<th>Hội viên</th>
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
											</tr>
											</thead>
											<tbody>@else<thead>
													<tr>
														<th>Hội viên</th>
														<!-- <th>Đài</th> -->
														<!-- <th>Thể loại</th> -->
														<th>Thời gian</th>
														<th>Mã cược</th>
														<th>Kết quả</th>@if ($key->game_code > 4000)<th>Tỷ lệ cược</th>
														<th>Tiền cược</th>
														<th>Thắng thua</th>@else<th>Cược hợp lệ</th>
														<th>Tổng đặt cược</th>
														<th>Thắng/Thua</th>@endif
													</tr>
												</thead>
											<tbody>@endif @foreach($xosorecords as $xosorecord) @if ($xosorecord->game_id == $key->game_code)
												<?php $haveData = true;
												if ($xosorecord->locationslug > 20 && $xosorecord->locationslug != 50 && $xosorecord->locationslug != 60 && $xosorecord->locationslug != 70 && $xosorecord->locationslug != 80) $location_name = GameHelpers::ChuyenDoiDaiByDate($xosorecord->locationslug, strtotime($xosorecord->created_at));
												else $location_name = $xosorecord->location;
												if ($xosorecord->locationslug == 60) $total_bet_money += $xosorecord->total_bet_money * 1000;
												else $total_bet_money += $xosorecord->total_bet_money;
												if ($xosorecord->game_id > 3000) {
													$total_bet_accept_money += $xosorecord->com;
												}
												if ($xosorecord->total_win_money > 0 && $xosorecord->game_id < 1000) {
													if ($xosorecord->game_id == 15 || $xosorecord->game_id == 16 || $xosorecord->game_id == 316 || $xosorecord->game_id == 416 || $xosorecord->game_id == 516 || $xosorecord->game_id == 616 || $xosorecord->game_id == 115 || $xosorecord->game_id == 116) {
														$total_win_money += $xosorecord->total_win_money;
													} else $total_win_money += ($xosorecord->total_win_money - $xosorecord->total_bet_money);
												} else {
													if ($xosorecord->locationslug == 60) $total_win_money += $xosorecord->total_win_money * 1000;
													else $total_win_money += $xosorecord->total_win_money;
												}
												$bonus = 0;
												if ($xosorecord->locationslug == 70 || $xosorecord->locationslug == 80) {
													$arrBonus = explode(",", $xosorecord->bonus);
													$bonus = end($arrBonus);
													$total_bonus += $bonus;
												}

												$betnumber .= $xosorecord->bet_number . ','; ?> <tr>
													<td>{{$xosorecord->name}}</td>
													<!-- <td>{{$location_name}} @if ($xosorecord->game_id == 18 || $xosorecord->game_id == 9 || $xosorecord->game_id == 10 || $xosorecord->game_id == 11 || $xosorecord->game_id == 29)({{27-$xosorecord->xien_id}})@endif @if ($xosorecord->game_id >= 100 && $xosorecord->game_id <= 200 && isset($xosorecord->xien_id) && $xosorecord->xien_id <=24) ( Kỳ {{$xosorecord->xien_id}}) @endif</td> -->
													<!-- <td>{{$xosorecord->game}}</td> -->
													<td>{{date("d-m-Y H:i:s", strtotime($xosorecord->created_at))}} @if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif</td> @if($xosorecord->game_id < 3000) <td>
														<?php $highlightbet = $xosorecord->bet_number;
														$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
														foreach ($arrbet as $bet) {
															if (!empty($bet)) if (strpos($xosorecord->win_number, $bet) !== false) {
																$highlightbet = str_replace($bet, '<b>' . $bet . '</b>', $highlightbet);
															}
														}
														echo ($highlightbet); ?>
														@if ($xosorecord->game_id == 18 || $xosorecord->game_id == 9 || $xosorecord->game_id == 10 || $xosorecord->game_id == 11 || $xosorecord->game_id == 29)({{27-$xosorecord->xien_id}})@endif 
														@if ($xosorecord->game_id >= 100 && $xosorecord->game_id <= 200 && isset($xosorecord->xien_id) && $xosorecord->xien_id <=24) ( Kỳ {{$xosorecord->xien_id}}) @endif </td>
																<td class="text_right text-bold">{{number_format($xosorecord->exchange_rates,0)}}</td>
																<td class="text_right text-bold">{{number_format($xosorecord->odds,0)}}</td>
																@else 
																	@if($xosorecord->game_id > 4000)
																		@if ($key->game_code >= 7000 && $key->game_code < 8000) 
																			<td style="text-align: left;">
																				@if(isset($xosorecord->rawBet))
																					@if(isset($xosorecord->rawBet->note) && $xosorecord->rawBet->note!= "")
																					<span style="color:red; font-weight:700;">{{$xosorecord->rawBet->note}}</span><br>
																					@endif
																					@if($xosorecord->rawBet->bet_type != "parlay")
																						@if($xosorecord->rawBet->bet_type == "outright")
																						<span style="color:black; font-weight:700;">Chung cuộc</span>
																						<br>
																						@endif
																						<span style="color:#2596be; font-weight:700;"> {{$xosorecord->rawBet->bet_type_txt}}</span>
																						<br>
																						<b>{{$xosorecord->rawBet->bet_on_txt}}</b> <b>{{"@"}}{{number_format(isset($xosorecord->rawBet->bet_odd) ? $xosorecord->rawBet->bet_odd : 0, 2)}} @if (isset($xosorecord->rawBet->bet_match_current)) ({{XoSoRecordHelpers::converScoreMatch($xosorecord->rawBet->bet_type,$xosorecord->rawBet->bet_match_current)}}) @endif</b>
																						<br>
																						@if($xosorecord->rawBet->bet_type != "outright")
																							{{$xosorecord->rawBet->m_tnHomeName}} vs {{$xosorecord->rawBet->m_tnAwayName}}
																							<br>
																							<b>{{isset($xosorecord->rawBet->m_tnName) ? $xosorecord->rawBet->m_tnName : ""}}</b>
																							<br>
																							{{isset($xosorecord->betTime) && $xosorecord->betTime != "" ? $xosorecord->betTime : (isset($xosorecord->rawBet->kickoffVN) ? $xosorecord->rawBet->kickoffVN : "")}}
																						@endif

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
																					@else
																						<?php
																						$parlay = json_decode($xosorecord->rawBet->parlay);
																						$detailMatchOnBetLst = isset($xosorecord->rawBet->bet_match_current) ? json_decode($xosorecord->rawBet->bet_match_current) : null;
																						$parlay_match_result = isset($xosorecord->rawBet->parlay_match_result) ? json_decode($xosorecord->rawBet->parlay_match_result) : null;
																						// var_dump($parlay);
																						$bet_data_lst = json_decode($xosorecord->rawBet->bet_data);
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
																						<span>@if(isset($parlayOne->betting_odd))
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
																						@endif</span>
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
																		<?php
																		
																		// var_dump(MinigameHelpers::convertGametype($xosorecord->rawBet->choice));
																		// echo "<br>";
																		?>
																			<td>{{isset($xosorecord->rawBet->game_result_id) ? ("Mã ván " . $xosorecord->rawBet->game_result_id) : ""}} <br>  {{MinigameHelpers::convertGametype($xosorecord->rawBet->choice,$xosorecord->game_id)}}</td>
																			<td>{{$xosorecord->rawBet->resultTxt}}</td>
																		@endif

																		@if ($key->game_code > 5000 && $key->game_code < 6000) 
																			<td>{{number_format($xosorecord->rawBet->txns[0]->detail[0]->odds, 2)}}</td>
																		@else
																			@if ($key->game_code >= 7000 && $key->game_code < 8000)
																				@if(isset($xosorecord->rawBet))
																					<td @if($xosorecord->rawBet->bet_odd <= 0) style="color:red;" @endif>
																					{{number_format(isset($xosorecord->rawBet->bet_odd) ? $xosorecord->rawBet->bet_odd : 0, 2)}}
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

																			@endif @else<td class="text_right text-bold">{{($xosorecord->SerialID)}}</td>
																			<td class="text_right text-bold">{{($xosorecord->result)}}</td>
																			<td class="text_right text-bold">{{number_format($xosorecord->com,0)}}</td>@endif @endif @if($xosorecord->game_id < 3000) <td class="text_right"> @if ($xosorecord->exchange_rates != 0)@if($xosorecord->game_id==29 || $xosorecord->game_id==329 || $xosorecord->game_id==429 || $xosorecord->game_id==529 || $xosorecord->game_id==629 || $xosorecord->game_id==9 || $xosorecord->game_id==309 ||$xosorecord->game_id==409 ||$xosorecord->game_id==509||$xosorecord->game_id==609||$xosorecord->game_id==709 || $xosorecord->game_id==10 || $xosorecord->game_id==310 ||$xosorecord->game_id==410 ||$xosorecord->game_id==510||$xosorecord->game_id==610||$xosorecord->game_id==710 || $xosorecord->game_id==11 || $xosorecord->game_id==311 ||$xosorecord->game_id==411 ||$xosorecord->game_id==511||$xosorecord->game_id==611||$xosorecord->game_id==711|| $xosorecord->game_id==21 || $xosorecord->game_id==20 || $xosorecord->game_id==19)
																				<?php $fact = 1;
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
																						break;
																				}
																				$countbetnumber = count(explode(',', $xosorecord->bet_number));
																				$Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($fact) / XoSoRecordHelpers::fact($countbetnumber - $fact);
																				$betpoint = $xosorecord->total_bet_money / $xosorecord->exchange_rates / $Ank;
																				$total_point += $betpoint; ?> {{number_format($betpoint,0)}}@else<?php $total_point += ($xosorecord->total_bet_money / $xosorecord->exchange_rates); ?>{{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}@endif @else 0 @endif </td>@endif @if ($xosorecord->locationslug == 60)<td class="text_right text-bold">{{number_format($xosorecord->total_bet_money*1000,0)}}</td>@else<td class="text_right text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td>@endif
																				<?php $win_money = $xosorecord->total_win_money;
																				if ($xosorecord->locationslug == 60) $win_money = $xosorecord->total_win_money * 1000;
																				else $win_money = $xosorecord->total_win_money;
																				if ($win_money > 0 && $xosorecord->game_id < 3000) {
																					if ($xosorecord->game_id == 15 || $xosorecord->game_id == 16 || $xosorecord->game_id == 316 || $xosorecord->game_id == 416  || $xosorecord->game_id == 516  || $xosorecord->game_id == 616 || $xosorecord->game_id == 115 || $xosorecord->game_id == 116) {
																					} else $win_money -= $xosorecord->total_bet_money;
																				} ?>@if($win_money>0)<td class="text_right text_bold">{{number_format($win_money,0)}}@elseif ($win_money<0) <td class="text_right text_bold" style=" color:red;">{{number_format($win_money,0)}}@elseif ($win_money==0)
																				<td class="text_right text_bold"> @if($xosorecord->game_id < 3000) Chưa xử lý @else 0 @endif @endif @if($bonus>0)<br><span style="color:black !important;">{{number_format($bonus)}}</span>@endif </td>@if($xosorecord->game_id < 3000) <td>{{isset($xosorecord->ipaddr)?$xosorecord->ipaddr:"" }}</td>@endif
												</tr> @endif @endforeach</tbody>
											<tfoot>
												<tr>@if($key->game_code < 3000) <td colspan="5" class="text_right pr10">Tổng cộng</td>
														<td class="text_right pr10 suminvoice">{{number_format($total_point,0)}}</td>
														<td class="text_right pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
														<td class="text_right pr10 suminvoice" @if ($total_win_money < 0) style=" color:red;" @endif>{{number_format($total_win_money,0)}}</td>
												</tr>
											</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div class="modal-footer"><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Đóng</button></div>
					</div>
				</div>
			</div>@else <td colspan="4" class="text_right pr10">Tổng cộng</td>
			<td class="text_right pr10 suminvoice">{{number_format($total_bet_accept_money,0)}}</td>
			<td class="text_right pr10 suminvoice">{{number_format($total_bet_money,0)}}</td>
			<td class="text_right pr10 suminvoice" @if ($total_win_money < 0) style=" color:red;" @endif>{{number_format($total_win_money,0)}}
				@if($total_bonus>0)<br><span style="color:black !important;">{{number_format($total_bonus)}}</span>@endif
			</td>

			</tr>
			</tfoot>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer"><button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Đóng</button></div>
</div>
</div>
</div>@endif @endforeach
</div>
</div>
</div>
@endsection
@section('js_call')
<script src="/assets/admin/js/report.js?v=1.23"></script>
@endsection