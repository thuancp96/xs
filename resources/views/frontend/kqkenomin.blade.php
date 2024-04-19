<div class="keno_TK_KQ row">
						@if (isset($rs) && count($rs) < 1 )

							<div class=" col-sm-6 row">
							    <div class="boxTotal row">
    							<div class="col-6">
									<div class="rowKenoTK rowKenoTop leftTK"><span class="icKeno icChan"></span>CHẲN: <span id="tk_chan" class="tk">0</span></div>
									<div class="rowKenoTK rowKenoBot leftTK"><span class="icKeno icLe"></span>LẺ: <span id="tk_le" class="tk">0</span></div>
    							</div>
    							<div class="col-6">
									<div class="rowKenoTK rowKenoTop rightTK"><span class="icKeno icLon"></span>LỚN: <span id="tk_lon" class="tk">0</span></div>
									<div class="rowKenoTK rowKenoBot rightTK"><span class="icKeno icBe"></span>BÉ: <span id="tk_be" class="tk">0</span></div>
    							</div>
    							</div>
								<div class="clear"></div>
								<div class="boxTotal row">
									<div class="col-6">
										<div class="totalKeno leftTK">Tổng: <span id="tk_total" class="tk">0</span></div>
									</div>
									<div class="col-6">
										<div class="totalKeno rightTK">Kỳ sau: <span id="tk_countdown" class="tk">00:00</span></div>
									</div>
									<div class="clear" style="height:0"></div>
								</div>
							</div>
							<div class="boxKQKeno col-sm-6" id="kq">
								<div class="rowKQKeno"><span class="keno_ball" id="ball_1"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_2"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_3"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_4"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_5"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_6"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_7"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_8"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_9"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_10"><img src="/assets/images/wait_keno.svg"></span></div><div class="rowKQKeno"><span class="keno_ball" id="ball_11"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_12"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_13"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_14"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_15"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_16"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_17"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_18"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_19"><img src="/assets/images/wait_keno.svg"></span><span class="keno_ball" id="ball_20"><img src="/assets/images/wait_keno.svg"></span></div>
							</div>
							<div class="keno_waiting"><div class="keno_time_title">KỲ XỔ TIẾP THEO</div><div class="keno_time_waiting">00:01</div></div>

						@else
						<?php
							$kq = $rs['DB'];
							$kq1 = array_slice($kq,0,10);
							$kq2 = array_slice($kq,10,10);
							$time = explode(" ",$rs['updated_at'] . '');
							$time[1] = substr($time[1],0,5);
							$chan = 0;
							$be = 0;
							foreach($kq as $item){
								if ($item <= 40)
									$be++;
								if ($item %2 == 0)
									$chan++;
							}
						?>
						<div class=" col-sm-6  row">
						    
						    <div class="boxTotal row">
							<div class="col-6">
								<div class="rowKenoTK rowKenoTop leftTK"><span class="icKeno icChan"></span>CHẲN: <span id="tk_chan" class="tk">{{$chan}}</span></div>
								<div class="rowKenoTK rowKenoBot leftTK"><span class="icKeno icLe"></span>LẺ: <span id="tk_le" class="tk">{{20-$chan}}</span></div>
							</div>
							<div class="col-6">
								<div class="rowKenoTK rowKenoTop rightTK"><span class="icKeno icLon"></span>LỚN: <span id="tk_lon" class="tk">{{20-$be}}</span></div>
								<div class="rowKenoTK rowKenoBot rightTK"><span class="icKeno icBe"></span>BÉ: <span id="tk_be" class="tk">{{$be}}</span></div>
							</div>
							</div>

							<div class="clear"></div>
							<div class="boxTotal row">
								<div class="col-6">
									<div class="totalKeno leftTK">Tổng: <span id="tk_total" class="tk">{{$rs['1']}}</span></div>
								</div>
								<div class="col-6">
									<div class="totalKeno rightTK">Kỳ: <span id="tk_countdown" class="tk"> {{$rs['8']}} {{$time[1]}}</span></div>
								</div>
								<div class="clear" style="height:0"></div>
							</div>
						</div>
						<div class="boxKQKeno col-sm-6" id="kq">
							<div class="rowKQKeno">
										@foreach($kq1 as $item)
											<span class="keno_ball" id="ball_1">{{$item}}</span>
										@endforeach
							</div>

							<div class="rowKQKeno">
										@foreach($kq2 as $item)
											<span class="keno_ball" id="ball_1">{{$item}}</span>
										@endforeach
							</div>
						</div>
						@endif
	
	
</div> </br> </br>