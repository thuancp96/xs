<!-- @foreach($games as $game)
				<tr>
					<td>{{$game['game_name']}}</td>
                    @if($user->roleid==1)
                        <td>
                            <input type="text" value="{{round($game['exchange_rates'])}}" class="form-control" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" >
                        </td>
                        <td>
                            <input type="text" value="{{round($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" >
                        </td>
						<td>
							<input type="text" value="{{round($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" >
						</td>
						<td>
							<input type="text" value="{{round($game['max_point_one'])}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" >
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
								<input type="text" value="{{round($game['exchange_rates'])}}" class="form-control" id="input_{{$game['game_code'].'_'.$type}}" onblur="InputChange(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['exchange_rates'])}}" id="min_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="text" disabled value="{{round($game['exchange_rates'])}}" class="form-control" id="input_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['exchange_rates'])}}" id="min_{{$game['game_code'].'_'.$type}}">
							@endif
						</td>
						<td>
							@if($game['change_odds'])
								<input type="text" value="{{round($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" onblur="InputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['odds'])}}" id="max_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="text" disabled value="{{round($game['odds'])}}" class="form-control" id="odds_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['odds'])}}" id="max_{{$game['game_code'].'_'.$type}}">
							@endif
						</td>
						<td>
							@if($game['change_max'])
								<input type="text" value="{{round($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" onblur="InputChangeMax(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point'])}}" id="max_max_point_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="text" disabled value="{{round($game['max_point'])}}" class="form-control" id="max_point_{{$game['game_code'].'_'.$type}}" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point'])}}" id="max_max_point_{{$game['game_code'].'_'.$type}}">
							@endif
						</td>
						<td>
							@if($game['change_max_one'])
								<input type="text" value="{{round($game['max_point_one'],0)}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" onblur="InputChangeMaxOne(this,'{{$game['game_code']}}','{{$type}}')" >
								<input type="hidden" value="{{round($games_parent[$count]['max_point_one'],0)}}" id="max_max_point_one_{{$game['game_code'].'_'.$type}}">
							@else
								<input type="text" disabled value="{{round($game['max_point_one'])}}" class="form-control" id="max_point_one_{{$game['game_code'].'_'.$type}}" >
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
			@endforeach -->