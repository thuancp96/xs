@extends('admin.admin-template')

@section('content')

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
	<div class="row">
	<div class="col-sm-12">
		<div class="portlet"><!-- /primary heading -->
			<div class="portlet-heading">
				<h3 class="portlet-title text-dark text-uppercase">

					Xuất số super

				</h3>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

<div class="card-box">
	<div class="row">
		<div class="col-xs-12">

					<?php

					$totalByNumber = array();
					$gameall = GameHelpers::GetAllGame(0);
                    
					$rsPoint[7] = [];
					for ($i = 0; $i < 10; $i++)
						for ($j = 0; $j < 10; $j++) {
							$k = 0;
							$rsPoint[7][$i * 10 + $j] = 0;
					}

					$rsPoint[12] = [];
					for ($i = 0; $i < 10; $i++)
						for ($j = 0; $j < 10; $j++) {
							$k = 0;
							$rsPoint[12][$i * 10 + $j] = 0;
					}

					$rsPoint[14] = [];
					for ($i = 0; $i < 10; $i++)
						for ($j = 0; $j < 10; $j++) {
							$k = 0;
							$rsPoint[14][$i * 10 + $j] = 0;
					}

					$users = UserHelpers::GetAllUserChildv2Admin("luk79", Auth::user()->id, 2);

					foreach ($users as $userMaster) {

						$arrUser = UserHelpers::GetAllUserV2($userMaster);
						$thau = isset($userMaster->thau) ? $userMaster->thau : 0;
						$rs[7] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money/exchange_rates * '.$thau.'/100) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 7)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();

						for ($i = 0; $i < 10; $i++)
							for ($j = 0; $j < 10; $j++) {
								if (isset($rs[7][$i * 10 + $j])) {
									$rsPoint[7][(int)$rs[7][$i * 10 + $j]->bet_number] += $rs[7][$i * 10 + $j]->sumbet;
								}
							}

                    	$rs[12] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money/exchange_rates * '.$thau.'/100) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 12)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();

						for ($i = 0; $i < 10; $i++)
							for ($j = 0; $j < 10; $j++) {
								if (isset($rs[12][$i * 10 + $j])) {
									$rsPoint[12][(int)$rs[12][$i * 10 + $j]->bet_number] += $rs[12][$i * 10 + $j]->sumbet;
								}
							}

                    	$rs[14] = DB::table('xoso_record')
				        ->select('bet_number', DB::raw('SUM(total_bet_money/exchange_rates * '.$thau.'/100) AS sumbet'))
                        ->orderBy('sumbet', 'desc')
                        ->where('isDelete',false)
                        ->where('date',date('Y-m-d'))
                        ->where('game_id', 14)
                        ->whereIn('user_id', $arrUser)
                        ->groupBy('bet_number')
                        ->get();

						for ($i = 0; $i < 10; $i++)
							for ($j = 0; $j < 10; $j++) {
								if (isset($rs[14][$i * 10 + $j])) {
									$rsPoint[14][(int)$rs[14][$i * 10 + $j]->bet_number] += $rs[14][$i * 10 + $j]->sumbet;
								}
							}
					}

					$min7 = 99999;
					$min12 = 99999;
					$min14 = 99999;
					$rsPointReBuild[7] = [];
					for ($i = 0; $i < 10; $i++)
							for ($j = 0; $j < 10; $j++) {
								if (isset($rsPoint[7][$i * 10 + $j])) {
									if ($min7 > $rsPoint[7][$i * 10 + $j]) $min7 = $rsPoint[7][$i * 10 + $j];
									$rsPointReBuild[7][$i * 10 + $j] = 
											["bet_number" => $i . $j , "sumbet" => $rsPoint[7][$i * 10 + $j] ];
								}
							}

					$rsPointReBuild[12] = [];
					for ($i = 0; $i < 10; $i++)
							for ($j = 0; $j < 10; $j++) {
								if (isset($rsPoint[12][$i * 10 + $j])) {
									if ($min12 > $rsPoint[12][$i * 10 + $j]) $min12 = $rsPoint[12][$i * 10 + $j];
									$rsPointReBuild[12][$i * 10 + $j] = 
											["bet_number" => $i . $j , "sumbet" => $rsPoint[12][$i * 10 + $j]];
								}
							}

					$rsPointReBuild[14] = [];
					for ($i = 0; $i < 10; $i++)
							for ($j = 0; $j < 10; $j++) {
								if (isset($rsPoint[14][$i * 10 + $j])) {
									if ($min14 > $rsPoint[14][$i * 10 + $j]) $min14 = $rsPoint[14][$i * 10 + $j];
									// $rsPoint[14][(int)$rs[14][$i * 10 + $j]->bet_number] += $rs[14][$i * 10 + $j]->sumbet;
									$rsPointReBuild[14][$i * 10 + $j] = 
											["bet_number" => $i . $j , "sumbet" => $rsPoint[14][$i * 10 + $j]];
								}
							}

					usort($rsPointReBuild[7], function ($first, $second) {
						if (isset($second) && isset($first))
							return $first["sumbet"] < $second["sumbet"];
						else return true;
					});

					usort($rsPointReBuild[12], function ($first, $second) {
						if (isset($second) && isset($first))
							return $first["sumbet"] < $second["sumbet"];
						else return true;
					});

					usort($rsPointReBuild[14], function ($first, $second) {
						if (isset($second) && isset($first))
							return $first["sumbet"] < $second["sumbet"];
						else return true;
					});
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
										if (isset($rsPointReBuild[7][$i * 10 + $j]) && $rsPointReBuild[7][$i * 10 + $j]["sumbet"] > 0) {
											$arrLo[] = '<td class="text_center"><label style="color:black;">' .$rsPointReBuild[7][$i * 10 + $j]["bet_number"] . '</label><br><label style="color:red;">'. number_format($rsPointReBuild[7][$i * 10 + $j]["sumbet"]-$min7) . 'đ</label>'. '</td>';
										}
								}

								$arrDe = [];
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    									$k = 0;
										if (isset($rsPointReBuild[14][$i * 10 + $j]) && $rsPointReBuild[14][$i * 10 + $j]["sumbet"] > 0) {
											$arrDe[] = '<td class="text_center"><label style="color:black;">' .$rsPointReBuild[14][$i * 10 + $j]["bet_number"] . '</label><br><label style="color:red;">'. number_format($rsPointReBuild[14][$i * 10 + $j]["sumbet"]-$min7) . 'đ</label>'. '</td>';
										}
								}

								$arrNhat = [];
    							for ($i = 0; $i < 10; $i++)
    								for ($j = 0; $j < 10; $j++) {
    									$k = 0;
										if (isset($rsPointReBuild[12][$i * 10 + $j])&& $rsPointReBuild[12][$i * 10 + $j]["sumbet"] > 0) {
											$arrNhat[] = '<td class="text_center"><label style="color:black;">' .$rsPointReBuild[12][$i * 10 + $j]["bet_number"] . '</label><br><label style="color:red;">'. number_format($rsPointReBuild[12][$i * 10 + $j]["sumbet"]-$min7) . 'đ</label>'. '</td>';
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
</div>


@endsection