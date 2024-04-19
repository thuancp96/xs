<?php
	use App\XoSoResult;
	$user = Auth::user();
	$data2All = GameHelpers::GetGame_AllNumber($game2['game_code']);
	$datachuan2 = GameHelpers::GetByCusTypeGameCode($game2['game_code'],$user->customer_type);

	$data3All = GameHelpers::GetGame_AllNumber($game3['game_code']);
	$datachuan3 = GameHelpers::GetByCusTypeGameCode($game3['game_code'],$user->customer_type);

	$data4All = GameHelpers::GetGame_AllNumber($game4['game_code']);
	$datachuan4 = GameHelpers::GetByCusTypeGameCode($game4['game_code'],$user->customer_type);

	$dataxnAll = GameHelpers::GetGame_AllNumber($gamexn['game_code']);
	$datachuanxn = GameHelpers::GetByCusTypeGameCode($gamexn['game_code'],$user->customer_type);

	$datagoc = 21680;
	$now = date('Y-m-d');
	$kqxs = XoSoResult::where('location_id', 1)
	->where('date', $now)->get();
	if (count($kqxs) < 1)
		$kqxsdr = 0;
	else
		$kqxsdr = $kqxs->first()->Giai_8;
	
?>


<div class="row">
    @for($i=-1;$i<10;$i++)
        <div class="col-1">
            <div class="checkbox checkbox-single">
                <input type="checkbox" id="Y_{{$gamecode}}_{{$i}}" onclick="SelectY('{{$i}}')" aria-label="Single checkbox One">
                <label></label>
            </div>
        </div>
    @endfor
