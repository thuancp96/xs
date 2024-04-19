@extends('admin.admin-template')
@section('title','Bảng chuẩn')
@section('content')

	<?php
	$customertypes = UserHelpers::GetCustomertype();
	?>
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Bảng thao tác tự động lên giá
					</h3>

					<!-- <div class="col-sm-1 ">
						<a class="btn btn-default btn-custom waves-effect waves-light btn-sm @if($locationId == 1) disabled @endif" href="\control-auto-price/mien-bac">Miền Bắc</a>
					</div>
					  
					<div class="col-sm-1" style="margin-left: 5px;">
						<a class="btn btn-default btn-custom waves-effect waves-light btn-sm @if($locationId == 4) disabled @endif" href="\control-auto-price/xs-ao">XS Ảo</a>
					</div> -->
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
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
	tbody tr td {
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
		
	</div>
</div>


</br>


<!-- <div class=""> -->
<div class="box-body table-responsive no-padding">

	<div class="col-md-12">

		<?php
			$user = Auth::user();
			$locations = LocationHelpers::getTopLocation();
		?>
		@foreach($locations as $location)
			<?php if ($location->id == 2 || $location->id == 3) continue;?>
		 	<?php $games=GameHelpers::GetAllGameByCusType('A',$user->id,$location->id)?>

		<table class="table table-bordered table-striped dataTable user_percent
		@if($location->id == 22 || $location->id == 31 || $location->id == 32  || $location->id == 4  || $location->id == 5)
			hidden
		@endif">

			<thead>
			<tr><th colspan="91" style="text-align:left; padding-left:10px; font-weight:bold">
			<span class="badge badge-blue">@if($location->id == 21) {{'Miền Nam + Miền Trung'}}   @else {{$location->name}} @endif</span></th>								</tr>
			<tr class="tablewidth120">
				<th></th>
				@foreach($games as $game)
					<?php
					if ($game->game_code == 2 || $game->game_code == 3 || $game->game_code == 15
					|| $game->game_code == 18 || $game->game_code == 17 || $game->game_code == 56
					|| $game->game_code == 118 || $game->game_code == 117
					|| $game->game_code == 102 || $game->game_code == 103 || $game->game_code == 115
					|| $game->game_code == 8 || $game->game_code == 19
					|| $game->game_code == 20 || $game->game_code == 21
					|| $game->game_code == 108 || $game->game_code == 119
					|| $game->game_code == 120 || $game->game_code == 121
					|| $game->game_code == 122 || $game->game_code == 123
					|| ($game->game_code >= 31 && $game->game_code <= 55) 
					|| $game->game_code == 302 || $game->game_code == 303 || $game->game_code == 315
					|| $game->game_code == 318 || $game->game_code == 317 || $game->game_code == 352 || $game->game_code == 353 || $game->game_code == 308
					|| $game->game_code == 402 || $game->game_code == 415 || $game->game_code == 417 || $game->game_code == 452 || $game->game_code == 453 || $game->game_code == 408
					|| $game->game_code == 502 || $game->game_code == 515 || $game->game_code == 517 || $game->game_code == 552 || $game->game_code == 553 || $game->game_code == 508
					|| $game->game_code == 602 || $game->game_code == 615 || $game->game_code == 617 || $game->game_code == 652 || $game->game_code == 653 || $game->game_code == 608
					|| $game->game_code == 352
					|| $game->game_code == 700 || $game->game_code == 702
					)
						continue;
					?>
					<th>{{$game['name']}}</th>
				@endforeach
				
			</tr>
			</thead>
			<tbody>

            <?php $count=0;?>

			<tr >
            		<td >Mốc thầu Max</td>
            		@foreach($games as $game)
            		<?php
					if ($game->game_code == 2 || $game->game_code == 3 || $game->game_code == 15
					|| $game->game_code == 18 || $game->game_code == 17 || $game->game_code == 56
					|| $game->game_code == 118 || $game->game_code == 117
					|| $game->game_code == 102 || $game->game_code == 103 || $game->game_code == 115
					|| $game->game_code == 8 || $game->game_code == 19
					|| $game->game_code == 20 || $game->game_code == 21
					|| $game->game_code == 108 || $game->game_code == 119
					|| $game->game_code == 120 || $game->game_code == 121
					|| $game->game_code == 122 || $game->game_code == 123
					|| ($game->game_code >= 31 && $game->game_code <= 55) 
					|| $game->game_code == 302 || $game->game_code == 303 || $game->game_code == 315
					|| $game->game_code == 318 || $game->game_code == 317 || $game->game_code == 352 || $game->game_code == 353 || $game->game_code == 308
					|| $game->game_code == 402 || $game->game_code == 415 || $game->game_code == 417 || $game->game_code == 452 || $game->game_code == 453 || $game->game_code == 408
					|| $game->game_code == 502 || $game->game_code == 515 || $game->game_code == 517 || $game->game_code == 552 || $game->game_code == 553 || $game->game_code == 508
					|| $game->game_code == 602 || $game->game_code == 615 || $game->game_code == 617 || $game->game_code == 652 || $game->game_code == 653 || $game->game_code == 608
					|| $game->game_code == 352
					|| $game->game_code == 700 || $game->game_code == 702)
						continue;
					?>
						<td>
						<input type="text" value="{{number_format($game['a'])}}" class="form-control autonumber" id="a_{{$game->game_code}}" onchange="AdminAChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							@if($game->game_code >= 721 && $game->game_code <= 739)
								style="display: none;"
							@endif
							>

							<input type="text" value="{{number_format($game['a3'])}}" class="form-control autonumber" id="a3_{{$game->game_code}}" onchange="AdminAChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							style="display: none;">

						</td>
						<?php $count++;?>
					@endforeach
            	</tr>


            	<tr>
					<td>Hệ số lên giá max</td>

            		@foreach($games as $game)
            		<?php
					if ($game->game_code == 2 || $game->game_code == 3 || $game->game_code == 15
					|| $game->game_code == 18 || $game->game_code == 17 || $game->game_code == 56
					|| $game->game_code == 118 || $game->game_code == 117
					|| $game->game_code == 102 || $game->game_code == 103 || $game->game_code == 115
					|| $game->game_code == 8 || $game->game_code == 19
					|| $game->game_code == 20 || $game->game_code == 21
					|| $game->game_code == 108 || $game->game_code == 119
					|| $game->game_code == 120 || $game->game_code == 121
					|| $game->game_code == 122 || $game->game_code == 123
					|| ($game->game_code >= 31 && $game->game_code <= 55) 
					|| $game->game_code == 302 || $game->game_code == 303 || $game->game_code == 315
					|| $game->game_code == 318 || $game->game_code == 317 || $game->game_code == 352 || $game->game_code == 353 || $game->game_code == 308
					|| $game->game_code == 402 || $game->game_code == 415 || $game->game_code == 417 || $game->game_code == 452 || $game->game_code == 453 || $game->game_code == 408
					|| $game->game_code == 502 || $game->game_code == 515 || $game->game_code == 517 || $game->game_code == 552 || $game->game_code == 553 || $game->game_code == 508
					|| $game->game_code == 602 || $game->game_code == 615 || $game->game_code == 617 || $game->game_code == 652 || $game->game_code == 653 || $game->game_code == 608
					|| $game->game_code == 352
					|| $game->game_code == 700 || $game->game_code == 702)
						continue;
					?>
						<td>
						<input type="text" value="{{number_format($game['x'])}}" class="form-control autonumber" id="x_{{$game->game_code}}" onchange="AdminXChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							@if($game->game_code >= 721 && $game->game_code <= 739)
								style="display: none;"
							@endif
							>
							
							<input type="text" value="{{number_format($game['x2'])}}" class="form-control autonumber" id="x2_{{$game->game_code}}" onchange="AdminXChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							style="display: none;">
							<input type="text" value="{{number_format($game['x3'])}}" class="form-control autonumber" id="x3_{{$game->game_code}}" onchange="AdminXChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							style="display: none;">

						</td>
						<?php $count++;?>
					@endforeach
            	</tr>

            	<tr>
					<td>Hệ số lên giá</td>
					
            		@foreach($games as $game)
            		<?php
					if ($game->game_code == 2 || $game->game_code == 3 || $game->game_code == 15
					|| $game->game_code == 18 || $game->game_code == 17 || $game->game_code == 56
					|| $game->game_code == 118 || $game->game_code == 117
					|| $game->game_code == 102 || $game->game_code == 103 || $game->game_code == 115
					|| $game->game_code == 8 || $game->game_code == 19
					|| $game->game_code == 20 || $game->game_code == 21
					|| $game->game_code == 108 || $game->game_code == 119
					|| $game->game_code == 120 || $game->game_code == 121
					|| $game->game_code == 122 || $game->game_code == 123
					|| ($game->game_code >= 31 && $game->game_code <= 55) 
					|| $game->game_code == 302 || $game->game_code == 303 || $game->game_code == 315
					|| $game->game_code == 318 || $game->game_code == 317 || $game->game_code == 352 || $game->game_code == 353 || $game->game_code == 308
					|| $game->game_code == 402 || $game->game_code == 415 || $game->game_code == 417 || $game->game_code == 452 || $game->game_code == 453 || $game->game_code == 408
					|| $game->game_code == 502 || $game->game_code == 515 || $game->game_code == 517 || $game->game_code == 552 || $game->game_code == 553 || $game->game_code == 508
					|| $game->game_code == 602 || $game->game_code == 615 || $game->game_code == 617 || $game->game_code == 652 || $game->game_code == 653 || $game->game_code == 608
					|| $game->game_code == 352
					|| $game->game_code == 700 || $game->game_code == 702)
						continue;
					?>
						<td>
						<input type="text" value="{{number_format($game['y'])}}" class="form-control autonumber" id="y_{{$game->game_code}}" onchange="AdminYChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							>
							<input type="text" value="{{number_format($game['y2'])}}" class="form-control autonumber" id="y2_{{$game->game_code}}" onchange="AdminYChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							style="display: none;">
							<input type="text" value="{{number_format($game['y3'])}}" class="form-control autonumber" id="y3_{{$game->game_code}}" onchange="AdminYChange(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							style="display: none;">


						</td>
						<?php $count++;?>
					@endforeach
				</tr>
				
				<tr>
					<td>Hệ số max</td>
					
            		@foreach($games as $game)
            		<?php
					if ($game->game_code == 2 || $game->game_code == 3 || $game->game_code == 15
					|| $game->game_code == 18 || $game->game_code == 17 || $game->game_code == 56
					|| $game->game_code == 118 || $game->game_code == 117
					|| $game->game_code == 102 || $game->game_code == 103 || $game->game_code == 115
					|| $game->game_code == 8 || $game->game_code == 19
					|| $game->game_code == 20 || $game->game_code == 21
					|| $game->game_code == 108 || $game->game_code == 119
					|| $game->game_code == 120 || $game->game_code == 121
					|| $game->game_code == 122 || $game->game_code == 123
					|| ($game->game_code >= 31 && $game->game_code <= 55) 
					|| $game->game_code == 302 || $game->game_code == 303 || $game->game_code == 315
					|| $game->game_code == 318 || $game->game_code == 317 || $game->game_code == 352 || $game->game_code == 353 || $game->game_code == 308
					|| $game->game_code == 402 || $game->game_code == 415 || $game->game_code == 417 || $game->game_code == 452 || $game->game_code == 453 || $game->game_code == 408
					|| $game->game_code == 502 || $game->game_code == 515 || $game->game_code == 517 || $game->game_code == 552 || $game->game_code == 553 || $game->game_code == 508
					|| $game->game_code == 602 || $game->game_code == 615 || $game->game_code == 617 || $game->game_code == 652 || $game->game_code == 653 || $game->game_code == 608
					|| $game->game_code == 700 || $game->game_code == 702)
						continue;
					?>
						<td>
						<input type="text" value="{{number_format($game['a2'])}}" class="form-control autonumber" id="a2_{{$game->game_code}}" onchange="AdminA2Change(this,'{{$game->game_code}}')" data-v-min = "0"
							data-parsley-min="0" 
							{{-- @if (Session::get('usersecondper') == 1)
						 		disabled
							@endif --}}
							@if($game->game_code >= 721 && $game->game_code <= 739)
								style="display: none;"
							@endif
							>

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
				{{-- @if (Session::get('usersecondper') == 1)
				@else --}}
				<div class="row" >
					<div class="col-lg-12" style="text-align: right !important;">
						<button type="button" id="btn_OK" onclick="SaveChangeAXY()" class="btn btn-default btn-custom waves-effect waves-light">Lưu</button> 
					</div>
				</div>
				{{--@endif--}}
			</div>

		</div>
	</div>
	<input type="hidden" id="urlUserpercent" value="{{url('/control-auto-price')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Chỉnh sửa thành công')"></a>
@endsection


@section('js_call')

	<!-- customertype.js -->
	<script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>
	<script>var _0xe980=["\x68\x69\x64\x65","\x2E\x72\x65\x66\x72\x65\x73\x68","\x72\x65\x61\x64\x79","\x73\x68\x6F\x77","\x66\x61\x64\x65\x4F\x75\x74","\x23","\x76\x61\x6C","\x23\x75\x72\x6C\x55\x73\x65\x72\x70\x65\x72\x63\x65\x6E\x74","\x2F\x6C\x6F\x61\x64\x2D\x74\x79\x70\x65\x2D\x67\x61\x6D\x65\x2F","\x66\x61\x64\x65\x49\x6E","\x6C\x6F\x61\x64","\x2F\x6C\x6F\x61\x64\x2D\x74\x79\x70\x65\x2D\x67\x61\x6D\x65\x2D\x6F\x72\x69\x67\x69\x6E\x61\x6C\x2F","\x2F\x6C\x6F\x61\x64\x2D\x74\x79\x70\x65\x2D\x67\x61\x6D\x65\x2D\x62\x79\x2D\x75\x73\x65\x72\x2F","\x2F","\x2F\x6C\x6F\x61\x64\x2D\x74\x79\x70\x65\x2D\x67\x61\x6D\x65\x2D\x6C\x6F\x77\x70\x2D\x62\x79\x2D\x75\x73\x65\x72\x2F","","\x2E","\x73\x70\x6C\x69\x74","\x6C\x65\x6E\x67\x74\x68","\x24\x31","\x2C","\x24\x32","\x72\x65\x70\x6C\x61\x63\x65","\x74\x65\x73\x74","\x23\x63\x75\x72\x72\x65\x6E\x74\x75\x73\x65\x72\x69\x64","\x75\x73\x65\x72\x69\x64\x20","\x6C\x6F\x67","\x23\x74\x6F\x6B\x65\x6E","\x2F\x63\x6F\x6E\x74\x72\x6F\x6C\x2D\x6D\x61\x78\x2F\x73\x74\x6F\x72\x65\x2D\x62\x79\x2D\x73\x75\x70\x65\x72\x2D\x6D\x61\x78\x6F\x6E\x65","\x50\x4F\x53\x54","\x68\x74\x6D\x6C","\x54\x68\xF4\x6E\x67\x20\x62\xE1\x6F","\x43\x68\u1EC9\x6E\x68\x20\x73\u1EED\x61\x20\x74\x68\xE0\x6E\x68\x20\x63\xF4\x6E\x67","\x73\x75\x63\x63\x65\x73\x73","\u0110\xE3\x20\x68\x69\u1EC3\x75","\x45\x72\x72\x6F\x72","\x61\x6A\x61\x78","\x2F\x73\x74\x6F\x72\x65\x2D\x62\x79\x2D\x75\x73\x65\x72","\x63\x6C\x69\x63\x6B","\x2E\x63\x6C\x6F\x73\x65","\x64\x65\x66\x65\x61\x74","\x74\x68\x65\x6E","\x54\x69\u1EBF\x70\x20\x74\u1EE5\x63","\x54\x68\x6F\xE1\x74","\x2F\x73\x74\x6F\x72\x65\x6C\x6F\x77\x70","\x2F\x73\x74\x6F\x72\x65","\x23\x6D\x69\x6E\x5F","\x5F","\x23\x6D\x61\x78\x5F","\x23\x6F\x64\x64\x73\x5F","\x23\x6D\x61\x78\x5F\x70\x6F\x69\x6E\x74\x5F","\x23\x6D\x61\x78\x5F\x70\x6F\x69\x6E\x74\x5F\x6F\x6E\x65\x5F","\x23\x6D\x61\x78\x5F\x6D\x61\x78\x5F\x70\x6F\x69\x6E\x74\x5F\x6F\x6E\x65\x5F","\x23\x6D\x61\x78\x5F\x6D\x61\x78\x5F\x70\x6F\x69\x6E\x74\x5F","\x23\x63\x68\x61\x6E\x67\x65\x5F\x6F\x64\x64\x73\x5F","\x31","\x23\x63\x68\x61\x6E\x67\x65\x5F\x6D\x61\x78\x5F","\x23\x63\x68\x61\x6E\x67\x65\x5F\x65\x78\x5F","\x23\x63\x68\x61\x6E\x67\x65\x5F\x6D\x61\x78\x5F\x6F\x6E\x65\x5F","\x6E\x61\x6D\x65","\x65\x78\x63\x68\x61\x6E\x67\x65","\x42\u1EA1\x6E\x20\x70\x68\u1EA3\x69\x20\x6E\x68\u1EAD\x70\x20\x67\x69\xE1\x20\x74\x72\u1ECB\x20\x3E\x3D\x20","\x66\x6F\x63\x75\x73","\x70\x75\x73\x68","\x23\x69\x6E\x70\x75\x74\x5F","\x6F\x64\x64\x73","\x42\u1EA1\x6E\x20\x70\x68\u1EA3\x69\x20\x6E\x68\u1EAD\x70\x20\x67\x69\xE1\x20\x74\x72\u1ECB\x20\x3C\x3D\x20","\x6D\x61\x78\x5F\x70\x6F\x69\x6E\x74","\x6D\x61\x78\x5F\x70\x6F\x69\x6E\x74\x5F\x6F\x6E\x65","\x65\x6E\x2D\x55\x53","\x74\x6F\x4C\x6F\x63\x61\x6C\x65\x53\x74\x72\x69\x6E\x67","\x72\x65\x70\x6C\x61\x63\x65\x41\x6C\x6C","\x3A\x63\x68\x65\x63\x6B\x65\x64","\x69\x73","\x23\x63\x68\x65\x63\x6B\x5F\x65\x78\x5F","\x23\x63\x68\x65\x63\x6B\x5F\x6F\x64\x64\x73\x5F","\x23\x63\x68\x65\x63\x6B\x5F\x6D\x61\x78\x5F","\x64\x61\x74\x61\x2D\x70\x61\x72\x73\x6C\x65\x79\x2D\x6D\x69\x6E","\x61\x74\x74\x72","\x37","\x38","\x31\x38","\x31\x30\x37","\x31\x30\x38","\x31\x31\x38","\x32\x39","\x33\x32\x39","\x34\x32\x39","\x35\x32\x39","\x36\x32\x39","\x4D\u1EE9\x63\x20\x74\u1ED1\x69\x20\u0111\x61\x20\x63\u1EE7\x61\x20\x43\x68\x75\u1EA9\x6E\x20","\x20\x6C\xE0\x20","\x77\x61\x72\x6E\x69\x6E\x67","\x4D\u1EE9\x63\x20\x74\u1ED1\x69\x20\x74\x68\x69\u1EC3\x75\x20\x63\u1EE7\x61\x20\x43\x68\x75\u1EA9\x6E\x20","\x74\x79\x70\x65","\x76\x61\x6C\x75\x65","\x41","\x42","\x43","\x44","\x64\x61\x74\x61\x2D\x70\x61\x72\x73\x6C\x65\x79\x2D\x6D\x61\x78","\x4D\u1EE9\x63\x20\x74\u1ED1\x69\x20\x74\x68\x69\u1EC3\x75\x20\x6C\xE0\x20","\x4D\u1EE9\x63\x20\x74\u1ED1\x69\x20\u0111\x61\x20\x6C\xE0\x20","\x65\x72\x72\x6F\x72","\x72\x69\x67\x68\x74\x20\x74\x6F\x70","\x6E\x6F\x74\x69\x66\x79","\x4E\x6F\x74\x69\x66\x69\x63\x61\x74\x69\x6F\x6E","\x63\x68\x61\x6E\x67\x65\x5F\x65\x78","\x63\x68\x61\x6E\x67\x65\x5F\x6F\x64\x64\x73","\x63\x68\x61\x6E\x67\x65\x5F\x6D\x61\x78","\x63\x68\x61\x6E\x67\x65\x5F\x6D\x61\x78\x5F\x6F\x6E\x65","\x23\x61\x5F","\x23\x61\x32\x5F","\x23\x61\x33\x5F","\x23\x78\x5F","\x23\x78\x32\x5F","\x23\x78\x33\x5F","\x23\x79\x5F","\x23\x79\x32\x5F","\x23\x79\x33\x5F","\x63\x62\x6F\x6E\x65"];$(document)[_0xe980[2]](function(){$(_0xe980[1])[_0xe980[0]]()});var changes=[];function LoadContentGame(_0xcaaex3){$(_0xe980[1])[_0xe980[3]]();changes= [];$(_0xe980[5]+ _0xcaaex3)[_0xe980[4]]();$(_0xe980[5]+ _0xcaaex3)[_0xe980[10]]($(_0xe980[7])[_0xe980[6]]()+ _0xe980[8]+ _0xcaaex3,function(){$(_0xe980[5]+ _0xcaaex3)[_0xe980[9]]();$(_0xe980[1])[_0xe980[0]]()})}function LoadContentGameOriginal(_0xcaaex3){$(_0xe980[1])[_0xe980[3]]();changes= [];$(_0xe980[5]+ _0xcaaex3)[_0xe980[4]]();$(_0xe980[5]+ _0xcaaex3)[_0xe980[10]]($(_0xe980[7])[_0xe980[6]]()+ _0xe980[11]+ _0xcaaex3,function(){$(_0xe980[5]+ _0xcaaex3)[_0xe980[9]]();$(_0xe980[1])[_0xe980[0]]()})}function LoadContentGame(_0xcaaex3,_0xcaaex5){$(_0xe980[1])[_0xe980[3]]();changes= [];$(_0xe980[5]+ _0xcaaex3)[_0xe980[4]]();$(_0xe980[5]+ _0xcaaex3)[_0xe980[10]]($(_0xe980[7])[_0xe980[6]]()+ _0xe980[8]+ _0xcaaex3,function(){$(_0xe980[5]+ _0xcaaex3)[_0xe980[9]]();$(_0xe980[1])[_0xe980[0]]()})}function LoadContentGameByUser(_0xcaaex3,_0xcaaex5){$(_0xe980[1])[_0xe980[3]]();changes= [];$(_0xe980[5]+ _0xcaaex3)[_0xe980[4]]();$(_0xe980[5]+ _0xcaaex3)[_0xe980[10]]($(_0xe980[7])[_0xe980[6]]()+ _0xe980[12]+ _0xcaaex3+ _0xe980[13]+ _0xcaaex5,function(){$(_0xe980[5]+ _0xcaaex3)[_0xe980[9]]();$(_0xe980[1])[_0xe980[0]]()})}function LoadContentGameLowpByUser(_0xcaaex3,_0xcaaex5){$(_0xe980[1])[_0xe980[3]]();changes= [];$(_0xe980[5]+ _0xcaaex3)[_0xe980[4]]();$(_0xe980[5]+ _0xcaaex3)[_0xe980[10]]($(_0xe980[7])[_0xe980[6]]()+ _0xe980[14]+ _0xcaaex3+ _0xe980[13]+ _0xcaaex5,function(){$(_0xe980[5]+ _0xcaaex3)[_0xe980[9]]();$(_0xe980[1])[_0xe980[0]]()})}function addCommas(_0xcaaex9){_0xcaaex9+= _0xe980[15];x= _0xcaaex9[_0xe980[17]](_0xe980[16]);x1= x[0];x2= x[_0xe980[18]]> 1?_0xe980[16]+ x[1]:_0xe980[15];var _0xcaaexa=/(\d+)(\d{3})/;while(_0xcaaexa[_0xe980[23]](x1)){x1= x1[_0xe980[22]](_0xcaaexa,_0xe980[19]+ _0xe980[20]+ _0xe980[21])};return x1+ x2}function SaveChangeAllTypeByUserSuperMaxone(){SaveChangeTypeByUserSuperMaxone($(_0xe980[24])[_0xe980[6]]())}function SaveChangeTypeByUserSuperMaxone(_0xcaaex5){if(changes[_0xe980[18]]> 0){console[_0xe980[26]](_0xe980[25]+ _0xcaaex5);$_token= $(_0xe980[27])[_0xe980[6]]();$[_0xe980[36]]({url:_0xe980[28],method:_0xe980[29],dataType:_0xe980[30],data:{changes:changes,userid:_0xcaaex5,_token:$_token},success:function(_0xcaaexd){swal({title:_0xe980[31],text:_0xe980[32],icon:_0xe980[33],timer:10000,button:_0xe980[34]})},error:function(_0xcaaexd){console[_0xe980[26]](_0xe980[35],_0xcaaexd)}})}}function SaveChangeAllType(){SaveChangeType()}function SaveChangeAllTypeLowp(){SaveChangeTypeLowp()}function SaveChangeAllTypeByUser(){SaveChangeTypeByUser($(_0xe980[24])[_0xe980[6]]())}function SaveChangeTypeByUser(_0xcaaex5){if(changes[_0xe980[18]]> 0){$_token= $(_0xe980[27])[_0xe980[6]]();$[_0xe980[36]]({url:$(_0xe980[7])[_0xe980[6]]()+ _0xe980[37],method:_0xe980[29],dataType:_0xe980[30],data:{changes:changes,userid:_0xcaaex5,_token:$_token},success:function(_0xcaaexd){swal({title:_0xe980[31],text:_0xe980[32],icon:_0xe980[33],timer:10000,buttons:{cancel:_0xe980[42],defeat:_0xe980[43]}})[_0xe980[41]]((_0xcaaex12)=>{switch(_0xcaaex12){case _0xe980[40]:$(_0xe980[39])[_0xe980[38]]();break;default:break}})},error:function(_0xcaaexd){console[_0xe980[26]](_0xe980[35],_0xcaaexd)}})}}function SaveChangeTypeLowp(){if(changes[_0xe980[18]]> 0){$_token= $(_0xe980[27])[_0xe980[6]]();$[_0xe980[36]]({url:$(_0xe980[7])[_0xe980[6]]()+ _0xe980[44],method:_0xe980[29],dataType:_0xe980[30],data:{changes:changes,_token:$_token},success:function(_0xcaaexd){swal({title:_0xe980[31],text:_0xe980[32],icon:_0xe980[33],timer:10000,button:_0xe980[34]})},error:function(_0xcaaexd){console[_0xe980[26]](_0xe980[35],_0xcaaexd)}})}}function SaveChangeType(){if(changes[_0xe980[18]]> 0){$_token= $(_0xe980[27])[_0xe980[6]]();$[_0xe980[36]]({url:$(_0xe980[7])[_0xe980[6]]()+ _0xe980[45],method:_0xe980[29],dataType:_0xe980[30],data:{changes:changes,_token:$_token},success:function(_0xcaaexd){swal({title:_0xe980[31],text:_0xe980[32],icon:_0xe980[33],timer:10000,button:_0xe980[34]})},error:function(_0xcaaexd){console[_0xe980[26]](_0xe980[35],_0xcaaexd)}})}}function InputChange(_0xcaaex16,_0xcaaex17,_0xcaaex18){var _0xcaaex19=$(_0xe980[46]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1a=$(_0xe980[48]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1e=$(_0xe980[52]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1f=$(_0xe980[53]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex20=$(_0xe980[54]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex21=$(_0xe980[56]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex22=$(_0xe980[57]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex23=$(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){if($(_0xcaaex16)[_0xe980[6]]()>= _0xcaaex19){changes[_0xcaaex25][_0xe980[60]]= $(_0xcaaex16)[_0xe980[6]]();_0xcaaex24= false}else {alert(_0xe980[61]+ _0xcaaex19);$(_0xcaaex16)[_0xe980[6]](_0xcaaex19);$(_0xcaaex16)[_0xe980[62]]()}}};if(_0xcaaex24){if($(_0xcaaex16)[_0xe980[6]]()>= _0xcaaex19){changes[_0xe980[63]]({name:_0xcaaex17,exchange:$(_0xcaaex16)[_0xe980[6]](),odds:_0xcaaex1b,type:_0xcaaex18,min:_0xcaaex19,max:_0xcaaex1a,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,max_max_point_one:_0xcaaex1e,max_max_point:_0xcaaex1f,change_odds:_0xcaaex20,change_max:_0xcaaex21,change_ex:_0xcaaex22,change_max_one:_0xcaaex23})}else {alert(_0xe980[61]+ _0xcaaex19);$(_0xcaaex16)[_0xe980[6]](_0xcaaex19);$(_0xcaaex16)[_0xe980[62]]()}}}function InputChangeOdds(_0xcaaex1b,_0xcaaex17,_0xcaaex18){var _0xcaaex19=$(_0xe980[46]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1a=$(_0xe980[48]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1e=$(_0xe980[52]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1f=$(_0xe980[53]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex20=$(_0xe980[54]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex21=$(_0xe980[56]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex22=$(_0xe980[57]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex23=$(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){if($(_0xcaaex1b)[_0xe980[6]]()<= _0xcaaex1a){changes[_0xcaaex25][_0xe980[65]]= $(_0xcaaex1b)[_0xe980[6]]();_0xcaaex24= false}else {alert(_0xe980[66]+ _0xcaaex1a);$(_0xcaaex1b)[_0xe980[6]](_0xcaaex1a);$(_0xcaaex1b)[_0xe980[62]]()}}};if(_0xcaaex24){if($(_0xcaaex1b)[_0xe980[6]]()<= _0xcaaex1a){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:$(_0xcaaex1b)[_0xe980[6]](),type:_0xcaaex18,min:_0xcaaex19,max:_0xcaaex1a,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,max_max_point_one:_0xcaaex1e,max_max_point:_0xcaaex1f,change_odds:_0xcaaex20,change_max:_0xcaaex21,change_ex:_0xcaaex22,change_max_one:_0xcaaex23});_0xcaaex24= false}else {alert(_0xe980[66]+ _0xcaaex1a);$(_0xcaaex1b)[_0xe980[6]](_0xcaaex1a);$(_0xcaaex1b)[_0xe980[62]]()}}}function InputChangeMax(_0xcaaex1c,_0xcaaex17,_0xcaaex18){var _0xcaaex19=$(_0xe980[46]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1a=$(_0xe980[48]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1e=$(_0xe980[52]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1f=$(_0xe980[53]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex20=$(_0xe980[54]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex21=$(_0xe980[56]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex22=$(_0xe980[57]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex23=$(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){if($(_0xcaaex1c)[_0xe980[6]]()<= _0xcaaex1f){changes[_0xcaaex25][_0xe980[67]]= $(_0xcaaex1c)[_0xe980[6]]();_0xcaaex24= false}else {alert(_0xe980[66]+ _0xcaaex1f);$(_0xcaaex1c)[_0xe980[6]](_0xcaaex1f);$(_0xcaaex1c)[_0xe980[62]]()}}};if(_0xcaaex24){if($(_0xcaaex1c)[_0xe980[6]]()<= _0xcaaex1f){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,min:_0xcaaex19,max:_0xcaaex1a,max_point:$(_0xcaaex1c)[_0xe980[6]](),max_point_one:_0xcaaex1d,max_max_point_one:_0xcaaex1e,max_max_point:_0xcaaex1f,change_odds:_0xcaaex20,change_max:_0xcaaex21,change_ex:_0xcaaex22,change_max_one:_0xcaaex23})}else {alert(_0xe980[66]+ _0xcaaex1f);$(_0xcaaex1c)[_0xe980[6]](_0xcaaex1f);$(_0xcaaex1c)[_0xe980[62]]()}}}function InputChangeMaxOne(_0xcaaex1d,_0xcaaex17,_0xcaaex18){var _0xcaaex19=$(_0xe980[46]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1a=$(_0xe980[48]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1e=$(_0xe980[52]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1f=$(_0xe980[53]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex20=$(_0xe980[54]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex21=$(_0xe980[56]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex22=$(_0xe980[57]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex23=$(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()== _0xe980[55]?true:false;var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){if($(_0xcaaex1d)[_0xe980[6]]()<= _0xcaaex1e){changes[_0xcaaex25][_0xe980[68]]= $(_0xcaaex1d)[_0xe980[6]]();_0xcaaex24= false}else {alert(_0xe980[66]+ _0xcaaex1e);$(_0xcaaex1d)[_0xe980[6]](_0xcaaex1e);$(_0xcaaex1d)[_0xe980[62]]()}}};if(_0xcaaex24){if($(_0xcaaex1d)[_0xe980[6]]()<= _0xcaaex1e){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,min:_0xcaaex19,max:_0xcaaex1a,max_point:_0xcaaex1c,max_point_one:$(_0xcaaex1d)[_0xe980[6]](),max_max_point_one:_0xcaaex1e,max_max_point:_0xcaaex1f,change_odds:_0xcaaex20,change_max:_0xcaaex21,change_ex:_0xcaaex22,change_max_one:_0xcaaex23});_0xcaaex24= false}else {alert(_0xe980[66]+ _0xcaaex1e);$(_0xcaaex1d)[_0xe980[6]](_0xcaaex1e);$(_0xcaaex1d)[_0xe980[62]]()}}}function AdminInputChange(_0xcaaex16,_0xcaaex17,_0xcaaex18,_0xcaaex2a= true){$(_0xcaaex16)[_0xe980[6]](Number($(_0xcaaex16)[_0xe980[6]]()[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]));var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;var _0xcaaex2f=parseInt($(_0xcaaex16)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex30=parseInt($(_0xcaaex16)[_0xe980[78]](_0xe980[77])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex31=1000;if(_0xcaaex17== _0xe980[79]|| _0xcaaex17== _0xe980[80]|| _0xcaaex17== _0xe980[81]|| _0xcaaex17== _0xe980[82]|| _0xcaaex17== _0xe980[83]|| _0xcaaex17== _0xe980[84]){_0xcaaex31= 23000};if(_0xcaaex17== _0xe980[85]|| _0xcaaex17== _0xe980[86]|| _0xcaaex17== _0xe980[87]|| _0xcaaex17== _0xe980[88]|| _0xcaaex17== _0xe980[89]){_0xcaaex31= 2000};if(_0xcaaex2f> _0xcaaex31){if(_0xcaaex17== _0xe980[79]|| _0xcaaex17== _0xe980[80]|| _0xcaaex17== _0xe980[81]|| _0xcaaex17== _0xe980[82]|| _0xcaaex17== _0xe980[83]|| _0xcaaex17== _0xe980[84]){swal({title:_0xe980[31],text:_0xe980[90]+ _0xcaaex18+ _0xe980[91]+ 23000,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex16)[_0xe980[6]](23000)}else {if(_0xcaaex17== _0xe980[85]|| _0xcaaex17== _0xe980[86]|| _0xcaaex17== _0xe980[87]|| _0xcaaex17== _0xe980[88]|| _0xcaaex17== _0xe980[89]){swal({title:_0xe980[31],text:_0xe980[90]+ _0xcaaex18+ _0xe980[91]+ 2000,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex16)[_0xe980[6]](2000)}else {swal({title:_0xe980[31],text:_0xe980[90]+ _0xcaaex18+ _0xe980[91]+ 1000,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex16)[_0xe980[6]](1000)}}};if(_0xcaaex2f< _0xcaaex30){swal({title:_0xe980[31],text:_0xe980[93]+ _0xcaaex18+ _0xe980[91]+ _0xcaaex30,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex16)[_0xe980[6]](_0xcaaex30)};for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]&& _0xcaaex18== changes[_0xcaaex25][_0xe980[94]]){changes[_0xcaaex25][_0xe980[60]]= $(_0xcaaex16)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);_0xcaaex24= false}};$(_0xcaaex16)[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:$(_0xcaaex16)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]),odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:_0xcaaex2e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[64]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex16)[_0xe980[6]]());$(_0xe980[64]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());AdminInputChange(_0xcaaex16,(Number(_0xcaaex17)+ 100)+ _0xe980[15],_0xcaaex18);$(_0xe980[64]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex16)[_0xe980[6]]());$(_0xe980[64]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());AdminInputChange(_0xcaaex16,(Number(_0xcaaex17)+ 200)+ _0xe980[15],_0xcaaex18);$(_0xe980[64]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex16)[_0xe980[6]]());$(_0xe980[64]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());AdminInputChange(_0xcaaex16,(Number(_0xcaaex17)+ 300)+ _0xe980[15],_0xcaaex18)};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 700&& _0xcaaex18== _0xe980[96]){$(_0xe980[64]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[6]]($(_0xcaaex16)[_0xe980[6]]());$(_0xe980[64]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());AdminInputChange(_0xcaaex16,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[97]);$(_0xe980[64]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[6]]($(_0xcaaex16)[_0xe980[6]]());$(_0xe980[64]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());AdminInputChange(_0xcaaex16,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[98]);$(_0xe980[64]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[6]]($(_0xcaaex16)[_0xe980[6]]());$(_0xe980[64]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[78]](_0xe980[95],$(_0xcaaex16)[_0xe980[6]]());AdminInputChange(_0xcaaex16,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[99])}}function AdminInputChangeOdds(_0xcaaex1b,_0xcaaex17,_0xcaaex18,_0xcaaex2a= true){$(_0xcaaex1b)[_0xe980[6]](Number($(_0xcaaex1b)[_0xe980[6]]()[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]));var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;var _0xcaaex33=parseInt($(_0xcaaex1b)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex34=parseInt($(_0xcaaex1b)[_0xe980[78]](_0xe980[77])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex35=parseInt($(_0xcaaex1b)[_0xe980[78]](_0xe980[100])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));if(_0xcaaex33< _0xcaaex34&& (_0xcaaex17> 4000&& _0xcaaex17< 5000)){swal({title:_0xe980[31],text:_0xe980[101]+ _0xcaaex34,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex1b)[_0xe980[6]](_0xcaaex34);return};if(_0xcaaex33> _0xcaaex35){swal({title:_0xe980[31],text:_0xe980[102]+ _0xcaaex35,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex1b)[_0xe980[6]](_0xcaaex35);return};for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]&& _0xcaaex18== changes[_0xcaaex25][_0xe980[94]]){changes[_0xcaaex25][_0xe980[65]]= $(_0xcaaex1b)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);_0xcaaex24= false}};$(_0xcaaex1b)[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:$(_0xcaaex1b)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]),type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:_0xcaaex2e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[49]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex1b)[_0xe980[6]]());$(_0xe980[49]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1b,(Number(_0xcaaex17)+ 100)+ _0xe980[15],_0xcaaex18);$(_0xe980[49]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex1b)[_0xe980[6]]());$(_0xe980[49]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1b,(Number(_0xcaaex17)+ 200)+ _0xe980[15],_0xcaaex18);$(_0xe980[49]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex1b)[_0xe980[6]]());$(_0xe980[49]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1b,(Number(_0xcaaex17)+ 300)+ _0xe980[15],_0xcaaex18)};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 700&& _0xcaaex18== _0xe980[96]){$(_0xe980[49]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[6]]($(_0xcaaex1b)[_0xe980[6]]());$(_0xe980[49]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1b,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[97]);$(_0xe980[49]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[6]]($(_0xcaaex1b)[_0xe980[6]]());$(_0xe980[49]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1b,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[98]);$(_0xe980[49]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[6]]($(_0xcaaex1b)[_0xe980[6]]());$(_0xe980[49]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[78]](_0xe980[95],$(_0xcaaex1b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1b,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[99])}}function AdminInputChangeMaxPoint(_0xcaaex1a,_0xcaaex17,_0xcaaex18){$(_0xcaaex1a)[_0xe980[6]](Number($(_0xcaaex1a)[_0xe980[6]]()[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]));var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;var _0xcaaex37=parseInt($(_0xcaaex1a)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex38=parseInt($(_0xcaaex1a)[_0xe980[78]](_0xe980[100])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex39=parseInt($(_0xcaaex1a)[_0xe980[78]](_0xe980[77])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));if(_0xcaaex37> _0xcaaex38){swal({title:_0xe980[31],text:_0xe980[102]+ _0xcaaex38,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex1a)[_0xe980[6]](Number((_0xcaaex38+ _0xe980[15])[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]))};if(_0xcaaex37< _0xcaaex39&& _0xcaaex17< 100){$[_0xe980[106]][_0xe980[105]](_0xe980[103],_0xe980[104],_0xe980[31],_0xe980[101]+ Number((_0xcaaex39+ _0xe980[15])[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]));$(_0xcaaex1a)[_0xe980[6]](Number((_0xcaaex39+ _0xe980[15])[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]))};if(_0xcaaex37> _0xcaaex1d&& _0xcaaex17> 4000){swal({title:_0xe980[31],text:_0xe980[102]+ _0xcaaex1d,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex1a)[_0xe980[6]](_0xcaaex1d);return};for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]&& _0xcaaex18== changes[_0xcaaex25][_0xe980[94]]){changes[_0xcaaex25][_0xe980[67]]= $(_0xcaaex1a)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);_0xcaaex24= false}};$(_0xcaaex1a)[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:$(_0xcaaex1a)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]),max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:_0xcaaex2e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[50]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex1a)[_0xe980[6]]());$(_0xe980[50]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());AdminInputChangeMaxPoint(_0xcaaex1a,(Number(_0xcaaex17)+ 100)+ _0xe980[15],_0xcaaex18);$(_0xe980[50]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex1a)[_0xe980[6]]());$(_0xe980[50]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());AdminInputChangeMaxPoint(_0xcaaex1a,(Number(_0xcaaex17)+ 200)+ _0xe980[15],_0xcaaex18);$(_0xe980[50]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex1a)[_0xe980[6]]());$(_0xe980[50]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());AdminInputChangeMaxPoint(_0xcaaex1a,(Number(_0xcaaex17)+ 300)+ _0xe980[15],_0xcaaex18)};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 700&& _0xcaaex18== _0xe980[96]){$(_0xe980[50]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[6]]($(_0xcaaex1a)[_0xe980[6]]());$(_0xe980[50]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1a,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[97]);$(_0xe980[50]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[6]]($(_0xcaaex1a)[_0xe980[6]]());$(_0xe980[50]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1a,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[98]);$(_0xe980[50]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[6]]($(_0xcaaex1a)[_0xe980[6]]());$(_0xe980[50]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[78]](_0xe980[95],$(_0xcaaex1a)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex1a,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[99])}}function AdminInputChangeMaxpointone(_0xcaaex3b,_0xcaaex17,_0xcaaex18,_0xcaaex2a= true){$(_0xcaaex3b)[_0xe980[6]](Number($(_0xcaaex3b)[_0xe980[6]]()[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]));var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;var _0xcaaex3c=parseInt($(_0xcaaex3b)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex3d=parseInt($(_0xcaaex3b)[_0xe980[78]](_0xe980[100])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));if(_0xcaaex3c> _0xcaaex3d){swal({title:_0xe980[31],text:_0xe980[102]+ _0xcaaex3d,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex3b)[_0xe980[6]](Number((_0xcaaex3d+ _0xe980[15])[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]))};for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]&& _0xcaaex18== changes[_0xcaaex25][_0xe980[94]]){changes[_0xcaaex25][_0xe980[68]]= $(_0xcaaex3b)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);_0xcaaex24= false}};$(_0xcaaex3b)[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:$(_0xcaaex3b)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]),change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:_0xcaaex2e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[51]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex3b)[_0xe980[6]]());$(_0xe980[51]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());AdminInputChangeMaxpointone(_0xcaaex3b,(Number(_0xcaaex17)+ 100)+ _0xe980[15],_0xcaaex18);$(_0xe980[51]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex3b)[_0xe980[6]]());$(_0xe980[51]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());AdminInputChangeMaxPoint(_0xcaaex3b,(Number(_0xcaaex17)+ 200)+ _0xe980[15],_0xcaaex18);$(_0xe980[51]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex3b)[_0xe980[6]]());$(_0xe980[51]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());AdminInputChangeMaxPoint(_0xcaaex3b,(Number(_0xcaaex17)+ 300)+ _0xe980[15],_0xcaaex18)};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 700&& _0xcaaex18== _0xe980[96]){$(_0xe980[51]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[6]]($(_0xcaaex3b)[_0xe980[6]]());$(_0xe980[51]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[97])[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex3b,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[97]);$(_0xe980[51]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[6]]($(_0xcaaex3b)[_0xe980[6]]());$(_0xe980[51]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[98])[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex3b,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[98]);$(_0xe980[51]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[6]]($(_0xcaaex3b)[_0xe980[6]]());$(_0xe980[51]+ (Number(_0xcaaex17))+ _0xe980[47]+ _0xe980[99])[_0xe980[78]](_0xe980[95],$(_0xcaaex3b)[_0xe980[6]]());AdminInputChangeOdds(_0xcaaex3b,(Number(_0xcaaex17))+ _0xe980[15],_0xe980[99])}}function AdminCbExChange(_0xcaaex2b,_0xcaaex17,_0xcaaex18){var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){changes[_0xcaaex25][_0xe980[107]]= $(_0xcaaex2b)[_0xe980[73]](_0xe980[72]);_0xcaaex24= false}};if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:$(_0xcaaex2b)[_0xe980[73]](_0xe980[72]),change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:_0xcaaex2e})}}function AdminCboddsChange(_0xcaaex40,_0xcaaex17,_0xcaaex18){var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){changes[_0xcaaex25][_0xe980[108]]= $(_0xcaaex40)[_0xe980[73]](_0xe980[72]);_0xcaaex24= false}};if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:$(_0xcaaex40)[_0xe980[73]](_0xe980[72]),change_max:_0xcaaex2d,change_max_one:_0xcaaex2e})}}function AdminCbmaxChange(_0xcaaex2d,_0xcaaex17,_0xcaaex18){var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]();var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2e=0;try{_0xcaaex2e= $(_0xe980[58]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])}catch(err){};var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){changes[_0xcaaex25][_0xe980[109]]= $(_0xcaaex2d)[_0xe980[73]](_0xe980[72]);_0xcaaex24= false}};if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:$(_0xcaaex2d)[_0xe980[73]](_0xe980[72]),change_max_one:_0xcaaex2e})}}function AdminCbmaxoneChange(_0xcaaex2e,_0xcaaex17,_0xcaaex18){$(_0xcaaex2e)[_0xe980[6]](Number($(_0xcaaex2e)[_0xe980[6]]()[_0xe980[71]](_0xe980[20],_0xe980[15])[_0xe980[71]](_0xe980[16],_0xe980[15]))[_0xe980[70]](_0xe980[69]));var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex24=true;var _0xcaaex43=parseInt($(_0xcaaex2e)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));var _0xcaaex3d=parseInt($(_0xcaaex2e)[_0xe980[78]](_0xe980[100])[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]));if(_0xcaaex43> _0xcaaex3d){swal({title:_0xe980[31],text:_0xe980[102]+ _0xcaaex3d,icon:_0xe980[92],timer:5000,button:_0xe980[34]});$(_0xcaaex2e)[_0xe980[6]](_0xcaaex3d)};for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){changes[_0xcaaex25][_0xe980[110]]= $(_0xcaaex2e)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);_0xcaaex24= false}};if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:$(_0xcaaex2e)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[58]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex2e)[_0xe980[6]]());$(_0xe980[58]+ (Number(_0xcaaex17)+ 100)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex2e)[_0xe980[6]]());AdminCbmaxoneChange(_0xcaaex2e,(Number(_0xcaaex17)+ 100)+ _0xe980[15],_0xcaaex18);$(_0xe980[58]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex2e)[_0xe980[6]]());$(_0xe980[58]+ (Number(_0xcaaex17)+ 200)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex2e)[_0xe980[6]]());AdminCbmaxoneChange(_0xcaaex2e,(Number(_0xcaaex17)+ 200)+ _0xe980[15],_0xcaaex18);$(_0xe980[58]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]($(_0xcaaex2e)[_0xe980[6]]());$(_0xe980[58]+ (Number(_0xcaaex17)+ 300)+ _0xe980[47]+ _0xcaaex18)[_0xe980[78]](_0xe980[95],$(_0xcaaex2e)[_0xe980[6]]());AdminCbmaxoneChange(_0xcaaex2e,(Number(_0xcaaex17)+ 300)+ _0xe980[15],_0xcaaex18)}}function AdminAChange(_0xcaaex45,_0xcaaex17){var _0xcaaex46=$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex47=$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex48=$(_0xe980[113]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex49=$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4a=$(_0xe980[115]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4b=$(_0xe980[116]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4c=$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4d=$(_0xe980[118]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4e=$(_0xe980[119]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex24=true;if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,aa:_0xcaaex46,aa2:_0xcaaex47,aa3:_0xcaaex48,xx:_0xcaaex49,xx2:_0xcaaex4a,xx3:_0xcaaex4b,yy:_0xcaaex4c,yy2:_0xcaaex4d,yy3:_0xcaaex4e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[111]+ (Number(_0xcaaex17)+ 100))[_0xe980[6]]($(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[111]+ (Number(_0xcaaex17)+ 100))[_0xe980[78]](_0xe980[95],$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]());AdminAChange(_0xe980[120],(Number(_0xcaaex17)+ 100)+ _0xe980[15]);$(_0xe980[111]+ (Number(_0xcaaex17)+ 200))[_0xe980[6]]($(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[111]+ (Number(_0xcaaex17)+ 200))[_0xe980[78]](_0xe980[95],$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]());AdminAChange(_0xe980[120],(Number(_0xcaaex17)+ 200)+ _0xe980[15]);$(_0xe980[111]+ (Number(_0xcaaex17)+ 300))[_0xe980[6]]($(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[111]+ (Number(_0xcaaex17)+ 300))[_0xe980[78]](_0xe980[95],$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]());AdminAChange(_0xe980[120],(Number(_0xcaaex17)+ 300)+ _0xe980[15])}}function AdminXChange(_0xcaaex50,_0xcaaex17){var _0xcaaex46=$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex47=$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex48=$(_0xe980[113]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex49=$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4a=$(_0xe980[115]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4b=$(_0xe980[116]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4c=$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4d=$(_0xe980[118]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4e=$(_0xe980[119]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex24=true;if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,aa:_0xcaaex46,aa2:_0xcaaex47,aa3:_0xcaaex48,xx:_0xcaaex49,xx2:_0xcaaex4a,xx3:_0xcaaex4b,yy:_0xcaaex4c,yy2:_0xcaaex4d,yy3:_0xcaaex4e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[114]+ (Number(_0xcaaex17)+ 100))[_0xe980[6]]($(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[114]+ (Number(_0xcaaex17)+ 100))[_0xe980[78]](_0xe980[95],$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]());AdminXChange(_0xe980[120],(Number(_0xcaaex17)+ 100)+ _0xe980[15]);$(_0xe980[114]+ (Number(_0xcaaex17)+ 200))[_0xe980[6]]($(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[114]+ (Number(_0xcaaex17)+ 200))[_0xe980[78]](_0xe980[95],$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]());AdminXChange(_0xe980[120],(Number(_0xcaaex17)+ 200)+ _0xe980[15]);$(_0xe980[114]+ (Number(_0xcaaex17)+ 300))[_0xe980[6]]($(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[114]+ (Number(_0xcaaex17)+ 300))[_0xe980[78]](_0xe980[95],$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]());AdminXChange(_0xe980[120],(Number(_0xcaaex17)+ 300)+ _0xe980[15])}}function AdminYChange(_0xcaaex52,_0xcaaex17){var _0xcaaex46=$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex47=$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex48=$(_0xe980[113]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex49=$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4a=$(_0xe980[115]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4b=$(_0xe980[116]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4c=$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4d=$(_0xe980[118]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4e=$(_0xe980[119]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex24=true;if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,aa:_0xcaaex46,aa2:_0xcaaex47,aa3:_0xcaaex48,xx:_0xcaaex49,xx2:_0xcaaex4a,xx3:_0xcaaex4b,yy:_0xcaaex4c,yy2:_0xcaaex4d,yy3:_0xcaaex4e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[117]+ (Number(_0xcaaex17)+ 100))[_0xe980[6]]($(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[117]+ (Number(_0xcaaex17)+ 100))[_0xe980[78]](_0xe980[95],$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]());AdminYChange(_0xe980[120],(Number(_0xcaaex17)+ 100)+ _0xe980[15]);$(_0xe980[117]+ (Number(_0xcaaex17)+ 200))[_0xe980[6]]($(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[117]+ (Number(_0xcaaex17)+ 200))[_0xe980[78]](_0xe980[95],$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]());AdminYChange(_0xe980[120],(Number(_0xcaaex17)+ 200)+ _0xe980[15]);$(_0xe980[117]+ (Number(_0xcaaex17)+ 300))[_0xe980[6]]($(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[117]+ (Number(_0xcaaex17)+ 300))[_0xe980[78]](_0xe980[95],$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]());AdminYChange(_0xe980[120],(Number(_0xcaaex17)+ 300)+ _0xe980[15])}}function AdminA2Change(_0xcaaex52,_0xcaaex17){var _0xcaaex46=$(_0xe980[111]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex47=$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex48=$(_0xe980[113]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex49=$(_0xe980[114]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4a=$(_0xe980[115]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4b=$(_0xe980[116]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4c=$(_0xe980[117]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4d=$(_0xe980[118]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex4e=$(_0xe980[119]+ _0xcaaex17)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex24=true;if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,aa:_0xcaaex46,aa2:_0xcaaex47,aa3:_0xcaaex48,xx:_0xcaaex49,xx2:_0xcaaex4a,xx3:_0xcaaex4b,yy:_0xcaaex4c,yy2:_0xcaaex4d,yy3:_0xcaaex4e})};if(Number(_0xcaaex17)- Number(_0xcaaex17)% 100== 300){$(_0xe980[112]+ (Number(_0xcaaex17)+ 100))[_0xe980[6]]($(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[112]+ (Number(_0xcaaex17)+ 100))[_0xe980[78]](_0xe980[95],$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]());AdminYChange(_0xe980[120],(Number(_0xcaaex17)+ 100)+ _0xe980[15]);$(_0xe980[112]+ (Number(_0xcaaex17)+ 200))[_0xe980[6]]($(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[112]+ (Number(_0xcaaex17)+ 200))[_0xe980[78]](_0xe980[95],$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]());AdminYChange(_0xe980[120],(Number(_0xcaaex17)+ 200)+ _0xe980[15]);$(_0xe980[112]+ (Number(_0xcaaex17)+ 300))[_0xe980[6]]($(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]());$(_0xe980[112]+ (Number(_0xcaaex17)+ 300))[_0xe980[78]](_0xe980[95],$(_0xe980[112]+ _0xcaaex17)[_0xe980[6]]());AdminYChange(_0xe980[120],(Number(_0xcaaex17)+ 300)+ _0xe980[15])}}function SaveChangeAXY(){if(changes[_0xe980[18]]> 0){$_token= $(_0xe980[27])[_0xe980[6]]();$[_0xe980[36]]({url:$(_0xe980[7])[_0xe980[6]]()+ _0xe980[45],method:_0xe980[29],dataType:_0xe980[30],data:{changes:changes,_token:$_token},success:function(_0xcaaexd){swal({title:_0xe980[31],text:_0xe980[32],icon:_0xe980[33],timer:10000,button:_0xe980[34]})},error:function(_0xcaaexd){console[_0xe980[26]](_0xe980[35],_0xcaaexd)}})}}function AdminCbmaxoneChangeControlmax(_0xcaaex2e,_0xcaaex17,_0xcaaex18){var _0xcaaex16=$(_0xe980[64]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1c=$(_0xe980[50]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1b=$(_0xe980[49]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex1d=$(_0xe980[51]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);var _0xcaaex2b=$(_0xe980[74]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2c=$(_0xe980[75]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex2d=$(_0xe980[76]+ _0xcaaex17+ _0xe980[47]+ _0xcaaex18)[_0xe980[73]](_0xe980[72]);var _0xcaaex24=true;for(var _0xcaaex25=0;_0xcaaex25< changes[_0xe980[18]];_0xcaaex25++){if(_0xcaaex17== changes[_0xcaaex25][_0xe980[59]]){changes[_0xcaaex25][_0xe980[110]]= $(_0xcaaex2e)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15]);_0xcaaex24= false}};if(_0xcaaex24){changes[_0xe980[63]]({name:_0xcaaex17,exchange:_0xcaaex16,odds:_0xcaaex1b,type:_0xcaaex18,max_point:_0xcaaex1c,max_point_one:_0xcaaex1d,change_ex:_0xcaaex2b,change_odds:_0xcaaex2c,change_max:_0xcaaex2d,change_max_one:$(_0xcaaex2e)[_0xe980[6]]()[_0xe980[22]](/[^0-9\.]+/g,_0xe980[15])})}}</script>
<!-- 
<script type="text/javascript">
    $( document ).ready(function() {
 		$('.autonumber').autoNumeric('init');
 		$.extend($.fn.autoNumeric.defaults, {              
            mDec:0
        });      
    });
        
    </script> -->
    <script type="text/javascript">
    $( document ).ready(function() {

        $('input').on('input',function (e) {
           $this = $(this);
           $this.val(Number($this.val().replaceAll(',','').replaceAll('.','') ).toLocaleString('en-US') );
        });
    });
 
    </script>
@endsection


