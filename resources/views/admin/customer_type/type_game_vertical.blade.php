<style>
        .user_percent td {
            		padding: 0px !important;
            
            		/*margin-top: 5px;*/
    	}
    
    	.user_percent .form-control{
    		font-size: 12px !important;
    	}
    
    	/*.user_percent th{*/
    	/*	padding: 10px !important;*/
    	/*}*/
    	.user_percent input{
    		width: 70px !important;
    		padding: 10px !important;
    	}
    	.user_percent tbody tr td {
            font-size: 12px;
            padding-left: 11px !important;
            white-space: nowrap;
            overflow: hidden;
            text-align: center;
            vertical-align: middle !important;
        }

</style>

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-2">
		<!-- <button type="button" id="btn_OK" onclick="SaveChangeType('{{$type}}')" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button> -->
	</div>
</div>


</br>

@if(count($games) > 0)

<!-- <div class=""> -->
<div class="box-body table-responsive no-padding">

	<div class="col-md-12">

		<table class="table table-bordered table-striped dataTable user_percent">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold; border-bottom:solid 1px white !important"><span class="badge badge-blue">Chuẩn {{$type}} Miền Bắc</span></th></tr>
			<tr class="tablewidth120">
				<th></th>
				<th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				@if ($user->roleid == 6)
					<th>Cược tối đa(điểm)</th>
					<th>Tối đa/1 cược(điểm)</th>
                @endif
				
			</tr>
			</thead>
			<tbody>
			<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
					<tr>
						<td style="font-size:13px;text-align:left; padding-left:10px; font-weight:500; border-bottom:solid 1px white !important">{{$game['short_name']}}</td>
						<td>
						@if($user->roleid > 1 )
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							oninput="formatNumber(this)"
							@if($game['game_code'] == 15 || $game['game_code']==16 || $game['game_code'] == 115 || $game['game_code']==116)
							disabled
							@endif

						 	@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
							>
						@else
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
							data-parsley-min="0" 
							
							@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif >
						@endif
						</td>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="1" data-parsley-max="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0"
								@if($game['game_code'] != 15 && $game['game_code']!=16 && $game['game_code'] != 115 && $game['game_code']!=116)
								disabled
								@endif 
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@else
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
								data-parsley-max="999999999" data-parsley-min="1"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
						<td @if ($user->roleid != 6)
						 		hidden
                            @endif>
						@if($user->roleid > 1)
							<input type="text" value="{{$game['max_point']}}" class="form-control autonumber" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['max_point']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['max_point']:0}}" data-parsley-id="max_point_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0"
							disabled
							>
							@else
								<input type="text" value="{{$game['max_point']}}" class="form-control autonumber" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
								data-parsley-min="999999999" disabled>
							@endif
						</td>
						<td @if ($user->roleid != 6)
						 		hidden
                            @endif>
						@if($user->roleid > 1)
							<input type="text" value="{{$game['max_point_one']}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['max_point_one']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['max_point_one']:0}}" data-parsley-id="max_point_one_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							disabled
							>
							@else
							<input type="text" value="{{$game['max_point_one']}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
							data-parsley-min="999999999" disabled>
							@endif
						</td>
					</tr>
					<?php $count++;?>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

</br>

@endif

<script type="text/javascript">
    $( document ).ready(function() {
		// $('input').on('input',function (e) {
        //    $this = $(this);
        //    $this.val(Number($this.val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
        // });  
		$('.autonumber').autoNumeric('init',{ mDec:0});
    });
 
    </script>
