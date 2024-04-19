<?php
							$locknumber = GameHelpers::LockNumber($game['game_code']);
							$locknumberRed = GameHelpers::LockNumberRed($game['game_code']);
							// echo $locknumber;
							?>
<div class="row" style="text-align: center" @if( Auth::user()->roleid == 1) onclick="ConfirmDialog(this,'{{$i}}','{{$j}}','{{$game['game_code']}}','locknumber', '{{str_contains($locknumber,$i.$j)||str_contains($locknumberRed,$i.$j)? 1:0}}');" @endif>
					<div class="badge" style="font-size: 14px;
					@if (str_contains($locknumber, ''.$i.$j) )
											background-color: #4c5667;
										@endif
										@if (Auth::user()->roleid == 1 && str_contains($locknumberRed, ''.$i.$j) )
											background-color: red;
										@endif
					margin: 3px 0;
					padding: 3px;" >
						<?php
							// $locknumber = GameHelpers::LockNumber($game['game_code']);
							$data = GameHelpers::GetGame_Number($game['game_code'],$i.$j);
                            $exchange_rates = "";
                            $a = "";
                            $x = "";
							$y = "";
                            if(count($data)>0) {
                                $exchange_rates = $data[0]['exchange_rates'];
                                if ( $exchange_rates < $customer_type['exchange_rates'])
                                	$exchange_rates = $customer_type['exchange_rates'];
                                $a = $data[0]['a'];
                                $x = $data[0]['x'];
								$y = $data[0]['y'];
								$total = $data[0]['total'];
                            }
                            else{
                                $exchange_rates =  $customer_type['exchange_rates'];
                                $a = $game['a'];
                                $x = $game['x'];
								$y = 0;
								$total = 0;
                            }
                            // if ($game['game_code']==24){
								// $totalBetNumber=0;
								// $totalBetNumberThau=0;
								// $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber(22,$i.$j);
								// $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(23,$i.$j);
								// $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(25,$i.$j);
								// $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(26,$i.$j);
								// $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(27,$i.$j);
								// $totalBetNumber+= XoSoRecordHelpers::TotalBetTodayByNumber(28,$i.$j);
							// }else{
								// $total = XoSoRecordHelpers::TotalBetTodayByNumberThau($game['game_code'],$i.$j);
								$total = Cache::get('TotalBetTodayByNumberThau-'.$game['game_code'].'-'.$i.$j.'-'.Auth::user()->id,[0,0]);
								$totalBetNumber = $total[0];
								$totalBetNumberThau = $total[1];
							// }
						?>
						{{$i.$j}}
					</div>
				</div>
				<div class="row" style="text-align: center">
					<div class="popover-markup">
						<a href="javascript:void(0);" class="trigger @if( Auth::user()->roleid != 1)
						 not-active
						 @endif 
						 @if (Session::get('usersecondper') == 1)
						 not-active
                                    @endif exchange">
									@if ($game['game_code']!=24)
										{{number_format($exchange_rates, 0)}}
									@endif
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

<script type="text/javascript">
	$('.popover-markup>.trigger').popover({
		html: true,
		title: function () {
			return $(this).parent().find('.head').html();
		},
		content: function () {
			return $(this).parent().find('.content').html();
		}
	});
</script>