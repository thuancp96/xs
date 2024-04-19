<div class="row" style="text-align: center">
								<div class="badge" style="font-size: 14px;
margin: 3px 0;
padding: 3px;">
                                    <?php
                                    $data = GameHelpers::GetGame_Number($game['game_code'],$t.$k.$l);
                                    $exchange_rates = "";
                                    $a = "";
                                    $x = "";
                                    if(count($data)>0) {
                                        $exchange_rates = $data[0]['exchange_rates'];
                                        if ( $data[0]['exchange_rates'] < $customer_type['exchange_rates'])
                                	$exchange_rates = $customer_type['exchange_rates'];
                                        $a = $data[0]['a'];
                                        $x = $data[0]['x'];
										$total = $data[0]['total'];
                                    }
                                    else{
                                        $exchange_rates =  $customer_type['exchange_rates'];
                                        $a = $game['a'];
                                        $x = $game['x'];
										$total = 0;
                                    }
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