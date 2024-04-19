@extends('admin.admin-template')

@section('content')

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<!-- <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Thống kê hôm nay</a></li>
		<li class=""><a href="#tab_2" data-toggle="tab">Thống kê tài khoản</a></li>
		<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="true">Thông báo</a></li> -->
		<li class="active"><a href="#tab_4" data-toggle="tab" aria-expanded="true">Thống kê theo mã</a></li>
		<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Thống kê hôm nay</a></li>
	</ul>
	<div class="tab-content">
		
		<div class="tab-pane active" id="tab_4">
			
			<div class="row">
				<div class="col-xs-12">
					<?php

					function build_sorter($key)
					{
						return function ($a, $b) use ($key) {
							return $a[$key] < $b[$key] ? 1 : 0;
						};
					}

					$totalByNumber = array();
					$gameall = GameHelpers::GetAllGame(0);
                    $arrUser = UserHelpers::GetAllUserV2(Auth::user());
					$rs[7] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 7)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();
                    $rs[12] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 12)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();
                    $rs[14] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money) AS sumbet'))
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
								    echo ('<th>' . 'Số đánh' . '</th>');
									echo ('<th> Tổng đặt Lô</th>');
								?>
								<!-- <th>Giá trị</th> -->
							</tr>
						</thead>
						<tbody>
                            
							<?php
							//   print_r($totalByNumber);
    							$count = 0;
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    								// 	echo ('<tr><td class="text_center">' . $i . '' . $j . '</td>');
    									$k = 0;
									if (isset($rs[7][$i * 10 + $j])) {
									        echo ('<td class="text_center">' .( $i * 10 + $j +1 ). '</td>');
											echo ('<td class="text_center">' .$rs[7][$i * 10 + $j]->bet_number . '</td>');
											echo ('<td class="text_center">' . number_format($rs[7][$i * 10 + $j]->sumbet) . '</td>');
										} else {
										    echo ('<td class="text_center">' .( $i * 10 + $j +1). '</td>');
											echo ('<td class="text_center"></td>');
											echo ('<td class="text_center"></td>');
										}
    										
    									// $count++;
    									echo ('</tr>');
								}

							?>

									


						</tbody>
						<tfoot>
						</tfoot>
					</table>
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important; display: inline; margin-left: 30px !important">
						<col width="20">
						<col width="80">
						<thead>
							<tr>
								<?php 
									echo ('<th>' . 'STT' . '</th>');
								    echo ('<th>' . 'Số đánh' . '</th>');
									echo ('<th> Tổng đặt Đề</th>');
								?>
								<!-- <th>Giá trị</th> -->
							</tr>
						</thead>
						<tbody>
                            
							<?php
							//   print_r($totalByNumber);
    							$count = 0;
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    								// 	echo ('<tr><td class="text_center">' . $i . '' . $j . '</td>');
    									$k = 0;
									if (isset($rs[14][$i * 10 + $j])) {
									        echo ('<td class="text_center">' .( $i * 10 + $j +1 ). '</td>');
											echo ('<td class="text_center">' .$rs[14][$i * 10 + $j]->bet_number . '</td>');
											echo ('<td class="text_center">' . number_format($rs[14][$i * 10 + $j]->sumbet) . '</td>');
										} else {
										    echo ('<td class="text_center">' .( $i * 10 + $j +1). '</td>');
											echo ('<td class="text_center"></td>');
											echo ('<td class="text_center"></td>');
										}
    										
    									// $count++;
    									echo ('</tr>');
								}

							?>

									


						</tbody>
						<tfoot>
						</tfoot>
					</table>
					
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important; display: inline; margin-left: 30px !important">
						<col width="20">
						<col width="80">
						<thead>
							<tr>
								<?php 
									echo ('<th>' . 'STT' . '</th>');
								    echo ('<th>' . 'Số đánh' . '</th>');
									echo ('<th style="width: 200px"> Tổng đặt Nhất</th>');
								?>
								<!-- <th>Giá trị</th> -->
							</tr>
						</thead>
						<tbody>
                            
							<?php
							//   print_r($totalByNumber);
    							$count = 0;
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    								// 	echo ('<tr><td class="text_center">' . $i . '' . $j . '</td>');
    									$k = 0;
									if (isset($rs[12][$i * 10 + $j])) {
									        echo ('<td class="text_center">' .( $i * 10 + $j +1 ). '</td>');
											echo ('<td class="text_center">' .$rs[12][$i * 10 + $j]->bet_number . '</td>');
											echo ('<td class="text_center">' . number_format($rs[12][$i * 10 + $j]->sumbet) . '</td>');
										} else {
										    echo ('<td class="text_center">' .( $i * 10 + $j +1). '</td>');
											echo ('<td class="text_center"></td>');
											echo ('<td class="text_center"></td>');
										}
    										
    									// $count++;
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

use Illuminate\Support\Facades\Auth;

					$gameList = GameHelpers::GetAllGameByParentID(0, 1);
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
							$gamechilderList = GameHelpers::GetAllGameByParentID($game['game_code'], 1);
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
					$gameList = GameHelpers::GetAllGameByParentID(0, 4);
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
								$gamechilderList = GameHelpers::GetAllGameByParentID($game['game_code'], 4);
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
	</div>
	<!-- /.tab-content -->
</div>


@endsection