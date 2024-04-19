@extends('admin.adminlte_template')
@section('title', 'Bảng thao tác giá')
@section('content')
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-heading">
					<h3 class="portlet-title text-dark text-uppercase">
						Bảng thao tác giá
					</h3>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="portlet"><!-- /primary heading -->
				<div class="portlet-body">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-horizontal" role="form">
								<div class="form-group has-feedback">
									<div class="col-sm-2">
										<input type="text" class="form-control" readonly id="time_count">
										<span class="fa fa-spin fa-refresh form-control-feedback"></span>
									</div>
									<div class="col-sm-2">Kỳ: <span class="badge badge-white term_name" current="20161013">{{date('Ymd')}}</span></div>
									<div class="col-sm-2">Hết hạn: <span class="badge badge-white deadlineBet">18:30:00</span></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<?php
					$gameList = GameHelpers::GetGameList(1);
					$count = 0;
					?>
					@if(count($gameList)>0)
						<ul class="nav nav-pills m-b-30 pull-right">
							<li>
								<a data-toggle="modal" data-target="#changecontrol-modal">Cực nhanh</a>
							</li>
							@foreach($gameList as $game)
								<li>
									<a href="#{{$game['game_code']}}" onclick="LoadContentNumber('{{$game['game_code']}}')" data-toggle="tab" aria-expanded="false">{{$game['name']}}</a>
								</li>
							@endforeach
						</ul>
					@endif
					<div class="tab-content br-n pn">
						@foreach($gameList as $game)
							<?php
							$gamechilderList = GameHelpers::GetAllGameByParentID($game['id']);
							$count = 0;
							?>
							@if(count($gamechilderList)>0)
							<div id="{{$game['game_code']}}" class="tab-pane">
								<div class="panel-body">
									<div class="row">
										<div class="centered-pills">
											<ul class="nav nav-pills m-b-30" >
												@foreach($gamechilderList as $children)
													<li>
														<a href="#{{$children['game_code']}}" onclick="LoadContentNumber('{{$children['game_code']}}')" data-toggle="tab" aria-expanded="false">{{$children['name']}}</a>
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
								</div>
							</div>
							@else
								<div id="{{$game['game_code']}}" class="tab-pane">
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
		</div>
	</div>
	@include('admin.control.changecontrol',['gameList'=>$gameList])
	<input type="hidden" id="url" value="{{url('/control')}}">
	<input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection
@section('js_call')
<script src="/assets/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/admin/plugins/parsleyjs/dist/parsley.min.js"></script>
    <script src="/assets/admin/plugins/autoNumeric/autoNumeric.js" type="text/javascript"></script> 
	<script src="/assets/admin/plugins/isotope/dist/isotope.pkgd.min.js"></script>
	<script src="/assets/admin/js/control.js"></script>
@endsection