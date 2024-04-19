<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-2">
		<button type="button" id="btn_OK" onclick="SaveChangeType('{{$type}}')" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button>
	</div>
</div>
<div class="box-body table-responsive no-padding">
	<div class="col-md-12">
		<table class="table table-bordered table-striped dataTable">

			<thead>
			<tr>
				<th>Trò chơi</th>
				<th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi Đặt cược tối đa( điểm )</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
			@foreach($games as $game)
				<tr>
					<td>{{$game['game_name']}}</td>
                    @if($user->roleid==1)
                        <td>
                            <input type="number" value="{{round($game['exchange_rates'])}}" class="form-control" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" >
                        </td>
                        <td>
                            <input type="number" value="{{round($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" >
                        </td>
						<td>
							<input type="number" value="{{round($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" >
						</td>
						<td>
							<input type="number" value="{{round($game['max_point_one'])}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" >
						</td>
						<td>
							<div class="">
								@if($game['change_ex'])
									<input type="checkbox" checked id="check_ex_{{$game['game_code'].'_'.$type}}" onblur="AdminCbExChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@else
									<input type="checkbox" id="check_ex_{{$game['game_code'].'_'.$type}}" onblur="AdminCbExChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@endif
								<label></label>
							</div>
						</td>
						<td>
							<div class="">
								@if($game['change_odds'])
									<input type="checkbox" checked id="check_odds_{{$game['game_code'].'_'.$type}}" onblur="AdminCboddsChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@else
									<input type="checkbox"  id="check_odds_{{$game['game_code'].'_'.$type}}" onblur="AdminCboddsChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@endif
								<label></label>
							</div>
						</td>
						<td>
							<div class="">
								@if($game['change_max'])
									<input type="checkbox" checked id="check_max_{{$game['game_code'].'_'.$type}}" onblur="AdminCbmaxChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@else
									<input type="checkbox" id="check_max_{{$game['game_code'].'_'.$type}}" onblur="AdminCbmaxChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@endif
								<label></label>
							</div>
						</td>
						<td>
							<div class="">
								@if($game['change_max_one'])
									<input type="checkbox" checked id="check_maxone_{{$game['game_code'].'_'.$type}}" onblur="AdminCbmaxoneChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@else
									<input type="checkbox" id="check_maxone_{{$game['game_code'].'_'.$type}}" onblur="AdminCbmaxoneChange(this,'{{$game['game_code']}}','{{$type}}')" >
								@endif
								<label></label>
							</div>
						</td>
                    @else
						<td>
							@if($game['change_ex'])
								<input type="number" value="{{round($game['exchange_rates'])}}" class="form-control" id="input_{{$game['game_code'].'_'.$type}}" onblur="InputChange(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['exchange_rates'])}}" id="min_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="number" disabled value="{{round($game['exchange_rates'])}}" class="form-control" id="input_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['exchange_rates'])}}" id="min_{{$game['game_code'].'_'.$type}}">
							@endif
						</td>
						<td>
							@if($game['change_odds'])
								<input type="number" value="{{round($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" onblur="InputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['odds'])}}" id="max_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="number" disabled value="{{round($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['odds'])}}" id="max_{{$game['game_code'].'_'.$type}}">
							@endif
						</td>
						<td>
							@if($game['change_max'])
								<input type="number" value="{{round($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" onblur="InputChangeMax(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point'])}}" id="max_max_point_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="number" disabled value="{{round($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point'])}}" id="max_max_point_{{$game['game_code'].'_'.$type}}">
							@endif
						</td>
						<td>
							@if($game['change_max_one'])
								<input type="number" value="{{round($game['max_point_one'],0)}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" onblur="InputChangeMaxOne(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point_one'],0)}}" id="max_max_point_one_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="number" disabled value="{{round($game['max_point_one'])}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point_one'])}}" id="max_max_point_one_{{$game['game_code'].'_'.$type}}">
							@endif
								<input type="hidden" value="{{$game['change_odds']}}" id="change_odds_{{$game['game_code'].'_'.$type}}">
								<input type="hidden" value="{{$game['change_max']}}" id="change_max_{{$game['game_code'].'_'.$type}}">
								<input type="hidden" value="{{$game['change_ex']}}" id="change_ex_{{$game['game_code'].'_'.$type}}">
								<input type="hidden" value="{{$game['change_max_one']}}" id="change_max_one_{{$game['game_code'].'_'.$type}}">
						</td>
                    @endif
				</tr>
                <?php $count++;?>
			@endforeach
			</tbody>
		</table>
	</div>
</div>
