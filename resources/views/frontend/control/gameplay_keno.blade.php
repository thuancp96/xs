<div>

@for($i=721;$i<=739;$i++)

@if ($i==721 || $i==723 || $i==725 || $i==728 || $i==731 || $i==735)
<div class="row">
@endif

@if ($i >= 721 && $i<=724)
<div class="col-xs-6 number_content" id="{{$i}}_00" style="border: #C3C3C3 1px solid!important;margin: 1px;height: 70px;width:48%">
@endif

@if ($i >= 725 && $i<=730)
<div class="col-xs-4 number_content" id="{{$i}}_00" style="border: #C3C3C3 1px solid!important;margin: 1px;height: 70px;width:32%">
@endif

@if ($i >= 731 && $i <= 734)
<div class="col-xs-3 number_content" id="{{$i}}_00" style="border: #C3C3C3 1px solid!important;margin: 1px;height: 70px;width:48%">
@endif

@if ($i >= 735 )
<div class="col-xs-2 number_content" id="{{$i}}_00" style="border: #C3C3C3 1px solid!important;margin: 1px;height: 70px;width:19.1%">
@endif

<?php
?>
	<div class="row number_content keno_class" style="text-align: center" id="select_{{$i}}_00"  onclick="Select_Keno(this,'{{$i}}','00')">
		<div class="" style="font-size: 18px;margin: 3px 0;padding: 3px;" id="game_name_{{$i}}_00">
									{{$gameByCode['game'.$i]->name}}
		</div>
		<div class="hidden" id="odds_{{$i}}_00">
									{{$gameByCode['game'.$i]->odds}}
		</div>
	</div>

	<div class="row" style="text-align: center">
		<label class="label_game exchange" id="exchange_{{$i}}_00">{{$gameByCode['game'.$i]->exchange_rates}}</label>
	</div>

	<div class="row" style="text-align: center">
					<input type="text" min="0" onkeypress='return event.charCode >= 48 && event.charCode <= 57' class="input_game" id="input_{{$i}}_00" onkeyup="KeyUpInputChange(this,'{{$i}}','00',event.keyCode)"  value="">
				</div>
</div>

@if ($i==722 || $i==724 || $i==727 || $i==730 || $i==734 || $i==739)
</div>
@endif

@endfor

</div>