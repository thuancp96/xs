<?php

use App\Helpers\GameHelpers;

	if ($game['game_code'] >= 31 && $game['game_code'] <= 55){
		$dataAll = GameHelpers::GetGame_AllNumber(24);
		$datachuan = $customer_type;
	}else{
		$dataAll = GameHelpers::GetGame_AllNumber($game['game_code']);
		// $datachuan = GameHelpers::GetByCusTypeGameCode($game['game_code'],$user->customer_type);
		$datachuan = $customer_type;
	}
	$locknumber = GameHelpers::LockNumber($game['game_code']);
	$locknumberRed = GameHelpers::LockNumberRed($game['game_code']);
?>

@for($i=0;$i<10;$i++)
	<div class="row">
	
		@for($j=0;$j<10;$j++)
		<?php
			if ($game['game_code']>=721 && $game['game_code'] <= 739)
			if($i >0 || $j > 0) continue;
			?>
			<div class="mix board_two" id="{{$game['game_code'].'_'.$i.$j}}" style="border: #C3C3C3 1px solid!important;margin: 1px;">
				<div class="row" style="text-align: center" @if( Auth::user()->roleid == 1) onclick="ConfirmDialog(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','locknumber', '{{str_contains($locknumber,$i.$j) || str_contains($locknumberRed,$i.$j)? 1:0}}');" @endif>
					<div class="badge" style="font-size: 14px;
										margin: 3px 0;
										@if (str_contains($locknumber, ''.$i.$j) )
											background-color: #4c5667;
										@endif
										@if (Auth::user()->roleid == 1 && str_contains($locknumberRed, ''.$i.$j) )
											background-color: red;
										@endif
										padding: 3px;">
						<?php
							$data = null;
							foreach($dataAll as $struct) {
								if ($i.$j == $struct->number) {
									$data = $struct;
									break;
								}
							}

							$exchange_rates = "";
							if ($data != null && count($datachuan)>0){
								$exchange_rates = $datachuan['exchange_rates'];
								if ($data['exchange_rates'] > $datachuan['exchange_rates']){
									$exchange_rates = $data['exchange_rates'];
								}
								$a = $data['a'];
                                $x = $data['x'];
								$y = $data['y'];
								$total = $data['total'];
							}else
							if($data != null) {
								// if(count($datachuan)){
								// 	$g = bcadd($game['exchange_rates'],'0',2);
								// 	$num = bcadd($datachuan['exchange_rates'],'0',2);
								// 	$chuan = bcadd($data['exchange_rates'],'0',2);
								// 	$exchange_rates =  round($chuan*$num/$g);
								// }
								// else
								// {
									
								// }
								$exchange_rates = $data['exchange_rates'];
								$a = $data['a'];
                                $x = $data['x'];
								$y = $data['y'];
								$total = $data['total'];
							}
							else{
								if(isset($datachuan) && is_array($datachuan) && count($datachuan)>0){
									$exchange_rates =  $datachuan['exchange_rates'];
								}
								else
								{
									$exchange_rates =  $game['exchange_rates'];
								}
								$a = $game['a'];
                                $x = $game['x'];
								$y = 0;
								$total = 0;
							}

							// $data = GameHelpers::GetGame_Number($game['game_code'],$i.$j);
                            // $exchange_rates = "";
                            // $a = "";
                            // $x = "";
                            // if(count($data)>0) {
                            //     $exchange_rates = $data[0]['exchange_rates'];
                            //     if( $exchange_rates < $customer_type['exchange_rates'])
                            //     	$exchange_rates = $customer_type['exchange_rates'];
                            //     $a = $data[0]['a'];
                            //     $x = $data[0]['x'];
							// 	$total = $data[0]['total'];
                            // }
                            // else
                            // {
                            //     $exchange_rates =  $customer_type['exchange_rates'];
                            //     $a = $game['a'];
                            //     $x = $game['x'];
							// 	$total = 0;
                            // }
							
							if ($game['game_code']==24){
								$totalBetNumber = 0;
								$totalBetNumberThau = 0;
								// for($k=31;$k<=55;$k++)
								// {
									// $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber($k,$i.$j);
									$total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j.'-'.Auth::user()->id,[0,0]);
									// $total = Cache::get('TotalBetTodayByNumberThau-'.$game_code.'-'.$bet_number,[0,0]);
									$totalBetNumber = $total[0];
									$totalBetNumberThau = $total[1];
								// }
							}else{
								// $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($game['game_code'],$i.$j);
								$total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j.'-'.Auth::user()->id,[0,0]);
								$totalBetNumber = $total[0];
								$totalBetNumberThau = $total[1];
							}
						?>
						{{$i.$j}}
					</div>
				</div>
				<div class="row" style="text-align: center">
					<div class="popover-markup">
						<a href="javascript:void(0);" class="trigger not-active @if( Auth::user()->roleid != 1)
						 not-active
						 @endif 
						 @if (Session::get('usersecondper') == 1)
						 not-active
                                    @endif
                                    exchange">
									{{number_format($exchange_rates, 0)}}
							
						</a>
						<div class="head hide">Thay đổi giá bán</div>
						<div class="content hide" >
							<div class="form-group">
								<div class="input-group " style="width: 250px">
									<input type="text" value="{{$exchange_rates}}"  name="example-input2-group2" class="form-control exchange_input" placeholder="">
									<span class="input-group-btn">
										<a type="button" onclick="ChangeEx(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','exchange_rates','{{$exchange_rates}}');"  class="btn waves-effect waves-light btn-primary exchange_chg">Lưu</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row @if( Auth::user()->roleid != 1)
						 hidden
						 @endif" style="text-align: center">
					<div class="popover-markup">
						<a href="javascript:void(0);" class="trigger y" style="background-color: yellow;"> 
							 + {{number_format($y, 0)}}
						</a>
						<div class="head hide">Thay đổi giá trị biến</div>
						<div class="content hide" >
							<div class="form-group">
							
								<div class="input-group " style="width: 250px">

									<span class="input-group-btn">
										<a type="button" onclick="ChangeInputY(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','ChangeInputSubY');"  class="btn waves-effect waves-light btn-primary ChangeInputSubY" style="width: 60px;">-</a>
									</span>

									<input type="text" value="{{isset($y) ? $y : 0}}" name="example-input2-group2" class="form-control" placeholder="">

									<span class="input-group-btn">
										<a type="button" onclick="ChangeInputY(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','ChangeInputAddY');"  class="btn waves-effect waves-light btn-primary ChangeInputAddY" style="width: 60px;">+</a>
									</span>

								</div>
								
								</br>

								<span class="input-group-btn">
									<a type="button" onclick="ChangeY(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','y','{{$exchange_rates}}');"  class="btn waves-effect waves-light btn-primary">Lưu</a>
								</span>

								<!-- <span class="input-group-btn">
									<a type="button" onclick="LockNumber(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','locknumber');"  class="btn waves-effect waves-light btn-warning">Khóa cược</a>
								</span> -->
							</div>
							
						</div>
					</div>
				</div>
				<div class="row hidden @if( Auth::user()->roleid != 1)
						 hidden
						 @endif" style="text-align: center">
					<div class="popover-markup">
						<a href="javascript:void(0);" class="trigger not-active x">
							{{number_format($x, 0)}}
						</a>
						<div class="head hide">Thay đổi giá trị biến</div>
						<div class="content hide" >
							<div class="form-group">
								<div class="input-group " style="width: 250px">
									<input type="text" value="{{$x}}" name="example-input2-group2" class="form-control" placeholder="">
									<span class="input-group-btn">
										<a type="button" onclick="ChangeEx(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','x');"  class="btn waves-effect waves-light btn-primary">Lưu</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				@if( Auth::user()->roleid == 1)
				<div class="row total" style="text-align: center">
					{{number_format($totalBetNumber, 0)}}
				</div>

				<div class="row totalThau " style="text-align: center" hidden>
					* {{number_format($totalBetNumberThau, 0)}}
				</div>

				@endif

				@if( Auth::user()->roleid == 2)

				<div class="row total" style="text-align: center">
					{{number_format($totalBetNumber, 0)}}
				</div>

				<div class="row totalThau " style="text-align: center">
					* {{number_format($totalBetNumberThau, 0)}}
				</div>

				@endif
			</div>
		@endfor
	<div class="col-lg-1"></div>
	</div>
@endfor