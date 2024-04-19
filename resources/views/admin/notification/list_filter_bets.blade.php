		<?php

use App\Helpers\MinigameHelpers;

 $countI = count($notifications); 
?>

		@foreach($notifications as $key=>$item)
		<?php 
			if (!isset($item)){
				continue;
			} 
		?>

<div class="col-lg-12 line-break" style="text-align: left !important;background: #3f86c3; color:white;" bis_skin_checked="1">
							<label style="display: flex; align-items: center;">{{$item->name}} {{isset($item->id) ? ($item->id) : "" }}
								<div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
									<em style="font-weight:500; font-size:12px;">{{isset($item->created_at) ? $item->created_at : ""}}</em>
									
								</div>
							</label>
						</div>
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
						@if ($item->locationslug == 70)
				<div style="text-align: left;">
					@if(isset($item->rawBet))
						@if($item->rawBet->bet_type != "parlay")
							@if($item->rawBet->bet_type == "outright")
								<span style="color:black; font-weight:700;">Chung cuộc</span>
								<br>
							@endif
							<span style="color:#2596be; font-weight:700;"> {{$item->rawBet->bet_type_txt}}</span>
							<br>
							<b>{{$item->rawBet->bet_on_txt}}</b> <b @if($item->rawBet->bet_odd <= 0) style="color:red;" @endif>{{"@"}}{{number_format(isset($item->rawBet->bet_odd) ? $item->rawBet->bet_odd : 0, 2)}} @if(str_contains($item->rawBet->bet_type,"#my")) MY @else DEC @endif </b> <b> @if (isset($item->rawBet->bet_match_current)) ({{XoSoRecordHelpers::converScoreMatch($item->rawBet->bet_type,$item->rawBet->bet_match_current)}}) @endif</b>
							<br>
							@if($item->rawBet->bet_type != "outright")
								{{$item->rawBet->m_tnHomeName}} vs {{$item->rawBet->m_tnAwayName}}
								<br>
								<b>{{isset($item->rawBet->m_tnName) ? $item->rawBet->m_tnName : ""}}</b>
								<br>
								{{isset($item->betTime) && $item->betTime != "" ? $item->betTime : (isset($item->rawBet->kickoffVN) ? $item->rawBet->kickoffVN : "")}}
								<br>
							@endif
							<b>Đặt cược {{number_format($item->total_bet_money)}}</b>
						@else
							<?php
							$parlay = json_decode($item->rawBet->parlay);
							$detailMatchOnBetLst = isset($item->rawBet->bet_match_current) ? json_decode($item->rawBet->bet_match_current) : null;
							// var_dump($parlay);
							$bet_data_lst = json_decode($item->rawBet->bet_data);
							$bet_ons = json_decode($item->rawBet->parlay_money);
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
							<span style="color:#2596be; font-weight:700;"> {{$item->rawBet->bet_type_txt}} {{$strBet_on}}</span>
							<br>
							@foreach($parlay as $parlayOne)
								<?php
								$match_id = $parlayOne->match_id;
								$bet_type = $parlayOne->betting_type_id;

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
								<br>
								<span>-----</span>
								<br>
							@endforeach

							@foreach ($bet_ons as $bet_on)
								<?php if ($bet_on->money == 0) continue; ?>
								<span class="flex items-center space-x-[5px] whitespace-nowrap"><span>Đặt Cược: {{$bet_on->nameParlay}}</span>
									<span class="flex text-sm text-primary">{{number_format($bet_on->money)}}</span>
								</span>
								<br>
							@endforeach
						@endif
					@endif
				</div>
			@endif
			@if ($item->locationslug == 1)
				<?php
				$maxLengthSplit = 51;
				$text = $item->content;
				$resultSplit = "";
				if (strlen($text) > $maxLengthSplit) {
					$temp = $text;
					$isShowName = true;
					$countWhile = 0;
					while (true) {
						$countWhile++;
						if ($countWhile > 100) break;
						$sub_temp = substr($temp, 0, $maxLengthSplit);
						$temp = substr($temp, $maxLengthSplit);
						$resultSplit .= ($sub_temp . "<br>");
						$isShowName = false;
						if (strlen($temp) == 0) break;
						if ($temp[0] == ",") $temp = substr($temp, 1);
						if (strlen($temp) <= $maxLengthSplit) {
							$sub_temp = substr($temp, 0, strlen($temp));
							$resultSplit .= ($sub_temp);
							break;
						}
					}
				} else
					$resultSplit = $item->content;
				?>
				<div style="text-align: left;">{!!$resultSplit!!}
					<br>
					<b>Đặt cược {{number_format($item->total_bet_money)}}</b>
				</div>
			@endif

			@if ($item->locationslug == 80)
				
				<div style="text-align: left;">
				{{$item->rawBet->gameName}}
				<br>
				{{isset($item->rawBet->game_result_id) ? ("Mã ván " . $item->rawBet->game_result_id) : ""}}
				<br>
				{{MinigameHelpers::convertGametype($item->rawBet->choice,$item->game_id)}} @ {{$item->rawBet->odd}}
				<br>
				{{$item->rawBet->resultTxt}}
					<br>
					<b>Đặt cược {{number_format($item->total_bet_money)}}</b>
				</div>
			@endif
						</div>
				<br><br>
		
		@endforeach

<span>Tìm thấy <mark>{{count($notifications)}}</mark> thông báo. Bạn đang ở trang 1 trên tổng số 1 trang</span>