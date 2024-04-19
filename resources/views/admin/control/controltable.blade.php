@extends('admin.admin-template')
@section('title', 'Bảng thao tác giá')
@section('content')

<?php

use App\Location;

$location = Location::find(intval($locationId));
?>
<style>
	li.active {
		pointer-events: none;
		cursor: default;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="portlet">
			<!-- /primary heading -->
			<div class="portlet-heading">
				<h3 class="portlet-title text-dark text-uppercase">
					Bảng thao tác giá
				</h3>
				<!--<div class="col-sm-1 ">-->
				<!--	<a class="btn btn-default btn-custom waves-effect waves-light btn-sm @if($locationId == 1) disabled @endif" href="\control-price/mien-bac">Miền Bắc</a>-->
				<!--</div>-->

				<!--<div class="col-sm-1 hidden" style="margin-left: 5px;">-->
				<!--			<button type="button" class="btn btn-default btn-custom waves-effect waves-light btn-sm"  data-toggle="dropdown" aria-expanded="false" -->
				<!--			@if(isset($location) && ($location->slug==21 || $location->slug==22) )-->
				<!--		@endif >Miền Nam</button>-->
				<!--			<ul class="dropdown-menu" role="menu" >-->
				<!--				<li><a class="btn btn-warning btn-custom waves-effect waves-light btn-xs" style="margin-bottom: 0px;margin-left: 0px;" href="/control-price/mien-nam/21">Đài 1</a></li>-->
				<!--				<li><a class="btn btn-warning btn-custom waves-effect waves-light btn-xs" style="margin-bottom: 0px;margin-left: 0px;" href="/control-price/mien-nam/22">Đài 2</a></li>-->
				<!--			</ul>-->
				<!--		</div>-->

				<!--		<div class="col-sm-1" style="margin-left: 12px;">-->
				<!--			<button type="button" class="btn btn-default btn-custom waves-effect waves-light btn-sm"  data-toggle="dropdown" aria-expanded="false" -->
				<!--			@if(isset($location) && ($location->slug==31 || $location->slug==32) )-->
				<!--		@endif >Miền Trung</button>-->
				<!--			<ul class="dropdown-menu" role="menu" >-->
				<!--				<li><a class="btn btn-warning btn-custom waves-effect waves-light btn-xs" style="margin-bottom: 0px;margin-left: 0px;" href="/control-price/mien-trung/31">Đài 1</a></li>-->
				<!--				<li><a class="btn btn-warning btn-custom waves-effect waves-light btn-xs" style="margin-bottom: 0px;margin-left: 0px;" href="/control-price/mien-trung/32">Đài 2</a></li>-->
				<!--			</ul>-->
				<!--		</div>-->

				<!--		<div class="col-sm-1" style="margin-left: 20px;">-->
				<!--	<a class="btn btn-default btn-custom waves-effect waves-light btn-sm @if($locationId == 5) disabled @endif" href="\control-price/keno">Keno</a>-->
				<!--</div>-->
				<!--<div class="col-sm-1" style="margin-left: 5px;">-->
				<!--	<a class="btn btn-default btn-custom waves-effect waves-light btn-sm @if($locationId == 4) disabled @endif" href="\control-price/xs-ao">XS Ảo</a>-->
				<!--</div>-->
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="portlet">
			<!-- /primary heading -->
			<div class="portlet-body">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="form-horizontal" role="form">

							<div class="form-group has-feedback">

								<div class="col-sm-2 col-xs-2">
									<div class="col-sm-8 input-group">
										<!-- <span class="input-group-addon"><i class="fa fa-drupal"></i></span> -->
										<select name="custype" id="custype" selected=3>
											<option value="60">60s</option>
											<option value="40">40s</option>
											<option value="30">30s</option>
											<option value="20">20s</option>
											<option selected="selected" value="10">10s</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4  col-xs-4">Kỳ: <span class="badge badge-white term_name" current="20161013">{{date('Ymd')}}</span></div>
								<div class="col-sm-4  col-xs-4">Hết hạn: <span class="badge badge-white deadlineBet">18:30</span></div>
								<div class="col-sm-2  col-xs-2"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@if (Auth::user()->roleid == 1 || Auth::user()->roleid == 2)
<div class="row">
	<div class="col-sm-12 col-xs-12">
		<div class="portlet">
			<!-- /primary heading -->
			<div class="portlet-body">
				<div class="row">
					<div class="col-sm-12 col-xs-12">
						<div class="col-sm-8 col-xs-8">
							<div class="">
								<!-- <input type="tel" id="input_point" name="example-input2-group2" class="form-control" placeholder="Khoá/Mở khoá nhanh" autocomplete="off"> -->

								<input type="tel" id="number_select_text" name="example-input2-group2" class="form-control" placeholder="Nhập nhanh số cược" autocomplete="off" control-id="ControlID-11">
								<!-- onkeyup="ChangeTotalPoint(this)"  -->
								<!-- onkeypress='return event.charCode >= 48 && event.charCode <= 57' -->
								<!-- <span class="input-group-btn">
												<button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array">Xác nhận</button>
												</span> -->
							</div>
						</div>
						<div class="col-sm-4 col-xs-4">
							@if(Auth::user()->roleid == 1)
								<button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array" onclick="ConfirmDialogQuickLockRed(this,'qlocknumber', '0');" >Khoá số</button>
							@else
								<button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array" onclick="ConfirmDialogQuickLockBlack(this,'qlocknumber', '0');" >Khoá số</button>
							@endif
							<button type="button" class="btn waves-effect waves-light btn-primary" id="enter_array" onclick="ConfirmDialogQuickLock(this,'qlocknumber', '1');" >Mở khoá</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="panel panel-default">

			<div class="panel-body">


				<div class="tab-content br-n pn">
					<!-- <div class="panel-body"> -->
					<div class="row">
						<div class="centered-pills">
							<?php
							$gameList = GameHelpers::GetAgGameList($locationId);
							$count = 0;
							?>
							@if(count($gameList)>0)

							<select name="forma" onchange="onClickTabLink(this.value)" style="    height: 30px;
    font-size: 14px;
    margin-bottom: 10px;">
								@foreach($gameList as $game)
								<?php if (
									$game['game_code'] == 18 || $game['game_code'] == 17 || $game['game_code'] == 318 || $game['game_code'] == 417 || $game['game_code'] == 517 || $game['game_code'] == 617 || $game['game_code'] == 317 || $game['game_code'] == 56
									|| $game['game_code'] == 122 || $game['game_code'] == 123
									|| $game['game_code'] == 118 || $game['game_code'] == 117
									|| $game['game_code'] == 352
								)
									continue;
								?>
									<option value="{{$game['game_code']}}">{{$game['name']}}</option>
								@endforeach
							
							</select>

							<ul class="nav nav-pills m-b-30" hidden>
								<!-- <li>
								<a class="btn btn-default btn-custom waves-effect waves-light btn-sm" data-toggle="modal" data-target="#changecontrol-modal">Cực nhanh</a>
							</li> -->
								@foreach($gameList as $game)
								<?php if (
									$game['game_code'] == 18 || $game['game_code'] == 17 || $game['game_code'] == 318 || $game['game_code'] == 417 || $game['game_code'] == 517 || $game['game_code'] == 617 || $game['game_code'] == 317 || $game['game_code'] == 56
									|| $game['game_code'] == 122 || $game['game_code'] == 123
									|| $game['game_code'] == 118 || $game['game_code'] == 117
									|| $game['game_code'] == 352
								)
									continue;
								?>
								<li>
									<a id="tab_link_{{$game['game_code']}}" class="btn btn-default btn-custom waves-effect waves-light btn-sm" href="#{{$game['game_code']}}" onclick="LoadContentNumber('{{$game['game_code']}}')" data-toggle="tab" aria-expanded="false">

										@if(isset($location))
										{{$location->name}}
										@endif - {{$game['name']}}

									</a>
								</li>
								@endforeach
							</ul>
							@endif
							<!-- </div> -->
						</div>
					</div>
					@foreach($gameList as $game)
					<?php
					$gamechilderList = GameHelpers::GetAllAgGameByParentID($game['game_code']);
					$count = 0;
					?>
					@if(count($gamechilderList)>0 && $game['game_code']!= 24)
					<div id="{{$game['game_code']}}" class="tab-pane">
						<!-- <div class="panel-body"> -->
						<div class="row">
							<div class="centered-pills">
								<ul class="nav nav-pills m-b-30">
									@foreach($gamechilderList as $children)
									<?php if (
										$children['game_code'] == 8 || $children['game_code'] == 19
										|| $children['game_code'] == 308
										|| $children['game_code'] == 20 || $children['game_code'] == 21
										|| $children['game_code'] == 108 || $children['game_code'] == 119
										|| $children['game_code'] == 120 || $children['game_code'] == 121
									) continue; ?>
									<li>
										<a class="btn btn-default btn-custom waves-effect waves-light btn-sm" href="#{{$children['game_code']}}" onclick="LoadContentNumber('{{$children['game_code']}}')" data-toggle="tab" aria-expanded="false">{{$children['name']}}</a>
									</li>
									@endforeach
								</ul>
							</div>
							<div class="tab-content br-n pn">
								@foreach($gamechilderList as $children)
								<div id="{{$children['game_code']}}" class="tab-pane">
								</div>
								@endforeach
							</div>
						</div>
						<!-- </div> -->
					</div>
					@else
					<div id="{{$game['game_code']}}" class="tab-pane">
					</div>
					@endif
					@endforeach
					<div class="row">
						<div class="col-lg-12" style="text-align: center !important;">
							<span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('admin.control.changecontrol',['gameList'=>$gameList])
<input type="hidden" id="url" value="{{url('/control-price')}}">
<input type="hidden" id="gamecode" value="">
<input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection
@section('js_call')
<script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>
<script src="/assets/admin/js/control.js?v=1.031"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css" rel="stylesheet" />
<script>
	function onClickTabLink(value){
		console.log($('#tab_link_'+value))
		$('#tab_link_'+value).click()
	}
	$('.nav.nav-pills.m-b-30 li a')[0].click();

	$('#number_select_text').keyup(function(event) {
		var number_length = 1;
		if ($('#gamecode').val() == "17" || $('#gamecode').val() == "317" || $('#gamecode').val() == "56" || $('#gamecode').val() == "22" || $('#gamecode').val() == "8" || $('#gamecode').val() == "308"
		|| $('#gamecode').val() == "117" || $('#gamecode').val() == "122" || $('#gamecode').val() == "108" || $('#gamecode').val() == "352" || $('#gamecode').val() == "452" || $('#gamecode').val() == "552" || $('#gamecode').val() == "652")
			number_length = 2;

		var value = $(this).val().split(',')
		if (value[value.length - 1].length > number_length) {
			$(this).val(value + ',')
		}
		// $('#number_select').html($('#number_select_text').val())

		if (event.keyCode == 13) {
			$("#enter_array").click();
		}
	});
</script>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script> -->
@endsection