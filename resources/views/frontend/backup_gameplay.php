<div class="tab-content br-n pn">
				<div class="row">
				<ul class="nav nav-pills m-b-30" >
					@foreach($gameList as $game)
						<?php
						$gamechilderList = GameHelpers::GetAllGameByParentID($game['id']);
						?>
							@if(count($gamechilderList)>0)
								<li>
									<a class="btn btn-white btn-custom waves-effect waves-light btn-sm @if($game['id'] == 18)
										not-active
									@endif" href="#{{$game['game_code']}}"  data-toggle="tab" aria-expanded="false"
									onclick="ClickTabGame('{{$game['game_code']}}')"

									>{{$game['name']}}</a>
								</li>
							@else
								<?php
								$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,$game['id']);
								?>
								<li>
									<a class="btn btn-white btn-custom waves-effect waves-light btn-sm @if($game['id'] == 18)
										not-active
									@endif" href="#{{$gamecur['game_code']}}" onclick="LoadContentGameParent('{{$gamecur['game_code']}}','{{$gamecur['name']}}','{{$gamecur['max_point']}}','{{$gamecur['max_point_one']}}','{{$gamecur['odds']}}','{{$gamecur['open']}}','{{$gamecur['close']}}')" data-toggle="tab" aria-expanded="false"
									>{{$gamecur['name']}}</a>
								</li>
							@endif

					@endforeach

					<div class="row hidden" style=" margin-top: 50px;" id="quick_input_gameplay">
						<div class="col-md-10">
							<div class="editable-input">
								<input type="text" class="form-control input_number_arr" id="number_select_text" autocomplete="off">
							</div>
						</div>
						<div class="col-md-2">

							<button type="button" id="enter_array" class="btn btn-success waves-effect waves-light">Nhập</button>
						</div>
					</div>
				</ul>
				</div>

				@foreach($gameList as $game)
					<?php
						$gamechilderList = GameHelpers::GetAllGameListByCusType($user->customer_type,$user->id,$game['id']);
						$count = 0;
					?>
					@if(count($gamechilderList)>0)
					<div id="{{$game['game_code']}}" class="tab-pane">
						<!-- <div class="panel-body"> -->
							<div class="row">
								<!-- <div class="row"> -->
									<ul class="nav nav-pills m-b-30" >
										@foreach($gamechilderList as $children)
											<li>
												<a class="btn btn-warning btn-custom waves-effect waves-light btn-sm" href="#{{$children['game_code']}}" onclick="LoadContentGame('{{$children['game_code']}}','{{$children['name']}}','{{$children['max_point']}}','{{$children['max_point_one']}}','{{$children['odds']}}','{{$children['open']}}','{{$children['close']}}')" data-toggle="tab" aria-expanded="true"
												 id="gamecode{{$gamecur['game_code']}}">{{$children['name']}}</a>
											</li>
										@endforeach
									</ul>
								<!-- </div> -->
								<div class="tab-content br-n pn hidden-xs">
									@foreach($gamechilderList as $children)
										<div id="{{$children['game_code']}}" class="tab-pane">
											<div class="col-sm-12 col-lg-12 col-md-12 {{$children['game_code']}} game_content" >
											</div>
										</div>
									@endforeach
								</div>
							<!-- </div> -->
						</div>

					</div>
					@else
						<div id="{{$game['game_code']}}" class="tab-pane hidden-xs">
							<div class="row">
								<div class="col-sm-12 col-lg-12 col-md-12 {{$game['game_code']}} game_content">
								</div>
							</div>
						</div>
					@endif
				@endforeach
					<div class="row" >
						<div class="col-lg-12" style="text-align: center !important;">
							<span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
						</div>
					</div>
			</div>



			<?php
$now = \Carbon\Carbon::now();
    $yesterday = date('Y-m-d', time()-86400);
    $datepickerXS= date('d-m-Y', time()-86400);
    if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('M') )<30)){
        $rs = xoso::getKetQua(1,$yesterday);
    }
    else{
        $rs = xoso::getKetQua(1,date('Y-m-d'));
        $datepickerXS= date('d-m-Y');
    }

$gameList = GameHelpers::GetAllGameByParentID(0);
?>
<style>
	li.active{
	pointer-events: none;
	cursor: default;
	}
