<?php
$now = \Carbon\Carbon::now();
    $yesterday = date('Y-m-d', time()-86400);
    $datepickerXS= date('d-m-Y', time()-86400);
    if(intval(date('H') )<18 || (intval(date('H') )==18 && intval(date('M') )<30)){
		// Cache::tags('authors')->add('kqxs-'.$yesterday,xoso::getKetQua(1,$yesterday),env('CACHE_TIME', 0));
		$rs = 
		// Cache::tags('kqxs')->remember('kqxs-1-'.$yesterday, env('CACHE_TIME', 0), function () use ($yesterday) {
			// return 
			xoso::getKetQua(1,$yesterday);
		// });
    }
    else{
		// $rs = xoso::getKetQua(1,date('Y-m-d'));
		$rs = 
		// Cache::tags('kqxs')->remember('kqxs-1-'.date('Y-m-d'), env('CACHE_TIME', 0), function () {
			// return 
			xoso::getKetQua(1,date('Y-m-d'));
		// });
        $datepickerXS= date('d-m-Y');
    }

$gameList = GameHelpers::GetAllGameByParentID(0,$location->slug);
?>
<style>
	/*li.active{
	pointer-events: none;
	cursor: default;
	}*/

	li{
	margin:2px;
	}

	.panel-title{
		font-size: 12px !important;
	}
</style>
@extends("frontend.frontend-template")
@section('sidebar-menu')
	@parent

	{{-- <div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title">Chọn khu vực</h6>
		</div>
		<div class="panel-body">
			<button type="button" class="btn btn-primary  waves-effect waves-light btn-xs">Miền Bắc</button>
			<button type="button" class="btn btn-primary btn-custom waves-effect waves-light btn-xs not-active">Miền Trung</button>
			<button type="button" class="btn btn-primary btn-custom waves-effect waves-light btn-xs not-active">Miền Nam</button>
		</div>
	</div> --}}

	<!-- <div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h6 class="panel-title">Chọn loại</h6>
		</div>
		<div class="panel-body">
			<div class="tab-content br-n pn">
				

				
					
			</div>
		</div>
	</div> -->

	<div class="panel panel-color panel-inverse">
		<div class="panel-heading recent-heading">
			<h3 class="panel-title">Thời gian còn lại</h3>
		</div>
		<div class="panel-body hidden" id="open_close_game_timer" >
			@foreach($gameList as $game)
			<div class="row">
				<!-- <div class="col-xs-4"><b>{{$game['name']}}: </b></div> -->
				<input type="hidden" class="hd_clock_open" value="{{$game['open']}}">
				<input type="hidden" class="hd_clock_close" value="{{$game['close']}}">
				<!-- <div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div> -->
			</div>
			@endforeach
		</div>
		<div class="panel-body">
			@foreach($gameList as $game)
			<div class="row">
				<div class="col-xs-6"><b>{{$game['name']}}: </b></div>
				<!-- <input type="hidden" class="hd_clock_open" value="{{$game['open']}}"> -->
				<!-- <input type="hidden" class="hd_clock_close" value="{{$game['close']}}"> -->
				<div class="col-xs-6"><p class="clock" id="clock_{{$game['game_code']}}"></p></div>
			</div>
			@endforeach
		</div>
	</div>

@stop
@section("content")
	<div id="game-play" class="panel panel-color panel-default panel-border panel-shadow panel-account">
		<div class="panel-body">
			<div class="row">
			</div>
			
			
			<div class="row">
				<div class="form-group">
				
					<table class="table table-bordered mails m-0 table-actions-bar table-striped table-hover" style="font-size: 12px !important;">
                        <thead>
                        <tr>
							<th style="width:5%">STT</th>
                            <th style="width:15%">Thời gian</th>
                            <th style="width:40%">Nội dung cược nhanh</th>
							<th style="width:40%">Nội dung cược hủy</th>
                        </tr>
                        </thead>
                        <tbody>
						<?php
							$count=1;
						?>
							@for($i=0;$i< count($quickplayhistory);$i++)
								<tr>
									<td width='50px'>{{$count++}}</td>
									<td width='150px'>{{$quickplayhistory[$i]->created_at}}</td>
									<td>{{$quickplayhistory[$i]->content}}</td>
									<td style='color: red;'>{{$quickplayhistory[$i]->cancel}}</td>
								</tr>
							@endfor
						</tbody>
						<tfoot>
                            							<tr>
							                            </tr>
							                        </tfoot>
				</div>
			</div>

			</br>
			
		</div>
		
	</div>
	<input type="hidden" id="current_game" value="">
	<input type="hidden" id="gamecode" value="">
	<input type="hidden" id="url" value="{{url('/games')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
	<input type="hidden" id="open" value="">
	<input type="hidden" id="close" value="">
	<input type="hidden" id="url_kqsxmin" value="{{url('/kqsxmin-by-day')}}">

	@if($user->roleid==1 || $user->roleid==2 || $user->roleid==4 || $user->roleid==5)
		<input type="hidden" id="flag-play" value="0">
	@else
		<input type="hidden" id="flag-play" value="1">
	@endif
		<a id="btn_CheckGame" href="javascript:;" onclick="$.Notification.notify('error','top center', 'Thông báo', 'Chưa chọn loại game')"></a>
		<!-- <a id="btn_CreateOK" href="javascript:;" onclick="$.Notification.notify('success','top center', 'Thông báo', 'Đã cược thành công')"></a> -->
@endsection

<script type="text/javascript">

	function refreshHistory() {
		$('#history').fadeOut();
		$('#history').load("{{url('/refresh-history')}}", function() {
			$('#history').fadeIn();
		});
	}
	// function ClickTabGame(gamecode)
	// {
	// 	$('#number_select_text').val('');
	// 	$('#number_select_xien').html('');
	// 	if (gamecode == 1)
	// 	{
	// 		$('#gamecode'+15).click();
	// 	}
	// }

	
</script>
