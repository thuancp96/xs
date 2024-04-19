<style>
	.user_percent td {
		padding: 0px !important;

		/*margin-top: 5px;*/
	}

	.user_percent .form-control{
		font-size: 12px !important;
	}

	.user_percent th{
		padding-right: 0px !important;
	}
	.user_percent input{
		width: 90px !important;
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
					<?php if ($game['game_code'] >= 100) continue;
					?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;?>
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
					<?php if ($game['game_code'] >= 100) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
						<td>
						@if(Auth::user()->roleid != 1 )
							<input type="text" value="{{number_format($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
							required="" data-parsley-min="{{$games_parent[$count]['exchange_rates']}}" {{$games_parent[$count]['game_code']}} placeholder="Min value is {{$games_parent[$count]['exchange_rates']}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							@if($game['game_code'] == 15 || $game['game_code']==16 || $game['game_code'] == 115 || $game['game_code']==116)
							disabled
							@endif

						 	@if (Session::get('usersecondper') == 11)
						 		disabled
                            @endif
							>
						@else
							<input type="text" value="{{number_format($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
							data-parsley-min="0" 
							@if (Session::get('usersecondper') == 11)
						 		disabled
                            @endif >
						@endif
						</td>
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr hidden>
					<td>Tỉ lệ trả thưởng</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
						<td>
							@if(Auth::user()->roleid != 1 )
								<input type="text" value="{{number_format($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
								data-parsley-type="number"
							required="" data-parsley-min="{{$games_parent[$count]['odds']}}" placeholder="Min value is {{$games_parent[$count]['odds']}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0"
								@if($game['game_code'] != 15 && $game['game_code']!=16 && $game['game_code'] != 115 && $game['game_code']!=116)
								disabled
								@endif 
								@if (Session::get('usersecondper') == 11)
						 		disabled
                            @endif
                            >
							@else
								<input type="text" value="{{number_format($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
								data-parsley-min="999999999"
								@if (Session::get('usersecondper') == 11)
						 		disabled
                            @endif
                            >
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

				<tr class="" hidden>
					<td>Cược tối đa</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
						<td>
							@if(Auth::user()->roleid != 1 )
							<input type="text" value="{{number_format($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" data-parsley-min="{{$games_parent[$count]['max_point']}}" placeholder="Min value is {{$games_parent[$count]['max_point']}}" data-parsley-id="max_point_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0"
							
							>
							@else
								<input type="text" value="{{number_format($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
								data-parsley-min="999999999">
							@endif
						</td>
						<?php $count++;?>
					@endforeach
				</tr>

				<tr class="" hidden>
					<td>Tối đa/ 1 cược</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] >= 100) {$count++;continue;}
					?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
						<td>
							@if(Auth::user()->roleid != 1 )
							<input type="text" value="{{number_format($game['max_point_one'])}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" data-parsley-min="{{$games_parent[$count]['max_point_one']}}" placeholder="Min value is {{$games_parent[$count]['max_point_one']}}" data-parsley-id="max_point_one_{{$game['game_code'].'_'.$type}}"
							data-v-min = "0" 
							
							>
							@else
							<input type="text" value="{{number_format($game['max_point_one'])}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
							data-parsley-min="999999999">
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>
			
			</tbody>
		</table>

		

		

	</div>
</div>

</br>

@endif
<!-- 
<script type="text/javascript">
    $( document ).ready(function() {
 		$('.autonumber').autoNumeric('init');
 		$.extend($.fn.autoNumeric.defaults, {              
            mDec:0
        });      
    });
 
    </script> -->
