<?php
	$user = Auth::user();
	$dataAll = GameHelpers::GetGame_AllNumber($game['game_code']);
	$datachuan = GameHelpers::GetByCusTypeGameCode($game['game_code'],$user->customer_type);
?>

<div class="row">
	@for($s=-1;$s<10;$s++)
		<div class="col-1">
			<div class="checkbox checkbox-single" style="text-align: right">
				<input type="checkbox" id="Y_{{$game['game_code']}}_{{$t}}{{$s}}" onclick="SelectY_1000('{{$s}}')" aria-label="Single checkbox One">
				<label></label>
			</div>
		</div>
	@endfor
</div>
@for($k=0;$k<10;$k++)
	<div class="row">
		<div class="col-1">
			<div class="col-1">
				<div class="checkbox checkbox-single">
					<input type="checkbox" id="X_{{$game['game_code']}}_{{$t}}{{$k}}" onclick="SelectX_1000('{{$k}}')" aria-label="Single checkbox One">
					<label></label>
				</div>
			</div>
		</div>
		@for($l=0;$l<10;$l++)
			<div class="col-1 number_content bubble"  id="{{$game['game_code'].'_'.$t.$k.$l}}" style="border: #C3C3C3 1px solid!important;margin: 1px;height: 60px"  onclick="Select_Number($(this).children('div')[0],'{{$game['game_code']}}','{{$t.$k.$l}}')" >
				<div class="row number_content" style="text-align: center" id="select_{{$game['game_code'].'_'.$t.$k.$l}}" onclick="Select_Number(this,'{{$game['game_code']}}','{{$t.$k.$l}}');event.cancelBubble=true;">
					<div class="badge"  style="font-size: 14px;
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
						}
						else{
							if(count($datachuan)>0){
								$exchange_rates =  $datachuan['exchange_rates'];
							}
							else
							{
								$exchange_rates =  $game['exchange_rates'];
							}
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
						?>
						{{$t.$k.$l}}
					</div>
				</div>
				<div class="row" style="text-align: center">
					<label class="label_game exchange" id="exchange_{{$game['game_code'].'_'.$t.$k.$l}}">{{number_format($exchange_rates, 0)}}</label>
				</div>
				<div class="row" style="text-align: center">
					<input type="text" min="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="input_game" id="input_{{$game['game_code'].'_'.$t.$k.$l}}" onclick="event.cancelBubble=true;" onkeyup="KeyUpInputChange(this,'{{$game['game_code']}}','{{$t.$k.$l}}')"  value="">
				</div>
			</div>
		@endfor
	</div>
@endfor