	
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
		?>
		@foreach($locations as $location)
		<?php if ($location->id != 1) continue;//ẩn bảng thao tác ngoài xsmb   ?>
		 <?php $games=GameHelpers::GetAllGameByCusType(isset($user->customer_type) ? $user->customer_type : "A",$user->id,$location->id);
		 $games_parent = GameHelpers::GetAllGameParentByCusType(isset($user->customer_type) ? $user->customer_type : "A",$user->user_create,$location->id);
		//  $games_parent = GameHelpers::GetAllGameParentByCusType('A',$user->id,$location->id);
		?>
				
				<table class="table table-bordered table-striped dataTable user_percent">
			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold">
			<span class="badge badge-blue">{{$location->name}}</span></th></tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
				<?php if ($game['game_code'] != 14 && $game['game_code'] != 12) continue;?>
					<th>{{$game['game_name']}}</th>
				@endforeach
			</tr>
			</thead>
			<tbody>
            <?php $count=0;?>
				<tr>
					<td>
					Trị số chia
					</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] != 14 && $game['game_code'] != 12) continue;?>
					
						<td>
							@if($user->roleid != 1 )
							<input type="text" value="{{($game['ratio_ex'])}}" class="form-control autonumber" id="ratio_ex_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeRatioEx(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number" {{$games_parent[$count]['game_code']}}
							required="" data-parsley-max="{{$games_parent[$count]['ratio_ex']}}" placeholder="Min value is {{$games_parent[$count]['ratio_ex']}}" data-parsley-id="ratio_ex_{{$game['ratio_ex'].'_'.$type}}"
							>
							@else
								<input type="text" value="{{($game['ratio_ex'])}}" class="form-control autonumber" id="ratio_ex_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeRatioEx(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0" >
							@endif 
						</td>
						<?php $count++;?>
					@endforeach
				</tr>

				<tr>
				<td>
						Giá mua max
					</td>
					<?php $count=0;?>
					@foreach($games as $game)
					<?php if ($game['game_code'] != 14 && $game['game_code'] != 12) continue;?>

						<td>
							@if($user->roleid != 1 )
							<input type="text" value="{{($game['max_ex'])}}" class="form-control autonumber" id="max_ex_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeRatioEx(this,'{{$game['game_code']}}','{{$type}}')" 

							data-parsley-type="number"
							required="" >
							@else
							<input type="text" value="{{($game['max_ex'])}}" class="form-control autonumber" id="max_ex_{{$game['game_code'].'_'.$type}}" onchange="AdminInputChangeRatioEx(this,'{{$game['game_code']}}','{{$type}}')" data-v-min = "0">
							@endif
						</td>
					<?php $count++;?>
					@endforeach
				</tr>

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
                        <button type="button" id="btn_OK" onclick="SaveChangeAllTypeByUserSuperMaxex()" class="btn btn-success btn-rounded waves-effect waves-light">Lưu</button> 
                    </div>
                </div>
				@endif
			</div>

		</div>
	</div>
	<input type="hidden" id="urlUserpercent" value="{{url('/control-ex')}}">
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

