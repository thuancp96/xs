<?php
	use App\XoSoResult;
	$user = Auth::user();
	if ($game['game_code'] == 18){
		$dataAll = GameHelpers::GetGame_AllNumber(18);
		$datachuan = GameHelpers::GetByCusTypeGameCode(18,$user->customer_type);
	}else if ($game['game_code'] >= 31 && $game['game_code'] <= 55){
		$dataAll = GameHelpers::GetGame_AllNumber(24);
		$datachuan = GameHelpers::GetByCusTypeGameCode(24,$user->customer_type);
	}
	else{
		$dataAll = GameHelpers::GetGame_AllNumber($game['game_code']);
		$datachuan = GameHelpers::GetByCusTypeGameCode($game['game_code'],$user->customer_type);
	}
	
	if ($game['game_code'] == 18 || $game['game_code'] == 200 || $game['game_code'] == 29 || $game['game_code'] == 9 || $game['game_code'] == 10 || $game['game_code'] == 11){
		$datagoc = 21680;
		$now = date('Y-m-d');
		$kqxs = XoSoResult::where('location_id', 1)
		->where('date', $now)->get();
		if (count($kqxs) < 1)
			$kqxsdr = 0;
		else
			$kqxsdr = $kqxs->first()->Giai_8;
	}
?>

<div class="row">
    @for($i=-1;$i<10;$i++)
        <div class="col-1">
            <div class="checkbox checkbox-single">
                <input type="checkbox" id="Y_{{$game['game_code']}}_{{$i}}" onclick="SelectY('{{$i}}')" aria-label="Single checkbox One">
                <label></label>
            </div>
        </div>
    @endfor
</div>
@for($i=0;$i<10;$i++)
	<div class="row">
		<div class="col-1">
            <div class="checkbox checkbox-single">
                <input type="checkbox" id="X_{{$game['game_code']}}_{{$i}}" onclick="SelectX('{{$i}}')" aria-label="Single checkbox One">
                <label></label>
            </div>
        </div>
		@for($j=0;$j<10;$j++)
			<?php
				if ($game['game_code'] >= 700){
					if ($i*10+$j > 80 ) continue;
				}
			?>
			<div class="col-1 number_content bubble"  id="{{$game['game_code'].'_'.$i.$j}}" style="border: #C3C3C3 1px solid!important;margin: 1px;height: 60px"  onclick="Select_Number($(this).children('div')[0],'{{$game['game_code']}}','{{$i.$j}}') ">
				<div class="row number_content" style="text-align: center" id="select_{{$game['game_code'].'_'.$i.$j}}" onclick="Select_Number(this,'{{$game['game_code']}}','{{$i.$j}}');event.cancelBubble=true;">
					<div class="badge"  style="font-size: 14px;
margin: 3px 0;
padding: 3px;">
						<?php
						// $data = array_filter(
						// 	$dataAll,
						// 	function ($e) {
						// 		return $e->number == $i.$j;
						// 	}
						// )->first();

						$data = [];
						foreach($dataAll as $struct) {
							if ($i.$j == $struct->number) {
								$data = $struct;
								break;
							}
						}

						// GameHelpers::GetGame_Number($game['game_code'],$i.$j);
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
							if(isset($datachuan['exchange_rates'])){
								$exchange_rates =  $datachuan['exchange_rates'];
							}
							else
							{
								$exchange_rates =  $game['exchange_rates'];
							}
						}
						if ($game['game_code'] == 18)
							if ($kqxsdr == 0) 
								$exchange_rates = 830*(27-1) + ($exchange_rates-$datagoc);
							else if ($kqxsdr >= 25)
								$exchange_rates = 0;
							else
								$exchange_rates = 830*(27-$kqxsdr-1) + ($exchange_rates-$datagoc)
						?>
						{{$i.$j}}
					</div>
				</div>
				<div class="row" style="text-align: center">
					<label class="label_game exchange" id="exchange_{{$game['game_code'].'_'.$i.$j}}">{{number_format($exchange_rates, 0)}}</label>
				</div>
				<div class="row" style="text-align: center">
					<input type="text" min="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="input_game" id="input_{{$game['game_code'].'_'.$i.$j}}" onclick="event.cancelBubble=true;" onkeyup="KeyUpInputChange(this,'{{$game['game_code']}}','{{$i.$j}}',event.keyCode)"  value="">
				</div>
			</div>
		@endfor
		
		
		<div class="col-1"></div>
	</div>
	
@endfor

@if ($game['game_code'] == 18)
<div class="row text_bold text_red" style="margin-left: 1px; display: inline-block; margin-top: 20px; margin-right: 10px; text-align: right; width: 100%;">
	Còn <div class="badge" id="kqxsdr_badge" style="font-size: 14px;margin: 3px 0;padding: 3px;margin-top: -2px;  display: inline;">{{27-2-$kqxsdr}}</div> kết quả
</div>
@endif

@if ($game['game_code'] == 200 || $game['game_code'] == 29 || $game['game_code'] == 9 || $game['game_code'] == 10 || $game['game_code'] == 11)
<div class="row text_bold text_red" style="margin-left: 1px; display: inline-block; margin-top: 20px; margin-right: 10px; text-align: right; width: 100%;">
		Còn <div class="badge" id="kqxsdr_badge{{$game['game_code']}}" style="font-size: 14px;margin: 3px 0;padding: 3px;margin-top: -2px;     display: inline;">{{27-$kqxsdr}}</div> kết quả
</div>
@endif
