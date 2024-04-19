<div class="row" style="text-align: center">
	<div class="col-lg-12 col-md-12 col-sm-12 ">
		<div class="portfolioFilter">
		<ul class="nav nav-pills m-b-30">
			@for($i=0;$i<10;$i++)
			<li>
				<a class="btn btn-default btn-custom waves-effect waves-light btn-sm" href="#" data-filter=".{{$i}}">{{$i}}</a>
			</li>
			@endfor
		</ul>
		</div>
	</div>
</div>
<?php
	// $user = Auth::user();
	$dataAll = GameHelpers::GetGame_AllNumber($game['game_code']);
	$datachuan = $customer_type;
?>

<div class="row port">
	<div class="portfolioContainer m-b-15">
		@for($t=0;$t<10;$t++)
		<div class="col-sm-12 col-lg-12 col-md-12 {{$t}}">
			@for($k=0;$k<10;$k++)
				<div class="row">
					
					@for($l=0;$l<10;$l++)
						<div id="{{$game['game_code'].'_'.$t.$k.$l}}" class="mix board_two" style="border: #C3C3C3 1px solid!important;margin: 1px;">
							<div class="row" style="text-align: center">
								<div class="badge" style="font-size: 14px;
margin: 3px 0;
padding: 3px;">
                                    <?php

										$data = null;
										foreach($dataAll as $struct) {
											if ($t.$k.$l == $struct->number) {
												$data = $struct;
												break;
											}
										}
										// $user = Auth::user();
										// $data = GameHelpers::GetGame_Number($game['game_code'],$t.$k.$l);
										// $datachuan = GameHelpers::GetByCusTypeGameCode($game['game_code'],$user->customer_type);
										$exchange_rates = "";

										if ( count($data)>0 && count($datachuan)>0){
										$exchange_rates = $datachuan['exchange_rates'];
										if ($data['exchange_rates'] > $datachuan['exchange_rates']){
											$exchange_rates = $data['exchange_rates'];
										}
										$a = $data['a'];
										$x = $data['x'];
										$total = $data['total'];
										}else
										if(count($data)>0) {
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
										$total = $data['total'];
										}
										else{
										if(count($datachuan)>0){
											$exchange_rates =  $datachuan['exchange_rates'];
										}
										else
										{
											$exchange_rates =  $game['exchange_rates'];
										}
										$a = $data['a'];
										$x = $data['x'];
										$total = 0;
										}
										// if(count($data)>0) {
										// 	if(count($datachuan)){
										// 		$g = bcadd($game['exchange_rates'],'0',2);
										// 		$num = bcadd($datachuan['exchange_rates'],'0',2);
										// 		$chuan = bcadd($data['exchange_rates'],'0',2);
										// 		$exchange_rates =  round($chuan*$num/$g);
										// 	}
										// 	else
										// 	{
										// 		$exchange_rates = $data['exchange_rates'];
										// 	}
										// }
										// else{
										// 	if(count($datachuan)){
										// 		$exchange_rates =  $datachuan['exchange_rates'];
										// 	}
										// 	else
										// 	{
										// 		$exchange_rates =  $game['exchange_rates'];
										// 	}
										// }

                                    // $data = GameHelpers::GetGame_Number($game['game_code'],$t.$k.$l);
                                    // $exchange_rates = "";
                                    // $a = "";
                                    // $x = "";
                                    // if(count($data)>0) {
                                    //     $exchange_rates = $data[0]['exchange_rates'];
                                    //     if ( $data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                                	// $exchange_rates = $customer_type['exchange_rates'];
                                    //     $a = $data[0]['a'];
                                    //     $x = $data[0]['x'];
									// 	$total = $data[0]['total'];
                                    // }
                                    // else{
                                    //     $exchange_rates =  $customer_type['exchange_rates'];
                                    //     $a = $game['a'];
                                    //     $x = $game['x'];
									// 	$total = 0;
                                    // }
                                    $totalBetNumber = XoSoRecordHelpers::TotalBetTodayByNumber($game['game_code'],$t.$k.$l);
                                    ?>
									{{$t.$k.$l}}
								</div>
							</div>
							<div class="row @if( Auth::user()->roleid != 1)
						 hidden
						 @endif" style="text-align: center">
								<div class="popover-markup">
									<a href="javascript:void(0);" class="trigger @if( Auth::user()->roleid != 1)
						 not-active
						 @endif 
						 @if (Session::get('usersecondper') == 1)
						 not-active
                                    @endif
                                    exchange">{{number_format($exchange_rates, 0)}}</a>
									<div class="head hide">Thay đổi giá bán</div>
									<div class="content hide" >
										<div class="form-group">
											<div class="input-group " style="width: 250px">
												<input type="text" value="{{$exchange_rates}}" name="example-input2-group2 exchange_input" class="form-control" placeholder="">
												<span class="input-group-btn">
										            <a type="button" onclick="ChangeEx1000(this,'{{$t}}','{{$k}}','{{$l}}','{{$game['game_code']}}','exchange_rates','{{$exchange_rates}}');"  class="btn waves-effect waves-light btn-primary exchange_chg">Lưu</a>
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
									<a href="javascript:void(0);" class="trigger not-active a">{{number_format($a, 0)}}</a>
									<div class="head hide">Thay đổi giá trị biến</div>
									<div class="content hide" >
										<div class="form-group">
											<div class="input-group " style="width: 250px">
												<input type="text" value="{{$a}}"  name="example-input2-group2" class="form-control" placeholder="">
												<span class="input-group-btn">
										<a type="button" onclick="ChangeEx1000(this,'{{$t}}','{{$k}}','{{$l}}','{{$game['game_code']}}','a');" class="btn waves-effect waves-light btn-primary">Lưu</a>
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
									<a href="javascript:void(0);" class="trigger not-active x">{{number_format($x, 0)}}</a>
									<div class="head hide">Thay đổi giá trị biến</div>
									<div class="content hide" >
										<div class="form-group">
											<div class="input-group " style="width: 250px">
												<input type="text" value="{{$x}}" name="example-input2-group2" class="form-control" placeholder="">
												<span class="input-group-btn">
										<a type="button" onclick="ChangeEx1000(this,'{{$t}}','{{$k}}','{{$l}}','{{$game['game_code']}}','x');"  class="btn waves-effect waves-light btn-primary">Lưu</a>
									</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row total" style="text-align: center">
								{{number_format($totalBetNumber, 0)}}
							</div>
						</div>
					@endfor
					<div class="col-lg-1"></div>
				</div>
			@endfor
		</div>
		@endfor
	</div>
</div> <!-- End row -->
