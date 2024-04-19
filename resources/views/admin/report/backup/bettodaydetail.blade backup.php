@extends('admin.admin-template')
@section('title', 'Thắng thua chưa xử lý')
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
					Thắng thua chưa xử lý
				</h3>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<?php
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
								$all_total_point = 0;
								?>
								@foreach (LocationHelpers::getTopLocation() as $keyLocation)
								<?php
								$haveData = false;
								$total_bet_money = 0;
								$total_bet_accept_money = 0;
								$total_win_money = 0;
								$total_point = 0;
								$betnumber = "";
								$game_id = "";
								$countrecord = 0;
								$location_name = '';
								?>
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
									$total_bet_money += $xosorecord->total_bet_money*1000;
								else
									$total_bet_money += $xosorecord->total_bet_money;
								// $total_bet_money += $xosorecord->total_bet_money;

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
								} else{
									if ($xosorecord->locationslug == 60)
										$total_win_money += $xosorecord->total_win_money*1000;
									else
										$total_win_money += $xosorecord->total_win_money;
									// $total_win_money += $xosorecord->total_win_money;
								}
									
								// $total_win_money += $xosorecord->total_win_money;


								$highlightbet = $xosorecord->bet_number;

								$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
								foreach ($arrbet as $bet) {
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
									<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal{{$keyLocation->slug}}">{{$user->name}}</button></td>
									<td>{{$location_name}}</td>
									<!--<td></td>-->
									<td>
										<!--php-->

										{{$countrecord}}

									</td>
									<td class="text_right text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_point)}}</td>
									<td class="text_right text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($total_bet_money)}}</td>
									<td class="text_right text-bold" @if ($total_win_money<0) style=" color:red;" @endif>{{number_format($total_win_money)}}</td>

								</tr>
								<?php
								$all_total_bet_money += $total_bet_money;
								$all_total_win_money += $total_win_money;
								$all_total_point += $total_point;
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



			@foreach (LocationHelpers::getTopLocation() as $keyLocation)
			<div id="full-width-modal{{$keyLocation->slug}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-full">
					<div class="modal-content">
						<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title" id="full-width-modalLabel">Chi tiết cược</h4>
						</div>
						<div class="modal-body">
							<div class="table-rep-plugin">
								<div class="table-responsive">
								<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">
							<thead>
								<tr>
									<!-- <th>#</th> -->
									<th>Hội viên</th>
									<th>Đài</th>
									<th>Thể loại</th>
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
								$all_total_point = 0;
								?>
								@foreach (GameHelpers::GetAllGame() as $key)
								<?php
								$haveData = false;
								$total_bet_money = 0;
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

								if ($xosorecord->game_id > 3000) {
									$total_bet_accept_money += $xosorecord->com;
								}
								if ($xosorecord->locationslug == 60)
									$total_bet_money += $xosorecord->total_bet_money*1000;
								else
									$total_bet_money += $xosorecord->total_bet_money;
								// $total_bet_money += $xosorecord->total_bet_money;

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
								} else{
									if ($xosorecord->locationslug == 60)
										$total_win_money += $xosorecord->total_win_money*1000;
									else
										$total_win_money += $xosorecord->total_win_money;
								}
									// $total_win_money += $xosorecord->total_win_money;
								// $total_win_money += $xosorecord->total_win_money;


								$highlightbet = $xosorecord->bet_number;

								$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
								foreach ($arrbet as $bet) {
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
									<td><button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#full-width-modal{{$key->game_code}}">{{$user->name}}</button></td>
									<td>{{$location_name}}</td>
									<td>{{$key->name}}</td>
									<!--<td></td>-->
									<td>
										<!--php-->

										{{$countrecord}}

									</td>
									<td class="text_right text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($total_point)}}</td>
									<td class="text_right text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($total_bet_money)}}</td>
									<td class="text_right text-bold" @if ($total_win_money<0) style=" color:red;" @endif>{{number_format($total_win_money)}}</td>

								</tr>
								<?php
								$all_total_bet_money += $total_bet_money;
								$all_total_win_money += $total_win_money;
								$all_total_point += $total_point;
								?>
								@endif
								@endforeach
							</tbody>
							<tfoot>
								<tr>
									<td colspan="4" class="text_right pr10">Tổng cộng</td>
									<td class="text_right pr10 suminvoice text-bold" @if ($total_point<0) style=" color:red;" @endif>{{number_format($all_total_point,0)}}</td>
									<td class="text_right pr10 suminvoice text-bold" @if ($total_bet_money<0) style=" color:red;" @endif>{{number_format($all_total_bet_money,0)}}</td>
									<td class="text_right pr10 suminvoice text-bold" @if ($all_total_win_money < 0) style=" color:red;" @endif>{{number_format($all_total_win_money,0)}}</td>
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
					$betnumber = "";
					$location_name = '';
					?>
					<div id="full-width-modal{{$key->game_code}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="full-width-modalLabel" aria-hidden="true" style="display: none;">
						<div class="modal-dialog modal-full">
							<div class="modal-content">
								<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h4 class="modal-title" id="full-width-modalLabel">Chi tiết cược</h4>
								</div>
								<div class="modal-body">
									<div class="table-rep-plugin">
										<div class="table-responsive">
											<table id="table_winlose" class="table table-bordered mails m-0 table-actions-bar table-striped dataTable no-footer">

												@if($key->game_code < 3000) <thead>
													<tr>
														<th>Hội viên</th>
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
													</tr>
													</thead>
													<tbody>
														@else
														<thead>
															<tr>
																<th>Hội viên</th>
																<th>Đài</th>
																<th>Thể loại</th>
																<th>Thời gian</th>
																<th>Mã cược</th>
																<th>Kết quả</th>
																@if ($key->game_code > 4000)
																	<th>Tỷ lệ cược</th>
																	<th>Tiền cược</th>

																	<th>Thắng thua</th>
																@else
																	<th>Cược hợp lệ</th>
																	<th>Tổng đặt cược</th>
																	<th>Thắng/Thua</th>
																@endif
																
															</tr>
														</thead>
													<tbody>
														@endif

														@foreach($xosorecords as $xosorecord) @if ($xosorecord->game_id == $key->game_code)
														<?php $haveData = true;
														if ($xosorecord->locationslug > 20 && $xosorecord->locationslug != 50 && $xosorecord->locationslug != 60) $location_name = GameHelpers::ChuyenDoiDaiByDate($xosorecord->locationslug, strtotime($xosorecord->created_at));
														else $location_name = $xosorecord->location;
														if ($xosorecord->locationslug == 60)
															$total_bet_money += $xosorecord->total_bet_money*1000;
														else
															$total_bet_money += $xosorecord->total_bet_money;
														// $total_bet_money += $xosorecord->total_bet_money;
														if ($xosorecord->game_id > 3000) {
															$total_bet_accept_money += $xosorecord->com;
														}
														// fix tra thuong
														if ($xosorecord->total_win_money > 0 && $xosorecord->game_id < 1000) {
															if (
																$xosorecord->game_id == 15 || $xosorecord->game_id == 16 ||
																$xosorecord->game_id == 316 || $xosorecord->game_id == 416 || $xosorecord->game_id == 516 || $xosorecord->game_id == 616 ||
																$xosorecord->game_id == 115 || $xosorecord->game_id == 116
															) {
																$total_win_money += $xosorecord->total_win_money;
																// || $xosorecord->game_id == 16|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21
															} else
																$total_win_money += ($xosorecord->total_win_money - $xosorecord->total_bet_money);
														} else{
															if ($xosorecord->locationslug == 60)
																$total_win_money += $xosorecord->total_win_money*1000;
															else
																$total_win_money += $xosorecord->total_win_money;
														}
															// $total_win_money += $xosorecord->total_win_money;
														$betnumber .= $xosorecord->bet_number . ',';
														// if ($xosorecord->exchange_rates != 0)
														// 	$total_point+=$xosorecord->total_bet_money/$xosorecord->exchange_rates;
														// 
														?>


														<tr>
															<td>{{$xosorecord->name}}</td>
															<td>{{$location_name}} @if ($xosorecord->game_id == 18 || $xosorecord->game_id == 9 || $xosorecord->game_id == 10 || $xosorecord->game_id == 11 || $xosorecord->game_id == 29)({{27-$xosorecord->xien_id}})@endif @if ($xosorecord->game_id >= 100 && $xosorecord->game_id <= 200 && isset($xosorecord->xien_id) && $xosorecord->xien_id <=24) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>
															<td>{{$xosorecord->game}}</td>
															<td>{{date("d-m-Y H:i:s", strtotime($xosorecord->created_at))}} @if ($xosorecord->game_id >= 700 && $xosorecord->game_id < 800 && isset($xosorecord->xien_id)) ( Kỳ {{$xosorecord->xien_id}}) @endif</td>
															
															@if($xosorecord->game_id < 3000) 
																<td>
																<?php
																$highlightbet = $xosorecord->bet_number;
																$arrbet = explode(",", str_replace(" ", "", $xosorecord->bet_number));
																foreach ($arrbet as $bet) {
																	if (strpos($xosorecord->win_number, $bet) !== false) {
																		$highlightbet = str_replace($bet, '<b>' . $bet . '</b>', $highlightbet);
																	}
																}
																echo ($highlightbet);
																?></td>
																<td class="text_right text-bold">{{number_format($xosorecord->exchange_rates,0)}}</td>
																<td class="text_right text-bold">{{number_format($xosorecord->odds,0)}}</td>
															@else
																@if($xosorecord->game_id > 4000) 

																	@if ($key->game_code > 5000 && $key->game_code < 6000)
																	<td style="    text-align: left;">
																			Xiên:
																			<br><br>
																			@foreach($xosorecord->rawBet->ticketDetail as $ticketItem)

																				Cược {{$ticketItem->betChoice}}
																				<br>
																				{{$ticketItem->point}} [ {{$ticketItem->homeScore}} - {{$ticketItem->awayScore}} ]
																				<br>
																				{{$ticketItem->homeName}}
																				<br>
																				{{$ticketItem->awayName}}
																				<br>
																				{{$ticketItem->leagueName}}
																				<br>
																				<?php
																				$date = new DateTime($ticketItem->matchDateTime, new DateTimeZone('GMT-4'));
																				$date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
																				echo $date->format('Y-m-d H:i:s');
																				?>
																				<br><br>
																			@endforeach
																	</td>
																@else
																	@if (isset($xosorecord->rawBet->ticketList))
															<td style="text-align: left;">
															@foreach($xosorecord->rawBet->ticketList as $ticketItem)

																Cược {{isset($ticketItem->betChoice_en) ? $ticketItem->betChoice_en : ''}}
																<br>
																{{$xosorecord->rawBet->productName_en}} - {{$xosorecord->rawBet->gameName_en}}
																<br>
																<?php
																	// $date = new DateTime($ticketItem->betTime, new DateTimeZone('GMT-4'));
																	// $date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
																	// echo $date->format('Y-m-d H:i:s');
																?>
															@endforeach
															</td>
                                            @else
																<td style="    text-align: left;">
																	Cược {{$xosorecord->rawBet->betChoice}}
																	<br>
																	{{$xosorecord->rawBet->point}} [ {{$xosorecord->rawBet->homeScore}} - {{$xosorecord->rawBet->awayScore}} ]
																	<br>
																	{{$xosorecord->rawBet->homeName}}
																	<br>
																	{{$xosorecord->rawBet->awayName}}
																	<br>
																	{{$xosorecord->rawBet->leagueName}}
																	<br>
																	<?php
																		$date = new DateTime($xosorecord->rawBet->matchDateTime, new DateTimeZone('GMT-4'));
																		$date->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));
																		echo $date->format('Y-m-d H:i:s');
																	?>

                                    							</td>
																@endif
																@endif
                                    <td>
                                        <?php
                                            switch ($xosorecord->result) {
                                                case 'won':
                                                    echo 'Thắng';
                                                    break;
                                                case 'lose':
                                                    echo 'Thua';
                                                    break;
                                                case 'half won':
                                                    echo 'Thắng nửa';
                                                    break;
                                                case 'half lose':
                                                    echo 'Thua nửa';
                                                    break;
                                                case 'refund':
                                                    echo 'Hoàn tiền';
                                                    break;
                                                case 'reject':
                                                    echo 'Huỷ cược';
                                                    break;
                                                case 'draw':
                                                    echo 'Hoà';
                                                    break;
                                                default:
                                                    echo '';
                                                    break;
                                            }
                                        ?>
                                    </td>
									<!-- <td class="text_right text-bold">{{number_format($xosorecord->com,0)}}</td> -->
									@if ($key->game_code > 5000 && $key->game_code < 6000)
                                                    <td>{{number_format($xosorecord->rawBet->txns[0]->detail[0]->odds, 2)}}</td>
                                                @else
                                                    <td>{{number_format(isset($xosorecord->rawBet->odds) ? $xosorecord->rawBet->odds : 0, 2)}}</td>
                                                @endif

									
																@else
																	<td class="text_right text-bold">{{($xosorecord->SerialID)}}</td>
																	<td class="text_right text-bold">{{($xosorecord->result)}}</td>
																	<td class="text_right text-bold">{{number_format($xosorecord->com,0)}}</td>
																@endif

															@endif

																@if($xosorecord->game_id < 3000) <td class="text_right"> @if ($xosorecord->exchange_rates != 0)
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
																	$countbetnumber = count(explode(',', $xosorecord->bet_number));
																	$Ank = XoSoRecordHelpers::fact($countbetnumber) / XoSoRecordHelpers::fact($fact) / XoSoRecordHelpers::fact($countbetnumber - $fact);
																	$betpoint = $xosorecord->total_bet_money / $xosorecord->exchange_rates / $Ank;
																	$total_point += $betpoint;
																	?>
																	{{number_format($betpoint,0)}}
																	@else
																	<?php
																	$total_point += ($xosorecord->total_bet_money / $xosorecord->exchange_rates);
																	?>
																	{{number_format($xosorecord->total_bet_money/$xosorecord->exchange_rates,0)}}
																	@endif
																	<?php
																	// $total_point += $xosorecord->total_bet_money/$xosorecord->exchange_rates;
																	?>
																	@else
																	0
																	@endif
																	</td>
																	@endif
																	@if ($xosorecord->locationslug == 60)
																		<td class="text_right text-bold">{{number_format($xosorecord->total_bet_money*1000,0)}}</td>
																	@else
																	<td class="text_right text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td>
																	@endif
																	<!-- <td class="text_right text-bold">{{number_format($xosorecord->total_bet_money,0)}}</td> -->
																	<?php
																	$win_money = $xosorecord->total_win_money;
																	if ($xosorecord->locationslug == 60)
																		$win_money = $xosorecord->total_win_money*1000;
																	else
																		$win_money = $xosorecord->total_win_money;
																	// fix tra thuong
																	if ($win_money > 0 && $xosorecord->game_id < 3000) {
																		if (
																			$xosorecord->game_id == 15 || $xosorecord->game_id == 16
																			|| $xosorecord->game_id == 316 || $xosorecord->game_id == 416  || $xosorecord->game_id == 516  || $xosorecord->game_id == 616
																			|| $xosorecord->game_id == 115 || $xosorecord->game_id == 116
																		) {
																			//|| $xosorecord->game_id == 19|| $xosorecord->game_id == 20 || $xosorecord->game_id == 21  || $xosorecord->game_id == 16
																		} else
																			$win_money -= $xosorecord->total_bet_money;
																	}
																	?>
																	@if($win_money>0)
																	<td class="text_right text_bold">{{number_format($win_money,0)}}
																		@elseif ($win_money<0) <td class="text_right text_bold" style=" color:red;">{{number_format($win_money,0)}}
																			@elseif ($win_money==0)
																	<td class="text_right text_bold">
																		@if($xosorecord->game_id < 3000) Chưa xử lý @else 0 @endif @endif </td>
																			@if($xosorecord->game_id < 3000) <td>{{isset($xosorecord->ipaddr)?$xosorecord->ipaddr:"" }}</td>
																	@endif

														</tr> @endif @endforeach
													</tbody>
													<tfoot>
														<tr>

															@if($key->game_code < 3000) <td colspan="7" class="text_right pr10">Tổng cộng</td>
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
					</div>
					@else
					<td colspan="6" class="text_right pr10">Tổng cộng</td>
					<td class="text_right pr10 suminvoice">{{number_format($total_bet_accept_money,0)}}</td>
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
</div>
@endif

@endforeach
</div>
</div>
</div>
@endsection
@section('js_call')
<script src="/assets/admin/js/report.js?v=1.01111"></script>
@endsection