</style>
@extends("frontend.frontend-template")
@section('sidebar-menu')
	@parent

	<div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Chọn loại</h3>
		</div>
		<div class="panel-body">
			<div class="tab-content br-n pn">
				<div class="row">
				<ul class="nav nav-pills m-b-30" >
					@foreach($gameList as $game)
						<?php
						$gamechilderList = GameHelpers::GetAllGameByParentID($game['id']);
						?>
							@if(count($gamechilderList)>0)
								<li>
									<a class="btn btn-white btn-custom waves-effect waves-light btn-sm @if($game['id'] == 18)
										not-active
									@endif" href="#{{$game['game_code']}}"  data-toggle="tab" aria-expanded="false"
									onclick="ClickTabGame('{{$game['game_code']}}')"

									>{{$game['name']}}</a>
								</li>
							@else
								<?php
								$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,$game['id']);
								?>
								<li>
									<a class="btn btn-white btn-custom waves-effect waves-light btn-sm @if($game['id'] == 18)
										not-active
									@endif" href="#{{$gamecur['game_code']}}" onclick="LoadContentGameParent('{{$gamecur['game_code']}}','{{$gamecur['name']}}','{{$gamecur['max_point']}}','{{$gamecur['max_point_one']}}','{{$gamecur['odds']}}','{{$gamecur['open']}}','{{$gamecur['close']}}')" data-toggle="tab" aria-expanded="false"
									>{{$gamecur['name']}}</a>
								</li>
							@endif

					@endforeach

					<div class="row hidden" style=" margin-top: 50px;" id="quick_input_gameplay">
						<div class="col-md-10">
							<div class="editable-input">
								<input type="text" class="form-control input_number_arr" id="number_select_text" autocomplete="off">
							</div>
						</div>
						<div class="col-md-2">

							<button type="button" id="enter_array" class="btn btn-success waves-effect waves-light">Nhập</button>
						</div>
					</div>
				</ul>
				</div>

				@foreach($gameList as $game)
					<?php
						$gamechilderList = GameHelpers::GetAllGameListByCusType($user->customer_type,$user->id,$game['id']);
						$count = 0;
					?>
					@if(count($gamechilderList)>0)
					<div id="{{$game['game_code']}}" class="tab-pane">
						<!-- <div class="panel-body"> -->
							<div class="row">
								<!-- <div class="row"> -->
									<ul class="nav nav-pills m-b-30" >
										@foreach($gamechilderList as $children)
											<li>
												<a class="btn btn-warning btn-custom waves-effect waves-light btn-sm" href="#{{$children['game_code']}}" onclick="LoadContentGame('{{$children['game_code']}}','{{$children['name']}}','{{$children['max_point']}}','{{$children['max_point_one']}}','{{$children['odds']}}','{{$children['open']}}','{{$children['close']}}')" data-toggle="tab" aria-expanded="true"
												 id="gamecode{{$gamecur['game_code']}}">{{$children['name']}}</a>
											</li>
										@endforeach
									</ul>
								<!-- </div> -->
								<div class="tab-content br-n pn hidden-xs">
									@foreach($gamechilderList as $children)
										<div id="{{$children['game_code']}}" class="tab-pane">
											<div class="col-sm-12 col-lg-12 col-md-12 {{$children['game_code']}} game_content" >
											</div>
										</div>
									@endforeach
								</div>
							<!-- </div> -->
						</div>

					</div>
					@else
						<div id="{{$game['game_code']}}" class="tab-pane hidden-xs">
							<div class="row">
								<div class="col-sm-12 col-lg-12 col-md-12 {{$game['game_code']}} game_content">
								</div>
							</div>
						</div>
					@endif
				@endforeach
					<div class="row" >
						<div class="col-lg-12" style="text-align: center !important;">
							<span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
						</div>
					</div>
			</div>
		</div>
	</div>

	<div class="panel panel-color panel-inverse" style="display: block;" id="panel_bet">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Đặt cược</h3>
		</div>
		<div class="panel-body">

			
			
			<div class="row">
				<form class="form-horizontal" role="form">
					<div class="form-group" style="text-align: center">
						<div class="col-md-4 hidden-xs">
							<label class="control-label">Đặt cược</label>
							
						</div>
						<div class="col-md-5 col-xs-5">
							<input style="text-align: center" class="form-control" onkeypress='return event.charCode >= 48 && event.charCode <= 57' id="input_point" placeholder="Số điểm cho mỗi con" onkeyup="ChangeTotalPoint(this)" autocomplete="off">
						</div>
						<div class="col-md-3 hidden-xs">
							<label class="control-label">Điểm</label>
							
						</div>

					</div>
					<div class="form-group" style="text-align: center">
						<div class="col-md-6 col-xs-6">
							<button type="button" id="btn_Delete" onclick="Huy()" class="btn btn-danger btn-success waves-effect waves-light">Hủy</button>
						</div>
						<div class="col-md-6 col-xs-6">

							<button type="button" id="btn_OK" onclick="DatCuoc()" class="btn btn-success waves-effect waves-light">Đặt</button>
						</div>
					</div>
					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Đài:</label>
						<div class="col-md-6 col-xs-6">
							<p class="form-control-static" id="local_name">{{$location->name}}</p>
						</div>
					</div>

					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Hình thức:</label>
						<div class="col-md-6 col-xs-6">
							<p class="form-control-static" id="game_name">Đề</p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Số đánh:</label>
						<div class="col-md-6 col-xs-6">
							<p class="form-control-static" id="number_select"></p>
						</div>
					</div>

					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Số xiên:</label>
						<div class="col-md-6 col-xs-6">
							<p class="form-control-static" id="number_select_xien"></p>
						</div>
					</div>

					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Tổng điểm:</label>
						<div class="col-md-6 col-xs-6">
							<p class="form-control-static" id="point">0</p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Thành tiền:</label>
						<div class="col-md-6 col-xs-6">
							<p class="form-control-static" id="total">0</p>
						</div>
					</div>
					<div class="row hidden">
						<label class="col-md-6 control-label"><i>Cược tối đa:</i></label>
						<div class="col-md-6">
							<p class="form-control-static" id="max_point"></p>
						</div>
					</div>
					<div class="row hidden">
						<label class="col-md-6 control-label"><i>Cược tối đa /1 số:</i></label>
						<div class="col-md-6">
							<p class="form-control-static" id="max_point_one"></p>
						</div>
					</div>
					<div class="row">
						<label class="col-md-6 control-label form-control-static col-xs-6">Trả thưởng:</label>
						<div class="col-md-6 col-xs-5">
							<p class="form-control-static" id="odds"></p>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div class="panel panel-color panel-inverse hidden-xs">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Thời gian còn lại</h3>
		</div>
		<div class="panel-body">
			@foreach($gameList as $game)
			<div class="row">
				<div class="col-xs-6"><b>{{$game['name']}}: </b></div>
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
				<input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
				<div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
			</div>
			@endforeach
		</div>
	</div>

	<div class="panel panel-color panel-inverse hidden-xs">
        <div class="panel-heading recent-heading">
            <h3 class="panel-title">Kết quả ngày <span class="badge badge-blue">{{$rs['date']}}</span></h3>
        </div>
        <div class="panel-body">
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>ĐB</b></div>
                <div class="col-md-10">
                    <div class="col-md-12 jackpot"><span class="badge badge-blue">{{$rs['DB']}}</span></div>
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G1</b></div>
                <div class="col-md-10">
                    <div class="col-md-12 first"><span class="badge badge-blue">{{$rs['1']}}</span></div>
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G2</b></div>
                <div class="col-md-10">
                    @foreach($rs['2'] as $item)
                        <div class="col-md-6 second1st"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G3</b></div>
                <div class="col-md-10">
                    @foreach($rs['3'] as $item)
                        <div class="col-md-4 third3rd"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G4</b></div>
                <div class="col-md-10">
                    @foreach($rs['4'] as $item)
                        <div class="col-md-3 fourth4th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G5</b></div>
                <div class="col-md-10">
                    @foreach($rs['5'] as $item)
                        <div class="col-md-4 fiveth6th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach

                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G6</b></div>
                <div class="col-md-10">
                    @foreach($rs['6'] as $item)
                        <div class="col-md-4 sixth3rd"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
            <div class="row" style="margin:2px;">
                <div class="col-md-2"><b>G7</b></div>
                <div class="col-md-10">
                    @foreach($rs['7'] as $item)
                        <div class="col-md-3 seventh4th"><span class="badge badge-blue">{{$item}}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@stop