</div>
@for($i=0;$i<10;$i++)
	<div class="row">
		<div class="col-1">
            <div class="checkbox checkbox-single">
                <input type="checkbox" id="X_{{$gamecode}}_{{$i}}" onclick="SelectX('{{$i}}')" aria-label="Single checkbox One">
                <label></label>
            </div>
        </div>
		@for($j=0;$j<10;$j++)
			<?php
				// $user = Auth::user();
				// $data2 = GameHelpers::GetGame_Number($game2['game_code'],$i.$j);
				// $datachuan2 = GameHelpers::GetByCusTypeGameCode($game2['game_code'],$user->customer_type);

				$data2 = null;
				foreach($data2All as $struct) {
					if ($i.$j == $struct->number) {
						$data2 = $struct;
						break;
					}
				}

				$exchange_rates2 = "";
				if ( count($data2)>0 && count($datachuan2)>0){
					$exchange_rates2 = $datachuan2['exchange_rates'];
					if ($data2['exchange_rates'] > $datachuan2['exchange_rates']){
						$exchange_rates2 = $data2['exchange_rates'];
					}
				}else
				if(count($data2)>0) {
					// if(count($datachuan)){
					// 	$g = bcadd($game['exchange_rates'],'0',2);
					// 	$num = bcadd($datachuan['exchange_rates'],'0',2);
					// 	$chuan = bcadd($data['exchange_rates'],'0',2);
					// 	$exchange_rates =  round($chuan*$num/$g);
					// }
					// else
					// {
						
					// }
					$exchange_rates2 = $data2['exchange_rates'];
				}
				else{
					if(count($datachuan2)>0){
						$exchange_rates2 =  $datachuan2['exchange_rates'];
					}
					else
					{
						$exchange_rates2 =  $game2['exchange_rates'];
					}
				}

				// $data3 = GameHelpers::GetGame_Number($game3['game_code'],$i.$j);
				// $datachuan3 = GameHelpers::GetByCusTypeGameCode($game3['game_code'],$user->customer_type);
				
				$data3 = null;
				foreach($data3All as $struct) {
					if ($i.$j == $struct->number) {
						$data3 = $struct;
						break;
					}
				}

				$exchange_rates3 = "";
				if ( count($data3)>0 && count($datachuan3)>0){
					$exchange_rates3 = $datachuan3['exchange_rates'];
					if ($data3['exchange_rates'] > $datachuan3['exchange_rates']){
						$exchange_rates3 = $data3['exchange_rates'];
					}
				}else
				if(count($data3)>0) {
					// if(count($datachuan)){
					// 	$g = bcadd($game['exchange_rates'],'0',2);
					// 	$num = bcadd($datachuan['exchange_rates'],'0',2);
					// 	$chuan = bcadd($data['exchange_rates'],'0',2);
					// 	$exchange_rates =  round($chuan*$num/$g);
					// }
					// else
					// {
						
					// }
					$exchange_rates3 = $data3['exchange_rates'];
				}
				else{
					if(count($datachuan3)>0){
						$exchange_rates3 =  $datachuan3['exchange_rates'];
					}
					else
					{
						$exchange_rates3 =  $game3['exchange_rates'];
					}
				}

				// $data4 = GameHelpers::GetGame_Number($game4['game_code'],$i.$j);
				// $datachuan4 = GameHelpers::GetByCusTypeGameCode($game4['game_code'],$user->customer_type);

				$data4 = null;
				foreach($data4All as $struct) {
					if ($i.$j == $struct->number) {
						$data4 = $struct;
						break;
					}
				}

				$exchange_rates4 = "";
				if ( count($data4)>0 && count($datachuan4)>0){
					$exchange_rates4 = $datachuan4['exchange_rates'];
					if ($data4['exchange_rates'] > $datachuan4['exchange_rates']){
						$exchange_rates4 = $data4['exchange_rates'];
					}
				}else
				if(count($data4)>0) {
					// if(count($datachuan)){
					// 	$g = bcadd($game['exchange_rates'],'0',2);
					// 	$num = bcadd($datachuan['exchange_rates'],'0',2);
					// 	$chuan = bcadd($data['exchange_rates'],'0',2);
					// 	$exchange_rates =  round($chuan*$num/$g);
					// }
					// else
					// {
						
					// }
					$exchange_rates4 = $data4['exchange_rates'];
				}
				else{
					if(count($datachuan4)>0){
						$exchange_rates4 =  $datachuan4['exchange_rates'];
					}
					else
					{
						$exchange_rates4 =  $game4['exchange_rates'];
					}
				}

				$dataxn = null;
				foreach($dataxnAll as $struct) {
					if ($i.$j == $struct->number) {
						$data4 = $struct;
						break;
					}
				}

				$exchange_ratesxn = "";
				if ( count($dataxn)>0 && count($datachuanxn)>0){
					$exchange_ratesxn = $datachuanxn['exchange_rates'];
					if ($dataxn['exchange_rates'] > $datachuanxn['exchange_rates']){
						$exchange_ratesxn = $dataxn['exchange_rates'];
					}
				}else
				if(count($dataxn)>0) {
					// if(count($datachuan)){
					// 	$g = bcadd($game['exchange_rates'],'0',2);
					// 	$num = bcadd($datachuan['exchange_rates'],'0',2);
					// 	$chuan = bcadd($data['exchange_rates'],'0',2);
					// 	$exchange_rates =  round($chuan*$num/$g);
					// }
					// else
					// {
						
					// }
					$exchange_ratesxn = $dataxn['exchange_rates'];
				}
				else{
					if(count($datachuanxn)>0){
						$exchange_ratesxn =  $datachuanxn['exchange_rates'];
					}
					else
					{
						$exchange_ratesxn =  $gamexn['exchange_rates'];
					}
				}
			?>
			<div class="col-1 number_content xien"  id="{{$gamecode.'_'.$i.$j}}" style="border: #C3C3C3 1px solid!important;margin: 1px; text-indent: 2px;" onclick="Select_Number($(this).children('div')[0],'{{$gamecode}}','{{$i.$j}}')">
				<div class="row number_content" style="text-align: center" id="select_{{$gamecode.'_'.$i.$j}}" onclick="Select_Number(this,'{{$gamecode}}','{{$i.$j}}');event.cancelBubble=true;">
					<div class="badge"  style="font-size: 14px;margin: 3px 0;padding: 3px;">
						{{$i.$j}}
					</div>
				</div>
				<div class="row" style="text-align: center">
					x2 <label class="label_game exchange xien" id="exchange_{{$gamecode2.'_'.$i.$j}}">{{number_format($exchange_rates2, 0)}}</label>
				</div>
				<div class="row" style="text-align: center">
					x3 <label class="label_game exchange xien" id="exchange_{{$gamecode3.'_'.$i.$j}}">{{number_format($exchange_rates3, 0)}}</label>
				</div>
				<div class="row" style="text-align: center">
					x4 <label class="label_game exchange xien" id="exchange_{{$gamecode4.'_'.$i.$j}}">{{number_format($exchange_rates4, 0)}}</label>
				</div>
				<div class="row" style="text-align: center">
					xn <label class="label_game exchange xien" id="exchange_{{$gamecodexn.'_'.$i.$j}}">{{$exchange_ratesxn}}</label>
				</div>
				<div class="row" style="text-align: center">
					<input type="text" min="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="input_game" id="input_{{$gamecode.'_'.$i.$j}}" onclick="event.cancelBubble=true;" onkeyup="KeyUpInputChange(this,'{{$gamecode}}','{{$i.$j}}')"  value="">
				</div>
			</div>
		@endfor
		<div class="col-1"></div>
	</div>
	
	

@endfor

<div class="row text_bold text_red" style="margin-left: 1px; display: inline-block; margin-top: 20px; margin-right: 10px; text-align: right; width: 100%;">
		Còn <div class="badge" id="kqxsdr_badge2" style="font-size: 14px;margin: 3px 0;padding: 3px;margin-top: -2px; display: inline">{{27-$kqxsdr}}</div> kết quả
</div>
