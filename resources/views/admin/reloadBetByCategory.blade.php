<?php

use App\Helpers\GameHelpers;
use App\Helpers\UserHelpers;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

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
					// $startDate = $;
					// $endDate = date('d-m-Y');
                    
					// echo $startDate . ' ' . $endDate;
					$begin = new DateTime($startDate);
					$end = new DateTime($endDate);
					if ($end > (new DateTime()))
						$end = new DateTime();
					$end->modify('+1 day');
						
					$interval = DateInterval::createFromDateString('1 day');
					$period = new DatePeriod($begin, $interval, $end);

					$counttotalMember = 0;
					$counttotalThau = 0;
					$countwinloseAdmin = 0;
					$countwinloseMember = 0;
					// echo Auth::user()->id;
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
                                    if ($game_id == 9 || $game_id == 10 || $game_id == 11 ) $game_id = 2;

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