@section("content")
	<div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
		<div class="panel-body">
			
			<div class="tab-content br-n pn">
				<div class="row">
				<ul class="nav nav-pills m-b-30" >
					@foreach($gameList as $game)
						<?php
						$gamechilderList = GameHelpers::GetAllGameByParentID($game['id']);
						?>
							@if(count($gamechilderList)>0)
								<li>
									<a class="btn btn-white btn-custom waves-effect waves-light btn-sm @if($game['id'] == 18)
										not-active
									@endif" href="#{{$game['game_code']}}"  data-toggle="tab" aria-expanded="false"
									onclick="ClickTabGame('{{$game['game_code']}}')"

									>{{$game['name']}}</a>
								</li>
							@else
								<?php
								$gamecur = GameHelpers::GetGameByCusType($user->customer_type,$user->id,$game['id']);
								?>
								<li>
									<a class="btn btn-white btn-custom waves-effect waves-light btn-sm @if($game['id'] == 18)
										not-active
									@endif" href="#{{$gamecur['game_code']}}" onclick="LoadContentGameParent('{{$gamecur['game_code']}}','{{$gamecur['name']}}','{{$gamecur['max_point']}}','{{$gamecur['max_point_one']}}','{{$gamecur['odds']}}','{{$gamecur['open']}}','{{$gamecur['close']}}')" data-toggle="tab" aria-expanded="false"
									>{{$gamecur['name']}}</a>
								</li>
							@endif

					@endforeach

					<div class="row hidden" style=" margin-top: 50px;" id="quick_input_gameplay">
						<div class="col-md-10">
							<div class="editable-input">
								<input type="text" class="form-control input_number_arr" id="number_select_text" autocomplete="off">
							</div>
						</div>
						<div class="col-md-2">

							<button type="button" id="enter_array" class="btn btn-success waves-effect waves-light">Nhập</button>
						</div>
					</div>
				</ul>
				</div>

				@foreach($gameList as $game)
					<?php
						$gamechilderList = GameHelpers::GetAllGameListByCusType($user->customer_type,$user->id,$game['id']);
						$count = 0;
					?>
					@if(count($gamechilderList)>0)
					<div id="{{$game['game_code']}}" class="tab-pane">
						<!-- <div class="panel-body"> -->
							<div class="row">
								<!-- <div class="row"> -->
									<ul class="nav nav-pills m-b-30" >
										@foreach($gamechilderList as $children)
											<li>
												<a class="btn btn-warning btn-custom waves-effect waves-light btn-sm" href="#{{$children['game_code']}}" onclick="LoadContentGame('{{$children['game_code']}}','{{$children['name']}}','{{$children['max_point']}}','{{$children['max_point_one']}}','{{$children['odds']}}','{{$children['open']}}','{{$children['close']}}')" data-toggle="tab" aria-expanded="true"
												 id="gamecode{{$gamecur['game_code']}}">{{$children['name']}}</a>
											</li>
										@endforeach
									</ul>
								<!-- </div> -->
								<div class="tab-content br-n pn hidden-xs">
									@foreach($gamechilderList as $children)
										<div id="{{$children['game_code']}}" class="tab-pane">
											<div class="col-sm-12 col-lg-12 col-md-12 {{$children['game_code']}} game_content" >
											</div>
										</div>
									@endforeach
								</div>
							<!-- </div> -->
						</div>

					</div>
					@else
						<div id="{{$game['game_code']}}" class="tab-pane hidden-xs">
							<div class="row">
								<div class="col-sm-12 col-lg-12 col-md-12 {{$game['game_code']}} game_content">
								</div>
							</div>
						</div>
					@endif
				@endforeach
					<div class="row" >
						<div class="col-lg-12" style="text-align: center !important;">
							<span class="fa fa-spin fa-refresh refresh" style="text-align: center !important;"></span>
						</div>
					</div>
			</div>



		</div>
	</div>
	<input type="hidden" id="current_game" value="">
	<input type="hidden" id="gamecode" value="">
	<input type="hidden" id="url" value="{{url('/games')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<input type="hidden" id="open" value="">
	<input type="hidden" id="close" value="">

	@if($user->roleid==1 || $user->roleid==2 || $user->roleid==4 || $user->roleid==5)
		<input type="hidden" id="flag-play" value="0">
	@else
		<input type="hidden" id="flag-play" value="1">
	@endif
		<a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
		<a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã cược thành công')"></a>
@endsection

<script type="text/javascript">

	function refreshHistory() {
		$('#history').fadeOut();
		$('#history').load("{{url('/refresh-history')}}", function() {
			$('#history').fadeIn();
		});
	}
	function ClickTabGame(gamecode)
	{
		$('#number_select_text').val('');
		$('#number_select_xien').html('');
		if (gamecode == 1)
		{
			$('#gamecode'+15).click();
		}
	}

	
</script>
