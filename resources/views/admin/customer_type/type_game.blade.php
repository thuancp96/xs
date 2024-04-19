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
    		width: 120px !important;
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
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold; border-bottom:solid 1px white !important"><span class="badge badge-blue">Chuẩn {{$type}} Miền Bắc</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) continue;?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;?>
					<?php if ($game['game_code'] == 18) continue;?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
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
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td @if ($user->roleid != 6)
						 		hidden
                            @endif>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td @if ($user->roleid != 6)
						 		hidden
                            @endif>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		<table class="table table-bordered table-striped dataTable user_percent" hidden>

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold"><span class="badge badge-blue">Chuẩn {{$type}} Miền Nam + Miền Trung</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
				<?php if ($game['game_code'] < 300 || $game['game_code'] > 399) continue;
					?>
					<?php if ($game['game_code'] >= 331 && $game['game_code'] <= 355) 
					continue;?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}
					</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 300 || $game['game_code'] > 399) continue;
					?>
					<?php if ($game['game_code'] >= 331 && $game['game_code'] <= 355) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 300 || $game['game_code'] > 399) continue;
					?>
					<?php if ($game['game_code'] >= 331 && $game['game_code'] <= 355) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 300 || $game['game_code'] > 399) continue;
					?>
					<?php if ($game['game_code'] >= 331 && $game['game_code'] <= 355) 
					{$count++;continue;}?>
						<td>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 300 || $game['game_code'] > 399) continue;
					?>
					<?php if ($game['game_code'] >= 331 && $game['game_code'] <= 355) 
					 {$count++;continue;}?>
						<td>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		<table class="table table-bordered table-striped dataTable user_percent hidden">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold"><span class="badge badge-blue">Chuẩn {{$type}} Miền Nam Đài 2</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
					<?php if ($game['game_code'] < 400 || $game['game_code'] > 499) continue;
					?>
					<?php if ($game['game_code'] >= 431 && $game['game_code'] <= 455) 
					continue;?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 400 || $game['game_code'] > 499) continue;
					?>
					<?php if ($game['game_code'] >= 431 && $game['game_code'] <= 455) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 400 || $game['game_code'] > 499) continue;
					?>
					<?php if ($game['game_code'] >= 431 && $game['game_code'] <= 455) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 400 || $game['game_code'] > 499) continue;
					?>
					<?php if ($game['game_code'] >= 431 && $game['game_code'] <= 455) 
					{$count++;continue;}?>
						<td>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 400 || $game['game_code'] > 499) continue;
					?>
					<?php if ($game['game_code'] >= 431 && $game['game_code'] <= 455) 
					{$count++;continue;}?>
						<td>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		<table class="table table-bordered table-striped dataTable user_percent hidden">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold"><span class="badge badge-blue">Chuẩn {{$type}} Miền Trung Đài 1</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
					<?php if ($game['game_code'] < 500 || $game['game_code'] > 599) continue;
					?>
					<?php if ($game['game_code'] >= 531 && $game['game_code'] <= 555) 
					continue;?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 500 || $game['game_code'] > 599) continue;
					?>
					<?php if ($game['game_code'] >= 531 && $game['game_code'] <= 555) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 500 || $game['game_code'] > 599) continue;
					?>
					<?php if ($game['game_code'] >= 531 && $game['game_code'] <= 555) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 500 || $game['game_code'] > 599) continue;
					?>
					<?php if ($game['game_code'] >= 531 && $game['game_code'] <= 555) 
					{$count++;continue;}?>
						<td>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 500 || $game['game_code'] > 599) continue;
					?>
					<?php if ($game['game_code'] >= 531 && $game['game_code'] <= 555) 
					{$count++;continue;}?>
						<td>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>


		<table class="table table-bordered table-striped dataTable user_percent hidden">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold"><span class="badge badge-blue">Chuẩn {{$type}} Miền Trung Đài 2</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
					<?php if ($game['game_code'] < 600 || $game['game_code'] > 699) continue;
					?>
					<?php if ($game['game_code'] >= 631 && $game['game_code'] <= 655) 
					continue;?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 600 || $game['game_code'] > 699) continue;
					?>
					<?php if ($game['game_code'] >= 631 && $game['game_code'] <= 655) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}"  placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 600 || $game['game_code'] > 699) continue;
					?>
					<?php if ($game['game_code'] >= 631 && $game['game_code'] <= 655) 
					{$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 600 || $game['game_code'] > 699) continue;
					?>
					<?php if ($game['game_code'] >= 631 && $game['game_code'] <= 655) 
					{$count++;continue;}?>
						<td>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 600 || $game['game_code'] > 699) continue;
					?>
					<?php if ($game['game_code'] >= 631 && $game['game_code'] <= 655) 
					{$count++;continue;}?>
						<td>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>


		<table class="table table-bordered table-striped dataTable user_percent hidden">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold"><span class="badge badge-blue">Chuẩn {{$type}} XS Ảo</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
				<?php if ($game['game_code'] < 100 || $game['game_code'] > 200) continue;
					?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 100 || $game['game_code'] > 200) continue;
					?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 100 || $game['game_code'] > 200) continue;
					?>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 100 || $game['game_code'] > 200) continue;
					?>
						<td>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 100 || $game['game_code'] > 200) continue;
					?>
						<td>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		<table class="hidden table table-bordered table-striped dataTable user_percent
		@if ($type !='A' && $user->roleid != 6)
			hidden
		@endif
		">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold"><span class="badge badge-blue">Keno Vietllot</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
					<?php if ($game['game_code'] < 700) continue;
					?>
					<?php if ($game['game_code'] >= 800) continue;?>
					<th>
					<!-- @if ($game['location_id'] ==1) MB
						@else XSAO 
						@endif -->
						{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td>Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 700) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 800) {$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 700) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 800) {$count++;continue;}?>
						<td>
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Cược tối đa (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 700) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 800) {$count++;continue;}?>
						<td>
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td>Tối đa/ 1 cược (điểm)</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 700) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 800) {$count++;continue;}?>
						<td>
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
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		@if (Auth::user()->roleid == 666)
		<table class="table table-bordered table-striped dataTable user_percent" style="width: auto;">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold; border-bottom:solid 1px white !important"><span class="badge badge-blue">BBin</span></th>								</tr>
			<tr class="tablewidth120 hidden">
				<th></th>
				@foreach($games as $game)
					<?php if ($game['game_code'] != 3038) {$count++; continue;} ?>

						<th>{{$game['location_id'] == 50 ? "BBin" : $game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td class="hidden">Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] != 3038) {$count++; continue;} 
						// print_r()
					?>
						<td class="hidden">
						@if($user->roleid > 1 )
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td class="hidden">Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] != 3038) continue;?>
						<td class="hidden">
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td class="">{{"Chuyển vào tối đa( vnd )"}}</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] != 3038) {$count++; continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{$game['max_point']}}" class="form-control autonumber" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" 
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}"
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="" hidden>
					<td class="">{{"Tối đa thắng cược"}}</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] != 3038) {$count++; continue;}?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{$game['max_point_one']}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" 
							data-parsley-type="number"
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['max_point_one']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['max_point_one']:0}}" data-parsley-id="max_point_one_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							disabled
							>
							@else
							<input type="text" value="{{$game['max_point_one']}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
							data-parsley-min="999999999" disabled>
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		<?php
			// $locations = LocationHelpers::getTopLocation();
			$arrSaba = [4001,4002,4003,4005,4008,4010,4011,4043,4099,4161,4180,4190];
		?>
		<table class="table table-bordered table-striped dataTable user_percent" style="width: auto;" hidden>

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold; border-bottom:solid 1px white !important"><span class="badge badge-blue">SABA</span></th>								</tr>
			<tr class="tablewidth120 ">
				<th></th>
				@foreach($games as $game)
				<?php if ($game['game_code'] < 4001 || $game['game_code'] > 5000) {$count++; continue;} 
						if (!in_array($game['game_code'], $arrSaba)) {
							$count++;
							continue;
						}
					?>

						<th>{{$game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td class="hidden">Giá mua</td>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 4001 || $game['game_code'] > 5000) {$count++; continue;} 
						if (!in_array($game['game_code'], $arrSaba)) {
							$count++;
							continue;
						}
					?>
						<td class="hidden">
						@if($user->roleid > 1 )
							<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" {{isset($games_parent)?$games_parent[$count]['game_code']:0}} placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['exchange_rates']:0}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
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
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td class="">Cược tối thiểu</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 4001 || $game['game_code'] > 5000) {$count++; continue;} 
						if (!in_array($game['game_code'], $arrSaba)) {
							$count++;
							continue;
						}
					?>
						<td class="">
						@if($user->roleid > 1)
								<input type="text" value="{{$game['odds']}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['odds']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['odds']:0}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
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
								data-parsley-min="999999999"
								@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td class="">{{"Cược tối đa (điểm)"}}</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 4001 || $game['game_code'] > 5000) {$count++; continue;} 
						if (!in_array($game['game_code'], $arrSaba)) {
							$count++;
							continue;
						}
					?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{$game['max_point']}}" class="form-control autonumber" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" 
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}"
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="">
					<td class="">{{"Cược tối đa 1 trận"}}</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 4001 || $game['game_code'] > 5000) {$count++; continue;} 
						if (!in_array($game['game_code'], $arrSaba)) {
							$count++;
							continue;
						}
					?>
						<td>
						@if($user->roleid > 1)
							<input type="text" value="{{$game['max_point_one']}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" 
							data-parsley-type="number"
							required="{{$games_parent[$count]['game_id']}} {{$count}} {{count($games)}} {{count($games_parent)}}" data-parsley-min="{{isset($games_parent)?$games_parent[$count]['max_point_one']:0}}" placeholder="Min value is {{isset($games_parent)?$games_parent[$count]['max_point_one']:0}}" data-parsley-id="max_point_one_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							disabled
							>
							@else
							<input type="text" value="{{$game['max_point_one']}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
							data-parsley-min="999999999" disabled>
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>
			
				<tr>
					<td>	

							Trả thưởng tối đa 1 trận

					</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] < 4001 || $game['game_code'] > 5000) {$count++; continue;} 
						if (!in_array($game['game_code'], $arrSaba)) {
							$count++;
							continue;
						}
					?>
						<td>
							@if($user->roleid > 1)
							<input type="text" value="{{($game['change_max_one'])}}" class="form-control autonumber" id="change_max_one_{{$game['game_code'].'_'.$type}}" onchange="AdminCbmaxoneChange(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" data-parsley-min="{{$games_parent[$count]['change_max_one']}}" placeholder="Min value is {{isset($games_parent[$count]['change_max_one'])?$games_parent[$count]['change_max_one']:0}}" data-parsley-id="change_max_one_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							{{--  @if (Session::get('usersecondper') == 1)
						 		disabled
                            @endif --}}
							@if ($user->roleid == 6 && $user->id == Auth::user()->id)
						 		disabled
                            @endif
							>
							@else
							<input type="text" value="{{($game['change_max_one'])}}" class="form-control autonumber" id="change_max_one_{{$game['game_code'].'_'.$type}}" onchange="AdminCbmaxoneChange(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
							data-parsley-min="999999999"
							{{--  @if (Session::get('usersecondper') == 1)
						 		disabled
                            @endif --}} >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

			</tbody>
		</table>
		@endif
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
