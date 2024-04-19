@extends('admin.admin-template')
@section('title', 'Danh sách thông báo')
@section('content')
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/select2/dist/css/select2.min.css">
<style>
	/* Popover */
	.popover {
		/*border: 2px blue;*/
		width: 80px;
	}

	/* Popover Header */
	.popover-title {
		background-color: #73AD21;
		color: #FFFFFF;
		font-size: 12px;
		text-align: center;
		height: 40px;
	}

	/* Popover Body */
	.popover-content {
		/*background-color: coral;*/
		/*color: #FFFFFF;*/
		text-align: center;
		padding: 12px;
		height: 40px;
	}

	/* Popover Arrow */
	.arrow {
		/*border-right-color: red !important;*/
	}

	.select2-container--default .select2-selection--single,
	.select2-selection .select2-selection--single {
		border: 1px solid #d2d6de;
		border-radius: 0;
		padding: 6px 12px;
		height: 38px;
		font-size: 1.1em;
	}

	.select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 38px;
		position: absolute;
		top: 1px;
		right: 1px;
		width: 20px;
		font-size: 1.1em;
	}
</style>

<style>
	.dropbtn {
		/* background-color: #3498DB; */
		/* color: white; */
		/* padding: 16px; */
		/* font-size: 16px; */
		border: none;
		cursor: pointer;
	}

	.dropbtn:hover,
	.dropbtn:focus {
		background-color: #2980B9;
	}

	.dropdown {
		position: relative;
		display: inline-block;
	}

	.dropdown-content {
		display: none;
		position: absolute;
		background-color: #f1f1f1;
		min-width: 100px;
		overflow: auto;
		box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
		z-index: 1;
	}

	.dropdown-content a {
		color: black;
		padding: 10px 6px;
		text-decoration: none;
		display: block;
	}

	.dropdown a:hover {
		background-color: #ddd;
	}

	.show {
		display: block;
	}

	.tab-content{
		padding: 0px 0px 15px 0px !important;
	}
</style>

