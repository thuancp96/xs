	<?php
	$customertypes = UserHelpers::GetCustomertype();
	?>
	<form id="custom-type-user-form" data-parsley-validate novalidate>
	<div class="row">
		<div class="col-sm-12">
			<div class="card-box">
				<div class="row" style="text-align: center">
					<div class="col-lg-12 col-md-12 col-sm-12 ">
						<div class="portfolioFilter">
							
						</div>
					</div>
				</div>
				<div class="row" >
					<div class="col-lg-12" style="text-align: center !important;">
						<span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
					</div>
				</div>
				<div class="row port" >
					<div class="portfolioContainer m-b-15">

							<div class="col-sm-12 col-lg-12 col-md-12 A type_content" id="A">

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
		width: 200px !important;
		padding: 10px !important;
		height: 50px;
	}
	.user_percent tbody tr td {
        font-size: 12px;
        padding-left: 11px !important;
        max-width: 500px;
        white-space: nowrap;
        overflow: hidden;
        text-align: center;
        vertical-align: middle !important;
        padding: 0px !important;
    }
    	

</style>

<div class="row">
	<div class="col-md-3"></div>
	<div class="col-md-2">
		<!-- <button type="button" id="btn_OK" onclick="SaveChangeType('{{$type}}')" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button> -->
	</div>
</div>


</br>


