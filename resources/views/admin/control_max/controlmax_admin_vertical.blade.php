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
    		width: 100px !important;
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

<?php
$customertypes = UserHelpers::GetCustomertype();
$type=isset($user->customer_type) ? $user->customer_type : "A";
?>
<form id="custom-type-user-form" data-parsley-validate novalidate>
	<div class="row">
		<div class="col-sm-12">
					

						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<?php $first = true; ?>
								<?php
										$locations = LocationHelpers::getTopLocation();
										$arrSaba = [4001,4002,4003,4005,4008,4010,4011,4043,4099,4161,4180,4190];
										$arr7zball = [7000];
										$arrMinigame = [8001,8002,8003,8004,8005,8006];
									?>
								@foreach($locations as $location)
									<?php if ($location->id == 2 || $location->id == 3 || $location->id == 4 || $location->id == 5 || $location->id == 22 || $location->id == 31 || $location->id == 32 || $location->id == 50 || $location->id == 60) continue;//ẩn bảng thao tác ngoài xsmb   ?>
									<li id="li_tab_{{$location->id}}" class="@if($first) active @endif"><a href="#tab_{{$location->id}}" data-toggle="tab" aria-expanded="@if($first) true @endif">@if($location->id == 21) {{'Miền Nam + Miền Trung'}}   @else {{$location->name}} @endif</a></li>
									<?php $first = false; ?>
								@endforeach
							</ul>
							<div class="tab-content" style="padding:unset;">
							<?php $first = true; ?>
								@foreach($locations as $location)
									<?php if ($location->id == 2 || $location->id == 3 || $location->id == 4 || $location->id == 5) continue;//ẩn bảng thao tác ngoài xsmb   ?>
									<div class="tab-pane @if($first) active @endif" id="tab_{{$location->id}}">
										<div class="row">
											<div class="col-sm-12 col-lg-12 col-md-12 {{$location->id}} type_content" id="{{$location->id}}">
											<?php if ($location->id == 2 || $location->id == 3 || $location->id == 4 || $location->id == 5) continue;//ẩn bảng thao tác ngoài xsmb   ?>
									<?php $games=GameHelpers::GetAllGameByCusType(isset($user->customer_type) ? $user->customer_type : "A",$user->id,$location->id);
									$games_parent = GameHelpers::GetAllGameParentByCusType(isset($user->customer_type) ? $user->customer_type : "A",$user->user_create,$location->id);
									
									?>
											<div class="box-body table-responsive no-padding">
								<div class="col-md-12">
									<table class="table table-responsive no-padding table-bordered table-striped dataTable user_percent
									@if($location->id == 22 || $location->id == 31 || $location->id == 32 || $location->id == 50 || $location->id == 60)
										hidden
									@endif"
									@if($location->id == 50 || $location->id == 60 || $location->id == 70 || $location->id == 80)
									style="width: auto;"
									@endif
									>

										<thead>
											<tr>
												<th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold">
													<span class="badge badge-blue">@if($location->id == 21) {{'Miền Nam + Miền Trung'}}   @else {{$location->name}} @endif</span>
												</th>
											</tr>
											<tr>
												<th></th>
												<th hidden>Giá mua</th>
												<th class="@if($location->slug == 60 || $location->slug == 70  || $location->slug == 80)@else hidden @endif "> @if($location->slug == 70 || $location->slug == 80) Hoa hồng (%) @else @if($location->slug == 60) Cược tối thiểu @else Tỉ lệ trả thưởng @endif @endif </th>
												<th>
												@if($location->slug == 70 || $location->slug == 80) 
													Tối đa/1 cược
													@else 
														@if($location->slug == 60) 
															Cược tối đa 
														@else 
															@if($location->slug == 50) 
																Chuyển vào tối đa<br>(vnd) 
															@else 
																Đặt cược tối đa<br>(điểm) 
															@endif 
														@endif
												@endif
												</th>

												<th @if($location->slug == 50) hidden @endif>
													@if($location->slug == 60 || $location->slug == 70|| $location->slug == 80) 
														Tối đa 1 {{$location->slug == 80 ? "game" : "trận"}}
													@else 
														@if($location->slug == 50) 
															Tối đa thắng cược
														@else 
															Tối đa/ 1 cược<br>(điểm)
														@endif 
													@endif
												</th>

												@if((Auth::user()->roleid == 11 && $user->roleid == 2 && $location->slug != 50 && $location->slug != 60 && $location->slug != 70 && $location->slug != 80 ) || $location->slug == 60 || $location->slug == 70|| $location->slug == 80)
												<th @if(Auth::user()->roleid != 1 && $location->slug == 70  && $location->slug == 80) hidden @else @endif>	
												@if($location->slug == 70 || $location->slug == 80) 
												Tối đa thắng
													@else 
													@if($location->slug == 60) 
														Trả thưởng tối đa 1 trận
													@else 
														Tối đa Members
													@endif
													@endif
												</th>
												@endif
											</tr>

										</thead>
										<tbody>
										<?php $count=0;?>
										@foreach($games as $game)
											<?php if ($game['game_code'] == 18) {$count++; continue;}?>
											<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55){$count++; continue;}?>
											<?php if ($location->slug == 50 && $game['game_code'] != 3038) continue;
											if (!in_array($game['game_code'], $arrSaba) && $location->slug == 60) {
												$count++;
												continue;
											}
											if (!in_array($game['game_code'], $arr7zball) && $location->slug == 70) {
												$count++;
												continue;
											}
											if (!in_array($game['game_code'], $arrMinigame) && $location->slug == 80) {
												$count++;
												continue;
											}
											?>
											<tr>
												<td style="text-align:left; margin-right:5px;">{{$location->slug == 50 ? "BBin" : $game['short_name']}}</td>
												<td hidden>@if($user->roleid != 1 )
														<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-parsley-type="number"
														required="" data-parsley-min="{{$games_parent[$count]['exchange_rates']}}" placeholder="Min value is {{$games_parent[$count]['exchange_rates']}}" data-parsley-id="input_{{$game['game_code'].'_'.$type}}"
														data-v-min = "0" 
														@if($game['game_code'] == 15 || $game['game_code'] == 315 || $game['game_code'] == 415 || $game['game_code'] == 515 || $game['game_code'] == 615 
														|| $game['game_code']==16 || $game['game_code']==316 || $game['game_code']==416 || $game['game_code']==516 || $game['game_code']==616)
														disabled
														@endif

														{{-- @if (Session::get('usersecondper') == 1)
															disabled
														@endif --}}
														>
													@else
														<input type="text" value="{{($game['exchange_rates'])}}" class="form-control autonumber" id="input_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChange(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
														data-parsley-min="0" 
														{{-- @if (Session::get('usersecondper') == 1)
															disabled
														@endif --}}> 
													@endif</td>
													<td class="@if($location->slug != 60 && $location->slug != 70 && $location->slug != 80) hidden @endif">
														@if($user->roleid != 1 )
															<input type="text" value="{{($game['odds'])}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" 
															data-parsley-type="number"
														required=""
														@if($location->slug == 70)
															data-parsley-max="125" data-parsley-min="0"
														@else
															data-parsley-max="{{$games_parent[$count]['odds']}}" data-parsley-min="10"
														@endif
														placeholder="Min value is {{$games_parent[$count]['odds']}}" data-parsley-id="odds_{{$game['game_code'].'_'.$type}}"
														data-v-min = "0"
															@if($game['game_code'] != 15 && $game['game_code']!=16)
															@endif 
															{{-- @if (Session::get('usersecondper') == 1)
															disabled
															@endif --}}
														>
														
														@else
															<input type="text" value="{{($game['odds'])}}" class="form-control autonumber" id="odds_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeOdds(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
															@if($location->slug == 70)
																data-parsley-max="125" data-parsley-min="0"
															@else
																data-parsley-min="0" data-parsley-max="{{($game['odds'])}}"
															@endif
															{{--@if (Session::get('usersecondper') == 1)
															@endif--}}
														>
														@endif
													</td>
													<td>
														@if($user->roleid != 1 )
														<input type="text" value="{{($game['max_point'])}}" class="form-control autonumber" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" 

														data-parsley-type="number" {{$games_parent[$count]['game_code']}}
														required="" data-parsley-max="{{$games_parent[$count]['max_point']}}" placeholder="Min value is {{$games_parent[$count]['max_point']}}" data-parsley-id="max_point_{{$game['game_code'].'_'.$type}}"
														@if ($game['game_code'] < 100)
															data-parsley-min="{{($game['max_point_one'])}}"
														@else
															data-parsley-min="9"
														@endif
														{{-- @if (Session::get('usersecondper') == 1)
															disabled
														@endif --}}
														>
														@else
															<input type="text" value="{{($game['max_point'])}}" class="form-control autonumber" id="max_point_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxPoint(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" 
															@if($location->slug == 60)
															data-parsley-max="300000" data-parsley-min="{{($game['odds'])}}"
															@else
															data-parsley-max="9999999999" data-parsley-min="9"
															@endif
															
															{{--@if (Session::get('usersecondper') == 1)
															disabled
															@endif --}}>
														@endif 
													</td>
													<td>
														@if($user->roleid != 1 )
														<input type="text" value="{{($game['max_point_one'])}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" 

														data-parsley-type="number"
														required="" 
														@if($location->slug == 70 || $location->slug == 80) 
															data-parsley-max="{{$games_parent[$count]['max_point_one']}}" 
														@else
															@if ($games_parent[$count]['max_point_one'] < $game['max_point'])
															data-parsley-max="{{$games_parent[$count]['max_point_one']}}"  {{$games_parent[$count]['game_code']}}
															@else
															data-parsley-max="{{$game['max_point']}}" 
															@endif
														@endif
														placeholder="Min value is {{$games_parent[$count]['max_point_one']}}" data-parsley-id="max_point_one_{{$game['game_code'].'_'.$type}}"
														data-v-min = "0" 
														{{--  @if (Session::get('usersecondper') == 1)
															disabled
														@endif--}}
														>
														@else
														<input type="text" value="{{($game['max_point_one'])}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
														
														@if($location->slug == 60 || $location->slug == 70 || $location->slug == 80)
														data-parsley-min="{{($game['max_point'])}}" data-parsley-max="1500000"
															@else
															data-parsley-max="100000000000000" data-parsley-min="9"
															@endif

														{{--  @if (Session::get('usersecondper') == 1)
															disabled
														@endif--}}>
														@endif
													</td>

													@if((Auth::user()->roleid == 11 && $user->roleid == 2 && $location->slug != 50 && $location->slug != 60 && $location->slug != 70 && $location->slug != 80 ) || $location->slug == 60 || $location->slug == 70|| $location->slug == 80)
													<td>
														@if($user->roleid != 1 )
														<input type="text" value="{{($game['change_max_one'])}}" class="form-control autonumber" id="change_max_one_{{$game['game_code'].'_'.$type}}" onchange="AdminCbmaxoneChange(this,'{{$game['game_code']}}','{{$type}}')" 

														data-parsley-type="number"
														required="" data-parsley-max="{{$games_parent[$count]['change_max_one']}}" placeholder="Min value is {{$games_parent[$count]['change_max_one']}}" data-parsley-id="change_max_one_{{$game['game_code'].'_'.$type}}"
														data-v-min = "0" 
														{{--  @if (Session::get('usersecondper') == 1)
															disabled
														@endif --}}
														>
														@else
														<input type="text" value="{{($game['change_max_one'])}}" class="form-control autonumber" id="change_max_one_{{$game['game_code'].'_'.$type}}" onchange="AdminCbmaxoneChange(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0"
														data-parsley-min="{{($game['max_point_one'])}}" 
														
														@if($location->slug == 60 || $location->slug == 70 || $location->slug == 80) 
															data-parsley-max="12000000"
														@else 
															@if($location->slug == 50) 
																data-parsley-max="9999999999"
															@else 
																data-parsley-max="9999999999"
															@endif 
														@endif
														
														{{--  @if (Session::get('usersecondper') == 1)
															disabled
														@endif --}} >
														@endif
													</td>
													@endif
											</tr>

											<?php $count++;?>
										@endforeach
										</tbody>
									</table>
									</div>
									</div>
											</div>
										</div>
								</div>
									<?php $first = false; ?>
								@endforeach
							</div>
						</div>


				</div>
				@if (Session::get('usersecondper') == 11)
				@else
				<div class="row" style="margin-left: 10px;">
					<div class="col-lg-12">
						<button type="button" id="btn_OK" onclick="SaveChangeAllTypeByUserSuperMaxone()" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button>
					</div>
				</div>
				@endif
			</div>

	<input type="hidden" id="urlUserpercent" value="{{url('/control-max')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Chỉnh sửa thành công')"></a>

	<script type="text/javascript">
		$(document).ready(function() {
			// $('input').on('input',function (e) {
			//    $this = $(this);
			//    $this.val(Number($this.val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
			// });
			$('.autonumber').autoNumeric('init', {
				mDec: 0
			});
		});
	</script>