<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab_4" data-toggle="tab" aria-expanded="true">Tin cược chờ</a></li>
		<li class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Tin cược đã xử lý</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_4">
			<div class="row" hidden>
				<div class="col-sm-12">
					<div class="portlet"><!-- /primary heading -->
						<div class="portlet-heading">
							<h3 class="portlet-title text-dark text-uppercase">
								{{'Tin cược chờ'}}
							</h3>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="row hidden">
						<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
							<input class="form-control column_filter input-daterange-datepicker-self" type="text" name="daterange" value="" readonly="readonly" style="width: 250px; ">
						</div>
						<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
							<select class="js-notification-category-single" name="notification_category" id="select_notification_category">
								<option value="all">Tất cả</option>
								<option value="system">Hệ thống</option>
								<option value="generate">Chung</option>
								<option value="personal">Cá nhân</option>
							</select>
						</div>

						<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
							<span class="input-group-btn">
								<a style="margin-right:5px;" href="#" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter_target">Xem</a>
							</span>
						</div>
					</div>
					<br />
					<style>
						.line-break {
							padding-top: 10px;
							/* border-bottom: 1px solid rgba(0, 0, 0, 0.05);
							*/
							border: 1px solid rgba(0, 0, 0, 0.05);
						}
						.title-sub-card{
							font-size: 13px;
							font-weight: 600;
							text-decoration: solid underline purple 1px;
						}

						.card-text-line-break{
							padding-left: 10px;
							padding-top: 5px;
							word-wrap: break-word; white-space: -moz-pre-wrap; white-space: pre-wrap;
						}
					</style>
					@foreach($bets_nc as $item)
					<div class="card-box" style="font-size:14px;">
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<label style="display: flex; align-items: center;">{{$item->user_name}} Tin {{$item->id_inday}}
								<div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
									<em style="font-weight:500; font-size:12px;">{{$item->updated_at}}</em>
									
								</div>
							</label>
						</div>
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin gốc: </em>
							<p class="card-text card-text-line-break">{{$item->content}}</p>
						</div>

						@if(isset($item->confirmed) && false)
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin nhận: </em>
							<p class="card-text card-text-line-break">{{$item->confirmed}}</p>
						</div>
						@endif

						@if(isset($item->transition) )
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin nhận: </em>
							<p class="card-text card-text-line-break">{!!$item->transition!!}</p>
							<em class="card-text"> Đặt cược: {{number_format($item->money)}}</em>
							<!-- <p class="card-text" style="padding-left:10px; font-weight:600; font-size:13px;">Đặt cược: {{number_format($item->money)}}</p> -->
						</div>
						@endif

						@if(isset($item->cancel) && $item->cancel != "")
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin hủy: </em>
							<p class="card-text card-text-line-break" style="text-decoration: line-through;">{{$item->cancel}}</p>
							<!-- <p class="card-text" style="font-weight:600 !important;">Đặt cược {{$item->money}}</p> -->
						</div>
						@endif
						
						@if(!isset($item->is_done) || $item->is_done == 0)
						<div class="col-lg-12 line-break" style="text-align: right !important;" bis_skin_checked="1">
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 30)) )
							@else
							<button type="button" id="btn_Bet" onclick="actionBet(this)" bet_id="{{$item->id}}" class="btn btn-primary btn-custom waves-effect waves-light" control-id="ControlID-1">Vào cược</button>
							<a class="btn btn-default btn-custom waves-effect waves-light" href="/games/need-confirm-bet?id={{$item->id}}" class="button">Sửa tin</a>
							@endif
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
							@else
								<button type="button" id="btn_Cancel" onclick="actionCancel(this)" bet_user_name="{{$item->user_name}}" bet_id_inday="{{$item->id_inday}}" bet_id="{{$item->id}}" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy cược</button>
							@endif
							<!-- <button type="button" id="btn_Cancel" onclick="confirmCancel(this)" bet_user_name="{{$item->user_name}}" bet_id_inday="{{$item->id_inday}}" bet_id="{{$item->id}}" onclick="" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy cược</button> -->
							<!-- <button type="button" id="btn_OK" onclick="" class="btn btn-primary btn-custom waves-effect waves-light" control-id="ControlID-1">Xử lý tin</button> -->
							<!-- <button type="button" id="btn_OK" onclick="" class="btn btn-default btn-custom waves-effect waves-light" control-id="ControlID-1">Sửa tin</button> -->
							<!-- <button type="button" id="btn_OK" onclick="" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy bỏ</button> -->
						</div>
						@endif

						@if(isset($item->is_done) && $item->is_done == 1)
						<div class="col-lg-12 line-break" style="text-align: right !important;" bis_skin_checked="1">
							<label class="title-sub-card">Đã vào cược thành công: {{number_format($item->money)}}</label>
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
							@else
							<button type="button" id="btn_Cancel" onclick="confirmCancel(this)" bet_user_name="{{$item->user_name}}" bet_id_inday="{{$item->id_inday}}" bet_id="{{$item->id}}" onclick="" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy cược</button>
							@endif
						</div>
						@endif

						@if(isset($item->is_done) && $item->is_done == -1)
						<div class="col-lg-12 line-break" style="color:red;text-align: right !important;" bis_skin_checked="1">
							<label class="title-sub-card">Đã hủy cược thành công</label>
						</div>
						@endif

					</div>
					@endforeach
					<span class="col-lg-12">Tìm thấy <mark>{{count($bets)}}</mark> tin cược.</span>
				</div>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/users')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			</div>
		</div>

		<div class="tab-pane" id="tab_1">
			<div class="row" hidden>
				<div class="col-sm-12">
					<div class="portlet"><!-- /primary heading -->
						<div class="portlet-heading">
							<h3 class="portlet-title text-dark text-uppercase">
								{{'Tin cược đã xử lý'}}
							</h3>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="row hidden">
						<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
							<input class="form-control column_filter input-daterange-datepicker-self" type="text" name="daterange" value="" readonly="readonly" style="width: 250px; ">
						</div>
						<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
							<select class="js-notification-category-single" name="notification_category" id="select_notification_category">
								<option value="all">Tất cả</option>
								<option value="system">Hệ thống</option>
								<option value="generate">Chung</option>
								<option value="personal">Cá nhân</option>
							</select>
						</div>

						<div class="col-sm-2 col-xs-2" bis_skin_checked="1">
							<span class="input-group-btn">
								<a style="margin-right:5px;" href="#" class="btn waves-effect waves-light btn-primary" id="btn_view_by_filter_target">Xem</a>
							</span>
						</div>
					</div>
					<br />
					<style>
						.line-break {
							padding-top: 10px;
							/* border-bottom: 1px solid rgba(0, 0, 0, 0.05);
							*/
							border: 1px solid rgba(0, 0, 0, 0.05);
						}
						.title-sub-card{
							font-size: 13px;
							font-weight: 600;
							text-decoration: solid underline purple 1px;
						}

						.card-text-line-break{
							padding-left: 10px;
							padding-top: 5px;
							word-wrap: break-word; white-space: -moz-pre-wrap; white-space: pre-wrap;
						}
					</style>
					@foreach($bets as $item)
					<div class="card-box" style="font-size:14px;">
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<label style="display: flex; align-items: center;">{{$item->user_name}} Tin {{$item->id_inday}}
								<div style="flex: 1; display: flex; justify-content: flex-end;" bis_skin_checked="1">
									<em style="font-weight:500; font-size:12px;">{{$item->updated_at}}</em>
									
								</div>
							</label>
						</div>
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin gốc: </em>
							<p class="card-text card-text-line-break">{{$item->content}}</p>
						</div>

						@if(isset($item->confirmed) && false)
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin nhận: </em>
							<p class="card-text card-text-line-break">{{$item->confirmed}}</p>
						</div>
						@endif

						@if(isset($item->transition) )
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin nhận: </em>
							<p class="card-text card-text-line-break">{!!$item->transition!!}</p>
							<em class="card-text"> Đặt cược: {{number_format($item->money)}}</em>
							<!-- <p class="card-text" style="padding-left:10px; font-weight:600; font-size:13px;">Đặt cược: {{number_format($item->money)}}</p> -->
						</div>
						@endif

						@if(isset($item->cancel) && $item->cancel != "")
						<div class="col-lg-12 line-break" style="text-align: left !important;" bis_skin_checked="1">
							<em class="title-sub-card">Tin hủy: </em>
							<p class="card-text card-text-line-break" style="text-decoration: line-through;">{{$item->cancel}}</p>
							<!-- <p class="card-text" style="font-weight:600 !important;">Đặt cược {{$item->money}}</p> -->
						</div>
						@endif
						
						@if(!isset($item->is_done) || $item->is_done == 0)
						<div class="col-lg-12 line-break" style="text-align: right !important;" bis_skin_checked="1">
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 30)) )
							@else
							<button type="button" id="btn_Bet" onclick="actionBet(this)" bet_id="{{$item->id}}" class="btn btn-primary btn-custom waves-effect waves-light" control-id="ControlID-1">Vào cược</button>
							<a class="btn btn-default btn-custom waves-effect waves-light" href="/games/need-confirm-bet?id={{$item->id}}" class="button">Sửa tin</a>
							@endif
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
							@else
								<button type="button" id="btn_Cancel" onclick="actionCancel(this)" bet_id="{{$item->id}}" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy cược</button>
							@endif
							<!-- <button type="button" id="btn_Cancel" onclick="confirmCancel(this)" bet_user_name="{{$item->user_name}}" bet_id_inday="{{$item->id_inday}}" bet_id="{{$item->id}}" onclick="" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy cược</button> -->
							<!-- <button type="button" id="btn_OK" onclick="" class="btn btn-primary btn-custom waves-effect waves-light" control-id="ControlID-1">Xử lý tin</button> -->
							<!-- <button type="button" id="btn_OK" onclick="" class="btn btn-default btn-custom waves-effect waves-light" control-id="ControlID-1">Sửa tin</button> -->
							<!-- <button type="button" id="btn_OK" onclick="" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy bỏ</button> -->
						</div>
						@endif

						@if(isset($item->is_done) && $item->is_done == 1)
						<div class="col-lg-12 line-break" style="text-align: right !important;" bis_skin_checked="1">
							<label class="title-sub-card">Đã vào cược thành công: {{number_format($item->money)}}</label>
							@if (intval(date('H')) >= 19 || (intval(date('H')) >= 18 && intval(date('i') >= 5)) )
							@else
							<button type="button" id="btn_Cancel" onclick="confirmCancel(this)" bet_user_name="{{$item->user_name}}" bet_id_inday="{{$item->id_inday}}" bet_id="{{$item->id}}" onclick="" class="btn btn-danger btn-custom waves-effect waves-light" control-id="ControlID-1">Hủy cược</button>
							@endif
						</div>
						@endif

						@if(isset($item->is_done) && $item->is_done == -1)
						<div class="col-lg-12 line-break" style="color:red;text-align: right !important;" bis_skin_checked="1">
							<label class="title-sub-card">Đã hủy cược thành công</label>
						</div>
						@endif

					</div>
					@endforeach
					<span class="col-lg-12">Tìm thấy <mark>{{count($bets)}}</mark> tin cược.</span>
				</div>
				<input type="hidden" id="user-id-delete">
				<input type="hidden" id="url" value="{{url('/users')}}">
				<input type="hidden" id="token" value="{{ csrf_token() }}">
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
	});

	function actionBet(e){
		$.ajax({
					url: "/games/save-confirm-bet",
					method: 'POST',
					dataType: 'json',
					data: {
						confirmTextBet: $("#confirmTextBet").val(),
						id: e.getAttribute("bet_id"),
						type: "bet" 
						// _token: $_token,
					},
					complete: function(data) {
						// console.log(data)
						dataResponse = JSON.parse(data.responseText)
						if (dataResponse[0] == 1)
							swal({
								title: "Thông báo",
								text: dataResponse[1],
								type: "success",
								timer: 10000,
								confirmButtonText: "Đã hiểu",
							}).then((confirm) => {
								location.reload()
							})
						else{
							// location.url = '/games/need-confirm?id=1'
							window.location.href = "/games/need-confirm-bet?id="+e.getAttribute("bet_id");
						}
					}
				});
	}

	function confirmCancel(e){
		$.ajax({
			url: "/games/estimate-cancel-bet",
			method: 'POST',
			dataType: 'json',
			data: {
				id: e.getAttribute("bet_id"),
				type: "cancel" 
				// _token: $_token,
			},
			complete: function(data) {
				dataResponse = JSON.parse(data.responseText)
				
				swal({
					title: "Thông báo",
					text: "Bạn muốn hủy cược Tin " + e.getAttribute("bet_id_inday") + " của " + e.getAttribute("bet_user_name") + "\n Phí hủy: " +  dataResponse.fee,
					type: "success",
					buttons: true,
					buttons: ["Quay về!", "Tiếp tục"],
				}).then((confirm) => {
					console.log(confirm)
					if(confirm){
						actionCancel(e)
					}
					// 
				})
			}
		});
	}
	function actionCancel(e){
		$.ajax({
			url: "/games/save-confirm-bet",
			method: 'POST',
			dataType: 'json',
			data: {
				id: e.getAttribute("bet_id"),
				type: "cancel" 
				// _token: $_token,
			},
			complete: function(data) {
				dataResponse = JSON.parse(data.responseText)
				swal({
					title: "Thông báo",
                    text: dataResponse[1],
					type: "success",
					timer: 10000,
					confirmButtonText: "Đã hiểu",
				}).then((confirm) => {
					location.reload()
				})
			}
		});
	}

</script>
@endsection

@section('js_call')
<script src="/assets/admin/js/user.js?v=1.000111"></script>
@endsection