<!-- <div class=""> -->
<div class="box-body table-responsive no-padding">

	<div class="col-md-12">
		
		<?php
			$locations = LocationHelpers::getTopLocation();
			$arrSaba = [4001,4002,4003,4005,4008,4010,4011,4043,4099,4161,4180,4190];
			$arr7zball = [7000];
			$arrMinigame = [8001,8002,8003,8004,8005,8006];
		?>
		@foreach($locations as $location)
		<?php if ($location->id == 2 || $location->id == 3 || $location->id == 4 || $location->id == 5) continue;//ẩn bảng thao tác ngoài xsmb   ?>
		 <?php $games=GameHelpers::GetAllGameByCusType(isset($user->customer_type) ? $user->customer_type : "A",$user->id,$location->id);
		 $games_parent = GameHelpers::GetAllGameParentByCusType(isset($user->customer_type) ? $user->customer_type : "A",$user->user_create,$location->id);
		//  $games_parent = GameHelpers::GetAllGameParentByCusType('A',$user->id,$location->id);
		?>
				
				<table class="table table-bordered table-striped dataTable user_percent
		@if($location->id == 22 || $location->id == 31 || $location->id == 32  || $location->id == 32 || $location->id == 50 || $location->id == 60)
			hidden
		@endif"
		@if($location->id == 50 || $location->id == 60 || $location->id == 70 || $location->id == 80 )
		style="width: auto;"
		@endif
		>

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold">
			<span class="badge badge-blue">@if($location->id == 21) {{'Miền Nam + Miền Trung'}}   @else {{$location->name}} @endif</span></th></tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
				<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) continue;?>
				<?php if ($game['game_code'] == 18) continue;?>
				<?php 
					// if ($game['game_code'] == 18) continue;
					?>
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

					<th>{{$location->slug == 50 ? "BBin" : $game['game_name']}}</th>
				@endforeach
				<!-- <th>Giá mua</th>
				<th>Tỉ lệ trả thưởng</th>
				<th>Đặt cược tối đa( điểm )</th>
				<th>Tối đa/ 1 cược</th>
				@if($user->roleid==1)
					<th>Thay đổi giá mua</th>
					<th>Thay đổi tỉ lệ trả thưởng</th>
					<th>Thay đổi đặt cược thối đa</th>
					<th>Thay đổi tối đa / 1 sô</th>
				@endif -->
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
            	<tr>
            		<td class="hidden">Giá mua</td>
            		@foreach($games as $game)
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
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
					

						<td class="hidden">
						@if($user->roleid != 1 )
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
						@endif
						</td>
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr >
				<td class="@if($location->slug == 60 || $location->slug == 70 || $location->slug == 80)@else hidden @endif"> @if($location->slug == 70 || $location->slug == 80) Hoa hồng (%) @else @if($location->slug == 60) Cược tối thiểu @else Tỉ lệ trả thưởng @endif @endif </td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php 
					// if ($game['game_code'] == 18) continue;
					?>
					<?php if ($location->slug == 50 && $game['game_code'] != 3038) {$count++;continue;}
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
					<?php $count++;?>
					@endforeach
				</tr>

				<tr>
					<td>
					@if($location->slug == 70 || $location->slug == 80) 
						Tối đa/1 cược
						@else 
							@if($location->slug == 60) 
								Cược tối đa 
							@else 
								@if($location->slug == 50) 
									Chuyển vào tối đa( vnd ) 
								@else 
									Đặt cược tối đa( điểm ) 
								@endif 
							@endif
					@endif
					</td>
						<!-- {{$location->slug == 50 || $location->slug == 60 ? "Chuyển vào tối đa( vnd )" : "Đặt cược tối đa( điểm )"}} -->
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php 
					// if ($game['game_code'] == 18) continue;
					?>
					<?php if ($location->slug == 50 && $game['game_code'] != 3038) {$count++;continue;}
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
						<?php $count++;?>
					@endforeach
				</tr>

				<tr @if($location->slug == 50) hidden @endif>
				<td>
						@if($location->slug == 60 || $location->slug == 70 || $location->slug == 80) 
							Tối đa 1 {{$location->slug == 80 ? "game" : "trận"}}
						@else 
							@if($location->slug == 50) 
								Tối đa thắng cược
							@else 
								Tối đa/ 1 cược (điểm)
							@endif 
						@endif
					</td>
					<!-- <td>{{$location->slug == 50 || $location->slug == 60 ? "Tối đa thắng cược" : "Tối đa/ 1 cược"}}</td> -->
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php 
					// if ($game['game_code'] == 18) continue;
					?>
					<?php if ($location->slug == 50 && $game['game_code'] != 3038) {$count++;continue;}
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

						<td>
							@if($user->roleid != 1 )
							<input type="text" value="{{($game['max_point_one'])}}" class="form-control autonumber" id="max_point_one_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeMaxpointone(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" 
							@if($location->slug == 70 || $location->slug == 80) 
								data-parsley-max="{{$games_parent[$count]['max_point_one']}}" 
							@else
								@if ($games_parent[$count]['max_point_one'] < $game['max_point'])
								data-parsley-max="{{$games_parent[$count]['max_point_one']}}" 
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
					<?php $count++;?>
					@endforeach
				</tr>

				@if((Auth::user()->roleid == 11 && $user->roleid == 2 && $location->slug != 50 && $location->slug != 60 && $location->slug != 70 && $location->slug != 80 ) || $location->slug == 60 || $location->slug == 70|| $location->slug == 80)
				<tr @if(Auth::user()->roleid != 1 && $location->slug == 70 && $location->slug == 80) hidden @else @endif>
					<td>	
					@if($location->slug == 70 || $location->slug == 80) 
					Tối đa thắng
						@else 
						@if($location->slug == 60) 
							Trả thưởng tối đa 1 trận
						@else 
							Tối đa Members
						@endif
						@endif
					</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] == 18) {$count++;continue;}?>
					<?php if ($game['game_code'] >= 31 && $game['game_code'] <= 55) {$count++;continue;}?>
					<?php 
					// if ($game['game_code'] == 18) continue;
					?>
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
					<?php $count++;?>
					@endforeach
				</tr>
				@endif
			</tbody>
		</table>

		@endforeach
	</div>
</div>

</br>

							</div>
					</div>
				</div>
				@if (Session::get('usersecondper') == 11)
				@else
				<div class="row" >
                    <div class="col-lg-12">
                        <button type="button" id="btn_OK" onclick="SaveChangeAllTypeByUserSuperMaxone()" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button> 
                    </div>
                </div>
				@endif
			</div>

		</div>
	</div>
	<input type="hidden" id="urlUserpercent" value="{{url('/control-max')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Chỉnh sửa thành công')"></a>

	<script type="text/javascript">
		$( document ).ready(function() {
			// $('input').on('input',function (e) {
			//    $this = $(this);
			//    $this.val(Number($this.val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
			// });
			$('.autonumber').autoNumeric('init',{ mDec:0});
		});
    </script>

